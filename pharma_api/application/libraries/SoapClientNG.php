<?php

class SoapClientNG extends \SoapClient
{

    public function __doRequest($req, $location, $action, $version = SOAP_1_1)
    {

        $xml = explode("\r\n", parent::__doRequest($req, $location, $action, $version));

        $response = preg_replace('/^(\x00\x00\xFE\xFF|\xFF\xFE\x00\x00|\xFE\xFF|\xFF\xFE|\xEF\xBB\xBF)/', "", $xml[0]);

        return $response;

    }

}

// Source: http://stackoverflow.com/questions/14319696/soap-issue-soapfault-exception-client-looks-like-we-got-no-xml-document
function strip_bom($str)
{
    return preg_replace('/^(\x00\x00\xFE\xFF|\xFF\xFE\x00\x00|\xFE\xFF|\xFF\xFE|\xEF\xBB\xBF)/', "", $str);
}
