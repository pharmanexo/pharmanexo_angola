<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mix extends CI_Controller
{
    private $route, $views, $DB_COTACAO, $MIX;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('admin/relatorios/mix');
        $this->views = "admin/relatorios/mix";

        $this->DB_COTACAO = $this->load->database('sintese', TRUE);
        $this->MIX = $this->load->database('mix', TRUE);
    }

    /**
     * Exibe a view admin/relatorios/main.php
     *
     * @return  view
     */
    public function index()
    {
        $page_title = 'Mix';

        $data['header'] = $this->template->header([ 'title' => $page_title ]);
        $data['heading'] = $this->template->heading([ 'page_title' => $page_title ]);
        $data['scripts'] = $this->template->scripts();

        $data['relatorioMixPorComprador'] = $this->mixPorComprador();
        $data['relatorioAcionamentos'] = $this->acionamentosMix();

        $data['relatorio1'] = $this->cotacoesSantaMarcelina();
        $data['relatorio2'] = $this->cotacoesSantana();
        $data['relatorio3'] = $this->cotacoesLondrina();

        

        $this->load->view("{$this->views}/main", $data);
    }

    public function mixPorComprador()
    {
        $query = "SELECT  
                prods.id_cliente,
                c.razao_social,
                prods.cd_cotacao,
                MAX(prods.data_criacao) data_criacao
            FROM pharmanexo.cotacoes_produtos prods
            JOIN pharmanexo.compradores c
                ON c.id = prods.id_cliente
            WHERE prods.nivel = 3
            GROUP BY prods.id_cliente, c.razao_social
            ORDER BY data_criacao DESC
        ";

        return $this->db->query($query)->result_array();
    }

    public function acionamentosMix()
    {
        $this->MIX->select("cotacoes.cd_cotacao, cotacoes.data_criacao, c.razao_social");
        $this->MIX->where("DATE(cotacoes.data_criacao) = CURDATE()");
        $this->MIX->where("cotacoes.cd_cotacao NOT IN('COT8215-1987')");
        $this->MIX->join("pharmanexo.compradores c", "c.id = cotacoes.id_cliente");
        $this->MIX->order_by("cotacoes.data_criacao DESC");

        return $this->MIX->get('cotacoes')->result_array();
    }

    public function cotacoesSantaMarcelina()
    {

        $codigos = [445, 760, 761, 762, 763, 764, 765, 766, 875, 899, 901, 6893, 10522, 10524, 10525, 10527, 10529, 10530, 10531, 11905, 12396, 12397, 12398, 12399, 12400, 12401, 12402, 12403, 12404, 12405, 12434, 12648];

        $lista = implode($codigos, ',');

        $query = "SELECT 
                    cot.cd_cotacao,
                    cot.data_criacao,
                    c.razao_social,
                    f.nome_fantasia,
                    cot.dt_inicio_cotacao,
                    cot.dt_fim_cotacao
                FROM cotacoes_sintese.cotacoes cot
                JOIN pharmanexo.compradores c ON c.id = cot.id_cliente
                JOIN pharmanexo.fornecedores f ON f.id = cot.id_fornecedor
                WHERE cot.dt_fim_cotacao > now()
                    AND cot.id_fornecedor = 115
                    AND cot.id_cliente in ({$lista})
        ";

        return $this->DB_COTACAO->query($query)->result_array();
    }

    public function cotacoesSantana()
    {
        $query = "
            SELECT 
                cot.cd_cotacao,
                cot.data_criacao,
                c.razao_social,
                f.nome_fantasia,
                cot.dt_inicio_cotacao,
                cot.dt_fim_cotacao
            FROM cotacoes_sintese.cotacoes cot
            JOIN pharmanexo.compradores c ON c.id = cot.id_cliente
            JOIN pharmanexo.fornecedores f ON f.id = cot.id_fornecedor
            WHERE cot.dt_fim_cotacao > now()
                AND cot.id_fornecedor = 111
                AND cot.id_cliente in (452, 7114)";

        return $this->DB_COTACAO->query($query)->result_array();
    }

    public function cotacoesLondrina()
    {
       
        $query = "
            SELECT 
                cot.cd_cotacao,
                cot.data_criacao,
                c.razao_social,
                f.nome_fantasia,
                cot.dt_inicio_cotacao,
                cot.dt_fim_cotacao
            FROM cotacoes_sintese.cotacoes cot
            JOIN pharmanexo.compradores c ON c.id = cot.id_cliente
            JOIN pharmanexo.fornecedores f ON f.id = cot.id_fornecedor
            WHERE cot.dt_fim_cotacao > now()
                AND cot.id_fornecedor = 12
                AND cot.id_cliente in (545, 8215);
        ";

        return $this->DB_COTACAO->query($query)->result_array();
    }
}