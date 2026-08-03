<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat Akun Users Induk (Tidak terikat pegawai)
        User::create([
            'name' => 'Admin HR',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Atasan Direktur',
            'email' => 'atasan@test.com',
            'password' => bcrypt('password'),
            'role' => 'atasan',
        ]);

        // ============================================
        // AKUN TESTING UTAMA (BAYU & REXY)
        // ============================================
        $pegawaiBayu = \App\Models\Pegawai::create([
            'nip' => '131105',
            'nama' => 'bayu',
            'departemen' => 'IT',
            'jabatan' => 'Staff',
            'status' => 'Aktif',
            'gaji_pokok' => 5000000,
            'tunjangan' => 1000000,
            'created_at' => \Carbon\Carbon::now()->subMonths(6),
        ]);

        User::create([
            'name' => 'bayu',
            'email' => 'bayu@test.com', // Sesuaikan jika email bayu berbeda
            'password' => bcrypt('password'),
            'role' => 'pegawai',
            'pegawai_id' => $pegawaiBayu->id,
        ]);

        $pegawaiRexy = \App\Models\Pegawai::create([
            'nip' => '9876789',
            'nama' => 'rexy',
            'departemen' => 'IT',
            'jabatan' => 'Staff',
            'status' => 'Aktif',
            'gaji_pokok' => 5000000,
            'tunjangan' => 1000000,
            'created_at' => \Carbon\Carbon::now()->subMonths(6),
        ]);

        User::create([
            'name' => 'rexy',
            'email' => 'rexy@test.com', // Sesuaikan jika email rexy berbeda
            'password' => bcrypt('password'),
            'role' => 'pegawai',
            'pegawai_id' => $pegawaiRexy->id,
        ]);

        $pegawaiFathia = \App\Models\Pegawai::create([
            'nip' => '9988776',
            'nama' => 'fathia',
            'departemen' => 'Keuangan',
            'jabatan' => 'Staff',
            'status' => 'Aktif',
            'gaji_pokok' => 5000000,
            'tunjangan' => 1000000,
            'created_at' => \Carbon\Carbon::now()->subMonths(6),
        ]);

        User::create([
            'name' => 'fathia',
            'email' => 'fathia@test.com',
            'password' => bcrypt('password'),
            'role' => 'pegawai',
            'pegawai_id' => $pegawaiFathia->id,
        ]);
    }
}
