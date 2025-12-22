@extends('layouts.app')

@section('content')
@php
$warnaBulan = [
    '#2E7D32', // Jan
    '#388E3C', // Feb
    '#43A047', // Mar
    '#558B2F', // Apr
    '#6D4C41', // Mei
    '#00897B', // Jun
    '#1565C0', // Jul
    '#283593', // Agu
    '#4527A0', // Sep
    '#6A1B9A', // Okt
    '#AD1457', // Nov
    '#C62828', // Des
];
@endphp

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
        <!-- <a href="{{ route('alokasi.index') }}" class="btn btn-primary" style="border-radius: 8px; padding: 8px 18px;">
            Alokasi Pendapatan
        </a> -->
    
        <a href="{{ route('catatan.index') }}" class="btn btn-primary" style="border-radius: 8px; padding: 8px 18px;">
            Catatan Pengeluaran
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

                @foreach ($catatan_terbaru as $c)
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span>
                        {{ $c->kategori }}
                        @if($c->subkategori)/ {{ $c->subkategori }} @endif
                    </span>

                    <span class="{{ $c->tipe == 'pemasukan' ? 'text-success' : 'text-danger' }}">
                        {{ $c->tipe == 'pemasukan' ? '+' : '-' }}
                        Rp {{ number_format($c->nominal,0,',','.') }}
                    </span>
                </div>
                @endforeach

                <a href="{{ route('catatan.index') }}" class="btn-primary-custom mt-3">Lihat Semua</a>
            </div>
        </div>
    </div>

    {{-- DONUT --}}
    {{-- DONUT --}}
    <div class="card-custom mt-4">
        <h5>Distribusi Pengeluaran</h5>

        <div class="donut-wrapper">
            <canvas id="chartDonut"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const labels = @json($labels);
const pemasukan = @json($grafik_pemasukan);
const pengeluaran = @json($grafik_pengeluaran);

const isBulanan = {{ $bulan ? 'true' : 'false' }};
const warnaBulan = @json($warnaBulan);

// Warna garis
const warnaPemasukan = isBulanan
    ? '#2E7D32'   // hijau solid
    : warnaBulan.map(w => w);

const warnaPengeluaran = isBulanan
    ? '#ff0000ff'   // merah solid
    : warnaBulan.map(w => w.replace('#', '#FF')); // tone merah beda tiap bulan

new Chart(document.getElementById('chartTren'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Pemasukan',
                data: pemasukan,
                borderColor: warnaPemasukan,
                backgroundColor: 'transparent',
                tension: 0.4
            },
            {
                label: 'Pengeluaran',
                data: pengeluaran,
                borderColor: warnaPengeluaran,
                backgroundColor: 'transparent',
                tension: 0.4
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        },
        scales: {
            y: {
                ticks: {
                    callback: value => 'Rp ' + value.toLocaleString('id-ID')
                }
            }
        }
    }
});
</script>

<script>
/* =========================
   DATA DARI CONTROLLER
========================= */
const donutLabels = @json($donut_labels); // kategori
const donutData = @json($donut_data);     // total nominal

/* =========================
   CHART DONUT
========================= */
new Chart(document.getElementById('chartDonut'), {
    type: 'doughnut',
    data: {
        labels: donutLabels,
        datasets: [{
            data: donutData,
            backgroundColor: [
                '#EF5350', '#66BB6A', '#42A5F5',
                '#FFA726', '#AB47BC', '#26A69A'
            ]
        }]
    },
    options: {
        plugins: {
            legend: { position: 'right' },
            tooltip: {
                callbacks: {
                    label: function(ctx) {
                        let total = donutData.reduce((a,b)=>a+b,0);
                        let persen = ((ctx.raw / total) * 100).toFixed(1);
                        return `${ctx.label}: Rp ${ctx.raw.toLocaleString('id-ID')} (${persen}%)`;
                    }
                }
            }
        }
    }
});
</script>


@endsection