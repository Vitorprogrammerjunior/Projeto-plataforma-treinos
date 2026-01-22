<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration para vídeos de treino.
 * Suporta upload local ou link externo (Vimeo privado).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');                            // Título do vídeo
            $table->string('slug')->unique();                   // URL amigável
            $table->text('description')->nullable();            // Descrição
            $table->string('thumbnail')->nullable();            // Imagem de capa
            $table->string('video_path')->nullable();           // Caminho local do vídeo
            $table->string('video_url')->nullable();            // URL externa (Vimeo)
            $table->enum('video_source', ['local', 'external'])->default('local');
            $table->integer('duration_seconds')->nullable();    // Duração em segundos
            $table->string('category')->nullable();             // Categoria do treino
            $table->integer('order')->default(0);               // Ordem de exibição
            $table->boolean('is_active')->default(true);        // Se está disponível
            $table->boolean('is_free')->default(false);         // Se é conteúdo grátis (preview)
            $table->unsignedInteger('views_count')->default(0); // Contador de views
            $table->timestamps();
            
            $table->index(['is_active', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
