<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Integracoes extends CI_Controller
{
    private $route, $views, $DB_COTACAO;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('admin/relatorios/integracoes');
        $this->views = "admin/relatorios/integracoes";

        $this->DB_COTACAO = $this->load->database('sintese', TRUE);
    }

    /**
     * Exibe a view admin/relatorios/main.php
     *
     * @return  view
     */
    public function index()
    {
        $page_title = 'Relatórios de Integrações Pharmanexo';

        $data['header'] = $this->template->header([ 'title' => $page_title ]);
        $data['heading'] = $this->template->heading([ 'page_title' => $page_title ]);
        $data['scripts'] = $this->template->scripts();

        $data['relatorioCotacaoAutomatico'] = $this->relatorioCotacao(2);
        $data['relatorioCotacaoManual'] = $this->relatorioCotacao(1);
        $data['relatorioCotacaoSintese'] = $this->relatorioCotacaoSintese();
        $data['relatorioEstoque'] = $this->relatorioEstoque();
        $data['relatorioPreco'] = $this->relatorioPreco();

        $this->load->view("{$this->views}/main", $data);
    }

    public function relatorioCotacao($nivel)
    {
        $query = "SELECT  
                prods.id_fornecedor,
                f.nome_fantasia,
                MAX(prods.data_criacao) data_criacao
            FROM cotacoes_produtos prods
            JOIN fornecedores f
                ON f.id = prods.id_fornecedor
            WHERE prods.nivel = {$nivel}
            GROUP BY prods.id_fornecedor, f.nome_fantasia
            ORDER BY data_criacao DESC
        ";

        return $this->db->query($query)->result_array();
    }

    public function relatorioCotacaoSintese()
    {
        $this->DB_COTACAO->select("MAX(c.data_criacao) data_criacao, c.id_fornecedor, f.nome_fantasia");
        $this->DB_COTACAO->from('cotacoes c');
        $this->DB_COTACAO->join('pharmanexo.fornecedores f', 'f.id = c.id_fornecedor');
        $this->DB_COTACAO->group_by("id_fornecedor");

        return $this->DB_COTACAO->get()->result_array();
    }

    public function relatorioEstoque()
    {
        return $this->db->select('id, nome_fantasia, cnpj, termino_atualizacao_estoque')
        ->where('sintese', 1)
        ->order_by("termino_atualizacao_estoque DESC")
        ->get('fornecedores')
        ->result_array();
    }

    public function relatorioPreco()
    {
        return $this->db->select("f.nome_fantasia")
        ->select("(SELECT
                pp.data_criacao
        FROM pharmanexo.produtos_preco_max pp
        WHERE pp.id_fornecedor = f.id
        ORDER BY pp.data_criacao DESC
        LIMIT 1) data_criacao")
        ->from('fornecedores f')
        ->where('f.sintese', 1)
        ->order_by("data_criacao ASC")
        ->get()
        ->result_array();
    }
}