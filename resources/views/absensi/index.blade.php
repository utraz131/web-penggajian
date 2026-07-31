@extends('layouts.app')

@section('title', 'Monitor Absensi Hari Ini')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Kehadiran Hari Ini</h1>
            <p class="text-slate-500 text-sm mt-1">Tanggal: {{ \Carbon\Carbon::parse($today)->isoFormat('D MMMM Y') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Karyawan</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold text-center">Absen Masuk</th>
                        <th class="px-6 py-4 font-semibold text-center">Absen Pulang</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($absensis as $absen)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $absen->pegawai->nama }}</div>
                            <div class="text-xs text-slate-500">{{ $absen->pegawai->nip }} &bull; {{ $absen->pegawai->departemen }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($absen->status == 'Hadir')
                                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-200">Hadir</span>
                            @elseif($absen->status == 'Terlambat')
                                <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-semibold border border-amber-200">Terlambat</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full bg-slate-50 text-slate-700 text-xs font-semibold border border-slate-200">{{ $absen->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($absen->foto_masuk)
                                <img src="{{ $absen->foto_masuk }}" alt="Foto Masuk" class="w-16 h-16 object-cover rounded shadow-sm mx-auto mb-1 border border-slate-200 cursor-pointer hover:scale-150 transition-transform origin-center">
                                <div class="text-xs font-bold text-slate-700">{{ $absen->waktu_masuk }}</div>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($absen->foto_keluar)
                                <img src="{{ $absen->foto_keluar }}" alt="Foto Pulang" class="w-16 h-16 object-cover rounded shadow-sm mx-auto mb-1 border border-slate-200 cursor-pointer hover:scale-150 transition-transform origin-center">
                                <div class="text-xs font-bold text-slate-700">{{ $absen->waktu_keluar }}</div>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                            Belum ada karyawan yang absen masuk hari ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
