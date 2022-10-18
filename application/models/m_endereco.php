<?php 

if (! defined('BASEPATH')) exit('No direct script access');

class M_endereco extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
	
	public function retorna_estados()
	{
		$this->db->order_by("estado", "asc");
		$consulta = $this->db->get("p_estados");	
		if ($consulta->num_rows() > 0) 
		  return $consulta;
		else
			return false;
	}
		
	public function retorna_cidades() {	
		$id_estado = $this->input->post("id_estado");		
		$this->db->where("id_estado", $id_estado);		
		$this->db->order_by("cidade", "asc");		
		$consulta = $this->db->get("p_cidades");		
		if ($consulta->num_rows() > 0) 
		  return $consulta;
		else
			return false;
	}
	
	public function retorna_bairros($id_cidade=0) {
		$id_cidade = $this->input->post("id_cidade");
		if ($id_cidade != 0) {
		  $this->db->where("id_cidade", $id_cidade);		
		}		
		$this->db->order_by("bairro", "asc");		
		$consulta = $this->db->get("p_bairros");		
		if ($consulta->num_rows() > 0) 
		  return $consulta;
		else
			return false;
	}
	public function retorna_comunidades($id_bairro=0) {
		$id_bairro = $this->input->post("id_bairro");
		if ($id_bairro != 0) {
		  $this->db->where("id_bairro", $id_bairro);		
		}
		$this->db->order_by("comunidade", "asc");		
		$consulta = $this->db->get("p_comunidades");		
		if ($consulta->num_rows() > 0) 
		  return $consulta;
		else
			return false;
	}
		
	public function retorna_tipos_logradouros()
	{
		$this->db->order_by("id", "asc");
		$consulta = $this->db->get("p_tipos_logradouros");		
		if ($consulta->num_rows() > 0) 
		  return $consulta;
		else
			return false;
	}

	public function get_row($id){
        if (!isset($id)) return [];

        $this->db->where("id", $id);
        return $this->db->get("enderecos")->row_array();
    }
}