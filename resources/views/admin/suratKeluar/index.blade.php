@section('title', 'SIAR - Tabel Surat Keluar')
@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link href="{{ asset('argon/css/custom-datatables.css') }}" rel="stylesheet">
<!-- <script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script> -->
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

    .editor-error {
        border: 1px solid red;
    }

    .error-message {
        color: red;
        display: none;

        font-size: 0.8em;
        margin-top: 5px;
        margin-left: 0.5px;
    }


</style>
@endpush

@section('breadcrumb')
<h6 class="h2 text-white d-none d-inline-block mb-0">Halaman Surat Keluar </h6>
<nav aria-label="breadcrumb" class=" d-md-inline-block ml-xl-2 mt-md-2 mt-sm-2 ml-md-0">
    <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home"></i></a></li>
        <li class="breadcrumb-item"><a href="{{ route(Auth::user()->role->nama_role .'.surat-keluar.index') }}">Tables</a></li>
        <li class="breadcrumb-item active" aria-current="page">Surat Keluar</li>
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
                            <h5 class="mb-0">Tabel Surat Keluar</h5>
                            <p class="text-sm mb-2">
                                Halaman ini menampilkan seluruh surat keluar.
                            </p>
                        </div>


                        @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('pegawai'))
                        <div class="ml-auto ms-auto my-auto mt-lg-0 mt-4">
                            <div class="ms-auto my-auto">
                                <button type="button" class="btn bg-gradient-primary btn-sm mb-0 text-white" data-toggle="modal" data-target="#createSuratModal">
                                    +&nbsp; Tambah Surat Keluar
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Tabel Surat Keluar -->
                <div class="table-responsive mb-4">
                    @php
                    $counter = 1;
                    @endphp
                    <table class="table align-items-center table-flush mb-2" id="datatable-search">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>No Surat</th>
                                <th>Tujuan</th>
                                <th>Kategori</th>
                                <th class="text-center">Isi Surat</th>
                                <th>No Arsip</th>
                                <th>Judul Surat</th>
                                <th>Status Surat</th>
                                <th>Keterangan</th>
                                <th>Pengelola</th>
                                <th>Tanggal Surat</th>
                                <th>Validator</th>
                                <th>File</th>
                                <th class="text-center">Aksi</th>

                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach ($arsip as $surat)
                            <tr>
                                <th scope="row">{{ $counter++ }}</th>
                                <td>{{ $surat->no_surat }}</td>
                                <td>{{ $surat->tujuan_keluar }}</td>
                                <td>{{ $surat->kategori->nama_kategori }}</td>
                                <td>
                                    @if((Auth::user()->hasRole('admin') || Auth::user()->hasRole('pegawai')) && !empty($surat->ttd))
                                    <a href="javascript:void(0);" class="lihat-isi-surat preview-file" data-id="{{ $surat->id }}" data-status-keluar="{{ $surat->status_keluar }}" data-toggle="tooltip" title="Lihat Isi Surat">
                                        <i class="fas fa-eye text-primary"></i>
                                        Lihat Surat
                                    </a>
                                    @else
                                    <a href="javascript:void(0);" class="lihat-isi-surat preview-file" data-id="{{ $surat->id }}" data-ttd="{{ json_encode($surat->ttd) }}" data-toggle="tooltip" title="Lihat Isi Surat">
                                        <i class="fas fa-eye text-primary"></i>
                                        Lihat Surat
                                    </a>
                                    @endif
                                </td>


                                <td>{{ $surat->no_arsip }}</td>
                                <td>{{ $surat->nama_file }}</td>
                                <td>
                                    @if($surat->status_keluar == 'diproses')
                                    <span class="badge badge-warning">Menunggu ACC</span>
                                    @elseif($surat->status_keluar == 'disetujui')
                                    <span class="badge badge-success">{{ $surat->status_keluar }}</span>
                                    @elseif($surat->status_keluar == 'ditolak')
                                    <span class="badge badge-danger">Ditolak</span>
                                    @else
                                    <span class="badge badge-primary">{{ $surat->status_keluar }}</span>
                                    @endif
                                </td>

                                <td>{{ $surat->keterangan ? ucfirst($surat->keterangan) : '-' }}</td>
                                <td>{{ $surat->user->name }}</td>

                                <td>{{ \Carbon\Carbon::parse($surat->tgl_surat)->isoFormat('D MMMM YYYY') }}
                                </td>
                                <td>
                                    @php
                                    $validator = \App\Models\User::find($surat->validator);
                                    @endphp

                                    @if ($validator)
                                    {{ $validator->name ?? 'Tidak ada validator' }}
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>
                                    @php
                                    $ext = strtolower(pathinfo($surat->file, PATHINFO_EXTENSION));
                                    $icon = 'fa-file';
                                    if (in_array($ext, ['pdf'])) {
                                    $icon = 'fa-file-pdf';}
                                    elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                                    $icon = 'fa-file-image';}
                                    @endphp

                                    @if($surat->file)
                                    @if(in_array(pathinfo($surat->file, PATHINFO_EXTENSION), ['pdf', 'jpg', 'jpeg', 'png', 'gif']))
                                    <a href="javascript:;" class="preview-file" data-file="{{ $surat->file }}">
                                        <i class="fas {{ $icon }} mr-1"></i> Lihat File
                                    </a>
                                    @else
                                    {{ $surat->file }}
                                    @endif
                                    @else
                                    -
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="btn-group">
                                        @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('pegawai'))
                                        <a href="javascript:;" class="btn btn-sm btn-warning edit-surat" data-id="{{ $surat->id }}" data-toggle="tooltip" data-original-title="Edit Data">
                                            <i class="fas fa-edit "></i>
                                        </a>
                                        <a href="javascript:;" class="btn btn-sm btn-youtube delete-surat" data-id="{{ $surat->id }}" data-toggle="tooltip" data-original-title="Hapus Data">
                                            <i class="fas fa-trash "></i>
                                        </a>

                                        @elseif (auth()->user()->id == $surat->validator)
                                        <a href="javascript:void(0);" class="btn btn-success btn-sm  btn-signature mx-1" data-toggle="modal" data-target="#signatureModal" data-id="{{ $surat->id }}" data-has-signature="{{ !empty($surat->ttd) ? 'true' : 'false' }}">
                                            <i class="fas fa-signature mr-1"></i> Tanda Tangani
                                        </a>
                                        <a href="javascript:void(0);" class="btn btn-youtube btn-sm btn-reject mx-1" data-id="{{ $surat->id }}">
                                            <i class="fas fa-times mr-1"></i> Tolak
                                            Surat
                                        </a>
                                        @else (auth()->user()->id == $surat->validator)
                                        -
                                        @endif
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
            <div style="height: 1px; background-color: #dee2e6;"></div>
            <div class="modal-body">
                <div id="previewFileContainer"></div>
            </div>
            <div style="height: 1px; background-color: #dee2e6;"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <a href="" id="downloadFileButton" class="btn btn-primary" target="_blank">Unduh</a>
            </div>
        </div>
    </div>
