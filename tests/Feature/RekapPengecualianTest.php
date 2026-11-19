<?php

namespace Tests\Feature;

use App\Models\Aset;
use App\Models\KategoriAset;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Akun peminjam yang ditandai admin "dikecualikan_rekap" tetap bisa meminjam
 * secara normal, tetapi aktivitasnya tidak dihitung dalam rekap bulanan.
 */
class RekapPengecualianTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'is_active' => true]);
    }

    private function peminjam(array $attrs = []): User
    {
        return User::factory()->create(array_merge([
            'role' => 'peminjam', 'is_active' => true, 'jenis_peminjam' => 'umum', 'priority_score' => 1,
        ], $attrs));
    }

    private ?Aset $aset = null;

    private function peminjaman(User $peminjam, string $nomor): Peminjaman
    {
        $aset = $this->aset ??= Aset::create([
            'kategori_id' => KategoriAset::create(['nama' => 'Elektronik'])->id,
            'kode_aset' => 'TEST-001', 'nama_aset' => 'Laptop Uji', 'jumlah' => 10,
            'kondisi' => 'layak_pakai', 'status' => 'tersedia', 'bisa_dipinjam' => true,
        ]);

        return Peminjaman::create([
            'peminjam_id' => $peminjam->id, 'aset_id' => $aset->id, 'jumlah_pinjam' => 1,
            'nomor_pengajuan' => $nomor, 'tanggal_pinjam' => now()->addDay(), 'tanggal_kembali' => now()->addDays(2),
            'keperluan' => 'Uji', 'priority_score' => 1, 'status' => 'selesai',
        ]);
    }

    public function test_scope_rekap_mengabaikan_akun_yang_dikecualikan(): void
    {
        $normal  = $this->peminjam();
        $khusus  = $this->peminjam(['dikecualikan_rekap' => true]);

        $this->peminjaman($normal, 'PJM-T-1');
        $this->peminjaman($khusus, 'PJM-T-2');

        $this->assertSame(1, Peminjaman::masukRekap()->count());
        $this->assertSame(1, Peminjaman::dikecualikanRekap()->count());
        $this->assertSame(2, Peminjaman::count());
    }

    public function test_admin_bisa_menandai_dan_mencabut_tanda_rekap(): void
    {
        $admin = $this->admin();
        $user  = $this->peminjam();

        $this->actingAs($admin)
            ->post(route('admin.users.toggle-rekap', $user), ['catatan_pengecualian' => 'Akun internal'])
            ->assertRedirect();
        $this->assertTrue($user->fresh()->dikecualikan_rekap);
        $this->assertSame('Akun internal', $user->fresh()->catatan_pengecualian);

        $this->actingAs($admin)->post(route('admin.users.toggle-rekap', $user))->assertRedirect();
        $this->assertFalse($user->fresh()->dikecualikan_rekap);
        $this->assertNull($user->fresh()->catatan_pengecualian);
    }

    public function test_admin_bisa_verifikasi_sekaligus_menandai_akun_baru(): void
    {
        $user = $this->peminjam(['is_active' => false]);

        $this->actingAs($this->admin())
            ->post(route('admin.users.toggle-aktif', $user), ['dikecualikan_rekap' => '1'])
            ->assertRedirect();

        $user->refresh();
        $this->assertTrue($user->is_active);
        $this->assertTrue($user->dikecualikan_rekap);
    }

    public function test_hanya_peminjam_yang_bisa_dikecualikan(): void
    {
        $pengelola = User::factory()->create(['role' => 'pengelola', 'is_active' => true]);

        $this->actingAs($this->admin())
            ->post(route('admin.users.toggle-rekap', $pengelola))
            ->assertSessionHas('error');

        $this->assertFalse((bool) $pengelola->fresh()->dikecualikan_rekap);
    }

    public function test_bukan_admin_tidak_bisa_menandai(): void
    {
        $user = $this->peminjam();

        $this->actingAs($this->peminjam())
            ->post(route('admin.users.toggle-rekap', $user))
            ->assertForbidden();

        $this->assertFalse((bool) $user->fresh()->dikecualikan_rekap);
    }

    public function test_halaman_laporan_dan_rekap_pdf_berjalan(): void
    {
        $admin = $this->admin();
        $this->peminjaman($this->peminjam(), 'PJM-T-3');
        $this->peminjaman($this->peminjam(['dikecualikan_rekap' => true, 'name' => 'Akun Khusus']), 'PJM-T-4');

        $this->actingAs($admin)->get(route('admin.laporan.index'))
            ->assertOk()->assertSee('Akun Khusus');

        $this->actingAs($admin)
            ->get(route('admin.laporan.peminjaman.pdf', ['bulan' => now()->month, 'tahun' => now()->year]))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }
}
