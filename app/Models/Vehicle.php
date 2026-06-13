<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'prefix', 'identification_name', 'plate', 'model', 'chassis',
        'capacity', 'type', 'seat_type', 'year',
        'has_internet', 'has_wc', 'has_power_outlet', 'has_air_conditioning',
        'has_fridge', 'has_heating', 'has_video',
    ];

    protected function casts(): array
    {
        return [
            'has_internet' => 'boolean',
            'has_wc' => 'boolean',
            'has_power_outlet' => 'boolean',
            'has_air_conditioning' => 'boolean',
            'has_fridge' => 'boolean',
            'has_heating' => 'boolean',
            'has_video' => 'boolean',
        ];
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
}
