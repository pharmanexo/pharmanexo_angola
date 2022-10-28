<?php

class RemoveVendaDiferenciada extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {

        $this->db->where('dias > 0');
        $vds = $this->db->get('vendas_diferenciadas')->result_array();

        foreach ($vds as $vd) {

            $data_limite = date("Y-m-d", strtotime("+{$vd['dias']} days", strtotime($vd['data_criacao'])));
            $vd['data_limite'] = $data_limite;

            $date1 = new DateTime();
            $date2 = new DateTime($data_limite);


            if ($date1 >= $date2) {
                $this->db->where('id', $vd['id']);
                $d = $this->db->delete('vendas_diferenciadas');

                if ($d) {

                    $log = [
                        'action' => 'delete',
                        'module' => 'vendas_diferenciadas',
                        'message' => 'Excluido pelo robô por data de limite - ID: ' . $vd['id'],
                        'id_usuario' => 280
                    ];

                    $this->db->insert('ci_logs', $log);

                }

            }

        }

    }
}