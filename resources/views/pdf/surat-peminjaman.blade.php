<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pernyataan Peminjaman Aset</title>
    <style>
        /* Satu halaman A4: margin diatur via @page.
           DomPDF tidak mendukung flexbox → layout memakai table. */
        @page { margin: 12mm 18mm 12mm 22mm; }

        * { margin: 0; padding: 0; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            line-height: 1.4;
        }

        /* ── KOP SURAT (mengikuti format resmi Pemkot Palembang) ── */
        table.kop {
            width: 100%;
            border-collapse: collapse;
        }
        table.kop td { vertical-align: middle; }
        table.kop td.kop-logo,
        table.kop td.kop-spacer { width: 22mm; }
        table.kop td.kop-logo img {
            width: 21mm;
            height: auto;
        }
        table.kop td.kop-teks {
            text-align: center;
            font-family: Helvetica, Arial, sans-serif;
        }
        .kop-pemkot {
            font-size: 13pt;
            font-weight: bold;
            line-height: 1.15;
        }
        .kop-dinas {
            font-size: 17pt;
            font-weight: bold;
            line-height: 1.15;
        }
        .kop-alamat {
            font-size: 10pt;
            line-height: 1.25;
            margin-top: 1px;
        }
        /* Garis ganda: tebal di atas, tipis di bawah */
        .kop-garis-tebal {
            border-top: 3px solid #000;
            margin-top: 5px;
        }
        .kop-garis-tipis {
            border-top: 1px solid #000;
            margin-top: 1.5px;
            margin-bottom: 14px;
        }

        /* ── JUDUL ── */
        .judul-surat {
            text-align: center;
            margin-bottom: 8px;
        }
        .judul-surat h1 {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            letter-spacing: 0.5px;
        }
        .nomor-surat {
            font-size: 10.5pt;
            margin-top: 2px;
        }

        p.paragraf {
            text-align: justify;
            margin-bottom: 4px;
        }
        p.sub-judul {
            font-weight: bold;
            margin: 8px 0 2px;
        }

        /* ── TABEL DATA ── */
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-left: 12px;
        }
        table.data td {
            padding: 1px 4px;
            vertical-align: top;
        }
        table.data td.label  { width: 32%; }
        table.data td.titik  { width: 3%; text-align: center; }
        table.data td.nilai  { width: 65%; }

        /* ── ISI PERNYATAAN ── */
        ol.pernyataan {
            margin: 2px 0 4px 30px;
            text-align: justify;
        }
        ol.pernyataan li { margin-bottom: 2px; }

        /* ── TANDA TANGAN ── */
        table.ttd {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }
        table.ttd td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        .ttd-ruang { height: 68px; }
        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
        }

        /* ── FOOTER + QR ── */
        table.footer {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border-top: 1px solid #9ca3af;
        }
        table.footer td {
            padding-top: 5px;
            vertical-align: middle;
            font-family: Helvetica, Arial, sans-serif;
            font-size: 7.5pt;
            color: #4b5563;
        }
        table.footer td.qr {
            width: 25mm;
            text-align: center;
        }
        table.footer td.qr img {
            width: 22mm;
            height: 22mm;
        }
        .token {
            font-family: 'Courier New', monospace;
            font-size: 7pt;
            color: #6b7280;
        }
    </style>
</head>
<body>
@php
    $logoPath = public_path('images/logo-palembang.png');
    $logoBase64 = is_file($logoPath)
        ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
        : null;
    $pengesah = $peminjaman->disetujuiOleh ?? null;
