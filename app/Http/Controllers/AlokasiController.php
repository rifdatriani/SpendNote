<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Alokasi;
use App\Models\PembagianPlan;
use App\Models\DanaDarurat;
use Carbon\Carbon;
use DB;

class AlokasiController extends Controller
{
    /**
     * Dashboard Alokasi
     */
    public function index()
    {
        $userId = Auth::id();

        // Ambil salary bulan ini
        $salary = Alokasi::where('user_id', $userId)
            ->where('nama_alokasi', 'Gaji')
            ->where('tipe', 'pemasukan')
            ->orderBy('tanggal', 'desc')
            ->value('jumlah') ?? 0;

        // semua plan
        $plans = PembagianPlan::where('user_id', $userId)->get();

        // transaksi terbaru
        $transactions = Alokasi::where('user_id', $userId)
            ->orderBy('tanggal','desc')
            ->orderBy('id','desc')
            ->take(100)
            ->get();

        // 5 terbaru
        $alokasi_terbaru = Alokasi::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        // dana darurat
        $dana = DanaDarurat::where('user_id', $userId)
            ->orderBy('created_at','desc')
            ->get();

        $totalDanaDarurat = DanaDarurat::where('user_id', $userId)->sum('jumlah');

        return view('alokasi/index', compact(
            'salary','plans','transactions','alokasi_terbaru','dana','totalDanaDarurat'
        ));
    }


    /**
     * Simpan plan pembagian gaji.
     * Tidak duplicate gaji per bulan.
     */
    public function storePlan(Request $request)
    {
        $request->validate([
            'salary' => 'required|numeric|min:0',
            'allocations' => 'required|array',
        ]);

        $userId = Auth::id();
        $bulan = Carbon::now()->format('Y-m'); // contoh: 2025-12

        // ====================================================
        // 1. Simpan atau update plan (CRUD-ready)
        // ====================================================
        foreach ($request->allocations as $alloc) {
            if (!empty($alloc['nama_kebutuhan']) && isset($alloc['nominal'])) {
                PembagianPlan::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'nama_kebutuhan' => $alloc['nama_kebutuhan']
                    ],
                    [
                        'nominal' => intval($alloc['nominal'])
                    ]
                );
            }
        }

        // ====================================================
        // 2. Cek apakah gaji bulan ini sudah tercatat
        // ====================================================
        $existingGaji = Alokasi::where('user_id', $userId)
            ->where('nama_alokasi', 'Gaji')
            ->where('tipe', 'pemasukan')
            ->where('tanggal', 'like', "$bulan%")
            ->first();

        // Jika belum ada → simpan pemasukan gaji
        if (!$existingGaji) {
            Alokasi::create([
                'user_id' => $userId,
                'nama_alokasi' => 'Gaji',
                'subkategori' => 'Plan Gaji',
                'tipe' => 'pemasukan',
                'jumlah' => intval($request->salary),
                'tanggal' => Carbon::now()->toDateString(),
                'keterangan' => 'Pemasukan gaji dari plan',
            ]);
        }

        return redirect()->route('alokasi.index')
            ->with('success', 'Plan berhasil disimpan. Gaji bulan ini tidak berubah.');
    }

    /**
     * Simpan transaksi lain
     */
    public function storeTransaction(Request $request)
    {
        $request->validate([
            'nama_alokasi' => 'required|string',
            'jumlah' => 'required|numeric',
            'tipe' => 'required|in:pemasukan,pengeluaran',
            'tanggal' => 'nullable|date',
            'subkategori' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $userId = Auth::id();
        $tanggal = $request->tanggal ?: Carbon::now()->toDateString();

        DB::beginTransaction();
        try {
            $alok = Alokasi::create([
                'user_id' => $userId,
                'nama_alokasi' => $request->nama_alokasi,
                'subkategori' => $request->subkategori,
                'tipe' => $request->tipe,
                'jumlah' => intval($request->jumlah),
                'tanggal' => $tanggal,
                'keterangan' => $request->keterangan,
            ]);

            // Jika alokasi = Dana Darurat
            if (strtolower($request->nama_alokasi) === 'dana darurat'
                || strtolower($request->nama_alokasi) === 'dana_darurat'
                || strtolower($request->nama_alokasi) === 'darurat') {

                $dt = Carbon::parse($tanggal);
                $day = (int)$dt->day;
                $minggu = (int) ceil($day / 7);
                $bulan = $dt->format('F');

                $prevTotal = DanaDarurat::where('user_id', $userId)->sum('jumlah');

                $newJumlah = intval($request->jumlah);
                $newTotal = $prevTotal + $newJumlah;

                DanaDarurat::create([
                    'user_id' => $userId,
                    'bulan' => $bulan,
                    'minggu' => $minggu,
                    'status' => 'Pemasukan',
                    'sumber' => 'Pengeluaran - Dana Darurat',
                    'jumlah' => $newJumlah,
                    'total' => $newTotal,
                ]);
            }

            DB::commit();
            return redirect()->route('alokasi.index')->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('alokasi.index')->with('error', 'Gagal menyimpan transaksi: '.$e->getMessage());
        }
    }

    /**
     * Edit transaksi
     */
    public function edit($id)
    {
        $alok = Alokasi::findOrFail($id);
        $this->authorizeUser($alok);

        return view('alokasi_edit', compact('alok'));
    }

    /**
     * Update transaksi
     */
    public function update(Request $request, $id)
    {
        $alok = Alokasi::findOrFail($id);
        $this->authorizeUser($alok);

        $request->validate([
            'nama_alokasi' => 'required|string',
            'jumlah' => 'required|numeric',
            'tipe' => 'required|in:pemasukan,pengeluaran',
            'tanggal' => 'nullable|date',
            'subkategori' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $alok->update($request->only([
            'nama_alokasi','subkategori','tipe',
            'jumlah','tanggal','keterangan'
        ]));

        return redirect()->route('alokasi.index')->with('success', 'Transaksi berhasil diupdate.');
    }

    /**
     * Hapus transaksi
     */
    public function destroy($id)
    {
        $alok = Alokasi::findOrFail($id);
        $this->authorizeUser($alok);

        $alok->delete();

        return redirect()->route('alokasi.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    /**
     * Akses hanya untuk user yg punya datanya
     */
    private function authorizeUser(Alokasi $alok)
    {
        if ($alok->user_id != Auth::id()) {
            abort(403);
        }
    }
}
