<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TripResource;
use App\Models\Trip;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TripController extends Controller
{
    // GET /api/trips — lista todas as viagens em JSON
    public function index(): AnonymousResourceCollection
    {
        $trips = Trip::with(['vehicle', 'driver'])->get();

        return TripResource::collection($trips);
    }
}
