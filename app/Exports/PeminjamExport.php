<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PeminjamExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    /**
     * Data peminjam yang akan diekspor.
     */
    public function collection()
    {
        return User::peminjam()
            ->withCount('peminjamans')
            ->orderBy('name')
            ->get();
    }

    /**
     * Header kolom Excel.
     */
    public function headings(): array
    {
        return [
            'No.',
            'Nama Lengkap',
            'Email',
            'NIK',
            'No. HP',
            'Alamat',
            'Jenis Peminjam',
            'Skor Prioritas',
            'Status Akun',
            'Rekap Bulanan',
            'Total Peminjaman',
            'Tanggal Daftar',
        ];
    }

    /**
     * Mapping data untuk setiap baris.
     */
    public function map($user): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $user->name,
            $user->email,
            $user->nik ?? '-',
            $user->no_hp ?? '-',
            $user->alamat ?? '-',
            ucfirst($user->jenis_peminjam),
            $user->priority_score,
            $user->is_active ? 'Aktif' : 'Nonaktif',
            $user->dikecualikan_rekap ? 'Dikecualikan' : 'Dihitung',
            $user->peminjamans_count,
            $user->created_at->format('d/m/Y'),
        ];
    }

    /**
     * Style header row.
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => '1E3A5F']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }

    /**
     * Nama sheet di Excel.
     */
    public function title(): string
    {
        return 'Data Peminjam';
    }
}
