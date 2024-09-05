@section('title', 'SIAR - Tabel Rapat')
@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link href="{{ asset('argon/css/custom-datatables.css') }}" rel="stylesheet">
<style>
    .thick-card {
        border: 1px solid #cad1d7;
    }

    .checkbox-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
    }

    .custom-checkbox {
        flex: 1 0 30%;
        min-width: 220px;
        max-width: 30%;
    }

    .list-styled {
        list-style-type: decimal;
        padding-left: 20px;
        font-size: 14px;
        line-height: 1.5;
        column-count: 1;
        column-gap: 20px;
    }

    .list-item {
        margin-bottom: 5px;
        word-wrap: break-word;
        overflow-wrap: break-word;
        padding-right: 10px;
    }

    .truncate {
        width: 250px;
        /* Atur lebar sesuai kebutuhan */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    @media (min-width: 768px) {
        .list-styled {
            column-count: 2;
        }
    }
</style>

@endpush

@section('breadcrumb')
<h6 class="h2 text-white d-none d-inline-block mb-0">Halaman Rapat</h6>
<nav aria-label="breadcrumb" class=" d-md-inline-block ml-xl-2 mt-md-2 mt-sm-2 ml-md-0">
    <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home"></i></a></li>
        <li class="breadcrumb-item"><a href="{{ route(Auth::user()->role->nama_role .'.rapat.index') }}">Tables</a></li>
        <li class="breadcrumb-item active" aria-current="page">Rapat</li>
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
                            <h5 class="mb-0">Tabel Rapat</h5>
                            <p class="text-sm mb-2">
                                Halaman ini menampilkan seluruh rapat.
                            </p>
                        </div>
                        @if (!Auth::user()->hasRole('dosen'))
                        <div class="ml-auto ms-auto my-auto mt-lg-0 mt-4">
                            <div class="ms-auto my-auto">
                                <button type="button" class="btn bg-gradient-primary btn-sm mb-0 text-white" data-toggle="modal" data-target="#createRapatModal">
                                    +&nbsp; Tambah Hasil Rapat
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Tabel Rapat -->
                <div class="table-responsive mb-4">
                    @php
                    $counter = 1;
                    @endphp
                    <table class="table align-items-center table-flush mb-2" id="datatable-search">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Agenda</th>
                                <th>Peserta Hadir</th>
                                <th>Ketua</th>
                                <th>Sekertaris</th>
                                <th>Keputusan</th>
                                <th>File</th>
                                <th>Status Rapat</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach ($rapat as $rap)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $rap->agenda->tentang }}</strong><br>
                                    <span class="text-muted">{{ $rap->agenda->no_agenda }}</span><br>
                                    <span class="text-muted">{{ \Carbon\Carbon::parse($rap->agenda->tanggal)->isoFormat('dddd, D MMMM YYYY') }}</span><br>
                                    <span class="text-muted">{{ ucfirst($rap->agenda->lokasi) }}</span>
                                </td>
                                <td>
                                    @if($rap->peserta && !$rap->peserta->isEmpty())
                                    <ol class="list-styled">
                                        @foreach ($rap->peserta as $index => $peserta)
                                        <li class="list-item">{{ $peserta->nama_peserta }}</li>
                                        @endforeach
                                    </ol>
                                    @else
                                    <span class="text-muted">No Participants</span>
                                    @endif
                                </td>
                                <td>{{ $rap->agenda->pimpinan->name }}</td>
                                <td>{{ $rap->agenda->sekertaris }}</td>
                                <td>{{ Str::limit($rap->keputusan, 25, ' ....') }}</td>

                                <td>
                                    @if($rap->file)
                                    @php
                                    $files = explode(',', $rap->file);
                                    @endphp
                                    @foreach($files as $file)
                                    <a href="{{ Storage::url($file) }}" target="_blank" class="btn btn-sm btn-primary mb-1">
                                        <i class="fas fa-eye"></i> Lihat file
                                    </a>
                                    @if(!$loop->last)
                                    <br>
                                    @endif

                                    @endforeach
                                    @else
                                    <span class="text-muted">No File</span>
                                    @endif
                                </td>
                                <td>
                                    @switch($rap->status_rapat)
                                    @case('diproses')
                                    <span class="badge badge-info">Proses</span>
                                    @break
                                    @case('acc')
                                    <span class="badge badge-success">ACC</span>
                                    @break
                                    @case('revisi')
                                    <span class="badge badge-warning">Revisi</span>
                                    <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Lihat Detail Revisi" onclick="showDetailModal({{ $rap->id }})">
                                        <i class="fas fa-eye text-danger"></i>
                                    </a>
                                    @break
                                    @case('selesai')
                                    <span class="badge badge-primary">Selesai</span>
                                    @break
                                    @default
                                    <span class="badge badge-secondary">Tidak Diketahui</span>
                                    @endswitch
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="javascript:;" class="btn btn-sm btn-primary download-rapat" data-id="{{ $rap->id }}" target="_blank" data-toggle="tooltip" data-original-title="Lihat Hasil Berita Acara">
                                            <i class="fas fa-file-download"></i>
                                        </a>

                                        @if (auth()->user()->id == $rap->agenda->dipimpin)
                                        <a href="javascript:void(0);" class="btn btn-success btn-sm btn-signature" data-toggle="modal" data-target="#signatureModal" data-id="{{ $rap->id }}" data-has-signature="{{ !empty($rap->ttd) ? 'true' : 'false' }}">
                                            <i class="fas fa-signature mr-1"></i> Tanda Tangani
                                        </a>
                                        <a href="javascript:void(0);" class="btn btn-warning btn-sm btn-revise" data-id="{{ $rap->id }}" data-toggle="tooltip" data-original-title="Revisi Rapat">
                                            <i class="fas fa-edit mr-1"></i> Revisi
                                        </a>
                                        @elseif (auth()->user()->hasRole('pegawai'))
                                        <a href="javascript:void(0);" class="btn btn-success btn-sm btn-signature" data-toggle="modal" data-target="#signatureModal" data-id="{{ $rap->id }}" data-has-signature="{{ !empty($rap->ttd) ? 'true' : 'false' }}">
                                            <i class="fas fa-signature mr-1"></i> Tanda Tangani
                                        </a>
                                        @endif

                                        <a href="javascript:;" class="btn btn-sm btn-info view-rapat" data-id="{{ $rap->id }}" data-toggle="tooltip" data-original-title="Lihat Data">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if (!Auth::user()->hasRole('dosen'))
                                        <a href="javascript:;" class="btn btn-sm btn-warning edit-rapat" data-id="{{ $rap->id }}" data-toggle="tooltip" data-original-title="Edit Data">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif
                                        @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('pegawai') )
                                        <a href="javascript:;" class="btn btn-sm btn-youtube delete-rapat" data-id="{{ $rap->id }}" data-toggle="tooltip" data-original-title="Hapus Data">
                                            <i class="fas fa-trash"></i>
                                        </a>
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


