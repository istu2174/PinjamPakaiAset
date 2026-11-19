<?php

namespace App\Console\Commands;

use App\Services\ReminderService;
use Illuminate\Console\Command;

/**
 * KirimReminderCommand
 *
 * Artisan command untuk mengirim reminder otomatis ke peminjam.
 * Dijalankan setiap hari melalui Laravel Scheduler.
 *
 * Jadwal: setiap hari pukul 08.00 WIB
 * Command: php artisan reminder:kirim
 */
class KirimReminderCommand extends Command
{
    protected $signature   = 'reminder:kirim';
    protected $description = 'Kirim notifikasi reminder otomatis kepada peminjam (H-7 s/d H-0)';

    public function __construct(
        private readonly ReminderService $reminderService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Memproses reminder otomatis SIPANDA...');

        $hasil = $this->reminderService->prosesHarian();

        $this->info("✓ Reminder terkirim : {$hasil['terkirim']}");
        $this->info("  Dilewati (sudah ada): {$hasil['dilewati']}");
        $this->newLine();
        $this->info('Proses reminder selesai.');

        return Command::SUCCESS;
    }
}
