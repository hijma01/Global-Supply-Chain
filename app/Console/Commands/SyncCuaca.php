<?php

namespace App\Console\Commands;

use App\Models\CacheCuaca;
use App\Models\Negara;
use App\Services\OpenMeteoService;
use Illuminate\Console\Command;

class SyncCuaca extends Command
{
    protected $signature = 'sync:cuaca';

    protected $description = 'Ambil data cuaca terkini dari Open-Meteo API untuk semua negara di tabel negara';

    public function handle(OpenMeteoService $service)
    {
        $daftarNegara = Negara::whereNotNull('lintang')
            ->whereNotNull('bujur')
            ->get();

        if ($daftarNegara->isEmpty()) {
            $this->error('Tidak ada data negara dengan koordinat. Jalankan "php artisan sync:negara" dulu.');
            return Command::FAILURE;
        }

        $this->info("Mengambil data cuaca untuk {$daftarNegara->count()} negara...");

        $jumlahBerhasil = 0;
        $jumlahGagal = 0;

        $bar = $this->output->createProgressBar($daftarNegara->count());
        $bar->start();

        foreach ($daftarNegara as $negara) {
            $dataCuaca = $service->ambilCuaca($negara->lintang, $negara->bujur);

            if ($dataCuaca === null) {
                $jumlahGagal++;
                $bar->advance();
                continue;
            }

            CacheCuaca::updateOrCreate(
                ['negara_id' => $negara->id],
                $dataCuaca
            );
            
            $jumlahBerhasil++;
            $bar->advance();

            // Jeda singkat antar request, sopan terhadap API gratis (bukan wajib, tapi praktik baik)
            usleep(200000); // 0.2 detik
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Selesai! {$jumlahBerhasil} data cuaca berhasil disimpan, {$jumlahGagal} gagal.");

        return Command::SUCCESS;
    }
}