</div>

@if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('pegawai'))
<!-- Modal Tambah Surat Keluar -->
<div class="modal fade" id="createSuratModal" tabindex="-1" role="dialog" aria-labelledby="createSuratModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSuratModalLabel"><i class="fas fa-envelope mr-2"></i> Form Tambah Surat Keluar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Custom separator line -->
            <div style="height: 1px; background-color: #dee2e6;"></div>
            <form id="form-id" action="{{ route(Auth::user()->role->nama_role .'.surat-keluar.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="no_surat"><i class="fas fa-envelope mr-1"></i> No Surat<span class="text-danger"> *</span></label>
                                <input type="text" class="form-control required" id="no_surat" name="no_surat" placeholder="Masukkan no surat" required>
                            </div>
                            <div class="form-group">
                                <label for="tgl_surat"><i class="fas fa-calendar-alt mr-1"></i> Tanggal Surat<span class="text-danger"> *</span></label>
                                <input type="date" class="form-control required" id="tgl_surat" name="tgl_surat" value="{{ now()->format('Y-m-d') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="tujuan_keluar">
                                    <i class="fas fa-user-tie mr-1"></i> Tujuan
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control required" id="tujuan_keluar" name="tujuan_keluar" placeholder="Masukkan penerima surat" oninput="this.value = this.value.replace(/\b\w/g, char => char.toUpperCase());" required>
                            </div>
                            <div class="form-group">
                                <label for="keterangan"><i class="fas fa-info-circle mr-1"></i> Keterangan</label>
                                <textarea class="form-control required" id="keterangan" name="keterangan" rows="3" placeholder="Masukkan keterangan"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="no_arsip"><i class="fas fa-hashtag mr-1"></i> No Arsip<span class="text-danger"> *</span></label>
                                <input type="text" class="form-control required" id="no_arsip" name="no_arsip" placeholder="Masukkan nomor arsip" required>
                            </div>
                            <div class="form-group">
                                <label for="nama_file"><i class="fas fa-file mr-1"></i> Judul Surat<span class="text-danger"> *</span></label>
                                <input type="text" class="form-control required" id="nama_file" name="nama_file" placeholder="Masukkan nama file" required>
                                <small class="form-text text-muted ml-1">Judul surat ini akan terlihat didalam surat</small>
                            </div>
                            <div class="form-group">
                                <label for="kategori_id"><i class="fas fa-list mr-1"></i> Kategori<span class="text-danger"> *</span></label>
                                <select class="form-control required" id="kategori_id" name="kategori_id" required>
                                    <option value="" disabled selected>- Pilih Kategori -</option>
                                    @foreach($kategori as $kat)
                                    <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-group">
                                <label for="user_id" style="display: none;"><i class="fas fa-user mr-1"></i> Pengguna<span class="text-danger"> *</span></label>
                                <input type="hidden" class="form-control required" id="user_id" name="user_id" value="{{ auth()->user()->id }}" required>
                            </div>

                            <div class="form-group">
                                <label for="file"><i class="fas fa-file-upload mr-1"></i> File (Opsional)</label>
                                <input type="file" class="form-control required" id="file" name="file" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                                <small class="form-text text-muted">Khusus File PDF, DOC, DOCX, PNG, JPG, JPEG</small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="editValidator"><i class="fas fa-user mr-1"></i> Validasi Surat<span class="text-danger"> *</span></label>
                                <select class="form-control required" id="editValidator" name="validator" required>
                                    @foreach($validators as $validator)
                                    <option value="{{ $validator->id }}">{{ $validator->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="isi"><i class="fas fa-align-left mr-1"></i> Isi Surat<span class="text-danger"> *</span></label>
                                <textarea class="form-control required" id="isi" name="isi" rows="15" placeholder="Masukkan isi surat" required></textarea>
                                <div id="isiError" class="error-message">Isi surat wajib diisi.</div>
                            </div>
                        </div>

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
@endif

<!-- Modal Edit Surat Keluar -->
<div class="modal fade" id="editSuratModal" tabindex="-1" role="dialog" aria-labelledby="editSuratModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSuratModalLabel"><i class="fas fa-envelope mr-2"></i> Form Edit Surat Keluar</h5> <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
            </div> <!-- Custom separator line -->
            <div style="height: 1px; background-color: #dee2e6;"></div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6"> <input type="hidden" id="editSuratId" name="id">
                            <div class="form-group">
                                <label for="editNoSurat"><i class="fas fa-envelope mr-1"></i> No Surat<span class="text-danger"> *</span></label> <input type="text" class="form-control required" id="editNoSurat" name="no_surat" placeholder="Masukkan no surat" required>
                            </div>
                            <div class="form-group">
                                <label for="editTglSurat"><i class="fas fa-calendar-alt mr-1"></i> Tanggal Surat<span class="text-danger"> *</span></label> <input type="date" class="form-control required" id="editTglSurat" name="tgl_surat" required>
                            </div>
                            <div class="form-group">
                                <label for="editTujuanKeluar"><i class="fas fa-user-tie mr-1"></i> Tujuan Keluar<span class="text-danger"> *</span></label> <input type="text" class="form-control required" id="editTujuanKeluar" name="tujuan_keluar" placeholder="Masukkan penerima surat" required>
                            </div>

                            <div class="form-group">
                                <label for="editKeterangan"><i class="fas fa-info-circle mr-1"></i> Keterangan
                                </label>
                                <textarea class="form-control required" id="editKeterangan" name="keterangan" rows="4" placeholder="Masukkan keterangan"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6"> <input type="hidden" id="editArsipId" name="arsip_id">
                            <div class="form-group">
                                <label for="editNoArsip"><i class="fas fa-hashtag mr-1"></i> No Arsip<span class="text-danger"> *</span></label> <input type="text" class="form-control required" id="editNoArsip" name="no_arsip" placeholder="Masukkan nomor arsip" required>
                            </div>
                            <div class="form-group">
                                <label for="editNamaFile"><i class="fas fa-file mr-1"></i> Judul Surat<span class="text-danger"> *</span>
                                </label> <input type="text" class="form-control required" id="editNamaFile" name="nama_file" placeholder="Masukkan nama file" required>
                                <small class="form-text text-muted">Judul surat ini akan terlihat didalam surat</small>
                            </div>


                            <div class="form-group">
                                <label for="editUserId"><i class="fas fa-user mr-1"></i> Pengelola<span class="text-danger"> *</span></label> <select class="form-control required" id="editUserId" name="user_id" required>
                                    <option value="" disabled selected>- Pilih User -</option> @foreach($users as $user) <option value="{{ $user->id }}">{{ $user->name }}</option> @endforeach
                                </select>
                            </div>
                            <div class="form-group"> <label for="editKategoriId"><i class="fas fa-list mr-1"></i> Kategori<span class="text-danger"> *</span></label> <select class="form-control required" id="editKategoriId" name="kategori_id" required>
                                    <option value="" disabled selected>- Pilih Kategori -</option> @foreach($kategori as $kat) <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option> @endforeach
                                </select> </div>
                            <div class="form-group"> <label for="editFile"><i class="fas fa-file-upload mr-1"></i> File (Opsional)
                                </label> <input type="file" class="form-control" id="editFile" name="file" accept=".pdf,.doc,.docx">
                                <small class="form-text text-muted">Khusus File PDF, DOC, DOCX, PNG, JPG, JPEG</small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="validator"><i class="fas fa-user mr-1"></i> Validasi Surat<span class="text-danger"> *</span></label>
                                <select class="form-control required" id="validator" name="validator" required>
                                    @foreach($validators as $validator)
                                    <option value="{{ $validator->id }}">{{ $validator->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="editIsi"><i class="fas fa-align-left mr-1"></i> Isi Surat
                                    <span class="text-danger"> *</span>
                                </label>
                                <textarea class="form-control required" id="editIsi" name="isi" rows="15" placeholder="Masukkan isi surat" required></textarea>
                            </div>

                        </div>
                    </div>
                </div> <!-- Custom separator line -->
                <div style="height: 1px; background-color: #dee2e6; "></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gradient-secondary btn-sm" data-dismiss="modal">
                        <i class="fas fa-times"></i> Tutup
                    </button> <button type="submit" class="btn bg-gradient-primary btn-sm text-white">
                        <i class="fas fa-save"></i> Ubah </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Tanda Tangan -->
<div class="modal fade" id="signatureModal" tabindex="-1" role="dialog" aria-labelledby="signatureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="signatureModalLabel">
                    <i class="fas fa-pencil-alt mr-1"></i>
                    Tanda Tangan Surat
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-center">
                    <canvas id="signature-pad" width="300" height="200" style="border:1px solid #000;"></canvas>
                </div>
                <small class="form-text text-muted mt-3" style="font-size: 7px!important;">Tanda tangan dikotak atas, menandatangani = menyetujui surat.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="button" class="btn btn-warning" onclick="clearSignature()">
                    <i class="fas fa-redo"></i> Reset
                </button>
                <button type="button" class="btn btn-primary" onclick="saveSignature()">
                    <i class="fas fa-save"></i> Simpan & Setujui
                </button>
            </div>
        </div>
    </div>
</div>
<!--  -->
<!-- <div class="modal fade" id="signatureModal" tabindex="-1" role="dialog" aria-labelledby="signatureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="signatureModalLabel">Tanda Tangan Surat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-center">
                    <canvas id="signature-pad" width="300" height="200" style="border:1px solid #000;"></canvas>
                </div>
                <small class="form-text text-muted mt-3" style="font-size: 7px!important;">Tanda tangan dikotak atas, menandatangani = menyetujui surat.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-warning" onclick="clearSignature()">Reset</button>
                <button type="button" class="btn btn-primary" onclick="saveSignature()">Simpan Tanda Tangan</button>
            </div>
        </div>
    </div>
</div> -->


@endsection
@push('js')

<script>
    let isSubmitting = false;

    $(document).ready(function() {
        $('#form-id').on('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return;
            }
            isSubmitting = true;
            $('button[type="submit"]').prop('disabled', true).text('Menyimpan...');
        });
    });
</script>


<script>
    $(document).ready(function() {
        var userRole = '{{ auth()->user()->role->nama_role }}';

        $('.btn-reject').click(function() {
            var suratId = $(this).data('id');
            var baseUrl = `/${userRole}/surat-keluar/`;

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Jika menolak surat ini, tanda tangan akan di kosongkan",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, tolak surat!',
                reverseButtons: true,
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary btn-md',
                    cancelButton: 'btn btn-danger btn-md'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: baseUrl + 'tolak/' + suratId,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire(
                                    'Ditolak!',
                                    response.message, // Menggunakan pesan dari server
                                    'success'
                                )
                                .then(() => {
                                    location.reload();
                                });
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error', 'Terjadi kesalahan saat menolak surat', 'error');
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
        var baseUrl = `/${userRole}/surat-keluar/`;
        // Edit surat keluar
        $('.edit-surat').click(function() {
            var suratId = $(this).data('id');


            $.get(baseUrl + suratId + '/edit', function(response) {
                var arsip = response.arsip;
                $('#editSuratId').val(arsip.id);
                $('#editNoSurat').val(arsip.no_surat);
                $('#editTglSurat').val(arsip.tgl_surat);
                $('#editTujuanKeluar').val(arsip.tujuan_keluar);
                CKEDITOR.instances.editIsi.setData(arsip.isi);

                $('#editNoArsip').val(arsip.no_arsip);
                $('#editNamaFile').val(arsip.nama_file);
                $('#editKeterangan').text(arsip.keterangan);
                $('#editValidator').val(arsip.validator);
                $('#editUserId').val(arsip.user_id);
                $('#editKategoriId').val(arsip.kategori_id);
                $('#editSuratModal').modal('show');
            });
        });

        // Update surat keluar
        $('#editForm').submit(function(e) {
            e.preventDefault();
            var suratId = $('#editSuratId').val();
            var formData = new FormData(this);


            formData.set('isi', CKEDITOR.instances.editIsi.getData());
            $.ajax({
                url: baseUrl + suratId,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(result) {
                    if (result.success) {
                        $('#editSuratModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data surat keluar berhasil diperbarui.',
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Gagal memperbarui data surat keluar.',
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat memperbarui data surat keluar.',
                    });
                }
            });
        });


        // Konfirmasi hapus surat keluar
        $('.delete-surat').click(function() {
            var suratId = $(this).data('id');


            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Setelah dihapus, Anda tidak akan dapat memulihkan surat keluar ini.",
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
                        url: baseUrl + suratId,
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
                                    text: 'Surat keluar telah dihapus!',
                                    icon: 'success'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Terjadi kesalahan Pada Sistem!', 'Gagal menghapus surat keluar.', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Terjadi kesalahan Pada Sistem!', 'Gagal menghapus surat keluar.', 'error');
                        }
                    });
                }
            });
        });

        // Menangani preview file
        $('.preview-file').click(function() {
            var file = $(this).data('file');
            var fileExtension = file.split('.').pop().toLowerCase();
            var fileUrl = window.location.origin + '/storage/arsip/suratkeluar/' + file.replace('/admin/arsip/suratkeluar/', '');

            if (fileExtension === 'pdf') {
                var pdfHtml = '<embed src="' + fileUrl + '" type="application/pdf" width="100%" height="600px">';
                $('#previewFileContainer').html(pdfHtml);
            } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                var imgHtml = '<img src="' + fileUrl + '" class="img-fluid">';
                $('#previewFileContainer').html(imgHtml);
            }

            $('#downloadFileButton').attr('href', fileUrl);
            $('#previewFileModal').modal('show');
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const suratLinks = document.querySelectorAll('.lihat-isi-surat');

        suratLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                const suratId = this.getAttribute('data-id');
                const statusKeluar = this.getAttribute('data-status-keluar');
                const role = '{{ auth()->user()->role->nama_role }}';

                console.log(statusKeluar); // Debugging output

                // Menangani jika surat ditolak
                if (statusKeluar === 'ditolak') {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Perhatian!',
                        text: 'Surat tidak disetujui (ditolak).',
                        confirmButtonText: 'Ok',
                    }).then((result) => {
                        if (result.isConfirmed || result.isDismissed) {
                            location.reload();
                        }
                    });
                    return;
                }

                // Menangani jika surat belum disetujui
                if ((role === 'admin' || role === 'pegawai') && statusKeluar !== 'disetujui') {
                    event.preventDefault();
                    showSuratBelumDisetujui();
                    return;
                }

                let url;
                if (role === 'admin') {
                    url = `/admin/surat-keluar/pdf/${suratId}`;
                } else if (role === 'pegawai') {
                    url = `/pegawai/surat-keluar/pdf/${suratId}`;
                } else if (role === 'dekan') {
                    url = `/dekan/surat-keluar/pdf/${suratId}`;
                } else if (role === 'kaprodi') {
                    url = `/kaprodi/surat-keluar/pdf/${suratId}`;
                } else {
                    console.error('Role pengguna tidak diketahui');
                    return;
                }

                const printWindow = window.open('', '_blank', 'width=1920,height=1080,left=300,top=200');

                printWindow.addEventListener('load', function() {
                    printWindow.focus();
                });

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        printWindow.document.open();
                        printWindow.document.write(html);
                        printWindow.document.close();
                    })
                    .catch(error => console.error('Error loading the page: ', error));
            });
        });
    });

    function showSuratBelumDisetujui() {
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian!',
            text: 'Surat ini belum disetujui.',
            confirmButtonText: 'Ok',
        }).then((result) => {
            if (result.isConfirmed || result.isDismissed) {
                location.reload();
            }
        });
    }
