@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Dashboard Admin</h1>
        <p class="text-slate-500 text-sm mt-1">Kelola data karyawan dan sistem.</p>
    </div>

    <!-- Top Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card 1 -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Karyawan Aktif</h3>
                <div class="w-8 h-8 rounded bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-slate-900 mb-1">{{ $totalKaryawanAktif }}</div>
            <div class="text-sm text-slate-500">Orang</div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Karyawan Non-Aktif</h3>
                <div class="w-8 h-8 rounded bg-slate-50 text-slate-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-slate-900 mb-1">{{ $totalKaryawanNonAktif }}</div>
            <div class="text-sm text-slate-500">Orang</div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Karyawan Baru</h3>
                <div class="w-8 h-8 rounded bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-slate-900 mb-1">{{ $karyawanBaru }}</div>
            <div class="text-sm text-slate-500">Bulan Ini</div>
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mt-8">
        <div class="p-6 border-b border-slate-200 flex justify-between items-center">
            <h2 class="text-lg font-bold text-slate-900">Karyawan Terdaftar Terbaru</h2>
            <a href="{{ route('pegawai.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Lihat Semua Karyawan</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Nama</th>
                        <th class="px-6 py-4 font-semibold">NIP</th>
                        <th class="px-6 py-4 font-semibold">Departemen</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold">Waktu Daftar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentPegawai as $pegawai)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $pegawai->nama }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $pegawai->nip }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $pegawai->departemen }}</td>
                        <td class="px-6 py-4">
                            @if($pegawai->status == 'Aktif')
                                <span class="px-2 py-1 bg-emerald-50 text-emerald-700 text-xs rounded-full border border-emerald-200">{{ $pegawai->status }}</span>
                            @else
                                <span class="px-2 py-1 bg-red-50 text-red-700 text-xs rounded-full border border-red-200">{{ $pegawai->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $pegawai->created_at->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">Belum ada data karyawan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
