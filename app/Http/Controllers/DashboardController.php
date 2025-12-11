<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alokasi;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        $bulan  = $request->bulan; // dari dropdown filter

        // === BASE QUERY ===
        $alokasiQuery = Alokasi::where('user_id', $userId);

        // === FILTER BULAN ===
        if ($bulan) {
            $alokasiQuery->whereMonth('created_at', $bulan);
        }

        // === PEMASUKAN ===
        $pemasukan = (clone $alokasiQuery)
                        ->where('tipe', 'pemasukan')
                        ->sum('jumlah');

        // === PENGELUARAN ===
        $pengeluaran = (clone $alokasiQuery)
                        ->where('tipe', 'pengeluaran')
                        ->sum('jumlah');

        // === SALDO ===
        $saldo = $pemasukan - $pengeluaran;

        // === 5 ALOKASI TERBARU ===
        $alokasi_terbaru = (clone $alokasiQuery)
                            ->latest()
                            ->limit(5)
                            ->get();

        // === DATA GRAFIK (tidak dipengaruhi filter, tetap 12 bulan) ===
        $bulan_arr = [];
        $grafik_pemasukan = [];
        $grafik_pengeluaran = [];

        for ($i = 1; $i <= 12; $i++) {
            $bulan_arr[] = date("F", mktime(0,0,0,$i,1));

            $grafik_pemasukan[] = Alokasi::where('user_id', $userId)
                ->where('tipe', 'pemasukan')
                ->whereMonth('created_at', $i)
                ->sum('jumlah');

            $grafik_pengeluaran[] = Alokasi::where('user_id', $userId)
                ->where('tipe', 'pengeluaran')
                ->whereMonth('created_at', $i)
                ->sum('jumlah');
        }

        // === DONUT ===
        $kategori = Alokasi::where('user_id', $userId)
                            ->groupBy('nama_alokasi')
                            ->pluck('nama_alokasi');

        $jumlah_per_kategori = Alokasi::where('user_id', $userId)
                            ->selectRaw('nama_alokasi, SUM(jumlah) as total')
                            ->groupBy('nama_alokasi')
                            ->pluck('total');

        return view('dashboard.home', [
            'data' => [
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran,
                'saldo' => $saldo,
            ],
            'alokasi_terbaru' => $alokasi_terbaru,
            'bulan' => $bulan_arr,
            'grafik_pemasukan' => $grafik_pemasukan,
            'grafik_pengeluaran' => $grafik_pengeluaran,
            'kategori' => $kategori,
            'jumlah_per_kategori' => $jumlah_per_kategori
        ]);
    }
}
