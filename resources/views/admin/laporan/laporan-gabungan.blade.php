{{-- laporan-gabungan.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laporan Gabungan - Arsip Masuk dan Keluar</title>
    <link href="{{ asset('argon/img/brand/favicon-siar.png') }}" rel="icon" type="image/png">
    <link href="{{ asset('argon/css/laporan-styles.css?v=1.0.0') }}" rel="stylesheet">
    <style>
        .text-nowrap {
            white-space: nowrap !important;
        }
    </style>
</head>

<body onload="window.print();">
    <div class="container">
        <div class="kop-surat">
            <img src="{{ asset('assets/img/unugha.png') }}" alt="Logo Instansi" />
            <h2>UNUGHA CILACAP</h2>
            <h3>FAKULTAS MATEMATIKA DAN ILMU KOMPUTER (FMIKOM)</h3>
            <p>Keputusan Kemendikbud RI Nomor : 264/E/O/2014 Tanggal 23 Juli 2014</p>
            <hr style="border: 0; border-top: 1px solid #00913c;">
            <hr style="border: 0; border-top: 2px solid #00913c; margin-top: -8px;">
        </div>
        <div class="judul-surat">Laporan Gabungan Arsip Masuk dan Keluar</div>
        <div class="report-header">
            <span class="report-range">
                <span class="light-text">Dari Tanggal:</span> {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }} <span class="light-text">s/d:</span> {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}
            </span>
            <span class="report-total">Total Data: {{ $totalRows }}</span>
        </div>
        <div class="content">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>No. Surat</th>
                        <th>Tanggal Surat</th>

                        <th>Tujuan Surat</th>
                        <th>Kategori</th>
                        <th>Jenis Arsip</th>
                        <th>Disposisi</th>
                        <th>Tujuan Disposisi</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($arsip as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->no_surat }}</td>
                        <td>{{ date('d/m/y', strtotime($item->tgl_surat)) }}</td>

                        <td>
                            @if ($item->jenis_arsip == 'masuk')
                            @foreach ($item->tujuanUsers as $user)
                            {{ $user->name }}@if(!$loop->last), @endif
                            @endforeach
                            @else
                            {{ $item->tujuan_keluar ?: '-' }}
                            @endif
                        </td>

                        <td>{{ $item->kategori->nama_kategori }}</td>
                        <td>{{ ucfirst($item->jenis_arsip) }}</td>

                        <td class="text-nowrap">
                            @forelse ($item->disposisi as $disposisi)
                            {{ ucfirst($disposisi->catatan) }} <br> ({{ $disposisi->created_at->format('d M Y') }})
                            @empty
                            No Disposisi
                            @endforelse
                        </td>
                        <td>
                            @if ($item->jenis_arsip == 'masuk')
                            @foreach ($item->tujuanUsers as $user)
                            {{ $user->name }}@if(!$loop->last), @endif
                            @endforeach
                            @else
                            {{ $item->tujuanUsers->pluck('name')->join(', ') ?: '-' }}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <span class="report-date">Tanggal Laporan: {{ date('d/m/Y H:i') }} WIB</span>
            <div class="footer">
                <p>&copy; {{ date('Y') }} Sistem Informasi Administrasi dan Rapat (SIAR) - Unugha Cilacap</p>
            </div>
        </div>
    </div>
</body>

</html>
