<?php

class UpdateMarcas extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function index(){

        $produtos = $this->db->where('id_marca', 0)->where('id_fornecedor', 20)->get('produtos_catalogo')->result_array();

        foreach ($produtos as $produto){

            $marca = $this->db->where('marca', $produto['marca'])->get('marcas')->row_array();

            $this->db->update('produtos_catalogo', ['id_marca' => $marca['id']], "id = {$produto['id']}");

        }

    }


}