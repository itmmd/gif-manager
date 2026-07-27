<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gifs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('file_path');
            $table->unsignedBigInteger('file_size');
            $table->string('mime_type', 50);
            $table->string('original_filename');
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gifs');
    }
};
