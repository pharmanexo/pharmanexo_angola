<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Cotacoes extends MY_Controller
{

    private $route;
    private $views;
    private $DB_COTACAO;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('/fornecedor/BI/cotacoes');
        $this->views = 'fornecedor/BI/cotacoes';

        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_marca', 'marca');
        $this->load->model('m_compradores', 'comprador');
        $this->load->model('m_encontrados_sintese', 'DEPARA');
        $this->load->model('m_estados', 'estado');
        $this->load->model('m_bi', 'BI');

        $this->DB_COTACAO = $this->load->database('sintese', TRUE);
    }

    /**
     * Exibe a tela inicial do BI
     *
     * @return view
     */
    public function index()
    {
        $page_title = "Relatório de cotações";

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'styles' => [
                THIRD_PARTY . 'theme/plugins/flatpickr/flatpickr.min.css'
            ]
        ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading(['page_title' => $page_title,
        'buttons' => [

            [
                'type' => 'a',
                'id' => 'btnVoltar',
                'url' => "produtosPreco",
                'class' => 'btn-secondary',
                'icone' => 'fa-arrow-left',
                'label' => 'Retornar'
            ]]]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                'https://cdn.jsdelivr.net/npm/apexcharts',
                THIRD_PARTY . 'theme/plugins/flatpickr/flatpickr.min.js',
                'https://npmcdn.com/flatpickr/dist/l10n/pt.js'
            ]
        ]);

        # URLs
        $data['url'] = "{$this->route}/main";

        $data['urlRelatorioCotacoes'] = "{$this->route}/datatableRelatorioCotacoes";
        $data['urlRelatorioProdutosCotacao'] = "{$this->route}/produtosCotacao";

        # Filtros

        $data['compradores'] = $this->comprador->find("id, CONCAT(cnpj, ' - ', razao_social) AS comprador", null, false, 'comprador ASC');
        $data['estados'] = $this->estado->find("id, uf, CONCAT(uf, ' - ', descricao) AS estado", null, false, 'estado ASC');

        if ($this->session->has_userdata('id_matriz')) {

            $data['selectMatriz'] = $this->fornecedor->find("id, nome_fantasia", "id_matriz = {$this->session->id_matriz}");
        }


        $this->load->view("{$this->views}/main", $data);
    }

    /**
     *  Exibe a tela de detalhes da cotação
     *
     * @param String Numero da cotação
     * @param int ID do fornecedor
     * @return  view
     */
    public function produtosCotacao($cd_cotacao, $id_fornecedor)
    {

        $page_title = "Produtos da cotação #{$cd_cotacao}";

        # Obtem a cotação
        $data['cotacao'] = $this->DB_COTACAO->where('cd_cotacao', $cd_cotacao)->where("id_fornecedor", $id_fornecedor)->get('cotacoes')->row_array();

        # Obtem o comprador
        $data['comprador'] = $this->comprador->find('*', ['id' => $data['cotacao']['id_cliente']], true);

        # Total de itens da cotação
        $data['total_itens'] = $this->DB_COTACAO->where('cd_cotacao', $cd_cotacao)->where('id_fornecedor', $id_fornecedor)->count_all_results('cotacoes_produtos');

        $data['produtos'] = $this->MatchProducts($cd_cotacao, $id_fornecedor);


        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' =>  [
                [
                    'type' => 'a',
                    'id' => 'btnBack',
                    'url' => $this->route,
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ],
            ]
        ]);

        $this->load->view("{$this->views}/detail", $data);
    }

    /**
     * obtem os dados para grafico e indicadores da pagina
     *
     * @param - post - String data inicio
     * @param - post - String data fim
     * @param - post - int ID fornecedor (opcional)
     * @param - post - int ID comprador (opcional)
     * @param - post - String UF estado (opcional)
     * @return json
     */
    public function main()
    {

        $post = $this->input->post();

        $dados = $this->filtros($post);

        # Grafico
        $graficoResultado = $this->BI->totalCotacoes($dados);
        $sobra = intval($graficoResultado['qtd_cotacao_total']) - intval($graficoResultado['qtd_cot_logado']);
        $series = [ $sobra, intval($graficoResultado['qtd_cot_logado'])];
        $labels = ['RESTRIÇÕES', $dados['empresa']];
        $data['series'] = $series;
        $data['labels'] = $labels;

        # Indicadores
        $data['indicadores'] = $this->BI->indicadoresCotacao($dados);
        $data['indicadores']['total_ofertado'] = $this->BI->getTotalOferta($dados)['total_ofertado'];
        $data['valor_formatado'] = number_format($data['indicadores']['total_ofertado'], 4, ',', '.');

       
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Obtem os dados do relatorio de cotações
     *
     * @param - get - INT flag para mudar de relatorio
     * @param - post - String data inicio
     * @param - post - String data fim
     * @param - post - int ID fornecedor (opcional)
     * @return json
     */
    public function datatableRelatorioCotacoes($restricao = null)
    {

        $post = $this->input->post();

        $dados = $this->filtros($post);

        if ( isset($restricao) ) {
            
            $data = $this->BI->dadosCotacaoNot($dados, $post);
        } else {

            $data = $this->BI->dadosCotacao($dados, $post);
        }


        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Trata os dados do POST
     *
     * @param - post - String data inicio
     * @param - post - String data fim
     * @param - post - int ID fornecedor (opcional)
     * @param - post - int ID comprador (opcional)
     * @param - post - String UF estado (opcional)
     * @return json
     */
    private function filtros($post) 
    {

        $id_fornecedor = (!empty($post['id_fornecedor'])) ? $post['id_fornecedor'] : $this->session->id_fornecedor;

        $post_id = false;

        if ($this->session->has_userdata('id_matriz') && !empty($post['id_fornecedor'])) {

            $post_id = true;
        }

        $filial = $this->BI->matrizFilial(FALSE, $post_id, $id_fornecedor);

        $empresa = "";
        $fornecedor = "";

        foreach ($filial as $key => $item) {

            $empresa = $key;
            $fornecedor = $item;
        }


        $dados = [
            'dt_inicio' => $post['dataini'],
            'dt_fim' => $post['datafim'],
            'fornecedor' => $fornecedor,
            'id_fornecedor' =>  $id_fornecedor,
            'empresa' => $empresa,
            'uf_cotacao' => $post['uf_cotacao'],
            'id_cliente' => $post['id_cliente']
        ];

        return $dados;
    }

    /**
     * Combina os produtos pharmanexo encontrado no depara com os produtos da SINTESE
     *
     * @param - String codigo da cotação
     * @param - Int ID do fornecedor
     * @return  bool/function getDetailsProducts
     */
    public function MatchProducts($cd_cotacao, $id_fornecedor, $return = null)
    {
        # Obtem os produtos da pharmanexo dos produtos da sintese
        $depara = $this->DEPARA->getProdutos_depara($cd_cotacao, "= {$id_fornecedor}");

        # Lista dos produtos da cotação na SINTESE
        $this->DB_COTACAO->select("id_produto_sintese");
        $this->DB_COTACAO->select("id_fornecedor");
        $this->DB_COTACAO->select("cd_produto_comprador");
        $this->DB_COTACAO->select("ds_produto_comprador");
        $this->DB_COTACAO->select("ds_unidade_compra");
        $this->DB_COTACAO->select("ds_complementar");
        $this->DB_COTACAO->select("SUM(qt_produto_total) AS qt_produto_total");
        $this->DB_COTACAO->select("cd_cotacao");
        $this->DB_COTACAO->where('cd_cotacao', $cd_cotacao);
        $this->DB_COTACAO->where('id_fornecedor', $id_fornecedor);
        $this->DB_COTACAO->group_by('id_produto_sintese, cd_produto_comprador');
        $produtos_cotacao = $this->DB_COTACAO->get('cotacoes_produtos')->result_array();

        $produtos = [];

        # Faz a combinação dos produtos Pharmanexo x Sintese
        foreach ($produtos_cotacao as $produto) {

            $encontrados = [];

            if ( isset($depara) && !empty($depara) ) {
                
                foreach ($depara as $prod) {

                    if ( $prod['id_produto'] == $produto['id_produto_sintese'] ) {

                        $encontrados[] = $prod;
                    }
                }
            }

            $produtos[] = [
                'cotado' => $produto,
                'encontrados' => $encontrados,
            ];
        }

        return $this->getDetailsProducts($produtos);
    }

    /**
     * Obtem informações como preço, estoque e total de envios para os depara encontrados
     *
     * @param - Array de produtos
     * @return  function OrganizeProducts
     */
    public function getDetailsProducts($produtos)
    {
        foreach ($produtos as $kk => $produto) {
                
            if ( isset($produto['encontrados']) && !empty($produto['encontrados']) ) {
                
               foreach ($produto['encontrados'] as $k => $p) {
                    
                    # Obtem o preço
                    $produtos[$kk]['encontrados'][$k]['preco_unitario'] = $this->getPrice($p['codigo'], $p['id_fornecedor']);

                    # Obtem o estoque
                    $produtos[$kk]['encontrados'][$k]['estoque'] = $this->getStock($p['codigo'], $p['id_fornecedor']);

                    # Marca
                    $produtos[$kk]['encontrados'][$k]['marca'] = $this->marca->get_row($p['id_marca'])['marca'];

                    # verifica se foi respondido
                    $produto_enviado = $this->db->select("*")
                        ->where("id_fornecedor", $p['id_fornecedor'])
                        ->where("cd_cotacao", $p['cd_cotacao'])
                        ->where("id_produto", $p['id_produto'])
                        ->where("id_pfv", $p['codigo'])
                        ->where("cd_produto_comprador", $produto['cotado']['cd_produto_comprador'])
                        ->where("submetido", 1)
                        ->group_start()
                            ->where("nivel", 1)
                            ->or_where("nivel", 2)
                        ->group_end()
                        ->get('cotacoes_produtos')
                        ->row_array();

                    if ( isset($produto_enviado) && !empty($produto_enviado) )  {
                        
                        $tipo_envio = ( $produto_enviado['nivel'] == 1 ) ? "Manual" : "Automática";
                    } else {

                        $tipo_envio = "Não";
                    }

                    $produtos[$kk]['encontrados'][$k]['enviado'] = $tipo_envio;
               }

            }
        }

        return $this->OrganizeProducts($produtos);
    }

    /**
     * Organiza os produtos 
     *
     * @param - Array de produtos
     * @return  array
     */
    public function OrganizeProducts($produtos)
    {

        $azuis = [];
        $verdes = [];
        $vermelhos = [];

        # Organiza array de produtos
        if (isset($produtos)) {

            foreach ($produtos as $kk => $p) {

                # Organiza os itens de um produto
                if (!empty($p['encontrados']) ) {

                    $prods = [];

                    $itens_com_estoque = [];
                    $itens_com_estoque_insuf = [];
                    $itens_sem_estoque = [];


                    foreach ($p['encontrados'] as $kj => $item) {

                        if (intval($item['estoque']) > 0 && intval($item['estoque']) >= $p['cotado']['qt_produto_total']) {
                            $itens_com_estoque[] = $item;
                        } else if ( intval($item['estoque']) > 0 && intval($item['estoque']) < $p['cotado']['qt_produto_total'] ) {
                            $itens_com_estoque_insuf[] = $item;
                        } else {
                            $itens_sem_estoque[] = $item;
                        }
                    }

                    $p['encontrados'] = array_merge($itens_com_estoque,  $itens_com_estoque_insuf, $itens_sem_estoque);
                }

                if ( isset($p['encontrados']) && in_array(1, array_column($p['encontrados'], 'enviado')) ) {

                    $p['cotado']['class'] = 'enviado';
                    $azuis[] = $p;
                } elseif ( isset($p['encontrados']) && !empty($p['encontrados']) && !in_array(1, array_column($p['encontrados'], 'enviado')) ) {

                    $p['cotado']['class'] = '';
                    $verdes[] = $p;
                } elseif( empty($p['encontrados']) ) {

                    $p['cotado']['class'] = 'nencontrado';
                    $vermelhos[] = $p;
                } else {

                    $p['cotado']['class'] = '';
                    $verdes[] = $p;
                }
            }


            if (!empty($azuis)) {

                foreach ($azuis as $kk => $p) {

                    $nome1[$kk]  = $p['cotado']['ds_produto_comprador'];
                }

                array_multisort($nome1, SORT_ASC, $azuis);
            }

            if (!empty($verdes)) {

                foreach ($verdes as $kk => $p) {

                    $nome2[$kk]  = $p['cotado']['ds_produto_comprador'];
                }

                array_multisort($nome2, SORT_ASC, $verdes);
            }

            if (!empty($vermelhos)) {

                foreach ($vermelhos as $kk => $p) {

                    $nome3[$kk]  = $p['cotado']['ds_produto_comprador'];
                }

                array_multisort($nome3, SORT_ASC, $vermelhos);
            }
        }

        return array_merge($azuis, $verdes, $vermelhos);
    }

    /**
     * Obtem o estoque de um produto 
     *
     * @param - INT codigo do produto
     * @param - INT ID do fornecedor
     * @return  int
     */
    public function getStock($codigo, $id_fornecedor)
    {

        $this->db->select("quantidade_unidade");
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('codigo', $codigo);
        
        $qtd_unidade = $this->db->get('produtos_catalogo')->row_array()['quantidade_unidade'];

        if ( isset($qtd_unidade) &&  $qtd_unidade > 0 ) {

            $this->db->select("( SUM(estoque) * {$qtd_unidade} )  AS estoque");
        } else {

            $this->db->select(" (SUM(estoque)) AS estoque");
        }

        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('codigo', $codigo);
        $estoque = $this->db->get('produtos_lote')->row_array()['estoque'];

        return $estoque;
    }

    /**
     * Obtem o preço de um produto 
     *
     * @param - INT codigo do produto
     * @param - INT ID do fornecedor
     * @return  number
     */
    public function getPrice($codigo, $id_fornecedor)
    {
        $f = $this->fornecedor->findById($id_fornecedor);

        $estado = $this->estado->find("id", "uf = '{$f['estado']}' ", TRUE);

        $preco = $this->price->getPrice([
            'id_fornecedor' => $id_fornecedor,
            'codigo' => $codigo,
            'id_estado' => $estado['id']
        ]);

        return $preco;
    }
}