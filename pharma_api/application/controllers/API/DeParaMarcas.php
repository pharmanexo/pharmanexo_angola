<?php

class DeParaMarcas extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {
        $marcas = $this->db
            ->distinct()
            ->select("marca")
            ->where('id_fornecedor', 5018)
            ->where('id_marca = 0')
            ->get('produtos_catalogo')
            ->result_array();


        foreach ($marcas as $marca) {

            $id = $this->db
                ->distinct()
                ->select("id")
                ->where('marca', $marca['marca'])
               // ->where('id_marca > 0')
                ->get('marcas')
                ->row_array();


           if (!empty($id)){

               $data['id_marca'] = $id['id'];

               $this->db->where('marca', $marca['marca']);
               $this->db->where('id_fornecedor', 5018);
               $this->db->update('produtos_catalogo', $data);

           }

        }

    }

}

