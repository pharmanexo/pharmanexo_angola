<?php

class CheckCnpj extends MY_Controller
{


    public function __construct()
    {
        parent::__construct();

    }

    protected function index_post()
    {

        $post = file_get_contents("php://input");

        $post = json_decode($post, true);

        $configFornecedor = $this->db->where('cnpj', $post['cnpj'])
            ->get('fornecedores')
            ->row_array()['config'];

        $statusFalse =
            [
                "status" => false,
                "url" => ""
            ];

        if (IS_NULL($configFornecedor)) {
            echo json_encode($statusFalse);
            exit();
        }

        $url = json_decode($configFornecedor, true);

        if (!isset($url['connect_pharma'])) {
            echo json_encode($statusFalse);
            exit();
        }

        $statusTrue =
            [
                "status" => true,
                "url" => $url['connect_pharma']
            ];

        echo json_encode($statusTrue, JSON_UNESCAPED_SLASHES);
    }
}