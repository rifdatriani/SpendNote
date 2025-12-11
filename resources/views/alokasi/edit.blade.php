@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <h3>Edit Alokasi</h3>

    <div class="card p-4">
        <form action="{{ route('alokasi.update', $alokasi->id) }}" method="POST">
            @csrf

            <label>Nama Alokasi</label>
            <input type="text" name="nama" class="form-control mb-3" value="{{ $alokasi->nama }}" required>

            <label>Jumlah (Rp)</label>
            <input type="number" name="jumlah" class="form-control mb-3" value="{{ $alokasi->jumlah }}" required>

            <label>Kategori (Opsional)</label>
            <input type="text" name="kategori" class="form-control mb-3" value="{{ $alokasi->kategori }}">

            <button class="btn btn-success">Update</button>
        </form>
    </div>

</div>

@endsection
