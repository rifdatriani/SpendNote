<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DanaDarurat;

class DanaDaruratController extends Controller
{
    /**
     * Tampilkan daftar Dana Darurat dengan filter bulan
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $bulan = $request->bulan; // null = semua bulan
        $tahun = date('Y');

        // Query Dana Darurat user
        $query = DanaDarurat::where('user_id', $userId)
                            ->whereYear('tanggal', $tahun);

        if($bulan) {
            $query->whereMonth('tanggal', $bulan);
        }

        $dana = $query->orderBy('tanggal', 'desc')->get();

        // Hitung saldo
        $saldo = DanaDarurat::where('user_id', $userId)
                    ->where('status', 'Pemasukan')->sum('nominal') -
                 DanaDarurat::where('user_id', $userId)
                    ->where('status', 'Pengeluaran')->sum('nominal');

        return view('dana.index', compact('dana', 'saldo', 'bulan'));
    }

    /**
     * Form input Dana Darurat baru (Pemasukan)
     */
    public function create()
    {
        return view('dana.create');
    }

    /**
     * Simpan Dana Darurat baru (Pemasukan)
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'status' => 'required|in:Pemasukan,Pengeluaran',
            'keterangan' => 'nullable|string',
        ]);

        $userId = Auth::id();

        // Hitung saldo sebelumnya
        $saldo = DanaDarurat::where('user_id', $userId)
                    ->where('status', 'Pemasukan')->sum('nominal') -
                 DanaDarurat::where('user_id', $userId)
                    ->where('status', 'Pengeluaran')->sum('nominal');

        // Pastikan pengeluaran tidak melebihi saldo
        if($request->status == 'Pengeluaran' && $request->nominal > $saldo) {
            return redirect()->route('dashboard')->withErrors(['nominal' => 'Nominal melebihi saldo Dana Darurat.'])->withInput();
        }

        // Simpan transaksi
        DanaDarurat::create([
            'user_id' => $userId,
            'tanggal' => $request->tanggal,
            'nominal' => $request->nominal,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'total' => $request->status == 'Pemasukan' ? $saldo + $request->nominal : $saldo - $request->nominal,
        ]);

        return redirect()->route('dana.index')->with('success', 'Dana Darurat berhasil dicatat.');
    }

    /**
     * Form edit Dana Darurat
     */
    public function edit($id)
    {
        $dana = DanaDarurat::where('user_id', Auth::id())
                    ->where('id', $id)
                    ->firstOrFail();

        return view('dana.edit', compact('dana'));
    }

    /**
     * Update Dana Darurat
     */
    public function update(Request $request, $id)
    {
        $dana = DanaDarurat::where('user_id', Auth::id())
                    ->where('id', $id)
                    ->firstOrFail();

        $request->validate([
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'status' => 'required|in:Pemasukan,Pengeluaran',
            'keterangan' => 'nullable|string',
        ]);

        // Hitung saldo termasuk transaksi ini
        $userId = Auth::id();
        $saldo = DanaDarurat::where('user_id', $userId)
                    ->where('status', 'Pemasukan')->sum('nominal') -
                 DanaDarurat::where('user_id', $userId)
                    ->where('status', 'Pengeluaran')->sum('nominal') +
                 ($dana->status == 'Pengeluaran' ? $dana->nominal : 0);

        // Validasi jika pengeluaran
        if($request->status == 'Pengeluaran' && $request->nominal > $saldo) {
            return redirect()->route('dashboard')->withErrors(['nominal' => 'Nominal melebihi saldo Dana Darurat.'])->withInput();
        }

        // Update transaksi
        $dana->update([
            'tanggal' => $request->tanggal,
            'nominal' => $request->nominal,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'total' => $request->status == 'Pemasukan' ? $saldo + $request->nominal : $saldo - $request->nominal,
        ]);

        return redirect()->route('dana.index')->with('success', 'Dana Darurat berhasil diperbarui.');
    }

    /**
     * Hapus Dana Darurat
     */
    public function destroy($id)
    {
        $dana = DanaDarurat::where('user_id', Auth::id())
                    ->where('id', $id)
                    ->firstOrFail();

        $dana->delete();

        return redirect()->route('dashboard')->with('success', 'Catatan berhasil dihapus!');
    }
}
