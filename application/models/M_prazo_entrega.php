<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_prazo_entrega extends MY_Model
{
    protected $table = 'prazos_entrega';
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

    public function listar_prazo_entrega_estado()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->select("pe.id, pe.prazo, e.uf, e.descricao, pe.id_estado FROM prazos_entrega pe INNER JOIN estados e ON e.id = pe.id_estado ", false);
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('id_cliente', NULL);
        $consulta = $this->db->get();

        if ($consulta->num_rows() > 0) {
            return $consulta;
        } else {
            return false;
        }
    }

    public function listar_prazo_entrega_cnpj()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->select(" pe.prazo, pe.id, u.id_dados_usuario, u.id_endereco, u.tipo_usuario, u.cnpj, u.ativo, du.razao_social, du.integracao FROM prazos_entrega pe INNER JOIN  usuarios u ON u.id=pe.id_cliente INNER JOIN dados_usuarios du ON du.id=u.id_dados_usuario INNER JOIN enderecos e ON e.id=u.id_endereco ", false);
        $this->db->where('pe.id_fornecedor', $id_fornecedor);
        $this->db->where('pe.id_estado', NULL);
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0) {
            return $consulta;
        } else {
            return false;
        }
    }

    public function gravar()
    {
        $this->db->trans_begin();

        $post = $this->input->post();

        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $id_tipo_venda = $this->session->userdata("id_tipo_venda"); //1-markplace 2- integranexo 3- markplace/integranexo
        $prazo_entrega = $post['prazo'];
        $elementos = explode(',', $this->input->post('elementos'));

        $option = $this->input->post("opcao");

        if ($option == 'ESTADOS') {

            # replica para todos os seus fornecedores
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

                        foreach ($elementos as $key => $id_estado) {

                            $id = $this->verifyIfExists($f, $id_estado, 'ESTADOS');

                            if ($id) {

                                $dataAtualizacao[] = [
                                    'id' => $id,
                                    'id_fornecedor' => $f,
                                    'id_estado' => $id_estado,
                                    'id_tipo_venda' => $id_tipo_venda,
                                    'prazo' => $prazo_entrega,
                                    'data_atualizacao' => date("Y-m-d H:i:s")
                                ];
                            } else {

                                $dataNovo[] = [
                                    'id_fornecedor' => $f,
                                    'id_estado' => $id_estado,
                                    'id_tipo_venda' => $id_tipo_venda,
                                    'prazo' => $prazo_entrega
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

                foreach ($elementos as $key => $id_estado) {

                    $id = $this->verifyIfExists($id_fornecedor, $id_estado, 'ESTADOS');

                    if ($id) {

                        $dataAtualizacao[] = [
                            'id' => $id,
                            'id_fornecedor' => $id_fornecedor,
                            'id_estado' => $id_estado,
                            'id_tipo_venda' => $id_tipo_venda,
                            'prazo' => $prazo_entrega,
                            'data_atualizacao' => date("Y-m-d H:i:s")
                        ];
                    } else {

                        $dataNovo[] = [
                            'id_fornecedor' => $id_fornecedor,
                            'id_estado' => $id_estado,
                            'id_tipo_venda' => $id_tipo_venda,
                            'prazo' => $prazo_entrega
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
        } else {

            # Se for ONCOPROD replica para todos os seus fornecedores
            if (in_array($id_fornecedor, $this->oncoprod) && isset($post['replicarMatriz'])) {

                foreach ($this->oncoprod as $f) {

                    $dataAtualizacao = [];
                    $dataNovo = [];

                    foreach ($elementos as $key => $id_cliente) {

                        $id = $this->verifyIfExists($f, $id_cliente, 'ESTADOS');

                        if ($id) {

                            $dataAtualizacao[] = [
                                'id' => $id,
                                'id_fornecedor' => $f,
                                'id_estado' => $id_cliente,
                                'id_tipo_venda' => $id_tipo_venda,
                                'prazo' => $prazo_entrega,
                                'data_atualizacao' => date("Y-m-d H:i:s")
                            ];
                        } else {

                            $dataNovo[] = [
                                'id_fornecedor' => $f,
                                'id_estado' => $id_cliente,
                                'id_tipo_venda' => $id_tipo_venda,
                                'prazo' => $prazo_entrega
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
            } elseif (in_array($id_fornecedor, $this->oncoexo) && isset($post['replicarMatriz'])) {

                foreach ($this->oncoexo as $f) {

                    $dataAtualizacao = [];
                    $dataNovo = [];

                    foreach ($elementos as $key => $id_cliente) {

                        $id = $this->verifyIfExists($f, $id_cliente, 'ESTADOS');

                        if ($id) {

                            $dataAtualizacao[] = [
                                'id' => $id,
                                'id_fornecedor' => $f,
                                'id_estado' => $id_cliente,
                                'id_tipo_venda' => $id_tipo_venda,
                                'prazo' => $prazo_entrega,
                                'data_atualizacao' => date("Y-m-d H:i:s")
                            ];
                        } else {

                            $dataNovo[] = [
                                'id_fornecedor' => $f,
                                'id_estado' => $id_cliente,
                                'id_tipo_venda' => $id_tipo_venda,
                                'prazo' => $prazo_entrega
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
            } else {

                $dataAtualizacao = [];
                $dataNovo = [];

                foreach ($elementos as $key => $id_cliente) {

                    $id = $this->verifyIfExists($id_fornecedor, $id_cliente, 'CLIENTES');

                    if ($id) {

                        $dataAtualizacao[] = [
                            'id' => $id,
                            'id_fornecedor' => $id_fornecedor,
                            'id_cliente' => $id_cliente,
                            'id_tipo_venda' => $id_tipo_venda,
                            'prazo' => $prazo_entrega,
                            'data_atualizacao' => date("Y-m-d H:i:s")
                        ];
                    } else {

                        $dataNovo[] = [
                            'id_fornecedor' => $id_fornecedor,
                            'id_cliente' => $id_cliente,
                            'id_tipo_venda' => $id_tipo_venda,
                            'prazo' => $prazo_entrega
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

        if ($this->db->trans_status() === FALSE) {

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

        return $this->db->delete('prazos_entrega');
    }

    public function getById($id)
    {
        $this->db->select("p.id, p.prazo, CONCAT(e.uf, ' - ', e.descricao) AS estado, CONCAT(c.cnpj, ' - ', c.razao_social) AS cliente");
        $this->db->from("{$this->table} AS p");
        $this->db->join('estados e', 'p.id_estado = e.id', 'LEFT');
        $this->db->join('usuarios u', 'p.id_cliente = u.id', 'LEFT');
        $this->db->join('compradores c', 'p.id_cliente = c.id', 'LEFT');

        $this->db->where('p.id', $id);
        $this->db->where('p.id_fornecedor', $this->session->userdata('id_fornecedor'));
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
        $query = $this->db->get();

        return $query->row_array()['id'];
    }
}
