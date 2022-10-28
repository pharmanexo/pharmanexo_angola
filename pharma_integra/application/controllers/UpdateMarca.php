<?php

class UpdateMarca extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index_get()
    {
        $arrayFornecedor = [];

        $fornecedores = $this->db->select('id')
			->where('id', 20)
            ->get('fornecedores')
            ->result_array();

        foreach ($fornecedores as $fornecedor) {

            $arrayFornecedor[] = intval($fornecedor['id']);
        }

        $produtos = $this->db->where_in('id_fornecedor', $arrayFornecedor)
            ->get('produtos_catalogo')
            ->result_array();

        foreach ($produtos as $produto) {

            $id_marca = $this->db->where('marca', $produto['marca'])
                ->get('marcas')->row_array()['id'];

            if (!IS_NULL($id_marca)) {

                $this->db->where('id_fornecedor', intval($produto['id_fornecedor']))
                    ->where('codigo', intval($produto['codigo']))
                    ->set('id_marca', intval($id_marca))
                    ->update('produtos_catalogo');

            } else {
                continue;
            }
        }
    }
}