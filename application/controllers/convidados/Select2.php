<?php

class Select2 extends Rep_controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('m_estoque', 'estoque');
    }

    public function get_compradores()
    {
        $this->output->set_content_type('application/json')->set_output(json_encode($this->select2->exec(
            $this->input->get(),
            "compradores",
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'razao_social', 'dt' => 'razao_social'],
            ]
        )));
    }

    public function to_select2_formas_pagamento()
    {
        $data = [];
        if (isset($_GET['page'])) {
            $page = $this->input->get('page');
            $length = 50;
            $data = [
                "start" => (($page - 1) * 50),
                "length" => $length
            ];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($this->select2->exec(
            array_merge($this->input->get(), $data),
            "formas_pagamento",
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'descricao', 'dt' => 'descricao'],
            ]
        )));
    }


    public function to_select2_compradores()
    {
        $data = [];
        if (isset($_GET['page'])) {
            $page = $this->input->get('page');
            $length = 50;
            $data = [
                "start" => (($page - 1) * 50),
                "length" => $length
            ];
        }


        $data['order'] = [
            ['column' => 'razao_social', 'dir' => 'ASC']
        ];


        $this->output->set_content_type('application/json')->set_output(json_encode($this->select2->exec(
            array_merge($this->input->get(), $data),
            "compradores",
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'cnpj', 'dt' => 'cnpj'],
                ['db' => 'razao_social', 'dt' => 'razao_social'],
                ['db' => 'estado', 'dt' => 'estado'],
            ]
        )));
    }


    public function get_produtos($uf = null)
    {
        $data = [];
        if (isset($uf)) {

            $consulta = $this->db->query("SELECT id FROM estados WHERE uf = '{$uf}'")->row_array();

            if (isset($consulta['id'])) {
                $uf = $consulta['id'];
                define("id_estado", $uf);
            }

            if (isset($_GET['page'])) {
                $page = $this->input->get('page');
                $length = 10;
                $data = [
                    "start" => (($page - 1) * 50),
                    "length" => $length
                ];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($this->select2->exec(
                $this->input->get(),
                "produtos_catalogo pc",
                [
                    ['db' => 'pc.id', 'dt' => 'id'],
                    ['db' => 'pc.codigo', 'dt' => 'codigo'],
                    ['db' => 'pc.ean', 'dt' => 'ean'],
                    ['db' => 'pc.descricao', 'dt' => 'descricao'],
                    ['db' => 'pc.apresentacao', 'dt' => 'apresentacao'],
                    ['db' => "(IF(pc.quantidade_unidade is null, 1, pc.quantidade_unidade))", 'dt' => 'quantidade_unidade'],
                    ['db' => 'pc.id_fornecedor', 'dt' => 'id_fornecedor'],
                    ['db' => 'pc.nome_comercial', 'dt' => 'nome_comercial', 'formatter' => function ($d, $r) {

                        $desc = (!is_null($r['apresentacao'])) ? $r['apresentacao'] : $r['descricao'];
                        return "{$d} - {$desc}";
                    }],
                    [
                        'db' => "(SELECT IF(SUM(pl.estoque) * quantidade_unidade IS NULL, 0, SUM(pl.estoque) * quantidade_unidade) FROM pharmanexo.produtos_lote pl WHERE pl.id_fornecedor = pc.id_fornecedor AND pl.codigo = pc.codigo)", 
                        'dt' => 'estoque'
                    ]
                ],
                null,
                "pc.id_fornecedor = {$this->session->id_fornecedor} AND pc.codigo IS NOT NULL"
            )));
        }
    }

}