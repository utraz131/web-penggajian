<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    // Halaman Admin: Pantau Absensi Hari Ini
    public function index()
    {
        $today = Carbon::today()->toDateString();
        $absensis = Absensi::with('pegawai')
                           ->where('tanggal', $today)
                           ->orderBy('created_at', 'desc')
                           ->get();

        return view('absensi.index', compact('absensis', 'today'));
    }

    // Halaman Pegawai: Kalender Kehadiran
    public function kalender(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'pegawai' || !$user->pegawai_id) {
            return redirect('/')->with('error', 'Hanya pegawai yang dapat melihat kalender.');
        }

        $pegawai = $user->pegawai;
        
        $month = $request->query('month', Carbon::today()->month);
        $year = $request->query('year', Carbon::today()->year);
        
        $currentDate = Carbon::createFromDate($year, $month, 1);
        $currentMonthStart = $currentDate->copy()->startOfMonth();
        $currentMonthEnd = $currentDate->copy()->endOfMonth();
        
        $absensiBulanIni = Absensi::where('pegawai_id', $pegawai->id)
                                ->whereBetween('tanggal', [$currentMonthStart->toDateString(), $currentMonthEnd->toDateString()])
                                ->get()
                                ->keyBy(function($item) {
                                    return \Carbon\Carbon::parse($item->tanggal)->toDateString();
                                });
        
        $cutiBulanIni = \App\Models\IzinCuti::where('pegawai_id', $pegawai->id)
                                ->where('status', 'Disetujui')
                                ->where(function($q) use ($currentMonthStart, $currentMonthEnd) {
                                    $q->whereBetween('tanggal_mulai', [$currentMonthStart->toDateString(), $currentMonthEnd->toDateString()])
                                      ->orWhereBetween('tanggal_selesai', [$currentMonthStart->toDateString(), $currentMonthEnd->toDateString()]);
                                })
                                ->get();
                                
        $kalenderAbsensi = [];
        $startDayOfWeek = $currentMonthStart->dayOfWeekIso; // 1 (Mon) - 7 (Sun)
        
        $summary = [
            'hadir' => 0,
            'telat' => 0,
            'alfa' => 0,
            'izin' => 0,
            'sakit' => 0,
        ];
        
        for ($date = $currentMonthStart->copy(); $date->lte($currentMonthEnd); $date->addDay()) {
            $dateStr = $date->toDateString();
            $statusKalender = 'belum';
            $waktuMasuk = '--:--';
            $waktuKeluar = '--:--';
            $keterangan = '';
            
            if ($absensiBulanIni->has($dateStr)) {
                $absenDay = $absensiBulanIni->get($dateStr);
                $waktuMasuk = $absenDay->waktu_masuk ? substr($absenDay->waktu_masuk, 0, 5) : '--:--';
                $waktuKeluar = $absenDay->waktu_keluar ? substr($absenDay->waktu_keluar, 0, 5) : '--:--';
                
                if (stripos($absenDay->status, 'Terlambat') !== false || stripos($absenDay->keterangan, 'Terlambat') !== false) {
                    $statusKalender = 'telat';
                    $keterangan = 'Terlambat';
                } elseif ($absenDay->status == 'Hadir') {
                    $statusKalender = 'hadir';
                } elseif ($absenDay->status == 'Alfa') {
                    $statusKalender = 'alfa';
                } else {
                    $statusKalender = 'hadir';
                }
            } else {
                $isCuti = false;
                $jenisCuti = 'izin';
                foreach($cutiBulanIni as $cuti) {
                    if ($dateStr >= $cuti->tanggal_mulai && $dateStr <= $cuti->tanggal_selesai) {
                        $isCuti = true;
                        $keterangan = $cuti->alasan;
                        $jenisCuti = strtolower($cuti->jenis); // e.g. sakit, izin, cuti
                        break;
                    }
                }
                if ($isCuti) {
                    if (strpos($jenisCuti, 'sakit') !== false) {
                        $statusKalender = 'sakit';
                    } else {
                        $statusKalender = 'izin';
                    }
                } else {
                    if ($date->isWeekend()) {
                        $statusKalender = 'libur';
                    } elseif ($date->gt(Carbon::today())) {
                        $statusKalender = 'belum';
                    } elseif ($date->lt(Carbon::today())) {
                        $statusKalender = 'alfa';
                    } else {
                        $statusKalender = 'belum';
                    }
                }
            }
            
            if ($statusKalender == 'hadir') $summary['hadir']++;
            elseif ($statusKalender == 'telat') $summary['telat']++;
            elseif ($statusKalender == 'alfa') $summary['alfa']++;
            elseif ($statusKalender == 'izin' || $statusKalender == 'cuti') $summary['izin']++;
            elseif ($statusKalender == 'sakit') $summary['sakit']++;
            
            $kalenderAbsensi[] = [
                'tanggal' => $date->day,
                'date_str' => $dateStr,
                'status' => $statusKalender,
                'waktu_masuk' => $waktuMasuk,
                'waktu_keluar' => $waktuKeluar,
                'keterangan' => $keterangan
            ];
        }

        // Sort absensiBulanIni for the table, descending by date
        $daftarAbsensi = $absensiBulanIni->sortByDesc(function ($item) {
            return $item->tanggal;
        });

        return view('absensi.kalender', compact('kalenderAbsensi', 'startDayOfWeek', 'currentDate', 'summary', 'daftarAbsensi'));
    }

    // Halaman Pegawai: Ambil Absen Kamera
    public function create()
    {
        $user = Auth::user();
        if ($user->role !== 'pegawai' || !$user->pegawai_id) {
            return redirect('/')->with('error', 'Hanya pegawai yang dapat melakukan absensi.');
        }

        $today = Carbon::today()->toDateString();
        
        // Cek status absen hari ini
        $absensiHariIni = Absensi::where('pegawai_id', $user->pegawai_id)
                                 ->where('tanggal', $today)
                                 ->first();

        return view('absensi.create', compact('absensiHariIni'));
    }

    // Proses Simpan Absen (Dipanggil via AJAX/Form POST)
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'pegawai' || !$user->pegawai_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'image' => 'required|string', // Base64 image
            'tipe' => 'required|in:masuk,keluar',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Validasi Jarak Lokasi
        $officeLat = env('OFFICE_LATITUDE', -6.175392);
        $officeLng = env('OFFICE_LONGITUDE', 106.827153);
        $maxRadius = env('OFFICE_RADIUS', 100);

        $distance = $this->calculateDistance($request->latitude, $request->longitude, $officeLat, $officeLng);

        // if ($distance > $maxRadius) {
        //     return response()->json([
        //         'success' => false, 
        //         'message' => 'Anda berada di luar jangkauan area kantor! Jarak Anda: ' . round($distance) . ' meter.'
        //     ], 403);
        // }

        $today = Carbon::today()->toDateString();
        $timeNow = Carbon::now()->toTimeString();
        $image = $request->image;

        $absensi = Absensi::where('pegawai_id', $user->pegawai_id)
                          ->where('tanggal', $today)
                          ->first();

        if ($request->tipe === 'masuk') {
            if ($absensi && $absensi->waktu_masuk) {
                return response()->json(['success' => false, 'message' => 'Anda sudah absen masuk hari ini.']);
            }
            
            // Validasi Jam Buka Absen Masuk (06:00 - 12:00)
            $jamSekarang = Carbon::now()->format('H:i');
            // DI-COMMENT SEMENTARA UNTUK DEMO SERKOM
            // if ($jamSekarang < '06:00') {
            //     return response()->json(['success' => false, 'message' => 'Absen masuk belum dibuka. Silakan kembali pada jam 06:00.']);
            // }
            // if ($jamSekarang > '12:00') {
            //     return response()->json(['success' => false, 'message' => 'Batas waktu absen masuk sudah habis (Tutup jam 12:00).']);
            // }

            // Cek keterlambatan (Batas jam masuk 08:00)
            $status = 'Hadir';
            if ($jamSekarang > '08:00') {
                $status = 'Terlambat';
            }

            if (!$absensi) {
                Absensi::create([
                    'pegawai_id' => $user->pegawai_id,
                    'tanggal' => $today,
                    'waktu_masuk' => $timeNow,
                    'foto_masuk' => $image,
                    'status' => $status
                ]);
            } else {
                $absensi->update([
                    'waktu_masuk' => $timeNow,
                    'foto_masuk' => $image,
                    'status' => $status
                ]);
            }
            
            return response()->json(['success' => true, 'message' => 'Absen Masuk Berhasil! Status: ' . $status]);
            
        } else { // Pulang
            if (!$absensi || !$absensi->waktu_masuk) {
                return response()->json(['success' => false, 'message' => 'Anda belum absen masuk.']);
            }
            if ($absensi->waktu_keluar) {
                return response()->json(['success' => false, 'message' => 'Anda sudah absen pulang hari ini.']);
            }

            // Validasi Jam Buka Absen Pulang (16:00 - 18:00)
            $jamSekarang = Carbon::now()->format('H:i');
            // DI-COMMENT SEMENTARA UNTUK DEMO SERKOM
            // if ($jamSekarang < '16:00') {
            //     return response()->json(['success' => false, 'message' => 'Belum waktunya pulang. Absen pulang dibuka jam 16:00.']);
            // }
            // if ($jamSekarang > '18:00') {
            //     return response()->json(['success' => false, 'message' => 'Batas waktu absen pulang sudah habis (Tutup jam 18:00).']);
            // }

            $absensi->update([
                'waktu_keluar' => $timeNow,
                'foto_keluar' => $image
            ]);
            
            return response()->json(['success' => true, 'message' => 'Absen Pulang Berhasil! Hati-hati di jalan.']);
        }
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earth_radius = 6371000; // Radius bumi dalam meter
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * asin(sqrt($a));
        return $earth_radius * $c;
    }
}
