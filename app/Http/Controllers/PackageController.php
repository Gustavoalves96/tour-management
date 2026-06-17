<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $destination = $request->input('destination');

        $packages = Package::query()
            // Busca por nome ou descrição (agrupada, pra não conflitar com o filtro)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'ilike', "%{$search}%")
                        ->orWhere('description', 'ilike', "%{$search}%");
                });
            })
            // Filtro por destino
            ->when($destination, fn ($query, $destination) => $query->where('destination', $destination))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        // Destinos distintos para o select do filtro
        $destinations = Package::query()
            ->whereNotNull('destination')
            ->distinct()
            ->orderBy('destination')
            ->pluck('destination');

        return view('packages.index', compact('packages', 'search', 'destination', 'destinations'));
    }

    public function store(Request $request)
    {
        Package::create($this->validateData($request));

        return redirect()->route('packages.index')
            ->with('status', 'Pacote cadastrado com sucesso.');
    }

    public function update(Request $request, Package $package)
    {
        $package->update($this->validateData($request));

        return redirect()->route('packages.index')
            ->with('status', 'Pacote atualizado com sucesso.');
    }

    public function destroy(Package $package)
    {
        $package->delete();

        return redirect()->route('packages.index')
            ->with('status', 'Pacote removido com sucesso.');
    }

    // Validação compartilhada entre store e update
    private function validateData(Request $request): array
    {
        // Normaliza o preço vindo com máscara (ex.: "1.234,56" -> "1234.56")
        $price = preg_replace('/[^\d,.]/', '', (string) $request->input('price'));
        $price = str_replace('.', '', $price);
        $price = str_replace(',', '.', $price);
        $request->merge(['price' => $price === '' ? null : $price]);

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'origin' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'max_people' => ['required', 'integer', 'min:1'],
        ]);
    }
}
