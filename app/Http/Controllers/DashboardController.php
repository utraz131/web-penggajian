<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Penggajian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return $this->adminDashboard();
        } elseif ($user->role === 'atasan') {
            return $this->atasanDashboard();
        } else {
            return $this->pegawaiDashboard();
        }
    }

    private function adminDashboard()
    {
        $totalKaryawanAktif = Pegawai::where('status', 'Aktif')->count();
        $totalKaryawanNonAktif = Pegawai::where('status', 'Non-Aktif')->count();
        
        // Karyawan baru bulan ini
        $karyawanBaru = Pegawai::whereMonth('created_at', Carbon::now()->month)
                               ->whereYear('created_at', Carbon::now()->year)
                               ->count();
                               
        $recentPegawai = Pegawai::orderBy('created_at', 'desc')->take(5)->get();

        return view('dashboard.admin', compact('totalKaryawanAktif', 'totalKaryawanNonAktif', 'karyawanBaru', 'recentPegawai'));
    }

    private function atasanDashboard()
    {
        $currentMonth = Carbon::now()->isoFormat('MMMM Y'); // e.g. "July 2026"
        $previousMonth = Carbon::now()->subMonth()->isoFormat('MMMM Y');
        
        // Translate to match format in DB "F Y" if using English, or keep as is if DB uses English F Y
        // Since we will use date('F Y') in the controller, it is English (e.g., "July 2026").
        $currentMonthStr = date('F Y');
        $prevMonthStr = date('F Y', strtotime('-1 month'));

        $totalPayrollBulanIni = Penggajian::where('bulan_tahun', $currentMonthStr)->sum('total_gaji');
        $totalPayrollBulanLalu = Penggajian::where('bulan_tahun', $prevMonthStr)->sum('total_gaji');

        $persentase = 0;
        if ($totalPayrollBulanLalu > 0) {
            $persentase = (($totalPayrollBulanIni - $totalPayrollBulanLalu) / $totalPayrollBulanLalu) * 100;
        }

        $totalKaryawanDigaji = Penggajian::where('bulan_tahun', $currentMonthStr)->count();
        
        // 6 Bulan Terakhir untuk Chart
        $enamBulan = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = date('F Y', strtotime("-$i month"));
            $total = Penggajian::where('bulan_tahun', $bulan)->sum('total_gaji');
            $enamBulan[] = [
                'label' => date('M', strtotime("-$i month")), // e.g. "Jul"
                'total' => $total,
                'total_formatted' => $total > 0 ? number_format($total/1000000, 0) . 'Jt' : '0'
            ];
        }

        $recentPayroll = Penggajian::with('pegawai')
                                    ->orderBy('created_at', 'desc')
                                    ->take(5)
                                    ->get();

        return view('dashboard.atasan', compact('totalPayrollBulanIni', 'persentase', 'totalKaryawanDigaji', 'enamBulan', 'recentPayroll'));
    }

    private function pegawaiDashboard()
    {
        $user = Auth::user();
        $pegawai = $user->pegawai;
        
        if (!$pegawai) {
            // Handle if a user has role 'pegawai' but no linked pegawai_id
            $totalGajiTahunIni = 0;
            $slipTerakhir = null;
            return view('dashboard.pegawai', compact('pegawai', 'totalGajiTahunIni', 'slipTerakhir'));
        }

        $currentYear = date('Y');
        
        $totalGajiTahunIni = Penggajian::where('pegawai_id', $pegawai->id)
                                       ->where('bulan_tahun', 'like', "%$currentYear%")
                                       ->sum('total_gaji');

        $slipTerakhir = Penggajian::where('pegawai_id', $pegawai->id)
                                  ->orderBy('created_at', 'desc')
                                  ->first();

        // Riwayat 5 Hari Terakhir (Termasuk yang Alfa/Tidak Absen)
        $riwayatAbsen = [];
        $daysBack = 0;
        
        while (count($riwayatAbsen) < 5 && $daysBack < 30) { // max 30 days back just to be safe
            $date = Carbon::today()->subDays($daysBack);
            $daysBack++;
            
            // Berhenti mengecek jika tanggal mundur sudah melewati tanggal bergabung karyawan
            if ($pegawai->created_at && $date->lt($pegawai->created_at->startOfDay())) {
                break;
            }

            // Cek data absen hari tersebut terlebih dahulu
            $dateString = $date->toDateString();
            $absen = \App\Models\Absensi::where('pegawai_id', $pegawai->id)
                        ->where('tanggal', $dateString)
                        ->first();
            
            if ($absen) {
                $riwayatAbsen[] = $absen;
            } else {
                // Jika tidak ada absen dan hari ini weekend, lewati saja (tidak dicatat sebagai Alfa)
                if ($date->isWeekend()) {
                    continue;
                }

                // Cek apakah ada cuti/izin di hari itu
                $cuti = \App\Models\IzinCuti::where('pegawai_id', $pegawai->id)
                    ->where('status', 'Disetujui')
                    ->where('tanggal_mulai', '<=', $dateString)
                    ->where('tanggal_selesai', '>=', $dateString)
                    ->first();

                if ($cuti) {
                    $riwayatAbsen[] = (object) [
                        'tanggal' => $dateString,
                        'status' => $cuti->jenis, // Cuti, Izin, Sakit
                        'waktu_masuk' => null
                    ];
                } else {
                    $riwayatAbsen[] = (object) [
                        'tanggal' => $dateString,
                        'status' => 'Alfa',
                        'waktu_masuk' => null
                    ];
                }
            }
        }

        $absenHariIni = \App\Models\Absensi::where('pegawai_id', $pegawai->id)
                                  ->whereDate('tanggal', date('Y-m-d'))
                                  ->first();

        // Data Grafik Kehadiran
        $firstAbsen = \App\Models\Absensi::where('pegawai_id', $pegawai->id)->orderBy('tanggal', 'asc')->first();
        $chartData = [];
        $maxChartCount = 1;
        
        if ($firstAbsen) {
            $startMonth = Carbon::parse($firstAbsen->tanggal)->startOfMonth();
            $endMonth = Carbon::today()->startOfMonth();
            
            // Limit to max 6 months to avoid overflowing the UI
            if ($startMonth->diffInMonths($endMonth) > 5) {
                $startMonth = $endMonth->copy()->subMonths(5);
            }
            
            while ($startMonth <= $endMonth) {
                $monthStr = $startMonth->format('Y-m');
                $count = \App\Models\Absensi::where('pegawai_id', $pegawai->id)
                            ->where('status', 'Hadir')
                            ->where('tanggal', 'like', "$monthStr-%")
                            ->count();
                $chartData[] = [
                    'label' => $startMonth->isoFormat('MMM'),
                    'count' => $count
                ];
                $startMonth->addMonth();
            }
        } else {
             $chartData[] = [
                 'label' => Carbon::today()->isoFormat('MMM'),
                 'count' => 0
             ];
        }
        
        $counts = array_column($chartData, 'count');
        $highestCount = count($counts) > 0 ? max($counts) : 0;
        // Asumsikan rata-rata hari kerja sebulan adalah 22 hari
        $maxChartCount = max(22, $highestCount);

        // Statistik Kinerja
        $absenTotalBulanIni = \App\Models\Absensi::where('pegawai_id', $pegawai->id)
                                ->whereMonth('tanggal', Carbon::now()->month)
                                ->whereYear('tanggal', Carbon::now()->year)
                                ->count();
        $absenTepatWaktu = \App\Models\Absensi::where('pegawai_id', $pegawai->id)
                                ->whereMonth('tanggal', Carbon::now()->month)
                                ->whereYear('tanggal', Carbon::now()->year)
                                ->where('status', 'Hadir')
                                ->count();
        
        $persentaseTepatWaktu = 0;
        if ($absenTotalBulanIni > 0) {
            $persentaseTepatWaktu = round(($absenTepatWaktu / $absenTotalBulanIni) * 100);
        }

        return view('dashboard.pegawai', compact('pegawai', 'totalGajiTahunIni', 'slipTerakhir', 'riwayatAbsen', 'absenHariIni', 'chartData', 'maxChartCount', 'persentaseTepatWaktu'));
    }
}
