@section('title', 'SIAR - Dashboard')
@extends('layouts.app')
@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">
            <!-- Card stats -->
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Total Arsip Masuk</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $totalSuratMasuk }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span>Total Keseluruhan Arsip Masuk Anda</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Total Data Disposisi</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $totalDisposisi }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-share-alt"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span>Total Keseluruhan Disposisi Anda</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
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
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Arsip Belum Diproses</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $totalBelumDiproses }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span>Total Arsip Masuk yang Belum Diproses</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid mt--7">
    <div class="row ">
        <div class="col-xl-8">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Tabel Arsip Masuk</h3>
                        </div>
                        <div class="col text-right">
                            <a href="{{ route('kaprodi.surat-masuk.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">No. Surat</th>
                                <th scope="col">Nama File</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suratMasuk as $surat)
                            <tr>
                                <td>{{ $surat->no_surat }}</td>
                                <td>{{ $surat->nama_file }}</td>
                                <td>{{ $surat->kategori->nama_kategori }}</td>
                                <td>{{ $surat->created_at->format('d M Y') }}</td>
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
                            <h3 class="mb-0">Agenda Terbaru</h3>
                        </div>
                        <div class="col text-right">
                            <a href="{{ route('dekan.rapat.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
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
                            @foreach ($agenda as $item)
                            <tr>
                                <td>{{ $item->tentang }} - {{ $item->no_agenda }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('dddd, D MMMM YYYY') }}</td>
                                <td>{{ date('H:i A', strtotime($item->dimulai)) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-xl-12">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Tabel Disposisi</h3>
                        </div>
                        <div class="col text-right">
                            <a href="{{ route('kaprodi.disposisi.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Nama Disposisi</th>
                                <th scope="col">Nomor Surat</th>

                            </tr>
                        </thead>
                        @forelse($disposisi as $user)
                        <tr>
                            <td>{{ $user->catatan }}</td>
                            <td>{{ $user->no_surat }}</td>

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
