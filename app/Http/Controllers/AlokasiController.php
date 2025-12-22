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
    public function index()
    {
        $userId = Auth::id();

        $salary = Alokasi::where('user_id', $userId)
            ->where('nama_alokasi', 'Gaji')
            ->where('tipe', 'pemasukan')
            ->latest()
            ->value('jumlah') ?? 0;

        $plans = PembagianPlan::where('user_id', $userId)->get();

        return view('alokasi.index', compact('salary','plans'));
    }

    public function storePlan(Request $request)
    {
        // simpan gaji + simpan pembagian
    }
}
