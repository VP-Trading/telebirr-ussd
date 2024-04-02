<?php

namespace Vptrading\TelebirrUssd\Actions;

use Illuminate\Http\Client\Response;

class CleanResponse
{
    public function handle($data)
    {
        $clean_xml = str_ireplace(['soapenv:', 'res:', 'api:'], '', $data);
        $cxml = simplexml_load_string($clean_xml);
        $json = json_encode($cxml);
        $array = json_decode($json, true);

        return $array;
    }
}