<!-- Modal Tambah Rapat -->
<div class="modal fade" id="createRapatModal" tabindex="-1" role="dialog" aria-labelledby="createRapatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createRapatModalLabel"><i class="fas fa-users mr-2"></i> Form Tambah Rapat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Custom separator line -->
            <div style="height: 1px; background-color: #dee2e6;"></div>
            <form action="{{ route(Auth::user()->role->nama_role .'.rapat.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="agenda_id"><i class="fas fa-calendar-alt mr-1"></i> Agenda<span class="text-danger"> *</span></label>
                                <select class="form-control required" id="agenda_id" name="agenda_id" required>
                                    <option value="" disabled selected>- Pilih Agenda -</option>
                                    @foreach ($availableAgendas as $ag)
                                    <option value="{{ $ag->id }}">
                                        {{ $ag->tentang }} ({{ $ag->no_agenda }}) - {{ \Carbon\Carbon::parse($ag->tanggal)->isoFormat('dddd, D MMMM YYYY') }} [{{ ucfirst($ag->lokasi) }}]
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Add Peserta with attendance option in the Modal -->
                            <div class="form-group">
                                <label><i class="fas fa-users mr-1"></i>Absensi Peserta<span class="text-danger"> *</span></label>
                                <div class="card thick-card">
                                    <div class="card-body p-3 pt-0">
                                        <ul class="list-group list-group-flush" data-toggle="checklist">
                                            <li class="list-group-item border-0 flex-column align-items-start ps-0 py-0 mb-3">
                                                <div class="checkbox-grid">
                                                    @foreach ($absen as $p)
                                                    <div class="custom-checkbox checklist-item checklist-item-primary">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="custom-control-input" id="peserta_{{ $p->id }}" name="peserta[{{ $p->id }}]" value="hadir">
                                                            <label class="custom-control-label" for="peserta_{{ $p->id }}">{{ $p->nama_peserta }}</label>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <small class="form-text text-muted">
                                    Silakan centang setiap nama peserta yang hadir di rapat. Kehadiran ini akan dicatat sebagai arsip rapat.
                                </small>

                            </div>
                            <div class="form-group">
                                <label for="keputusan"><i class="fas fa-check-double mr-1"></i> Keputusan<span class="text-danger"> *</span></label>
                                <textarea class="form-control required" id="keputusan" name="keputusan" rows="4" placeholder="Masukkan keputusan rapat" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="file"><i class="fas fa-file-upload mr-1"></i> File</label>
                                <input type="file" class="form-control" id="file" name="file[]" multiple>
                                <small class="form-text text-muted">
                                    Anda bisa mengupload file lebih dari 1, syaratnya anda harus meupload file secara bersamaan.
                                </small>
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


