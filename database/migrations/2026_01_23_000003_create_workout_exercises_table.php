<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workout_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workout_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->integer('sets')->nullable(); // séries
            $table->string('reps')->nullable(); // repetições (pode ser "12-15" ou "até falha")
            $table->string('rest')->nullable(); // descanso (ex: "60s", "1min")
            $table->string('weight')->nullable(); // peso sugerido
            $table->text('notes')->nullable(); // observações
            $table->string('video_url')->nullable(); // link de vídeo demonstrativo
            $table->string('image')->nullable(); // imagem do exercício
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workout_exercises');
    }
};
