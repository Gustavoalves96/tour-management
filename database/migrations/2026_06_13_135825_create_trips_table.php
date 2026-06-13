<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('name');                                    // Nome da viagem
            $table->string('rule');                                    // Regra (Turismo, Faculdade)
            $table->string('origin');                                  // Origem
            $table->string('destination');                             // Destino
            $table->date('date');                                      // Data
            $table->time('departure_time');                            // Horário de saída
            $table->time('arrival_time')->nullable();                  // Horário de chegada (RF01)
            $table->decimal('single_ticket_price', 10, 2)->nullable(); // Valor da passagem avulsa
            $table->unsignedSmallInteger('passengers')->nullable();    // Número de passageiros
            $table->string('status')->default('in_progress');         // in_progress, completed, cancelled
            $table->foreignId('vehicle_id')->constrained();            // veículo da viagem
            $table->foreignId('driver_id')->constrained();             // motorista da viagem
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
