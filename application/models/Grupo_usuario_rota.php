<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grupo_usuario_rota extends MY_Model
{
    public function __construct()
    {
    	parent::__construct();

    	$this->table = "grupos_usuarios_rotas";
    }


    public function get_routes($grupo){
        if (!isset($grupo)) return false;

        $this->db->select("*");
        $this->db->where("tipo_usuario = {$grupo} and situacao = 1");
        $this->db->join("rotas", "grupos_usuarios_rotas.id_rota = rotas.id");
        $this->db->order_by('posicao', 'ASC');
        
        return $this->db->get($this->table)->result_array();
    }

    public function get_routes_fornecedor($id_fornecedor, $grupo = null){
        if (!isset($id_fornecedor)) return false;

        if ($this->session->id_usuario == 187) {

            $this->db->select("*");
            $this->db->where("grupo", "1");
            $this->db->where("situacao", "1");
            $this->db->order_by('posicao', 'ASC');
            $rotas = $this->db->get("rotas")->result_array();
        } else {

            $this->db->select("*");
            if(isset($grupo)) $this->db->where("tipo_usuario = {$grupo}");
            $this->db->where("id_fornecedor = {$id_fornecedor}");

            $this->db->where("grupo", "1");
            $this->db->where("situacao", "1");
            $this->db->order_by('posicao', 'ASC');
            $rotas = $this->db->get("vw_fornecedores_usuarios_rotas")->result_array();
        }

        return $rotas;
    }
    
    public function atualizar($post)
    {

        $this->db->trans_begin();

        // Remover rotas
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);
        $this->db->where_in('tipo_usuario', [1, 2, 3]);
        $this->db->delete($this->table);

        $data = [];
    
        // inserir novas rotas financeiro
        if (isset($post['fin'])) {
            foreach ($post['fin'] as $id ) {
                $data = [
                    'id_rota' => $id,
                    'tipo_usuario' => 3,
                    'id_fornecedor' => $this->session->id_fornecedor
                ];
    
                $this->db->insert($this->table, $data);
            }
        }

        // inserir novas rotas dist
        if (isset($post['dist'])) {
            foreach ($post['dist'] as $id ) {
                $data = [
                    'id_rota' => $id,
                    'tipo_usuario' => 4,
                    'id_fornecedor' => $this->session->id_fornecedor
                ];

                var_dump($data);
                exit();

                $this->db->insert($this->table, $data);
            }
        }

        // inserir novas rotas comercial
        if ($post['com']) {
            foreach ($post['com'] as $id ) {
                $data = [
                    'id_rota' => $id,
                    'tipo_usuario' => 2,
                    'id_fornecedor' => $this->session->id_fornecedor
                ];

                $this->db->insert($this->table, $data);
            }
        }

        if ($this->db->trans_status() !== false) {

            $this->db->trans_commit();
            return true;

        } else {
            
            $this->db->trans_rollback();
            return false;
        }
    }

    /**
     * Atualiza as rotas do fornecedor pelo modulo admin
     *
     * @param - post do form do grupo de rotas
     * @param - int id do fornecedor
     * @return  view
     */
    public function updateAdmin($post, $id_fornecedor)
    {
        $this->db->trans_begin();

        // Remover rotas
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where_in('tipo_usuario', [1, 2, 3]);
        $this->db->delete($this->table);

        $data = [];
    
        // inserir novas rotas financeiro
        if (isset($post['fin'])) {
            foreach ($post['fin'] as $id ) {
                $data = [
                    'id_rota' => $id,
                    'tipo_usuario' => 3,
                    'id_fornecedor' => $id_fornecedor
                ];
    
                $this->db->insert($this->table, $data);
            }
        }

        // inserir novas rotas comercial
        if ($post['com']) {
            foreach ($post['com'] as $id ) {
                $data = [
                    'id_rota' => $id,
                    'tipo_usuario' => 2,
                    'id_fornecedor' => $id_fornecedor
                ];

                $this->db->insert($this->table, $data);
            }
        }


        // inserir novas rotas comercial
        if ($post['dist']) {
            foreach ($post['dist'] as $id ) {
                $data = [
                    'id_rota' => $id,
                    'tipo_usuario' => 4,
                    'id_fornecedor' => $id_fornecedor
                ];

                $this->db->insert($this->table, $data);
            }
        }

        // inserir novas rotas admin
        if ($post['adm']) {
            foreach ($post['adm'] as $id ) {
                $data = [
                    'id_rota' => $id,
                    'tipo_usuario' => 1,
                    'id_fornecedor' => $id_fornecedor
                ];

                $this->db->insert($this->table, $data);
            }
        }

        if ($this->db->trans_status() !== false) {

            $this->db->trans_commit();
            return true;

        } else {
            
            $this->db->trans_rollback();
            return false;
        }
    }

}

/* End of file .php */