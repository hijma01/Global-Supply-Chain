<?php

namespace App\Console\Commands;

use App\Models\DataEkonomiNegara;
use App\Models\Negara;
use App\Services\WorldBankService;
use Illuminate\Console\Command;

class SyncEkonomi extends Command
{
    protected $signature = 'sync:ekonomi';

    protected $description = 'Sinkronisasi data ekonomi dari World Bank API';

    public function handle(WorldBankService $service)
    {
        $negaraList = Negara::all();

        if ($negaraList->isEmpty()) {
            $this->error('Data negara masih kosong.');
            return Command::FAILURE;
        }

        $this->info("Mengambil data ekonomi {$negaraList->count()} negara...");

        $bar = $this->output->createProgressBar($negaraList->count());

        $bar->start();

        $berhasil = 0;
        $gagal = 0;

        foreach ($negaraList as $negara) {

            $data = $service->ambilDataEkonomi($negara->kode_negara);

            if (!$data) {

                $this->newLine();

                $this->error(
                    "{$negara->nama} ({$negara->kode_negara}) gagal."
                );

                $gagal++;

                $bar->advance();

                continue;
            }

            DataEkonomiNegara::updateOrCreate(

                [
                    'negara_id' => $negara->id,
                    'tahun' => $data['tahun']
                ],

                [
                    'pdb' => $data['pdb'],
                    'tingkat_inflasi' => $data['tingkat_inflasi'],
                    'nilai_ekspor' => $data['nilai_ekspor'],
                    'nilai_impor' => $data['nilai_impor']
                ]

            );

            $berhasil++;

            $bar->advance();

            usleep(150000);

        }

        $bar->finish();

        $this->newLine(2);

        $this->info("Sinkronisasi selesai.");

        $this->info("Berhasil : {$berhasil}");

        $this->warn("Gagal : {$gagal}");

        return Command::SUCCESS;
    }
}