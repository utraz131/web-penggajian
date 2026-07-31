@extends('layouts.app')

@section('title', 'Hasil Pencarian')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Hasil Pencarian</h1>
        <p class="text-slate-500 text-sm mt-1">
            Menampilkan hasil untuk: <span class="font-bold text-blue-900">"{{ $query }}"</span>
        </p>
    </div>

    @if(!$query)
        <div class="bg-white rounded-xl border border-slate-200 p-8 text-center text-slate-500 shadow-sm">
            Silakan masukkan kata kunci pencarian.
        </div>
    @else
        <!-- Hasil Karyawan -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h2 class="font-bold text-slate-900">Karyawan ({{ $pegawais->count() }})</h2>
            </div>
            @if($pegawais->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 uppercase bg-white border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Nama</th>
                            <th class="px-6 py-4 font-semibold">NIP</th>
                            <th class="px-6 py-4 font-semibold">Jabatan</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($pegawais as $pegawai)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $pegawai->nama }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $pegawai->nip }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $pegawai->jabatan }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $pegawai->status }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-6 text-center text-slate-500 text-sm">Tidak ada karyawan yang cocok.</div>
            @endif
        </div>

        <!-- Hasil Penggajian -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h2 class="font-bold text-slate-900">Slip Gaji ({{ $penggajians->count() }})</h2>
            </div>
            @if($penggajians->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 uppercase bg-white border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Karyawan</th>
                            <th class="px-6 py-4 font-semibold">Periode</th>
                            <th class="px-6 py-4 font-semibold text-right">Take Home Pay</th>
                            <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($penggajians as $slip)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $slip->pegawai->nama }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $slip->bulan_tahun }}</td>
                            <td class="px-6 py-4 text-right font-bold text-emerald-600">Rp {{ number_format($slip->total_gaji, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('penggajian.show', $slip->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">Lihat Slip</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-6 text-center text-slate-500 text-sm">Tidak ada slip gaji yang cocok.</div>
            @endif
        </div>
    @endif
</div>
@endsection
