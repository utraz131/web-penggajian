@extends('layouts.app')

@section('title', 'Employees')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Daftar Karyawan</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola data dan status karyawan aktif maupun non-aktif.</p>
        </div>
        <div class="flex gap-2">
            @if(auth()->user()->role === 'atasan')
            <!-- Tombol Modal Import -->
            <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Import CSV
            </button>
            <a href="{{ route('pegawai.create') }}" class="inline-flex items-center justify-center bg-blue-900 hover:bg-blue-800 text-white font-medium py-2.5 px-4 rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Karyawan
            </a>
            @endif
        </div>
    </div>

    <!-- Filters & Table Container -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <!-- Filters -->
        <div class="p-4 border-b border-slate-200 flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex-1 flex flex-col md:flex-row gap-4 w-full">
                <!-- Search -->
                <div class="relative w-full md:w-72">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" class="block w-full pl-9 pr-3 py-2 border border-slate-200 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari nama atau jabatan...">
                </div>
                <!-- Select Departemen -->
                <select class="w-full md:w-48 py-2 px-3 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 bg-white">
                    <option>Semua Departemen</option>
                    <option>Engineering</option>
                    <option>Human Resources</option>
                    <option>Finance</option>
                </select>
                <!-- Select Status -->
                <select class="w-full md:w-40 py-2 px-3 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 bg-white">
                    <option>Semua Status</option>
                    <option>Aktif</option>
                    <option>Non-Aktif</option>
                </select>
            </div>
            
            <div class="flex items-center gap-2">
                <button class="p-2 border border-slate-200 rounded-lg text-slate-500 hover:bg-slate-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                </button>
                <button class="p-2 border border-slate-200 rounded-lg text-slate-500 hover:bg-slate-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Karyawan</th>
                        <th class="px-6 py-4 font-semibold">Departemen</th>
                        <th class="px-6 py-4 font-semibold">Jabatan</th>
                        @if(auth()->user()->role === 'atasan')
                        <th class="px-6 py-4 font-semibold">Gaji Pokok</th>
                        @endif
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pegawais as $pegawai)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded bg-blue-100 text-blue-800 flex items-center justify-center font-bold text-sm shrink-0">
                                    {{ substr($pegawai->nama, 0, 2) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900">{{ $pegawai->nama }}</div>
                                    <div class="text-xs text-slate-500">{{ $pegawai->nip }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $pegawai->departemen }}</td>
                        <td class="px-6 py-4 text-slate-600">
                            <div>{{ $pegawai->jabatan }}</div>
                        </td>
                        @if(auth()->user()->role === 'atasan')
                        <td class="px-6 py-4 font-medium text-slate-900">
                            Rp {{ number_format($pegawai->gaji_pokok, 0, ',', '.') }}
                        </td>
                        @endif
                        <td class="px-6 py-4">
                            @if($pegawai->status == 'Aktif')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold border border-slate-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Non-Aktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('pegawai.edit', $pegawai->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form action="{{ route('pegawai.destroy', $pegawai->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            Belum ada data karyawan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination (Mock) -->
        <div class="p-4 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between text-sm text-slate-500">
            <div>Menampilkan 1 hingga {{ count($pegawais) }} dari {{ count($pegawais) }} data</div>
            <div class="mt-4 sm:mt-0 flex items-center gap-1">
                <button class="px-3 py-1.5 border border-slate-200 rounded bg-white text-slate-500 hover:bg-slate-50 disabled:opacity-50" disabled>Sebelumnya</button>
                <button class="w-8 h-8 flex items-center justify-center rounded bg-blue-900 text-white font-medium">1</button>
                <button class="px-3 py-1.5 border border-slate-200 rounded bg-white text-slate-500 hover:bg-slate-50 disabled:opacity-50" disabled>Selanjutnya</button>
            </div>
        </div>
    </div>
    </div>
</div>

<!-- Modal Import CSV -->
<div id="importModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-bold text-slate-900 text-lg">Import Data Karyawan</h3>
            <button onclick="document.getElementById('importModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="{{ route('pegawai.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-6">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Pilih File CSV Dataset</label>
                    <input type="file" name="file" accept=".csv" required
                           class="block w-full text-sm text-slate-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-full file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-blue-50 file:text-blue-700
                                  hover:file:bg-blue-100">
                    <p class="mt-2 text-xs text-slate-500">Pastikan Anda menyimpan file Excel Anda dalam format CSV (Comma delimited).</p>
                </div>
            </div>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-100 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition-colors">
                    Mulai Import
                </button>
            </div>
        </form>
    </div>
</div>
@endsection