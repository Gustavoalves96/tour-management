<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    protected $fillable = [
        'customer_id',
        'package_id',
        'number_of_people',
        'total_value',
        'status',
        'start_date',
        'end_date',
        'notes',
    ];

    protected $casts = [
        'start_date'       => 'date',
        'end_date'         => 'date',
        'total_value'      => 'decimal:2',
        'number_of_people' => 'integer',
    ];

    // Mapa de status: chave em inglês (banco) => rótulo em português (tela)
    public const STATUSES = [
        'pending'     => 'Pendente',
        'confirmed'   => 'Confirmado',
        'in_progress' => 'Em andamento',
        'completed'   => 'Concluído',
        'cancelled'   => 'Cancelado',
    ];

    // Um contrato pertence a um cliente
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    // Um contrato pertence a um pacote
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
