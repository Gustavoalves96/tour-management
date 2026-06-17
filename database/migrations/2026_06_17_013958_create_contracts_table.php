<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();

            // Relações: cada contrato pertence a um cliente e a um pacote
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete(); // se pacotes não for "packages", use ->constrained('nome_da_tabela')

            $table->unsignedInteger('number_of_people');   // nº de pessoas
            $table->decimal('total_value', 10, 2);          // valor total
            $table->string('status')->default('pending');   // pending|confirmed|in_progress|completed|cancelled
            $table->date('start_date');                      // data de início
            $table->date('end_date')->nullable();            // data de término (pode vir da duração do pacote)
            $table->text('notes')->nullable();               // observações (opcional)

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
