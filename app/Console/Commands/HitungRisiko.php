<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Negara;
use App\Models\CacheCuaca;
use App\Models\DataEkonomiNegara;
use App\Models\SkorRisiko;
use App\Models\BobotSkorRisiko;
use App\Models\NilaiTukar;
use App\Models\CacheBerita;

class HitungRisiko extends Command
{
    protected $signature = 'risiko:hitung';

    protected $description = 'Menghitung skor risiko seluruh negara';

    public function handle()
    {
        $this->info('Menghitung skor risiko...');

        $bobot = BobotSkorRisiko::first();

        if (!$bobot) {
            $this->error('Bobot skor risiko belum ada.');
            return self::FAILURE;
        }

        $jumlah = 0;

        foreach (Negara::all() as $negara) {

            $cuaca = CacheCuaca::where('negara_id', $negara->id)
                ->latest('dicatat_pada')
                ->first();

            $ekonomi = DataEkonomiNegara::where('negara_id', $negara->id)
                ->latest('tahun')
                ->first();

            $kurs = NilaiTukar::where(
                    'mata_uang_tujuan',
                    $negara->kode_mata_uang
                )
                ->latest('dicatat_pada')
                ->first();

            $berita = CacheBerita::latest('diterbitkan_pada')
                ->take(20)
                ->get();


            $skorCuaca = 0;

            if ($cuaca) {

                $skorCuaca += min($cuaca->curah_hujan ?? 0, 40);

                $skorCuaca += min(($cuaca->kecepatan_angin ?? 0) / 2, 30);

                if ($cuaca->tingkat_risiko_badai == 'tinggi') {

                    $skorCuaca += 30;

                } elseif ($cuaca->tingkat_risiko_badai == 'sedang') {

                    $skorCuaca += 15;

                }

                $skorCuaca = min($skorCuaca, 100);
            }

        
            $skorInflasi = 0;

            if ($ekonomi) {

                $skorInflasi = min(($ekonomi->tingkat_inflasi ?? 0) * 8, 100);

            }

            $skorKurs = 0;

            if ($kurs) {

                if ($kurs->nilai_kurs > 1000) {

                    $skorKurs = 80;

                } elseif ($kurs->nilai_kurs > 100) {

                    $skorKurs = 60;

                } elseif ($kurs->nilai_kurs > 10) {

                    $skorKurs = 40;

                } else {

                    $skorKurs = 20;

                }

            }

            $totalBerita = $berita->count();

            if ($totalBerita == 0) {

                $skorBerita = 0;

            } else {

                $negatif = $berita->where('label_sentimen', 'negatif')->count();

                $skorBerita = round(($negatif / $totalBerita) * 100, 2);

            }

            $skorTotal =
                ($skorCuaca * ($bobot->bobot_cuaca / 100)) +
                ($skorInflasi * ($bobot->bobot_inflasi / 100)) +
                ($skorKurs * ($bobot->bobot_kurs / 100)) +
                ($skorBerita * ($bobot->bobot_berita / 100));

            if ($skorTotal >= 70) {

                $tingkat = 'tinggi';

            } elseif ($skorTotal >= 40) {

                $tingkat = 'sedang';

            } else {

                $tingkat = 'rendah';

            }

            SkorRisiko::updateOrCreate(

                [
                    'negara_id' => $negara->id,
                ],

                [
                    'skor_cuaca' => round($skorCuaca, 2),
                    'skor_inflasi' => round($skorInflasi, 2),
                    'skor_kurs' => round($skorKurs, 2),
                    'skor_sentimen_berita' => round($skorBerita, 2),
                    'skor_total' => round($skorTotal, 2),
                    'tingkat_risiko' => $tingkat,
                    'dihitung_pada' => now(),
                ]

            );

            $jumlah++;

            $this->line(
                "{$negara->nama} -> " .
                round($skorTotal, 2) .
                " ({$tingkat})"
            );
        }

        $this->newLine();

        $this->info("Selesai menghitung {$jumlah} negara.");

        return self::SUCCESS;
    }
}