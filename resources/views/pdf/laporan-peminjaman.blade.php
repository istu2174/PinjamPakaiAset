<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman Aset</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9px; color: #1a1a2e; }
        .header { text-align: center; padding: 15px 20px; border-bottom: 2px solid #1e3a5f; margin-bottom: 12px; }
        .header .logo { font-size: 16px; font-weight: bold; color: #1e3a5f; letter-spacing: 1px; }
        .header .subtitle { font-size: 10px; color: #4a5568; margin-top: 3px; }
        .header .report-title { font-size: 13px; font-weight: bold; color: #2563eb; margin-top: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
        .meta { display: flex; justify-content: space-between; padding: 0 20px 8px; }
        .meta-item { font-size: 8px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin: 0 20px; width: calc(100% - 40px); }
        thead tr { background-color: #1e3a5f; color: white; }
        thead th { padding: 6px 8px; text-align: left; font-size: 8px; font-weight: 600; letter-spacing: 0.3px; }
        tbody tr:nth-child(even) { background-color: #f8fafc; }
        tbody tr:hover { background-color: #eff6ff; }
        tbody td { padding: 5px 8px; border-bottom: 1px solid #e2e8f0; font-size: 8px; }
        .status-badge { display: inline-block; padding: 2px 6px; border-radius: 9px; font-size: 7px; font-weight: bold; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-disetujui { background: #d1fae5; color: #065f46; }
        .status-dipinjam { background: #ddd6fe; color: #4c1d95; }
        .status-selesai { background: #e2e8f0; color: #4a5568; }
        .status-ditolak { background: #fee2e2; color: #991b1b; }
        .priority-high { background: #fef3c7; color: #92400e; padding: 2px 5px; border-radius: 9px; font-size: 7px; font-weight: bold; }
        .priority-med { background: #dbeafe; color: #1e40af; padding: 2px 5px; border-radius: 9px; font-size: 7px; font-weight: bold; }
        .priority-low { background: #f1f5f9; color: #475569; padding: 2px 5px; border-radius: 9px; font-size: 7px; font-weight: bold; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; padding: 8px 20px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; font-size: 7px; color: #9ca3af; }
        .total-row { background: #eff6ff; font-weight: bold; }
        .total-row td { padding: 6px 8px; border-top: 2px solid #2563eb; font-size: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">SIPANDA</div>
        <div class="subtitle">Sistem Informasi Peminjaman Aset Dinas</div>
        <div class="subtitle">Dinas Kepemudaan dan Olahraga Kota Palembang</div>
        <div class="report-title">Laporan Data Peminjaman Aset</div>
    </div>

    @php
        $jmlDikembalikan = $peminjamans->filter(fn ($p) => $p->pengembalian)->count();
        $jmlTerlambat    = $peminjamans->filter(fn ($p) => $p->pengembalian && $p->pengembalian->terlambat)->count();
        $jmlUnit         = $peminjamans->whereNotIn('status', ['ditolak', 'dibatalkan'])->sum(fn ($p) => $p->jumlah_pinjam ?? 1);
    @endphp
    <table style="margin-bottom:10px; border:none;">
        <tr>
            <td style="border:none; font-size:8px; color:#666; vertical-align:top; padding:0;">
                Tanggal cetak: {{ $tanggal }}<br>
                Periode:
                @if(!empty($filter['bulan']) || !empty($filter['tahun']))
                    {{ $filter['nama_bulan'] ?? 'Semua Bulan' }} {{ $filter['tahun'] ?? '' }}
                @else
                    Semua Periode
                @endif
                <br>
                @if(!empty($filter['status'])) Filter Status: {{ ucfirst($filter['status']) }}<br>@endif
            </td>
            <td style="border:none; font-size:8px; color:#666; text-align:right; vertical-align:top; padding:0;">
                Total Peminjaman: <strong>{{ $peminjamans->count() }}</strong> transaksi ({{ $jmlUnit }} unit)<br>
                Pengembalian Tercatat: <strong>{{ $jmlDikembalikan }}</strong> (terlambat: {{ $jmlTerlambat }})<br>
                @if(($jumlahDikecualikan ?? 0) > 0)
                    <span style="color:#b45309;">* {{ $jumlahDikecualikan }} transaksi dari akun yang dikecualikan admin tidak dihitung dalam rekap ini</span>
                @endif
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th style="width:3%">No</th>
                <th style="width:12%">No. Pengajuan</th>
                <th style="width:16%">Peminjam</th>
                <th style="width:14%">Aset</th>
                <th style="width:10%">Tgl Pinjam</th>
                <th style="width:10%">Tgl Kembali</th>
                <th style="width:8%">Prioritas</th>
                <th style="width:10%">Status</th>
                <th style="width:17%">Keperluan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjamans as $i => $p)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $p->nomor_pengajuan }}</td>
                    <td>
                        {{ $p->peminjam->name ?? '-' }}<br>
                        <span style="color:#9ca3af;font-size:7px">{{ ucfirst($p->peminjam->jenis_peminjam ?? '') }}</span>
                    </td>
                    <td>
                        {{ $p->aset->nama_aset ?? '-' }}<br>
                        <span style="color:#2563eb;font-weight:bold;font-size:7.5px">Qty: {{ $p->jumlah_pinjam ?? 1 }} Unit</span>
                    </td>
                    <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td>{{ $p->tanggal_kembali->format('d/m/Y') }}</td>
                    <td>
                        @php $sc = $p->priority_score; @endphp
                        <span class="{{ $sc==3?'priority-high':($sc==2?'priority-med':'priority-low') }}">
                            {{ $sc==3?'Tinggi':($sc==2?'Sedang':'Normal') }}
                        </span>
                    </td>
                    <td>
                        @php $statusClass = 'status-'.($p->status==='diverifikasi'?'pending':$p->status); @endphp
                        <span class="status-badge {{ $statusClass }}">{{ ucfirst($p->status) }}</span>
                    </td>
                    <td style="color:#4a5568">{{ Str::limit($p->keperluan, 50) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="7">Total Peminjaman</td>
                <td colspan="2">{{ $peminjamans->count() }} data</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <span>SIPANDA — Dinas Kepemudaan dan Olahraga Kota Palembang</span>
        <span>Dicetak: {{ $tanggal }}</span>
    </div>
</body>
</html>
