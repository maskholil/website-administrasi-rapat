@section('title', 'SIAR - Laporan')
@extends('layouts.app')

@section('breadcrumb')
<h6 class="h2 text-white d-none d-inline-block mb-0">Halaman Laporan</h6>
<nav aria-label="breadcrumb" class=" d-md-inline-block ml-xl-2 mt-md-2 mt-sm-2 ml-md-0">
    <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home"></i></a></li>
        <li class="breadcrumb-item active" aria-current="page">Laporan</li>
    </ol>
</nav>
@endsection

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="row justify-content-center">
        <div class="col-lg-23 col-md-12">
            <div class="card">
                <div class="card-header bg-transparent border-0">
                    <div class="d-lg-flex">
                        <div>
                            <h5 class="mb-0">Laporan</h5>
                            <p class="text-sm mb-2">
                                Halaman ini menampilkan berbagai laporan yang dapat dicetak PDF berdasarkan tanggal.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tombol Laporan -->
                <div class="card-body">
                    <div class="row">
                        @if (!Auth::user()->hasRole('ketua'))
                        <div class="col-lg-6 mb-3">
                            <!-- Trigger modal for printing Arsip -->
                            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#arsipModal">
                                <i class="fas fa-file-alt mr-2"></i> Cetak Arsip (Masuk & Keluar)
                            </button>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <!-- Trigger modal for printing Disposisi -->
                            <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#disposisiModal">
                                <i class="fas fa-file-signature mr-2"></i> Cetak Disposisi Arsip Masuk
                            </button>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <!-- Trigger modal for printing Gabungan -->
                            <button type="button" class="btn btn-warning btn-block" data-toggle="modal" data-target="#gabunganModal">
                                <i class="fas fa-file-alt mr-2"></i> Cetak Gabungan Arsip & Disposisi
                            </button>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <!-- Trigger modal for printing Rapat -->
                            <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#rapatModal">
                                <i class="fas fa-file-alt mr-2"></i> Cetak Laporan Hasil Rapat
                            </button>
                        </div>
                        @elseif (Auth::user()->hasRole('ketua'))
                        <div class="col-lg-6 mb-3">
                            <!-- Trigger modal for printing Rapat -->
                            <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#rapatModal">
                                <i class="fas fa-file-alt mr-2"></i> Cetak Laporan Hasil Rapat
                            </button>
                        </div>
                        @endif
                    </div>
                </div>


            </div>
        </div>
    </div>
    <!-- Footer -->
    @include('layouts.footers.auth')
</div>

<!-- Modal for Arsip Date Range Selection -->
<div class="modal fade" id="arsipModal" tabindex="-1" role="dialog" aria-labelledby="arsipModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="arsipModalLabel"><i class="fas fa-calendar-alt mr-2"></i> Pilih Rentang Tanggal dan Jenis Arsip</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div style="height: 1px; background-color: #dee2e6;"></div>
            <div class="modal-body">
                <form action="{{ route('laporan.cetakArsip') }}" method="GET" class="form-horizontal" target="_blank">
                    <div class="form-group">
                        <label for="archiveType" class="form-label">Jenis Arsip: <span class="text-danger"> *</span></label>
                        <select class="form-control" id="archiveType" name="archiveType">
                            <option value="all">Semua</option>
                            <option value="masuk">Masuk</option>
                            <option value="keluar">Keluar</option>
                        </select>
                        <small class="form-text text-muted"> <i class="fas fa-info-circle"></i> Pilih jenis arsip yang ingin dicetak.</small>
                    </div>
                    <div class="form-group">
                        <label for="startDate" class="form-label">Tanggal Awal: <span class="text-danger"> *</span></label>
                        <input type="date" class="form-control" id="startDate" name="startDate" required>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Tanggal Awal harus lebih kecil dari Tanggal Akhir
                        </small>
                    </div>
                    <div class="form-group mb-5">
                        <label for="endDate" class="form-label">Tanggal Akhir: <span class="text-danger"> *</span></label>
                        <input type="date" class="form-control" id="endDate" name="endDate" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div style="height: 1px; background-color: #dee2e6; "></div>
                    <div class=" float-right mt-4 justify-content-end">
                        <button type="submit" class="btn btn-primary ms-auto "><i class="fas fa-file-pdf mr-2"></i> Cetak PDF</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Disposisi Date Range Selection -->
