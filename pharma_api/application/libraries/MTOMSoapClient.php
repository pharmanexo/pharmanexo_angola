<?php

class MTOMSoapClient extends SoapClient {
    public function __doRequest($request, $location, $action, $version, $one_way = 0) {
        $response = parent::__doRequest($request, $location, $action, $version, $one_way);
        //if resposnse content type is mtom strip away everything but the xml.
        if (strpos($response, "Content-Type: application/xop+xml") !== false) {
            //not using stristr function twice because not supported in php 5.2 as shown below
            //$response = stristr(stristr($response, "<s:"), "</s:Envelope>", true) . "</s:Envelope>";
            $tempstr = stristr($response, "<s:");
            $response = substr($tempstr, 0, strpos($tempstr, "</s:Envelope>")) . "</s:Envelope>";
        }
        //log_message($response);
        return $response;
    }

}