@endphp

    {{-- ── KOP SURAT ── --}}
    <table class="kop">
        <tr>
            <td class="kop-logo">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo Kota Palembang">
                @endif
            </td>
            <td class="kop-teks">
                <div class="kop-pemkot">PEMERINTAH KOTA PALEMBANG</div>
                <div class="kop-dinas">DINAS KEPEMUDAAN DAN OLAHRAGA</div>
                <div class="kop-alamat">
                    Jl. Gubernur H.A. Bastari, Kelurahan 15 Ulu, Kecamatan Jakabaring Kota<br>
                    Palembang - Provinsi Sumatera Selatan 30267
                </div>
            </td>
            <td class="kop-spacer"></td>
        </tr>
    </table>
    <div class="kop-garis-tebal"></div>
    <div class="kop-garis-tipis"></div>

    {{-- ── JUDUL ── --}}
    <div class="judul-surat">
        <h1>Surat Pernyataan Peminjaman Aset</h1>
        <div class="nomor-surat">Nomor: {{ $nomorSurat }}</div>
    </div>

    <p class="paragraf">
        Yang bertanda tangan di bawah ini menerangkan bahwa telah terjadi peminjaman aset milik
        Dinas Kepemudaan dan Olahraga Kota Palembang kepada pihak berikut:
    </p>

    {{-- ── DATA PEMINJAM ── --}}
    <p class="sub-judul">A. Data Peminjam</p>
    <table class="data">
        <tr><td class="label">Nama Peminjam</td><td class="titik">:</td><td class="nilai"><strong>{{ $peminjaman->peminjam->name }}</strong></td></tr>
        <tr><td class="label">NIK</td><td class="titik">:</td><td class="nilai">{{ $peminjaman->peminjam->nik ?? '-' }}</td></tr>
        <tr><td class="label">No. HP</td><td class="titik">:</td><td class="nilai">{{ $peminjaman->peminjam->no_hp ?? '-' }}</td></tr>
        <tr><td class="label">Jenis Peminjam</td><td class="titik">:</td><td class="nilai">{{ ucfirst($peminjaman->peminjam->jenis_peminjam ?? '-') }}</td></tr>
        <tr><td class="label">Alamat</td><td class="titik">:</td><td class="nilai">{{ \Illuminate\Support\Str::limit($peminjaman->peminjam->alamat ?? '-', 200) }}</td></tr>
    </table>

    {{-- ── DATA ASET ── --}}
    <p class="sub-judul">B. Data Aset yang Dipinjam</p>
    <table class="data">
        <tr><td class="label">No. Pengajuan</td><td class="titik">:</td><td class="nilai">{{ $peminjaman->nomor_pengajuan }}</td></tr>
        <tr><td class="label">Nama Aset</td><td class="titik">:</td><td class="nilai"><strong>{{ $peminjaman->aset->nama_aset }}</strong></td></tr>
        <tr><td class="label">Kode Aset</td><td class="titik">:</td><td class="nilai">{{ $peminjaman->aset->kode_aset }}</td></tr>
        <tr><td class="label">Kategori Aset</td><td class="titik">:</td><td class="nilai">{{ $peminjaman->aset->kategori->nama ?? '-' }}</td></tr>
        <tr><td class="label">Jumlah Barang</td><td class="titik">:</td><td class="nilai"><strong>{{ $peminjaman->jumlah_pinjam ?? 1 }} Unit</strong></td></tr>
        <tr><td class="label">Lama Peminjaman</td><td class="titik">:</td><td class="nilai">{{ $peminjaman->tanggal_pinjam->translatedFormat('d F Y') }} s.d. {{ $peminjaman->tanggal_kembali->translatedFormat('d F Y') }}</td></tr>
        <tr><td class="label">Keperluan</td><td class="titik">:</td><td class="nilai">{{ \Illuminate\Support\Str::limit($peminjaman->keperluan, 260) }}</td></tr>
    </table>

    {{-- ── ISI PERNYATAAN ── --}}
    <p class="paragraf" style="margin-top:6px;">Dengan ini, peminjam menyatakan dan menyetujui hal-hal sebagai berikut:</p>
    <ol class="pernyataan">
        <li>Menggunakan aset tersebut <strong>hanya untuk keperluan yang telah disebutkan</strong> dan tidak mengalihkannya kepada pihak lain tanpa izin tertulis dari Dispora Kota Palembang.</li>
        <li>Bertanggung jawab penuh atas <strong>keamanan, keutuhan, dan kondisi aset</strong> selama masa peminjaman berlangsung.</li>
        <li>Mengembalikan aset <strong>paling lambat tanggal {{ $peminjaman->tanggal_kembali->translatedFormat('d F Y') }}</strong> dalam kondisi baik seperti saat diterima.</li>
        <li>Apabila terjadi kerusakan atau kehilangan akibat kelalaian peminjam, peminjam <strong>wajib memberikan ganti rugi</strong> sesuai nilai aset yang berlaku.</li>
        <li>Surat pernyataan ini dibuat dengan penuh kesadaran dan tanpa paksaan dari pihak mana pun.</li>
    </ol>

    <p class="paragraf">
        Demikian surat pernyataan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
    </p>

    {{-- ── TANDA TANGAN ── --}}
    <table class="ttd">
        <tr>
            <td>
                <div>&nbsp;</div>
                <div>Pihak Peminjam,</div>
                <div class="ttd-ruang"></div>
                <div class="ttd-nama">{{ $peminjaman->peminjam->name }}</div>
                <div>{{ ucfirst($peminjaman->peminjam->jenis_peminjam ?? 'Peminjam') }}</div>
            </td>
            <td>
                <div>Palembang, {{ $tanggalSurat }}</div>
                <div>Pengelola Aset Dispora Kota Palembang,</div>
                <div class="ttd-ruang"></div>
                <div class="ttd-nama">{{ $pengesah->name ?? '(....................................)' }}</div>
                <div>Pengelola Aset</div>
            </td>
        </tr>
    </table>

    {{-- ── FOOTER + QR CODE ── --}}
    <table class="footer">
        <tr>
            <td>
                Dokumen ini diterbitkan secara elektronik oleh SIPANDA — Sistem Informasi Peminjaman
                Aset Dinas Kepemudaan dan Olahraga Kota Palembang. Keaslian surat dapat diverifikasi
                dengan memindai QR Code di samping.<br>
                <span class="token">Token: {{ substr($barcodeToken ?? ($peminjaman->surat->barcode_token ?? '-'), 0, 24) }}...</span>
            </td>
            @if(!empty($qrCodeBase64))
                <td class="qr">
                    <img src="{{ $qrCodeBase64 }}" alt="QR Verifikasi">
                </td>
            @endif
        </tr>
    </table>
</body>
</html>
