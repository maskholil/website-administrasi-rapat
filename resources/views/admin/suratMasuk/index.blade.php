@section('title', 'SIAR - Tabel Surat Masuk')
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
<h6 class="h2 text-white d-none d-inline-block mb-0">Halaman Surat Masuk </h6>
<nav aria-label="breadcrumb" class=" d-md-inline-block ml-xl-2 mt-md-2 mt-sm-2 ml-md-0">
    <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home"></i></a></li>
        <li class="breadcrumb-item"><a href="{{ route(Auth::user()->role->nama_role .'.surat-masuk.index') }}">Tables</a></li>
        <li class="breadcrumb-item active" aria-current="page">Surat Masuk</li>
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
                            <h5 class="mb-0">Tabel Surat Masuk</h5>
                            <p class="text-sm mb-2">
                                Halaman ini menampilkan seluruh surat masuk.
                            </p>
                        </div>
                        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('pegawai'))
                        <div class="ml-auto ms-auto my-auto mt-lg-0 mt-4">
                            <div class="ms-auto my-auto">
                                <button type="button" class="btn bg-gradient-primary btn-sm mb-0 text-white" data-toggle="modal" data-target="#createSuratModal">
                                    +&nbsp; Tambah Surat Masuk
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Tabel Surat Masuk -->
                <div class="table-responsive mb-4">
                    @php
                    $counter = 1;
                    @endphp
                    <table class="table align-items-center table-flush mb-2" id="datatable-search">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>No Surat</th>
                                <th>Kategori</th>
                                <th>Tanggal Surat</th>
                                <th>Kepada</th>
                                <th>Status</th>
                                <th>Isi</th>
                                <th>No Arsip</th>
                                <th>Nama File</th>
                                <th>Keterangan</th>
                                <th>Pengelola</th>
                                <th>File</th>

                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach ($arsip as $surat)
                            <tr>
                                <th scope="row">{{ $counter++ }}</th>
                                <td>{{ $surat->no_surat }}</td>
                                <td>{{ $surat->kategori->nama_kategori }}</td>

                                <td>{{ \Carbon\Carbon::parse($surat->tgl_surat)->isoFormat('D MMMM YYYY') }}
                                </td>

                                <td>
                                    @foreach($surat->tujuanUsers as $tujuanUser)
                                    {{ $tujuanUser->name }}@if(!$loop->last), @endif <br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($surat->tujuanUsers as $tujuanUser)
                                    @if ($tujuanUser->pivot->status_masuk === 'diterima')
                                    <span class=" badge-success ">Diterima</span>
                                    @elseif ($tujuanUser->pivot->status_masuk === 'disposisi')
                                    <span class=" badge-warning">Disposisi</span>
                                    <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Lihat Detail Keterangan" onclick="showDetailModal({{$tujuanUser->pivot->id}})">
                                        <i class="fas fa-eye text-danger"></i>
                                    </a>
                                    @else
                                    <span class=" badge-primary">Menunggu</span>
                                    @endif
                                    @if(!$loop->last)<br>@endif
                                    @endforeach
                                </td>
                                <td>
                                    @php
                                    $isi = strip_tags($surat->isi);
                                    $maxLength = 40; // Panjang maksimum teks yang ingin ditampilkan

                                    if (strlen($isi) > $maxLength) {
                                    $truncated = substr($isi, 0, $maxLength);
                                    $lastSpace = strrpos($truncated, ' ');

                                    if ($lastSpace !== false) {
                                    $truncated = substr($truncated, 0, $lastSpace);
                                    }

                                    echo $truncated . '...';
                                    } else {
                                    echo $isi;
                                    }
                                    @endphp
                                </td>
                                <td>{{ $surat->no_arsip }}</td>
                                <td>{{ $surat->nama_file }}</td>
                                <td class="text-center">{{ $surat->keterangan ? $surat->keterangan : '-' }}</td>
                                <td>{{ $surat->user->name }}</td>
                                <td>
                                    <!-- icon dinamis -->
                                    @php
                                    $ext = strtolower(pathinfo($surat->file, PATHINFO_EXTENSION));
                                    $icon = 'fa-file';
                                    if (in_array($ext, ['pdf'])) {
                                    $icon = 'fa-file-pdf';
                                    } elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                                    $icon = 'fa-file-image';
                                    }
                                    @endphp
                                    <!-- menampilkan preview file -->
                                    @if(in_array(pathinfo($surat->file, PATHINFO_EXTENSION), ['pdf', 'jpg', 'jpeg', 'png', 'gif']))
                                    <a href="javascript:;" class="preview-file" data-file="{{ $surat->file }}">
                                        <i class="fas {{ $icon }}"></i> Lihat File
                                    </a>
                                    @else
                                    {{ $surat->file }}
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="btn-group">
                                        @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('pegawai'))

                                        <a href="javascript:;" class="btn btn-sm btn-info view-surat" data-id="{{ $surat->id }}" data-toggle="tooltip" data-original-title="Lihat Data">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="javascript:;" class="btn btn-sm btn-warning edit-surat" data-id="{{ $surat->id }}" data-tujuan-ids="{{ json_encode($surat->tujuanUsers->pluck('user_id')->toArray()) }}" data-toggle="tooltip" data-original-title="Edit Data">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="javascript:;" class="btn btn-sm btn-youtube delete-surat" data-id="{{ $surat->id }}" data-toggle="tooltip" data-original-title="Hapus Data">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                    @else
                                    @php
                                    $tujuanUser = $surat->tujuanUsers->where('id', Auth::user()->id)->first();
                                    @endphp
                                    @if ($tujuanUser)
                                    @if ($tujuanUser->pivot->status_masuk === 'diterima')
                                    <span class="badge badge-success">Surat Diterima</span>
                                    @elseif ($tujuanUser->pivot->status_masuk === 'disposisi')
                                    <span class="badge badge-warning">Telah Disposisi Ulang</span>

                                    @else
                                    <button class="btn btn-sm btn-success terima-surat mx-1" data-id="{{ $surat->id }}">Terima</button>
                                    <button class="btn btn-sm btn-warning disposisi-surat mx-1" data-id="{{ $surat->id }}">Disposisi</button>
                                    @endif
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

