<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Negara;
use App\Models\NilaiTukar;
use App\Services\FrankfurterService;

class SyncNilaiTukar extends Command
{
    protected $signature = 'sync:nilai-tukar';

    protected $description = 'Sinkronisasi nilai tukar dari Frankfurter API';

    public function handle(FrankfurterService $service)
    {
        $this->info('Mengambil data nilai tukar...');

        $negaraList = Negara::whereNotNull('kode_mata_uang')->get();

        $jumlahBaru = 0;
        $jumlahUpdate = 0;

        foreach ($negaraList as $negara) {

            if ($negara->kode_mata_uang == 'USD') {
                continue;
            }

            $kurs = $service->ambilKurs(
                'USD',
                $negara->kode_mata_uang
            );

            if (!$kurs) {
                $this->warn("Gagal mengambil {$negara->nama}");
                continue;
            }

            $data = NilaiTukar::updateOrCreate(

                [
                    'mata_uang_dasar'   => 'USD',
                    'mata_uang_tujuan'  => $negara->kode_mata_uang,
                ],

                [
                    'nilai_kurs' => $kurs['nilai_kurs'],
                    'dicatat_pada' => now(),
                ]

            );

            if ($data->wasRecentlyCreated) {
                $jumlahBaru++;
            } else {
                $jumlahUpdate++;
            }

            $this->line(
                "{$negara->nama} : {$kurs['nilai_kurs']}"
            );
        }

        $this->info('');
        $this->info('Sinkronisasi selesai');
        $this->info("Data baru : {$jumlahBaru}");
        $this->info("Data update : {$jumlahUpdate}");

        return Command::SUCCESS;
    }
}