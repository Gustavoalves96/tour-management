<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'rule', 'origin', 'destination', 'date',
        'departure_time', 'arrival_time', 'single_ticket_price',
        'passengers', 'status', 'vehicle_id', 'driver_id',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'single_ticket_price' => 'decimal:2',
        ];
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
