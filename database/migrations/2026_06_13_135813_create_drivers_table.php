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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('name');                           // Nome completo
            $table->date('birth_date');                       // Data de nascimento
            $table->string('registration_number')->unique();  // Matrícula
            $table->string('cpf', 14)->unique();              // CPF
            $table->string('rg', 20)->nullable();             // RG
            $table->string('email')->unique();
            $table->string('phone');                          // Telefone
            $table->string('profile_photo')->nullable();      // caminho da foto
            $table->string('postal_code', 9);                 // CEP
            $table->string('street');                         // Logradouro
            $table->string('number', 20);                     // Número
            $table->string('city');                           // Cidade
            $table->string('state', 2);                       // Estado (UF)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
