@extends('layouts.app')

@section('title', 'Kalender Kehadiran')

@section('content')
<div class="max-w-7xl mx-auto font-sans text-slate-800" id="kalender-container">
    <!-- Header Page -->
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center bg-white p-4 rounded-xl shadow-sm border border-slate-100 no-print">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <h1 class="text-xl font-bold text-slate-900">Manajemen Absensi</h1>
        </div>
        <div class="mt-4 md:mt-0 flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-lg border border-slate-100 text-sm font-medium text-slate-600">
            <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span id="realtime-date-clock">--/--/---- --:--:--</span>
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-6">
        <div class="flex justify-between items-center mb-6 no-print border-b border-slate-100 pb-4">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <h2 class="text-lg font-bold text-slate-800">Absensi Bulan Ini</h2>
            </div>
            <div class="flex items-center gap-4 text-sm font-semibold text-slate-600">
                <a href="{{ route('kalender.index', ['month' => $currentDate->copy()->subMonth()->month, 'year' => $currentDate->copy()->subMonth()->year]) }}" class="hover:text-blue-600 transition-colors">&larr;</a>
                <span class="w-24 text-center">{{ $currentDate->isoFormat('MMMM Y') }}</span>
                <a href="{{ route('kalender.index', ['month' => $currentDate->copy()->addMonth()->month, 'year' => $currentDate->copy()->addMonth()->year]) }}" class="hover:text-blue-600 transition-colors">&rarr;</a>
            </div>
        </div>

        <div class="mb-6 flex flex-wrap gap-4 text-xs font-semibold text-slate-600 no-print">
            <div class="flex items-center gap-2"><div class="w-2.5 h-2.5 rounded-full bg-green-500"></div> Hadir</div>
            <div class="flex items-center gap-2"><div class="w-2.5 h-2.5 rounded-full bg-orange-500"></div> Telat</div>
            <div class="flex items-center gap-2"><div class="w-2.5 h-2.5 rounded-full bg-red-500"></div> Alpha</div>
            <div class="flex items-center gap-2"><div class="w-2.5 h-2.5 rounded-full bg-blue-500"></div> Izin</div>
            <div class="flex items-center gap-2"><div class="w-2.5 h-2.5 rounded-full bg-yellow-500"></div> Sakit</div>
            <div class="flex items-center gap-2"><div class="w-2.5 h-2.5 rounded-full bg-slate-200"></div> Kosong</div>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 4px;" class="text-center text-xs mb-6">
            <div class="font-medium text-slate-500 py-2">Min</div>
            <div class="font-medium text-slate-500 py-2">Sen</div>
            <div class="font-medium text-slate-500 py-2">Sel</div>
            <div class="font-medium text-slate-500 py-2">Rab</div>
            <div class="font-medium text-slate-500 py-2">Kam</div>
            <div class="font-medium text-slate-500 py-2">Jum</div>
            <div class="font-medium text-slate-500 py-2">Sab</div>
            
            @php
                // Adjust start day of week since calendar usually starts on Sunday (0 or 7).
                // $startDayOfWeek is 1 for Mon, 7 for Sun. We want Sunday first.
                $startDayOfWeekIso = $startDayOfWeek;
                $blankDays = $startDayOfWeekIso == 7 ? 0 : $startDayOfWeekIso;
            @endphp
            
            @for($i = 0; $i < $blankDays; $i++)
                <div class="aspect-square bg-slate-50 border border-slate-100 rounded flex items-center justify-center"></div>
            @endfor
            
            @foreach($kalenderAbsensi ?? [] as $hari)
                @php
                    $bgColor = 'bg-white';
                    $textColor = 'text-slate-600';
                    $border = 'border border-slate-200';
                    $additionalClass = '';
                    
                    if ($hari['status'] == 'hadir') {
                        $bgColor = 'bg-green-500';
                        $textColor = 'text-white';
                        $border = 'border-transparent';
                    } elseif ($hari['status'] == 'telat') {
                        $bgColor = 'bg-orange-500';
                        $textColor = 'text-white';
                        $border = 'border-transparent';
                    } elseif ($hari['status'] == 'alfa') {
                        $bgColor = 'bg-red-500';
                        $textColor = 'text-white';
                        $border = 'border-transparent';
                    } elseif ($hari['status'] == 'izin') {
                        $bgColor = 'bg-blue-500';
                        $textColor = 'text-white';
                        $border = 'border-transparent';
                    } elseif ($hari['status'] == 'sakit') {
                        $bgColor = 'bg-yellow-500';
                        $textColor = 'text-white';
                        $border = 'border-transparent';
                    } elseif ($hari['status'] == 'libur' || $hari['status'] == 'belum') {
                        $bgColor = 'bg-slate-50';
                        $textColor = 'text-slate-400';
                        $border = 'border border-slate-100';
                    }

                    // Highlight today
                    if ($hari['date_str'] == \Carbon\Carbon::today()->toDateString() && $hari['status'] == 'belum') {
                        $border = 'border-2 border-slate-400';
                        $textColor = 'text-slate-800 font-bold';
                    }
                @endphp
                <div onclick="showDayDetails('{{ $hari['tanggal'] }}', '{{ ucfirst($hari['status']) }}', '{{ $hari['waktu_masuk'] }}', '{{ $hari['waktu_keluar'] }}', '{{ addslashes($hari['keterangan']) }}')" 
                     class="aspect-square rounded flex items-center justify-center cursor-pointer transition-transform hover:scale-105 {{ $bgColor }} {{ $textColor }} {{ $border }}">
                    <span class="text-sm font-medium">{{ $hari['tanggal'] }}</span>
                </div>
            @endforeach
        </div>

        <!-- Summary Badges -->
        <div class="flex flex-wrap gap-2 text-xs font-bold text-white no-print mt-2">
            <div class="bg-green-500 px-3 py-1.5 rounded flex items-center gap-1 shadow-sm">
                <span>Hadir:</span> <span>{{ $summary['hadir'] }}</span>
            </div>
            <div class="bg-orange-500 px-3 py-1.5 rounded flex items-center gap-1 shadow-sm">
                <span>Telat:</span> <span>{{ $summary['telat'] }}</span>
            </div>
            <div class="bg-red-500 px-3 py-1.5 rounded flex items-center gap-1 shadow-sm">
                <span>Alpha:</span> <span>{{ $summary['alfa'] }}</span>
            </div>
            <div class="bg-blue-500 px-3 py-1.5 rounded flex items-center gap-1 shadow-sm">
                <span>Izin:</span> <span>{{ $summary['izin'] }}</span>
            </div>
            <div class="bg-yellow-500 px-3 py-1.5 rounded flex items-center gap-1 shadow-sm">
                <span>Sakit:</span> <span>{{ $summary['sakit'] }}</span>
            </div>
        </div>
    </div>

    <!-- Daftar Absensi List Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 no-print">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-slate-100 pb-4 mb-6">
            <h2 class="text-lg font-bold text-slate-800">Daftar Absensi</h2>
            <a href="{{ route('absensi.create') }}" class="mt-4 md:mt-0 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Input Absensi Saya
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-semibold border-b border-slate-200">
                        <th class="py-3 px-4">Karyawan</th>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Jam Masuk</th>
                        <th class="py-3 px-4">Jam Keluar</th>
                        <th class="py-3 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700 divide-y divide-slate-100">
                    @forelse($daftarAbsensi as $absensi)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3 px-4">
                            <div class="font-medium text-slate-900">{{ Auth::user()->pegawai->nama_lengkap ?? Auth::user()->name }}</div>
                            <div class="text-xs text-slate-500">{{ Auth::user()->pegawai->nip ?? '' }}</div>
                        </td>
                        <td class="py-3 px-4 font-medium">{{ \Carbon\Carbon::parse($absensi->tanggal)->isoFormat('D MMMM Y') }}</td>
                        <td class="py-3 px-4">
                            @if($absensi->waktu_masuk)
                                <span class="px-2 py-1 bg-green-50 text-green-700 rounded text-xs font-semibold">{{ substr($absensi->waktu_masuk, 0, 5) }}</span>
                            @else
                                <span class="text-slate-400">--:--</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @if($absensi->waktu_keluar)
                                <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs font-semibold">{{ substr($absensi->waktu_keluar, 0, 5) }}</span>
                            @else
                                <span class="text-slate-400">--:--</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @if($absensi->status == 'Hadir')
                                <span class="px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Hadir</span>
                            @elseif($absensi->status == 'Terlambat' || stripos($absensi->keterangan, 'Terlambat') !== false)
                                <span class="px-2.5 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-semibold">Telat</span>
                            @elseif($absensi->status == 'Alfa')
                                <span class="px-2.5 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">Alfa</span>
                            @else
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-800 rounded-full text-xs font-semibold">{{ $absensi->status }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-500">Tidak ada data absensi di bulan ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #kalender-container, #kalender-container * {
            visibility: visible;
        }
        #kalender-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none !important;
        }
        .print-header {
            display: block !important;
        }
        /* Ensure background colors print */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>

