<?php

namespace Tests\Unit;

use App\Services\PrioritySchedulingService;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class PrioritySchedulingServiceTest extends TestCase
{
    private PrioritySchedulingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PrioritySchedulingService();
    }

    public function test_get_skor_konversi(): void
    {
        $this->assertEquals(3, PrioritySchedulingService::getSkor('pemerintah'));
        $this->assertEquals(2, PrioritySchedulingService::getSkor('organisasi'));
        $this->assertEquals(1, PrioritySchedulingService::getSkor('umum'));
        $this->assertEquals(1, PrioritySchedulingService::getSkor('unknown'));
    }

    public function test_tentukan_tanpa_konflik(): void
    {
        $res = $this->service->tentukan(1, new Collection());

        $this->assertTrue($res['bisa_diproses']);
        $this->assertEquals('aman', $res['status']);
        $this->assertEmpty($res['peminjaman_tergeser']);
    }

    public function test_tentukan_prioritas_lebih_tinggi(): void
    {
        $peminjamanEksisting = (object) ['id' => 10, 'priority_score' => 1];
        $konflikList = new Collection([$peminjamanEksisting]);

        $res = $this->service->tentukan(3, $konflikList);

        $this->assertTrue($res['bisa_diproses']);
        $this->assertEquals('prioritas_lebih_tinggi', $res['status']);
        $this->assertEquals([10], $res['peminjaman_tergeser']);
    }

    public function test_tentukan_prioritas_sama_fcfs(): void
    {
        $peminjamanEksisting = (object) ['id' => 10, 'priority_score' => 2];
        $konflikList = new Collection([$peminjamanEksisting]);

        $res = $this->service->tentukan(2, $konflikList);

        $this->assertFalse($res['bisa_diproses']);
        $this->assertEquals('konflik_sama_prioritas', $res['status']);
        $this->assertEmpty($res['peminjaman_tergeser']);
    }

    public function test_tentukan_prioritas_lebih_rendah(): void
    {
        $peminjamanEksisting = (object) ['id' => 10, 'priority_score' => 3];
        $konflikList = new Collection([$peminjamanEksisting]);

        $res = $this->service->tentukan(1, $konflikList);

        $this->assertFalse($res['bisa_diproses']);
        $this->assertEquals('prioritas_lebih_rendah', $res['status']);
        $this->assertEmpty($res['peminjaman_tergeser']);
    }

    public function test_bandingkan_algoritma(): void
    {
        $data = [
            [
                'id'              => 1,
                'nama_peminjam'   => 'Pemohon Umum',
                'jenis_peminjam'  => 'umum',
                'waktu_pengajuan' => '2026-09-08 08:00:00',
                'jumlah_pinjam'   => 1,
            ],
            [
                'id'              => 2,
                'nama_peminjam'   => 'Dinas Pemda',
                'jenis_peminjam'  => 'pemerintah',
                'waktu_pengajuan' => '2026-09-08 09:00:00',
                'jumlah_pinjam'   => 1,
            ],
        ];

        $res = $this->service->bandingkanAlgoritma($data, 1);

        // Tanpa algoritma: Umum menang karena daftar lebih awal (jam 08:00)
        $this->assertEquals('Diterima', $res['tanpa_algoritma'][0]['status_keputusan']);
        $this->assertEquals('Ditolak (Stok Habis)', $res['tanpa_algoritma'][1]['status_keputusan']);

        // Dengan algoritma: Dinas menang karena prioritas lebih tinggi (skor 3 > 1)
        $this->assertEquals('Diterima (Prioritas)', $res['dengan_algoritma'][0]['status_keputusan']);
        $this->assertEquals(2, $res['dengan_algoritma'][0]['id']);

        // Ada perbedaan terdeteksi
        $this->assertTrue($res['ada_perbedaan']);
        $this->assertCount(2, $res['perbedaan']);
    }
}
