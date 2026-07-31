@extends('layouts.app')

@section('title', 'Manajemen Izin & Cuti')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Manajemen Izin & Cuti</h1>
            <p class="text-slate-500 text-sm mt-1">Daftar riwayat dan pengajuan izin/cuti karyawan.</p>
        </div>
        @if(auth()->user()->role === 'pegawai')
        <a href="{{ route('izincuti.create') }}" class="px-5 py-2.5 bg-blue-900 hover:bg-blue-800 text-white font-semibold rounded-lg shadow-sm transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Buat Pengajuan
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
                        @if(in_array(auth()->user()->role, ['admin', 'atasan']))
                        <th class="px-6 py-4 font-semibold">Karyawan</th>
                        @endif
                        <th class="px-6 py-4 font-semibold">Jenis</th>
                        <th class="px-6 py-4 font-semibold">Tanggal</th>
                        <th class="px-6 py-4 font-semibold">Alasan</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        @if(in_array(auth()->user()->role, ['admin', 'atasan']))
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($izinCutis as $izin)
                    <tr class="hover:bg-slate-50 transition-colors">
                        @if(in_array(auth()->user()->role, ['admin', 'atasan']))
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $izin->pegawai->nama }}</div>
                            <div class="text-xs text-slate-500">{{ $izin->pegawai->nip }}</div>
                        </td>
                        @endif
                        <td class="px-6 py-4 font-semibold text-slate-700">{{ $izin->jenis }}</td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ \Carbon\Carbon::parse($izin->tanggal_mulai)->format('d M Y') }} - 
                            {{ \Carbon\Carbon::parse($izin->tanggal_selesai)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-slate-600 max-w-xs truncate" title="{{ $izin->alasan }}">
                            {{ $izin->alasan ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($izin->status === 'Disetujui')
                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-full border border-emerald-200">Disetujui</span>
                            @elseif($izin->status === 'Ditolak')
                                <span class="px-2.5 py-1 bg-red-50 text-red-700 text-xs font-semibold rounded-full border border-red-200">Ditolak</span>
                            @else
                                <span class="px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-semibold rounded-full border border-amber-200">Menunggu</span>
                            @endif
                        </td>
                        
                        @if(in_array(auth()->user()->role, ['admin', 'atasan']))
                        <td class="px-6 py-4 text-center">
                            @if($izin->status === 'Menunggu')
                            <form action="{{ route('izincuti.updateStatus', $izin->id) }}" method="POST" class="inline-block w-28">
                                @csrf
                                @method('PUT')
                                <div class="relative">
                                    <select name="status" onchange="this.form.submit()" class="block w-full text-xs font-semibold text-slate-700 py-2 pl-3 pr-8 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white cursor-pointer hover:bg-slate-50 transition-colors">
                                        <option value="" disabled selected>Aksi...</option>
                                        <option value="Disetujui">Setujui</option>
                                        <option value="Ditolak">Tolak</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </form>
                            @else
                            <span class="text-xs text-slate-400 font-medium italic">Selesai</span>
                            @endif
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                            Belum ada riwayat izin / cuti.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
