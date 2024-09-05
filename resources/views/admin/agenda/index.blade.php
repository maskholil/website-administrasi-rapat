@section('title', 'SIAR - Tabel Agenda')
@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link href="{{ asset('argon/css/custom-datatables.css') }}" rel="stylesheet">
@endpush

@section('breadcrumb')
<h6 class="h2 text-white d-none d-inline-block mb-0">Halaman Agenda</h6>
<nav aria-label="breadcrumb" class=" d-md-inline-block ml-xl-2 mt-md-2 mt-sm-2 ml-md-0">
    <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home"></i></a></li>
        <li class="breadcrumb-item"><a href="{{route(Auth::user()->role->nama_role .'.agenda.index') }}">Tables</a></li>
        <li class="breadcrumb-item active" aria-current="page">Agenda</li>
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
                            <h5 class="mb-0">Tabel Agenda</h5>
                            <p class="text-sm mb-2">
                                Halaman ini menampilkan seluruh agenda.
                            </p>
                        </div>
                        @if (!Auth::user()->hasRole('dosen'))
                        <div class="ml-auto ms-auto my-auto mt-lg-0 mt-4 ">
                            <div class="ms-auto my-auto ">
                                <button type="button" class="btn bg-gradient-primary btn-sm mb-0 text-white" data-toggle="modal" data-target="#createModal">
                                    +&nbsp; Tambah Agenda Rapat
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Tabel Agenda -->
                <div class="table-responsive mb-4">
                    @php
                    $counter = 1;
                    @endphp


                    <table class="table align-items-center table-flush mb-2" id="datatable-search">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>No Agenda</th>
                                <th>Tentang</th>
                                <th>Tujuan</th>
                                <th>Tanggal</th>
                                <th>Dimulai</th>
                                <th>Ditutup</th>
                                <th>Lokasi</th>
                                <th>Pimpinan Acara</th>
                                <th>Sekertaris</th>
                                @if (!Auth::user()->hasRole('dosen'))
                                <th class="text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach ($agenda as $ag)
                            <tr>
                                <th scope="row">{{ $counter++ }}</th>
                                <td>{{ $ag->no_agenda }}</td>
                                <td>{{ $ag->tentang }}</td>
                                <td>{{ $ag->tujuan }}</td>
                                <td>{{ \Carbon\Carbon::parse($ag->tanggal)->isoFormat('dddd, D MMMM YYYY') }}</td>
                                <td>{{ date('H:i A', strtotime($ag->dimulai)) }}</td>
                                <td>{{ date('H:i A', strtotime($ag->ditutup)) }}</td>


                                <td>{{ UCfirst($ag->lokasi) }}</td>
                                <td> {{ $ag->pimpinan->name ?? 'Tidak ada Pimpinan yang Ditugaskan' }}
                                </td>
                                <td>{{ UCfirst($ag->sekertaris) }}</td>
                                @if (!Auth::user()->hasRole('dosen'))
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="javascript:;" class="btn btn-sm btn-warning edit-agenda" data-id="{{ $ag->id }}" data-toggle="tooltip" data-original-title="Edit Data">
                                            <i class="fas fa-edit"></i>
                                        </a> <a href="javascript:;" class=" btn btn-sm btn-youtube delete-agenda" data-id="{{ $ag->id }}" data-toggle="tooltip" data-original-title="Hapus Data">
                                            <i class="fas fa-trash"></i> </a>

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

