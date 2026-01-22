<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration para assinaturas dos usuários.
 * Relaciona usuário -> plano comprado com datas de validade.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->string('payment_id')->nullable();           // ID do pagamento (Stripe/MP)
            $table->string('payment_status')->default('pending'); // pending, approved, failed
            $table->decimal('amount_paid', 10, 2);              // Valor pago
            $table->timestamp('starts_at')->nullable();         // Início do acesso
            $table->timestamp('expires_at')->nullable();        // Fim do acesso
            $table->timestamps();
            
            $table->index(['user_id', 'expires_at']);           // Otimização para consultas
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
