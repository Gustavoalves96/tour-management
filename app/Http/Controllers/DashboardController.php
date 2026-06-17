<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Package;
use App\Models\Trip;
use App\Models\Vehicle;
use Illuminate\View\View;

class DashboardController extends Controller
{
    // Agrega os totais dos modulos para o dashboard (nao cria tabela nova)
    public function index(): View
    {
        $stats = [
            'trips_in_progress' => Trip::where('status', 'in_progress')->count(),
            'trips_completed'   => Trip::where('status', 'completed')->count(),
            'trips_cancelled'   => Trip::where('status', 'cancelled')->count(),
            'drivers'           => Driver::count(),
            'vehicles'          => Vehicle::count(),
            'customers'         => Customer::count(),
            'packages'          => Package::count(),
            'contracts'         => Contract::count(),
            'contracts_total'   => Contract::sum('total_value'),
        ];

        return view('dashboard', compact('stats'));
    }
}
