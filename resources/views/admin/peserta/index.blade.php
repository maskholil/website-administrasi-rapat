@section('title', 'SIAR - Tabel Peserta')
@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css" defe>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link href="{{ asset('argon/css/custom-datatables.css') }}" rel="stylesheet">
@endpush

@section('breadcrumb')
<h6 class="h2 text-white d-none d-inline-block mb-0">Halaman Peserta</h6>
<nav aria-label="breadcrumb" class=" d-md-inline-block ml-xl-2 mt-md-2 mt-sm-2 ml-md-0">
    <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home"></i></a></li>
        <li class="breadcrumb-item"><a href="{{ route(Auth::user()->role->nama_role. '.peserta.index') }}">Tables</a></li>
        <li class="breadcrumb-item active" aria-current="page">Peserta</li>
    </ol>
</nav>
@endsection

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-lg-flex">
                        <div>
                            <h5 class="mb-0">Tabel Peserta</h5>
                            <p class="text-sm mb-2">
                                Halaman ini menampilkan seluruh peserta.
                            </p>
                        </div>
                        @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('pegawai') || Auth::user()->hasRole('ketua') || Auth::user()->hasRole('kaprodi') || Auth::user()->hasRole('dekan'))
                        <div class="ml-auto ms-auto my-auto mt-lg-0 mt-4">
                            <div class="ms-auto my-auto">
                                <button type="button" class="btn bg-gradient-primary btn-sm mb-0 text-white" data-toggle="modal" data-target="#createPesertaModal">
                                    +&nbsp; Tambah Peserta
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Tabel Peserta -->
                <div class="table-responsive mb-4">
                    @php
                    $counter = 1;
                    @endphp
                    <table class="table align-items-center table-flush mb-2" id="datatable-search">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Peserta</th>
                                @if (!Auth::user()->hasRole('dosen'))
                                <th class="text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach ($peserta as $p)
                            <tr>
                                <th scope="row">{{ $counter++ }}</th>
                                <td>{{ $p->nama_peserta }}</td>
                                @if (!Auth::user()->hasRole('dosen'))
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="javascript:;" class="btn btn-sm btn-warning edit-peserta" data-id="{{ $p->id }}" data-toggle="tooltip" data-original-title="Edit Data">
                                            <i class="fas fa-edit "></i>
                                        </a>
                                        @endif

                                        @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('pegawai'))
                                        <a href="javascript:;" class="btn btn-sm btn-youtube delete-peserta" data-id="{{ $p->id }}" data-toggle="tooltip" data-original-title="Hapus Data">
                                            <i class="fas fa-trash "></i>
                                        </a>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    @include('layouts.footers.auth')
</div>

<!-- Modal Tambah Peserta -->
<div class="modal fade" id="createPesertaModal" tabindex="-1" role="dialog" aria-labelledby="createPesertaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPesertaModalLabel"><i class="fas fa-user-plus mr-2"></i> Form Tambah Peserta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Custom separator line -->
            <div style="height: 1px; background-color: #dee2e6;"></div>
            <form href="{{ route(Auth::user()->role->nama_role . '.peserta.create') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_peserta"><i class="fas fa-user mr-1"></i> Nama Peserta<span class="text-danger"> *</span></label>
                        <input type="text" class="form-control required" id="nama_peserta" name="nama_peserta" placeholder="Masukkan nama peserta" tabindex="1" oninput="this.value = this.value.replace(/\b\w/g, char => char.toUpperCase());" required>
                    </div>
                </div>
                <!-- Custom separator line -->
                <div style="height: 1px; background-color: #dee2e6; "></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gradient-secondary btn-sm" data-dismiss="modal">
                        <i class="fas fa-times"></i> Tutup
                    </button>
                    <button type="submit" class="btn bg-gradient-primary btn-sm text-white">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Peserta -->
<div class="modal fade" id="editPesertaModal" tabindex="-1" role="dialog" aria-labelledby="editPesertaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPesertaModalLabel"><i class="fas fa-user-edit mr-2"></i> Form Edit Peserta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Custom separator line -->
            <div style="height: 1px; background-color: #dee2e6;"></div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="editPesertaId" name="id">
                    <div class="form-group">
                        <label for="editNamaPeserta"><i class="fas fa-user mr-1"></i> Nama Peserta<span class="text-danger"> *</span></label>
                        <input type="text" class="form-control required" id="editNamaPeserta" name="nama_peserta" placeholder="Masukkan nama peserta" required>
                    </div>
                </div>
                <!-- Custom separator line -->
                <div style="height: 1px; background-color: #dee2e6; "></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gradient-secondary btn-sm" data-dismiss="modal">
                        <i class="fas fa-times"></i> Tutup
                    </button>
                    <button type="submit" class="btn bg-gradient-primary btn-sm text-white">
                        <i class="fas fa-save"></i> Ubah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    $(document).ready(function() {
        var userRole = '{{ auth()->user()->role->nama_role }}';
        var baseUrl = `/${userRole}/peserta/`;

        // Edit peserta
        $('.edit-peserta').click(function() {
            var pesertaId = $(this).data('id');
            $.get(baseUrl + pesertaId + '/edit', function(response) {
                var peserta = response.peserta;

                $('#editPesertaId').val(peserta.id);
                $('#editNamaPeserta').val(peserta.nama_peserta);
                $('#editPesertaModal').modal('show');
            });
        });

        // Update peserta
        $('#editForm').submit(function(e) {
            e.preventDefault();
            var pesertaId = $('#editPesertaId').val();
            $.ajax({
                url: baseUrl + pesertaId,
                type: 'PUT',
                data: $('#editForm').serialize(),
                success: function(result) {
                    if (result.success) {
                        $('#editPesertaModal').modal('hide');
                        swal.fire("Berhasil!", "Data peserta berhasil diperbarui.", "success").then(() => {
                            location.reload();
                        });
                    } else {
                        swal.fire("Error!", "Gagal memperbarui data peserta.", "error");
                    }
                }
            });
        });

        // Konfirmasi hapus peserta
        $('.delete-peserta').click(function() {
            var pesertaId = $(this).data('id');
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Setelah dihapus, Anda tidak akan dapat memulihkan peserta ini.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
                reverseButtons: true,
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary btn-md',
                    cancelButton: 'btn btn-danger btn-md'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: baseUrl + pesertaId,
                        type: 'DELETE',
                        data: {
                            '_token': $('meta[name="csrf-token"]').attr('content')
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        success: function(result) {
                            if (result.success) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: 'Peserta telah dihapus!',
                                    icon: 'success'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Terjadi kesalahan Pada Sistem!',
                                    'Gagal menghapus peserta.',
                                    'error'
                                );
                            }
                        },
                        error: function() {
                            Swal.fire(
                                'Terjadi kesalahan Pada Sistem!',
                                'Gagal menghapus peserta.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>

<script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('argon/js/custom-datatables.js') }}"></script>
@endpush