<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * AI metadata for each GIF:
     *   - suggested_title / suggested_tags  → Auto-Tagging & Captioning
     *   - embedding (JSON float[])           → Semantic Search + Duplicate Detection
     *   - moderation_status                 → Content Moderation gate
     *
     * The table is created by the Ai module and references the gifs table.
     * If the Ai module is removed, this table simply disappears; the gifs
     * table is unaffected (no constraint on that side).
     */
    public function up(): void
    {
        Schema::create('gif_ai_metadata', function (Blueprint $table) {
            $table->id();

            $table->foreignId('gif_id')
                  ->unique()                // one metadata row per GIF
                  ->constrained('gifs')
                  ->cascadeOnDelete();

            // --- Auto-Tagging ---
            $table->string('suggested_title')->nullable();
            $table->json('suggested_tags')->nullable();   // string[]
            $table->text('description')->nullable();      // one-sentence description

            // --- Embeddings (stored as JSON float array for SQLite compat) ---
            // On PostgreSQL this could be upgraded to a pgvector column later.
            $table->json('embedding')->nullable();

            // --- Content Moderation ---
            $table->enum('moderation_status', ['pending', 'approved', 'flagged'])
                  ->default('pending');
            $table->string('moderation_reason')->nullable();

            // --- Processing state ---
            $table->timestamp('analyzed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gif_ai_metadata');
    }
};
