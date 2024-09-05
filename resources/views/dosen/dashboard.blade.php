@section('title', 'SIAR - Dashboard Dosen')
@extends('layouts.app')
@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">
            <!-- Card stats -->
            <div class="row">
                <div class="col-xl-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Total Data Agenda</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $totalAgenda }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span>Total Data Agenda Rapat</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Total Peserta</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $totalPeserta }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span>Jumlah seluruh peserta yang terdata.</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="container-fluid mt--7">
    <div class="row mt-5">
        <div class="col-xl-8">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Tabel Agenda</h3>
                        </div>
                        <div class="col text-right">
                            <a href="{{ route('dosen.rapat.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Nama Agenda</th>
                                <th scope="col">Tanggal Agenda</th>
                                <th scope="col">Jam Mulai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($agenda as $a)
                            <tr>
                                <td>{{ $a->tentang }} - {{ $a->no_agenda }}</td>
                                <td>{{ \Carbon\Carbon::parse($a->tanggal)->isoFormat('dddd, D MMMM YYYY') }}</td>
                                <td>{{ date('H:i A', strtotime($a->dimulai)) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-4 mt-5">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Tabel Peserta</h3>
                        </div>
                        <div class="col text-right">
                            <a href="{{ route('dosen.peserta.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Nama Peserta</th>
                                <th scope="col">Ditambahkan</th>

                            </tr>
                        </thead>
                        @forelse($peserta as $user)
                        <tr>
                            <td>{{ $user->nama_peserta }}</td>
                            <td>{{ $user->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center" colspan="2">Tidak ada data </td>
                        </tr>
                        @endforelse

                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth')
</div>
@endsection
