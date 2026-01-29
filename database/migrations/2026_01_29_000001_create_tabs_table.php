<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration para tabela de abas.
 * Abas organizam os vídeos em categorias/seções definidas pelo admin.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tabs', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // Nome da aba (ex: "Treinos Iniciantes")
            $table->string('slug')->unique();                // URL amigável
            $table->text('description')->nullable();         // Descrição da aba
            $table->string('icon')->nullable();              // Ícone (nome do ícone ou emoji)
            $table->integer('order')->default(0);            // Ordem de exibição
            $table->boolean('is_active')->default(true);     // Se está visível
            $table->timestamps();
            
            $table->index(['is_active', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tabs');
    }
};
