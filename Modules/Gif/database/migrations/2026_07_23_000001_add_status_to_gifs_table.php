<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add a publication status column to the gifs table.
     *
     * Statuses:
     *   pending_review — uploaded but awaiting AI moderation check
     *   approved       — cleared for public display
     *   flagged        — AI moderation flagged the content; admin must review
     *
     * All existing rows default to 'approved' so that already-live GIFs
     * are not suddenly hidden when the migration runs on a populated DB.
     */
    public function up(): void
    {
        Schema::table('gifs', function (Blueprint $table) {
            $table->enum('status', ['pending_review', 'approved', 'flagged'])
                  ->default('pending_review')
                  ->after('original_filename');
        });

        // Back-fill existing rows: they were already public, so approve them.
        \DB::table('gifs')->update(['status' => 'approved']);
    }

    public function down(): void
    {
        Schema::table('gifs', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
