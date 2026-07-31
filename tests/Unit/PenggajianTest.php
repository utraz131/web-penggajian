<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class PenggajianTest extends TestCase
{
    /**
     * A basic unit test to verify payroll calculation algorithm (Serkom Unit 4 & 9).
     *
     * @return void
     */
    public function test_perhitungan_gaji_bersih()
    {
        // Simulasi input dari database (Gaji Pokok, Tunjangan, Potongan)
        $gaji_pokok = 5000000;
        $tunjangan = 1000000;
        
        // Simulasi Absensi
        $jumlah_hadir = 18; // Kurang 2 hari dari standar 20 hari kerja
        $jumlah_telat = 2;
        $jumlah_cuti_izin = 0;
        
        // Algoritma perhitungan (Unit 4)
        $hari_kurang = max(0, 20 - ($jumlah_hadir + $jumlah_cuti_izin));
        $potongan_absen = ($hari_kurang * 100000) + ($jumlah_telat * 25000);
        
        // 2 hari kurang = 200,000. 2 hari telat = 50,000. Total potongan absen = 250,000.
        $this->assertEquals(250000, $potongan_absen);

        $potongan_bpjs_tk = $gaji_pokok * 0.02; // 100,000
        $potongan_bpjs_kes = $gaji_pokok * 0.01; // 50,000
        $potongan_bpjs = $potongan_bpjs_tk + $potongan_bpjs_kes; // 150,000
        
        $this->assertEquals(150000, $potongan_bpjs);

        $total_pendapatan = $gaji_pokok + $tunjangan; // 6,000,000
        $potongan_pajak = $total_pendapatan > 5000000 ? ($total_pendapatan * 0.05) : 0; // 5% dari 6 juta = 300,000
        
        $this->assertEquals(300000, $potongan_pajak);

        $total_gaji = max(0, $total_pendapatan - ($potongan_absen + $potongan_bpjs + $potongan_pajak));
        // 6,000,000 - (250,000 + 150,000 + 300,000) = 5,300,000
        
        $this->assertEquals(5300000, $total_gaji);
    }
}
