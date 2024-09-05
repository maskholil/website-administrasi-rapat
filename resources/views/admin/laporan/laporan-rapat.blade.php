{{-- laporan-rapat.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laporan Rapat</title>
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

        <div class="judul-surat">Laporan Rapat</div>

        <div class="report-header">
            <span class="report-range">
                <span class="light-text">Dari Tanggal:</span> {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }} <span class="light-text">s/d:</span> {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}
            </span>
            <span class="report-total">Total Rapat: {{ $totalRows }}</span>
        </div>

        <div class="content">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal Rapat</th>
                        <th>Agenda</th>
                        <th>Tujuan Rapat</th>
                        <th>Keputusan</th>
                        <th>Keterangan</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($rapat as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td class="text-nowrap">{{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('dddd, D MMMM YYYY') }}</td>
                        <td>{{ $item->agenda->tentang }} <br> {{ $item->agenda->no_agenda }}</td>
                        <td>{{ $item->agenda->tujuan }}</td>
                        <td>{{ ucfirst($item->keputusan) }}</td>
                        <td>{{ $item->keterangan ?: 'Tidak ada keterangan' }}</td>

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
