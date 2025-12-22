<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Catatan;
use App\Models\DanaDarurat;

class CatatanController extends Controller
{
    /**
     * Tampilkan daftar catatan transaksi dengan filter bulan
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $bulan = $request->bulan; // null jika pilih "Semua Bulan"
        $tahun = date('Y');

        // Query catatan transaksi user
        $catatanQuery = Catatan::where('user_id', $userId)
            ->whereYear('tanggal', $tahun);

        if ($bulan) {
            $catatanQuery->whereMonth('tanggal', $bulan);
        }

        $transactions = $catatanQuery->orderBy('tanggal', 'desc')->get();

        // === Tambahkan query Dana Darurat ===
        $dana = DanaDarurat::where('user_id', $userId)
                ->orderBy('tanggal', 'desc')
                ->get();

        return view('catatan.index', compact('transactions', 'bulan', 'dana'));
    }


    /**
     * Tampilkan form tambah transaksi baru
     */
    public function create()
    {
        $userId = Auth::id();

        $transactions = Catatan::where('user_id', $userId)
            ->orderBy('tanggal', 'desc')
            ->take(20)
            ->get();

        return view('catatan.create', compact('transactions'));
    }

    /**
     * Simpan transaksi baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|string',
            'subkategori' => 'nullable|string',
            'tipe' => 'required|in:pemasukan,pengeluaran',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $userId = Auth::id();

        // Simpan ke catatan
        $catatan = Catatan::create([
            'user_id' => $userId,
            'kategori' => $request->kategori,
            'subkategori' => $request->subkategori,
            'tipe' => $request->tipe,
            'nominal' => $request->nominal,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);

         // Jika kategori Dana Darurat dan tipe pengeluaran, otomatis top-up ke DanaDarurat
        if(strtolower($request->kategori) === 'dana darurat' && $request->tipe === 'pengeluaran') {
            DanaDarurat::create([
                'user_id' => $userId,
                'tanggal' => $request->tanggal,
                'nominal' => $request->nominal,
                'status' => 'Pemasukan',
                'keterangan' => 'Top-up dari catatan',
                'total' => DanaDarurat::where('user_id', $userId)->sum('nominal') + $request->nominal,
            ]);
        }

        return redirect()->route('catatan.index')
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    /**
     * Update transaksi
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|string',
            'subkategori' => 'nullable|string',
            'tipe' => 'required|in:pemasukan,pengeluaran',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $catatan = Catatan::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $catatan->update([
            'kategori' => $request->kategori,
            'subkategori' => $request->subkategori,
            'tipe' => $request->tipe,
            'nominal' => $request->nominal,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('catatan.index')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }


    /**
     * Hapus transaksi
     */
    public function destroy($id)
    {
        $catatan = Catatan::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $catatan->delete();

        return redirect()->route('catatan.index')->with('success', 'Catatan berhasil dihapus!');
    }
}
