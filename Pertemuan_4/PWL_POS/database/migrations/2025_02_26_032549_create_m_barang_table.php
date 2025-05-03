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
        Schema::create('stok', function (Blueprint $table) {
            $table->unsignedBigInteger('barang_id'); // foreign key
            $table->string('barang_nama', 100); // kolom nama barang yang disalin
            $table->integer('jumlah_stok');
            $table->timestamps();

            // foreign key constraint
            $table->foreign('barang_id')->references('barang_id')->on('m_barang')->onDelete('cascade');

            // menambahkan kolom nama_barang dari tabel m_barang
            $table->string('barang_nama', 100)->nullable(); // menambahkan nama barang
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_barang');
    }
};
