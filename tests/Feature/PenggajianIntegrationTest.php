<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class PenggajianIntegrationTest extends TestCase
{
    /**
     * A basic feature test for Payroll Integration (Serkom Unit 10)
     *
     * @return void
     */
    public function test_atasan_bisa_mengakses_halaman_run_payroll()
    {
        // 1. Buat user dummy dengan role 'atasan' (jika menggunakan Factory)
        // Karena kita tidak merefresh database agar tidak menghapus data asli, 
        // kita akan membuat atau mencari user atasan secara statis
        $atasan = User::firstOrCreate(
            ['email' => 'atasan_test@example.com'],
            [
                'name' => 'Atasan Tester',
                'password' => bcrypt('password'),
                'role' => 'atasan',
            ]
        );

        // 2. Simulasikan login menggunakan akun Atasan
        $response = $this->actingAs($atasan)->get('/penggajian/create');

        // 3. Pastikan halaman terbuka dengan sukses (Status 200 OK)
        $response->assertStatus(200);

        // 4. Pastikan di dalam halaman ada tulisan "Pilih Periode Payroll"
        $response->assertSee('Pilih Periode Payroll');
        
        // Bersihkan data test
        $atasan->delete();
    }
}