<script>
function updateKalenderClock() {
    const now = new Date();
    const day = String(now.getDate()).padStart(2, '0');
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const year = now.getFullYear();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    const timeStr = `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
    
    const clockEl = document.getElementById('realtime-date-clock');
    if(clockEl) clockEl.textContent = timeStr;
}
setInterval(updateKalenderClock, 1000);
updateKalenderClock();

function showDayDetails(tanggal, status, masuk, keluar, ket) {
    if (status === 'Belum' || status === 'Libur') return;
    
    let htmlContent = '';
    if (status === 'Alfa') {
        htmlContent = `<p class="text-slate-600 mb-2">Anda tidak hadir pada tanggal ini tanpa keterangan.</p>`;
    } else if (status === 'Cuti' || status === 'Izin') {
        htmlContent = `<p class="text-slate-600 mb-2"><strong>Status:</strong> ${status}</p>
                       <p class="text-slate-600"><strong>Alasan:</strong> ${ket || '-'}</p>`;
    } else {
        htmlContent = `<div class="flex justify-around my-4">
                           <div class="text-center">
                               <p class="text-sm text-slate-500 mb-1">Jam Masuk</p>
                               <p class="text-xl font-bold text-slate-800">${masuk}</p>
                           </div>
                           <div class="text-center">
                               <p class="text-sm text-slate-500 mb-1">Jam Pulang</p>
                               <p class="text-xl font-bold text-slate-800">${keluar}</p>
                           </div>
                       </div>
                       ${ket ? `<p class="text-sm text-red-500 mt-2"><strong>Keterangan:</strong> ${ket}</p>` : ''}`;
    }
    
    Swal.fire({
        title: `Detail Kehadiran Tgl ${tanggal}`,
        html: htmlContent,
        icon: status === 'Alfa' ? 'error' : (status === 'Cuti' ? 'info' : 'success'),
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Tutup'
    });
}
</script>
@endsection
