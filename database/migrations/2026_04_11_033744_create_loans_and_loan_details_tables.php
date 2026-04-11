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
        Schema::dropIfExists('lending_details');
        Schema::dropIfExists('lendings');

        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('tanggal_pinjam');
            $table->enum('status', ['dipinjam', 'pending_konfirmasi', 'dikembalikan'])->default('dipinjam');
            $table->timestamps();
        });

        Schema::create('loan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('loans')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->integer('jumlah_pinjam');
            $table->string('kondisi_awal')->default('baik');
            $table->integer('jumlah_kembali_baik')->nullable();
            $table->integer('jumlah_kembali_rusak')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_details');
        Schema::dropIfExists('loans');

        // Restore the tables we dropped in UP
        Schema::create('lendings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('ket');
            $table->date('tanggal_pinjam')->nullable();
            $table->date('tanggal_kembali')->nullable();
            $table->boolean('is_returned')->default(false);
            $table->string('edited_by');
            $table->timestamps();
        });

        Schema::create('lending_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lending_id')->constrained('lendings')->onDelete('cascade');
            $table->string('item_name');
            $table->integer('total');
            $table->timestamps();
        });
    }
};
