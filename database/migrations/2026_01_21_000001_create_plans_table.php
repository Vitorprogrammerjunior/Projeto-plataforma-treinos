<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration para tabela de planos de assinatura.
 * Estrutura simples: um plano ativo = acesso aos vídeos.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // Nome do plano
            $table->string('slug')->unique();                // URL amigável
            $table->text('description')->nullable();         // Descrição para landing
            $table->decimal('price', 10, 2);                 // Preço em BRL
            $table->integer('duration_days')->default(30);   // Duração do acesso
            $table->json('features')->nullable();            // Lista de benefícios
            $table->boolean('is_active')->default(true);     // Se está disponível
            $table->boolean('is_featured')->default(false);  // Destaque na landing
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
