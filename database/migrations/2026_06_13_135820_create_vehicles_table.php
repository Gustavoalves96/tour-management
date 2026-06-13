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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('prefix');                  // Prefixo
            $table->string('identification_name');     // Nome de identificação
            $table->string('plate')->unique();         // Placa
            $table->string('model');                   // Modelo
            $table->string('chassis');                 // Chassi
            $table->unsignedSmallInteger('capacity');  // Capacidade
            $table->string('type');                    // Tipo (Ônibus, Van)
            $table->string('seat_type')->nullable();   // Bancada (Semi-Leito)
            $table->unsignedSmallInteger('year');      // Ano
            $table->boolean('has_internet')->default(false);
            $table->boolean('has_wc')->default(false);
            $table->boolean('has_power_outlet')->default(false);    // Tomada
            $table->boolean('has_air_conditioning')->default(false);
            $table->boolean('has_fridge')->default(false);          // Geladeira
            $table->boolean('has_heating')->default(false);         // Calefação
            $table->boolean('has_video')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
