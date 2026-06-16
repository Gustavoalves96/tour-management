<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
