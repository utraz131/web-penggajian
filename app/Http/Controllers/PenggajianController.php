<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Penggajian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenggajianController extends Controller
{
    // Menampilkan riwayat gaji (Reports)
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'pegawai') {
            $penggajians = Penggajian::with('pegawai')->where('pegawai_id', $user->pegawai_id)->get();
        } else {
            $penggajians = Penggajian::with('pegawai')->get();
        }
        
        return view('penggajian.history', compact('penggajians'));
    }

    // Langkah 1: Form Pilih Periode
    public function create()
    {
        return view('penggajian.step1');
    }

    // Menampilkan Form Gaji Manual
    public function createManual(Request $request)
    {
        $pegawais = Pegawai::where('status', 'Aktif')->get();
        $default_month = date('Y-m');
        if ($request->has('bulan_tahun')) {
            try {
                $default_month = \Carbon\Carbon::createFromFormat('F Y', $request->bulan_tahun)->format('Y-m');
            } catch (\Exception $e) {}
        }
        return view('penggajian.manual', compact('pegawais', 'default_month'));
    }

    // Menyimpan Gaji Manual
    public function storeManual(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'bulan_tahun' => 'required|string',
            'jumlah_hadir' => 'required|integer|min:0',
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan' => 'required|numeric|min:0',
            'potongan_absen' => 'required|numeric|min:0',
            'potongan_bpjs' => 'required|numeric|min:0',
            'potongan_pajak' => 'required|numeric|min:0',
            'total_gaji' => 'required|numeric|min:0',
        ]);

        // Format bulan_tahun dari Y-m (cth: 2026-07) menjadi F Y (cth: July 2026)
        $formatted_bulan = \Carbon\Carbon::createFromFormat('Y-m', $request->bulan_tahun)->format('F Y');

        // Cek apakah data gaji bulan ini sudah ada
        if (Penggajian::where('pegawai_id', $request->pegawai_id)
            ->where('bulan_tahun', $formatted_bulan)->exists()) {
            return back()->withInput()->with('error', 'Gaji untuk karyawan ini pada periode tersebut sudah ada.');
        }

        Penggajian::create([
            'pegawai_id' => $request->pegawai_id,
            'bulan_tahun' => $formatted_bulan,
            'jumlah_hadir' => $request->jumlah_hadir,
            'gaji_pokok' => $request->gaji_pokok,
            'tunjangan' => $request->tunjangan,
            'potongan_absen' => $request->potongan_absen,
            'potongan_bpjs' => $request->potongan_bpjs,
            'potongan_pajak' => $request->potongan_pajak,
            'total_gaji' => $request->total_gaji,
        ]);

        return redirect()->route('penggajian.index')->with('success', 'Gaji manual berhasil disimpan!');
    }

    public function getAbsensiStats(Request $request)
    {
        $pegawai_id = $request->pegawai_id;
        $bulan_tahun = $request->bulan_tahun;

        if (!$pegawai_id || !$bulan_tahun) {
            return response()->json(['jumlah_hadir' => 0]);
        }

        try {
            $date = \Carbon\Carbon::createFromFormat('Y-m', $bulan_tahun);
            $month = $date->month;
            $year = $date->year;

            $jumlah_hadir = \App\Models\Absensi::where('pegawai_id', $pegawai_id)
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->whereNotNull('waktu_masuk')
                ->count();

            return response()->json(['jumlah_hadir' => $jumlah_hadir]);
        } catch (\Exception $e) {
            return response()->json(['jumlah_hadir' => 0]);
        }
    }

    // Langkah 2: Preview Perhitungan Gaji
    public function preview(Request $request)
    {
        $request->validate([
            'bulan_tahun' => 'required|string',
        ]);

        $bulan_tahun = $request->bulan_tahun;
        $pegawais = Pegawai::where('status', 'Aktif')->get();
        
        $previewData = [];
        
        $month = date('m', strtotime($bulan_tahun));
        $year = date('Y', strtotime($bulan_tahun));

        foreach ($pegawais as $pegawai) {
            // Skip pegawai yang baru bergabung setelah bulan penggajian ini
            $penggajianDate = \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth();
            if ($pegawai->created_at && $pegawai->created_at->startOfDay()->gt($penggajianDate)) {
                continue;
            }

            // Skip jika sudah ada penggajian
            if (Penggajian::where('pegawai_id', $pegawai->id)->where('bulan_tahun', $bulan_tahun)->exists()) {
                continue;
            }

            $gaji_pokok = $pegawai->gaji_pokok;
            $tunjangan = $pegawai->tunjangan;
            
            $jumlah_hadir = \App\Models\Absensi::where('pegawai_id', $pegawai->id)
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->whereNotNull('waktu_masuk')
                ->count();
                
            $jumlah_telat = \App\Models\Absensi::where('pegawai_id', $pegawai->id)
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->where('status', 'Terlambat')
                ->count();

            $jumlah_cuti_izin = 0;
            $izinCutis = \App\Models\IzinCuti::where('pegawai_id', $pegawai->id)
                ->where('status', 'Disetujui')
                ->where(function($q) use ($month, $year) {
                    $q->whereMonth('tanggal_mulai', $month)->whereYear('tanggal_mulai', $year)
                      ->orWhereMonth('tanggal_selesai', $month)->whereYear('tanggal_selesai', $year);
                })->get();

            foreach($izinCutis as $izin) {
                $start = \Carbon\Carbon::parse($izin->tanggal_mulai);
                $end = \Carbon\Carbon::parse($izin->tanggal_selesai);
                
                while($start->lte($end)) {
                    if ($start->month == $month && $start->year == $year) {
                        $jumlah_cuti_izin++;
                    }
                    $start->addDay();
                }
            }

            $hari_kurang = max(0, 20 - ($jumlah_hadir + $jumlah_cuti_izin));
            $potongan_absen = ($hari_kurang * 100000) + ($jumlah_telat * 25000);
            
            $potongan_bpjs_tk = $gaji_pokok * 0.02;
            $potongan_bpjs_kes = $gaji_pokok * 0.01;
            $potongan_bpjs = $potongan_bpjs_tk + $potongan_bpjs_kes;
            
            $total_pendapatan = $gaji_pokok + $tunjangan;
            $potongan_pajak = $total_pendapatan > 5000000 ? ($total_pendapatan * 0.05) : 0;

            $total_gaji = max(0, $total_pendapatan - ($potongan_absen + $potongan_bpjs + $potongan_pajak));

            $previewData[] = (object) [
                'pegawai' => $pegawai,
                'gaji_pokok' => $gaji_pokok,
                'tunjangan' => $tunjangan,
                'jumlah_hadir' => $jumlah_hadir,
                'jumlah_telat' => $jumlah_telat,
                'potongan_absen' => $potongan_absen,
                'potongan_bpjs' => $potongan_bpjs,
                'potongan_pajak' => $potongan_pajak,
                'total_gaji' => $total_gaji,
            ];
        }

        return view('penggajian.step2', compact('previewData', 'bulan_tahun'));
    }

    // Langkah 3: Simpan Data Gaji
    public function store(Request $request)
    {
        $request->validate([
            'bulan_tahun' => 'required|string',
            'pegawai_id' => 'required|array',
        ]);

        $bulan_tahun = $request->bulan_tahun;
        $month = date('m', strtotime($bulan_tahun));
        $year = date('Y', strtotime($bulan_tahun));

        foreach ($request->pegawai_id as $pegawai_id) {
            $pegawai = Pegawai::find($pegawai_id);
            if (!$pegawai) continue;
            
            // Skip pegawai yang baru bergabung setelah bulan penggajian ini
            $penggajianDate = \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth();
            if ($pegawai->created_at && $pegawai->created_at->startOfDay()->gt($penggajianDate)) {
                continue;
            }

            if (Penggajian::where('pegawai_id', $pegawai_id)->where('bulan_tahun', $bulan_tahun)->exists()) {
                continue;
            }

            $gaji_pokok = $pegawai->gaji_pokok;
            $tunjangan = $pegawai->tunjangan;
            
            $jumlah_hadir = \App\Models\Absensi::where('pegawai_id', $pegawai_id)
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->whereNotNull('waktu_masuk')
                ->count();
                
            $jumlah_telat = \App\Models\Absensi::where('pegawai_id', $pegawai_id)
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->where('status', 'Terlambat')
                ->count();

            $jumlah_cuti_izin = 0;
            $izinCutis = \App\Models\IzinCuti::where('pegawai_id', $pegawai_id)
                ->where('status', 'Disetujui')
                ->where(function($q) use ($month, $year) {
                    $q->whereMonth('tanggal_mulai', $month)->whereYear('tanggal_mulai', $year)
                      ->orWhereMonth('tanggal_selesai', $month)->whereYear('tanggal_selesai', $year);
                })->get();

            foreach($izinCutis as $izin) {
                $start = \Carbon\Carbon::parse($izin->tanggal_mulai);
                $end = \Carbon\Carbon::parse($izin->tanggal_selesai);
                
                while($start->lte($end)) {
                    if ($start->month == $month && $start->year == $year) {
                        $jumlah_cuti_izin++;
                    }
                    $start->addDay();
                }
            }

            $hari_kurang = max(0, 20 - ($jumlah_hadir + $jumlah_cuti_izin));
            $potongan_absen = ($hari_kurang * 100000) + ($jumlah_telat * 25000);
            
            $potongan_bpjs_tk = $gaji_pokok * 0.02;
            $potongan_bpjs_kes = $gaji_pokok * 0.01;
            $potongan_bpjs = $potongan_bpjs_tk + $potongan_bpjs_kes;
            
            $total_pendapatan = $gaji_pokok + $tunjangan;
            $potongan_pajak = $total_pendapatan > 5000000 ? ($total_pendapatan * 0.05) : 0;

            $total_gaji = max(0, $total_pendapatan - ($potongan_absen + $potongan_bpjs + $potongan_pajak));

            Penggajian::create([
                'pegawai_id'   => $pegawai_id,
                'bulan_tahun'  => $bulan_tahun,
                'jumlah_hadir' => $jumlah_hadir,
                'gaji_pokok'   => $gaji_pokok,
                'tunjangan'    => $tunjangan,
                'potongan_absen' => $potongan_absen,
                'potongan_bpjs'  => $potongan_bpjs,
                'potongan_pajak' => $potongan_pajak,
                'total_gaji'   => $total_gaji
            ]);
        }

        return redirect()->route('penggajian.success');
    }

    public function success()
    {
        return view('penggajian.step3');
    }

    // Menampilkan Slip Gaji Digital
    public function show(Penggajian $penggajian)
    {
        $user = Auth::user();
        // Proteksi: Pegawai hanya bisa lihat slip sendiri
        if ($user->role === 'pegawai' && $penggajian->pegawai_id !== $user->pegawai_id) {
            abort(403, 'Akses ditolak.');
        }

        $penggajian->load('pegawai');
        return view('penggajian.show', compact('penggajian'));
    }
}