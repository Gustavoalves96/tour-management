<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $fillable = [
        'name',
        'description',
        'origin',
        'destination',
        'price',
        'duration_days',
        'max_people',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_days' => 'integer',
        'max_people' => 'integer',
    ];

    // Um pacote pode ter vários contratos
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }
}