<div class="modal fade" id="disposisiModal" tabindex="-1" role="dialog" aria-labelledby="disposisiModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="disposisiModalLabel"><i class="fas fa-calendar-alt mr-2"></i> Pilih Rentang Tanggal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div style="height: 1px; background-color: #dee2e6;"></div>
            <div class="modal-body">
                <form action="{{ route('laporan.cetakDisposisi') }}" method="GET" class="form-horizontal" target="_blank">
                    <div class="form-group">
                        <label for="startDate" class="form-label">Tanggal Awal: <span class="text-danger"> *</span></label>
                        <input type="date" class="form-control" id="startDate" name="startDate" required>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Tanggal Awal harus lebih kecil dari Tanggal Akhir
                        </small>
                    </div>
                    <div class="form-group mb-5">
                        <label for="endDate" class="form-label">Tanggal Akhir: <span class="text-danger"> *</span></label>
                        <input type="date" class="form-control" id="endDate" name="endDate" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div style="height: 1px; background-color: #dee2e6; "></div>
                    <div class=" float-right mt-4 justify-content-end">
                        <button type="submit" class="btn btn-primary ms-auto "><i class="fas fa-file-pdf mr-2"></i> Cetak PDF</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal for Gabungan Date Range Selection -->
<div class="modal fade" id="gabunganModal" tabindex="-1" role="dialog" aria-labelledby="gabunganModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gabunganModalLabel"><i class="fas fa-calendar-alt mr-2"></i> Pilih Rentang Tanggal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div style="height: 1px; background-color: #dee2e6;"></div>
            <div class="modal-body">
                <form action="{{ route('laporan.cetakGabungan') }}" method="GET" class="form-horizontal" target="_blank">
                    <div class="form-group">
                        <label for="startDate" class="form-label">Tanggal Awal: <span class="text-danger"> *</span></label>
                        <input type="date" class="form-control" id="startDate" name="startDate" required>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Tanggal Awal harus lebih kecil dari Tanggal Akhir
                        </small>
                    </div>
                    <div class="form-group mb-5">
                        <label for="endDate" class="form-label">Tanggal Akhir: <span class="text-danger"> *</span></label>
                        <input type="date" class="form-control" id="endDate" name="endDate" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div style="height: 1px; background-color: #dee2e6; "></div>
                    <div class=" float-right mt-4 justify-content-end">
                        <button type="submit" class="btn btn-primary ms-auto "><i class="fas fa-file-pdf mr-2"></i> Cetak PDF</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Rapat Date Range Selection -->
<div class="modal fade" id="rapatModal" tabindex="-1" role="dialog" aria-labelledby="rapatModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rapatModalLabel"><i class="fas fa-calendar-alt mr-2"></i> Pilih Rentang Tanggal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div style="height: 1px; background-color: #dee2e6;"></div>
            <div class="modal-body">
                <form action="{{ route('laporan.cetakRapat') }}" method="GET" class="form-horizontal" target="_blank">
                    <div class="form-group">
                        <label for="startDate" class="form-label">Tanggal Awal: <span class="text-danger"> *</span></label>
                        <input type="date" class="form-control" id="startDate" name="startDate" required>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Tanggal Awal harus lebih kecil dari Tanggal Akhir
                        </small>
                    </div>
                    <div class="form-group mb-5">
                        <label for="endDate" class="form-label">Tanggal Akhir: <span class="text-danger"> *</span></label>
                        <input type="date" class="form-control" id="endDate" name="endDate" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div style="height: 1px; background-color: #dee2e6; "></div>
                    <div class=" float-right mt-4 justify-content-end">
                        <button type="submit" class="btn btn-primary ms-auto "><i class="fas fa-file-pdf mr-2"></i> Cetak PDF</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@push('js')
<script>
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>

<script>

    $(document).ready(function() {
        // Arsip Modal
        $('#arsipModal').on('show.bs.modal', function(event) {
            var modal = $(this);
            modal.find('.modal-body #startDate').val('');
            modal.find('.modal-body #endDate').val(new Date().toISOString().substring(0, 10));
        });

        // Disposisi Modal
        $('#disposisiModal').on('show.bs.modal', function(event) {
            var modal = $(this);
            modal.find('.modal-body #startDate').val('');
            modal.find('.modal-body #endDate').val(new Date().toISOString().substring(0, 10));
        });

        // Gabungan Modal
        $('#gabunganModal').on('show.bs.modal', function(event) {
            var modal = $(this);
            modal.find('.modal-body #startDate').val('');
            modal.find('.modal-body #endDate').val(new Date().toISOString().substring(0, 10));
        });

        // Rapat Modal
        $('#rapatModal').on('show.bs.modal', function(event) {
            var modal = $(this);
            modal.find('.modal-body #startDate').val('');
            modal.find('.modal-body #endDate').val(new Date().toISOString().substring(0, 10));
        });
    });
</script>
@endpush
