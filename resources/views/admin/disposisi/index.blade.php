@section('title', 'SIAR - Tabel Disposisi')
@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link href="{{ asset('argon/css/custom-datatables.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/viewerjs/dist/viewer.min.css">
<script src="https://unpkg.com/viewerjs/dist/viewer.min.js"></script>
<style>
    .preview-file {
        display: inline-block;
        padding: 8px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        color: #007bff;
        text-decoration: none;
    }

    .preview-file:hover {
        background-color: #e2e6ea;
    }

    #image-preview {
        cursor: pointer;
    }
</style>
@endpush

@section('breadcrumb')
<h6 class="h2 text-white d-none d-inline-block mb-0">Halaman Disposisi</h6>
<nav aria-label="breadcrumb" class=" d-md-inline-block ml-xl-2 mt-md-2 mt-sm-2 ml-md-0">
    <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home"></i></a></li>
        <li class="breadcrumb-item"><a href="{{ route(Auth::user()->role->nama_role .'.disposisi.index') }}">Tables</a></li>
        <li class="breadcrumb-item active" aria-current="page">Disposisi</li>
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
                            <h5 class="mb-0">Tabel Disposisi</h5>
                            <p class="text-sm mb-2">
                                Halaman ini menampilkan seluruh disposisi.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tabel Disposisi -->
                <div class="table-responsive mb-4">
                    @php
                    $counter = 1;
                    @endphp
                    <table class="table align-items-center table-flush mb-2" id="datatable-search">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>No Surat Masuk</th>
                                <th>Catatan</th>
                                <th>Lihat Surat</th>

                                <th>Tujuan</th>
                                <th>Status Disposisi</th>

                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach ($disposisi as $dis)
                            <tr>
                                <th scope="row">{{ $counter++ }}</th>
                                <td>{{ $dis->arsip->no_surat }}</td>
                                <td>{{ Ucfirst($dis->catatan) }}</td>
                                <td>
                                    <!-- icon dinamis -->
                                    @php
                                    $ext = strtolower(pathinfo($dis->arsip->file, PATHINFO_EXTENSION));
                                    $icon = 'fa-file';
                                    if (in_array($ext, ['pdf'])) {
                                    $icon = 'fa-file-pdf';
                                    } elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                                    $icon = 'fa-file-image';
                                    }
                                    @endphp
                                    <!-- menampilkan preview file -->
                                    @if(in_array(pathinfo($dis->arsip->file, PATHINFO_EXTENSION), ['pdf', 'jpg', 'jpeg', 'png', 'gif']))
                                    <a href="javascript:;" class="preview-file" data-file="{{ $dis->arsip->file }}">
                                        <i class="fas {{ $icon }}"></i> Lihat File
                                    </a>
                                    @else
                                    {{ $dis->arsip->file }}
                                    @endif
                                </td>
                                <td>
                                    @if($dis->tujuanUsers->isEmpty())
                                    <span class="badge badge-warning">Menunggu Disposisi Ulang</span>
                                    @else
                                    @php $counter = 1; @endphp
                                    @foreach($dis->tujuanUsers as $tujuanUser)
                                    {{ $counter++ }}. {{ Ucfirst($tujuanUser->name) }} - {{ Ucfirst($tujuanUser->status_disposisi)}}<br>
                                    @endforeach
                                    @endif
                                </td>
                                <td>

                                    @foreach($dis->tujuanUsers as $tujuanUser)
                                    @if($tujuanUser->pivot->status_disposisi == 'diterima')
                                    <span class="badge badge-success">Diterima</span><br>
                                    @elseif($tujuanUser->pivot->status_disposisi == 'disposisi')
                                    <span class="badge badge-warning">Disposisi</span><br>
                                    @else
                                       -
                                    {{ ucfirst($tujuanUser->pivot->status_disposisi) }}<br>
                                    @endif

                                    @endforeach
                                </td>


                                <td class="text-center">
                                    @if ($dis->tujuanUsers->pluck('id')->contains(auth()->user()->id))
                                    @if ($dis->allUsersDisposisi())
                                    <span class="badge badge-info">Disposisi Ulang</span>
                                    @else
                                    <button class="btn btn-sm btn-success terima-disposisi mx-1" data-id="{{ $dis->id }}">Terima</button>
                                    <button class="btn btn-sm btn-warning disposisi-ulang-kembali mx-1" data-id="{{ $dis->id }}">Disposisi</button>
                                    @endif
                                    @else

                                    @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('pegawai') )
                                    <div class="btn-group">
                                        <a href="javascript:;" class="btn btn-sm btn-info view-disposisi " data-id="{{ $dis->id }}" data-toggle="tooltip" data-original-title="Lihat Data">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="javascript:;" class="btn btn-sm btn-warning disposisi-ulang" data-id="{{ $dis->id }}" data-toggle="tooltip" data-original-title="Disposisi Ulang">
                                            <i class="fas fa-share"></i> Disposisi Ulang
                                        </a>
                                        <a href="javascript:;" class="btn btn-sm btn-youtube delete-disposisi" data-id="{{ $dis->id }}" data-toggle="tooltip" data-original-title="Hapus Data">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                    @endif
                                    @endif
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
<!-- Modal Lihat Disposisi -->
<div class="modal fade" id="viewDisposisiModal" tabindex="-1" role="dialog" aria-labelledby="viewDisposisiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDisposisiModalLabel"><i class="fas fa-file-alt mr-2"></i> Detail Disposisi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Custom separator line -->
            <div style="height: 1px; background-color: #dee2e6;"></div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="viewSuratMasuk"><i class="fas fa-envelope mr-1"></i>Nomor Surat Masuk</label>
                    <input type="text" class="form-control" id="viewSuratMasuk" name="surat_masuk" readonly>
                </div>
                <div class="form-group">
                    <label for="viewCatatan"><i class="fas fa-sticky-note mr-1"></i> Catatan Disposisi</label>
                    <textarea class="form-control" id="viewCatatan" name="catatan" rows="4" readonly></textarea>
                </div>
                <div class="form-group">
                    <label for="viewTujuan"><i class="fas fa-map-marker-alt mr-1"></i> Tujuan Surat</label>
                    <div id="viewTujuan"></div>
                </div>

            </div>
            <!-- Custom separator line -->
            <div style="height: 1px; background-color: #dee2e6; "></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-gradient-secondary btn-sm" data-dismiss="modal">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Disposisi Ulang -->