<!-- Modal detail disposisi -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel"> <i class="fas fa-eye mr-1"></i>
                    Keterangan Disposisi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="modalContent" style="font-size: 18px; line-height: 1.5; padding: 20px; border: 1px solid #ddd; border-radius: 4px;">
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-2"></i>Close</button>

            </div>
        </div>
    </div>
</div>


<script>
    function showDetailModal(pivotId) {
        // console.log(pivotId);
        let role = '{{ auth()->user()->role->nama_role }}';

        let url;
        if (role === 'pegawai') {
            url = '/pegawai/get-pivot-details/' + pivotId;
        } else if (role === 'admin') {
            url = '/admin/get-pivot-details/' + pivotId;
        } else if (role === 'dekan') {
            url = '/dekan/get-pivot-details/' + pivotId;
        } else if (role === 'kaprodi') {
            url = '/kaprodi/get-pivot-details/' + pivotId;
        }

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                // Update modal content
                document.getElementById('modalContent').textContent = data.keterangan || 'No description provided.';
                $('#detailModal').modal('show');
            })
            .catch(error => {
                console.error('Error loading the details:', error);
                alert('Failed to fetch details: ' + error.message);
            });
    }
</script>



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

@if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('pegawai'))


<!-- Modal Tambah Surat Masuk -->
<div class="modal fade" id="createSuratModal" tabindex="-1" role="dialog" aria-labelledby="createSuratModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSuratModalLabel"><i class="fas fa-envelope-open-text mr-2"></i> Form Tambah Surat Masuk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Custom separator line -->
            <div style="height: 1px; background-color: #dee2e6;"></div>
            <form id="form_submitted" action="{{ route(Auth::user()->role->nama_role .'.surat-masuk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="hidden" name="jenis_arsip" value="surat masuk">
                            <div class="form-group">
                                <label for="no_surat"><i class="fas fa-envelope mr-1"></i> No Surat<span class="text-danger"> *</span></label>
                                <input type="text" class="form-control required" id="no_surat" name="no_surat" placeholder="Masukkan no surat" required>
                            </div>
                            <div class="form-group">
                                <label for="tgl_surat"><i class="fas fa-calendar-alt mr-1"></i> Tanggal Surat<span class="text-danger"> *</span></label>
                                <input type="date" class="form-control required" id="tgl_surat" name="tgl_surat" value="{{ now()->format('Y-m-d') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="tujuan">
                                    <i class="fas fa-user-tie mr-1"></i> Tujuan
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control required" id="tujuan" name="tujuan[]" multiple required>
                                    @foreach($tujuans as $index => $user)
                                    <option value="{{ $user->id }}">{{ $index + 1 }}. {{ $user->name }} - {{ $user->email }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">
                                    Jika lebih dari 1 tujuan, gunakan tombol <strong>CTRL (Tahan)</strong> + <strong>Pilih Item </strong>.
                                </small>
                            </div>
                            <div class="form-group">
                                <label for="isi"><i class="fas fa-align-left mr-1"></i> Isi Surat<span class="text-danger"> *</span></label>
                                <textarea class="form-control required" id="isi" name="isi" rows="4" placeholder="Masukkan isi surat" required></textarea>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="no_arsip"><i class="fas fa-hashtag mr-1"></i> No Arsip<span class="text-danger"> *</span></label>
                                <input type="text" class="form-control required" id="no_arsip" name="no_arsip" placeholder="Masukkan nomor arsip" required>
                            </div>
                            <div class="form-group">
                                <label for="nama_file"><i class="fas fa-file mr-1"></i> Nama File<span class="text-danger"> *</span></label>
                                <input type="text" class="form-control required" id="nama_file" name="nama_file" placeholder="Masukkan nama file" required>
                            </div>


                            <div class="form-group">
                                <label for="user_id" style="display: none;"><i class="fas fa-user mr-1"></i> Pengguna<span class="text-danger"> *</span></label>
                                <input type="hidden" class="form-control required" id="user_id" name="user_id" value="{{ auth()->user()->id }}" required>
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
                                <label for="file"><i class="fas fa-file-upload mr-1"></i> File<span class="text-danger">
                                        *</span></label>
                                <input type="file" class="form-control" id="file" name="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>

                                <small class="form-text text-muted">
                                    Upload file dgn format .pdf,.doc,.docx,.jpg,.jpeg atau .png
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="keterangan"><i class="fas fa-info-circle mr-1"></i> Keterangan</span></label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Masukkan keterangan"></textarea>
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

<!-- Modal Lihat Surat Masuk -->
<div class="modal fade" id="viewSuratModal" tabindex="-1" role="dialog" aria-labelledby="viewSuratModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewSuratModalLabel"><i class="fas fa-envelope-open-text mr-2"></i> Detail Surat Masuk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Custom separator line -->
            <div style="height: 1px; background-color: #dee2e6;"></div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="viewNoSurat"><i class="fas fa-envelope mr-1"></i> No Surat</label>
                            <input type="text" class="form-control" id="viewNoSurat" name="no_surat" readonly>
                        </div>
                        <div class="form-group"> <label for="viewKategori">
                                <i class="fas fa-list mr-1"></i> Kategori
                            </label> <input type="text" class="form-control" id="viewKategori" name="kategori" readonly>
                        </div>

                        <div class="form-group">
                            <label for="viewTglSurat"><i class="fas fa-calendar-alt mr-1"></i> Tanggal Surat</label>
                            <input type="date" class="form-control" id="viewTglSurat" name="tgl_surat" readonly>
                        </div>
                        <div class="form-group">
                            <label for="viewKepada"><i class="fas fa-user-tie mr-1"></i> Kepada</label>
                            <textarea class="form-control" id="viewKepada" name="kepada" readonly></textarea>
                        </div>
                        <div class="form-group">
                            <label for="viewIsi"><i class="fas fa-align-left mr-1"></i> Isi Surat</label>
                            <textarea class="form-control" id="viewIsi" name="isi" rows="4" readonly></textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="viewNoArsip"><i class="fas fa-hashtag mr-1"></i> No Arsip</label>
                            <input type="text" class="form-control" id="viewNoArsip" name="no_arsip" readonly>
                        </div>
                        <div class="form-group"> <label for="viewNamaFile">
                                <i class="fas fa-file mr-1"></i> Nama File
                            </label> <input type="text" class="form-control" id="viewNamaFile" name="nama_file" readonly>
                        </div>


                        <div class="form-group"> <label for="viewUser">
                                <i class="fas fa-user mr-1"></i> Penanggung Jawab
                            </label> <input type="text" class="form-control" id="viewUser" name="user" readonly>
                        </div>
                        <div class="form-group"> <label for="viewKeterangan">
                                <i class="fas fa-info-circle mr-1"></i> Keterangan
                            </label> <textarea class="form-control" id="viewKeterangan" name="keterangan" rows="3" readonly></textarea>
                        </div>
                        <div class="form-group">
                            <label for="viewFile">
                                <i class="fas fa-file-download mr-1"></i> File Surat Masuk
                            </label>


                            <div class="float-right">
                                <div id="viewFile" title="Download File Surat Masuk">
                                    <a href="#" class="btn btn-primary">
                                        <i class="fas fa-download"></i> Download File
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>



                </div>
            </div> <!-- Custom separator line -->
            <div style="height: 1px; background-color: #dee2e6; "></div>
            <div class="modal-footer"> <button type="button" class="btn btn-gradient-secondary btn-sm" data-dismiss="modal"> <i class="fas fa-times"></i> Tutup </button> </div>
        </div>
    </div>
</div>

<!-- Modal Edit Surat Masuk -->
<div class="modal fade" id="editSuratModal" tabindex="-1" role="dialog" aria-labelledby="editSuratModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSuratModalLabel"><i class="fas fa-envelope-open-text mr-2"></i> Form Edit Surat Masuk</h5> <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
            </div> <!-- Custom separator line -->
            <div style="height: 1px; background-color: #dee2e6;"></div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT') <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6"> <input type="hidden" id="editSuratId" name="id">
                            <div class="form-group">
                                <label for="editNoSurat">
                                    <i class="fas fa-envelope mr-1"></i> No Surat
                                    <span class="text-danger"> *</span></label>
                                <input type="text" class="form-control required" id="editNoSurat" name="no_surat" placeholder="Masukkan no surat" required>
                            </div>
                            <div class="form-group">
                                <label for="editTglSurat">
                                    <i class="fas fa-calendar-alt mr-1"></i> Tanggal Surat
                                    <span class="text-danger"> *</span></label>
                                <input type="date" class="form-control required" id="editTglSurat" name="tgl_surat" required>
                            </div>
                            <div class="form-group">
                                <label for="tujuan">
                                    <i class="fas fa-user-tie mr-1"></i> Tujuan
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control required" id="tujuan" name="tujuan[]" multiple required>
                                    @foreach($tujuans as $index => $user)
                                    <option value="{{ $user->id }}">{{ $index + 1 }}. {{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">
                                    Jika lebih dari 1, gunakan tombol <strong>CTRL (Tahan)</strong> + <strong>Klik Tujuan </strong>.
                                </small>
                            </div>
                            <div class="form-group"> <label for="editIsi"><i class="fas fa-align-left mr-1"></i> Isi Surat<span class="text-danger"> *</span></label> <textarea class="form-control required" id="editIsi" name="isi" rows="4" placeholder="Masukkan isi surat" required></textarea> </div>
                            <div class="form-group">
                                <label for="editKeterangan">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Keterangan
                                </label>
                                <textarea class="form-control required" id="editKeterangan" name="keterangan" rows="3" placeholder="Masukkan keterangan"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6"> <input type="hidden" id="editArsipId" name="arsip_id">
                            <div class="form-group">
                                <label for="editNoArsip">
                                    <i class="fas fa-hashtag mr-1"></i>
                                    No Arsip<span class="text-danger"> *</span>
                                </label> <input type="text" class="form-control required" id="editNoArsip" name="no_arsip" placeholder="Masukkan nomor arsip" required>
                            </div>
                            <div class="form-group">
                                <label for="editNamaFile">
                                    <i class="fas fa-file mr-1"></i>
                                    Nama File<span class="text-danger"> *</span>
                                </label> <input type="text" class="form-control required" id="editNamaFile" name="nama_file" placeholder="Masukkan nama file" required>
                            </div>


                            <div class="form-group">
                                <label for="editUserId">
                                    <i class="fas fa-user mr-1"></i>
                                    Penanggung Jawab
                                    <span class="text-danger"> *</span>
                                </label>
                                <select class="form-control required" id="editUserId" name="user_id" required readonly>
                                    <option value="" disabled selected>- Pilih User -</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-group">
                                <label for="editKategoriId"><i class="fas fa-list mr-1"></i> Kategori
                                    <span class="text-danger"> *</span></label>
                                <select class="form-control required" id="editKategoriId" name="kategori_id" required>
                                    <option value="" disabled selected>- Pilih Kategori -</option> @foreach($kategori as $kat)
                                    <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option> @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="editFile"><i class="fas fa-file-upload mr-1"></i> File (Opsional)</label>
                                <input type="file" class="form-control" id="editFile" name="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <small class="form-text text-muted">File harus berekstensi PDF, DOC, DOCX, JPG, JPEG, PNG.</small>
                            </div>

                        </div>
                    </div>
                </div> <!-- Custom separator line -->
                <div style="height: 1px; background-color: #dee2e6; "></div>
                <div class="modal-footer"> <button type="button" class="btn btn-gradient-secondary btn-sm" data-dismiss="modal"> <i class="fas fa-times"></i> Tutup </button> <button type="submit" class="btn bg-gradient-primary btn-sm text-white"> <i class="fas fa-save"></i> Ubah </button> </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('js')

<script>
    let isSubmitting = false;

    $(document).ready(function() {
        $('#form_submitted').on('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return;
            }
            isSubmitting = true;
            $('button[type="submit"]').prop('disabled', true).text('Menyimpan...');
        });
    });
</script>

<!-- modal konfirmasi dan diposisi -->
<script>
    $(document).ready(function() {
        let suratId;
        let role = '{{ auth()->user()->role->nama_role }}';

        // Menangani klik tombol "Terima"
        $(document).on('click', '.terima-surat', function() {
            suratId = $(this).data('id');

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
                        url = '/pegawai/surat-masuk/terima/' + suratId;
                    } else if (role === 'dekan') {
                        url = '/dekan/surat-masuk/terima/' + suratId;
                    } else if (role === 'kaprodi') {
                        url = '/kaprodi/surat-masuk/terima/' + suratId;
                    }

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            '_token': '{{ csrf_token() }}'
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


        // Menangani klik tombol "Disposisi"
        $(document).on('click', '.disposisi-surat', function() {
            suratId = $(this).data('id');

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
                        url = '/pegawai/surat-masuk/disposisi/' + suratId;
                    } else if (role === 'dekan') {
                        url = '/dekan/surat-masuk/disposisi/' + suratId;
                    } else if (role === 'kaprodi') {
                        url = '/kaprodi/surat-masuk/disposisi/' + suratId;
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

        $('.view-surat').click(function() {
            var arsipId = $(this).data('id');
            var baseUrl = `/${userRole}/surat-masuk/`;

            // console.log(baseUrl + arsipId);

            $.ajax({
                url: baseUrl + arsipId,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var arsip = response.arsip;
                    var kategori = arsip.kategori;
                    var user = arsip.user;
                    var tujuanUsers = response.tujuanUsers || [];

                    $('#viewNoSurat').val(arsip.no_surat);
                    $('#viewTglSurat').val(arsip.tgl_surat);
                    $('#viewIsi').text(arsip.isi);
                    $('#viewNoArsip').val(arsip.no_arsip);
                    $('#viewNamaFile').val(arsip.nama_file);
                    $('#viewKeterangan').text(arsip.keterangan);
                    $('#viewUser').val(user.name);
                    $('#viewKategori').val(kategori.nama_kategori);

                    var kepada = tujuanUsers.map(function(user, index) {
                        return (index + 1) + '. ' + user.name;
                    }).join('\n');
                    $('#viewKepada').val(kepada);

                    if (arsip.file) {
                        $('#viewFile').html('<a href="/storage/arsip/suratmasuk/' + arsip.file + '" target="_blank" class="btn btn-gradient-primary btn-sm"><i class="fas fa-file-download mr-1"></i> Download</a>');
                    } else {
                        $('#viewFile').html('<span class="text-danger">Tidak ada file</span>');
                    }

                    $('#viewSuratModal').modal('show');
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error',
                        'Terjadi kesalahan saat melihat data surat masuk', 'error'
                    );
                }

            });
        });


        // Edit arsip
        // $('.edit-surat').click(function() {
        //     var suratId = $(this).data('id');
        //     var baseUrl = `/${userRole}/surat-masuk/`;
        //     $.get(baseUrl + suratId + '/edit', function(response) {
        //         if (response.success === false) {

        //             Swal.fire('Error!', response.message, 'error');
        //         } else {
        //             var surat = response.arsip;
        //             var tujuanUserIds = response.tujuanUserIds;


        //             var allDisposisi = true;

        //             $.each(surat.tujuanUsers, function(index, tujuanUser) {
        //                 if (tujuanUser.pivot.status_masuk !== 'disposisi') {
        //                     allDisposisi = false;
        //                     return false;

        //                 }
        //             });

        //             if (allDisposisi) {
        //                 Swal.fire({
        //                     icon: 'warning',
        //                     title: 'Perhatian!',
        //                     text: 'Anda tidak dapat melakukan perubahan data, karena data telah didisposisikan, kunjungi halaman disposisi.',
        //                 });
        //             } else {
        //                 $('#editSuratId').val(surat.id);
        //                 $('#editNoSurat').val(surat.no_surat);
        //                 $('#editTglSurat').val(surat.tgl_surat);
        //                 $('#editIsi').text(surat.isi);
        //                 $('#editNoArsip').val(surat.no_arsip);
        //                 $('#editNamaFile').val(surat.nama_file);
        //                 $('#editKeterangan').text(surat.keterangan);
        //                 $('#editUserId').val(surat.user_id);
        //                 $('#editKategoriId').val(surat.kategori_id);

        //                 // Menghapus pilihan tujuan sebelumnya
        //                 $('#tujuan').val(null).trigger('change');

        //                 // Memilih opsi tujuan berdasarkan tujuanUserIds
        //                 $.each(tujuanUserIds, function(index, userId) {
        //                     $('#tujuan option[value="' + userId + '"]').prop('selected', true);
        //                 });
        //                 $('#tujuan').trigger('change');

        //                 $('#editSuratModal').modal('show');
        //             }
        //         }
        //     }).fail(function(xhr) {
        //         if (xhr.status === 403) {
        //             Swal.fire('Error!', xhr.responseJSON.message, 'error');
        //         } else {
        //             Swal.fire('Error!', 'Terjadi kesalahan saat mengambil data surat.', 'error');
        //         }
        //     });
        // });

        // Edit arsip
        $('.edit-surat').click(function() {
            var suratId = $(this).data('id');
            var baseUrl = `/${userRole}/surat-masuk/`;
            $.get(baseUrl + suratId + '/edit', function(response) {
                if (response.success === false) {
                    Swal.fire('Error!', response.message, 'error');
                } else {
                    var surat = response.arsip;
                    var tujuanUserIds = response.tujuanUserIds;

                    $('#editSuratId').val(surat.id);
                    $('#editNoSurat').val(surat.no_surat);
                    $('#editTglSurat').val(surat.tgl_surat);
                    $('#editIsi').text(surat.isi);
                    $('#editNoArsip').val(surat.no_arsip);
                    $('#editNamaFile').val(surat.nama_file);
                    $('#editKeterangan').text(surat.keterangan);
                    $('#editUserId').val(surat.user_id);
                    $('#editKategoriId').val(surat.kategori_id);

                    // Menghapus pilihan tujuan sebelumnya
                    $('#tujuan').val(null).trigger('change');

                    // Memilih opsi tujuan berdasarkan tujuanUserIds
                    $.each(tujuanUserIds, function(index, userId) {
                        $('#tujuan option[value="' + userId + '"]').prop('selected', true);
                    });
                    $('#tujuan').trigger('change');

                    $('#editSuratModal').modal('show');
                }
            }).fail(function(xhr) {
                if (xhr.status === 403) {
                    Swal.fire('Error!', xhr.responseJSON.message, 'error');
                } else {
                    Swal.fire('Error!', 'Terjadi kesalahan saat mengambil data surat.', 'error');
                }
            });
        });

        // Update arsip
        $('#editForm').submit(function(e) {
            e.preventDefault();

            var arsipId = $('#editSuratId').val();
            var formData = new FormData(this);
            var baseUrl = `/${userRole}/surat-masuk/`;

            $.ajax({
                url: baseUrl + arsipId,
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
                            text: 'Data arsip berhasil diperbarui.',
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Gagal memperbarui data arsip.',
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat memperbarui data arsip.',
                    });
                }
            });
        });

        // Konfirmasi hapus surat masuk
        $('.delete-surat').click(function() {
            var suratId = $(this).data('id');
            var baseUrl = `/${userRole}/surat-masuk/`;
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Setelah dihapus, Anda tidak akan dapat memulihkan surat masuk ini.",
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
                                    text: 'Surat masuk telah dihapus!',
                                    icon: 'success'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Terjadi kesalahan Pada Sistem!', 'Gagal menghapus surat masuk.', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Terjadi kesalahan Pada Sistem!', 'Gagal menghapus surat masuk.', 'error');
                        }
                    });
                }
            });
        });
    });

    // Menangani preview file
    $('.preview-file').click(function() {
        var file = $(this).data('file');
        var fileExtension = file.split('.').pop().toLowerCase();
        var fileUrl = window.location.origin + '/storage/arsip/suratmasuk/' + file.replace('/kaprodi/arsip/suratmasuk/', '');

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
</script>
<script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('argon/js/custom-datatables.js') }}"></script>
@endpush
