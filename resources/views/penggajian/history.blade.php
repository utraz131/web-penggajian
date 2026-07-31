@extends('layouts.app')

@section('title', 'Riwayat Penggajian')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Riwayat Penggajian</h1>
            <p class="text-slate-500 text-sm mt-1">Daftar slip gaji yang telah diterbitkan.</p>
        </div>
        @if(auth()->user()->role === 'atasan')
        <a href="{{ route('penggajian.create') }}" class="px-4 py-2 bg-blue-900 text-white rounded-lg font-medium hover:bg-blue-800 transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Proses Payroll Baru
        </a>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Periode</th>
                        <th class="px-6 py-4 font-semibold">Nama Karyawan</th>
                        <th class="px-6 py-4 font-semibold">Gaji Pokok</th>
                        <th class="px-6 py-4 font-semibold">Total Potongan</th>
                        <th class="px-6 py-4 font-semibold">Take Home Pay</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($penggajians as $gaji)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $gaji->bulan_tahun }}</td>
                        <td class="px-6 py-4 text-slate-700">{{ $gaji->pegawai->nama }}</td>
                        <td class="px-6 py-4 text-slate-600">Rp {{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-red-600">-Rp {{ number_format($gaji->potongan_absen + $gaji->potongan_bpjs + $gaji->potongan_pajak, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 font-bold text-emerald-600">Rp {{ number_format($gaji->total_gaji, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('penggajian.show', $gaji->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 font-medium rounded hover:bg-blue-100 transition-colors">
                                Lihat Slip
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                            Belum ada data riwayat penggajian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
