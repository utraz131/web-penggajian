<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IzinCuti;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\LeaveRequestedNotification;
use App\Notifications\LeaveStatusUpdatedNotification;
use App\Models\Pegawai;
use Carbon\Carbon;

class IzinCutiController extends Controller
{
    /**
     * Menampilkan daftar pengajuan izin dan cuti.
     * 
     * Jika role adalah 'pegawai', hanya menampilkan data milik sendiri.
     * Jika role adalah 'admin' atau 'atasan', menampilkan seluruh data pegawai.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'pegawai') {
            $izinCutis = IzinCuti::where('pegawai_id', $user->pegawai_id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $izinCutis = IzinCuti::with('pegawai')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('izincuti.index', compact('izinCutis'));
    }

    /**
     * Menampilkan halaman formulir pengajuan izin/cuti baru.
     * 
     * Hanya dapat diakses oleh user dengan role 'pegawai'.
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if (Auth::user()->role !== 'pegawai') {
            abort(403);
        }

        $pegawai = Pegawai::find(Auth::user()->pegawai_id);
        $sisaCuti = $pegawai ? $pegawai->sisa_cuti : 0;

        return view('izincuti.create', compact('sisaCuti'));
    }

    /**
     * Memproses dan menyimpan data pengajuan izin/cuti pegawai.
     * 
     * Fungsi ini memvalidasi input form, melakukan pengecekan kuota sisa cuti 
     * (termasuk cuti yang masih berstatus 'Menunggu'), dan mengirimkan 
     * notifikasi pengajuan baru kepada atasan.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'pegawai') {
            abort(403);
        }

        $request->validate([
            'jenis' => 'required|in:Izin,Cuti,Sakit',
            'tanggal_mulai' => 'required|date|after:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'nullable|string',
        ]);

        if ($request->jenis == 'Cuti') {
            $pegawai = Pegawai::find(Auth::user()->pegawai_id);
            $start = Carbon::parse($request->tanggal_mulai);
            $end = Carbon::parse($request->tanggal_selesai);
            $days = $start->diffInDays($end) + 1;
            
            $pendingCutiDays = 0;
            $pendingCutis = IzinCuti::where('pegawai_id', $pegawai->id)
                ->where('jenis', 'Cuti')
                ->where('status', 'Menunggu')
                ->get();
            foreach ($pendingCutis as $pc) {
                $pStart = Carbon::parse($pc->tanggal_mulai);
                $pEnd = Carbon::parse($pc->tanggal_selesai);
                $pendingCutiDays += $pStart->diffInDays($pEnd) + 1;
            }

            if (($days + $pendingCutiDays) > $pegawai->sisa_cuti) {
                return back()->withErrors(['jenis' => 'Sisa cuti Anda (' . $pegawai->sisa_cuti . ' hari) tidak mencukupi. Pengajuan saat ini: ' . $days . ' hari, Menunggu persetujuan: ' . $pendingCutiDays . ' hari.'])->withInput();
            }
        }

        $izinCuti = IzinCuti::create([
            'pegawai_id' => Auth::user()->pegawai_id,
            'jenis' => $request->jenis,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'alasan' => $request->alasan,
            'status' => 'Menunggu',
        ]);

        /**
         * Mengirimkan notifikasi ke seluruh user dengan role 'atasan'
         * bahwa ada pengajuan cuti/izin baru yang masuk.
         */
        $atasanUsers = User::where('role', 'atasan')->get();
        foreach ($atasanUsers as $atasan) {
            $atasan->notify(new LeaveRequestedNotification($izinCuti));
        }

        return redirect()->route('izincuti.index')->with('success', 'Pengajuan berhasil dikirim.');
    }

    /**
     * Memperbarui status persetujuan izin/cuti (Disetujui/Ditolak).
     * 
     * Fungsi ini hanya bisa diakses oleh 'admin' atau 'atasan'.
     * Jika status diubah menjadi 'Disetujui', sistem otomatis memotong kuota 
     * sisa cuti pegawai. Notifikasi perubahan status dikirimkan ke pegawai terkait.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\IzinCuti  $izincuti
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, IzinCuti $izincuti)
    {
        if (!in_array(Auth::user()->role, ['admin', 'atasan'])) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:Disetujui,Ditolak',
        ]);

        $oldStatus = $izincuti->status;

        $izincuti->update([
            'status' => $request->status,
        ]);

        if ($izincuti->jenis == 'Cuti') {
            $pegawai = Pegawai::find($izincuti->pegawai_id);
            $start = Carbon::parse($izincuti->tanggal_mulai);
            $end = Carbon::parse($izincuti->tanggal_selesai);
            $days = $start->diffInDays($end) + 1;

            if ($oldStatus != 'Disetujui' && $request->status == 'Disetujui') {
                $pegawai->sisa_cuti -= $days;
                $pegawai->save();
            } elseif ($oldStatus == 'Disetujui' && $request->status != 'Disetujui') {
                $pegawai->sisa_cuti += $days;
                $pegawai->save();
            }
        }

        /**
         * Mengirimkan notifikasi pembaruan status ke pegawai yang bersangkutan.
         */
        $employeeUser = User::where('pegawai_id', $izincuti->pegawai_id)->first();
        if ($employeeUser) {
            $employeeUser->notify(new LeaveStatusUpdatedNotification($izincuti));
        }

        return redirect()->route('izincuti.index')->with('success', 'Status pengajuan berhasil diperbarui.');
    }
}