<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_rota extends MY_Model
{

    protected $table = 'rotas';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = '';
    protected $order_direction = 'ASC';

    public function __construct()
    {
    	parent::__construct();
    }

    /**
     *  Obtem as rotas de administrador
     *
     * @param - int id usuario
     * @return array
     */
    public function rotasAdmin($perfil)
    {
        # Obtem os IDs das rotas do perfil do usuario
        $rotas = $this->db->where('id', $perfil)->get('perfis')->row_array();

        $this->db->select('*');
        $this->db->where("id in (" . $rotas['id_rotas'] . ")");
        $this->db->where("situacao", 1);
        $this->db->order_by('posicao', 'asc');
        $result = $this->db->get('rotas')->result_array();

    	return $result;
    }
}

/* End of file .php */