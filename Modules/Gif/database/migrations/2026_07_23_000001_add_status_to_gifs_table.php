<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gifs', function (Blueprint $table) {
            $table->enum('status', ['pending_review', 'approved', 'flagged'])
                  ->default('pending_review')
                  ->after('original_filename');
        });

        \DB::table('gifs')->update(['status' => 'approved']);
    }

    public function down(): void
    {
        Schema::table('gifs', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
