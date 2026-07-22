<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WorldBankService
{
    protected string $baseUrl = 'https://api.worldbank.org/v2';

    public function ambilDataEkonomi(string $kodeNegara): ?array
    {
        try {

            $pdb = $this->ambilIndikator($kodeNegara,'NY.GDP.MKTP.CD');
            $inflasi = $this->ambilIndikator($kodeNegara,'FP.CPI.TOTL.ZG');
            $ekspor = $this->ambilIndikator($kodeNegara,'NE.EXP.GNFS.CD');
            $impor = $this->ambilIndikator($kodeNegara,'NE.IMP.GNFS.CD');

            // minimal ada satu data
            if (
                is_null($pdb) &&
                is_null($inflasi) &&
                is_null($ekspor) &&
                is_null($impor)
            ) {
                return null;
            }

            return [

                'pdb' => $pdb,

                'tingkat_inflasi' => $inflasi,

                'nilai_ekspor' => $ekspor,

                'nilai_impor' => $impor,

                'tahun' => now()->year,

            ];

        } catch (\Throwable $e) {

            Log::error($e->getMessage());

            return null;
        }
    }

    private function ambilIndikator(string $kodeNegara,string $indikator): ?float
    {
        $response = Http::timeout(30)->retry(3,1000)->get(

            "{$this->baseUrl}/country/{$kodeNegara}/indicator/{$indikator}",

            [

                'format'=>'json',

                'per_page'=>60,

            ]

        );

        if(!$response->successful()){

            return null;

        }

        $json = $response->json();

        if(!isset($json[1])){

            return null;

        }

        foreach($json[1] as $row){

            if(isset($row['value']) && $row['value'] !== null){

                return (float)$row['value'];

            }

        }

        return null;
    }
}