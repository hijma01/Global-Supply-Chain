<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Negara;
use App\Models\CacheBerita;
use Carbon\Carbon;

class BeritaService
{

    public function updateBerita($output = null)
    {

        $kategoriList = [

            'logistik' => [
                'logistics',
                'supply chain',
                'cargo'
            ],

            'perdagangan' => [
                'trade',
                'export',
                'import'
            ],

            'pelayaran' => [
                'shipping',
                'port',
                'maritime'
            ],

            'ekonomi' => [
                'economy',
                'market',
                'business'
            ],

        ];


        // batasi agar Google RSS tidak overload
        $semuaNegara = Negara::limit(20)->get();


        $output?->info(
            "Jumlah negara : ".$semuaNegara->count()
        );


        $berhasil = 0;
        $gagal = 0;



        foreach($semuaNegara as $negara){


            foreach($kategoriList as $kategoriDb=>$keyword){


                try{


                    $query =
                    $negara->nama .
                    ' (' .
                    implode(
                        ' OR ',
                        $keyword
                    )
                    .
                    ')';



                    $url =
                    'https://news.google.com/rss/search?q='
                    .
                    urlencode($query)
                    .
                    '&hl=en-US&gl=US&ceid=US:en';



                    $output?->info(
                        "Negara : ".$negara->nama
                    );


                    $output?->info(
                        "Kategori : ".$kategoriDb
                    );



                    $response = Http::timeout(30)
                        ->withHeaders([
                            'User-Agent' =>
                            'Mozilla/5.0'
                        ])
                        ->get($url);



                    if($response->failed()){


                        $gagal++;


                        $output?->error(
                            "RSS gagal"
                        );


                        continue;

                    }




                    libxml_use_internal_errors(true);



                    $xml = simplexml_load_string(
                        $response->body()
                    );



                    if(
                        !$xml ||
                        !isset($xml->channel->item)
                    ){

                        $output?->warn(
                            "RSS kosong"
                        );

                        continue;

                    }




                    $jumlahBerita = 0;




                    foreach($xml->channel->item as $item){



                        // maksimal 5 berita
                        if($jumlahBerita >= 5){

                            break;

                        }




                        $judul =
                        trim(
                            (string)$item->title
                        );



                        $link =
                        trim(
                            (string)$item->link
                        );



                        if(!$judul || !$link){

                            continue;

                        }




                        try{


                            $tanggalPublikasi =
                            Carbon::parse(
                                (string)$item->pubDate
                            );


                        }
                        catch(\Exception $e){


                            $tanggalPublikasi =
                            now();


                        }





                        /*
                        Ambil berita 3 hari terakhir saja
                        */


                        if(
                            $tanggalPublikasi
                            ->lt(
                                now()->subDays(3)
                            )
                        ){

                            continue;

                        }




                        // cek duplikat

                        if(
                            CacheBerita::where(
                                'url',
                                $link
                            )->exists()
                        ){

                            continue;

                        }





                        [
                            $skorPositif,
                            $skorNegatif,
                            $sentimen
                        ] =
                        $this->analisisSentimen(
                            $judul
                        );







                        CacheBerita::create([


                            'judul' =>
                            $judul,


                            'deskripsi' =>
                            isset($item->description)
                            ?
                            strip_tags(
                                (string)$item->description
                            )
                            :
                            null,


                            'url' =>
                            $link,


                            'sumber' =>
                            'Google News RSS',


                            'kategori' =>
                            $kategoriDb,


                            'diterbitkan_pada' =>
                            $tanggalPublikasi,


                            'skor_positif' =>
                            $skorPositif,


                            'skor_negatif' =>
                            $skorNegatif,


                            'label_sentimen' =>
                            $sentimen,


                        ]);




                        $jumlahBerita++;

                        $berhasil++;



                        $output?->info(
                            "✓ ".$judul
                        );



                    }




                    sleep(1);



                }
                catch(\Exception $e){



                    $gagal++;


                    $output?->error(
                        $e->getMessage()
                    );



                }


            }


        }



        return [

            'berhasil'=>$berhasil,

            'gagal'=>$gagal

        ];

    }






    private function analisisSentimen($teks)
    {

        $teks = strtolower($teks);



        $kataPositif = [

            'growth',
            'increase',
            'investment',
            'profit',
            'recovery',
            'trade',
            'export',
            'import',
            'agreement',
            'development',
            'expansion',
            'stable',
            'improve',
            'success',
            'cooperation',

        ];



        $kataNegatif = [

            'war',
            'crisis',
            'conflict',
            'inflation',
            'recession',
            'loss',
            'delay',
            'strike',
            'sanction',
            'risk',
            'attack',
            'disruption',
            'disaster',
            'shutdown',
            'shortage',

        ];



        $skorPositif = 0;

        $skorNegatif = 0;




        foreach($kataPositif as $kata){


            if(str_contains($teks,$kata)){

                $skorPositif++;

            }

        }





        foreach($kataNegatif as $kata){


            if(str_contains($teks,$kata)){

                $skorNegatif++;

            }

        }





        $sentimen = 'netral';



        if($skorPositif > $skorNegatif){


            $sentimen = 'positif';


        }
        elseif($skorNegatif > $skorPositif){


            $sentimen = 'negatif';


        }





        return [

            $skorPositif,

            $skorNegatif,

            $sentimen

        ];

    }





    private function hitungRisikoBerita($sentimen)
    {

        return match($sentimen){

            'positif'=>1,

            'netral'=>2,

            'negatif'=>3,

            default=>2,

        };

    }

}