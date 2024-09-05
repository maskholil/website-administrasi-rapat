<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Surat Keluar</title>
    <link href="{{ asset('argon/img/brand/favicon-siar.png') }}" rel="icon" type="image/png">
    <style>


        @page {
            size: A4;
            margin: 0mm 0mm 1mm 0mm;
        }

        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            box-sizing: border-box;
        }

        .container {
            width: 210mm;
            height: 297mm;
            margin: 5mm 5mm 5mm 5mm;
            /* Atas, Kanan, Bawah, Kiri */
            padding: 3mm;
            box-sizing: border-box;
            position: relative;
            page-break-after: avoid;
        }

        .kop-surat {
            text-align: center;
            margin-top: -15px;
            color: #00913c;
        }

        .kop-surat img {
            max-width: 80px;
            margin-bottom: 5px;
        }

        .kop-surat h2,
        .kop-surat h3,
        .kop-surat p {
            margin: 0;
            padding: 0;
        }

        .kop-surat hr {
            border: 0;
            border-top: 2px solid #000;
            margin: 10px 0;
        }

        .judul-surat {
            margin-top: 18px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            text-decoration: underline;
        }

        .nomor-surat {
            text-align: center;
            margin-bottom: 20px;
        }

        .content {
            text-align: justify;
            padding: 0 5mm;
            margin-bottom: 5mm;
            display: flex;
            flex-direction: column;
            flex: 1 0 auto;
        }

        .content p {
            margin: 6px 0;
        }

        .content ul {
            margin: 6px 0;
            padding-left: 35px;
        }

        .footer {
            text-align: left;
            page-break-inside: avoid;
            margin-top: auto;
            align-self: flex-end;
        }

        .ttd {
            margin-bottom: 5mm;
        }

        .ttd img {
            max-width: 220px;
            height: auto;
            margin-bottom: -50px;
            margin-left: -18px;
        }

        .tanggal {
            margin-bottom: -25px;
        }

        .nama {
            margin-top: 40px;
        }

        .nama .nik {
            margin: 0;
            padding: 0;
        }

        .nama .nama-ttd {
            font-weight: bold;
            margin: 0;
            padding: 0;
        }

        .footer-bottom {
            position: fixed;
            left: 0;
            bottom: 0;
            right: 0;
            text-align: center;
            padding: 1mm 0;
            border-top: 1px solid #00913c;
            font-size: 10pt;
            color: #00913c;
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
            <hr style="border: 0; border-top: 1px solid #00913c;">
            <hr style="border: 0; border-top: 2px solid #00913c; margin-top: -8px;">
        </div>
        <div class="content">
            {!! $isi !!}
            <div class="footer">
                <div class="tanggal">
                    Cilacap, {{ date('d F Y', strtotime($tgl_surat)) }}
                    <br> Dekan
                </div>
                <div class="ttd">
                    @if($ttd)
                    <img src="{{ asset('storage/'.$ttd) }}" alt="Tanda Tangan">
                    @endif
                </div>
                <div class="nama">
                    <p class="nama-ttd">{{ $validator }}</p>
                    <p class="nik">{{ $no_identitas }}</p>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            UNIVERSITAS NAHDLATUL ULAMA AL GHAZALI CILACAP<br>
            Jln. Kemerdekaan Barat No.17 Kesugihan Cilacap Jawa Tengah K.Pos 53274, http://unugha.ac.id,<br>
            Email: fimkom@unugha.ac.id Telb.: (0282) 695415, 695407, Fax : (0282) 695407
        </div>
    </div>
</body>

</html>
