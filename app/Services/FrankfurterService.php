<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FrankfurterService
{
    /**
     * Base URL Frankfurter API
     */
    protected string $baseUrl = 'https://api.frankfurter.app';

    /**
     * Mengambil nilai tukar secara real-time.
     *
     * @param string $from
     * @param string $to
     * @return array|null
     */
    public function ambilKurs(string $from, string $to): ?array
    {
        try {

            $from = strtoupper(trim($from));
            $to   = strtoupper(trim($to));

            if (empty($from) || empty($to)) {
                return null;
            }

            // Jika mata uang sama
            if ($from === $to) {
                return [
                    'mata_uang_dasar'   => $from,
                    'mata_uang_tujuan'  => $to,
                    'nilai_kurs'        => 1,
                    'tanggal'           => now()->toDateString(),
                ];
            }

            $response = Http::timeout(20)
                ->acceptJson()
                ->get($this->baseUrl . '/latest', [
                    'from' => $from,
                    'to'   => $to,
                ]);

            if (!$response->successful()) {

                Log::error('Frankfurter API gagal.', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                return null;
            }

            $data = $response->json();

            if (!isset($data['rates'])) {

                Log::warning('Response tidak memiliki rates.', [
                    'response' => $data,
                ]);

                return null;
            }

            if (!array_key_exists($to, $data['rates'])) {

                Log::warning('Kode mata uang tidak ditemukan.', [
                    'from' => $from,
                    'to' => $to,
                    'response' => $data,
                ]);

                return null;
            }

            return [

                'mata_uang_dasar' => $from,

                'mata_uang_tujuan' => $to,

                'nilai_kurs' => $data['rates'][$to],

                'tanggal' => $data['date'] ?? now()->toDateString(),

            ];

        } catch (\Throwable $e) {

            Log::error('Frankfurter Error : ' . $e->getMessage());

            return null;
        }
    }
}