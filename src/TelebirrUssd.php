<?php

namespace Vptrading\TelebirrUssd;

use Illuminate\Support\Facades\Http;
use SimpleXMLElement;
use Vptrading\TelebirrUssd\Actions\CleanResponse;
use Vptrading\TelebirrUssd\Actions\PrepareXml;

class TelebirrUssd
{
    public static function push($amount, $phone, $reference)
    {
        $xmlRequest = (new PrepareXml)->handle($reference, $phone, $amount);

        $response = Http::send('post', config('telebirrussd.url'), options: [
            'body' => $xmlRequest,
            'verify' => false
        ]);

        $cleanResponse = (new CleanResponse)->handle($response->body());

        return $cleanResponse;
    }

    public static function deconstruct($data)
    {
        $cleanResponse = (new CleanResponse)->handle($data);

        return $cleanResponse;
    }
}
