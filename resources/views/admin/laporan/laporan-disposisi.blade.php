{{-- laporan-disposisi.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laporan Disposisi</title>
    <link href="{{ asset('argon/img/brand/favicon-siar.png') }}" rel="icon" type="image/png">
    <link href="{{ asset('argon/css/laporan-styles.css?v=1.0.0') }}" rel="stylesheet">
</head>

<body onload="window.print();">
    <div class="container">
        <div class="kop-surat">
            <img src="{{asset('assets/img/unugha.png')}}" alt="Logo Instansi" />
            <h2>UNUGHA CILACAP</h2>
            <h3>FAKULTAS MATEMATIKA DAN ILMU KOMPUTER (FMIKOM)</h3>
            <p>Keputusan Kemendikbud RI Nomor : 264/E/O/2014 Tanggal 23 Juli 2014</p>
            <hr style="border: 0; border-top: 1px solid #00913c;">
            <hr style="border: 0; border-top: 2px solid #00913c; margin-top: -8px;">
        </div>

        <div class="judul-surat">Laporan Disposisi</div>

        <div class="report-header">
            <span class="report-range">
                <span class="light-text">Dari Tanggal:</span> {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }}
                <span class="light-text">s/d:</span> {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}
            </span>
            <span class="report-total">Total Data: {{ $totalRows }}</span>
        </div>

        <div class="content">
        <table>
    <thead>
        <tr>
            <th>No.</th>
            <th>No. Surat</th>
            <th>Tanggal Disposisi</th>
            <th>Catatan Disposisi</th>
            <th>Penerima Disposisi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($arsip as $key => $arsipItem)
            @if ($arsipItem->disposisi->isNotEmpty())
                @foreach ($arsipItem->disposisi as $disposisi)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $arsipItem->no_surat }}</td>
                        <td>{{ $disposisi->created_at->format('d M Y') }}</td>
                        <td>{{ $disposisi->catatan }}</td>
                        <td>{{ $disposisi->tujuanUsers->pluck('name')->join(', ') }}</td> 
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $arsipItem->no_surat }}</td>
                    <td>N/A</td>
                    <td>No Disposisi</td>
                    <td>No Recipient</td>
                </tr>
            @endif
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
