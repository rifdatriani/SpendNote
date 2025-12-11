@extends('layouts.app')

@section('content')

<div class="container dashboard-wrapper">

    {{-- BAGIAN JUDUL + FILTER --}}
    <div class="d-flex justify-content-between align-items-center">

        <h3 class="dashboard-title">Dashboard Keuangan</h3>

        {{-- FILTER BULAN --}}
        <form action="{{ route('dashboard') }}" method="GET">
            <div class="d-flex align-items-center">

                <select name="bulan" class="form-select me-2" style="width: 180px;">
                    <option value="">Semua Bulan</option>

                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                        </option>
                    @endfor

                </select>

                <button class="btn btn-dark">Filter</button>

            </div>
        </form>

    </div>

    {{-- BUTTON ALOKASI PENDAPATAN --}}
    <div class="mt-3">
        <a href="{{ route('alokasi.index') }}" class="btn btn-primary" style="border-radius: 8px; padding: 8px 18px;">
            Alokasi Pendapatan
        </a>
    </div>

    {{-- INFO BULAN (OPSIONAL) --}}
    @if(request('bulan'))
        <p class="text-muted mt-2">
            Menampilkan data bulan:
            <strong>{{ DateTime::createFromFormat('!m', request('bulan'))->format('F') }}</strong>
        </p>
    @endif

    {{-- KARTU SALDO - PEMASUKAN - PENGELUARAN --}}
    <div class="row mt-3 mb-4">

        <div class="col-md-4">
            <div class="card-custom blue-shadow">
                <p>Pemasukan Bulan Ini</p>
                <h2>Rp {{ number_format($data['pemasukan'],0,',','.') }}</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-custom red-shadow">
                <p>Pengeluaran Bulan Ini</p>
                <h2>Rp {{ number_format($data['pengeluaran'],0,',','.') }}</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-custom green-shadow">
                <p>Total Saldo</p>
                <h2>Rp {{ number_format($data['saldo'],0,',','.') }}</h2>
            </div>
        </div>

    </div>

    {{-- GRAFIK & RIWAYAT --}}
    <div class="row mt-3">

        <div class="col-md-8">
            <div class="card-custom chart-card">
                <h5>Tren Keuangan</h5>
                <canvas id="chartTren"></canvas>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-custom">
                <h5>5 Transaksi Terbaru</h5>

                @foreach ($alokasi_terbaru as $a)
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span>{{ $a->nama_alokasi }}</span>
                    <span class="text-primary">Rp {{ number_format($a->jumlah,0,',','.') }}</span>
                </div>
                @endforeach

                <a href="{{ route('alokasi.index') }}" class="btn-primary-custom mt-3">Lihat Semua</a>
            </div>
        </div>

    </div>

    {{-- DONUT --}}
    <div class="card-custom mt-4">
        <h5>Distribusi Pengeluaran</h5>
        <canvas id="chartDonut"></canvas>
    </div>

</div>

@endsection