<!-- Modal Tambah Data -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel"><i class="fas fa-calendar-plus mr-2"></i> Form Tambah Agenda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Custom separator line -->
            <div style="height: 1px; background-color: #dee2e6;"></div>
            <form action="{{route(Auth::user()->role->nama_role .'.agenda.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Form fields for creating agenda -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="no_agenda"><i class="fas fa-hashtag mr-1"></i> No Agenda<span class="text-danger"> *</span></label>
                                <input type="text" class="form-control required" id="no_agenda" name="no_agenda" placeholder="Masukkan nomor agenda" required>
                            </div>
                            <div class="form-group">
                                <label for="tentang"><i class="fas fa-info-circle mr-1"></i> Tentang<span class="text-danger"> *</span></label>
                                <input type="text" class="form-control required" id="tentang" name="tentang" placeholder="Masukkan tentang agenda" oninput="this.value = this.value.replace(/\b\w/g, char => char.toUpperCase());" required>
                            </div>
                            <div class="form-group">
                                <label for="tanggal">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    Tanggal Agenda
                                    <span class="text-danger"> *</span>
                                </label>
                                <input type="date" class="form-control required" id="tanggal" name="tanggal" placeholder="Masukkan tanggal" required>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="hari"><i class="fas fa-calendar-day mr-1"></i> Hari<span class="text-danger"> *</span></label>
                                        <input type="text" class="form-control required" id="hari" name="hari" placeholder="Masukkan hari" readonly required>

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="bulan"><i class="fas fa-calendar-alt mr-1"></i> Bulan<span class="text-danger"> *</span></label>
                                        <input type="text" class="form-control required" id="bulan" name="bulan" placeholder="Masukkan bulan" readonly required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tahun"><i class="fas fa-calendar-alt mr-1"></i> Tahun<span class="text-danger"> *</span></label>
                                        <input type="text" class="form-control required" id="tahun" name="tahun" placeholder="Masukkan tahun" readonly required>
                                    </div>
                                </div>

                            </div>
                            <small class="form-text text-muted mb-3" style="margin-top: -20px;">Hari, Bulan, Tahun akan <strong>Otomatis</strong> terisi setelah mengisi tanggal Agenda.</small>


                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lokasi"><i class="fas fa-map-marker-alt mr-1"></i> Lokasi<span class="text-danger"> *</span></label>
                                <textarea type="text" class="form-control required" id="lokasi" rows="2" name="lokasi" placeholder="Masukkan lokasi agenda" oninput="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);" required></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dimulai"><i class="fas fa-clock mr-1"></i> Jam Dimulai<span class="text-danger"> *</span></label>
                                        <input type="time" class="form-control required" id="dimulai" name="dimulai" placeholder="HH:MM" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ditutup"><i class="fas fa-clock mr-1"></i> Jam Selesai<span class="text-danger"> *</span></label>
                                        <input type="time" class="form-control required" id="ditutup" name="ditutup" placeholder="HH:MM" required>
                                    </div>
                                </div>

                            </div>

                            <div class="form-group">
                                <label for="dipimpin"><i class="fas fa-user mr-1"></i> Dipimpin oleh<span class="text-danger"> *</span></label>
                                <select class="form-control required" id="dipimpin" name="dipimpin" required>
                                    @foreach($pimpinan as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="sekertaris"><i class="fas fa-user mr-1"></i> Sekertaris<span class="text-danger"> *</span></label>
                                <input type="text" class="form-control required" id="sekertaris" name="sekertaris" placeholder="Masukkan sekertaris agenda " oninput="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="tujuan"><i class="fas fa-bullseye mr-1"></i> Tujuan Rapat <span class="text-danger"> *</span></label>
                                <input type="text" class="form-control" id="tujuan" name="tujuan" placeholder="Masukkan tujuan Rapat" oninput="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);" required>
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

<!-- Modal Edit Data -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel"><i class="fas fa-calendar-edit mr-2"></i> Form Edit Agenda</h5>
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
                    <!-- Form fields for editing agenda -->
                    <input type="hidden" id="editAgendaId" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editNoAgenda"><i class="fas fa-hashtag mr-1"></i> No Agenda<span class="text-danger"> *</span></label>
                                <input type="text" class="form-control required" id="editNoAgenda" name="no_agenda" placeholder="Masukkan nomor agenda" required>
                            </div>
                            <div class="form-group">
                                <label for="editTentang"><i class="fas fa-info-circle mr-1"></i> Tentang<span class="text-danger"> *</span></label>
                                <input type="text" class="form-control required" id="editTentang" name="tentang" placeholder="Masukkan tentang agenda" oninput="this.value = this.value.replace(/\b\w/g, char => char.toUpperCase());" required>
                            </div>
                            <div class="form-group">
                                <label for="editTanggal">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    Tanggal Agenda
                                    <span class="text-danger"> *</span>
                                </label>
                                <input type="date" class="form-control required" id="editTanggal" name="tanggal" placeholder="Masukkan tanggal" required>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="editHari"><i class="fas fa-calendar-day mr-1"></i> Hari<span class="text-danger"> *</span></label>
                                        <input type="text" class="form-control required" id="editHari" name="hari" placeholder="Masukkan hari" readonly required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="editBulan"><i class="fas fa-calendar-alt mr-1"></i> Bulan<span class="text-danger"> *</span></label>
                                        <input type="text" class="form-control required" id="editBulan" name="bulan" placeholder="Masukkan bulan" readonly required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="editTahun"><i class="fas fa-calendar-alt mr-1"></i> Tahun<span class="text-danger"> *</span></label>
                                        <input type="text" class="form-control required" id="editTahun" name="tahun" placeholder="Masukkan tahun" readonly required>
                                    </div>
                                </div>
                            </div>
                            <small class="form-text text-muted mb-3" style="margin-top: -20px;">Hari, Bulan, Tahun akan otomatis terisi setelah mengisi tanggal Agenda.</small>
                            <div class="form-group">
                                <label for="editTujuan"><i class="fas fa-bullseye mr-1"></i> Tujuan Rapat</label>
                                <input type="text" class="form-control" id="editTujuan" name="tujuan" placeholder="Masukkan tujuan Rapat">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editLokasi"><i class="fas fa-map-marker-alt mr-1"></i> Lokasi<span class="text-danger"> *</span></label>
                                <textarea type="text" class="form-control required" id="editLokasi" rows="2" name="lokasi" placeholder="Masukkan lokasi agenda" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="editDimulai"><i class="fas fa-clock mr-1"></i> Jam Dimulai<span class="text-danger"> *</span></label>
                                <input type="time" class="form-control required" id="editDimulai" name="dimulai" required>
                            </div>
                            <div class="form-group">
                                <label for="editDitutup"><i class="fas fa-clock mr-1"></i> Jam Selesai<span class="text-danger"> *</span></label>
                                <input type="time" class="form-control required" id="editDitutup" name="ditutup" required>
                            </div>

                            <div class="form-group">
                                <label for="editDipimpin"><i class="fas fa-user mr-1"></i> Dipimpin oleh<span class="text-danger"> *</span></label>
                                <select class="form-control required" id="editDipimpin" name="dipimpin" required>
                                    @foreach($pimpinan as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="editSekertaris"><i class="fas fa-user mr-1"></i> Sekertaris<span class="text-danger"> *</span></label>
                                <input type="text" class="form-control required" id="editSekertaris" name="sekertaris" placeholder="Masukkan sekertaris agenda" oninput="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);" required>
                            </div>
                        </div>
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
        var baseUrl = `/${userRole}/agenda/`;
        // Menangani pemilihan tanggal pada form create
        $('#tanggal').on('change', function() {
            var selectedDate = new Date($(this).val());
            var days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            $('#hari').val(days[selectedDate.getDay()]);
            $('#bulan').val(months[selectedDate.getMonth()]);
            $('#tahun').val(selectedDate.getFullYear());
        });

        // Menangani pemilihan tanggal pada form edit
        $('#editTanggal').on('change', function() {
            var selectedDate = new Date($(this).val());
            var days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            $('#editHari').val(days[selectedDate.getDay()]);
            $('#editBulan').val(months[selectedDate.getMonth()]);
            $('#editTahun').val(selectedDate.getFullYear());
        });

        // Edit agenda
        $('.edit-agenda').click(function() {
            var agendaId = $(this).data('id');
            $.get(baseUrl + agendaId + '/edit', function(response) {
                var agenda = response.agenda;
                $('#editAgendaId').val(agenda.id);
                $('#editNoAgenda').val(agenda.no_agenda);
                $('#editTentang').val(agenda.tentang);
                $('#editHari').val(agenda.hari);
                $('#editTanggal').val(agenda.tanggal);
                $('#editBulan').val(agenda.bulan);
                $('#editTahun').val(agenda.tahun);
                $('#editLokasi').val(agenda.lokasi);
                $('#editDimulai').val(agenda.dimulai.substring(0, 5)); // Ensure time format is HH:MM
                $('#editDitutup').val(agenda.ditutup.substring(0, 5));
                $('#editDipimpin').val(agenda.dipimpin);
                $('#editSekertaris').val(agenda.sekertaris);
                $('#editTujuan').val(agenda.tujuan);
                $('#editModal').modal('show');
            });
        });

        // Update agenda
        $('#editForm').submit(function(e) {
            e.preventDefault();
            var agendaId = $('#editAgendaId').val();
            $.ajax({
                url: baseUrl + agendaId,
                type: 'PUT',
                data: $('#editForm').serialize(),
                success: function(result) {
                    if (result.success) {
                        $('#editModal').modal('hide');
                        swal.fire("Berhasil!", "Data agenda berhasil diperbarui.", "success").then(() => {
                            location.reload();
                        });
                    } else {
                        swal.fire("Error!", "Gagal memperbarui data agenda.", "error");
                    }
                }
            });
        });

        // Konfirmasi hapus agenda
        $('.delete-agenda').click(function() {
            var agendaId = $(this).data('id');
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Setelah dihapus, Anda tidak akan dapat memulihkan agenda ini.",
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
                        url: baseUrl + agendaId,
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
                                    text: 'Agenda telah dihapus!',
                                    icon: 'success'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Terjadi kesalahan Pada Sistem!', 'Gagal menghapus agenda.', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Terjadi kesalahan Pada Sistem!', 'Gagal menghapus agenda.', 'error');
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
