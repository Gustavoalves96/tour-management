<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Customer;
use App\Models\Package;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    // Lista contratos com busca (nome do cliente) e filtro por status
    public function index(Request $request)
    {
        $contracts = Contract::query()
            ->with(['customer', 'package']) // evita N+1 ao exibir cliente/pacote
            ->when($request->search, function ($query, $search) {
                // busca pelo nome do cliente relacionado
                $query->whereHas('customer', function ($q) use ($search) {
                    $q->where('name', 'ilike', "%{$search}%"); // ilike = case-insensitive no Postgres
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // listas para os selects do formulário (drawer)
        $customers = Customer::orderBy('name')->get(['id', 'name']);
        $packages  = Package::orderBy('name')->get(['id', 'name', 'price', 'duration_days']);

        return view('contracts.index', compact('contracts', 'customers', 'packages'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        Contract::create($data);

        return back()->with('success', 'Contrato criado com sucesso.');
    }

    public function update(Request $request, Contract $contract)
    {
        $data = $this->validateData($request);
        $contract->update($data);

        return back()->with('success', 'Contrato atualizado com sucesso.');
    }

    public function destroy(Contract $contract)
    {
        $contract->delete();

        return back()->with('success', 'Contrato excluído com sucesso.');
    }

    // Validação compartilhada entre store e update
    private function validateData(Request $request): array
    {
        // normaliza o valor mascarado "1.234,56" -> "1234.56" antes de validar
        $request->merge([
            'total_value' => $this->normalizeMoney($request->total_value),
        ]);

        return $request->validate([
            'customer_id'      => ['required', 'exists:customers,id'],
            'package_id'       => ['required', 'exists:packages,id'], // ajuste o nome da tabela se necessário
            'number_of_people' => ['required', 'integer', 'min:1'],
            'total_value'      => ['required', 'numeric', 'min:0'],
            'status'           => ['required', 'in:' . implode(',', array_keys(Contract::STATUSES))],
            'start_date'       => ['required', 'date'],
            'end_date'         => ['nullable', 'date', 'after_or_equal:start_date'],
            'notes'            => ['nullable', 'string', 'max:1000'],
        ]);
    }

    // "1.234,56" -> "1234.56"
    private function normalizeMoney(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return str_replace(',', '.', str_replace('.', '', $value));
    }
}
