<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'birth_date', 'registration_number', 'cpf', 'rg',
        'email', 'phone', 'profile_photo',
        'postal_code', 'street', 'number', 'city', 'state',
    ];

    protected function casts(): array
    {
        return ['birth_date' => 'date'];
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
}
