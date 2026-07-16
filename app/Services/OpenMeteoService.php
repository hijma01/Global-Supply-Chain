<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk mengambil data cuaca dari Open-Meteo API.
 * Gratis, tanpa API Key, tanpa batas kuota bulanan.
 * Dokumentasi: https://open-meteo.com/en/docs
 */
class OpenMeteoService
{
    protected string $baseUrl = 'https://api.open-meteo.com/v1/forecast';

    /**
     * Ambil data cuaca terkini berdasarkan koordinat (lintang, bujur).
     * Return array siap disimpan ke tabel `cache_cuaca`.
     */
    public function ambilCuaca(float $lintang, float $bujur): ?array
    {
        $response = Http::timeout(30)->get($this->baseUrl, [
            'latitude' => $lintang,
            'longitude' => $bujur,
            'current' => 'temperature_2m,precipitation,wind_speed_10m,weather_code',
            'timezone' => 'auto',
        ]);

        if (!$response->successful()) {
            Log::error('Gagal mengambil data dari Open-Meteo API', [
                'status' => $response->status(),
                'lintang' => $lintang,
                'bujur' => $bujur,
            ]);
            return null;
        }

        $data = $response->json();

        if (!is_array($data) || empty($data['current'])) {
            Log::error('Response Open-Meteo API tidak sesuai format yang diharapkan');
            return null;
        }

        $current = $data['current'];

        $suhu = $current['temperature_2m'] ?? null;
        $curahHujan = $current['precipitation'] ?? 0;
        $kecepatanAngin = $current['wind_speed_10m'] ?? 0;
        $kodeCuaca = $current['weather_code'] ?? 0;

        return [
            'suhu' => $suhu,
            'curah_hujan' => $curahHujan,
            'kecepatan_angin' => $kecepatanAngin,
            'tingkat_risiko_badai' => $this->hitungTingkatRisikoBadai($kodeCuaca, $kecepatanAngin, $curahHujan),
            'dicatat_pada' => now(),
        ];
    }

    /**
     * Algoritma sederhana untuk menentukan tingkat risiko badai,
     * berdasarkan kode cuaca WMO dan kecepatan angin/curah hujan.
     *
     * Kode cuaca WMO (weather_code) referensi dari Open-Meteo:
     * 0-3   : Cerah - berawan sebagian
     * 45-48 : Berkabut
     * 51-67 : Gerimis - hujan
     * 71-77 : Salju
     * 80-82 : Hujan deras (shower)
     * 95-99 : Badai petir (thunderstorm)
     *
     * Silakan sesuaikan lagi ambang batasnya sesuai kebutuhan/analisis kamu sendiri.
     */
    private function hitungTingkatRisikoBadai(int $kodeCuaca, float $kecepatanAngin, float $curahHujan): string
    {
        // Badai petir atau angin sangat kencang -> risiko tinggi
        if ($kodeCuaca >= 95 || $kecepatanAngin >= 50) {
            return 'tinggi';
        }

        // Hujan deras/shower atau angin cukup kencang -> risiko sedang
        if ($kodeCuaca >= 80 || $kecepatanAngin >= 25 || $curahHujan >= 10) {
            return 'sedang';
        }

        return 'rendah';
    }
}