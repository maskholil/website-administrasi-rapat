@section('title', 'SIAR - Tabel Kategori')
@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link href="{{ asset('argon/css/custom-datatables.css') }}" rel="stylesheet">
@endpush

@section('breadcrumb')
<h6 class="h2 text-white d-none d-inline-block mb-0">Halaman Kategori</h6>
<nav aria-label="breadcrumb" class=" d-md-inline-block ml-xl-2 mt-md-2 mt-sm-2 ml-md-0">
    <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home"></i></a></li>
        <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Tables</a></li>
        <li class="breadcrumb-item active" aria-current="page">Kategori</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-lg-flex">
                        <div>
                            <h5 class="mb-0">Tabel Kategori</h5>
                            <p class="text-sm mb-2">
                                Halaman ini menampilkan seluruh kategori.
                            </p>
                        </div>
                        <div class="ml-auto ms-auto my-auto mt-lg-0 mt-4 ">
                            <div class="ms-auto my-auto ">
                                <button type="button" class="btn bg-gradient-primary btn-sm mb-0 text-white" data-toggle="modal" data-target="#createModal">
                                    +&nbsp; Tambah Kategori
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Kategori -->
                <div class="table-responsive mb-4">
                    @php
                    $counter = 1;
                    @endphp
                    <table class="table align-items-center table-flush mb-2" id="datatable-search">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Kategori</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach ($kategori as $kat)
                            <tr>
                                <th scope="row">{{ $counter++ }}</th>
                                <td>
                                    {{ $kat->nama_kategori }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="javascript:;" class="btn btn-sm btn-warning edit-kategori " data-id="{{ $kat->id }}" data-toggle="tooltip" data-original-title="Edit Data">
                                            <i class="fas fa-edit "></i>
                                        </a>
                                        <a href="javascript:;" class="btn btn-sm btn-youtube delete-kategori " data-id="{{ $kat->id }}" data-toggle="tooltip" data-original-title="Hapus Data">
                                            <i class="fas fa-trash "></i>
                                        </a>
                                    </div>
                                </td>

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


<!-- Modal Tambah Data -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel"><i class="fas fa-folder-plus mr-2"></i> Form Tambah Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Custom separator line -->
            <div style="height: 1px; background-color: #dee2e6;"></div>
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_kategori"><i class="fas fa-folder mr-1"></i> Nama Kategori<span class="text-danger"> *</span></label>
                        <input type="text" class="form-control required" id="nama_kategori" name="nama_kategori" placeholder="Masukkan nama kategori" oninput="this.value = this.value.replace(/\b\w/g, char => char.toUpperCase());" required>
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

<!-- Modal Edit Data -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel"><i class="fas fa-folder-open mr-2"></i> Form Edit Kategori</h5>
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
                    <input type="hidden" id="editKategoriId" name="id">
                    <div class="form-group">
                        <label for="editNamaKategori"><i class="fas fa-folder mr-1"></i> Nama Kategori<span class="text-danger"> *</span></label>
                        <input type="text" class="form-control required" id="editNamaKategori" name="nama_kategori" placeholder="Masukkan nama kategori" oninput="this.value = this.value.replace(/\b\w/g, char => char.toUpperCase());" required>
                    </div>
                </div>
                <!-- Custom separator line -->
                <div style="height: 1px; background-color: #dee2e6;"></div>
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

        // Edit kategori
        $('.edit-kategori').click(function() {
            var kategoriId = $(this).data('id');
            var baseUrl = `/${userRole}/kategori/`;

            $.get(baseUrl + kategoriId + '/edit', function(response) {
                var kategori = response.kategori;

                $('#editKategoriId').val(kategori.id);
                $('#editNamaKategori').val(kategori.nama_kategori);
                $('#editModal').modal('show');
            });
        });

        // Update kategori
        $('#editForm').submit(function(e) {
            e.preventDefault();
            var kategoriId = $('#editKategoriId').val();
            var baseUrl = `/${userRole}/kategori/`;
            $.ajax({
                url: baseUrl + kategoriId,
                type: 'PUT',
                data: $('#editForm').serialize(),
                success: function(result) {
                    if (result.success) {
                        $('#editModal').modal('hide');
                        swal.fire("Berhasil!", "Data kategori berhasil diperbarui.", "success").then(() => {
                            location.reload();
                        });
                    } else {
                        swal.fire("Error!", "Gagal memperbarui data kategori.", "error");
                    }
                }
            });
        });

        // Konfirmasi hapus kategori
        $('.delete-kategori').click(function() {
            var kategoriId = $(this).data('id');
            var baseUrl = `/${userRole}/kategori/`;

            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Setelah dihapus, Anda tidak akan dapat memulihkan kategori ini.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
                reverseButtons: true,
                buttonsStyling: false, // Menghilangkan styling default
                customClass: {
                    confirmButton: 'btn btn-primary btn-md',
                    cancelButton: 'btn btn-danger btn-md'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: baseUrl + kategoriId,
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
                                    text: 'Kategori telah dihapus!',
                                    icon: 'success'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Terjadi kesalahan Pada Sistem!',
                                    'Gagal menghapus kategori.',
                                    'error'
                                );
                            }
                        },
                        error: function() {
                            Swal.fire(
                                'Terjadi kesalahan Pada Sistem!',
                                'Gagal menghapus kategori.',
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