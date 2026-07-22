<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WorldPortIndexService
{
    protected string $filePath;

    public function __construct()
    {
        $this->filePath = storage_path('app/ports/updatedpub150.csv');
    }

    /**
     * Mapping nama negara WPI -> countries.dev
     */
    protected array $countryMap = [

        'Indonesia' => 'Indonesia',
        'Malaysia' => 'Malaysia',
        'Singapore' => 'Singapore',
        'Thailand' => 'Thailand',
        'Viet Nam' => 'Vietnam',
        'Philippines' => 'Philippines',
        'Brunei Darussalam' => 'Brunei',
        'Cambodia' => 'Cambodia',
        "Lao People's Democratic Republic" => 'Laos',
        'Myanmar' => 'Myanmar',

        'Japan' => 'Japan',
        'Korea, Republic Of' => 'South Korea',
        'China' => 'China',
        'India' => 'India',
        'Pakistan' => 'Pakistan',

        'United States' => 'United States',
        'Canada' => 'Canada',
        'Mexico' => 'Mexico',
        'Brazil' => 'Brazil',
        'Argentina' => 'Argentina',

        'United Kingdom' => 'United Kingdom',
        'France' => 'France',
        'Germany' => 'Germany',
        'Italy' => 'Italy',
        'Spain' => 'Spain',
        'Portugal' => 'Portugal',
        'Netherlands' => 'Netherlands',
        'Belgium' => 'Belgium',
        'Switzerland' => 'Switzerland',
        'Austria' => 'Austria',

        'Sweden' => 'Sweden',
        'Norway' => 'Norway',
        'Finland' => 'Finland',
        'Denmark' => 'Denmark',
        'Poland' => 'Poland',

        'Russian Federation' => 'Russia',
        'Ukraine' => 'Ukraine',
        'Türkiye' => 'Turkey',
        'Saudi Arabia' => 'Saudi Arabia',
        'United Arab Emirates' => 'United Arab Emirates',

        'Egypt' => 'Egypt',
        'South Africa' => 'South Africa',
        'Nigeria' => 'Nigeria',
        'Kenya' => 'Kenya',

        'Australia' => 'Australia',
        'New Zealand' => 'New Zealand',

        'Chile' => 'Chile',
        'Colombia' => 'Colombia',
        'Peru' => 'Peru',
        'Greece' => 'Greece',

    ];

    public function ambilSemuaPelabuhan(): array
    {
        if (!file_exists($this->filePath)) {
            Log::error("File CSV tidak ditemukan.");
            return [];
        }

        $handle = fopen($this->filePath, 'r');

        $header = fgetcsv($handle);
    

        $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);

        $hasil = [];

        while (($row = fgetcsv($handle)) !== false) {

            if (count($row) != count($header)) {
                continue;
            }

            $item = array_combine($header, $row);

            if (!$item) {
                continue;
            }

            $namaNegara = trim($item['Country Code'] ?? '');

            if (!isset($this->countryMap[$namaNegara])) {
                continue;
            }

            $namaNegara = $this->countryMap[$namaNegara];

            $hasil[] = [

                'nama_pelabuhan' => trim($item['Main Port Name'] ?? ''),

                'nama_negara' => $namaNegara,

                'lintang' => is_numeric($item['Latitude'] ?? '')
                    ? (float) $item['Latitude']
                    : null,

                'bujur' => is_numeric($item['Longitude'] ?? '')
                    ? (float) $item['Longitude']
                    : null,

                'ukuran_pelabuhan' => trim($item['Harbor Size'] ?? ''),

                'tipe_pelabuhan' => trim($item['Harbor Type'] ?? ''),
            ];
        }

        fclose($handle);

        return $hasil;
    }
}