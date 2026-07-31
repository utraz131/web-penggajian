@extends('layouts.app')

@section('title', 'Absensi Kamera')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-slate-900">Kehadiran Harian</h1>
        <p class="text-slate-500 text-sm mt-1">Silakan ambil foto selfie untuk melakukan absensi.</p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
        
        <!-- Status Absensi -->
        <div class="mb-6 flex gap-4 justify-center">
            <div class="px-4 py-3 rounded-lg border {{ ($absensiHariIni && $absensiHariIni->waktu_masuk) ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-slate-50 border-slate-200 text-slate-500' }} text-center">
                <div class="text-xs uppercase font-bold tracking-wider mb-1">Masuk</div>
                <div class="text-lg font-semibold">{{ ($absensiHariIni && $absensiHariIni->waktu_masuk) ? $absensiHariIni->waktu_masuk : '--:--:--' }}</div>
            </div>
            <div class="px-4 py-3 rounded-lg border {{ ($absensiHariIni && $absensiHariIni->waktu_keluar) ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-slate-50 border-slate-200 text-slate-500' }} text-center">
                <div class="text-xs uppercase font-bold tracking-wider mb-1">Pulang</div>
                <div class="text-lg font-semibold">{{ ($absensiHariIni && $absensiHariIni->waktu_keluar) ? $absensiHariIni->waktu_keluar : '--:--:--' }}</div>
            </div>
        </div>

        @if($absensiHariIni && $absensiHariIni->waktu_masuk && $absensiHariIni->waktu_keluar)
            <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl text-center border border-emerald-200 font-medium">
                Anda sudah menyelesaikan absensi hari ini. Terima kasih!
            </div>
        @else
            <!-- Camera UI -->
            <div class="relative w-full max-w-md mx-auto aspect-[3/4] bg-slate-900 rounded-xl overflow-hidden shadow-inner mb-6">
                <video id="camera-stream" autoplay playsinline class="w-full h-full object-cover transform scale-x-[-1]"></video>
                <canvas id="canvas" class="hidden"></canvas>
                <img id="photo-preview" class="hidden w-full h-full object-cover transform scale-x-[-1]" />
                
                <!-- Loading state -->
                <div id="camera-loading" class="absolute inset-0 flex items-center justify-center text-white bg-slate-900">
                    <svg class="animate-spin h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </div>
            </div>

            <!-- Controls -->
            <div class="flex justify-center gap-4">
                @if(!$absensiHariIni || !$absensiHariIni->waktu_masuk)
                    <button id="btn-absen-masuk" class="px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white font-bold rounded-xl shadow-md transition-all flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Absen Masuk
                    </button>
                @elseif(!$absensiHariIni->waktu_keluar)
                    <button id="btn-absen-pulang" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl shadow-md transition-all flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Absen Pulang
                    </button>
                @endif
            </div>
            
            <form id="absen-form" class="hidden">
                @csrf
                <input type="hidden" id="tipe-absen" name="tipe">
                <input type="hidden" id="image-data" name="image">
            </form>
        @endif
    </div>
</div>

@if(!($absensiHariIni && $absensiHariIni->waktu_masuk && $absensiHariIni->waktu_keluar))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const video = document.getElementById('camera-stream');
        const canvas = document.getElementById('canvas');
        const photoPreview = document.getElementById('photo-preview');
        const loading = document.getElementById('camera-loading');
        const btnMasuk = document.getElementById('btn-absen-masuk');
        const btnPulang = document.getElementById('btn-absen-pulang');
        
        let stream = null;
        let currentLat = -6.175392; // Dummy mock
        let currentLng = 106.827153; // Dummy mock

        // Start GPS
        function getLocation() {
            // Disabled for demo purposes so it won't trigger GPS errors
            console.log("GPS check bypassed for demo");
        }

        getLocation();

        // Start Camera
        async function startCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { facingMode: "user" },
                    audio: false 
                });
                video.srcObject = stream;
                video.onloadedmetadata = () => {
                    loading.classList.add('hidden');
                };
            } catch (err) {
                console.error("Error accessing camera: ", err);
                alert("Tidak dapat mengakses kamera. Pastikan Anda telah memberikan izin kamera.");
                loading.innerHTML = '<span class="text-sm">Kamera tidak tersedia</span>';
            }
        }

        startCamera();

        // Capture photo
        function takeSnapshot() {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            
            // Mirror image processing
            ctx.translate(canvas.width, 0);
            ctx.scale(-1, 1);
            
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Get base64 Data URL
            const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
            return dataUrl;
        }

        async function submitAbsen(tipe) {
            const btn = tipe === 'masuk' ? btnMasuk : btnPulang;
            btn.disabled = true;
            btn.innerHTML = 'Memproses...';

            if (!currentLat || !currentLng) {
                alert("Menunggu sinyal GPS atau akses lokasi ditolak. Pastikan izin lokasi aktif lalu coba lagi.");
                btn.disabled = false;
                btn.innerHTML = tipe === 'masuk' ? 'Absen Masuk' : 'Absen Pulang';
                // Coba ambil lokasi lagi
                getLocation();
                return;
            }

            // Ambil foto
            const imageData = takeSnapshot();
            
            // Tampilkan preview foto (freeze frame)
            video.classList.add('hidden');
            photoPreview.src = imageData;
            photoPreview.classList.remove('hidden');
            
            // Kirim ke server
            try {
                const response = await fetch('{{ route('absensi.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({
                        tipe: tipe,
                        image: imageData,
                        latitude: currentLat,
                        longitude: currentLng
                    })
                });
                
                const result = await response.json();
                if (result.success) {
                    sessionStorage.setItem('absen_success_msg', result.message);
                    window.location.href = "{{ route('dashboard') ?? '/' }}";
                } else {
                    alert(result.message || 'Terjadi kesalahan.');
                    // reset camera view if failed
                    video.classList.remove('hidden');
                    photoPreview.classList.add('hidden');
                    btn.disabled = false;
                    btn.innerHTML = tipe === 'masuk' ? 'Absen Masuk' : 'Absen Pulang';
                }
            } catch (err) {
                console.error(err);
                alert('Gagal mengirim data.');
                btn.disabled = false;
                btn.innerHTML = tipe === 'masuk' ? 'Absen Masuk' : 'Absen Pulang';
            }
        }

        if(btnMasuk) {
            btnMasuk.addEventListener('click', () => submitAbsen('masuk'));
        }
        if(btnPulang) {
            btnPulang.addEventListener('click', () => submitAbsen('keluar'));
        }
        
        // Stop camera on page leave
        window.addEventListener('beforeunload', () => {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
        });
    });
</script>
@endif
@endsection
