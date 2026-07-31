@extends('layouts.app')

@section('title', 'Tambah Karyawan')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('pegawai.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1 mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>
        <h1 class="text-2xl font-bold text-slate-900">Tambah Data Karyawan</h1>
        <p class="text-slate-500 text-sm mt-1">Masukkan informasi detail karyawan baru ke dalam sistem.</p>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <form action="{{ route('pegawai.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- NIP -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">NIP (Nomor Induk Pegawai) <span class="text-red-500">*</span></label>
                    <input type="text" name="nip" required class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors" placeholder="Contoh: EMP-001">
                </div>

                <!-- Nama -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" required class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors" placeholder="Masukkan nama lengkap">
                </div>

                <!-- Departemen -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Departemen <span class="text-red-500">*</span></label>
                    <select name="departemen" required class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 bg-white transition-colors">
                        <option value="">Pilih Departemen</option>
                        <option value="Engineering">Engineering</option>
                        <option value="Human Resources">Human Resources</option>
                        <option value="Finance">Finance</option>
                        <option value="Marketing">Marketing</option>
                        <option value="General">General</option>
                    </select>
                </div>

                <!-- Jabatan -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Jabatan <span class="text-red-500">*</span></label>
                    <input type="text" name="jabatan" required class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors" placeholder="Contoh: Senior Frontend Developer">
                </div>
            </div>
            
            <hr class="border-slate-100 mb-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="md:col-span-2">
                    <h3 class="text-sm font-bold text-slate-900 mb-2 uppercase tracking-wider">Informasi Akun Login</h3>
                </div>
                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" required class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors" placeholder="Masukkan email aktif">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required minlength="8" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors" placeholder="Minimal 8 karakter">
                </div>
            </div>

            <hr class="border-slate-100 mb-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                @if(auth()->user()->role === 'atasan')
                <div class="md:col-span-2">
                    <h3 class="text-sm font-bold text-slate-900 mb-2 uppercase tracking-wider">Informasi Gaji</h3>
                </div>
                <!-- Gaji Pokok -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Gaji Pokok <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-slate-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="number" name="gaji_pokok" required class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors" placeholder="0">
                    </div>
                </div>

                <!-- Tunjangan -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Total Tunjangan</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-slate-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="number" name="tunjangan" value="0" class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors" placeholder="0">
                    </div>
                </div>
                @endif
                
                <!-- Status -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Status Karyawan <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 bg-white transition-colors">
                        <option value="Aktif">Aktif</option>
                        <option value="Non-Aktif">Non-Aktif</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('pegawai.index') }}" class="px-5 py-2.5 rounded-lg border border-slate-200 text-slate-600 font-medium hover:bg-slate-50 transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-blue-900 text-white font-medium hover:bg-blue-800 transition-colors shadow-sm">Simpan Data Karyawan</button>
            </div>
        </form>
    </div>
</div>
@endsection