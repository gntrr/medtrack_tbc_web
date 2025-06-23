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
        Schema::create('riwayat', function (Blueprint $table) {
            $table->id(); // This serves as id_histori_pengingat
            $table->foreignId('jadwal_id')->constrained('jadwal')->onDelete('cascade'); // id_jadwal_pengingat
            $table->dateTime('waktu_pengiriman');
            $table->enum('status', ['terkirim', 'gagal']);
            $table->text('pesan')->nullable(); // Message content that was sent
            $table->text('respons')->nullable(); // Response from the WhatsApp API
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat');
    }
};
