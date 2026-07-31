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
        Schema::create('penggajians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pegawai_id');
            $table->string('bulan_tahun');
            $table->integer('jumlah_hadir')->default(20);
            $table->bigInteger('gaji_pokok');
            $table->bigInteger('tunjangan')->default(0);
            $table->bigInteger('potongan_absen')->default(0);
            $table->bigInteger('potongan_bpjs')->default(0);
            $table->bigInteger('potongan_pajak')->default(0);
            $table->bigInteger('total_gaji');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggajians');
    }
};
