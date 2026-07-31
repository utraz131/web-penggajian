<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyAbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pegawais = \App\Models\Pegawai::all();
        $year = date('Y');
        
        // Buat absensi untuk bulan Juli, Agustus, September (bulan 7, 8, 9)
        $months = [7, 8, 9];
        
        foreach ($pegawais as $pegawai) {
            foreach ($months as $month) {
                // Asumsi 25 hari kerja tiap bulan
                for ($day = 1; $day <= 25; $day++) {
                    $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
                    
                    // Skip weekend (Sabtu & Minggu)
                    $dayOfWeek = date('N', strtotime($date));
                    if ($dayOfWeek >= 6) {
                        continue;
                    }

                    // Random sedikit karyawan terlambat agar lebih natural (10% peluang)
                    $isLate = rand(1, 100) <= 10;
                    $waktuMasuk = $isLate ? '08:' . rand(10, 59) . ':00' : '07:' . rand(30, 59) . ':00';
                    $status = $isLate ? 'Terlambat' : 'Hadir';
                    $waktuKeluar = '17:' . rand(00, 30) . ':00';

                    // Insert jika belum ada
                    \App\Models\Absensi::firstOrCreate(
                        [
                            'pegawai_id' => $pegawai->id,
                            'tanggal' => $date,
                        ],
                        [
                            'waktu_masuk' => $waktuMasuk,
                            'waktu_keluar' => $waktuKeluar,
                            'foto_masuk' => 'dummy.jpg',
                            'foto_keluar' => 'dummy.jpg',
                            'status' => $status
                        ]
                    );
                }
            }
        }
    }
}
