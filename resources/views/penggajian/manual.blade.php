@extends('layouts.app')

@section('title', 'Input Gaji Manual')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('penggajian.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1">
            &larr; Kembali ke Riwayat
        </a>
    </div>

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Input Gaji Manual</h1>
        <p class="text-slate-500 text-sm mt-1">Masukkan rincian gaji karyawan secara manual.</p>
    </div>

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif
    
    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <form action="{{ route('penggajian.storeManual') }}" method="POST" id="manual-form">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Pilih Karyawan <span class="text-red-500">*</span></label>
                    <select name="pegawai_id" id="pegawai_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" required>
                        <option value="">-- Pilih Karyawan --</option>
                        @foreach($pegawais as $p)
                            <option value="{{ $p->id }}" data-gaji="{{ $p->gaji_pokok }}" data-tunjangan="{{ $p->tunjangan }}">{{ $p->nip }} - {{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Periode (Bulan & Tahun) <span class="text-red-500">*</span></label>
                    <input type="month" name="bulan_tahun" id="bulan_tahun" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required value="{{ $default_month ?? date('Y-m') }}">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Jumlah Hadir (Hari) <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_hadir" id="jumlah_hadir" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm calc-input" required value="0" min="0">
                </div>
            </div>

            <hr class="my-6 border-slate-200">
            <h3 class="font-bold text-slate-800 mb-4">Pendapatan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Gaji Pokok (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="gaji_pokok" id="gaji_pokok" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm calc-input" required value="0" min="0">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Tunjangan (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="tunjangan" id="tunjangan" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm calc-input" required value="0" min="0">
                </div>
            </div>

            <hr class="my-6 border-slate-200">
            <h3 class="font-bold text-slate-800 mb-4">Potongan</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Potongan Absen (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="potongan_absen" id="potongan_absen" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm calc-input" required value="0" min="0">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Potongan BPJS (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="potongan_bpjs" id="potongan_bpjs" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm calc-input" required value="0" min="0">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Potongan Pajak (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="potongan_pajak" id="potongan_pajak" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm calc-input" required value="0" min="0">
                </div>
            </div>

            <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 flex justify-between items-center mb-8">
                <span class="font-bold text-blue-900">Total Take Home Pay</span>
                <div class="text-right">
                    <span class="text-sm text-blue-600 font-medium">Rp</span>
                    <span class="text-2xl font-bold text-blue-700" id="total_display">0</span>
                    <input type="hidden" name="total_gaji" id="total_gaji" value="0">
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('penggajian.index') }}" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-blue-900 hover:bg-blue-800 text-white font-bold rounded-lg shadow-sm transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Gaji
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const selectPegawai = document.getElementById('pegawai_id');
        const inGajiPokok = document.getElementById('gaji_pokok');
        const inTunjangan = document.getElementById('tunjangan');
        const inJumlahHadir = document.getElementById('jumlah_hadir');
        const inputBulanTahun = document.getElementById('bulan_tahun');
        
        function fetchAttendanceStats() {
            const pegawai_id = selectPegawai.value;
            const bulan_tahun = inputBulanTahun.value;
            
            if (pegawai_id && bulan_tahun) {
                fetch(`/penggajian/manual/stats?pegawai_id=${pegawai_id}&bulan_tahun=${bulan_tahun}`)
                    .then(response => response.json())
                    .then(data => {
                        inJumlahHadir.value = data.jumlah_hadir || 0;
                        calculateTotal();
                    })
                    .catch(err => console.error(err));
            } else {
                inJumlahHadir.value = 0;
                calculateTotal();
            }
        }

        // Auto-fill Gaji Pokok & Tunjangan when selecting pegawai
        selectPegawai.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const gp = selectedOption.getAttribute('data-gaji') || 0;
                const tunj = selectedOption.getAttribute('data-tunjangan') || 0;
                inGajiPokok.value = gp;
                inTunjangan.value = tunj;
            } else {
                inGajiPokok.value = 0;
                inTunjangan.value = 0;
            }
            fetchAttendanceStats();
        });

        inputBulanTahun.addEventListener('change', fetchAttendanceStats);

        // Calculate Total
        const calcInputs = document.querySelectorAll('.calc-input');
        const totalDisplay = document.getElementById('total_display');
        const totalGajiInput = document.getElementById('total_gaji');

        function calculateTotal() {
            const gp = parseFloat(inGajiPokok.value) || 0;
            const tunj = parseFloat(inTunjangan.value) || 0;
            const potAbsen = parseFloat(document.getElementById('potongan_absen').value) || 0;
            const potBpjs = parseFloat(document.getElementById('potongan_bpjs').value) || 0;
            const potPajak = parseFloat(document.getElementById('potongan_pajak').value) || 0;

            const total = (gp + tunj) - (potAbsen + potBpjs + potPajak);
            const finalTotal = total > 0 ? total : 0;

            totalGajiInput.value = finalTotal;
            totalDisplay.textContent = new Intl.NumberFormat('id-ID').format(finalTotal);
        }

        calcInputs.forEach(input => {
            input.addEventListener('input', calculateTotal);
        });
    });
</script>
@endsection
