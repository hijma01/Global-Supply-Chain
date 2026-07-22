<?php

namespace App\Console\Commands;

use App\Models\Negara;
use App\Models\Pelabuhan;
use App\Services\WorldPortIndexService;
use Illuminate\Console\Command;

class SyncPelabuhan extends Command
{
    /**
     * Nama command.
     */
    protected $signature = 'sync:pelabuhan';

    /**
     * Deskripsi command.
     */
    protected $description = 'Sinkronisasi data pelabuhan dunia dari World Port Index';

    /**
     * Jalankan command.
     */
    public function handle(WorldPortIndexService $service)
    {
        $this->info('Mengambil data World Port Index...');

        $dataPelabuhan = $service->ambilSemuaPelabuhan();

        if (empty($dataPelabuhan)) {
            $this->error('Tidak ada data pelabuhan yang ditemukan.');
            return Command::FAILURE;
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil seluruh negara (50 negara) dari database
        |--------------------------------------------------------------------------
        */

        $daftarNegara = Negara::all()->keyBy(function ($negara) {
            return strtolower(trim($negara->nama));
        });

        $jumlahBaru = 0;
        $jumlahUpdate = 0;
        $jumlahSkip = 0;

        $bar = $this->output->createProgressBar(count($dataPelabuhan));
        $bar->start();

        foreach ($dataPelabuhan as $item) {

            $namaNegara = strtolower(trim($item['nama_negara']));

            // Jika negara tidak ada di database, lewati
            if (!isset($daftarNegara[$namaNegara])) {
                $jumlahSkip++;
                $bar->advance();
                continue;
            }

            $negara = $daftarNegara[$namaNegara];

            $pelabuhan = Pelabuhan::updateOrCreate(

                [
                    'nama_pelabuhan' => $item['nama_pelabuhan'],
                    'negara_id'      => $negara->id,
                ],

                [
                    'lintang'          => $item['lintang'],
                    'bujur'            => $item['bujur'],
                    'ukuran_pelabuhan' => $item['ukuran_pelabuhan'],
                    'tipe_pelabuhan'   => $item['tipe_pelabuhan'],
                ]

            );

            if ($pelabuhan->wasRecentlyCreated) {
                $jumlahBaru++;
            } else {
                $jumlahUpdate++;
            }

            $bar->advance();
        }

        $bar->finish();

        $this->newLine(2);

        $this->info('======================================');
        $this->info('Sinkronisasi Pelabuhan Selesai');
        $this->info('======================================');
        $this->info("Pelabuhan Baru   : {$jumlahBaru}");
        $this->info("Pelabuhan Update : {$jumlahUpdate}");
        $this->warn("Dilewati         : {$jumlahSkip}");
        $this->info("Total Diproses   : " . count($dataPelabuhan));

        return Command::SUCCESS;
    }
}