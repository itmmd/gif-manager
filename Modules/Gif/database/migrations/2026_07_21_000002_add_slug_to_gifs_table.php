<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Add a URL-friendly slug column to the gifs table.
     *
     * Format: {slugified-title}-{6-char random hex}
     * Example: "funny-cat-reaction-a3f9b2"
     *
     * The random suffix guarantees uniqueness even when two GIFs share
     * the same title, without a slow uniqueness-loop query on insert.
     *
     * Existing rows get a slug generated from their current title + id
     * so the migration is safe to run on a populated database.
     */
    public function up(): void
    {
        Schema::table('gifs', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
        });

        // Back-fill existing rows.
        \DB::table('gifs')->orderBy('id')->each(function ($row) {
            \DB::table('gifs')->where('id', $row->id)->update([
                'slug' => Str::slug($row->title) . '-' . substr(md5($row->id), 0, 6),
            ]);
        });

        // Now that every row has a value, tighten the constraint.
        Schema::table('gifs', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('gifs', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
