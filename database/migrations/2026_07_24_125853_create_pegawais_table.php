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
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->unique(); // Ini kolom yang tadi dicari tapi nggak ketemu
            $table->string('nama');
            $table->string('departemen')->default('General');
            $table->string('jabatan');
            $table->string('status')->default('Aktif'); // Aktif / Non-Aktif
            $table->integer('gaji_pokok');
            $table->integer('tunjangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
