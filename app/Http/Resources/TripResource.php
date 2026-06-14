<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'rule' => $this->rule,
            'origin' => $this->origin,
            'destination' => $this->destination,
            'date' => $this->date?->format('Y-m-d'),
            'departure_time' => substr($this->departure_time, 0, 5),
            'arrival_time' => $this->arrival_time ? substr($this->arrival_time, 0, 5) : null,
            'single_ticket_price' => $this->single_ticket_price,
            'passengers' => $this->passengers,
            'status' => $this->status,
            // Relacionamentos (incluídos só quando carregados)
            'vehicle' => $this->whenLoaded('vehicle', fn () => [
                'id' => $this->vehicle->id,
                'prefix' => $this->vehicle->prefix,
                'model' => $this->vehicle->model,
                'plate' => $this->vehicle->plate,
            ]),
            'driver' => $this->whenLoaded('driver', fn () => [
                'id' => $this->driver->id,
                'name' => $this->driver->name,
            ]),
        ];
    }
}
