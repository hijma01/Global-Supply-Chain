<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RestCountriesService
{
    protected string $baseUrl = 'https://countries.dev';

    /**
     * Daftar 50 negara yang akan diambil.
     */
    protected array $negaraDipilih = [
        'ID', 'MY', 'SG', 'TH', 'VN',
        'PH', 'BN', 'KH', 'LA', 'MM',
        'JP', 'KR', 'CN', 'IN', 'PK',
        'US', 'CA', 'MX', 'BR', 'AR',
        'GB', 'FR', 'DE', 'IT', 'ES',
        'PT', 'NL', 'BE', 'CH', 'AT',
        'SE', 'NO', 'FI', 'DK', 'PL',
        'RU', 'UA', 'TR', 'SA', 'AE',
        'EG', 'ZA', 'NG', 'KE', 'AU',
        'NZ', 'CL', 'CO', 'PE', 'GR'
    ];

    /**
     * Ambil data 50 negara.
     */
    public function ambilSemuaNegara(): array
    {
        $response = Http::timeout(30)->get("{$this->baseUrl}/countries");

        if (!$response->successful()) {
            Log::error('Gagal mengambil data dari countries.dev API', [
                'status' => $response->status(),
            ]);
            return [];
        }

        $dataMentah = $response->json();

        if (!is_array($dataMentah)) {
            Log::error('Response countries.dev API bukan array yang valid');
            return [];
        }

        $hasil = [];

        foreach ($dataMentah as $item) {

            if (!is_array($item)) {
                continue;
            }

            if (!in_array($item['alpha2Code'] ?? '', $this->negaraDipilih)) {
                continue;
            }

            $hasil[] = $this->formatData($item);
        }

        return $hasil;
    }

    /**
     * Ambil satu negara berdasarkan kode.
     */
    public function ambilSatuNegara(string $kodeNegara): ?array
    {
        $response = Http::timeout(30)->get("{$this->baseUrl}/alpha/{$kodeNegara}");

        if (!$response->successful()) {
            Log::error("Gagal mengambil data negara {$kodeNegara}");
            return null;
        }

        $item = $response->json();

        if (!is_array($item)) {
            return null;
        }

        return $this->formatData($item);
    }

    /**
     * Format data agar sesuai tabel negara.
     */
    private function formatData(array $item): array
    {
        $kodeMataUang = null;
        $namaMataUang = null;

        if (!empty($item['currencies']) && is_array($item['currencies'])) {

            foreach ($item['currencies'] as $key => $currency) {

                if (is_array($currency)) {
                    $kodeMataUang = $currency['code'] ?? $key;
                    $namaMataUang = $currency['name'] ?? null;
                    break;
                }

                $kodeMataUang = $key;
                $namaMataUang = $currency;
                break;
            }
        }

        $lintang = null;
        $bujur = null;

        if (!empty($item['latlng']) && is_array($item['latlng'])) {
            $lintang = $item['latlng'][0] ?? null;
            $bujur = $item['latlng'][1] ?? null;
        }

        return [
            'nama'              => $item['name'] ?? '-',
            'kode_negara'       => $item['alpha2Code'] ?? null,
            'kode_negara_3'     => $item['alpha3Code'] ?? null,
            'kode_mata_uang'    => $kodeMataUang,
            'nama_mata_uang'    => $namaMataUang,
            'wilayah'           => $item['region'] ?? null,
            'sub_wilayah'       => $item['subregion'] ?? null,
            'ibu_kota'          => $item['capital'] ?? null,
            'populasi'          => $item['population'] ?? null,
            'url_bendera'       => $item['flags']['png'] ?? null,
            'lintang'           => $lintang,
            'bujur'             => $bujur,
        ];
    }
}