<div class="modal fade" id="disposisiUlangModal" tabindex="-1" role="dialog" aria-labelledby="disposisiUlangModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="disposisiUlangModalLabel"><i class="fas fa-share mr-2"></i> Form Disposisi Ulang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Custom separator line -->
            <div style="height: 1px; background-color: #dee2e6;"></div>
            <form id="disposisiUlangForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="disposisiId" name="disposisi_id">
                    <div class="form-group">
                        <label for="tujuan"><i class="fas fa-map-marker-alt mr-1"></i> Tujuan<span class="text-danger"> *</span></label>
                        <select class="form-control" id="tujuan" name="tujuan[]" multiple required>
                            @foreach($disposisiUsers as $disposisiUser)
                            @if($disposisiUser['disposisi_id'] == $dis->id)
                            @foreach($disposisiUser['available_users'] as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                            @endif
                            @endforeach
                        </select>
                        <small class="form-text text-muted ml-1"> Pilih disposisi surat tujuan, hanya data tujuan di atas yang tersedia.</small>
                    </div>
                    <div class="form-group">
                        <label for="catatan"><i class="fas fa-sticky-note mr-1"></i> Catatan Baru<span class="text-danger"> *</span></label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="3" required></textarea>
                        <small class="form-text text-muted ml-1"> Masukan catatan baru untuk disposisi ini.</small>
                    </div>
                </div>
                <!-- Custom separator line -->
                <div style="height: 1px; background-color: #dee2e6; "></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gradient-secondary btn-sm" data-dismiss="modal">
                        <i class="fas fa-times"></i> Tutup
                    </button>
                    <button type="submit" class="btn bg-gradient-primary btn-sm text-white">
                        <i class="fas fa-share"></i> Disposisi Ulang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>




<!-- Modal Preview File -->
<div class="modal fade" id="previewFileModal" tabindex="-1" role="dialog" aria-labelledby="previewFileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewFileModalLabel"><i class="fas fa-eye mr-2"></i>Preview File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="previewFileContainer"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i>
                    Tutup</button>
                <a href="" id="downloadFileButton" class="btn btn-primary" target="_blank"><i class="fas fa-download"></i> Unduh</a>

            </div>
        </div>
    </div>
</div>

@endsection

@push('js')

<script>
    $(document).ready(function() {
        let disposisiId;
        let role = '{{ auth()->user()->role->nama_role }}';
        let userRole = '{{ auth()->user()->role->nama_role }}';


        $(document).on('click', '.terima-disposisi', function() {
            const disposisiId = $(this).data('id');
            // Ajax call to backend to mark as accepted
            // console.log("ROLE ID:", role);
            // console.log("Accepting disposisi with ID:", disposisiId);
            // Implement your AJAX request here
            Swal.fire({
                title: 'Konfirmasi Surat',
                text: 'Apakah yakin ingin menerima surat ini? Aksi ini tidak dapat dibatalkan.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, terima',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary btn-md',
                    cancelButton: 'btn btn-danger btn-md'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let url;
                    if (role === 'pegawai') {
                        url = '/pegawai/disposisi/terima/' + disposisiId;
                    } else if (role === 'dekan') {
                        url = '/dekan/disposisi/terima/' + disposisiId;
                    } else if (role === 'kaprodi') {
                        url = '/kaprodi/disposisi/terima/' + disposisiId;
                    }

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            '_token': $('meta[name="csrf-token"]').attr('content'), // Use meta tag for CSRF token
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    title: 'Berhasil',
                                    text: 'Surat berhasil diterima.',
                                    icon: 'success'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error', 'Terjadi kesalahan saat menerima surat.', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'Terjadi kesalahan saat menerima surat.', 'error');
                        }
                    });
                }
            });
        });

        $(document).on('click', '.disposisi-ulang-kembali', function() {
            const disposisiId = $(this).data('id');
            // const role = '{{ auth()->user()->role->nama_role }}';
            // Ajax call to backend to re-dispose
            console.log("ROLE ID:", role);
            console.log("Re-disposing disposisi with ID:", disposisiId);
            // Implement your AJAX request here
            Swal.fire({
                title: 'Konfirmasi Disposisi',
                html: `
    <div class="form-group d-flex flex-column">
        <label for="swal-input1" class="text-left mb-2">Masukan keterangan di bawah ini, mengapa surat ini perlu didisposisi ulang:</label>
        <textarea id="swal-input1" class="swal2-textarea" rows="3"></textarea>
    </div>
`,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Disposisi',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary btn-md',
                    cancelButton: 'btn btn-danger btn-md',
                    htmlContainer: 'text-left'
                },
                preConfirm: () => {
                    const keterangan = document.getElementById('swal-input1').value.trim();
                    if (!keterangan) {
                        Swal.showValidationMessage('Keterangan harus diisi');
                    }
                    return {
                        keterangan: keterangan
                    };
                },
                didOpen: () => {
                    const textarea = document.getElementById('swal-input1');
                    const container = textarea.parentNode;
                    container.style.width = '100%';
                    textarea.style.width = '80%';
                    textarea.style.boxSizing = 'border-box';
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let url;
                    if (role === 'pegawai') {
                        url = '/pegawai/disposisi/disposisi/' + disposisiId;
                    } else if (role === 'dekan') {
                        url = '/dekan/disposisi/disposisi/' + disposisiId;
                    } else if (role === 'kaprodi') {
                        url = '/kaprodi/disposisi/disposisi/' + disposisiId;
                    }

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'keterangan': result.value.keterangan
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    title: 'Berhasil',
                                    text: 'Surat berhasil didisposisikan.',
                                    icon: 'success'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error', 'Terjadi kesalahan saat mendisposisikan surat.', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'Terjadi kesalahan saat mendisposisikan surat.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        var userRole = '{{ auth()->user()->role->nama_role }}';
        var baseUrl = `/${userRole}/disposisi/`;

        $('.preview-file').click(function() {
            var file = $(this).data('file');
            var fileExtension = file.split('.').pop().toLowerCase();
            var fileUrl = window.location.origin + '/storage/arsip/suratmasuk/' + file.replace('/admin/arsip/suratmasuk/', '');
            // console.log(fileUrl);

            // c3ek file url
            // console.log(fileUrl);
            var contentHtml = '';
            if (fileExtension === 'pdf') {
                console.log(fileUrl); // Memastikan URL benar
                var pdfHtml = '<object data="' + fileUrl + '" type="application/pdf" width="100%" height="600px" style="border: none;">This browser does not support PDFs. Please download the PDF to view it: <a href="' + fileUrl + '">Download PDF</a>.</object>';
                $('#previewFileContainer').html(pdfHtml);
            } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                contentHtml = '<img src="' + fileUrl + '" class="img-fluid img-thumbnail mx-auto d-block" id="image-preview">';
                $('#previewFileContainer').html(contentHtml);

                var viewer = new Viewer(document.getElementById('image-preview'), {
                    navbar: false,
                    toolbar: true,
                    title: false
                });
            } else {
                contentHtml = 'File format not supported for preview.';
                $('#previewFileContainer').html(contentHtml);
            }

            $('#downloadFileButton').attr('href', fileUrl);
            $('#previewFileModal').modal('show');
        });

        // Lihat disposisi
        $('.view-disposisi').click(function() {
            var disposisiId = $(this).data('id');

            $.get(baseUrl + disposisiId, function(response) {
                // console.log(response);
                var disposisi = response.disposisi;

                $('#viewCatatan').text(disposisi.catatan || 'No data');
                // console.log(disposisi.tujuan_users);
                $('#viewTujuan').empty();
                if (disposisi.tujuan_users && disposisi.tujuan_users.length > 0) {
                    disposisi.tujuan_users.forEach(function(user) {
                        $('#viewTujuan').append('<span class="badge badge-primary mr-1">' + user.name + '</span>');
                    });
                } else {
                    $('#viewTujuan').append('<span class="text-muted">Tidak ada tujuan</span>');
                }

                if (disposisi.arsip && disposisi.arsip.no_surat) {
                    $('#viewSuratMasuk').val(disposisi.arsip.no_surat);
                } else {
                    $('#viewSuratMasuk').val('Disposisi Sekarang');
                }

                $('#viewDisposisiModal').modal('show');
            });
        });

        // Disposisi ulang
        $('.disposisi-ulang').click(function() {
            var disposisiId = $(this).data('id');
            $('#disposisiId').val(disposisiId);
            $('#disposisiUlangModal').modal('show');
        });

        // Proses disposisi ulang
        $('#disposisiUlangForm').submit(function(e) {
            e.preventDefault();
            var disposisiId = $('#disposisiId').val();

            $.ajax({
                url: baseUrl + disposisiId + '/disposisi-ulang',
                type: 'POST',
                data: $('#disposisiUlangForm').serialize(),
                success: function(result) {
                    if (result.success) {
                        $('#disposisiUlangModal').modal('hide');
                        swal.fire("Berhasil!", "Disposisi ulang berhasil dilakukan.", "success").then(() => {
                            location.reload();
                        });
                    } else {
                        swal.fire("Error!", "Gagal melakukan disposisi ulang.", "error");
                    }
                }
            });
        });

        // Konfirmasi hapus disposisi
        $('.delete-disposisi').click(function() {
            var disposisiId = $(this).data('id');
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Setelah dihapus, Anda tidak akan dapat memulihkan disposisi ini.",
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
                        url: baseUrl + disposisiId,
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
                                    text: 'Disposisi telah dihapus!',
                                    icon: 'success'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Terjadi kesalahan Pada Sistem!',
                                    'Gagal menghapus disposisi.',
                                    'error'
                                );
                            }
                        },
                        error: function() {
                            Swal.fire(
                                'Terjadi kesalahan Pada Sistem!',
                                'Gagal menghapus disposisi.',
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
