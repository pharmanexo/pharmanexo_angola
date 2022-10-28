<?php

class DeParaMarcasOncoprod extends MY_Controller
{

    private $oncroprods = [12, 111, 112, 115, 120, 123];

    public function __construct()
    {
        parent::__construct();
    }

    public function index_get()
    {
        $produtos = $this->db->where_not_in('id_marca', 0)
            ->where_in('id_fornecedor', $this->oncroprods)
            ->get('produtos_catalogo')
            ->result_array();

        foreach ($produtos as $produto) {

            foreach ($this->oncroprods as $onco) {

                $key = "";

                $key = array_search($produto['id_fornecedor'], $this->oncroprods);

                if ($onco != $this->oncroprods[$key]) {

                    $this->db->where('id_fornecedor', $onco)
                        ->where('codigo', intval($produto['codigo']))
                        ->where('marca', $produto['marca'])
                        ->set('id_marca', intval($produto['id_marca']))
                        ->update('produtos_catalogo');
                }
            }
        }
    }
}