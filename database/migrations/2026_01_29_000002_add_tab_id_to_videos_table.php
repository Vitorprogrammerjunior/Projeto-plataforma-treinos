<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adiciona relacionamento de vídeos com abas.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->foreignId('tab_id')->nullable()->after('user_id')->constrained('tabs')->nullOnDelete();
            
            $table->index('tab_id');
        });
    }

    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropForeign(['tab_id']);
            $table->dropColumn('tab_id');
        });
    }
};
