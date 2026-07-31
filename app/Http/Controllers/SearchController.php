<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Penggajian;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        
        $pegawais = collect();
        $penggajians = collect();

        if ($query) {
            $pegawais = Pegawai::where('nama', 'like', "%{$query}%")
                ->orWhere('nip', 'like', "%{$query}%")
                ->orWhere('jabatan', 'like', "%{$query}%")
                ->get();
                
            $penggajians = Penggajian::whereHas('pegawai', function($q) use ($query) {
                $q->where('nama', 'like', "%{$query}%");
            })
            ->orWhere('bulan_tahun', 'like', "%{$query}%")
            ->get();
        }

        return view('search.index', compact('query', 'pegawais', 'penggajians'));
    }
}
