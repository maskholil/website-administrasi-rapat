@section('title', 'SIAR - Dashboard Pegawai')
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
                                    <h5 class="card-title text-uppercase text-muted mb-0">Total Surat Masuk</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $totalSuratMasuk }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-{{ $suratMasukPercentage >= 0 ? 'success' : 'danger' }} mr-2"><i class="fa fa-arrow-{{ $suratMasukPercentage >= 0 ? 'up' : 'down' }}"></i> {{ abs($suratMasukPercentage) }}%</span>
                                <span class="text-nowrap">Sejak bulan lalu</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Total Surat Keluar</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $totalSuratKeluar }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-paper-plane"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-{{ $suratKeluarPercentage >= 0 ? 'success' : 'danger' }} mr-2"><i class="fas fa-arrow-{{ $suratKeluarPercentage >= 0 ? 'up' : 'down' }}"></i> {{ abs($suratKeluarPercentage) }}%</span>
                                <span class="text-nowrap">Sejak bulan lalu</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Total Agenda</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $totalAgenda }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-{{ $agendaPercentage >= 0 ? 'success' : 'danger' }} mr-2"><i class="fa fa-arrow-{{ $agendaPercentage >= 0 ? 'up' : 'down' }}"></i> {{ abs($agendaPercentage) }}%</span>
                                <span class="text-nowrap">Sejak bulan lalu</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
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
    <div class="row">
        <div class="col-xl-8">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Tabel Arsip</h3>
                            <small class="text-sm">Tabel ini menampilkan seluruh data arsip (Masuk & Keluar).</small>
                        </div>

                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">No Arsip</th>
                                <th scope="col">Nama File</th>
                                <th scope="col">Tanggal Surat</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Jenis Surat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($arsip as $a)
                            <tr>
                                <td>{{ $a->no_arsip }}</td>
                                <td>{{ $a->nama_file }}</td>
                                <td>{{ \Carbon\Carbon::parse($a->tgl_surat)->isoFormat('D MMMM YYYY') }}
                                </td>

                                <td>{{ $a->kategori->nama_kategori }}</td>
                                <td>
                                    @if($a->jenis_arsip == 'masuk')
                                    <span class="badge badge-success">{{ ucfirst($a->jenis_arsip) }}</span>
                                    @else
                                    <span class="badge badge-warning">{{ ucfirst($a->jenis_arsip) }}</span>
                                    @endif
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Tabel Agenda</h3>
                            <small class="text-sm">Seluruh agenda berikutnya.</small>
                        </div>
                        <div class="col text-right">
                            <a href="{{ route(Auth::user()->role->nama_role .'.agenda.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Nama Agenda</th>
                                <th scope="col">Tanggal Agenda</th>
                                <th scope="col">Jam</th>
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
    </div>

    <div class="col-xl-4 mt-5">
        <div class="col-xl-12">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Tabel Disposisi</h3>
                            <small class="text-sm"> Tabel ini menampilkan seluruh disposisi yang tertuju kepada anda.</small>
                        </div>
                        <div class="col text-right">
                            <a href="{{ route('pegawai.disposisi.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Keterangan Disposisi</th>
                                <th scope="col">Nomor Surat</th>
                                <th scope="col">Tujuan Disposisi</th>

                            </tr>
                        </thead>
                        @forelse($disposisi as $user)
                        <tr>
                            <td>{{ $user->catatan }}</td>
                            <td>{{ $user->arsip->no_surat }}</td>
                            <td>
                                @if($user->tujuanUsers->isEmpty())
                                <span class="badge badge-warning">Menunggu Disposisi Ulang</span>
                                @else
                                @php $counter = 1; @endphp
                                @foreach($user->tujuanUsers as $tujuanUser)
                                {{ $counter++ }}. {{ Ucfirst($tujuanUser->name) }}<br>
                                @endforeach
                                @endif
                            </td>
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