<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WorldBankService
{
    protected string $baseUrl = 'https://api.worldbank.org/v2';

    /**
     * Mengambil data ekonomi terbaru suatu negara.
     *
     * @param string $kodeNegara ISO Alpha-2 (ID, CN, US, JP, dll)
     * @return array|null
     */
    public function ambilDataEkonomi(string $kodeNegara): ?array
    {
        try {

            return [
                'pdb' => $this->ambilIndikator($kodeNegara, 'NY.GDP.MKTP.CD'),
                'tingkat_inflasi' => $this->ambilIndikator($kodeNegara, 'FP.CPI.TOTL.ZG'),
                'nilai_ekspor' => $this->ambilIndikator($kodeNegara, 'NE.EXP.GNFS.CD'),
                'nilai_impor' => $this->ambilIndikator($kodeNegara, 'NE.IMP.GNFS.CD'),
                'tahun' => date('Y'),
            ];

        } catch (\Throwable $e) {

            Log::error('WorldBank API Error', [
                'country' => $kodeNegara,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Mengambil nilai indikator terbaru.
     */
    private function ambilIndikator(string $kodeNegara, string $indikator): ?float
    {
        $url = "{$this->baseUrl}/country/{$kodeNegara}/indicator/{$indikator}";

        $response = Http::timeout(30)->get($url, [
            'format' => 'json',
            'mrnev' => 1,
        ]);

        if (!$response->successful()) {
            return null;
        }

        $json = $response->json();

        if (!isset($json[1]) || !is_array($json[1])) {
            return null;
        }

        foreach ($json[1] as $item) {

            if (!empty($item['value'])) {
                return (float) $item['value'];
            }

        }

        return null;
    }
}