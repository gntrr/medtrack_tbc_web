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
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id(); // This serves as id_jadwal_pengingat
            $table->foreignId('pasien_id')->constrained('pasien')->onDelete('cascade'); // id_pasien
            
            $table->enum('jenis', ['kontrol', 'obat'])->default('kontrol'); // kontrol: jadwal kontrol hingga 6 bulan, sesuai periode. obat: jadwal pengingat obat harian
            
            $table->dateTime('tanggal_waktu_pengingat');
            $table->enum('status', ['menunggu', 'terkirim', 'gagal']);

            // —— Khusus link konfirmasi minum obat harian ——
            $table->enum('status_konfirmasi', ['belum', 'sudah', 'terlambat'])->default('belum');
            $table->string('token_konfirmasi')->unique()->nullable();
            $table->dateTime('tgl_waktu_konfirmasi')->nullable();

            // —— Khusus jadwal kontrol ke puskesmas sesuai periode ——
            $table->enum('fase', ['intensif', 'lanjutan'])->nullable();
            $table->integer('periode')->nullable();   // minggu / dua-mingguan
            
            $table->timestamps();

            // index untuk performa
            $table->index('tanggal_waktu_pengingat');
            $table->index(['pasien_id','jenis']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
