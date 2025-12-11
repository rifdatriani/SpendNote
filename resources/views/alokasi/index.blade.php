@extends('layouts.app')

@section('content')

<div class="container alokasi-wrapper">

    {{-- =======================
        BOX 1 — INPUT GAJI + PREVIEW PLAN
    ======================== --}}
    <div class="card-box mb-4">

        <div class="row">

            {{-- INPUT GAJI --}}
            <div class="col-md-5">
                <div class="salary-box">
                    <button class="btn-input-salary w-100 mb-3">
                        Input Gaji dan Alokasi Bulanan
                    </button>

                    <div class="card-custom blue-shadow text-center salary-display-box">
                        <p>Saldo Bulan Ini</p>
                        <h2>Rp {{ number_format($salary,0,',','.') }}</h2>
                    </div>

                </div>
            </div>

            {{-- PREVIEW PLAN --}}
            <div class="col-md-7">
                <div class="plan-preview">
                    <h4 class="mb-3">Preview Plan Alokasi Gaji</h4>

                    <div class="plan-table-box">
                        <table class="table table-bordered plan-table">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th>Nominal</th>
                                    <th width="80">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                            @foreach ($plans as $p)
                                <tr>
                                    <td>{{ $p->kategori }}</td>
                                    <td>Rp {{ number_format($p->nominal,0,',','.') }}</td>
                                    <td>
                                        <a href="{{ route('plan.edit', $p->id) }}" class="edit-btn">✎</a>

                                        <form action="{{ route('plan.destroy', $p->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="delete-btn">🗑</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>



    {{-- =======================
        BOX 2 — TABEL TRANSAKSI
    ======================== --}}
    <div class="card-box mb-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Catatan Pengeluaran / Transaksi</h4>
            <button class="btn-add">+ Data</button>
        </div>

        <table class="table table-bordered transaksi-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Subkategori</th>
                    <th>Tipe</th>
                    <th>Nominal</th>
                    <th>Keterangan</th>
                    <th width="80">Aksi</th>
                </tr>
            </thead>

            <tbody>
            @foreach ($transactions as $i => $t)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $t->tanggal }}</td>
                    <td>{{ $t->kategori }}</td>
                    <td>{{ $t->subkategori }}</td>
                    <td>{{ $t->tipe }}</td>
                    <td>Rp {{ number_format($t->nominal,0,',','.') }}</td>
                    <td>{{ $t->keterangan }}</td>
                    <td>
                        <a href="#" class="edit-btn">✎</a>
                        <a href="#" class="delete-btn">🗑</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>



    {{-- =======================
        BOX 3 — DANA DARURAT
    ======================== --}}
    <div class="card-box">

        <div class="row">

            <div class="col-md-6">
                <h4>Dana Darurat</h4>

                <table class="table table-bordered dana-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nominal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach ($dana as $i => $d)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $d->tanggal }}</td>
                            <td>Rp {{ number_format($d->nominal,0,',','.') }}</td>
                            <td>✎</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="col-md-6 d-flex align-items-center justify-content-center">
                <p class="quote-text fs-4 text-center">
                    Catatan kecilmu adalah investasi besar
                </p>
            </div>

        </div>

    </div>

</div>

@endsection
