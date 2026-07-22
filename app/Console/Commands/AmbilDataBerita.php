<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BeritaService;

class AmbilDataBerita extends Command
{
    protected $signature = 'berita:update';

    protected $description = 'Sinkronisasi berita dari Google News RSS';

    public function handle(BeritaService $service)
    {
        $this->info('=========================================');
        $this->info(' Memulai sinkronisasi berita...');
        $this->info('=========================================');

        $hasil = $service->updateBerita($this);

        $this->newLine();

        $this->info('=========================================');
        $this->info(' Sinkronisasi selesai');
        $this->info('=========================================');

        $this->line('Berhasil : ' . $hasil['berhasil']);
        $this->line('Gagal    : ' . $hasil['gagal']);

        return Command::SUCCESS;
    }
}