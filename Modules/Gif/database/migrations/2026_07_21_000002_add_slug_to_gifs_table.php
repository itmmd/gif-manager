<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gifs', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
        });

        \DB::table('gifs')->orderBy('id')->each(function ($row) {
            \DB::table('gifs')->where('id', $row->id)->update([
                'slug' => Str::slug($row->title) . '-' . substr(md5($row->id), 0, 6),
            ]);
        });

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
