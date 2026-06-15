<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DriverController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $drivers = Driver::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'ilike', "%{$search}%")
                        ->orWhere('email', 'ilike', "%{$search}%")
                        ->orWhere('cpf', 'ilike', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('drivers.index', compact('drivers', 'search'));
    }

    public function create(): View
    {
        return view('drivers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        unset($data['profile_photo']); // tratamos o arquivo separadamente

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')->store('drivers', 'public');
        }

        Driver::create($data);

        return redirect()->route('drivers.index')->with('status', 'Motorista cadastrado com sucesso!');
    }

    public function edit(Driver $driver): View
    {
        return view('drivers.edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver): RedirectResponse
    {
        $data = $this->validateData($request, $driver);
        unset($data['profile_photo']);

        if ($request->hasFile('profile_photo')) {
            // apaga a foto antiga antes de salvar a nova
            if ($driver->profile_photo) {
                Storage::disk('public')->delete($driver->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')->store('drivers', 'public');
        }

        $driver->update($data);

        return redirect()->route('drivers.index')->with('status', 'Motorista atualizado com sucesso!');
    }

    public function updatePhoto(Request $request, Driver $driver)
    {
        $request->validate([
            'profile_photo' => ['required', 'image', 'max:2048'],
        ]);

        // remove a foto antiga, se houver
        if ($driver->profile_photo) {
            Storage::disk('public')->delete($driver->profile_photo);
        }

        $driver->update([
            'profile_photo' => $request->file('profile_photo')->store('drivers', 'public'),
        ]);

        return back()
            ->with('status', 'Foto atualizada com sucesso.')
            ->with('reopen_driver', $driver->id); // reabre o drawer no motorista
    }

    public function destroy(Driver $driver): RedirectResponse
    {
        if ($driver->profile_photo) {
            Storage::disk('public')->delete($driver->profile_photo);
        }
        $driver->delete();

        return redirect()->route('drivers.index')->with('status', 'Motorista removido com sucesso!');
    }

    private function validateData(Request $request, ?Driver $driver = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'registration_number' => ['required', 'string', 'max:50', Rule::unique('drivers')->ignore($driver?->id)],
            'cpf' => ['required', 'string', 'max:14', Rule::unique('drivers')->ignore($driver?->id)],
            'rg' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', Rule::unique('drivers')->ignore($driver?->id)],
            'phone' => ['required', 'string', 'max:20'],
            'profile_photo' => ['nullable', 'image', 'max:2048'], // até 2MB
            'postal_code' => ['required', 'string', 'max:9'],
            'street' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:20'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:2'],
        ]);
    }
}
