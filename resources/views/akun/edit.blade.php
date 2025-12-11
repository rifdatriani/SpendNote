@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <h3>Edit Akun</h3>

    <div class="card p-4">
        <form action="{{ route('akun.update') }}" method="POST">
            @csrf

            <label>Nama</label>
            <input type="text" name="name" class="form-control mb-3" value="{{ $user->name }}">

            <label>Email</label>
            <input type="email" name="email" class="form-control mb-3" value="{{ $user->email }}">

            <button class="btn btn-success">Simpan Perubahan</button>
        </form>
    </div>

</div>

@endsection
