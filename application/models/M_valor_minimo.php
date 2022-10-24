<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_valor_minimo extends MY_Model
{
    protected $table = 'valor_minimo_cliente';
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

    public function listar_valor_minimo_estado()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->select("vm.id, vm.valor_minimo, e.uf,e.descricao, vm.id_estado FROM valor_minimo_cliente vm INNER JOIN  estados e ON e.id = vm.id_estado ", false);
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('id_cliente', NULL);
        $consulta = $this->db->get();

        if ($consulta->num_rows() > 0) {
            return $consulta;
        } else {
            return false;
        }
    }

    public function listar_valor_minimo_cnpj()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->select(" vm.valor_minimo, vm.id, u.id_dados_usuario, u.id_endereco, u.tipo_usuario, u.cnpj, u.ativo , du.razao_social, du.integracao FROM valor_minimo_cliente vm INNER JOIN  usuarios u ON u.id=vm.id_cliente INNER JOIN dados_usuarios du ON du.id=u.id_dados_usuario INNER JOIN enderecos e ON e.id=u.id_endereco ", false);
        $this->db->where('vm.id_fornecedor', $id_fornecedor);
        $this->db->where('vm.id_estado', NULL);
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0) {
            return $consulta;
        } else {
            return false;
        }
    }

    public function gravar()
    {

        $post = $this->input->post();

        $this->db->trans_begin();

        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $id_tipo_venda = $this->session->userdata("id_tipo_venda");
        $valor_minimo = dbNumberFormat($this->input->post('valor_minimo'));
        $desconto = dbNumberFormat($this->input->post('desconto_padrao'));
        $elementos = explode(',', $this->input->post('elementos'));

        $option = $this->input->post('opcao');

        if ($option === 'ESTADOS') {
           
            # Se for ONCOPROD replica para todos os seus fornecedores
            if ( in_array($id_fornecedor, $this->oncoprod) && isset($post['replicarMatriz']) ) {

                foreach ($this->oncoprod as $f) {

                    $dataAtualizacao = [];
                    $dataNovo = [];
                    
                    foreach ($elementos as $key => $id_estado) {

                        $id = $this->verifyIfExists($f, $id_estado, 'ESTADOS');

                        if ( $id ) {

                            $dataAtualizacao[] = [
                                'id' => $id,
                                'id_fornecedor' => $f,
                                'id_estado'     => $id_estado,
                                'id_tipo_venda' => $id_tipo_venda,
                                'valor_minimo'  => $valor_minimo,
                                'desconto_padrao'  => $desconto,
                                'data_atualizacao' => date("Y-m-d H:i:s")
                            ];
                        } else {

                            $dataNovo[] = [
                                'id_fornecedor' => $f,
                                'id_estado'     => $id_estado,
                                'id_tipo_venda' => $id_tipo_venda,
                                'valor_minimo'  => $valor_minimo,
                                'desconto_padrao' => $desconto,
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
                    
                    foreach ($elementos as $key => $id_estado) {

                        $id = $this->verifyIfExists($f, $id_estado, 'ESTADOS');

                        if ( $id ) {

                            $dataAtualizacao[] = [
                                'id' => $id,
                                'id_fornecedor' => $f,
                                'id_estado'     => $id_estado,
                                'id_tipo_venda' => $id_tipo_venda,
                                'valor_minimo'  => $valor_minimo,
                                'desconto_padrao'  => $desconto,
                                'data_atualizacao' => date("Y-m-d H:i:s")
                            ];
                        } else {

                            $dataNovo[] = [
                                'id_fornecedor' => $f,
                                'id_estado'     => $id_estado,
                                'id_tipo_venda' => $id_tipo_venda,
                                'valor_minimo'  => $valor_minimo,
                                'desconto_padrao' => $desconto,
                            ];
                        }
                    }

                    if (!empty($dataNovo)) {$this->db->insert_batch($this->table, $dataNovo); }
                    if (!empty($dataAtualizacao)) {$this->db->update_batch($this->table, $dataAtualizacao, 'id'); }
                }
            } else {

                $dataAtualizacao = [];
                $dataNovo = [];

                foreach ($elementos as $key => $id_estado) {

                    $id = $this->verifyIfExists($id_fornecedor, $id_estado, 'ESTADOS');

                    if ( $id ) {

                        $dataAtualizacao[] = [
                            'id' => $id,
                            'id_fornecedor' => $id_fornecedor,
                            'id_estado'     => $id_estado,
                            'id_tipo_venda' => $id_tipo_venda,
                            'valor_minimo'  => $valor_minimo,
                            'desconto_padrao'  => $desconto,
                            'data_atualizacao' => date("Y-m-d H:i:s")
                        ];
                    } else {

                        $dataNovo[] = [
                            'id_fornecedor' => $id_fornecedor,
                            'id_estado'     => $id_estado,
                            'id_tipo_venda' => $id_tipo_venda,
                            'valor_minimo'  => $valor_minimo,
                            'desconto_padrao'  => $desconto,
                        ];
                    }
                }

                if (!empty($dataNovo)) { $this->db->insert_batch($this->table, $dataNovo); }
                if (!empty($dataAtualizacao)) { $this->db->update_batch($this->table, $dataAtualizacao, 'id'); }
            }
        } else {

            # Se for ONCOPROD replica para todos os seus fornecedores
            if ( in_array($id_fornecedor, $this->oncoprod) && isset($post['replicarMatriz']) ) {

                foreach ($this->oncoprod as $f) {

                    $dataAtualizacao = [];
                    $dataNovo = [];
                    
                    foreach ($elementos as $key => $id_cliente) {

                        $id = $this->verifyIfExists($f, $id_cliente, 'ESTADOS');

                        if ( $id ) {

                            $dataAtualizacao[] = [
                                'id' => $id,
                                'id_fornecedor' => $f,
                                'id_estado'     => $id_cliente,
                                'id_tipo_venda' => $id_tipo_venda,
                                'valor_minimo'  => $valor_minimo,
                                'desconto_padrao'  => $desconto,
                                'data_atualizacao' => date("Y-m-d H:i:s")
                            ];
                        } else {

                            $dataNovo[] = [
                                'id_fornecedor' => $f,
                                'id_estado'     => $id_cliente,
                                'id_tipo_venda' => $id_tipo_venda,
                                'valor_minimo'  => $valor_minimo,
                                'desconto_padrao'  => $desconto,
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
                    
                    foreach ($elementos as $key => $id_cliente) {

                        $id = $this->verifyIfExists($f, $id_cliente, 'ESTADOS');

                        if ( $id ) {

                            $dataAtualizacao[] = [
                                'id' => $id,
                                'id_fornecedor' => $f,
                                'id_estado'     => $id_cliente,
                                'id_tipo_venda' => $id_tipo_venda,
                                'valor_minimo'  => $valor_minimo,
                                'desconto_padrao'  => $desconto,
                                'data_atualizacao' => date("Y-m-d H:i:s")
                            ];
                        } else {

                            $dataNovo[] = [
                                'id_fornecedor' => $f,
                                'id_estado'     => $id_cliente,
                                'id_tipo_venda' => $id_tipo_venda,
                                'valor_minimo'  => $valor_minimo,
                                'desconto_padrao' => $desconto,
                            ];
                        }
                    }

                    if (!empty($dataNovo)) {$this->db->insert_batch($this->table, $dataNovo); }
                    if (!empty($dataAtualizacao)) {$this->db->update_batch($this->table, $dataAtualizacao, 'id'); }
                }
            } else {

                $dataAtualizacao = [];
                $dataNovo = [];

                foreach ($elementos as $key => $id_cliente) {

                    $id = $this->verifyIfExists($id_fornecedor, $id_cliente, 'CLIENTES');

                    if ( $id ) {

                        $dataAtualizacao[] = [
                            'id' => $id,
                            'id_fornecedor' => $id_fornecedor,
                            'id_cliente'    => $id_cliente,
                            'id_tipo_venda' => $id_tipo_venda,
                            'valor_minimo'  => $valor_minimo,
                            'desconto_padrao'  => $desconto,
                            'data_atualizacao' => date("Y-m-d H:i:s")
                        ];

                    } else {

                        $dataNovo[] = [
                            'id_fornecedor' => $id_fornecedor,
                            'id_cliente'    => $id_cliente,
                            'id_tipo_venda' => $id_tipo_venda,
                            'valor_minimo'  => $valor_minimo,
                            'desconto_padrao' => $desconto,
                        ];

                    }
                }

                if (!empty($dataNovo)) { $this->db->insert_batch($this->table, $dataNovo); }
                if (!empty($dataAtualizacao)) { $this->db->update_batch($this->table, $dataAtualizacao, 'id'); }
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
        $this->db->select("v.id, v.desconto_padrao, v.valor_minimo, CONCAT(e.uf, ' - ', e.descricao) AS estado, CONCAT(c.cnpj, ' - ', c.razao_social) AS cliente");
        $this->db->from("{$this->table} AS v");
        $this->db->join('estados e', 'v.id_estado = e.id', 'LEFT');
        $this->db->join('usuarios u', 'v.id_cliente = u.id', 'LEFT');
        $this->db->join('compradores c', 'u.id_comprador = c.id', 'LEFT');
        $this->db->where('v.id', $id);
        $this->db->where('v.id_fornecedor', $this->session->userdata('id_fornecedor'));
        $this->db->limit(1);

        $query = $this->db->get();
        return $query->row_array();
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
