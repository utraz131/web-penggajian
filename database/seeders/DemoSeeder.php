<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pegawai;
use App\Models\User;
use App\Models\Absensi;
use App\Models\Penggajian;
use App\Models\IzinCuti;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run()
    {
        // Tidak pakai seed global, kita set seed per pegawai di bawah

        // Bersihkan data riwayat LAMA saja (agar absen hari ini tidak terhapus)
        // Kita hanya menghapus riwayat gaji, cuti, dan absen yang tanggalnya sebelum hari ini
        $today = Carbon::today()->toDateString();
        Absensi::where('tanggal', '<', $today)->delete();
        Penggajian::truncate(); // Gaji selalu bulanan ke belakang, jadi aman di-truncate
        IzinCuti::where('tanggal_mulai', '<', $today)->delete();

        // 1. Buat Pegawai Tambahan (Ahmad, Budi, dll) agar data ramai
        $departemen = ['IT', 'HRD', 'Keuangan', 'Operasional', 'Pemasaran'];
        $jabatans = ['Manager', 'Supervisor', 'Staff', 'Asisten', 'Koordinator'];
        $names = ['Ahmad', 'Budi', 'Citra', 'Dewi', 'Eko', 'Fajar', 'Gita', 'Hadi', 'Intan', 'Joko'];
        
        for ($i = 0; $i < 10; $i++) {
            // Cek apakah sudah ada biar gak duplikat kalo di-run berkali-kali
            $nip = 'DMO' . str_pad($i + 1, 3, '0', STR_PAD_LEFT);
            if (!Pegawai::where('nip', $nip)->exists()) {
                $gaji = rand(4, 15) * 1000000;
                Pegawai::create([
                    'nip' => $nip,
                    'nama' => $names[$i], // Tanpa kata Dummy
                    'departemen' => $departemen[array_rand($departemen)],
                    'jabatan' => $jabatans[array_rand($jabatans)],
                    'status' => 'Aktif',
                    'gaji_pokok' => $gaji,
                    'tunjangan' => $gaji * 0.1,
                    'created_at' => Carbon::now()->subMonths(4),
                ]);
            }
        }

        // 2. Ambil Semua Pegawai yang sudah ada di Database (termasuk Bayu, Rexy, dan yg baru dibuat di atas)
        $pegawais = Pegawai::all();

        // 2. Generate Riwayat Absensi & Cuti (3 Bulan Terakhir sampai kemarin) untuk SEMUA Pegawai
        foreach ($pegawais as $pegawai) {
            // Re-seed PRNG secara spesifik per pegawai agar datanya 100% konsisten
            // dan tidak bergeser meskipun urutan/jumlah pegawai berubah
            mt_srand(crc32($pegawai->nama . 'rexycorp'));

            // Jika pegawai belum punya User account, buatin (password: password)
            if (!User::where('pegawai_id', $pegawai->id)->exists()) {
                // Hindari duplikat email
                $email = strtolower(str_replace(' ', '', $pegawai->nama)) . rand(1, 100) . '@example.com';
                User::create([
                    'name' => $pegawai->nama,
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'role' => 'pegawai',
                    'pegawai_id' => $pegawai->id,
                ]);
            }

            $start = Carbon::now()->subMonths(3)->startOfMonth();
            $end = Carbon::yesterday();
            
            while ($start->lte($end)) {
                // Lewati Weekend
                if ($start->isWeekend()) {
                    $start->addDay();
                    continue;
                }

                $rand = rand(1, 100);
                
                // Bikin variasi kehadiran lebih menyebar, supaya gak semuanya dapet 20-22 hari
                // Misal: 70% Hadir Tepat Waktu, 15% Terlambat, 15% Alfa/Izin
                if ($rand <= 70) {
                    // 70% Hadir
                    Absensi::create([
                        'pegawai_id' => $pegawai->id,
                        'tanggal' => $start->toDateString(),
                        'waktu_masuk' => $start->copy()->setHour(rand(7, 8))->setMinute(rand(0, 59))->toTimeString(),
                        'waktu_keluar' => $start->copy()->setHour(rand(17, 18))->setMinute(rand(0, 30))->toTimeString(),
                        'status' => 'Hadir',
                        'created_at' => $start
                    ]);
                } elseif ($rand <= 85) {
                    // 15% Terlambat
                    Absensi::create([
                        'pegawai_id' => $pegawai->id,
                        'tanggal' => $start->toDateString(),
                        'waktu_masuk' => $start->copy()->setHour(rand(9, 10))->setMinute(rand(0, 59))->toTimeString(),
                        'waktu_keluar' => $start->copy()->setHour(rand(17, 18))->setMinute(rand(0, 30))->toTimeString(),
                        'status' => 'Terlambat',
                        'created_at' => $start
                    ]);
                } else {
                    // 15% Alfa (Tidak absen)
                    // Sepertiganya adalah Cuti/Izin/Sakit yang di acc
                    if (rand(1, 3) == 1) {
                        $jenisList = ['Izin', 'Cuti', 'Sakit'];
                         IzinCuti::create([
                             'pegawai_id' => $pegawai->id,
                             'jenis' => $jenisList[array_rand($jenisList)],
                             'tanggal_mulai' => $start->toDateString(),
                             'tanggal_selesai' => $start->toDateString(),
                             'alasan' => 'Keperluan mendadak (Data Demo)',
                             'status' => 'Disetujui',
                             'created_at' => $start->copy()->subDays(2)
                         ]);
                    }
                }
                
                $start->addDay();
            }
        }


        // 3. Generate Penggajian (Hanya 2 bulan lalu, bulan lalu dikosongkan untuk demo live)
        for ($m = 2; $m >= 2; $m--) {
            $bulan = date('F Y', strtotime("-$m month"));
            
            foreach ($pegawais as $pegawai) {
                mt_srand(crc32($pegawai->nama . $bulan));
                // Potongan acak biar riwayatnya kelihatan bervariasi
                $potonganAbsen = rand(0, 10) * 25000;
                $potonganBpjs = 50000;
                
                $total_pendapatan = $pegawai->gaji_pokok + $pegawai->tunjangan;
                $potonganPajak = $total_pendapatan > 5000000 ? ($total_pendapatan * 0.05) : 0;
                
                $totalGaji = $total_pendapatan - $potonganAbsen - $potonganBpjs - $potonganPajak;

                Penggajian::create([
                    'pegawai_id' => $pegawai->id,
                    'bulan_tahun' => $bulan,
                    'jumlah_hadir' => rand(12, 22),
                    'gaji_pokok' => $pegawai->gaji_pokok,
                    'tunjangan' => $pegawai->tunjangan,
                    'potongan_absen' => $potonganAbsen,
                    'potongan_bpjs' => $potonganBpjs,
                    'potongan_pajak' => $potonganPajak,
                    'total_gaji' => max(0, $totalGaji),
                    'created_at' => Carbon::now()->subMonths($m)->endOfMonth()
                ]);
            }
        }
    }
}
