<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Catatan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use DateTime;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id(); // 🔹 Ambil user yang sedang login

        // ======================
        // FILTER BULAN
        // ======================
        $bulan  = $request->bulan; // 🔹 dari ?bulan= di URL
        $tahun  = date('Y');

        // ======================
        // QUERY DASAR CATATAN
        // ======================
        $catatanQuery = Catatan::where('user_id', $userId)
            ->whereYear('tanggal', $tahun);

        if ($bulan) {
            $catatanQuery->whereMonth('tanggal', $bulan);
        }

        // ======================
        // RINGKASAN KEUANGAN (KARTU ATAS)
        // ======================
        $pemasukan   = (clone $catatanQuery)->where('tipe', 'pemasukan')->sum('nominal');
        $pengeluaran = (clone $catatanQuery)->where('tipe', 'pengeluaran')->sum('nominal');

        $data = [
            'pemasukan'   => $pemasukan,   // 🔹 dipakai di card pemasukan
            'pengeluaran' => $pengeluaran, // 🔹 dipakai di card pengeluaran
            'saldo'       => $pemasukan - $pengeluaran, // 🔹 total saldo
        ];

        // ======================
        // 5 TRANSAKSI TERBARU
        // ======================
        $catatan_terbaru = (clone $catatanQuery)
            ->latest('tanggal')
            ->take(5)
            ->get(); // 🔹 dipakai di sidebar kanan

        // ======================
        // DATA GRAFIK GARIS
        // ======================
        $labels = [];
        $grafik_pemasukan = [];
        $grafik_pengeluaran = [];

        if ($bulan) {
            // 🔹 MODE HARIAN (kalau filter bulan aktif)
            $daysInMonth = Carbon::create($tahun, $bulan)->daysInMonth;

            for ($d = 1; $d <= $daysInMonth; $d++) {
                $labels[] = 'Tgl ' . $d;

                $grafik_pemasukan[] = Catatan::where('user_id', $userId)
                    ->where('tipe', 'pemasukan')
                    ->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan)
                    ->whereDay('tanggal', $d)
                    ->sum('nominal');

                $grafik_pengeluaran[] = Catatan::where('user_id', $userId)
                    ->where('tipe', 'pengeluaran')
                    ->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan)
                    ->whereDay('tanggal', $d)
                    ->sum('nominal');
            }
        } else {
            // 🔹 MODE BULANAN (tanpa filter)
            for ($i = 1; $i <= 12; $i++) {
                $labels[] = DateTime::createFromFormat('!m', $i)->format('M');

                $grafik_pemasukan[] = Catatan::where('user_id', $userId)
                    ->where('tipe', 'pemasukan')
                    ->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $i)
                    ->sum('nominal');

                $grafik_pengeluaran[] = Catatan::where('user_id', $userId)
                    ->where('tipe', 'pengeluaran')
                    ->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $i)
                    ->sum('nominal');
            }
        }

        // ======================
        // DONUT DISTRIBUSI PENGELUARAN
        // ======================
        $donut = Catatan::where('user_id', $userId)
            ->where('tipe', 'pengeluaran') // 🔴 PENTING: cuma pengeluaran
            ->when($bulan, fn($q) => $q->whereMonth('tanggal', $bulan))
            ->selectRaw('kategori, SUM(nominal) as total')
            ->groupBy('kategori')
            ->get();

        $donut_labels = $donut->pluck('kategori'); // 🔹 contoh: Makan, Main, Bensin
        $donut_data   = $donut->pluck('total');    // 🔹 nominal per kategori

        // ======================
        // KIRIM KE VIEW
        // ======================
        return view('dashboard.home', [
            // CARD
            'data' => $data,

            // LIST
            'catatan_terbaru' => $catatan_terbaru,

            // LINE CHART
            'labels' => $labels,
            'grafik_pemasukan' => $grafik_pemasukan,
            'grafik_pengeluaran' => $grafik_pengeluaran,

            // DONUT
            'donut_labels' => $donut_labels,
            'donut_data' => $donut_data,

            // FILTER
            'bulan' => $bulan,
        ]);
    }
}
