<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_mapa_logistico extends MY_Model
{
    protected $table = 'mapa_logistico';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = 'id';
    protected $order_direction = 'ASC';

    public function __construct()
    {
        parent::__construct();

        $this->load->model('m_estados', 'estado');
        $this->load->model('m_estoque', 'estoque');
        $this->load->model('m_usuarios', 'usuario');
    }

    public function gravar()
    {
        $this->db->trans_begin();

        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $id_tipo_venda = $this->session->userdata("id_tipo_venda");

        $selected = $this->input->post('selectElements');

        $produtos = explode(',', $this->input->post('produtos'));
        $selecionados = explode(',', $this->input->post('opcoes'));

        $key = ($selected === 'ESTADOS') ? 'id_estado' : 'id_cliente';

        $dataInsert = [];
        $dataUpdate = [];

        foreach ($produtos as $p) {
            foreach ($selecionados as $s) {
                $dados = [
                    'codigo'    => $p,
                    'id_fornecedor' => $id_fornecedor,
                    $key            => $s
                ];

                $id = $this->verificarSeExiste($dados, $key);

                if ($id) {
                    $dados['id'] = $id;
                    array_push($dataUpdate, $dados);
                } else {
                    array_push($dataInsert, $dados);
                }
            }
        }

        if (!empty($dataUpdate)) {
            $this->db->update_batch($this->table, $dataUpdate, 'id');
        }

        if (!empty($dataInsert)) {
            $this->db->insert_batch($this->table, $dataInsert);
        }

        if ($this->db->trans_status() === FALSE) {
            var_dump($this->db->error());
            exit();
            $this->db->trans_rollback();



            return FALSE;
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
        return $this->db->delete('mapa_logistico');
    }

    public function getList()
    {
        $this->db->select("c.id, CONCAT(c.cnpj, ' - ', c.razao_social) AS cliente");
        $this->db->from('compradores AS c');
        $this->db->order_by('c.razao_social', 'ASC');

        return $this->db->get()->result_array();
    }

    /**
     * Verificar se já existe registro na tabela
     *
     * @param - array
     * @param - string tipo
     * @return - int id
     */
    public function verificarSeExiste($data, $tipo)
    {
        $this->db->select('*');
        $this->db->from('mapa_logistico');

        if ($tipo === 'id_estado') {
            $this->db->where('id_estado', $data['id_estado']);
        } else {
            $this->db->where('id_cliente', $data['id_cliente']);
        }

        $this->db->where('codigo', $data['codigo']);
        $this->db->where('id_fornecedor', $this->session->userdata('id_fornecedor'));
        $this->db->limit(1);

        $query = $this->db->get();
        return $query->row_array()['id'];
    }
}

/* End of file: M_mapa_logistico.php */
