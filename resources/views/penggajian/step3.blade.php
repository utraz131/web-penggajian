@extends('layouts.app')

@section('title', 'Selesai - Konfirmasi Payroll')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Stepper -->
    <div class="mb-10">
        <div class="flex items-center justify-between relative">
            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-0.5 bg-slate-200 -z-10"></div>
            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-0.5 bg-blue-900 -z-10"></div>
            
            <div class="flex flex-col items-center gap-2 bg-slate-50 px-2">
                <div class="w-8 h-8 rounded-full bg-blue-900 text-white flex items-center justify-center font-bold text-sm shadow">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="text-xs font-semibold text-slate-900">Pilih Periode</span>
            </div>
            <div class="flex flex-col items-center gap-2 bg-slate-50 px-2">
                <div class="w-8 h-8 rounded-full bg-blue-900 text-white flex items-center justify-center font-bold text-sm shadow">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="text-xs font-bold text-slate-900">Perhitungan Gaji</span>
            </div>
            <div class="flex flex-col items-center gap-2 bg-slate-50 px-2">
                <div class="w-8 h-8 rounded-full bg-blue-900 text-white flex items-center justify-center font-bold text-sm shadow ring-4 ring-blue-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="text-xs font-bold text-blue-900">Konfirmasi</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-10 text-center max-w-lg mx-auto">
        <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        
        <h1 class="text-2xl font-bold text-slate-900 mb-2">Payroll Berhasil Diproses!</h1>
        <p class="text-slate-500 text-sm mb-8">Data slip gaji karyawan untuk periode ini telah disimpan dan diterbitkan. Karyawan kini dapat melihat slip gajinya pada portal mandiri masing-masing.</p>
        
        <div class="flex flex-col gap-3">
            <a href="{{ route('reports.index') }}" class="w-full py-3 bg-blue-900 hover:bg-blue-800 text-white font-semibold rounded-lg shadow-sm transition-colors">
                Lihat Laporan Pengeluaran (Reports)
            </a>
            <a href="{{ route('dashboard') }}" class="w-full py-3 bg-white hover:bg-slate-50 text-slate-600 font-semibold rounded-lg border border-slate-200 transition-colors">
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
