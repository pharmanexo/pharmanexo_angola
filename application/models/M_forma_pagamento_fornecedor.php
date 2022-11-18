<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_forma_pagamento_fornecedor extends MY_Model
{
    protected $table = 'formas_pagamento_fornecedores';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = 'id';
    protected $order_direction = 'ASC';
    protected $oncoprod;
    protected $oncoexo;

    function __construct()
    {
        parent::__construct();

        $this->oncoprod = explode(',', ONCOPROD);
        $this->oncoexo = explode(',', ONCOEXO);
    }

    public function gravar()
    {
        $this->db->trans_begin();

        $post = $this->input->post();
        $id_fornecedor      = $this->session->userdata("id_fornecedor");
        $id_tipo_venda      = $this->session->userdata("id_tipo_venda");
        $id_forma_pagamento = $post['id_forma_pagamento'];
        $elementos          = explode(',',$post['elementos']);

        $option = $this->input->post("opcao");

        if ($option == 'ESTADOS') {

            if (isset($post['replicarMatriz'])) {
                $fornecedores = [];

                if (isset($_SESSION['id_matriz'])) {
                    $fornecedores = $this->db->select('id')->where('id_matriz', $_SESSION['id_matriz'])->get('fornecedores')->result_array();
                }

                if (!empty($fornecedores)) {
                    foreach ($fornecedores as $fornecedor) {
                        $f = $fornecedor['id'];

                        $dataAtualizacao = [];
                        $dataNovo = [];

                        foreach ($elementos as $key => $value) {

                            $id = $this->verifyIfExists($f, $value, 'ESTADOS');

                            if ($id) {

                                $dataAtualizacao[] = [
                                    'id' => $id,
                                    'id_fornecedor' => $f,
                                    'id_estado' => $value,
                                    'id_tipo_venda' => $id_tipo_venda,
                                    'id_forma_pagamento' => $id_forma_pagamento,
                                    'data_atualizacao' => date("Y-m-d H:i:s")
                                ];
                            } else {

                                $dataNovo[] = [
                                    'id_fornecedor' => $f,
                                    'id_estado' => $value,
                                    'id_tipo_venda' => $id_tipo_venda,
                                    'id_forma_pagamento' => $id_forma_pagamento
                                ];
                            }
                        }

                        if (!empty($dataNovo)) {
                            $this->db->insert_batch($this->table, $dataNovo);
                        }
                        if (!empty($dataAtualizacao)) {
                            $this->db->update_batch($this->table, $dataAtualizacao, 'id');
                        }
                    }
                }
            } else {
                    
                $dataAtualizacao = [];
                $dataNovo = [];

                foreach ($elementos as $key => $value) {

                    $id = $this->verifyIfExists($id_fornecedor, $value, 'ESTADOS');

                    if ( $id ) {

                        $dataAtualizacao[] = [
                            'id'                 => $id,
                            'id_fornecedor'      => $id_fornecedor,
                            'id_estado'          => $value,
                            'id_tipo_venda'      => $id_tipo_venda,
                            'id_forma_pagamento' => $id_forma_pagamento,
                            'data_atualizacao'   => date("Y-m-d H:i:s")
                        ]; 
                    } else {

                        $dataNovo[] = [
                            'id_fornecedor'      => $id_fornecedor,
                            'id_estado'          => $value,
                            'id_tipo_venda'      => $id_tipo_venda,
                            'id_forma_pagamento' => $id_forma_pagamento
                        ];
                    }
                }

                if (!empty($dataNovo)) { $this->db->insert_batch($this->table, $dataNovo); }
                if (!empty($dataAtualizacao)) { $this->db->update_batch($this->table, $dataAtualizacao, 'id'); }
            }
        } else {

            if ( in_array($id_fornecedor, $this->oncoprod) && isset($post['replicarMatriz']) ) {

                foreach ($this->oncoprod as $f) {
                    
                    $dataAtualizacao = [];
                    $dataNovo = [];

                    foreach ($elementos as $key => $value) {

                        $id = $this->verifyIfExists($f, $value, 'ESTADOS');

                        if ( $id ) {

                            $dataAtualizacao[] = [
                                'id'                 => $id,
                                'id_fornecedor'      => $f,
                                'id_cliente'         => $value,
                                'id_tipo_venda'      => $id_tipo_venda,
                                'id_forma_pagamento' => $id_forma_pagamento,
                                'data_atualizacao'   => date("Y-m-d H:i:s")
                            ]; 
                        } else {

                            $dataNovo[] = [
                                'id_fornecedor'      => $f,
                                'id_cliente'         => $value,
                                'id_tipo_venda'      => $id_tipo_venda,
                                'id_forma_pagamento' => $id_forma_pagamento
                            ];
                        }
                    }

                    if (!empty($dataNovo)) {$this->db->insert_batch($this->table, $dataNovo); }
                    if (!empty($dataAtualizacao)) {$this->db->update_batch($this->table, $dataAtualizacao, 'id'); }
                }
            } elseif ( in_array($id_fornecedor, $this->oncoexo) && isset($post['replicarMatriz']) ) {
              
                foreach ($this->oncoexo as $f) {
                    
                    $dataAtualizacao = [];
                    $dataNovo = [];

                    foreach ($elementos as $key => $value) {

                        $id = $this->verifyIfExists($f, $value, 'ESTADOS');

                        if ( $id ) {

                            $dataAtualizacao[] = [
                                'id'                 => $id,
                                'id_fornecedor'      => $f,
                                'id_cliente'         => $value,
                                'id_tipo_venda'      => $id_tipo_venda,
                                'id_forma_pagamento' => $id_forma_pagamento,
                                'data_atualizacao'   => date("Y-m-d H:i:s")
                            ]; 
                        } else {

                            $dataNovo[] = [
                                'id_fornecedor'      => $f,
                                'id_cliente'         => $value,
                                'id_tipo_venda'      => $id_tipo_venda,
                                'id_forma_pagamento' => $id_forma_pagamento
                            ];
                        }
                    }

                    if (!empty($dataNovo)) {$this->db->insert_batch($this->table, $dataNovo); }
                    if (!empty($dataAtualizacao)) {$this->db->update_batch($this->table, $dataAtualizacao, 'id'); }
                }
            } else {

                $dataAtualizacao = [];
                $dataNovo = [];

                foreach ($elementos as $key => $value) {

                    $id =  $this->verifyIfExists($id_fornecedor, $value, 'CLIENTES');

                    if ( $id ) {
                        
                        $dataAtualizacao[] = [
                            'id' => $id,
                            'id_fornecedor'      => $id_fornecedor,
                            'id_cliente'         => $value,
                            'id_tipo_venda'      => $id_tipo_venda,
                            'id_forma_pagamento' => $id_forma_pagamento,
                            'data_atualizacao'   => date("Y-m-d H:i:s")
                        ];
                    } else {

                        $dataNovo[] = [
                            'id_fornecedor'      => $id_fornecedor,
                            'id_cliente'          => $value,
                            'id_tipo_venda'      => $id_tipo_venda,
                            'id_forma_pagamento' => $id_forma_pagamento
                        ];
                    }
                }

                if (!empty($dataNovo)) 
                    $this->db->insert_batch($this->table, $dataNovo);

                if (!empty($dataAtualizacao)) 
                    $this->db->update_batch($this->table, $dataAtualizacao, 'id');
            }
        }

        if ($this->db->trans_status() === false) {

            $this->db->trans_rollback();

            return false;
        } else {

            $this->db->trans_commit();

            return true;
        }
    }

    public function excluir($id)
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");

        $this->db->where('id', $id);
        $this->db->where('id_fornecedor', $id_fornecedor);
        return $this->db->delete($this->table);
    }

    public function getById($id)
    {

        $this->db->select("f.id, f.id_forma_pagamento, CONCAT(e.uf, ' - ', e.descricao) AS estado, CONCAT(c.cnpj, ' - ', c.razao_social) AS cliente");
        $this->db->from("{$this->table} AS f");
        $this->db->join('estados e', 'f.id_estado = e.id', 'LEFT');
        $this->db->join('compradores c', 'f.id_cliente = c.id', 'LEFT');
        $this->db->where('f.id', $id);
        $this->db->limit(1);

        return $this->db->get()->row_array();
    }

    public function getList($option)
    {
        if ($option === 'ESTADOS') {

            $this->db->select("id, CONCAT(uf, ' - ', descricao) as descricao");
            $this->db->from('estados');
            $this->db->order_by('descricao', 'ASC');
        } else {

            $this->db->select("id, CONCAT(cnpj, ' - ', razao_social) as descricao");
            $this->db->from('compradores');
            $this->db->order_by('cnpj', 'ASC');
        }

        return $this->db->get()->result_array();
    }

    private function verifyIfExists($id_fornecedor, $param, $option)
    {
        $this->db->select('*');
        $this->db->from($this->table);

        if ($option == 'ESTADOS') {
            $this->db->where('id_estado', $param);
        } else {
            $this->db->where('id_cliente', $param);
        }

        $this->db->where("id_fornecedor", $id_fornecedor);


        $this->db->limit(1);
        return $this->db->get()->row_array()['id'];
    }
}
