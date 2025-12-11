@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <h3>Akun Saya</h3>
    <p>Kelola informasi akun Anda</p>

    <div class="card p-4">
        <p><b>Nama:</b> {{ $user->name }}</p>
        <p><b>Email:</b> {{ $user->email }}</p>

        <a href="{{ route('akun.edit') }}" class="btn btn-primary">Edit Akun</a>

        {{-- Logout pindah ke sini --}}
        <form action="{{ route('logout') }}" method="POST" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-danger w-100">Logout</button>
        </form>
    </div>

</div>

@endsection
