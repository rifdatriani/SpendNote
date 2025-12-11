@extends('layouts.app')

@section('content')
<div class="container mt-4">

    {{-- ========================== --}}
    {{--   INPUT GAJI PER BULAN    --}}
    {{-- ========================== --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white fw-bold">Input Gaji Bulanan</div>
        <div class="card-body">
            <form action="{{ route('alokasi.storeSalary') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Jumlah Gaji</label>
                        <input type="number" name="salary" class="form-control" required value="{{ $salary->jumlah_gaji ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label>Bulan</label>
                        <input type="month" name="bulan" class="form-control" required value="{{ $salary->bulan ?? '' }}">
                    </div>
                </div>
                <button class="btn btn-primary">Simpan Gaji</button>
            </form>
        </div>
    </div>

    {{-- ========================== --}}
    {{--     INPUT ALOKASI GAJI     --}}
    {{-- ========================== --}}
    <div class="card mb-4">
        <div class="card-header bg-success text-white fw-bold">Buat Pembagian Alokasi Gaji</div>
        <div class="card-body">
            <form action="{{ route('alokasi.storePlan') }}" method="POST">
                @csrf
                <input type="hidden" name="salary_id" value="{{ $salary->id ?? '' }}">

                <div id="allocationsContainer">
                    <div class="row allocation-row mb-2">
                        <div class="col-6">
                            <input type="text" name="allocations[0][nama_kebutuhan]" class="form-control" placeholder="Nama kebutuhan" required>
                        </div>
                        <div class="col-5">
                            <input type="number" name="allocations[0][nominal]" class="form-control nominalInput" placeholder="Nominal" required>
                        </div>
                        <div class="col-1">
                            <button type="button" class="btn btn-danger btn-sm remove-row">×</button>
                        </div>
                    </div>
                </div>

                <button type="button" id="addAlloc" class="btn btn-secondary btn-sm mt-2">+ Tambah Alokasi</button>

                <div class="alert alert-secondary mt-3">
                    Total Alokasi: <strong id="totalAlokasi">Rp 0</strong>
                </div>
                <div id="warningLimit" class="text-danger fw-bold" style="display:none;">
                    ⚠ Total alokasi melebihi jumlah gaji!
                </div>

                <button class="btn btn-success mt-3">Simpan Alokasi</button>
            </form>
        </div>
    </div>

    {{-- ========================== --}}
    {{--     PREVIEW / CRUD PLAN    --}}
    {{-- ========================== --}}
    <div class="card mb-4">
        <div class="card-header bg-dark text-white fw-bold">Preview Plan Alokasi Gaji</div>
        <div class="card-body">
            <div class="alert alert-info">
                <strong>Total Plan:</strong> Rp {{ number_format($plans->sum('nominal'), 0, ',', '.') }}
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Kebutuhan</th>
                        <th>Nominal</th>
                        <th style="width: 120px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($plans as $plan)
                        <tr>
                            <td>{{ $plan->nama_kebutuhan }}</td>
                            <td>Rp {{ number_format($plan->nominal, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('plan.edit', $plan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('plan.destroy', $plan->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus plan ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted">Belum ada plan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ========================== --}}
    {{--   FORM TRANSAKSI           --}}
    {{-- ========================== --}}
    <div class="card mb-4">
        <div class="card-header bg-info text-white fw-bold">Pencatatan Transaksi</div>
        <div class="card-body">
            <form action="{{ route('alokasi.storeTransaction') }}" method="POST">
                @csrf
                <div class="row g-2">
                    <div class="col-md-3">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label>Kategori</label>
                        <select name="nama_alokasi" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($plans as $p)
                                <option value="{{ $p->nama_kebutuhan }}">{{ $p->nama_kebutuhan }}</option>
                            @endforeach
                            <option value="Dana Darurat">Dana Darurat</option>
                            <option value="Bensin">Bensin</option>
                            <option value="Hiburan">Hiburan</option>
                            <option value="Lain-lain">Lain-lain</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Subkategori</label>
                        <input type="text" name="subkategori" class="form-control" placeholder="opsional">
                    </div>
                    <div class="col-md-2">
                        <label>Jumlah (Rp)</label>
                        <input type="number" name="jumlah" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label>Tipe</label>
                        <select name="tipe" class="form-control">
                            <option value="pengeluaran" selected>Pengeluaran</option>
                            <option value="pemasukan">Pemasukan</option>
                        </select>
                    </div>
                    <div class="col-md-12 mt-2">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="opsional">
                    </div>
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ========================== --}}
    {{--   TABEL DANA DARURAT        --}}
    {{-- ========================== --}}
    <div class="row">
        <div class="col-lg-6 mb-3">
            <div class="card p-3">
                <h5>Dana Darurat</h5>
                <p class="small text-muted">Riwayat & total dana darurat</p>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th>Minggu</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dana ?? [] as $dd)
                            <tr>
                                <td>{{ $dd->bulan }}</td>
                                <td>{{ $dd->minggu }}</td>
                                <td>Rp {{ number_format($dd->jumlah,0,',','.') }}</td>
                                <td>Rp {{ number_format($dd->total,0,',','.') }}</td>
                            </tr>
                        @endforeach
                        @if(count($dana ?? []) == 0)
                            <tr><td colspan="4" class="text-muted">Belum ada dana darurat</td></tr>
                        @endif
                    </tbody>
                </table>
                <div class="mt-2">
                    <strong>Total Dana Darurat: </strong> Rp {{ number_format($totalDanaDarurat ?? 0,0,',','.') }}
                </div>
            </div>
        </div>

        {{-- ========================== --}}
        {{--   TABEL TRANSAKSI          --}}
        {{-- ========================== --}}
        <div class="col-lg-6 mb-3">
            <div class="card p-3">
                <h5>Catatan Pengeluaran / Transaksi</h5>
                <p class="small text-muted">Riwayat transaksi (terbaru ditampilkan)</p>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th>Sub</th>
                            <th>Jumlah</th>
                            <th>Tipe</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksis as $tr)
                            <tr>
                                <td>{{ $tr->tanggal ?? $tr->created_at->format('Y-m-d') }}</td>
                                <td>{{ $tr->nama_alokasi }}</td>
                                <td>{{ $tr->subkategori }}</td>
                                <td>Rp {{ number_format($tr->jumlah,0,',','.') }}</td>
                                <td>{{ $tr->tipe }}</td>
                                <td>
                                    <a href="{{ route('alokasi.edit', $tr->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('alokasi.destroy', $tr->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus transaksi?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @if(count($transaksis ?? []) == 0)
                            <tr><td colspan="6" class="text-muted">Belum ada transaksi</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let idx = 1;

    function updateTotal() {
        let total = 0;
        let salary = parseInt(document.querySelector("input[name='salary']")?.value || 0);
        document.querySelectorAll("input[name*='[nominal]']").forEach(el => { total += parseInt(el.value || 0); });
        document.getElementById("totalAlokasi").innerText = "Rp " + total.toLocaleString("id-ID");
        document.getElementById("warningLimit").style.display = (total > salary && salary > 0) ? "block" : "none";
    }

    document.getElementById('addAlloc').addEventListener('click', function() {
        const container = document.getElementById('allocationsContainer');
        const row = document.createElement('div');
        row.className = 'row allocation-row mb-2';
        row.innerHTML = `
            <div class="col-6">
                <input type="text" name="allocations[${idx}][nama_kebutuhan]" class="form-control" placeholder="Nama kebutuhan" required>
            </div>
            <div class="col-5">
                <input type="number" name="allocations[${idx}][nominal]" class="form-control nominalInput" placeholder="Nominal" required>
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-danger btn-sm remove-row">×</button>
            </div>
        `;
        container.appendChild(row);
        idx++;
        updateTotal();
    });

    document.body.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-row')) { e.target.closest('.allocation-row').remove(); updateTotal(); }
    });

    document.body.addEventListener('input', function(e) {
        if (e.target.classList.contains('nominalInput')) { updateTotal(); }
    });
});
</script>
@endsection
