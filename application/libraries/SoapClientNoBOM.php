<?php

defined('BASEPATH') or exit('No direct script access allowed');

class SoapClientNoBOM extends SoapClient
{
    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $response = parent::__doRequest($request, $location, $action, $version, $one_way);
        // strip away everything but the xml (including removal of BOM)
        $response = preg_replace('#^.*(<\?xml.*>)[^>]*$#s', '$1', $response);
        // also remove unit separator
        $response = str_replace("\x1f", '', $response);
        return $response;
    }
}