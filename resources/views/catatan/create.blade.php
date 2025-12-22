@extends('layouts.app')

@section('content')

<div class="container alokasi-wrapper">

    {{-- =======================
        JUDUL HALAMAN
    ======================== --}}
    <div class="mb-4">
        <h3>Tambah Transaksi Baru</h3>
        <p>Isi data transaksi dengan lengkap, termasuk kategori, tipe, dan nominal.</p>
    </div>

    {{-- =======================
        FORM INPUT TRANSAKSI
    ======================== --}}
    <div class="card-box mb-4">
        <form action="{{ route('catatan.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                {{-- Tanggal --}}
                <div class="col-md-3">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control"
                        value="{{ old('tanggal', date('Y-m-d')) }}">
                </div>

                {{-- Kategori --}}
                <div class="col-md-3">
                    <label for="kategori" class="form-label">Kategori</label>
                    <select name="kategori" class="form-select">
                        <option value="">Pilih kategori</option>
                        <option value="Makan" {{ old('kategori')=='Makan' ? 'selected' : '' }}>Makan</option>
                        <option value="Belanja" {{ old('kategori')=='Belanja' ? 'selected' : '' }}>Belanja</option>
                        <option value="Dana Darurat" {{ old('kategori')=='Dana Darurat' ? 'selected' : '' }}>Dana Darurat</option>
                        <option value="Transport" {{ old('kategori')=='Transport' ? 'selected' : '' }}>Transport</option>
                        <option value="Lainnya" {{ old('kategori')=='Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                {{-- Subkategori --}}
                <div class="col-md-3">
                    <label for="subkategori" class="form-label">Subkategori (Opsional)</label>
                    <input type="text" name="subkategori" class="form-control" value="{{ old('subkategori') }}">
                </div>

                {{-- Tipe --}}
                <div class="col-md-3">
                    <label for="tipe" class="form-label">Tipe</label>
                    <select name="tipe" class="form-select">
                        <option value="pengeluaran" {{ old('tipe')=='pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                        <option value="pemasukan" {{ old('tipe')=='pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                    </select>
                </div>

                {{-- Nominal --}}
                <div class="col-md-3">
                    <label for="nominal" class="form-label">Nominal (Rp)</label>
                    <input type="number" name="nominal" class="form-control" value="{{ old('nominal') }}">
                </div>

                {{-- Keterangan --}}
                <div class="col-md-6">
                    <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                    <textarea name="keterangan" class="form-control" rows="1">{{ old('keterangan') }}</textarea>
                </div>

                {{-- Tombol Submit --}}
                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                </div>
            </div>
        </form>
    </div>

    {{-- =======================
        TABEL TRANSAKSI TERBARU (OPSIONAL)
    ======================== --}}
    <div class="card-box">
        <h4>Daftar Transaksi Terbaru</h4>
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
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
    </div>

</div>

@endsection
