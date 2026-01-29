<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->enum('video_type', ['youtube', 'upload'])->default('youtube')->after('url');
            $table->string('file_path')->nullable()->after('video_type');
            $table->text('instructions')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'video_type', 'file_path', 'instructions']);
        });
    }
};
