@extends('layouts.app')

@section('title', 'Perhitungan Gaji (Preview)')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Close button -->
    <div class="flex justify-between items-center mb-6 border-b border-slate-200 pb-4">
        <div class="flex items-center gap-2 text-slate-800 font-bold text-lg">
            <div class="w-6 h-6 bg-blue-900 rounded shadow flex items-center justify-center text-white font-bold text-xs">RC</div>
            REXY CORP
        </div>
        <a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-slate-800 flex items-center gap-1 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            Batalkan
        </a>
    </div>

    <!-- Stepper -->
    <div class="max-w-4xl mx-auto mb-10">
        <div class="flex items-center justify-between relative">
            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-0.5 bg-slate-200 -z-10"></div>
            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1/2 h-0.5 bg-blue-900 -z-10"></div>
            
            <div class="flex flex-col items-center gap-2 bg-slate-50 px-2">
                <div class="w-8 h-8 rounded-full bg-blue-900 text-white flex items-center justify-center font-bold text-sm shadow">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="text-xs font-semibold text-slate-900">Pilih Periode</span>
            </div>
            <div class="flex flex-col items-center gap-2 bg-slate-50 px-2">
                <div class="w-8 h-8 rounded-full bg-blue-900 text-white flex items-center justify-center font-bold text-sm shadow ring-4 ring-blue-50">
                    2
                </div>
                <span class="text-xs font-bold text-blue-900">Perhitungan Gaji</span>
            </div>
            <div class="flex flex-col items-center gap-2 bg-slate-50 px-2">
                <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 border border-slate-200 flex items-center justify-center font-bold text-sm">
                    3
                </div>
                <span class="text-xs font-medium text-slate-500">Konfirmasi</span>
            </div>
        </div>
    </div>

    <form action="{{ route('penggajian.store') }}" method="POST">
        @csrf
        <input type="hidden" name="bulan_tahun" value="{{ $bulan_tahun }}">
        
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Panel -->
            <div class="flex-1">
                <div class="flex justify-between items-end mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">Preview Rincian Gaji</h1>
                        <p class="text-slate-500 text-sm mt-1">Sistem telah menghitung potongan absen, pajak, dan BPJS untuk <b>{{ count($previewData) }} karyawan</b>.</p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-slate-500 uppercase tracking-wider font-semibold mb-1">Periode</div>
                        <div class="text-lg font-bold text-slate-900">{{ $bulan_tahun }}</div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-4 font-semibold">Karyawan</th>
                                    <th class="px-6 py-4 font-semibold text-center">Kehadiran</th>
                                    <th class="px-6 py-4 font-semibold text-emerald-600">Pendapatan</th>
                                    <th class="px-6 py-4 font-semibold text-red-600">Total Potongan</th>
                                    <th class="px-6 py-4 font-semibold text-blue-900 text-right">Take Home Pay</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($previewData as $data)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <input type="hidden" name="pegawai_id[]" value="{{ $data->pegawai->id }}">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded bg-blue-100 text-blue-800 flex items-center justify-center font-bold text-sm shrink-0">
                                                {{ substr($data->pegawai->nama, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-900">{{ $data->pegawai->nama }}</div>
                                                <div class="text-xs text-slate-500">{{ $data->pegawai->jabatan }} &bull; {{ $data->pegawai->departemen }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="font-semibold text-slate-900">{{ $data->jumlah_hadir }} <span class="text-xs font-normal text-slate-500">hari</span></div>
                                        @if($data->jumlah_telat > 0)
                                            <div class="text-xs text-red-500 font-semibold mt-0.5">{{ $data->jumlah_telat }} kali telat</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-900">Rp {{ number_format($data->gaji_pokok + $data->tunjangan, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php $totalPotongan = $data->potongan_absen + $data->potongan_bpjs + $data->potongan_pajak; @endphp
                                        <div class="font-medium text-red-600">-Rp {{ number_format($totalPotongan, 0, ',', '.') }}</div>
                                        <div class="text-xs text-slate-400 mt-0.5" title="Absen: {{ $data->potongan_absen }}, BPJS: {{ $data->potongan_bpjs }}, PPh21: {{ $data->potongan_pajak }}">
                                            Lihat detail
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="text-lg font-bold text-emerald-600">Rp {{ number_format($data->total_gaji, 0, ',', '.') }}</div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                        Semua karyawan aktif sudah diproses gajinya pada bulan ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar Summary -->
            <div class="w-full lg:w-80 shrink-0">
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm sticky top-6">
                    <h3 class="font-bold text-slate-900 text-lg mb-1">Aksi Penggajian</h3>
                    <p class="text-slate-500 text-sm mb-6">Jika data draft di samping sudah benar, silakan lanjutkan untuk menerbitkan slip gaji.</p>
                    
                    <div class="space-y-3">
                        <button type="submit" {{ count($previewData) == 0 ? 'disabled' : '' }} class="w-full py-3 bg-blue-900 hover:bg-blue-800 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-semibold rounded-lg shadow flex items-center justify-center gap-2 transition-colors">
                            Simpan & Terbitkan Slip
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </button>
                        <a href="{{ route('penggajian.create') }}" class="w-full py-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 font-semibold rounded-lg shadow-sm flex items-center justify-center gap-2 transition-colors">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
