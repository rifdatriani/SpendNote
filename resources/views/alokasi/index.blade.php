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

</div>

@endsection
