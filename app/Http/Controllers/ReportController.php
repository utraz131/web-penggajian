<?php

namespace App\Http\Controllers;

use App\Models\Penggajian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Grup data berdasarkan bulan_tahun untuk membuat ringkasan bulanan
        $reports = Penggajian::select(
            'bulan_tahun',
            DB::raw('count(id) as total_karyawan'),
            DB::raw('sum(gaji_pokok + tunjangan) as total_kotor'),
            DB::raw('sum(potongan_absen + potongan_bpjs + potongan_pajak) as total_potongan'),
            DB::raw('sum(total_gaji) as total_bersih')
        )
        ->groupBy('bulan_tahun')
        ->orderByRaw('MAX(created_at) desc')
        ->get();

        return view('reports.index', compact('reports'));
    }

    public function exportCsv()
    {
        $reports = Penggajian::select(
            'bulan_tahun',
            DB::raw('count(id) as total_karyawan'),
            DB::raw('sum(gaji_pokok + tunjangan) as total_kotor'),
            DB::raw('sum(potongan_absen + potongan_bpjs + potongan_pajak) as total_potongan'),
            DB::raw('sum(total_gaji) as total_bersih')
        )
        ->groupBy('bulan_tahun')
        ->orderByRaw('MAX(created_at) desc')
        ->get();

        $filename = "laporan_penggajian_" . date('Ymd') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Periode', 'Total Karyawan', 'Total Kotor (Rp)', 'Total Potongan (Rp)', 'Total Bersih (Rp)'];

        $callback = function() use($reports, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($reports as $row) {
                fputcsv($file, [
                    $row->bulan_tahun,
                    $row->total_karyawan,
                    $row->total_kotor,
                    $row->total_potongan,
                    $row->total_bersih
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
