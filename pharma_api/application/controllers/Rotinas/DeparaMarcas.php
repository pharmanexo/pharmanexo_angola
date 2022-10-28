<?php

class DeparaMarcas extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        $fornecedores = $this->db
            ->where('sintese', 1)
           // ->where('id', 5002)
            ->get('fornecedores')->result_array();


        foreach ($fornecedores as $fornecedor) {
            $marcas = $this->db
                ->select('marca')
                ->where('id_fornecedor', $fornecedor['id'])
                ->where("(id_marca = 0 or id_marca is null)")
                ->group_by('marca')
                ->get('produtos_catalogo')
                ->result_array();


            foreach ($marcas as $marca) {

                $m = $this->db->where('marca', $marca['marca'])->get('marcas')->row_array();

                if (!empty($m)) {
                    $m['prod'] = $marca;

                    $this->db
                        ->where('marca', $marca['marca'])
                        ->where('id_fornecedor', $fornecedor['id'])
                        ->where("(id_marca = 0 or id_marca is null)")
                        ->update('produtos_catalogo', ['id_marca' => $m['id']]);
                }

            }
        }


    }

} // class

