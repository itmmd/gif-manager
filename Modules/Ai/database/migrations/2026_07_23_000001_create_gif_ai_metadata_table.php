<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gif_ai_metadata', function (Blueprint $table) {
            $table->id();

            $table->foreignId('gif_id')
                  ->unique()
                  ->constrained('gifs')
                  ->cascadeOnDelete();

            $table->string('suggested_title')->nullable();
            $table->json('suggested_tags')->nullable();
            $table->text('description')->nullable();
            $table->json('embedding')->nullable();

            $table->enum('moderation_status', ['pending', 'approved', 'flagged'])
                  ->default('pending');
            $table->string('moderation_reason')->nullable();
            $table->timestamp('analyzed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gif_ai_metadata');
    }
};
