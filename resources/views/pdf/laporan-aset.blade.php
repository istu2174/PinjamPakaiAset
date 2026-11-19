<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Aset</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9px; color: #1a1a2e; }
        .header { text-align: center; padding: 15px 20px; border-bottom: 2px solid #1e3a5f; margin-bottom: 12px; }
        .header .logo { font-size: 16px; font-weight: bold; color: #1e3a5f; letter-spacing: 1px; }
        .header .subtitle { font-size: 10px; color: #4a5568; margin-top: 3px; }
        .header .report-title { font-size: 13px; font-weight: bold; color: #2563eb; margin-top: 8px; text-transform: uppercase; }
        .meta { display: flex; justify-content: space-between; padding: 0 20px 8px; font-size: 8px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin: 0 20px; width: calc(100% - 40px); }
        thead tr { background-color: #1e3a5f; color: white; }
        thead th { padding: 6px 8px; text-align: left; font-size: 8px; font-weight: 600; }
        tbody tr:nth-child(even) { background-color: #f8fafc; }
        tbody td { padding: 5px 8px; border-bottom: 1px solid #e2e8f0; font-size: 8px; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 9px; font-size: 7px; font-weight: bold; }
        .kondisi-layak { background: #d1fae5; color: #065f46; }
        .kondisi-perawatan { background: #fef3c7; color: #92400e; }
        .kondisi-tidak { background: #fee2e2; color: #991b1b; }
        .status-tersedia { background: #d1fae5; color: #065f46; }
        .status-dipinjam { background: #dbeafe; color: #1e40af; }
        .status-maintenance { background: #ffedd5; color: #9a3412; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; padding: 8px 20px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; font-size: 7px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">SIPANDA</div>
        <div class="subtitle">Sistem Informasi Peminjaman Aset Dinas</div>
        <div class="subtitle">Dinas Kepemudaan dan Olahraga Kota Palembang</div>
        <div class="report-title">Laporan Data Aset</div>
    </div>

    <div class="meta">
        <span>Tanggal cetak: {{ $tanggal }}</span>
        <span>Total Aset: {{ $asets->count() }} unit</span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:3%">No</th>
                <th style="width:10%">Kode</th>
                <th style="width:20%">Nama Aset</th>
                <th style="width:12%">Kategori</th>
                <th style="width:7%">Tahun</th>
                <th style="width:8%">Jumlah</th>
                <th style="width:13%">Lokasi</th>
                <th style="width:13%">Kondisi (DT)</th>
                <th style="width:10%">Status</th>
                <th style="width:4%">Dipakai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($asets as $i => $a)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td style="font-family:monospace">{{ $a->kode_aset }}</td>
                    <td>{{ $a->nama_aset }}<br><span style="color:#9ca3af;font-size:7px">{{ $a->merk ?? '' }}</span></td>
                    <td>{{ $a->kategori->nama ?? '-' }}</td>
                    <td>{{ $a->tahun_pengadaan ?? '-' }}</td>
                    <td>{{ $a->jumlah ?? 1 }}</td>
                    <td>{{ $a->lokasi ?? '-' }}</td>
                    <td>
                        @php
                            $k = $a->kondisiTerbaru;
                            $kLabel = $k ? str_replace('_',' ',ucfirst($k->hasil_decision_tree)) : 'Belum dinilai';
                            $kClass = $k ? ($k->hasil_decision_tree==='layak_pakai'?'kondisi-layak':($k->hasil_decision_tree==='perlu_perawatan'?'kondisi-perawatan':'kondisi-tidak')) : '';
                        @endphp
                        <span class="badge {{ $kClass }}">{{ $kLabel }}</span>
                    </td>
                    <td>
                        @php $sc = 'status-'.($a->status ?? 'tersedia'); @endphp
                        <span class="badge {{ $sc }}">{{ ucfirst($a->status ?? '-') }}</span>
                    </td>
                    <td>{{ $a->peminjamans()->aktif()->count() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <span>SIPANDA — Dinas Kepemudaan dan Olahraga Kota Palembang</span>
        <span>Dicetak: {{ $tanggal }}</span>
    </div>
</body>
</html>
