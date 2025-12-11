@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <h3>Tambah Alokasi Pendapatan</h3>

    <div class="card p-4">
        <form action="{{ route('alokasi.store') }}" method="POST">
            @csrf

            <label>Nama Alokasi</label>
            <input type="text" name="nama" class="form-control mb-3" required>

            <label>Jumlah (Rp)</label>
            <input type="number" name="jumlah" class="form-control mb-3" required>

            <label>Kategori (Opsional)</label>
            <input type="text" name="kategori" class="form-control mb-3">

            <button class="btn btn-success">Simpan</button>
        </form>
    </div>

</div>

@endsection
