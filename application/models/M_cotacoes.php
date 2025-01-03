<?php
defined('BASEPATH') or exit('No direct script access allowed');

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

class M_cotacoes extends MY_Model
{
    protected $table = 'cotacoes_produtos';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = '';
    protected $order_direction = 'ASC';
    protected $DB_COTACAO;

    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Obtem as cotações sintese e bionexo em aberto para exibir em datatable
     *
     * @param - String sigla do estado
     * @return  array
     */
    public function cotacoesEmAberto($uf = null)
    {

        $filtros = ($this->session->has_userdata('filtros')) ? $this->session->filtros : null;


        if (isset($_SESSION['id_matriz'])) {
            $id_fornecedor = [];

            $fornecedores = $this->db
                ->select('id')
                ->where('id_matriz', $_SESSION['id_matriz'])
                ->get('fornecedores')->result_array();
            if (!empty($fornecedores)) {
                foreach ($fornecedores as $fornecedor) {
                    $id_fornecedor[] = $fornecedor['id'];
                }
            }

            if (!empty($id_fornecedor)) {
                $id_fornecedor = implode(',', $id_fornecedor);
                $where = "cot.id_fornecedor in ({$id_fornecedor}) AND cot.oculto != 1 AND ";
            } else {
                $where = "cot.id_fornecedor = {$this->session->id_fornecedor} AND cot.oculto != 1 AND ";
            }

        } else {
            $where = "cot.id_fornecedor = {$this->session->id_fornecedor} AND cot.oculto != 1 AND ";
        }

        if (isset($uf)) {
            $where .= "cot.uf_cotacao= '{$uf}' AND ";
        }


        if (!empty($filtros)) {

            if (isset($filtros['integrador']) && !empty($filtros['integrador'])) {
                $where .= " cot.integrador = '{$filtros['integrador']}' AND ";
            }

            if (isset($filtros['id_cliente']) && !empty($filtros['id_cliente'])) {
                $where .= " cot.id_cliente = '{$filtros['id_cliente']}' AND ";
            }

            if (isset($filtros['cd_cotacao']) && !empty($filtros['cd_cotacao'])) {
                $where .= " cot.cd_cotacao = '{$filtros['cd_cotacao']}' AND ";
            }
        }

        $where = rtrim($where, "AND ");

        $datatables = $this->datatable->exec(
            $this->input->post(),
            'vw_cotacoes_integrador cot',
            [
                ['db' => 'cot.id', 'dt' => 'id'],
                ['db' => 'cot.integrador', 'dt' => 'integrador'],
                ['db' => 'cot.cd_cotacao', 'dt' => 'cd_cotacao'],
                ['db' => 'cot.uf_cotacao', 'dt' => 'uf_cotacao'],
                ['db' => 'cot.id_cliente', 'dt' => 'id_cliente'],
                ['db' => 'cot.total_itens', 'dt' => 'total_itens'],
                ['db' => 'cot.oferta', 'dt' => 'oferta'],
                ['db' => 'cot.dt_fim_cotacao', 'dt' => 'dt_fim_cotacao'],
                ['db' => 'c.cnpj', 'dt' => 'cnpj'],
                ['db' => 'cot.revisada', 'dt' => 'revisada'],
            //    ['db' => 'f.nome_fantasia', 'dt' => 'loja'],
                ['db' => 'cot.ds_cotacao', 'dt' => 'ds_cotacao', 'formatter' => function ($value, $row) {
                    return "<small>{$value}</small>";
                }],
                ['db' => 'c.razao_social', 'dt' => 'comprador', 'formatter' => function ($value, $row) {
                   // $value = utf8_decode(utf8_encode(substr($value, 0, 50)));
                    return "<small data-toggle='tooltip' title='{$row['ds_cotacao']}'>{$row['cnpj']} - {$value}</small>";
                }],
                ['db' => 'cot.dt_fim_cotacao', 'dt' => 'datafim', 'formatter' => function ($value, $row) {

                    return date('d/m/Y H:i', strtotime($value));
                }],
                [
                    'db' => "(SELECT COUNT(0) FROM pharmanexo.cotacoes_produtos cp WHERE cp.cd_cotacao = cot.cd_cotacao 
                        AND cp.id_fornecedor = cot.id_fornecedor AND cp.submetido = 1)",
                    'dt' => 'respondido'
                ],
                [
                    'db' => "(SELECT COUNT(0) FROM pharmanexo.cotacoes_produtos cp 
                        WHERE cp.cd_cotacao = cot.cd_cotacao 
                            AND cp.id_fornecedor = cot.id_fornecedor 
                            AND cp.submetido = 1
                            AND cp.controle = 1
                            AND cp.nivel = 2)",
                    'dt' => 'total_oferta_aut'
                ],
                [
                    'db' => "(SELECT COUNT(0) FROM pharmanexo.cotacoes_produtos cp 
                        WHERE cp.cd_cotacao = cot.cd_cotacao 
                            AND cp.id_fornecedor = cot.id_fornecedor 
                            AND cp.submetido = 1
                            AND cp.controle = 1
                            AND cp.nivel = 1)",
                    'dt' => 'total_oferta_manual'
                ],
            ],
            [
                ['compradores c', 'c.id = cot.id_cliente ', 'left'],
                ['fornecedores f', 'f.id = cot.id_fornecedor ', 'left'],
            ],
            "{$where}",
            "cot.cd_cotacao, cot.id_cliente"
        );

        return $datatables;
    }

    /**
     * Obtem a quantidade de cotações sintese em aberto
     *
     * @param - INT ID do fornecedor
     * @return  int
     */
    public function getTotalOpenQuotes($id_fornecedor)
    {
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where("oculto != 1");
        $this->db->from('vw_cotacoes_integrador');

        return $this->db->count_all_results();
    }

    /**
     * Obtem a data do ultimo registro de cotação
     *
     * @param - INT ID do fornecedor
     * @return  date
     */
    public function ultimoRegistroCotacao($id_fornecedor)
    {

        if (in_array($id_fornecedor, explode(',', ONCOPROD))) {

            $this->DB_COTACAO->select('*');
            $this->DB_COTACAO->where("id_fornecedor in (" . ONCOPROD . ")");
            $this->DB_COTACAO->order_by('data_criacao DESC');
            $this->DB_COTACAO->limit(1);
            $ultimoRegistro = $this->DB_COTACAO->get('cotacoes')->row_array()['data_criacao'];
        } elseif (in_array($id_fornecedor, explode(',', ONCOEXO))) {

            $this->DB_COTACAO->select('*');
            $this->DB_COTACAO->where("id_fornecedor in (" . ONCOEXO . ")");
            $this->DB_COTACAO->order_by('data_criacao DESC');
            $this->DB_COTACAO->limit(1);
            $ultimoRegistro = $this->DB_COTACAO->get('cotacoes')->row_array()['data_criacao'];
        } else {

            $this->DB_COTACAO->select('*');
            $this->DB_COTACAO->where('id_fornecedor', $id_fornecedor);
            $this->DB_COTACAO->order_by('data_criacao DESC');
            $this->DB_COTACAO->limit(1);
            $ultimoRegistro = $this->DB_COTACAO->get('cotacoes')->row_array()['data_criacao'];
        }

        return $ultimoRegistro;
    }

    public function verificaResposta($cd_cotacao, $id_fornecedor)
    {
        $this->db->where('cd_cotacao', $cd_cotacao);
        $this->db->where('id_fornecedor', $id_fornecedor);
        $query = $this->db->get('cotacoes_produtos');

        return $query->num_rows();
    }

    public function verificaRecusa($cd_cotacao, $id_fornecedor, $integrador)
    {
        $this->db->where('cd_cotacao', $cd_cotacao);
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('integrador', $integrador);
        $query = $this->db->get('vw_cotacoes_recusas');

        return $query->row_array();
    }

}

/* End of file: M_cotacoes.php */
