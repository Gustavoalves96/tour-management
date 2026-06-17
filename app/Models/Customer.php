<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'cpf',
        'birth_date',
        'city',
        'notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    // Um cliente pode ter vários contratos
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }
}
