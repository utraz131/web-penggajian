@extends('layouts.app')

@section('title', 'Pegawai Dashboard')

@section('content')
<!-- Dashboard Content Container (bg-slate-50 from layout usually) -->
<div class="max-w-7xl mx-auto font-sans text-slate-800">
    
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
    </div>

    @if(!$pegawai)
    <div class="bg-red-50 border border-red-200 text-red-700 p-5 rounded-lg flex gap-4 shadow-sm">
        <svg class="w-6 h-6 shrink-0 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <div>
            <h4 class="font-bold mb-1">Akun Belum Tertaut</h4>
            <p class="text-sm">Akun login kamu belum dikaitkan dengan profil karyawan (NIP) manapun oleh Admin HR. Silakan hubungi tim HR.</p>
        </div>
    </div>
    @else
    
    <!-- Real-time Clock -->
    <div class="mb-4 text-slate-600 font-bold flex items-center gap-2">
        <svg class="w-5 h-5 text-[#42b8a3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span id="realtime-clock" class="text-xl tracking-wider">--:--:--</span>
        <span class="text-sm text-slate-400 font-medium ml-1" id="realtime-date"></span>
    </div>

    <!-- Banner Absensi Terpisah -->
    <div class="bg-gradient-to-r from-[#42b8a3] to-[#2a8775] rounded-xl shadow-lg p-6 mb-6 flex flex-col md:flex-row items-center justify-between text-white relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
        <div class="relative z-10 flex items-center gap-4 mb-4 md:mb-0">
            <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h2 class="text-xl font-bold">Portal Absensi Mandiri</h2>
                <p class="text-teal-50 text-sm opacity-90">Jangan lupa untuk mengambil absen masuk dan pulang setiap hari kerja.</p>
            </div>
        </div>
        <div class="relative z-10 shrink-0">
            @if($absenHariIni && $absenHariIni->waktu_masuk && $absenHariIni->waktu_keluar)
                <div class="px-6 py-3 bg-white/20 text-white font-bold rounded-lg border border-white/30 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Selesai Absen Hari Ini
                </div>
            @elseif($absenHariIni && $absenHariIni->waktu_masuk)
                <a href="{{ route('absensi.create') }}" class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-lg shadow-lg shadow-orange-500/30 transition-all hover:-translate-y-0.5 flex items-center gap-2 group">
                    <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Ambil Absen Pulang
                </a>
            @else
                <div class="relative group">
                    <div class="absolute -inset-1 bg-white rounded-lg blur opacity-40 group-hover:opacity-100 transition duration-1000 group-hover:duration-200 animate-pulse"></div>
                    <a href="{{ route('absensi.create') }}" class="relative px-6 py-3 bg-white hover:bg-slate-50 text-teal-700 font-bold rounded-lg shadow-lg shadow-black/5 transition-all hover:-translate-y-0.5 flex items-center gap-2 group animate-pulse">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        Ambil Absen Masuk
                        <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-3 w-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                        </span>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Top Cards Grid (Now 3 cards) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        
        <!-- Card 1: Total Gaji -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 flex flex-col justify-center items-center text-center">
            <div class="flex items-center gap-3 w-full justify-center mb-2">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-500 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="text-sm font-semibold text-slate-500">Total Diterima</div>
            </div>
            <div class="text-2xl font-bold text-slate-900">Rp {{ number_format($totalGajiTahunIni, 0, ',', '.') }}</div>
            <div class="text-[10px] text-emerald-500 font-bold mt-1 bg-emerald-50 px-2 py-0.5 rounded flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                Tahun {{ date('Y') }}
            </div>
        </div>

        <!-- Card 2: Kehadiran -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 flex flex-col justify-center items-center text-center">
            <div class="flex items-center gap-3 w-full justify-center mb-2">
                <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-500 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div class="text-sm font-semibold text-slate-500">Kehadiran</div>
            </div>
            <div class="text-2xl font-bold text-slate-900">{{ $absenHariIni && $absenHariIni->waktu_masuk ? 'Hadir' : 'Belum Absen' }}</div>
            <div class="text-[10px] text-slate-500 font-medium mt-1">Hari ini</div>
        </div>

        <!-- Card 3: Status -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 flex flex-col justify-center items-center text-center">
            <div class="flex items-center gap-3 w-full justify-center mb-2">
                <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="text-sm font-semibold text-slate-500">Status Pegawai</div>
            </div>
            <div class="text-2xl font-bold text-slate-900">{{ $pegawai->status }}</div>
            <div class="text-[10px] text-teal-600 font-bold mt-1 bg-teal-50 px-2 py-0.5 rounded flex items-center gap-1">
                Aktif
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Chart Area (Left side - 2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-100 p-5">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-bold text-slate-800">Grafik Kehadiran (Hadir)</h2>
                <div class="flex gap-2">
                    @foreach(array_slice($chartData, -3) as $data)
                    <span class="text-xs font-semibold text-slate-400">{{ $data['label'] }}</span>
                    @endforeach
                </div>
            </div>
            
            <div class="h-64 flex items-end justify-around gap-2 px-4 pb-4 border-b border-l border-slate-200 relative">
                <!-- Grid lines -->
                <div class="absolute w-full border-t border-slate-100 top-0"></div>
                <div class="absolute w-full border-t border-slate-100 top-1/4"></div>
                <div class="absolute w-full border-t border-slate-100 top-2/4"></div>
                <div class="absolute w-full border-t border-slate-100 top-3/4"></div>

                <!-- Bars (Dynamic based on data) -->
                @foreach($chartData as $data)
                @php
                    $percentage = ($data['count'] / $maxChartCount) * 100;
                    if ($data['count'] == 0) $percentage = 0;
                    else $percentage = max(5, $percentage); // min height 5% for visibility if not 0
                @endphp
                <div class="w-12 bg-[#8bcbbd] rounded-t-sm relative z-10 hover:bg-[#57a895] transition-colors group" style="height: {{ $percentage }}%;">
                    <!-- tooltip -->
                    <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-slate-800 text-white text-[10px] font-bold py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap">
                        {{ $data['count'] }} Kehadiran
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="flex justify-around mt-3 text-xs font-semibold text-slate-500">
                @foreach($chartData as $data)
                <span class="w-12 text-center">{{ $data['label'] }}</span>
                @endforeach
            </div>
        </div>

        <!-- Right Side List (Menu Top Harian style) -->
        <div class="flex flex-col gap-6">
            <!-- Statistik Kinerja Ringkas -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5">
                <div class="flex justify-between items-center mb-2">
                    <h2 class="text-base font-bold text-slate-800">Kedisiplinan Bulan Ini</h2>
                    <div class="text-[#57a895] bg-teal-50 px-2 py-0.5 rounded text-xs font-bold">{{ \Carbon\Carbon::now()->isoFormat('MMM Y') }}</div>
                </div>
                <div class="flex items-end gap-3 mt-4">
                    <div class="text-4xl font-black {{ $persentaseTepatWaktu >= 90 ? 'text-emerald-500' : ($persentaseTepatWaktu >= 75 ? 'text-orange-500' : 'text-red-500') }}">{{ $persentaseTepatWaktu }}%</div>
                    <div class="pb-1 text-sm font-semibold text-slate-500">Tepat Waktu</div>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2 mt-4 overflow-hidden">
                    <div class="h-2 rounded-full {{ $persentaseTepatWaktu >= 90 ? 'bg-emerald-500' : ($persentaseTepatWaktu >= 75 ? 'bg-orange-500' : 'bg-red-500') }}" style="width: {{ $persentaseTepatWaktu }}%"></div>
                </div>
                <p class="text-xs text-slate-500 mt-3 text-center">Persentase kehadiran tepat waktu (sebelum jam 08:00) dari total absensi bulan ini.</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-base font-bold text-slate-800">Riwayat Absensi</h2>
                <button class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path></svg>
                </button>
            </div>
            
            <div class="space-y-4">
                @forelse(collect($riwayatAbsen ?? [])->take(4) as $index => $absen)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="font-bold text-slate-400 text-sm">{{ $index + 1 }}.</div>
                        <div>
                            <div class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($absen->tanggal)->isoFormat('D MMM Y') }}</div>
                            <div class="text-xs text-slate-500">Masuk: {{ $absen->waktu_masuk ?? '--:--' }}</div>
                        </div>
                    </div>
                    @php
                        if ($absen->status == 'Hadir') {
                            $statusStyle = 'bg-[#57a895] text-white';
                        } elseif ($absen->status == 'Alfa') {
                            $statusStyle = 'bg-red-100 text-red-600';
                        } elseif (in_array($absen->status, ['Cuti', 'Izin', 'Sakit'])) {
                            $statusStyle = 'bg-orange-100 text-orange-600';
                        } else {
                            $statusStyle = 'bg-slate-100 text-slate-600';
                        }
                    @endphp
                    <div class="px-2 py-1 rounded {{ $statusStyle }} text-[10px] font-bold">
                        {{ $absen->status }}
                    </div>
                </div>
                @empty
                <div class="text-center text-sm text-slate-500 py-4">Belum ada riwayat absen.</div>
                @endforelse
            </div>
        </div>
        </div>

        <!-- Bottom Left (Produk Terfavorit style) -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-100 p-5">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-base font-bold text-slate-800">Slip Gaji Terakhir</h2>
                <div class="flex gap-4 text-xs font-bold">
                    <a href="{{ route('penggajian.index') }}" class="text-[#57a895] border-b-2 border-[#57a895] pb-1">Terbaru</a>
                </div>
            </div>
            
            @if($slipTerakhir)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                <div class="flex items-start gap-4 p-3 border border-slate-50 rounded-lg hover:bg-slate-50 transition-colors">
                    <div class="w-16 h-12 bg-slate-200 rounded shrink-0 flex items-center justify-center text-slate-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-800">Periode {{ $slipTerakhir->bulan_tahun }}</h4>
                        <div class="text-[#57a895] font-bold text-sm mt-1">Rp {{ number_format($slipTerakhir->total_gaji, 0, ',', '.') }}</div>
                        <a href="{{ route('penggajian.show', $slipTerakhir->id) }}" class="text-[10px] text-slate-400 underline mt-1 block">Lihat Detail</a>
                    </div>
                </div>
            </div>
            @else
            <div class="text-center text-sm text-slate-500 py-8">Belum ada data penggajian.</div>
            @endif
        </div>

        <!-- Bottom Right Pie Chart (Mock) -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-base font-bold text-slate-800">Proporsi Gaji</h2>
                <button class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
            
            <div class="flex justify-center my-6">
                <!-- Pure CSS Pie Chart matching reference -->
                <div class="w-32 h-32 rounded-full" style="background: conic-gradient(#3a7b70 0% 30%, #57a895 30% 70%, #8bcbbd 70% 100%);"></div>
            </div>
            
            <div class="space-y-2 mt-4">
                <div class="flex items-center gap-2 text-xs text-slate-600 font-medium">
                    <div class="w-3 h-3 bg-[#57a895] rounded-sm"></div> Gaji Pokok
                </div>
                <div class="flex items-center gap-2 text-xs text-slate-600 font-medium">
                    <div class="w-3 h-3 bg-[#8bcbbd] rounded-sm"></div> Tunjangan
                </div>
                <div class="flex items-center gap-2 text-xs text-slate-600 font-medium">
                    <div class="w-3 h-3 bg-[#3a7b70] rounded-sm"></div> Potongan (Pajak, dll)
                </div>
            </div>
        </div>
        
    </div>

    @endif
</div>

<script>
    function updateClock() {
        const now = new Date();
        
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const dateStr = now.toLocaleDateString('id-ID', options);
        
        const clockEl = document.getElementById('realtime-clock');
        const dateEl = document.getElementById('realtime-date');
        
        if(clockEl && dateEl) {
            clockEl.textContent = `${hours}:${minutes}:${seconds}`;
            dateEl.textContent = dateStr;
        }
    }
    
    setInterval(updateClock, 1000);
    updateClock();
</script>
@endsection

