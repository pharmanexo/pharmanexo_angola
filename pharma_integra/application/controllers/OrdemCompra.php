<?php

class OrdemCompra extends MY_Controller
{

    /**
     * @author : Chule Cabral
     * Data: 24/09/2020
     */

    private $oncoexo = [15, 25];
    private $oncoprod = [12, 111, 112, 115, 120, 123, 126];

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Engine');
    }

    protected function index_post()
    {

        /**
         * Controller responspavel por chamar as Determinas Models de Ordem de Compra, conforme fornecedor.
         */

        $info = $this->_post_args; //POST

        $id_fornecedor = intval($info['id_fornecedor']);

        if (in_array($id_fornecedor, $this->oncoexo)) {

            $this->load->model('Oncoexo', 'onco');

            $this->onco->index_oncoexo($info);

        } else if (in_array($id_fornecedor, $this->oncoprod)) {

            $this->load->model('Oncoprod', 'prod');

            $this->prod->index_oncoprod($info);

        } else if ($id_fornecedor == 104) {

            $this->load->model('Biohosp', 'bio');

            $this->bio->index_biohosp($info);

        } else {

            $this->notAuthorized();

        }
    }

    private function notAuthorized()
    {
        echo "Acesso não autorizado.";
    }
}