<!-- Modal Lihat Rapat -->
<div class="modal fade" id="viewRapatModal" tabindex="-1" role="dialog" aria-labelledby="viewRapatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewRapatModalLabel"><i class="fas fa-users mr-2"></i> Detail Rapat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Custom separator line -->
            <div style="height: 1px; background-color: #dee2e6;"></div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="viewAgenda"><i class="fas fa-calendar-alt mr-1"></i> Agenda</label>
                            <input type="text" class="form-control" id="viewAgenda" name="agenda" readonly>
                        </div>
                        <div class="form-group">
                            <label for="viewPeserta"><i class="fas fa-user mr-1"></i> Peserta</label>
                            <ol class="list-styled" id="viewPeserta"></ol>
                        </div>

                        <div class="form-group">
                            <label for="viewKeputusan"><i class="fas fa-check-double mr-1"></i> Keputusan</label>
                            <textarea class="form-control" id="viewKeputusan" name="keputusan" rows="4" readonly></textarea>
                        </div>
                        <div class="form-group">
                            <label for="viewFile"><i class="fas fa-file-download mr-1"></i> File</label>
                            <div id="viewFile"></div>
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
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Rapat -->
<div class="modal fade" id="editRapatModal" tabindex="-1" role="dialog" aria-labelledby="editRapatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRapatModalLabel"><i class="fas fa-users mr-2"></i> Form Edit Rapat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Custom separator line -->
            <div style="height: 1px; background-color: #dee2e6;"></div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" id="editRapatId" name="id">
                            <div class="form-group">
                                <label for="editAgenda"><i class="fas fa-calendar-alt mr-1"></i> Agenda</label>
                                <input type="text" class="form-control" id="editAgenda" name="agenda" readonly>
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-users mr-1"></i>Absensi Peserta<span class="text-danger"> *</span></label>
                                <div class="card thick-card">
                                    <div class="card-body p-3 pt-0">
                                        <ul class="list-group list-group-flush" data-toggle="checklist">
                                            <li class="list-group-item border-0 flex-column align-items-start ps-0 py-0 mb-3">
                                                <div class="checkbox-grid" id="editPeserta">
                                                    <!-- Checkbox peserta akan diisi secara dinamis melalui JavaScript -->
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <small class="form-text text-muted">
                                    Silakan centang setiap nama peserta yang hadir di rapat. Kehadiran ini akan dicatat sebagai arsip rapat.
                                </small>
                            </div>
                            <div class="form-group">
                                <label for="editKeputusan"><i class="fas fa-check-double mr-1"></i> Keputusan<span class="text-danger"> *</span></label>
                                <textarea class="form-control required" id="editKeputusan" name="keputusan" rows="4" placeholder="Masukkan keputusan rapat" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="editFile"><i class="fas fa-file-upload mr-1"></i> File (opsional)</label>
                                <input type="file" class="form-control" id="editFile" name="file[]" multiple>
                                <small class="form-text text-muted">
                                    Jika tidak mengubah file, biarkan kosong. Jika ingin menambah file baru, silahkan unggah file baru.
                                </small>
                                <div id="oldFileLinks"></div>
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
                        <i class="fas fa-save"></i> Ubah
                    </button>
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
                    Tanda Tangan Rapat
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-center">
                    <canvas id="signature-pad" width="300" height="200" style="border:1px solid #000;"></canvas>
                </div>
                <small class="form-text text-muted mt-3" style="font-size: 7px!important;">Tanda tangan dikotak atas, menandatangani = menyetujui rapat.</small>
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


