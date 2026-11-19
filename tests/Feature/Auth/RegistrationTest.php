<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);
    }


    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'nik' => '1234567890123456',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Merdeka No. 1 Palembang',
            'jenis_peminjam' => 'umum',
            'foto_ktp' => \Illuminate\Http\UploadedFile::fake()->image('ktp.jpg'),
        ]);

        // Akun baru wajib diverifikasi admin dulu: tidak auto-login,
        // diarahkan ke halaman login, dan tersimpan dalam status non-aktif.
        $response->assertSessionHasNoErrors();
        $this->assertGuest();
        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('users', [
            'email'     => 'testuser@example.com',
            'role'      => 'peminjam',
            'is_active' => false,
        ]);
    }
}



