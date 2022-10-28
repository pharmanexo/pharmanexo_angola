<?php

class OncoprodNotaFiscal extends MY_Controller
{
    /**
     * @author : Chule Cabral
     * Data: 27/11/2020
     */

    private $urlClient;

    public function __construct()
    {
        parent::__construct();

        $this->urlClient = 'http://oncoweb.oncoprod.com.br/WsIntegradorPharmanexo_HOMOLOG/wsIntegradorPharmanexo_homolog/IntegradorPharmanexo.svc?wsdl';

        $this->load->model('Financeiro');
    }

    public function index_get()
    {

        $soapClient = new SoapClient($this->urlClient);

        $statusPedido = $soapClient->VerificaStatusDoPedido(['pedido' => '7001707']);

        $dadosPedido = json_decode(json_encode($statusPedido), true);

        var_dump($dadosPedido);

    }
}
