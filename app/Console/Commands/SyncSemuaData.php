<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SyncSemuaData extends Command
{
    protected $signature = 'sync:semua';

    protected $description = 'Sinkronisasi seluruh data dan hitung ulang skor risiko';

    public function handle()
    {
        $this->info('==============================');
        $this->info('Sinkronisasi dimulai');
        $this->info('==============================');

        $this->info('1. Sinkronisasi Cuaca...');
        Artisan::call('sync:cuaca');
        $this->line(Artisan::output());

        $this->info('2. Sinkronisasi Ekonomi...');
        Artisan::call('sync:ekonomi');
        $this->line(Artisan::output());

        $this->info('3. Sinkronisasi Nilai Tukar...');
        Artisan::call('sync:nilai-tukar');
        $this->line(Artisan::output());

        $this->info('4. Sinkronisasi Berita...');
        Artisan::call('berita:update');
        $this->line(Artisan::output());

        $this->info('5. Menghitung Skor Risiko...');
        Artisan::call('risiko:hitung');
        $this->line(Artisan::output());

        $this->info('==============================');
        $this->info('Seluruh sinkronisasi selesai');
        $this->info('==============================');

        return Command::SUCCESS;
    }
}