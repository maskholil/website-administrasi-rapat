<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Hasil Berita Acara</title>
    <link href="{{ asset('argon/img/brand/favicon-siar.png') }}" rel="icon" type="image/png">
    <style>
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            box-sizing: border-box;
        }

        .container {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 2mm;
            box-sizing: border-box;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .kop-surat {
            text-align: center;
            margin-top: 10px;
            color: #00913c;
        }

        .kop-surat img {
            max-width: 80px;
            margin-bottom: 10px;
        }

        .kop-surat h2,
        .kop-surat h3,
        .kop-surat p {
            margin: 0;
            padding: 0;
        }

        .kop-surat hr {
            border: 0;
            border-top: 2px solid #00913c;
            margin: 10px 0;
        }

        .judul-surat {
            margin-top: 15px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
        }

        .content {
            text-align: justify;
            padding: 0 5mm;

            line-height: 1.6;
            flex: 1;
        }

        .content p {
            margin: 10px 0;
        }

        .content ol,
        .content ul {
            padding-left: 18px;
        }

        .content li {
            padding-bottom: 5px;
        }

        .peserta-container {
            display: flex;
            justify-content: space-between;
        }

        .peserta-column {
            flex-basis: 48%;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            page-break-inside: avoid;
            padding: 0 20mm;
        }

        .footer .role {
            flex-basis: 48%;
            text-align: center;
        }

        .footer .signature-line {
            display: block;
            border-bottom: 1px solid #000;
            margin: 5mm 20mm;
            height: 1px;
        }

        .nama-ttd {
            font-weight: bold;
            margin-top: -4mm;
        }

        .tanggal-surat {
            text-align: center;
            margin-left: 5mm;

        }
    </style>
</head>

<body onload="window.print();">
    <div class="container">
        <div class="kop-surat">
            <img src="{{asset('assets/img/unugha.png')}}" alt="Logo Instansi" />
            <h2>UNUGHA CILACAP</h2>
            <h3>FAKULTAS MATEMATIKA DAN ILMU KOMPUTER (FMIKOM)</h3>
            <p>Keputusan Kemendikbud RI Nomor : 264/E/O/2014 Tanggal 23 Juli 2014</p>
            <!-- Garis tipis di atas -->
            <hr style="border: 0; border-top: 1px solid #00913c;">
            <!-- Garis tebal di bawah -->
            <hr style="border: 0; border-top: 2px solid #00913c; margin-top: -8px;">
        </div>
        <h3 class="judul-surat">BERITA ACARA RAPAT</h3>

        <div class="content">
            <p>Pada hari <span>{{ $hari }}</span>,
                tanggal <span>{{ date('d', strtotime($tanggal)) }}</span>
                bulan <span>{{ $bulan }}</span>
                tahun <span>{{ $tahun }}</span>, di <span>{{ $lokasi }}</span></p>
            <p>berkedudukan di Cilacap, Jl. Kemerdekaan Barat Nomor 17. Kesugihan, Cilacap. telah diadakan Rapat <span>{{ $tujuan }}</span> dengan agenda <span>{{ $tentang }}</span></p>
            <p>dengan ringkasan sebagai berikut:</p>
            <ol>
                <li>Rapat dibuka pukul <span>{{ $dimulai }} WIB,</span> dipimpin oleh <span>{{ $dipimpin ?? 'Nama tidak tersedia' }}</span> dan sekretaris <span>{{ $sekertaris ?? 'Nama tidak tersedia' }}</span></li>
                <li>Rapat dihadiri oleh:
                    @if($peserta->count() > 0)
                    <div class="peserta-container">
                        <div class="peserta-column">
                            <ol>
                                @foreach($peserta->slice(0, ceil($peserta->count() / 2)) as $pesertaRapat)
                                <li>{{ $pesertaRapat->nama_peserta }}</li>
                                @endforeach
                            </ol>
                        </div>
                        <div class="peserta-column">
                            <ol start="{{ ceil($peserta->count() / 2) + 1 }}">
                                @foreach($peserta->slice(ceil($peserta->count() / 2)) as $pesertaRapat)
                                <li>{{ $pesertaRapat->nama_peserta }}</li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                    @else
                    <p>Tidak ada peserta yang terdaftar.</p>
                    @endif
                </li>
                <li>Rapat memutuskan:
                    <p style="margin-bottom: 20mm;">{{ ucfirst($keputusan) }}</p>
                </li>
            </ol>
            <p>Rapat ditutup pukul <span>{{ $ditutup }} WIB.</span></p>
        </div>

        <div class="footer">
            <div class="role">
                <p style="margin-bottom: -5px;">Pimpinan Rapat</p>
                @if($rapat->ttd_dipimpin)
                <img src="{{ asset('storage/ttd/'.$rapat->ttd_dipimpin) }}" alt="Tanda Tangan Dipimpin" style="max-width: 200px; height: auto;">
                @else
                <span class="signature-line"></span>
                @endif
                <p class="nama-ttd">{{ $dipimpin }}</p>
            </div>

            <div class="role">
                <div class="tanggal-surat">
                    <p>Cilacap, {{ date('d F Y', strtotime($rapat->created_at)) }}</p>
                </div>
                <p style="margin-bottom: -5px;">Sekretaris</p>
                @if($rapat->ttd_sekretaris)
                <img src="{{ asset('storage/ttd/'.$rapat->ttd_sekretaris) }}" alt="Tanda Tangan Sekretaris" style="max-width: 200px; height: auto;">
                @else
                <span class="signature-line"></span>
                @endif
                <p class="nama-ttd">{{ $sekertaris }}</p>
            </div>
        </div>
    </div>
</body>


</html>