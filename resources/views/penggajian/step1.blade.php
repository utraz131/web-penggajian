@extends('layouts.app')

@section('title', 'Pilih Periode Penggajian')

@section('content')
<div class="max-w-4xl mx-auto">
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
    <div class="mb-10">
        <div class="flex items-center justify-between relative">
            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-0.5 bg-slate-200 -z-10"></div>
            
            <div class="flex flex-col items-center gap-2 bg-slate-50 px-2">
                <div class="w-8 h-8 rounded-full bg-blue-900 text-white flex items-center justify-center font-bold text-sm shadow ring-4 ring-blue-50">
                    1
                </div>
                <span class="text-xs font-bold text-blue-900">Pilih Periode</span>
            </div>
            <div class="flex flex-col items-center gap-2 bg-slate-50 px-2">
                <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 border border-slate-200 flex items-center justify-center font-bold text-sm">
                    2
                </div>
                <span class="text-xs font-medium text-slate-500">Perhitungan Gaji</span>
            </div>
            <div class="flex flex-col items-center gap-2 bg-slate-50 px-2">
                <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 border border-slate-200 flex items-center justify-center font-bold text-sm">
                    3
                </div>
                <span class="text-xs font-medium text-slate-500">Konfirmasi</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8 text-center max-w-lg mx-auto">
        <h1 class="text-2xl font-bold text-slate-900 mb-2">Pilih Periode Payroll</h1>
        <p class="text-slate-500 text-sm mb-8">Pilih bulan dan tahun untuk memproses slip gaji karyawan. Sistem otomatis akan mengambil riwayat absensi pada bulan terpilih.</p>
        
        <form action="{{ route('penggajian.preview') }}" method="POST">
            @csrf
            <div class="mb-6 text-left">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Periode (Bulan/Tahun)</label>
                <select name="bulan_tahun" class="w-full px-4 py-3 border border-slate-200 rounded-lg text-slate-700 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500" required>
                    @for ($i = -3; $i <= 1; $i++)
                        @php $val = date('F Y', strtotime("$i month")); @endphp
                        <option value="{{ $val }}" {{ $i == -1 ? 'selected' : '' }}>{{ $val }}</option>
                    @endfor
                </select>
            </div>
            
            <button type="submit" class="w-full py-3 bg-blue-900 hover:bg-blue-800 text-white font-semibold rounded-lg shadow-sm flex items-center justify-center gap-2 transition-colors mb-4">
                Lanjut ke Perhitungan
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
            
            <div class="relative flex py-2 items-center">
                <div class="flex-grow border-t border-slate-200"></div>
                <span class="flex-shrink-0 mx-4 text-slate-400 text-xs">Atau</span>
                <div class="flex-grow border-t border-slate-200"></div>
            </div>
            
            <a href="{{ route('penggajian.manual') }}" class="mt-2 w-full py-2.5 bg-white border border-slate-300 text-slate-700 font-semibold rounded-lg shadow-sm flex items-center justify-center gap-2 hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Input Gaji Manual (Satu Per Satu)
            </a>
        </form>
    </div>
</div>
@endsection