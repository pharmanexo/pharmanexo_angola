<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cotacoes_mix extends MY_Controller
{
    private $route;
    private $views;
    private $oncoprod;
    private $DB_COTACAO;
    private $MIX;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('fornecedor/cotacoes_mix');
        $this->views = "fornecedor/cotacoes/cotacoes_mix";

        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_marca', 'marca');
        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_cotacoes', 'cotacoes');
        $this->load->model('m_forma_pagamento', 'forma_pagamento');

        $this->oncoprod = explode(',', ONCOPROD);

        $this->DB_COTACAO = $this->load->database('sintese', TRUE);
        $this->MIX = $this->load->database('mix', TRUE);
    }

    /**
     * Exibe a view admin/cotacoes_manuais/main.php
     *
     * @return  view
     */
    public function index()
    {
        $page_title = 'Lista de Cotações MIX';

        # Lista das cotações MIX
        $data['cotacoes'] = $this->getCotacoes();

        # Filtros
        $data['compradores'] = $this->compradores->find("*", null, false, "razao_social desc");
        $data['select_cotacoes'] = $this->cotacoes->find("*", "id_fornecedor = {$this->session->id_fornecedor}", FALSE, null, "cd_cotacao");

        # URLs
        $data['url_detalhes'] = "{$this->route}/detalhes";

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'styles' => [
                THIRD_PARTY . 'theme/plugins/flatpickr/flatpickr.min.css'
            ]
        ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title, 
            'buttons' => [
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel', 
                    'label' => 'Exportar Excel'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                'https://cdn.jsdelivr.net/npm/apexcharts',
                THIRD_PARTY . 'theme/plugins/flatpickr/flatpickr.min.js',
                'https://npmcdn.com/flatpickr/dist/l10n/pt.js'
            ]
        ]);

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Exibe a view admin/cotacoes_manuais/detail.php
     *
     * @param   int  $id_cotacao
     * @return  view
     */
    public function detalhes($id_cotacao)
    {
        $data = [];

        # Obtem a cotação MIX
        $data['cotacao'] = $this->MIX->where('id_cotacao', $id_cotacao)->get('cotacoes')->row_array();

        # Obtem os dados da cotação sintese
        $data['cotacao_sintese'] = $this->DB_COTACAO->select("*")
            ->where('cd_cotacao', $data['cotacao']['cd_cotacao'])
            ->where('id_fornecedor', $this->session->id_fornecedor)
            ->get('cotacoes')
            ->row_array();

        # Obtem o objeto do comprador
        $data['comprador'] = $this->compradores->findById($data['cotacao']['id_cliente']);

        # Obtem os produtos MIX combinados com seus envios automaticos
        $data['produtos_cotacao'] = $this->getProdutosAutMix($id_cotacao);

        # Obtem a data do ultimo envio da automatica
        $data['data_envio_automatica'] = $this->db
            ->select("data_criacao")
            ->where('id_fornecedor', $this->session->id_fornecedor)
            ->where('cd_cotacao', $data['cotacao']['cd_cotacao'])
            ->where('nivel', 2)
            ->order_by("data_criacao", 'DESC')
            ->get('cotacoes_produtos', 1)
            ->row_array()['data_criacao'];

        # Obtem a qtd total de produtos da cotação e o nº de itens respondidos pelo aut e mix.
        $data['totais'] = $this->getCountCot($this->session->id_fornecedor, $data['cotacao']['cd_cotacao'], 1);

        # Obtem a qtd total de produtos da cotação e o nº de itens respondidos pelo aut e mix.
        $data['valores_totais'] = $this->getTotalCotado($this->session->id_fornecedor, $id_cotacao, $data['cotacao']['cd_cotacao'], 1);


        # 



        # URLs
        $data['url_grafico_aprovado1'] = "{$this->route}/getCountCot/{$this->session->id_fornecedor}/{$data['cotacao']['cd_cotacao']}";
        $data['url_grafico_aprovado2'] = "{$this->route}/getTotalCotado/{$this->session->id_fornecedor}/{$id_cotacao}/{$data['cotacao']['cd_cotacao']}";

        $data['url_grafico_rejeitado1'] = "{$this->route}/getCountCot/{$this->session->id_fornecedor}/{$data['cotacao']['cd_cotacao']}";
        $data['url_grafico_rejeitado2'] = "{$this->route}/getCountCot/{$this->session->id_fornecedor}/{$data['cotacao']['cd_cotacao']}";

        $page_title = "Produtos da cotação #{$data['cotacao']['cd_cotacao']}";
        $data['header'] = $this->template->header([ 'title' => $page_title ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnBack',
                    'url' => $this->route,
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts([

            'scripts' => ["https://www.gstatic.com/charts/loader.js"]
        ]);

        $this->load->view("{$this->views}/detail", $data);
    }

    /**
     * Obtem os produtos da cotação da oferta MIX
     *
     * @param = int id_cotacao
     * @return  array
     */
    public function getProdutosAutMix($id_cotacao)
    {

        $this->MIX->select("*");
        $this->MIX->from("cotacoes_produtos");
        $this->MIX->where("id_cotacao", $id_cotacao);
        $this->MIX->order_by('ds_produto_marca ASC');
        $produtos = $this->MIX->get()->result_array();

        $enviados = [];
        $rejeitados = [];

        foreach ($produtos as $kk => $produto) {
            
            # Busca se o produto foi enviado
            $this->db->select('*');
            $this->db->from('cotacoes_produtos');
            $this->db->where('cd_cotacao', $produto['cd_cotacao']);
            $this->db->where('id_produto', $produto['cd_produto_sintese']);
            $this->db->where('id_sintese', $produto['cd_produto_marca']);
            $this->db->where('id_fornecedor', $this->session->id_fornecedor);
            $this->db->where('nivel', 3);
            $produto_enviado = $this->db->get()->row_array();

            if ( isset($produto_enviado) && !empty($produto_enviado) ) {

                # Obtem a marca do produto enviado
                $marca = $this->marca->get_row($produto_enviado['id_marca']);
                $produto_enviado['marca'] = (isset($marca)) ? $marca['marca'] : '';

                $produtos[$kk]['enviado'][] = $produto_enviado;
                $enviados[] = $produtos[$kk];
            } else {

                $produtos[$kk]['enviado'] = null;
                $rejeitados[] = $produtos[$kk];
            }
        }

        $data['enviados'] = $enviados;
        $data['rejeitados'] = $rejeitados;
        
        return $data;
    }

    /**
     * Obtem o valor total cotado via aut e mix
     *
     * @param = int ID do fornecedor
     * @param = int ID da cotação MIX
     * @param = String codigo da cotação
     * @param = Bool - modifica o retorno da função
     * @return  array/json
     */
    public function getTotalCotado($id_fornecedor, $id_cotacao, $cd_cotacao, $return = null)
    {

        # Valor Total MIX
        $valor_total_mix = $this->db
            ->select("SUM( (preco_marca * qtd_solicitada) ) AS valor_total")
            ->where('id_fornecedor', $id_fornecedor)
            ->where('cd_cotacao', $cd_cotacao)
            ->where('nivel', 3)
            ->get('cotacoes_produtos')
            ->row_array()['valor_total'];

        // $valor_total_mix = $this->MIX
        //     ->select("SUM( (vl_preco_produto * qt_produto_total_solicitado) ) AS valor_total")
        //     ->where('id_cotacao', $id_cotacao)
        //     ->get('cotacoes_produtos')
        //     ->row_array()['valor_total'];

        # Obtem Valor total da automatica
        $valor_total_automatica = $this->db
            ->select("SUM( (preco_marca * qtd_solicitada) ) AS valor_total")
            ->where('id_fornecedor', $id_fornecedor)
            ->where('cd_cotacao', $cd_cotacao)
            ->where('nivel', 2)
            ->get('cotacoes_produtos')
            ->row_array()['valor_total'];

        if (isset($return)) {

            $data['valor_total_mix'] = $valor_total_mix;
            $data['valor_total_automatica'] = $valor_total_automatica;

            return $data;
        } else {

            $data = [
                ['Cotações', 'Total'],
                ['Automática', floatval($valor_total_automatica)],
                ['Mix', floatval($valor_total_mix)]
            ];

            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    /**
     * Obtem a qtd de registros da cotação e repondidos via aut e mix
     *
     * @param = int ID do fornecedor
     * @param = String codigo da cotação
     * @param = Bool - modifica o retorno da função
     * @return  array/json
     */
    public function getCountCot($id_fornecedor, $cd_cotacao, $return = null)
    {
        # Numero de itens respondidos pela aut
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('cd_cotacao', $cd_cotacao);
        $this->db->where('nivel', 2);
        $this->db->where('submetido', 1);
        $this->db->from('cotacoes_produtos');
        $total_aut = $this->db->count_all_results();


        # Numero de itens respondidos pelo mix
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('cd_cotacao', $cd_cotacao);
        $this->db->where('nivel', 3);
        $this->db->from('cotacoes_produtos');
        $total_mix = $this->db->count_all_results();

        # Numero de itens da cotação
        $this->DB_COTACAO->where('id_fornecedor', $id_fornecedor);
        $this->DB_COTACAO->where('cd_cotacao', $cd_cotacao);
        $this->DB_COTACAO->from('cotacoes_produtos');
        $total_cot = $this->DB_COTACAO->count_all_results();

        if (isset($return)) {

            $data['total_itens_aprovados_aut'] = $total_aut;
            $data['total_itens_aprovados_mix'] = $total_mix;
            $data['total_itens_cotacao'] = $total_cot;

            return $data;
        } else {

            $data = [
                ['Cotações', 'Numero de registros'],
                // ['Cotações', $total_cot],
                ['Automática', $total_aut],
                ['Mix', $total_mix]
            ];

            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    /**
     * Obtem os cotações MIX
     *
     * @return  array
     */
    public function getCotacoes()
    {
        
        $this->MIX->select("cot.id_cotacao, cot.id_cliente, CONCAT(c.cnpj, ' - ', c.razao_social) AS comprador, cot.cd_cotacao, cot.data_criacao");
        $this->MIX->from("cotacoes cot");
        $this->MIX->join("pharmanexo.compradores c", "c.id = cot.id_cliente");
        $this->MIX->order_by('data_criacao DESC');
        $cotacoes = $this->MIX->get()->result_array();

        # Obtem o total
        foreach ($cotacoes as $kk => $cotacao) {
           
            # Obtem a quantidade de produtos na cotação
            $this->MIX->where('id_cotacao', $cotacao['id_cotacao']);
            $this->MIX->from('cotacoes_produtos');
            $cotacoes[$kk]['total'] = $this->MIX->count_all_results();

            # Obtem a quantidade de registros enviados
            $this->db->where('cd_cotacao', $cotacao['cd_cotacao']);
            $this->db->where('id_fornecedor', $this->session->id_fornecedor);
            $this->db->where('nivel', 3);
            $this->db->from('cotacoes_produtos');
            $cotacoes[$kk]['total_enviado'] = $this->db->count_all_results();

            # Remove as cotações do array que não exista itens enviados pelo fornecedor logado?

        }

        return $cotacoes;
    }
}