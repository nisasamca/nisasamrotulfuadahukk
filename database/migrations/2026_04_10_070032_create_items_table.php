<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->foreignId('kategori_id')->constrained('categories')->onDelete('cascade');
        $table->enum('kondisi', ['baik', 'rusak']);
        $table->string('lokasi');
        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};