</script>

<script src="{{ asset('argon/ckeditor/ckeditor.js') }}"></script>


<script>
    CKEDITOR.replace('editIsi', {
        tabSpaces: 6,
        width: '100%',
        height: 550,

    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        CKEDITOR.replace('isi', {
            tabSpaces: 6,
            width: '100%',
            height: 550,
        });

        document.getElementById('form-id').addEventListener('submit', function(e) {
            var isiCKEditor = CKEDITOR.instances.isi.getData();
            var editorContainer = CKEDITOR.instances.isi.container.$;
            var errorDiv = document.getElementById('isiError');

            if (!isiCKEditor) {
                errorDiv.style.display = 'block';
                editorContainer.style.border = '1px solid red';
                e.preventDefault();
            } else {
                errorDiv.style.display = 'none';
                editorContainer.style.border = '';
            }
        });
    });

</script>



<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script>
    $('#signatureModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var suratId = button.data('id');
        $(this).data('id', suratId);
    });

    function clearSignature() {
        signaturePad.clear();
    }

    var canvas = document.getElementById('signature-pad');
    var signaturePad = new SignaturePad(canvas);

    function saveSignature() {
        if (signaturePad.isEmpty()) {
            Swal.fire('Error', 'Mohon berikan tanda tangan terlebih dahulu.', 'error');
            return;
        }
        var data = signaturePad.toDataURL();
        var suratId = $('#signatureModal').data('id');
        var url = '{{ route("surat-keluar.tandaTangan", ["id" => ":id"]) }}'.replace(':id', suratId);

        fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    signature: data
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Berhasil', 'Tanda tangan berhasil disimpan.', 'success').then(() => {
                        $('#signatureModal').modal('hide');
                        $('.btn-signature').prop('disabled', true);
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', 'Gagal menyimpan tanda tangan.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Terjadi kesalahan saat menyimpan tanda tangan.', 'error');
            });
    }
</script>
<script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('argon/js/custom-datatables.js') }}"></script>
@endpush
