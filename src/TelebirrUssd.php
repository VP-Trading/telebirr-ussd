<?php

namespace Vptrading\TelebirrUssd;

use Illuminate\Support\Facades\Http;
use SimpleXMLElement;

class TelebirrUssd
{
    public static function push($amount, $phone, $reference)
    {
        $xml = new SimpleXMLElement('<api:Request></api:Request>', LIBXML_NOERROR, false, 'api', true);

        $header = $xml->addChild('req:req:Header');
        $body = $xml->addChild('req:req:Body');

        $header->addChild('req:req:Version', '1.0');
        $header->addChild('req:req:CommandID', 'InitTrans_BuyGoodsForCustomer');
        $header->addChild('req:req:OriginatorConversationID', $reference);

        $caller = $header->addChild('req:req:Caller');
        $caller->addChild('req:req:CallerType', '2');
        $caller->addChild('req:req:ThirdPartyID', config('telebirrussd.third_party_id'));
        $caller->addChild('req:req:Password', config('telebirrussd.password'));
        $caller->addChild('req:req:ResultURL', config('telebirrussd.result_url'));

        $header->addChild('req:req:KeyOwner', '1');
        $header->addChild('req:req:Timestamp', now()->format("YmdHis"));

        $identity = $body->addChild('req:req:Identity');

        $initiator = $identity->addChild('req:req:Initiator');
        $initiator->addChild('req:req:IdentifierType', '12');
        $initiator->addChild('req:req:Identifier', config('telebirrussd.operator_id'));
        $initiator->addChild('req:req:SecurityCredential', config('telebirrussd.security_credential'));
        $initiator->addChild('req:req:ShortCode', config('telebirrussd.short_code'));

        $primaryParty = $identity->addChild('req:req:PrimaryParty');
        $primaryParty->addChild('req:req:IdentifierType', '1');
        $primaryParty->addChild('req:req:Identifier', $phone);

        $receiverParty = $identity->addChild('req:req:ReceiverParty');
        $receiverParty->addChild('req:req:IdentifierType', '4');
        $receiverParty->addChild('req:req:Identifier', config('telebirrussd.short_code'));

        $transactionRequest = $body->addChild('req:req:TransactionRequest');
        $parameters = $transactionRequest->addChild('req:req:Parameters');
        $parameters->addChild('req:req:Amount', $amount);
        $parameters->addChild('req:req:Currency', 'ETB');

        $customXML = new SimpleXMLElement($xml->asXML(), LIBXML_NOERROR);

        $dom = dom_import_simplexml($customXML);

        $cleanXml = $dom->ownerDocument->saveXML($dom->ownerDocument->documentElement);

        $soapHeader = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:api="http://cps.huawei.com/cpsinterface/api_requestmgr" xmlns:req="http://cps.huawei.com/cpsinterface/request" xmlns:com="http://cps.huawei.com/cpsinterface/common"><soapenv:Header/><soapenv:Body>';

        $soapFooter = '</soapenv:Body></soapenv:Envelope>';

        $xmlRequest = $soapHeader . $cleanXml . $soapFooter;

        $response = Http::send('post', config('telebirrussd.url'), options: [
            'body' => $xmlRequest,
            'verify' => false
        ]);

        $clean_xml = str_ireplace(['soapenv:', 'res:', 'api:'], '', $response->body());
        $cxml = simplexml_load_string($clean_xml);
        $json = json_encode($cxml);
        $array = json_decode($json, true);

        return $array;
    }

    public static function deconstruct($data)
    {
        $clean_xml = str_ireplace(['soapenv:', 'res:', 'api:', 'com:'], '', $data);
        $cxml = simplexml_load_string($clean_xml);
        $json = json_encode($cxml);
        $array = json_decode($json, true);

        return $array;
    }
}
