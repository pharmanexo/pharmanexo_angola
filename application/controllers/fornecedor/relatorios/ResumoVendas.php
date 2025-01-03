<?php

class ResumoVendas extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {
        $this->getData();
    }


    private function main()
    {

    }

    private function getData()
    {
         $cotacoes = $this->db
             ->select('count(distinct(cd_cotacao)) as total, count(distinct(codigo_oc)) as pedidos, cp.id_usuario, u.nome')
             ->from('cotacoes_produtos cp')
             ->join('usuarios u', 'u.id = cp.id_usuario')
             ->where("cp.data_criacao between '2022-01-01' and '2022-01-31'")
             ->where("id_usuario = 366")
             ->group_by('cp.id_usuario')
             ->get()
             ->result_array();

         foreach ($cotacoes as $k => $cotacao){

             var_dump($cotacao);
             exit();

         }

        $pedidos = $this->db->query(
                                "select count(ocs_sintese.Cd_Cotacao)
                    from ocs_sintese
                    where ocs_sintese.Cd_Cotacao in (select cpp.cd_cotacao
                                                     from cotacoes_produtos cpp
                                                              join usuarios u on u.id = cpp.id_usuario
                                                     where cpp.data_criacao between '2022-01-01' and '2022-01-31'
                                                       and id_usuario = 366
                                                     group by cd_cotacao)
                      and id_fornecedor = 20"
        )->result_array();

        var_dump($pedidos);
        exit();


    }


}