<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alokasi;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Hitung saldo
        $pemasukan = Alokasi::where('user_id', $userId)
                            ->where('tipe', 'pemasukan')
                            ->sum('jumlah');

        $pengeluaran = Alokasi::where('user_id', $userId)
                            ->where('tipe', 'pengeluaran')
                            ->sum('jumlah');

        $saldo = $pemasukan - $pengeluaran;

        // Ambil 5 data terbaru
        $alokasi_terbaru = Alokasi::where('user_id', $userId)
                                    ->latest()
                                    ->take(5)
                                    ->get();

        // Data grafik bulanan
        $bulan = [];
        $grafik_pemasukan = [];
        $grafik_pengeluaran = [];

        for ($i = 1; $i <= 12; $i++) {
            $bulan[] = date("F", mktime(0,0,0,$i,10));

            $grafik_pemasukan[] = Alokasi::where('user_id', $userId)
                ->where('tipe', 'pemasukan')
                ->whereMonth('created_at', $i)
                ->sum('jumlah');

            $grafik_pengeluaran[] = Alokasi::where('user_id', $userId)
                ->where('tipe', 'pengeluaran')
                ->whereMonth('created_at', $i)
                ->sum('jumlah');
        }

        // Donut kategori
        $kategori = Alokasi::where('user_id', $userId)
                            ->groupBy('nama_alokasi')
                            ->pluck('nama_alokasi');

        $jumlah_per_kategori = Alokasi::where('user_id', $userId)
                            ->selectRaw('nama_alokasi, SUM(jumlah) as total')
                            ->groupBy('nama_alokasi')
                            ->pluck('total');

        // PENTING: arahkan ke home.blade.php
        return view('dashboard.home', [
            'data' => [
                'saldo' => $saldo,
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran,
            ],
            'alokasi_terbaru' => $alokasi_terbaru,
            'bulan' => $bulan,
            'grafik_pemasukan' => $grafik_pemasukan,
            'grafik_pengeluaran' => $grafik_pengeluaran,
            'kategori' => $kategori,
            'jumlah_per_kategori' => $jumlah_per_kategori
        ]);
    }
}
