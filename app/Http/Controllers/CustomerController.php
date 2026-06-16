<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $city = $request->input('city');

        $customers = Customer::query()
            // Busca por nome, e-mail ou CPF (agrupada, pra não conflitar com o filtro)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'ilike', "%{$search}%")
                        ->orWhere('email', 'ilike', "%{$search}%")
                        ->orWhere('cpf', 'ilike', "%{$search}%");
                });
            })
            // Filtro por cidade
            ->when($city, fn ($query, $city) => $query->where('city', $city))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        // Cidades distintas para alimentar o select do filtro
        $cities = Customer::query()
            ->whereNotNull('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        return view('customers.index', compact('customers', 'search', 'city', 'cities'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        Customer::create($data);

        return redirect()->route('customers.index')
            ->with('status', 'Cliente cadastrado com sucesso.');
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $this->validateData($request, $customer);
        $customer->update($data);

        return redirect()->route('customers.index')
            ->with('status', 'Cliente atualizado com sucesso.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('status', 'Cliente removido com sucesso.');
    }

    // Validação compartilhada entre store e update
    private function validateData(Request $request, ?Customer $customer = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'cpf' => ['required', 'string', 'max:14', Rule::unique('customers', 'cpf')->ignore($customer)],
            'birth_date' => ['required', 'date'],
            'city' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);
    }
}
