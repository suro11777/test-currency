<?php

namespace App\Console\Commands;

use App\Models\Api\Currency;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GetRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get-rate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var string
     */
    public $urlEng = '_eng';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        $ratesRus = $this->getRateFromApi('');
        $ratesEng = $this->getRateFromApi($this->urlEng);
        $rates = array_merge_recursive($ratesRus, $ratesEng);
        $newRates = [];
        if (!empty($rates)) {
            foreach ($rates as $rate) {
                unset($rate['nominal']);
                $newRates[] = $rate;
            }
            if (!Currency::first(['id'])) {
                Currency::insert($newRates);
            } else {
                foreach ($newRates as $value) {
                    Currency::where('alphabetic_code', $value['alphabetic_code'])->update($value);
                }
            }
        } else {
            Log::info('not rate value');
        }

    }

    /**
     * @return array|bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function getRateFromApi($url)
    {
        $request = new Request(
            'POST',
            'http://www.cbr.ru/scripts/XML_daily' . $url . '.asp',
            ['Content-Type' => 'text/xml; charset=UTF8']
        );
        $client = new Client();
        $response = $client->send($request);
        if (empty($response)) {
            Log::warning('response not found');
            return false;
        }

        $xmlData = $response->getBody()->getContents();
        $p = xml_parser_create();
        xml_parse_into_struct($p, $xmlData, $values, $index);
        xml_parser_free($p);
//dd($values);
        $ratesRus = [];
        $ratesEng = [];

        foreach ($values as $key => $value) {
            if (isset($value['attributes']["ID"])) {
                $id = $value['attributes']["ID"];
                foreach ($values as $k => $v) {
                    if ($k < $key) {
                        continue;
                    }
                    if (empty($url)) {
                        if (array_search("NUMCODE", $v)) {
                            $ratesRus[$id]['digit_code'] = $v['value'];

                        }
                        if (array_search("CHARCODE", $v)) {
                            $ratesRus[$id]['alphabetic_code'] = $v['value'];

                        }
                        if (array_search("NOMINAL", $v)) {
                            $ratesRus[$id]['nominal'] = $v['value'];

                        }
                        if (array_search("NAME", $v)) {
                            $ratesRus[$id]['name'] = $v['value'];

                        }
                        if (array_search("VALUE", $v)) {
                            $ratesRus[$id]['rate'] = (float)$v['value'] / $ratesRus[$id]['nominal'];
                            break;
                        }
                    } else {
                        if (array_search("NAME", $v)) {
                            $ratesEng[$id]['english_name'] = $v['value'];
                            break;
                        }
                    }
                }
            }

        }
        return $ratesRus ? $ratesRus : $ratesEng;
    }


}
