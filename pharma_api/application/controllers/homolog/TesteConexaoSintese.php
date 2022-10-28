<?php

class TesteConexaoSintese extends CI_Controller
{

    /**
     * @author : Eric Lempê
     * Data: 25/09/2020
     */

    private $DB1, $DB2, $oncoprod;

    public function __construct()
    {
        parent::__construct();

        $this->urlCliente = $this->config->item('db_config')['url_client'];

        error_reporting(0);
        ini_set('display_errors', 0);
        ini_set('default_socket_timeout', 1800);
    }

    public function index()
    {
        try {
            $url = $this->urlCliente['principal'];

            if (strpos($this->testeUrl($url), 'funcionando') == false) {
                $data = date("d/m/Y H:i:s");

                $errorMsg = [
                    "to" => "marlon.boecker@pharmanexo.com.br",
                    "greeting" => "",
                    "subject" => "URGENTE - FALHA CONEXÃO SINTESE",
                    "message" => "<b>Falha ao se conectar com o WebService da Sintese<br>
						URL: {$url}<br>
                        <b>Data de Envio:</b> {$data} <br>
                        "
                ];

                $this->notify->send($errorMsg);

                $sms = ['5527992994049', '5527999429900'];
                #$sms = ['5527992994049'];

                foreach ($sms as $dest) {
                    $this->notify->sendSMS($dest, "URGENTE - Falha ao se conectar com o WebService da Sintese - Verifique a conexão.");
                }
            }

        } catch (Exception $ex) {

        }
    }


    private function testeUrl($url)
    {
        $client = new SoapClient("{$url}?WSDL");

        $function = 'TesteDeWebServiceAtivo';

        $options = array('location' => $url);
        $result = $client->__soapCall($function, [], $options);

        $resposta = $result->TesteDeWebServiceAtivoResult;

        return $resposta;
    }

}
