@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <h3>Alokasi Pendapatan</h3>

    <a href="{{ route('alokasi.create') }}" class="btn btn-primary mb-3">+ Tambah Alokasi</a>

    <div class="card p-3">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Jumlah</th>
                    <th>Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($data as $a)
                <tr>
                    <td>{{ $a->nama }}</td>
                    <td>Rp {{ number_format($a->jumlah,0,',','.') }}</td>
                    <td>{{ $a->kategori ?? '-' }}</td>
                    <td>
                        <a href="{{ route('alokasi.edit', $a->id) }}" class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('alokasi.destroy', $a->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus alokasi?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

</div>

@endsection
