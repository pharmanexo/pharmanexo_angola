<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cotacoes_comprador extends Admin_controller
{
    private $route, $views, $DB_COTACAO;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('admin/relatorios/cotacoes_comprador');
        $this->views = "admin/relatorios/cotacoes_comprador";

        $this->DB_COTACAO = $this->load->database('sintese', TRUE);

        $this->load->model('m_fornecedor', 'fornecedores');
        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_estados', 'estados');
        $this->load->model('m_encontrados_sintese', 'DEPARA');
        $this->load->model('m_marca', 'marcas');
    }

    public function index()
    {
        $page_title = 'Total de cotações por Comprador';

        $data['datatables'] = "{$this->route}/datatables_compradoresCot";
        $data['url_detalhes'] = "{$this->route}/details";

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'styles' => [THIRD_PARTY . 'theme/plugins/flatpickr/flatpickr.min.css']
        ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([ 'page_title' => $page_title ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'theme/plugins/flatpickr/flatpickr.min.js',
                'https://npmcdn.com/flatpickr/dist/l10n/pt.js'
            ]
        ]);

        $data['fornecedores'] = $this->fornecedores->find("*", "sintese = 1", false, 'nome_fantasia ASC');
        $data['compradores'] = $this->compradores->find('*', NULL, FALSE, 'razao_social ASC');

        $this->load->view("{$this->views}/main", $data);
    }

    public function details($id_fornecedor, $id_cliente, $dataini, $datafim)
    {
        $page_title = "Cotações por Comprador";

        $data['comprador'] = $this->compradores->findById($id_cliente);
        $data['fornecedor'] = $this->fornecedores->findById($id_fornecedor);
        
        $data['datatables'] = "{$this->route}/datatables_cotacoesComprador/{$dataini}/{$datafim}/{$id_fornecedor}/{$id_cliente}";
        $data['url_detalhes'] = "{$this->route}/details_cotacao/{$id_fornecedor}";

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
                ],
            ]
        ]);
        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/detail", $data);
    }

    public function details_cotacao($id_fornecedor, $cd_cotacao)
    {
        
        $page_title = "Produtos da cotação #{$cd_cotacao}";

        $data['produtos'] = $this->MatchProducts($cd_cotacao, $id_fornecedor);

        // var_dump($data['produtos']); exit();

        $data['header'] = $this->template->header([ 'title' => $page_title ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnBack',
                    'url' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $this->route,
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ],
            ]
        ]);
        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/detail_cotacao", $data);
    }

   
    public function datatables_compradoresCot()
    {

        if ( $this->input->is_ajax_request() ) {

            $post = $this->input->post();

            $dataini = ( !empty($post['dataini']) ) ? $post['dataini'] : date('Y-m-d');
            $datafim = ( !empty($post['datafim']) ) ? $post['datafim'] : date('Y-m-d');

            if (strtotime($datafim) < strtotime($dataini)) {

                $data = [];
            } else {

                $this->DB_COTACAO->select("c.cnpj");
                $this->DB_COTACAO->select("c.razao_social");
                $this->DB_COTACAO->select("f.nome_fantasia");
                $this->DB_COTACAO->select("cot.id_fornecedor");
                $this->DB_COTACAO->select("cot.id_cliente");
                $this->DB_COTACAO->select("SUM(cot.oferta) AS totalProduto");
                $this->DB_COTACAO->select("COUNT(cot.cd_cotacao) AS total");
                $this->DB_COTACAO->from("cotacoes cot");
                $this->DB_COTACAO->join("pharmanexo.compradores c", "c.id = cot.id_cliente");
                $this->DB_COTACAO->join("pharmanexo.fornecedores f", "f.id = cot.id_fornecedor");
                $this->DB_COTACAO->where("date(cot.dt_inicio_cotacao) BETWEEN '{$dataini}' AND '{$datafim}' ");

                if ( $post['id_cliente'] != '' ) {
                    $this->DB_COTACAO->where("cot.id_cliente = {$post['id_cliente']}");
                }

                if ( $post['id_fornecedor'] != '' ) {
                    $this->DB_COTACAO->where("cot.id_fornecedor = {$post['id_fornecedor']}");
                }

                $this->DB_COTACAO->group_by("c.cnpj, c.razao_social, f.nome_fantasia, cot.id_fornecedor, cot.id_cliente");

                $data = $this->DB_COTACAO->get()->result_array();
            }

            if ( !empty($data) ) {

                $output = ['type' => 'success', 'data' => $data];
            } else {

                $output = ['type' => 'warning', 'data' => null];
            }


           $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function datatables_cotacoesComprador($dataini, $datafim, $id_fornecedor, $id_cliente)
    {
        $dt = $this->datatable->exec(
            $this->input->post(),
            'cotacoes_sintese.cotacoes',
            [
                ['db' => 'cd_cotacao', 'dt' => 'cd_cotacao'],
                ['db' => 'id_fornecedor', 'dt' => 'id_fornecedor'],
                ['db' => 'id_cliente', 'dt' => 'id_cliente'],
                ['db' => 'ds_cotacao', 'dt' => 'ds_cotacao'],
                ['db' => 'uf_cotacao', 'dt' => 'uf_cotacao'],
                ['db' => 'dt_inicio_cotacao', 'dt' => 'dt_inicio_cotacao', 'formatter' => function ($value, $row) {
                    return date("d/m/Y H:i", strtotime($value));
                }],
                ['db' => 'dt_fim_cotacao', 'dt' => 'dt_fim_cotacao', 'formatter' => function ($value, $row) {
                    return date("d/m/Y H:i", strtotime($value));
                }]
            ],
            null, 
            "id_fornecedor = {$id_fornecedor} AND id_cliente = {$id_cliente} AND DATE(dt_inicio_cotacao) BETWEEN '{$dataini}' AND '{$datafim}' "
        );


        $this->output->set_content_type('application/json')->set_output(json_encode($dt));
    }

    public function getCotacoes()
    {

        if ( $this->input->is_ajax_request() ) {

            $post = $this->input->post();
            
            $this->DB_COTACAO->where("id_fornecedor", $post['id_fornecedor']);
            $this->DB_COTACAO->where("dt_fim_cotacao > now()");
            $this->DB_COTACAO->where("oculto != 1");
            $this->DB_COTACAO->group_by('cd_cotacao');
            $this->DB_COTACAO->order_by('oferta DESC');
            $this->DB_COTACAO->order_by('dt_fim_cotacao ASC');
            $data = $this->DB_COTACAO->get('cotacoes')->result_array();

            # Busca comprador
            foreach ($data as $kk => $row) {
                    
                if ( isset($row['id_cliente']) && !empty($row['id_cliente']) ) {
                    
                    $cliente = $this->compradores->findById($row['id_cliente']);
                } else {

                    $cnpj = mask($row['cd_comprador'], '##.###.###/####-##');
                    $cliente = $this->compradores->get_byCNPJ($cnpj);
                }

                $data[$kk]['comprador'] = "{$cliente['cnpj']} - {$cliente['razao_social']}";

                $data[$kk]['dt_inicio'] = date('d/m/Y H:i', strtotime($row['dt_inicio_cotacao']));
                $data[$kk]['dt_fim'] = date('d/m/Y H:i', strtotime($row['dt_fim_cotacao']));
            }


            if ( !empty($data) ) {

                $warning = ['type' => 'success', 'data' => $data];
            } else {

                $warning = ['type' => 'warning', 'data' => null];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
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
                    $produtos[$kk]['encontrados'][$k]['marca'] = $this->marcas->get_row($p['id_marca'])['marca'];

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
        $f = $this->fornecedores->findById($id_fornecedor);

        $estado = $this->estados->find("id", "uf = '{$f['estado']}' ", TRUE);

        $preco = $this->price->getPrice([
            'id_fornecedor' => $id_fornecedor,
            'codigo' => $codigo,
            'id_estado' => $estado['id']
        ]);

        return $preco;
    }
}