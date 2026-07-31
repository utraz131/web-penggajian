<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    // Menampilkan tabel data pegawai
    public function index()
    {
        $pegawais = Pegawai::all();
        return view('pegawai.index', compact('pegawais'));
    }

    // Menampilkan form tambah pegawai
    public function create()
    {
        return view('pegawai.create');
    }

    // Menyimpan data pegawai baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|string|unique:pegawais',
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'departemen' => 'required|string',
            'jabatan' => 'required|string',
            'status' => 'required|string',
        ]);

        $data = [
            'nip'        => $request->nip,
            'nama'       => $request->nama,
            'departemen' => $request->departemen,
            'jabatan'    => $request->jabatan,
            'status'     => $request->status,
            'gaji_pokok' => $request->gaji_pokok ?? 0,
            'tunjangan'  => $request->tunjangan ?? 0,
        ];

        $pegawai = Pegawai::create($data);

        // Buat Akun User untuk Pegawai
        \App\Models\User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'pegawai',
            'pegawai_id' => $pegawai->id,
        ]);

        return redirect()->route('pegawai.index')->with('success', 'Karyawan dan akun berhasil ditambahkan');
    }

    public function edit(Pegawai $pegawai)
    {
        return view('pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $data = [
            'nip'        => $request->nip,
            'nama'       => $request->nama,
            'departemen' => $request->departemen,
            'jabatan'    => $request->jabatan,
            'status'     => $request->status,
        ];

        // Hanya Atasan yang bisa mengubah gaji
        if (auth()->user()->role === 'atasan') {
            $data['gaji_pokok'] = $request->gaji_pokok ?? 0;
            $data['tunjangan']  = $request->tunjangan ?? 0;
        }

        $pegawai->update($data);

        return redirect()->route('pegawai.index')->with('success', 'Karyawan berhasil diupdate');
    }

    public function destroy(Pegawai $pegawai)
    {
        $pegawai->delete();
        return redirect()->route('pegawai.index')->with('success', 'Karyawan berhasil dihapus');
    }

    // Mengimport data pegawai dari file CSV
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        $file = $request->file('file');
        
        $fileHandle = fopen($file->getPathname(), 'r');
        
        // Skip header
        $header = fgetcsv($fileHandle, 1000, ',');
        
        // Cek jika delimiter ;
        if (count($header) <= 1) {
            fclose($fileHandle);
            $fileHandle = fopen($file->getPathname(), 'r');
            $header = fgetcsv($fileHandle, 1000, ';');
        }

        while (($row = fgetcsv($fileHandle, 1000, (count($header) > 1 && strpos(file_get_contents($file->getPathname()), ';') !== false) ? ';' : ',')) !== FALSE) {
            if (count($row) < 8) continue; // Pastikan data minimal terisi

            // Skip baris TOTAL atau baris kosong
            if (empty(trim($row[0])) || strtoupper(trim($row[1])) === 'TOTAL' || strtoupper(trim($row[3])) === 'TOTAL' || strtoupper(trim($row[0])) === 'TOTAL') {
                continue;
            }

            // Pembersihan string Rp dan koma untuk angka
            $gajiPokok = (int) preg_replace('/[^0-9]/', '', $row[4]);
            $tunjangan = (int) preg_replace('/[^0-9]/', '', $row[7]);

            Pegawai::updateOrCreate(
                ['nip' => $row[0]], // Cari berdasarkan ID_Pegawai (NIP)
                [
                    'nama' => $row[1],
                    'departemen' => $row[2],
                    'jabatan' => $row[3],
                    'gaji_pokok' => $gajiPokok,
                    'tunjangan' => $tunjangan,
                    'status' => 'Aktif',
                ]
            );
        }

        fclose($fileHandle);

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil diimpor!');
    }
}