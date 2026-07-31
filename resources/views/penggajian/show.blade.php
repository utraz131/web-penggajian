@extends('layouts.app')

@section('title', 'Slip Gaji Digital')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('penggajian.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
        <div class="flex items-center gap-4">
            <button class="p-2 text-slate-500 hover:text-amber-500 transition-colors" title="Bintangi">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
            </button>
            <button class="p-2 text-slate-500 hover:text-blue-600 transition-colors" title="Like">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
            </button>
            <button class="p-2 text-slate-500 hover:text-red-600 transition-colors" title="Dislike">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.715.211-1.413.608-2.008L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5"></path></svg>
            </button>
        </div>
    </div>

    <!-- Payslip Card -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        
        <!-- Header Identitas -->
        <div class="p-8 border-b border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-900 rounded-lg shadow-md flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 leading-tight">REXY CORP</h2>
                    <p class="text-sm text-slate-500 font-medium">Slip Gaji Karyawan</p>
                </div>
            </div>
            <div class="text-left md:text-right">
                <h3 class="text-lg font-bold text-slate-900">{{ $penggajian->pegawai->nama }}</h3>
                <p class="text-sm text-slate-500 mb-1">ID: {{ $penggajian->pegawai->nip }}</p>
                <p class="text-xs font-bold text-blue-900 uppercase tracking-wider">PERIODE: {{ strtoupper($penggajian->bulan_tahun) }}</p>
            </div>
        </div>

        <!-- Rincian -->
        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- Penerimaan -->
            <div class="bg-slate-50/50 rounded-xl border border-slate-100 p-6">
                <h4 class="text-emerald-700 font-bold mb-6 flex items-center gap-2 text-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Penerimaan (Earnings)
                </h4>
                
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600 font-medium">Gaji Pokok</span>
                        <span class="text-slate-900 font-semibold">Rp {{ number_format($penggajian->pegawai->gaji_pokok, 0, ',', '.') }}</span>
                    </div>
                    @php 
                        $total_penerimaan = $penggajian->gaji_pokok + $penggajian->tunjangan;
                    @endphp
                    @if($penggajian->tunjangan > 0)
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600 font-medium">Tunjangan</span>
                        <span class="text-slate-900 font-semibold">Rp {{ number_format($penggajian->tunjangan, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
                
                <div class="mt-6 pt-4 border-t border-slate-200 flex justify-between items-center">
                    <span class="font-bold text-slate-900">Total Penerimaan</span>
                    <span class="font-bold text-slate-900">Rp {{ number_format($total_penerimaan, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Potongan -->
            <div class="bg-slate-50/50 rounded-xl border border-slate-100 p-6">
                <h4 class="text-red-700 font-bold mb-6 flex items-center gap-2 text-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Potongan (Deductions)
                </h4>
                
                <div class="space-y-4 text-sm">
                    @php 
                        $total_potongan = $penggajian->potongan_absen + $penggajian->potongan_bpjs + $penggajian->potongan_pajak;
                    @endphp
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600 font-medium">BPJS (Kesehatan & TK)</span>
                        <span class="text-slate-900 font-semibold">Rp {{ number_format($penggajian->potongan_bpjs, 0, ',', '.') }}</span>
                    </div>
                    @if($penggajian->potongan_pajak > 0)
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600 font-medium">Pajak PPh21</span>
                        <span class="text-slate-900 font-semibold">Rp {{ number_format($penggajian->potongan_pajak, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    @if($penggajian->potongan_absen > 0)
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600 font-medium">Potongan Absensi / Keterlambatan</span>
                        <span class="text-slate-900 font-semibold">Rp {{ number_format($penggajian->potongan_absen, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
                
                <div class="mt-6 pt-4 border-t border-slate-200 flex justify-between items-center">
                    <span class="font-bold text-slate-900">Total Potongan</span>
                    <span class="font-bold text-slate-900">Rp {{ number_format($total_potongan, 0, ',', '.') }}</span>
                </div>
            </div>
            
        </div>

        <!-- Take Home Pay -->
        <div class="px-8 pb-8">
            <div class="bg-blue-50 border-l-4 border-blue-900 rounded-r-xl p-6 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 mb-1">Take Home Pay</h3>
                    <p class="text-sm text-slate-600">Total gaji bersih yang diterima pada bulan {{ $penggajian->bulan_tahun }}.</p>
                </div>
                <div class="text-3xl md:text-4xl font-extrabold text-blue-900">
                    Rp {{ number_format($penggajian->total_gaji, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex justify-end gap-4 print:hidden">
            <button class="px-6 py-2.5 bg-white border border-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-100 transition-colors shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                Bagikan
            </button>
            <button onclick="window.print()" class="px-6 py-2.5 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 transition-colors shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Unduh PDF / Cetak
            </button>
        </div>

    </div>
</div>
@endsection
