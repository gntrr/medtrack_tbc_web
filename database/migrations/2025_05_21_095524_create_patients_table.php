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
        Schema::create('pasien', function (Blueprint $table) {
            $table->id(); // This serves as id_pasien
            $table->string('nama');
            $table->text('alamat');
            $table->text('nomor_telepon'); // Will be encrypted
            $table->date('jadwal_pengobatan'); // Sesuai ERD
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasien');
    }
};
