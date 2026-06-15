<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VehicleController extends Controller
{
    // Opcionais (checkboxes) reaproveitados na validação
    private array $amenities = [
        'has_internet', 'has_wc', 'has_power_outlet', 'has_air_conditioning',
        'has_fridge', 'has_heating', 'has_video',
    ];

    public function index(Request $request): View
    {
        $search = $request->input('search');
        $seatType = $request->input('seat_type');

        $vehicles = Vehicle::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('prefix', 'ilike', "%{$search}%")
                        ->orWhere('plate', 'ilike', "%{$search}%")
                        ->orWhere('model', 'ilike', "%{$search}%");
                });
            })
            ->when($seatType, fn ($query, $seatType) => $query->where('seat_type', $seatType))
            ->orderBy('prefix')
            ->paginate(10)
            ->withQueryString();

        return view('vehicles.index', compact('vehicles', 'search', 'seatType'));
    }

    public function create(): View
    {
        return view('vehicles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Vehicle::create($this->validateData($request));

        return redirect()->route('vehicles.index')->with('status', 'Veículo cadastrado com sucesso!');
    }

    public function edit(Vehicle $vehicle): View
    {
        return view('vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $vehicle->update($this->validateData($request, $vehicle));

        return redirect()->route('vehicles.index')->with('status', 'Veículo atualizado com sucesso!');
    }

    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        $vehicle->delete();

        return redirect()->route('vehicles.index')->with('status', 'Veículo removido com sucesso!');
    }

    // Validação compartilhada entre criar e editar
    private function validateData(Request $request, ?Vehicle $vehicle = null): array
    {
        // Placa sempre em maiúsculo (antes de validar, pra checagem de duplicidade já considerar maiúsculo)
        $request->merge(['plate' => strtoupper((string) $request->input('plate'))]);

        $data = $request->validate([
            'prefix' => ['required', 'string', 'max:50'],
            'identification_name' => ['required', 'string', 'max:255'],
            'plate' => ['required', 'string', 'max:10', Rule::unique('vehicles')->ignore($vehicle?->id)],
            'model' => ['required', 'string', 'max:255'],
            'chassis' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:1'],
            'type' => ['required', 'string', 'max:100'],
            'seat_type' => ['nullable', 'string', 'max:100'],
            'year' => ['required', 'integer', 'min:1950', 'max:' . (date('Y') + 1)],
        ]);

        // Checkbox ausente = false; presente = true
        foreach ($this->amenities as $amenity) {
            $data[$amenity] = $request->boolean($amenity);
        }

        return $data;
    }
}
