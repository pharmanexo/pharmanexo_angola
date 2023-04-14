<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_perfil_acesso_rota extends MY_Model
{

    protected $table = 'perfis_acesso_rotas';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = '';
    protected $order_direction = 'ASC';

    public function __construct()
    {
    	parent::__construct();
    }

    /**
     * obtem as rotas por tipo de usuario
     *
     * @param - INt tipo de usuario
     * @return array
     */
    public function get_routes($id_perfil, $id_empresa)
    {



        $this->db->select("*");
        $this->db->from("{$this->table} p");
        $this->db->where("p.id_perfil = {$id_perfil}");
        $this->db->where("p.id_empresa = {$id_empresa}");
        $this->db->where("r.situacao", '1');
        $this->db->join("rotas r", "p.id_rota = r.id");
        $this->db->order_by('r.posicao', 'ASC');
        
        return $this->db->get()->result_array();
    }

    public function get_routes_grupo($grupo)
    {
        if (!isset($grupo)) return false;

        $this->db->select("r.*");
        $this->db->from("grupos_usuarios_rotas gr");
        $this->db->join("rotas r", "gr.id_rota = r.id");
        $this->db->where("gr.id_grupo = {$grupo} and r.situacao = 1");
        $this->db->order_by('r.posicao', 'ASC');

        return $this->db->get()->result_array();
    }

    /**
     * obtem as rotas por fornecedor
     *
     * @param - INt ID do fornecedor
     * @param - INt tipo de usuario
     * @return array
     */
    public function get_routes_fornecedor($id_fornecedor, $tipo_usuario = null)
    {
        if (!isset($id_fornecedor)) return false;

        if ($this->session->id_usuario == 187) {

            $this->db->select("*");
            $this->db->where("grupo", "1");
            $this->db->where("situacao", "1");
            $this->db->order_by('posicao', 'ASC');
            $rotas = $this->db->get("rotas")->result_array();
        } else {

            $this->db->select("*");
            if(isset($tipo_usuario)) $this->db->where("tipo_usuario = {$tipo_usuario}");
            $this->db->where("id_fornecedor = {$id_fornecedor}");

            $this->db->where("grupo", "1");
            $this->db->where("situacao", "1");
            $this->db->order_by('posicao', 'ASC');
            $rotas = $this->db->get("vw_fornecedores_usuarios_rotas")->result_array();
        }

        return $rotas;
    }
    
    /**
     * Salva as rotas para um fornecedor
     *
     * @param - POST do form de rotas
     * @return bool
     */
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