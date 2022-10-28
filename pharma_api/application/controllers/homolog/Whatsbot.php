<?php

class Whatsbot extends CI_Controller
{

    /**
     * @author : Eric Lempê
     * Data: 25/09/2020
     */

    private $DB1, $DB2, $oncoprod;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->view("bot");
    }

    public function consultaCnpj()
    {
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);

        $comprador = $this->db->where('cnpj', $input['cnpj'])->get('compradores')->row_array();


        $data = [
            "messages" => [
                "Encontrei: {$comprador['razao_social']}"
            ],
            "updateVariables" => [
                "consultacnpj" => $comprador['razao_social']
            ]
        ];


        $this->output->set_content_type('application/json')->set_output(json_decode($data));

    }
}