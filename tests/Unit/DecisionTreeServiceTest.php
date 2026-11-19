<?php

namespace Tests\Unit;

use App\Services\DecisionTreeService;
use PHPUnit\Framework\TestCase;

class DecisionTreeServiceTest extends TestCase
{
    private DecisionTreeService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DecisionTreeService();
    }

    public function test_klasifikasi_layak_pakai_kondisi_baik_dan_muda(): void
    {
        $res = $this->service->klasifikasi([
            'kondisi_fisik' => 5,
            'usia_aset' => 2,
            'riwayat_perawatan_rutin' => true,
            'frekuensi_penggunaan' => 3,
            'masih_bisa_diperbaiki' => true,
        ]);

        $this->assertEquals('layak_pakai', $res['hasil']);
        $this->assertCount(2, $res['jalur']);
    }

    public function test_klasifikasi_layak_pakai_kondisi_baik_tua_tapi_dirawat(): void
    {
        $res = $this->service->klasifikasi([
            'kondisi_fisik' => 4,
            'usia_aset' => 12,
            'riwayat_perawatan_rutin' => true,
            'frekuensi_penggunaan' => 2,
            'masih_bisa_diperbaiki' => true,
        ]);

        $this->assertEquals('layak_pakai', $res['hasil']);
        $this->assertCount(3, $res['jalur']);
    }

    public function test_klasifikasi_perlu_perawatan_kondisi_baik_tua_tanpa_perawatan(): void
    {
        $res = $this->service->klasifikasi([
            'kondisi_fisik' => 3,
            'usia_aset' => 11,
            'riwayat_perawatan_rutin' => false,
            'frekuensi_penggunaan' => 4,
            'masih_bisa_diperbaiki' => true,
        ]);

        $this->assertEquals('perlu_perawatan', $res['hasil']);
    }

    public function test_klasifikasi_perlu_perawatan_kondisi_buruk_tapi_bisa_diperbaiki(): void
    {
        $res = $this->service->klasifikasi([
            'kondisi_fisik' => 2,
            'usia_aset' => 5,
            'riwayat_perawatan_rutin' => false,
            'frekuensi_penggunaan' => 5,
            'masih_bisa_diperbaiki' => true,
        ]);

        $this->assertEquals('perlu_perawatan', $res['hasil']);
    }

    public function test_klasifikasi_layak_lelang_kondisi_buruk_dan_tidak_bisa_diperbaiki(): void
    {
        $res = $this->service->klasifikasi([
            'kondisi_fisik' => 1,
            'usia_aset' => 15,
            'riwayat_perawatan_rutin' => false,
            'frekuensi_penggunaan' => 5,
            'masih_bisa_diperbaiki' => false,
        ]);

        $this->assertEquals('layak_lelang', $res['hasil']);
    }
}



