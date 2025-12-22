@extends('layouts.app')

@section('content')

<div class="container alokasi-wrapper">
    {{-- =======================
        NOTIFIKASI
    ======================= --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal!</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- =======================
        FILTER BULAN
    ======================== --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Catatan Pengeluaran / Transaksi</h4>

        <form method="GET" action="{{ route('catatan.index') }}" class="d-flex align-items-center">
            <select name="bulan" class="form-select me-2" style="width: 180px;">
                <option value="">Semua Bulan</option>
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ ($bulan == $i) ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                    </option>
                @endfor
            </select>
            <button class="btn btn-dark">Filter</button>
        </form>
    </div>

    {{-- =======================
        TABEL TRANSAKSI
    ======================== --}}
    <div class="card-box mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('catatan.create') }}" class="btn btn-primary btn-sm">+ Tambah Transaksi</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered transaksi-table mb-0">
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
                @forelse ($transactions as $i => $t)

                <form action="{{ route('catatan.update', $t->id) }}" method="POST">
                @csrf
                @method('PUT')

                <tr>
                    <td>{{ $i + 1 }}</td>

                    <td>
                        <span class="view">{{ $t->tanggal }}</span>
                        <input type="date" name="tanggal" class="edit form-control d-none" value="{{ $t->tanggal }}">
                    </td>

                    <td>
                        <span class="view">{{ $t->kategori }}</span>
                        <input type="text" name="kategori" class="edit form-control d-none" value="{{ $t->kategori }}">
                    </td>

                    <td>
                        <span class="view">{{ $t->subkategori }}</span>
                        <input type="text" name="subkategori" class="edit form-control d-none" value="{{ $t->subkategori }}">
                    </td>

                    <td>
                        <span class="view">{{ ucfirst($t->tipe) }}</span>
                        <select name="tipe" class="edit form-select d-none">
                            <option value="pemasukan" {{ $t->tipe=='pemasukan'?'selected':'' }}>Pemasukan</option>
                            <option value="pengeluaran" {{ $t->tipe=='pengeluaran'?'selected':'' }}>Pengeluaran</option>
                        </select>
                    </td>

                    <td>
                        <span class="view">
                            {{ $t->tipe == 'pemasukan' ? '+' : '-' }}
                            Rp {{ number_format($t->nominal,0,',','.') }}
                        </span>
                        <input type="number" name="nominal" class="edit form-control d-none" value="{{ $t->nominal }}">
                    </td>

                    <td>
                        <span class="view">{{ $t->keterangan }}</span>
                        <input type="text" name="keterangan" class="edit form-control d-none" value="{{ $t->keterangan }}">
                    </td>

                    <td class="text-center">

                        {{-- EDIT --}}
                        <button type="button" class="btn btn-sm btn-warning edit-btn">✎</button>

                        {{-- SAVE --}}
                        <button type="submit" class="btn btn-sm btn-success save-btn d-none">✔</button>

                </form>

                        {{-- DELETE (FORM TERPISAH, AMAN) --}}
                        <form action="{{ route('catatan.destroy', $t->id) }}"
                            method="POST"
                            style="display:inline"
                            onsubmit="return confirm('Yakin hapus transaksi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">🗑</button>
                        </form>

                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="8" class="text-center">Belum ada transaksi</td>
                </tr>
                @endforelse
                </tbody>


            </table>
        </div>
    </div>

    <!-- {{-- =======================
        BOX DANA DARURAT + QUOTE
    ======================== --}}
    <div class="card-box">
        <div class="row align-items-center">

            <div class="col-md-6 mb-3 mb-md-0">
                <h4>Dana Darurat</h4>

                <div class="table-responsive">
                    <table class="table table-bordered dana-table mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nominal</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dana as $i => $d)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $d->tanggal }}</td>
                                    <td>Rp {{ number_format($d->nominal,0,',','.') }}</td>
                                    <td>Rp {{ number_format($d->total,0,',','.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada dana darurat</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-6 d-flex align-items-center justify-content-center">
                <p class="quote-text fs-4 text-center mb-0">
                    Catatan kecilmu adalah investasi besar
                </p>
            </div>

        </div>
    </div>

</div> -->
<script>
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const row = this.closest('tr');

        row.querySelectorAll('.view').forEach(el => el.classList.add('d-none'));
        row.querySelectorAll('.edit').forEach(el => el.classList.remove('d-none'));

        row.querySelector('.edit-btn').classList.add('d-none');
        row.querySelector('.save-btn').classList.remove('d-none');
    });
});
</script>

<script>
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(alert => {
        alert.classList.remove('show');
        alert.classList.add('fade');
        setTimeout(() => alert.remove(), 300);
    });
}, 4000);
</script>


@endsection
