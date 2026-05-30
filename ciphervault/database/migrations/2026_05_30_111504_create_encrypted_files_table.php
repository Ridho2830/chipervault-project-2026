<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('encrypted_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('original_name');
            $table->string('encrypted_name');
            $table->bigInteger('file_size');
            $table->string('mime_type', 100);
            $table->longText('ciphertext'); // Use longText for base64 storage, conceptually LONGBLOB could be used as well but Laravel longText handles very large strings perfectly.
            $table->string('iv');
            $table->string('salt');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encrypted_files');
    }
};
