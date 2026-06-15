<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Trip;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TripController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $rule = $request->input('rule');

        $trips = Trip::query()
            ->with(['vehicle', 'driver'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'ilike', "%{$search}%")
                        ->orWhere('origin', 'ilike', "%{$search}%")
                        ->orWhere('destination', 'ilike', "%{$search}%");
                });
            })
            ->when($status, fn ($query, $status) => $query->where('status', $status))
            ->when($rule, fn ($query, $rule) => $query->where('rule', $rule))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('trips.index', compact('trips', 'search', 'status', 'rule'));
    }

    public function create(): View
    {
        return view('trips.create', [
            'vehicles' => Vehicle::orderBy('prefix')->get(),
            'drivers' => Driver::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Trip::create($this->validateData($request));

        return redirect()->route('trips.index')->with('status', 'Viagem cadastrada com sucesso!');
    }

    public function edit(Trip $trip): View
    {
        return view('trips.edit', [
            'trip' => $trip,
            'vehicles' => Vehicle::orderBy('prefix')->get(),
            'drivers' => Driver::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Trip $trip): RedirectResponse
    {
        $trip->update($this->validateData($request));

        return redirect()->route('trips.index')->with('status', 'Viagem atualizada com sucesso!');
    }

    public function destroy(Trip $trip): RedirectResponse
    {
        $trip->delete();

        return redirect()->route('trips.index')->with('status', 'Viagem removida com sucesso!');
    }

    private function validateData(Request $request): array
    {
        // Normaliza o valor da passagem: "R$ 1.234,56" -> "1234.56"
        $price = $request->input('single_ticket_price');
        if ($price !== null && $price !== '') {
            $price = str_replace('.', '', $price);         // tira separador de milhar
            $price = str_replace(',', '.', $price);         // vírgula decimal -> ponto
            $price = preg_replace('/[^\d.]/', '', $price);   // tira "R$", espaços etc
        }
        $request->merge(['single_ticket_price' => $price === '' ? null : $price]);

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'rule' => ['required', Rule::in(Trip::RULES)],
            'origin' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'departure_time' => ['required', 'date_format:H:i'],
            'arrival_time' => ['nullable', 'date_format:H:i'],
            'single_ticket_price' => ['nullable', 'numeric', 'min:0'],
            'passengers' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', Rule::in(array_keys(Trip::STATUSES))],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'driver_id' => ['required', 'exists:drivers,id'],
        ]);
    }
}
