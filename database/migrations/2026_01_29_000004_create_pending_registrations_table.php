<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabela para armazenar registros pendentes de pagamento.
 * Usuário só é criado após pagamento aprovado.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pending_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');                      // Hash da senha
            $table->string('phone')->nullable();
            $table->foreignId('plan_id')->constrained('plans');
            $table->string('payment_id')->nullable();        // ID do pagamento no MP
            $table->string('preference_id')->nullable();     // ID da preference no MP
            $table->enum('status', ['pending', 'paid', 'expired', 'cancelled'])->default('pending');
            $table->timestamp('expires_at')->nullable();     // Expiração do link de pagamento
            $table->timestamps();
            
            $table->index(['email', 'status']);
            $table->index('preference_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pending_registrations');
    }
};
