<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rota extends MY_Model
{
    public function __construct()
    {
    	parent::__construct();

    	$this->table = "rotas";
    }

    /**
     *  Obtem as rotas de administrador
     *
     * @param - int nivel do usuario
     * @return array
     */
    public function rotasAdmin($perfil)
    {
        # Obtem os IDs das rotas do perfil do usuario
        $rotas = $this->db->where('id', $perfil)->get('perfis')->row_array();

        $this->db->select('*');
        $this->db->where("id in (" . $rotas['id_rotas'] . ")");
        $this->db->order_by('posicao', 'asc');
        $result = $this->db->get('rotas')->result_array();

        // $this->db->select('rotas.*');
        // $this->db->from('admin_rotas');
        // $this->db->join('rotas', 'rotas.id = admin_rotas.id_rota');
        // $this->db->where('id_usuario', $perfil);
        // $this->db->order_by('rotas.posicao', 'asc');
        // $result = $this->db->get()->result_array();

        return $result;
    }
}

/* End of file .php */