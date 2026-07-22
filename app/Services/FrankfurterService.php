<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FrankfurterService
{
    /**
     * Base URL Frankfurter API v1
     */
    protected string $baseUrl = 'https://api.frankfurter.dev/v1';

    /**
     * Mengambil nilai tukar secara real-time.
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
                    'base'    => $from,
                    'symbols' => $to,
                ]);

            if (!$response->successful()) {

                Log::error('Frankfurter API gagal.', [
                    'url' => $this->baseUrl . '/latest',
                    'from' => $from,
                    'to' => $to,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return null;
            }

            $data = $response->json();

            if (!isset($data['rates'][$to])) {

                Log::warning('Mata uang tidak ditemukan', [
                    'from' => $from,
                    'to' => $to,
                    'response' => $data,
                ]);

                return null;
            }

            return [

                'mata_uang_dasar'  => $from,

                'mata_uang_tujuan' => $to,

                'nilai_kurs'       => $data['rates'][$to],

                'tanggal'          => $data['date'] ?? now()->toDateString(),

            ];

        } catch (\Throwable $e) {

            Log::error('Frankfurter Error : '.$e->getMessage());

            return null;
        }
    }
}