<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_controle_cotacoes extends MY_Model
{
    protected $table = 'controle_cotacoes';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = 'id';
    protected $order_direction = 'ASC';

    public function __construct()
    {
        parent::__construct();
    }

    public function gravar()
    {
        $this->db->trans_begin();

        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $id_tipo_venda = $this->session->userdata("id_tipo_venda");
        $regra_venda = $this->input->post('regra_venda');
        $elementos = explode(',', $this->input->post('elementos'));
        $int = $this->input->post('integrador');
        $integradores = (isset($int) && !empty($int)) ? $int : [1];


        $option = $this->input->post("opcao");

        if ($option === 'ESTADOS') {

            $dataNovo = [];
            $dataAtualizacao = [];

            foreach ($integradores as $integrador) {
                foreach ($elementos as $key => $value) {
                    $id = $this->verifyIfExists($value, 'ESTADOS', $integrador);

                    if ($id) {

                        $dataAtualizacao[] = [
                            'id' => $id,
                            'id_fornecedor' => $id_fornecedor,
                            'id_estado' => $value,
                            'id_tipo_venda' => $id_tipo_venda,
                            'regra_venda' => $regra_venda,
                            'integrador' => $integrador
                        ];
                    } else {

                        $dataNovo[] = [
                            'id_fornecedor' => $id_fornecedor,
                            'id_estado' => $value,
                            'id_tipo_venda' => $id_tipo_venda,
                            'regra_venda' => $regra_venda,
                            'integrador' => $integrador
                        ];
                    }
                }
            }

            if (!empty($dataNovo))
                $this->db->insert_batch($this->table, $dataNovo);

            if (!empty($dataAtualizacao))
                $this->db->update_batch($this->table, $dataAtualizacao, 'id');
        } else {

            $dataNovo = [];
            $dataAtualizacao = [];

            foreach ($integradores as $integrador) {
                foreach ($elementos as $key => $value) {

                    $id = $this->verifyIfExists($value, 'CLIENTES', $integrador);

                    if ($id) {

                        $dataAtualizacao[] = [
                            'id' => $id,
                            'id_fornecedor' => $id_fornecedor,
                            'id_cliente' => $value,
                            'id_tipo_venda' => $id_tipo_venda,
                            'regra_venda' => $regra_venda,
                            'integrador' => $integrador
                        ];

                    } else {

                        $dataNovo[] = [
                            'id_fornecedor' => $id_fornecedor,
                            'id_cliente' => $value,
                            'id_tipo_venda' => $id_tipo_venda,
                            'regra_venda' => $regra_venda,
                            'integrador' => $integrador
                        ];
                    }
                }
            }

            if (!empty($dataNovo))
                $this->db->insert_batch($this->table, $dataNovo);

            if (!empty($dataAtualizacao))
                $this->db->update_batch($this->table, $dataAtualizacao, 'id');
        }

        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();
            return false;
        } else {

            $this->db->trans_commit();
            return true;
        }
    }

    public function getById($id)
    {
        $this->db->select('c.id');
        $this->db->select('c.regra_venda');
        $this->db->select("CONCAT(e.uf, ' - ', e.descricao) AS estado");
        $this->db->select("CONCAT(u.cnpj, ' - ', u.razao_social) AS cliente");
        $this->db->from("{$this->table} AS c");
        $this->db->join('estados e', 'c.id_estado = e.id', 'LEFT');
        $this->db->join('compradores u', 'c.id_cliente = u.id', 'LEFT');
        $this->db->where('c.id', $id);
        $this->db->limit(1);

        return $this->db->get()->row_array();
    }

    public function getList($option)
    {
        if ($option === 'ESTADOS') {
            $this->db->select("e.id, CONCAT(e.uf, ' - ', e.descricao) as descricao");
            $this->db->from('estados e');
            $this->db->order_by('e.descricao', 'ASC');
        } else {
            $this->db->select("c.id, CONCAT(c.cnpj, ' - ', c.razao_social) as descricao");
            $this->db->from('compradores c');
            $this->db->order_by('c.cnpj', 'ASC');
        }

        return $this->db->get()->result_array();
    }

    private function verifyIfExists($param, $option, $integrador = null)
    {
        $this->db->select('id');
        $this->db->from($this->table);

        if ($option == 'ESTADOS') {
            $this->db->where('id_estado', $param);
        } else {
            $this->db->where('id_cliente', $param);
        }

        $this->db->where("id_fornecedor", $this->session->id_fornecedor);
        $this->db->where("integrador", $integrador);

        $this->db->limit(1);
        $query = $this->db->get();

        return $query->row_array()['id'];
    }
}

/* End of file: M_controle_cotacoes.php */