<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel"> <i class="fas fa-eye mr-1"></i>
                    Keterangan Revisi</h5>
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


@endsection

@push('js')
<script>
    // tampilkan detail revisi
    function showDetailModal(rapatId) {
        // console.log(rapatId);
        let role = '{{ auth()->user()->role->nama_role }}';

        let url;
        if (role === 'pegawai') {
            url = '/pegawai/rapat/' + rapatId + '/details';
        } else if (role === 'admin') {
            url = '/admin/rapat/' + rapatId + '/details';
        } else if (role === 'dekan') {
            url = '/dekan/rapat/' + rapatId + '/details';
        } else if (role === 'kaprodi') {
            url = '/kaprodi/rapat/' + rapatId + '/details';
        } else if (role === 'ketua') {
            url = '/kaprodi/rapat/' + rapatId + '/details';
        }


        fetch(url)
            .then(response => response.json())
            .then(data => {
                // Update modal content with catatan
                document.getElementById('modalContent').textContent = data.catatan || 'No description provided.';
                $('#detailModal').modal('show');
            })
            .catch(error => {
                console.error('Error loading the details:', error);
                alert('Failed to fetch details: ' + error.message);
            });
    }
</script>


<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script>
    $('#signatureModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var rapatId = button.data('id');
        $(this).data('id', rapatId);
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
        var rapatId = $('#signatureModal').data('id');
        const role = '{{ auth()->user()->role->nama_role }}';
        let url;
        if (role === 'pegawai') {
            url = '{{ route("pegawai.rapat.tandaTangan", ["id" => ":id"]) }}'.replace(':id', rapatId);
        } else if (role === 'dekan') {
            url = '{{ route("dekan.rapat.tandaTangan", ["id" => ":id"]) }}'.replace(':id', rapatId);
        } else if (role === 'kaprodi') {
            url = '{{ route("kaprodi.rapat.tandaTangan", ["id" => ":id"]) }}'.replace(':id', rapatId);
        } else if (role === 'ketua') {
            url = '{{ route("ketua.rapat.tandaTangan", ["id" => ":id"]) }}'.replace(':id', rapatId);
        } else {
            console.error('Role pengguna tidak diketahui');
            return;
        }
        console.log(url);
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
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else if (response.status === 403) {
                    throw new Error('Anda tidak berhak menandatangani rapat ini. hanya pimpinan rapat terkait yang bisa menandatanganinya');
                } else {
                    throw new Error('Terjadi kesalahan saat menyimpan tanda tangan.');
                }
            })
            .then(data => {
                if (data.success) {
                    Swal.fire('Berhasil', 'Tanda tangan berhasil disimpan.', 'success').then(() => {
                        // $('#signatureModal').modal('hide');
                        // $('.btn-signature').prop('disabled', true);
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message || 'Gagal menyimpan tanda tangan.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', error.message, 'error');
            });
    }

    $(document).ready(function() {
        var userRole = '{{ auth()->user()->role->nama_role }}';

        $('.btn-revise').click(function() {
            var rapatId = $(this).data('id');
            var baseUrl = `/${userRole}/rapat/`;


            Swal.fire({
                title: 'Anda ingin merevisi rapat ini ?',
                text: "Jika ingin merevisi berita acara rapat ini, Anda perlu memasukkan catatan revisinya",
                input: 'textarea',
                inputPlaceholder: 'Masukkan catatan revisi...',
                inputAttributes: {
                    'aria-label': 'Masukkan catatan revisi disini'
                },
                showCancelButton: true,
                confirmButtonText: 'Ya, revisi rapat!',
                reverseButtons: true,
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-warning btn-md',
                    cancelButton: 'btn btn-secondary btn-md'
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    $.ajax({

                        url: baseUrl + 'revisi/' + rapatId,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            catatan: result.value // Pass the note as the 'catatan'
                        },
                        success: function(response) {
                            Swal.fire(
                                'Direvisi!',
                                'Rapat telah direvisi dengan catatan Anda.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error', 'Terjadi kesalahan saat merevisi rapat', 'error');
                        }
                    });
                }
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rapatLinks = document.querySelectorAll('.download-rapat');

        rapatLinks.forEach(link => {
            link.addEventListener('click', function() {
                const rapatId = this.getAttribute('data-id');
                const role = '{{ auth()->user()->role->nama_role }}';

                let url;
                url = `/${role}/rapat/berita-acara/${rapatId}`;
                if (!['admin', 'pegawai', 'ketua', 'dekan', 'kaprodi','dosen'].includes(role)) {
                    console.error('Role pengguna tidak diketahui');
                    return;
                }

                // You might need to ensure that the server sends the correct headers to handle a download or display a PDF.
                const downloadWindow = window.open(url, '_blank');
                downloadWindow.focus();
            });
        });
    });

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
</script>
<script>
    $(document).ready(function() {
        var userRole = '{{ auth()->user()->role->nama_role }}';
        var baseUrl = `/${userRole}/rapat/`;

        // Lihat rapat
        $('.view-rapat').click(function() {
            var rapatId = $(this).data('id');
            $.get(baseUrl + rapatId, function(response) {
                var rapat = response.rapat;
                var agenda = rapat.agenda;
                var peserta = response.peserta;

                if (agenda) {
                    $('#viewAgenda').val(agenda.tentang + ' (' + agenda.no_agenda + ') - ' + agenda.formatted_tanggal + ' [' + agenda.formatted_lokasi + ']');
                } else {
                    $('#viewAgenda').val('Tidak ada agenda');
                }
                $('#viewPeserta').html(peserta);
                $('#viewKeputusan').text(rapat.keputusan);

                if (rapat.file) {
                    var files = rapat.file.split(',');
                    var fileLinks = '';

                    files.forEach(function(file) {
                        fileLinks += `<a href="{{ asset('storage') }}/${file}" target="_blank" class="btn btn-gradient-primary btn-sm mr-2"><i class="fas fa-file-download mr-1"></i> Download</a>`;
                    });


                    $('#viewFile').html(fileLinks);
                } else {
                    $('#viewFile').html('<span class="text-danger">Tidak ada file</span>');
                }
                $('#viewRapatModal').modal('show');
            });
        });


        // Edit rapat
        $('.edit-rapat').click(function() {
            var rapatId = $(this).data('id');
            $.get(baseUrl + rapatId + '/edit', function(response) {
                if (response.rapat && response.absen) {
                    var rapat = response.rapat;
                    var agenda = rapat.agenda;
                    var absen = response.absen;

                    // Set modal fields
                    $('#editRapatId').val(rapat.id);
                    if (agenda) {
                        $('#editAgenda').val(agenda.tentang + ' (' + agenda.no_agenda + ') - ' + agenda.formatted_tanggal + ' [' + agenda.formatted_lokasi + ']');
                    }
                    $('#editKeputusan').val(rapat.keputusan);

                    if (rapat.file) {
                        var files = rapat.file.split(',');
                        var fileLinks = '';

                        files.forEach(function(file) {
                            fileLinks += `<a href="{{ asset('storage') }}/${file}" target="_blank" class="btn btn-gradient-primary btn-sm mr-2"><i class="fas fa-file-download mr-1 mt-3"></i> Lihat File lama</a>`;
                        });

                        $('#oldFileLinks').html(fileLinks);
                    } else {
                        $('#oldFileLinks').html('<span class="text-danger mt-3">Tidak ada file</span>');
                    }

                    // Dynamically create checkboxes for participant attendance
                    var pesertaCheckboxes = '';
                    absen.forEach(function(peserta) {
                        var checked = rapat.peserta.some(function(p) {
                            return p.id === peserta.id;
                        }) ? 'checked' : '';
                        pesertaCheckboxes += `
                    <div class="custom-checkbox checklist-item checklist-item-primary">
                        <div class="form-check">
                            <input type="checkbox" class="custom-control-input" id="editPeserta_${peserta.id}" name="peserta[${peserta.id}]" value="hadir" ${checked}>
                            <label class="custom-control-label" for="editPeserta_${peserta.id}">${peserta.nama_peserta}</label>
                        </div>
                    </div>
                `;
                    });
                    $('#editPeserta').html(pesertaCheckboxes);

                    // Show the modal after all fields have been updated
                    $('#editRapatModal').modal('show');
                } else {
                    console.error('Missing data for rapat or absen.');
                    alert('Failed to load data for editing. Please try again.');
                }
            }).fail(function() {
                alert('Error retrieving data from server. Please check your network connection.');
            });
        });


        // Update rapat
        $('#editForm').submit(function(e) {
            e.preventDefault();
            var rapatId = $('#editRapatId').val();
            var formData = new FormData(this);

            $.ajax({
                url: baseUrl + rapatId,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    if (result.success) {
                        $('#editRapatModal').modal('hide');
                        swal.fire("Berhasil!", "Data rapat berhasil diperbarui.", "success").then(() => {
                            location.reload();
                        });
                    } else {
                        swal.fire("Error!", "Gagal memperbarui data rapat.", "error");
                        console.log(result.errors);
                    }
                },
                error: function(xhr, status, error) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = '';
                    $.each(errors, function(key, value) {
                        errorMessage += value[0] + '\n';
                    });
                    swal.fire("Error!", errorMessage, "error");
                }
            });
        });

        // Konfirmasi hapus rapat
        $('.delete-rapat').click(function() {
            var rapatId = $(this).data('id');
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Setelah dihapus, Anda tidak akan dapat memulihkan rapat ini.",
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
                        url: baseUrl + rapatId,
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
                                    text: 'Rapat telah dihapus!',
                                    icon: 'success'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Terjadi kesalahan Pada Sistem!',
                                    'Gagal menghapus rapat.',
                                    'error'
                                );
                            }
                        },
                        error: function() {
                            Swal.fire(
                                'Terjadi kesalahan Pada Sistem!',
                                'Gagal menghapus rapat.',
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
