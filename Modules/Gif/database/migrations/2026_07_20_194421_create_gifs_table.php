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
            $table->string('file_path');           // gifs/{uuid}.gif on public disk
            $table->unsignedBigInteger('file_size'); // bytes
            $table->string('mime_type', 50);       // image/gif
            $table->string('original_filename');   // original name for display
            $table->foreignId('uploaded_by')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gifs');
    }
};
