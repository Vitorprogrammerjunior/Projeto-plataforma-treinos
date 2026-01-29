<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meal_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meal_id')->constrained()->onDelete('cascade');
            $table->string('name'); // nome do alimento
            $table->string('quantity')->nullable(); // quantidade (ex: "100g", "1 unidade", "2 colheres")
            $table->integer('calories')->nullable();
            $table->integer('protein')->nullable(); // gramas
            $table->integer('carbs')->nullable(); // gramas
            $table->integer('fat')->nullable(); // gramas
            $table->string('image')->nullable(); // foto do alimento
            $table->text('notes')->nullable(); // observações (ex: "pode substituir por...")
            $table->text('alternatives')->nullable(); // alternativas de substituição
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meal_items');
    }
};
