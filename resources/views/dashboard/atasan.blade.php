@extends('layouts.app')

@section('title', 'Atasan Dashboard')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Dashboard Eksekutif</h1>
        <p class="text-slate-500 text-sm mt-1">Ringkasan finansial dan pengeluaran penggajian.</p>
    </div>

    <!-- Top Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card 1 -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Payroll Bulan Ini</h3>
                <div class="w-8 h-8 rounded bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-slate-900 mb-1">Rp {{ number_format($totalPayrollBulanIni, 0, ',', '.') }}</div>
            
            @if($persentase > 0)
                <div class="text-sm font-medium text-emerald-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    +{{ number_format($persentase, 1) }}% <span class="text-slate-500 font-normal">vs bulan lalu</span>
                </div>
            @elseif($persentase < 0)
                <div class="text-sm font-medium text-red-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"></path></svg>
                    {{ number_format($persentase, 1) }}% <span class="text-slate-500 font-normal">vs bulan lalu</span>
                </div>
            @else
                <div class="text-sm font-medium text-slate-500 flex items-center gap-1">
                    0% vs bulan lalu
                </div>
            @endif
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Karyawan Digaji (Bulan Ini)</h3>
                <div class="w-8 h-8 rounded bg-slate-50 text-slate-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-slate-900 mb-1">{{ $totalKaryawanDigaji }}</div>
            <div class="text-sm text-slate-500">Orang</div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal Gajian Berikutnya</h3>
                <div class="w-8 h-8 rounded bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-slate-900 mb-1">
                25 {{ date('M Y') }}
            </div>
            <div class="text-sm text-slate-500">Jadwal Rutin</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Aktivitas Terbaru -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-200 flex justify-between items-center">
                <h2 class="text-lg font-bold text-slate-900">Riwayat Pembayaran Terakhir</h2>
                <a href="{{ route('penggajian.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Periode</th>
                            <th class="px-6 py-4 font-semibold">Karyawan</th>
                            <th class="px-6 py-4 font-semibold text-right">Gaji Bersih</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentPayroll as $payroll)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-slate-500">{{ $payroll->bulan_tahun }}</td>
                            <td class="px-6 py-4 font-medium text-slate-700">{{ $payroll->pegawai->nama }}</td>
                            <td class="px-6 py-4 text-right font-bold text-emerald-600">Rp {{ number_format($payroll->total_gaji, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-slate-500">Belum ada riwayat penggajian.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tren Penggajian -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-bold text-slate-900">Tren Penggajian (6 Bulan)</h2>
                <a href="{{ route('reports.index') }}" class="text-sm border-slate-200 rounded-md py-1.5 px-3 bg-slate-50 border text-slate-600 hover:bg-slate-100">Detail Laporan</a>
            </div>
            
            @php
                $maxTotal = 1;
                foreach($enamBulan as $bulan) {
                    if ($bulan['total'] > $maxTotal) {
                        $maxTotal = $bulan['total'];
                    }
                }
            @endphp
            
            <div class="h-64 flex items-end justify-between gap-2 px-2 relative mt-4">
                <!-- Bars -->
                <div class="relative w-full flex justify-around items-end h-full z-10">
                    @foreach($enamBulan as $bulan)
                        @php
                            $heightPercentage = ($bulan['total'] / $maxTotal) * 100;
                            // Ensure a minimum height if it's 0 so it just looks empty but space is there
                            if ($heightPercentage == 0) $heightPercentage = 2;
                        @endphp
                        <div class="w-10 bg-blue-900 rounded-t-sm relative group" style="height: {{ $heightPercentage }}%;">
                            @if($bulan['total'] > 0)
                                <div class="absolute -top-7 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                    Rp {{ number_format($bulan['total'], 0, ',', '.') }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="flex justify-around text-xs text-slate-500 font-medium mt-2">
                @foreach($enamBulan as $bulan)
                    <span>{{ $bulan['label'] }}</span>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
