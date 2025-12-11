@extends('layouts.app')

@section('content')

<div class="container mt-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Dashboard Keuangan</h3>
    </div>

    {{-- Tombol Alokasi diletakkan di bawah Dashboard Keuangan --}}
    <div class="mb-4">
        <a href="{{ route('alokasi.index') }}" class="btn btn-primary">
            Alokasi Pendapatan
        </a>
    </div>

    {{-- RINGKASAN ATAS --}}
    <div class="row">

        {{-- Total Saldo --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm p-3 border-success">
                <p class="text-success fw-bold">Total Saldo Saat Ini</p>
                <h3 class="text-success fw-bold">Rp {{ number_format($data['saldo'],0,',','.') }}</h3>
            </div>
        </div>

        {{-- Pemasukan Bulan Ini --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm p-3 border-primary">
                <p class="text-primary fw-bold">Pemasukan Bulan Ini</p>
                <h3 class="text-primary fw-bold">Rp {{ number_format($data['pemasukan'],0,',','.') }}</h3>
            </div>
        </div>

        {{-- Pengeluaran Bulan Ini --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm p-3 border-danger">
                <p class="text-danger fw-bold">Pengeluaran Bulan Ini</p>
                <h3 class="text-danger fw-bold">Rp {{ number_format($data['pengeluaran'],0,',','.') }}</h3>
            </div>
        </div>

    </div>

    <div class="row mt-4">

        {{-- GRAFIK TREN BULANAN --}}
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm p-3">
                <h5>Tren Bulanan</h5>
                <canvas id="chartTren"></canvas>
            </div>
        </div>

        {{-- 5 Transaksi Terbaru --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm p-3">
                <h5>5 Transaksi Terbaru</h5>

                @foreach ($alokasi_terbaru as $a)
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>{{ $a->nama_alokasi }}</span>
                        <span class="text-primary">
                            Rp {{ number_format($a->jumlah,0,',','.') }}
                        </span>
                    </div>
                @endforeach

                <a href="{{ route('alokasi.index') }}" class="mt-3 btn btn-outline-primary w-100">
                    Lihat Semua Alokasi
                </a>
            </div>
        </div>

    </div>

    {{-- DISTRIBUSI PENGELUARAN --}}
    <div class="card shadow-sm p-3 mt-3">
        <h5>Distribusi Pengeluaran</h5>
        <canvas id="chartDonut"></canvas>
    </div>

</div>

@endsection
