<?php
date_default_timezone_set('America/Sao_Paulo');

class Cotacoes_vencidas extends MY_Controller
{
    private $urlCliente;
    private $client;
    private $location;
    private $route;
    private $views;
    private $oncoprod;
    private $DB_COTACAO;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('admin/m_fornecedor', 'fornecedor');
        $this->load->model('m_cotacoes_produtos', 'cotacao');
        $this->load->model('m_venda_diferenciada', 'venda_dif');
        $this->load->model('m_forma_pagamento', 'forma_pagamento');
        $this->load->model('m_forma_pagamento_fornecedor', 'forma_pagamento_fornecedor');
        $this->load->model('m_prazo_entrega', 'prazo_entrega');
        $this->load->model('m_valor_minimo', 'valor_minimo');
        $this->load->model('m_usuarios', 'usuario');
        $this->load->model('produto_fornecedor_validade', 'pfv');
        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_estados', 'estado');
        $this->load->model('m_estoque', 'estoque');
        $this->load->model('produto_marca_sintese', 'pms');
        $this->load->model('m_encontrados_sintese', 'encontrados');
        $this->load->model('m_marca', 'marca');

        // $this->urlCliente = 'https://plataformasintese.com:444/IntegrationService.asmx?WSDL';
        $this->urlCliente = 'http://integracao.plataformasintese.com/IntegrationService.asmx?WSDL';

        $this->route = base_url('/admin/cotacoes_vencidas/');
        $this->views = 'admin/cotacoes_vencidas/';

        $this->oncoprod = explode(',', ONCOPROD);
        $this->DB_COTACAO = $this->load->database('sintese', TRUE);
    }

    public function index()
    {
        $page_title = "Cotações Encerradas";

        $data['form_action'] = "{$this->route}detalhes";
        $data['fornecedores'] = $this->fornecedor->find("*", "id in (12, 111, 112, 115, 120, 123)");

        $this->DB_COTACAO->select('id, cd_cotacao');
        $this->DB_COTACAO->where_in('id_fornecedor', [12, 111, 112, 115, 120, 123]);
        $this->DB_COTACAO->where("data_criacao between '2019-11-01' and '2020-12-22'");
        $this->DB_COTACAO->group_by("cd_Cotacao");
        $data['cotacoes'] = $this->DB_COTACAO->get('cotacoes')->result_array();

        $data['header'] = $this->template->header([ 'title' => $page_title]);
        $data['navbar']  = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons'    => [
                [
                    'type'  => 'submit',
                    'id'    => 'btnSave',
                    'form'  => 'formCotacoes',
                    'class' => 'btn-primary',
                    'icone' => 'fa-arrow-right',
                    'label' => 'Avançar'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/jquery.form.min.js',
                THIRD_PARTY . 'plugins/jquery-validation-1.19.1/dist/jquery.validate.min.js'
            ]
        ]);

        $this->load->view("{$this->views}/main", $data);
    }

    public function detalhes($cd_cotacao)
    {
        $post = $this->input->post();

        if (isset($post) && !empty($post)) {
            
            $fornecedor = $this->fornecedor->findById($post['id_fornecedor']);
        } else {
            $fornecedor = $this->fornecedor->findById($this->session->id_fornecedor);
        }

        $page_title = "Cotação #{$cd_cotacao}";

        $this->DB_COTACAO->where('cd_cotacao', $cd_cotacao);
        $this->DB_COTACAO->where('id_fornecedor', $fornecedor['id']);
        $cot = $this->DB_COTACAO->get('cotacoes')->row_array();

        if (isset($cot) && !empty($cot)) {

            $session_data = [
                'id_fornecedor' => $fornecedor['id'],
                'razao_social' => $fornecedor['razao_social'],
                'cnpj' => $fornecedor['cnpj'],
                "id_tipo_venda" => $fornecedor['id_tipo_venda'],
                "id_estado" => $this->estado->find("id", "uf = '{$fornecedor['estado']}'", TRUE)['id'],
            ];

            $this->session->set_userdata($session_data);

            $data = [
                'header' => $this->template->header([
                    'title' => $page_title
                ]),
                'navbar' => $this->template->navbar(),
                'sidebar' => $this->template->sidebar(),
                'heading' => $this->template->heading([
                    'page_title' => $page_title,
                    'buttons' => [
                        [
                            'type' => 'a',
                            'id' => 'btnVoltar',
                            'url' => "{$this->route}",
                            'class' => 'btn-secondary',
                            'icone' => 'fa-arrow-left',
                            'label' => 'Retornar'
                        ],
                        [
                            'type' => 'button',
                            'id' => 'btn_ocultar', 
                            'url' => "",
                            'class' => ($cot['oculto'] == 1) ? 'btn-danger' : 'btn-outline-danger',
                            'icone' => ($cot['oculto'] == 1) ? 'fa-eye' : 'fa-eye-slash',
                            'label' => ($cot['oculto'] == 1) ? 'Remover Ocultação' : 'Ocultar Cotação'
                        ],
                        [
                            'type' => 'submit',
                            'id' => 'btnRascunho',
                            'form' => "respostaCotacao",
                            'class' =>  'btn-primary',
                            'icone' =>  'fa-save',
                            'label' => 'Salvar Rascunho' 
                        ]
                    ]
                ]),
                'scripts' => $this->template->scripts(
                    []
                ),
                'url_cotacoes' => $this->route . "get_cotacoes"
            ];

            $data['url_historico'] = "{$this->route}get_historico";
            $data['id_fornecedor'] = $this->session->id_fornecedor;
            $data['cot'] = $cot['cd_cotacao'];
            $data['url_findProduct'] = "{$this->route}findProduct/";
            $data['descricao_codigo'] = 'Código Kraft';
            $data['descricao_produto'] = 'Descrição Padrão do Produto';
            $data['oncoprod'] = 1;
            $data['cotacao'] = $this->get_item_oncoprod($cd_cotacao);
            $data['form_action'] = "{$this->route}enviar_resposta_oncoprod";
            $data['url_ocultar'] = "{$this->route}ocultarCotacao/{$cd_cotacao}/{$this->session->id_fornecedor}";

            $data['options_fornecedores'] = [
               [ 'id' => 111, 'fornecedor' => "CE"],
               [ 'id' => 120, 'fornecedor' => "DF"],
               [ 'id' => 112, 'fornecedor' => "ES"],
               [ 'id' => 123, 'fornecedor' => "PE"],
               [ 'id' => 12, 'fornecedor' =>  "POA"],
               [ 'id' => 115, 'fornecedor' => "SP"]
            ];


            $_SESSION['cotacao_atual'] = $data['cotacao']['id_cotacao'];

            $this->load->view("{$this->views}/detalhes_oncoprod", $data);
        } else {

            $this->session->set_userdata('warning', ['type' => 'warning', 'message' => "Não existe essa cotação para o fornecedor selecionado!"]);

            redirect($this->route);
        }
    }

    /**
     * Atualiza campo ocultar em cotacoes para não ser exibida novamente na listagem da oncoprod
     *
     * @param - String - codigo da cotação
     * @param - int - ID do fornecedor
     * @return  json
     */
    public function ocultarCotacao()
    {
        if ($this->input->is_ajax_request()) {

            $post = $this->input->post();

            $cd_cotacao = $post['cd_cotacao'];
            $id_fornecedor = $post['id_fornecedor'];

            $this->DB_COTACAO->where('cd_cotacao', $cd_cotacao);
            $this->DB_COTACAO->where('id_fornecedor', $id_fornecedor);
            $cotacao =  $this->DB_COTACAO->get('cotacoes')->row_array();

            $valor = ($cotacao['oculto'] == 1) ? 0 : 1;

            $this->DB_COTACAO->where('cd_cotacao', $cd_cotacao);
            $this->DB_COTACAO->where('id_fornecedor', $id_fornecedor);
            $update = $this->DB_COTACAO->update('cotacoes', [ 'oculto' => $valor ]);

            if ( $update ) {

                $warning = ['type' => 'success', 'message' => 'Registro atualizado com sucesso', 'route' => "{$this->route}?uf={$cotacao['uf_cotacao']}"];
            } else {
                $warning = ['type' => 'warning', 'message' => 'Erro ao salvar as informações!'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
    }

    /**
     * obtem histórico de ofertas de um produto
     *
     * @return  json
     */
    public function get_historico()
    {
        if ($this->input->is_ajax_request()) {

            $post = $this->input->post();

            $format = "%d/%m/%Y %H:%i";
            $this->db->select("id, cd_cotacao, preco_marca as preco, FORMAT(preco_marca, 4, 'de_DE') as preco_marca, DATE_FORMAT(data_cotacao, '{$format}') as data");
            $this->db->where('id_fornecedor', $post['id_fornecedor']);
            $this->db->where('filial', $post['filial']);
            $this->db->where('id_produto', $post['id_produto']);
            // $this->db->group_by();
            $this->db->order_by("data_cotacao desc");
            $this->db->limit(6);
            $ofertas = $this->db->get('cotacoes_produtos');

            if ($ofertas->num_rows() > 0) {

                $ofertas = $ofertas->result_array();

                $soma = array_sum( array_column($ofertas, 'preco') );

                $media = $soma / count($ofertas);
                
                $data = ['data' => $ofertas, 'media' => round($media, 2)];
            } else {
                $data = ['data' => 0, 'media' => 0];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    /**
     * view de localizar produto
     *
     * @return  view
     */
    public function findProduct($id_produto)
    {
        $produto = $this->pms->find('*', "id_produto = {$id_produto}", true);

        $page_title = "Produto: {$produto['descricao']}";

        $data = [
            'header' => $this->template->header(['title' => $page_title]),
            'navbar' => $this->template->navbar(),
            'sidebar' => $this->template->sidebar(),
            'heading' => $this->template->heading([
                'page_title' => $page_title,
                'buttons' => [
                    [
                        'type' => 'a',
                        'id' => 'btnVoltar',
                        'url' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $this->route,
                        'class' => 'btn-secondary',
                        'icone' => 'fa-arrow-left',
                        'label' => 'Retornar'
                    ],
                    [
                        'type' => 'button',
                        'id' => 'btnCombinar',
                        'url' => "",
                        'class' => 'btn-primary',
                        'icone' => 'fa-random',
                        'label' => 'Combinar Produtos'
                    ],
                ]
            ]),
            'scripts' => $this->template->scripts()
        ];

        $data['produto'] = $produto;
        $data['datatables'] = "{$this->route}datatables";
        $data['url_combinar'] = "{$this->route}combinar_produto_marca";

        $this->load->view("{$this->views}/find_product", $data);
    }

    /**
     * Função para combinar produto com marca
     *
     * @return  json
     */
    public function combinar_produto_marca()
    {
        if ($this->input->is_ajax_request()) {

            $post = $this->input->post();

            if ( in_array($post['id_fornecedor'], $this->oncoprod)) {

                $data = [];

                foreach ($this->oncoprod as $fornecedor) {

                    $this->db->where('id_fornecedor', $fornecedor);
                    $this->db->where('cd_produto', $post['cd_produto']);
                    $this->db->where('id_sintese', $post['id_sintese']);
                    $pfs = $this->db->get('produtos_fornecedores_sintese')->row_array();

                    if (empty($pfs)) {

                        $data[] = [
                            'id_fornecedor' => $fornecedor,
                            'cd_produto' => $post['cd_produto'],
                            'id_sintese' => $post['id_sintese']
                        ];   
                    }
                }

                if (!empty($data)) {

                    $v = $this->db->insert_batch('produtos_fornecedores_sintese', $data);

                    if ( $v != false ) {
                        $warning = ['type' => 'success', 'message' => 'Registrado com sucesso'];
                    } else {
                        $warning = ['type' => 'warning', 'message' => 'Erro ao registrar'];
                    }
                } else {
                    $warning = ['type' => 'warning', 'message' => 'Combinação de produtos já existente'];
                }
            } else {

                $this->db->where('id_fornecedor', $post['id_fornecedor']);
                $this->db->where('cd_produto', $post['cd_produto']);
                $this->db->where('id_sintese', $post['id_sintese']);
                $pfs = $this->db->get('produtos_fornecedores_sintese')->row_array();

                if (empty($pfs)) {
                    
                    if ($this->db->insert('produtos_fornecedores_sintese', $post)) {
                        $warning = ['type' => 'success', 'message' => 'Registrado com sucesso'];
                    } else {
                        $warning = ['type' => 'warning', 'message' => 'Erro ao registrar'];
                    }
                } else {
                    $warning = ['type' => 'warning', 'message' => 'Combinação de produtos já existente'];
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
    }

    /**
     * Exibe o catalogo de produtos
     *
     * @return  json
     */
    public function datatables()
    {
        $datatables = $this->datatable->exec(
            $this->input->post(),
            'produtos_catalogo',
            [
                ['db' => 'codigo', 'dt' => 'codigo'],
                ['db' => 'marca', 'dt' => 'marca'],
                ['db' => 'descricao', 'dt' => 'descricao'],
                ['db' => 'nome_comercial', 'dt' => 'nome_comercial'],
                ['db' => 'id_fornecedor', 'dt' => 'id_fornecedor'],
                ['db' => 'apresentacao', 'dt' => 'apresentacao', 'formatter' => function ($value, $row) {
                    if (!empty($row['descricao'])) {
                        return "{$row['nome_comercial']} - {$row['descricao']}";
                    }
                    return "{$row['nome_comercial']} - {$row['apresentacao']}";

                }],
            ],
            null,
            "ativo = 1 and id_fornecedor = {$this->session->id_fornecedor}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    # FUNÇÕES PARA ONCOPROD

    /**
     * Obtem venda diferencia
     *
     * @param - int - id do fornecedor
     * @param - int - codigo do produto
     * @param - int -id do cliente
     * @param - int - id do estado
     * @return  objeto
     */
    public function verificaVendaDiferenciada($fornecedor, $codigo, $cliente, $estado)
    {
        # Prioridade: venda diferencia do cliente
        $query = $this->db->select('*')
            ->from('vendas_diferenciadas')
            ->where('id_cliente', $cliente)
            ->where('id_fornecedor', $fornecedor)
            ->where('codigo', $codigo)
            ->where_not_in('regra_venda', 2)
            ->limit(1)
            ->get()
            ->row_array();

        # Se não existir, verifica por estado
        if (isset($query) && !empty($query)) {
            $query = $this->db->select('*')
                ->from('vendas_diferenciadas')
                ->where("id_estado = {$estado}")
                ->where('id_fornecedor', $fornecedor)
                ->where('codigo', $codigo)
                ->where_not_in('regra_venda', 2)
                ->limit(1)
                ->get()
                ->row_array();
        }

        return isset($query) ? $query : null;
    }

    /**
     * Obtem os dados da cotação
     *
     * @param - string - codigo da cotação
     * @return  json
     */
    public function get_item_oncoprod($cd_cotacao)
    {
        # Obtem Cotação
        $this->DB_COTACAO->where('cd_cotacao', $cd_cotacao);
        $this->DB_COTACAO->where('id_fornecedor', $this->session->id_fornecedor);
        $cotacao = $this->DB_COTACAO->get('cotacoes')->row_array();

        # Obtem Cliente
        $cnpj = mask($cotacao['cd_comprador'], '##.###.###/####-##');
        $cliente = $this->compradores->get_byCNPJ($cnpj);;

        # Obtem Estado do cliente
        if (isset($cliente) && !empty($cliente['estado'])) {
            $estado = $this->estado->find("*", "uf = '{$cliente['estado']}'", true)['id'];
        }

        # Obtem Lista de produtos da cotação
        $this->DB_COTACAO->select("*");
        $this->DB_COTACAO->where('cd_cotacao', $cd_cotacao);
        $this->DB_COTACAO->where('id_fornecedor', $this->session->id_fornecedor);
        $this->DB_COTACAO->group_by('id_produto_sintese, cd_produto_comprador');
        $produtos_cotacao = $this->DB_COTACAO->get('cotacoes_produtos')->result_array();

        # Cabeçalho de cada produto
        $data = [
            "id_cotacao" => $cotacao['cd_cotacao'],
            "cnpj" => $cnpj,
            "cliente" => $cliente,
            "condicao_pagamento" => $this->forma_pagamento->findById($cotacao['cd_condicao_pagamento'])['descricao'],
            "cd_condicao_pgto" => $cotacao['cd_condicao_pagamento'],
            "data_inicio" => $cotacao['dt_inicio_cotacao'],
            "data_fim" => $cotacao['dt_fim_cotacao'],
            "Dt_Validade_Preco" => $cotacao['dt_validade_preco'],
            "Ds_Entrega" => $cotacao['ds_entrega'],
            "Ds_Filiais" => $cotacao['ds_filiais'],
            "Ds_Cotacao" => $cotacao['ds_cotacao'],
            "itens" => count($produtos_cotacao),
            "link" => "{$this->route}get_item_oncoprod/{$cotacao['cd_cotacao']}",
            "produtos" => []
        ];

        $produtos = [];

        $lista_fornecedores = implode(',', $this->oncoprod);

        # Obtem Fornecedor e estado
        $fornecedor =   "in ({$lista_fornecedores})";

        if ($this->session->id_fornecedor == 112) {
            if ($estado == 8) {
               $state = " = 9";
            } else {
                $state = " = {$estado}";
            }
        } else {

            $state = " = {$estado}";
        }

        # Query bruta que obtem os produtos encontrados com preço e estoque definido
        $produtos_encontrados = $this->encontrados->getProdutosByFornecedor($cd_cotacao, $fornecedor, $state);

        $fornecedores_encontrados = array_unique(array_column($produtos_encontrados, 'id_fornecedor'));
        
        $f_nencontrados = array_diff($this->oncoprod, $fornecedores_encontrados);

        if ( count($f_nencontrados) > 0 && !empty($produtos_encontrados) ) {

            $ids_nencontrados = implode(',', $f_nencontrados);

            $fornecedores_nao_encontrados = $this->fornecedor->find("*", "id in ({$ids_nencontrados})");

            $lista_itens = [];

            $codigos_itens = implode(',', array_unique(array_column($produtos_encontrados, 'codigo')));

            $items = [];

            foreach ($produtos_encontrados as $encontrado) {
               
                $items[] = [
                    'codigo' => $encontrado['codigo'],
                    'id_produto' => $encontrado['id_produto'],
                ];
            }

            $items = multi_unique($items);

            foreach ($fornecedores_nao_encontrados  as $f) {

                foreach ($items as $p) {

                    $this->db->where('id_fornecedor', $f['id']);
                    $this->db->where("codigo", $p['codigo']);
                    $this->db->where('ativo', 1);
                    $this->db->where('bloqueado', 0);
                    $pro = $this->db->get('produtos_catalogo')->row_array();

                    $desc = $pro['nome_comercial'] . ' - ' . ( !empty($pro['apresentacao']) ) ? $pro['apresentacao'] : $pro['descricao'];

                    # Obtem o estoque
                    $this->db->select("SUM(estoque) total");
                    $this->db->where('id_fornecedor', $f['id']);
                    $this->db->where('codigo', $p['codigo']);
                    $estoque = $this->db->get('produtos_lote')->row_array();

                    # Obtem o preço
                    $this->db->select("*");
                    $this->db->where('id_fornecedor', $f['id']);
                    $this->db->where('codigo', $p['codigo']);
                    $this->db->where("id_estado {$state}");
                    $this->db->where("data_criacao = (SELECT max(p1.data_criacao) from produtos_preco p1
                                    WHERE p1.id_fornecedor = {$f['id']}
                                    and p1.codigo = {$p['codigo']}
                                    and p1.id_estado {$state})
                    ");
                    $preco = $this->db->get('produtos_preco')->row_array();
               
                    $lista_itens[] = [
                        'cd_cotacao' => $cd_cotacao,
                        'ds_produto_comprador' => '',
                        'codigo' =>  $p['codigo'],
                        'id' => $pro['id'],
                        'produto_descricao' => $desc,
                        'marca' => $pro['marca'],
                        'quantidade_unidade' => $pro['quantidade_unidade'],
                        'id_marca' => $pro['id_marca'],
                        'id_produto' => $p['id_produto'],
                        'id_fornecedor' => $f['id'],
                        'estoque' => (isset($estoque) && !empty($estoque)) ? $estoque['total'] * $pro['quantidade_unidade'] : 0,
                        'preco_unidade' => $preco['preco_unitario']
                       
                    ];
                }
            }
            
            // $produtos_encontrados = $lista_itens;
            $produtos_encontrados = array_merge($produtos_encontrados, $lista_itens);
        } 

        # Agrupa os produtos encontrados com seus respectivo produto da cotação
        foreach ($produtos_cotacao as $produto) {

            $encont = [];
  
            foreach ($produtos_encontrados as $prod) {

                if ( $prod['id_produto']  == $produto['id_produto_sintese'] ) {

                    if ( empty($prod['ds_produto_comprador']) ) {

                        $prod['ds_produto_comprador'] = $produto['ds_produto_comprador'];
                    }

                    $encont[] = $prod;
                } 
            }

            $produtos[] = [
                'cotado' => $produto,
                'encontrados' => (!empty($encont)) ? $encont : [],
            ];
        }

        foreach ($produtos as $kk => $p) {
            $totalEstoque = 0;
            if (!empty($p['encontrados'])) {

                foreach ($p['encontrados'] as $kj => $pr) {

                    if (is_null($pr['estoque'])) {
                        $produtos[$kk]['encontrados'][$kj]['estoque'] = 0;
                    }

                    $produtos[$kk]['encontrados'][$kj]['nome_fantasia'] = $this->fornecedor->findById($pr['id_fornecedor'])['nome_fantasia'];

                    $totalEstoque += $pr['estoque'];

                    $pr['estoque'] = intval($pr['estoque']);

                    # Marca
                    if (isset($pr['id_marca']) && !empty($pr['id_marca'])) {

                        $marca = $this->marca->get_row($pr['id_marca'])['marca'];
                    } else {
                        $marca = "Sem De -> Para de Marca";
                    }

                    $produtos[$kk]['encontrados'][$kj]['marca'] = $marca;

                    # Venda Diferenciada por cliente
                    $venda_dif = $this->verificaVendaDiferenciada($pr['id_fornecedor'], $pr['codigo'], $cliente['id'], $estado);

                    $valor = 0;
                    if (isset($venda_dif) and !empty($venda_dif) && isset($pr['preco_unidade'])) {

                        $valor = $pr['preco_unidade'] - ($pr['preco_unidade'] * (floatval($venda_dif['desconto_percentual']) / 100));
                    } elseif ( !isset($pr['preco_unidade']) ) {

                        $valor = 0;
                    } else {
                        $valor = $pr['preco_unidade'];
                    }

                    $produtos[$kk]['encontrados'][$kj]['preco_unidade'] = $valor;

                    # enviado
                    $cotacoes_produto = $this->db->select("*")
                        ->where("id_fornecedor", $this->session->id_fornecedor)
                        ->where("filial", $pr['id_fornecedor'])
                        ->where("cd_cotacao", $cd_cotacao)
                        ->where("id_produto", $pr['id_produto'])
                        ->where("id_pfv", $pr['codigo'])
                        ->get('cotacoes_produtos')
                        ->row_array();

                    if ( !empty($cotacoes_produto) ) {

                        # Se o item for salvo, altera o preco para o ofertado.
                        if ( !empty($cotacoes_produto['preco_marca']) ) {
                            $produtos[$kk]['encontrados'][$kj]['preco_unidade'] = $cotacoes_produto['preco_marca'] * $cotacoes_produto['qtd_embalagem'];
                        }

                        # Observacao do produto
                        if ( !empty($cotacoes_produto['obs_produto']) ) {

                            if (  isset( explode(' - ', $cotacoes_produto['obs_produto'])[1] ) ) {
                                $produtos[$kk]['encontrados'][$kj]['obs'] = explode(' - ', $cotacoes_produto['obs_produto'])[1];
                            } else {
                                 $produtos[$kk]['encontrados'][$kj]['obs'] = $cotacoes_produto['obs_produto'];
                            }
                        }

                        if ($cotacoes_produto['submetido'] == 1) {

                            $produtos[$kk]['encontrados'][$kj]['enviado'] = 1;
                        } else {

                            $produtos[$kk]['encontrados'][$kj]['rascunho'] = 1;
                            $produtos[$kk]['encontrados'][$kj]['enviado'] = 0;
                        }
                    } else {

                        $produtos[$kk]['encontrados'][$kj]['enviado'] = 0;
                        $produtos[$kk]['encontrados'][$kj]['rascunho'] = 0;
                    }

                    # Sem estoque
                    $class = '';

                    if ($pr['estoque'] < 1) {

                        $class = 'table-danger';

                        // Consulta se existe registro
                        $consultar_sem_estoque = $this->db->select('id')
                            ->where('id_produto', $pr['id_produto'])
                            ->where('codigo', $pr['codigo'])
                            ->where('id_fornecedor', $pr['id_fornecedor'])
                            ->where('cd_cotacao', $cd_cotacao)
                            ->get('produtos_sem_estoque');

                        // Se não existir, armazena no array para registrar
                        if ($consultar_sem_estoque->num_rows() < 1) {

                            $sem_estoque[] = [
                                'id_produto' => $pr['id_produto'],
                                'codigo' => $pr['codigo'],
                                'id_fornecedor' => $pr['id_fornecedor'],
                                'cd_cotacao' => $cd_cotacao
                            ];
                        }
                    } else if ($pr['estoque'] >= $p['cotado']['qt_produto_total']) {
                        $class = 'table-success';
                    } else if ($pr['estoque'] > 0 && $pr['estoque'] < $p['cotado']['qt_produto_total']) {
                        $class = 'table-warning';
                    }

                    $produtos[$kk]['encontrados'][$kj]['class'] = $class;
                }
            } else {
                $produtos[$kk]['encontrados'] = null;
            }

            $produtos[$kk]['cotado']['encontrados'] = $totalEstoque;
        }

        # Se existir produtos com estoque 0, registra.
        if (!empty($sem_estoque)) {
            $this->db->insert_batch('produtos_sem_estoque', $sem_estoque);
        }

        $azuis = [];
        $verdes = [];
        $vermelhos = [];

        # Organiza array de produtos
        if (isset($produtos)) {

            foreach ($produtos as $kk => $p) {

                # Organiza os itens de um produto
                if (!empty($p['encontrados']) ) {

                    $prods = [];

                    $itens_fornecedor_logado_com_estoque = [];
                    $itens_fornecedor_logado_estoque_insuf = [];
                    $itens_fornecedor_logado_sem_estoque = [];
                    $itens_com_estoque = [];
                    $itens_com_estoque_insuf = [];
                    $itens_sem_estoque = [];

             
                    foreach ($p['encontrados'] as $kj => $item) {

                        if ( intval($item['estoque']) > 0 && intval($item['id_fornecedor']) == $this->session->id_fornecedor && intval($item['estoque']) >= $p['cotado']['qt_produto_total'] ) {
                            
                            $itens_fornecedor_logado_com_estoque[] = $item;
                        } elseif ( intval($item['estoque']) > 0 && intval($item['id_fornecedor']) == $this->session->id_fornecedor  && intval($item['estoque']) < $p['cotado']['qt_produto_total']) {

                            $itens_fornecedor_logado_estoque_insuf[] = $item;
                        } elseif ( intval($item['estoque']) == 0 && intval($item['id_fornecedor']) == $this->session->id_fornecedor ) {

                            $itens_fornecedor_logado_sem_estoque[] = $item;
                        } elseif (intval($item['estoque']) > 0 && intval($item['id_fornecedor']) != $this->session->id_fornecedor && intval($item['estoque']) >= $p['cotado']['qt_produto_total']) {
                            $itens_com_estoque[] = $item;
                        } else if ( intval($item['estoque']) > 0 && intval($item['id_fornecedor']) != $this->session->id_fornecedor && intval($item['estoque']) < $p['cotado']['qt_produto_total'] ) {
                            $itens_com_estoque_insuf[] = $item;
                        } else {
                            $itens_sem_estoque[] = $item;
                        }
                    }

                    // $p['encontrados'] = $prods;
                    $p['encontrados'] = array_merge($itens_fornecedor_logado_com_estoque, $itens_fornecedor_logado_estoque_insuf, $itens_fornecedor_logado_sem_estoque, $itens_com_estoque,  $itens_com_estoque_insuf, $itens_sem_estoque);
                }

                if ( isset($p['encontrados']) && in_array(1, array_column($p['encontrados'], 'enviado')) ) {
                    $azuis[] = $p;
                } elseif (isset($p['encontrados']) && intval($p['cotado']['encontrados']) > 0) {
                    $verdes[] = $p;
                } elseif(!isset($p['encontrados'])) {
                    $vermelhos[] = $p;
                } else {
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

        $data['produtos'] = array_merge($azuis, $verdes, $vermelhos);

        return $data;
    }

    /**
     * Envia cotação para sintese, versao oncoprod
     *o
     * @return  xml
     */
    public function enviar_resposta_oncoprod($rascunho = null)
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();
            $cotacao_atual = $this->session->userdata('cotacao_atual');

            $this->DB_COTACAO->select("*");
            $this->DB_COTACAO->where('cd_cotacao', $cotacao_atual);
            $this->DB_COTACAO->where('id_fornecedor', $this->session->id_fornecedor);
            $this->DB_COTACAO->group_by('id_produto_sintese, cd_produto_comprador');
            $cotacao_session = $this->DB_COTACAO->get('cotacoes_produtos')->result_array();

            if (!isset($post['produtos']) || empty($post['produtos'])) redirect($this->route);

            $cliente = $this->compradores->get_byCNPJ($post['cnpj_comprador']);

            $estado = $this->estado->find("*", "uf = '{$cliente['estado']}'", true);

            #valor minimo
            $valor_minimo = $this->valor_minimo->find("*", "id_cliente = {$cliente['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
            if (empty($valor_minimo)) {
                $valor_minimo = $this->valor_minimo->find("*", "id_estado = {$estado['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
            }
            $valor_minimo = $valor_minimo['valor_minimo'];

            #prazo entrega
            if ( isset($post['prazo_entrega']) && !empty($post['prazo_entrega']) ) {

                $prazo_entrega = $post['prazo_entrega'];
            } else {

                $prazo_entrega = $this->prazo_entrega->find("*", "id_cliente = {$cliente['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
                if (empty($prazo_entrega)) {
                    $prazo_entrega = $this->prazo_entrega->find("*", "id_estado = {$estado['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
                }
                $prazo_entrega = $prazo_entrega['prazo'];
            }

            #condição pagamento
            if ( isset($post['forma_pagto']) && !empty($post['forma_pagto']) ) {

                $forma_pagamento = $post['forma_pagto'];
            } else {
                $forma_pagamento = $this->forma_pagamento_fornecedor->find("*", "id_cliente = {$cliente['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
                if (empty($forma_pagamento)) {
                    $forma_pagamento = $this->forma_pagamento_fornecedor->find("*", "id_estado = {$estado['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
                }

                $forma_pagamento = $forma_pagamento['id_forma_pagamento'];
            }

            #validações
            if (!isset($forma_pagamento) || empty($forma_pagamento)) {

                $warning = ["type" => "warning", "message" => "É necessário configurar uma forma de pagamento válida, em regras de vendas -> formas de pagamento"];
            }

            if (!isset($valor_minimo) || empty($valor_minimo)) {

                $warning = ["type" => "warning", "message" => "É necessário configurar um valor mínimo, em regras de vendas -> valor minimo"];
            }

            if (!isset($prazo_entrega) || empty($prazo_entrega)) {

                $warning = ["type" => "warning", "message" => "É necessário configurar prazo de entregas, em regras de vendas -> prazo de entregas"];
            }

            if (isset($warning)) {
                $this->session->set_userdata('warning', $warning);
                redirect($_SERVER['HTTP_REFERER']);
            }

            # Salva somente os produtos
            if (isset($rascunho) && $rascunho == 1) {

                #busca os produtos
                $prods = $post['produtos'];

                $encontrados = [];
                foreach ($prods as $k => $prod) {
                    foreach ($prod as $p) {
                        if (isset($p['marcado']) and isset($p['preco_oferta'])) {

                            $this->db->select("*");
                            $this->db->where("codigo = {$p['codigo']} and id_fornecedor = {$p['filial']}");
                            $this->db->group_by('codigo');
                            $buscaProdutos = $this->db->get('produtos_catalogo')->row_array();

                            $buscaProdutos['id_produto'] = $p['id_produto'];

                            $buscaProdutos['filial'] = $p['filial'];
                            // $buscaProdutos['id_fornecedor'] = $p['id_fornecedor'];

                            if (isset($p['preco_oferta'])) {
                                $buscaProdutos['preco_unidade'] = dbNumberFormat($p['preco_oferta']);
                            }

                            if (isset($p['obs'])) {
                                $buscaProdutos['obs'] = $buscaProdutos['nome_comercial'] . ' - ' . $p['obs'];
                            } else {
                                $buscaProdutos['obs'] = $buscaProdutos['nome_comercial'];
                            }

                            $n = "";
                            foreach ($cotacao_session as $produto) {

                                if ($produto['id_produto_sintese'] == $buscaProdutos['id_produto']) {
                                    $buscaProdutos['qtd_solicitada'] = $produto['qt_produto_total'];

                                    $info = [
                                        'ds_produto_comprador' => $produto['ds_produto_comprador'],
                                        'qt_produto_total' => $produto['qt_produto_total'],
                                        'ds_unidade_compra' => $produto['ds_unidade_compra'],
                                    ];
                                }

                                if ( $k == str_replace('.', '', $produto['cd_produto_comprador']) ) {

                                    $n = $produto['cd_produto_comprador'];
                                }
                            }

                            $encontrados[] = [
                                "cd_comprador" => (!empty($n)) ? $n : $k,
                                "produto" => $buscaProdutos
                            ];
                        }
                    }
                }

                #separa os produtos por id_produto e marca
                $produtos_marcas = [];

                if (empty($encontrados)) {

                    # Remove todos os produtos já cadastrados
                    $this->db->where('id_fornecedor_logado', $this->session->id_fornecedor);
                    $this->db->where('cd_cotacao', $cotacao_atual);
                    $this->db->delete('cotacoes_produtos');

                    redirect("{$this->route}detalhes/{$cotacao_atual}");
                } 

                foreach ($encontrados as $encontrado) {

                    $produtos_marcas[$encontrado['produto']['id_produto']]['cd_comprador'] = $encontrado['cd_comprador'];
                    $produtos_marcas[$encontrado['produto']['id_produto']]['itens'][] = $encontrado['produto'];
                }

                # Remove todos os produtos já cadastrados
                $this->db->where('id_fornecedor_logado', $this->session->id_fornecedor);
                $this->db->where('cd_cotacao', $cotacao_atual);
                $this->db->delete('cotacoes_produtos');

                $cotacaoInsert = [];

                #produtos da cotação
                foreach ($produtos_marcas as $k => $prod) {

                    foreach ($prod['itens'] as $produto) {

                        #insere no banco de dados
                        $cotacaoInsert[] = [
                            "produto" => $produto['nome_comercial'] . " - " . $produto['apresentacao'],
                            "qtd_solicitada" => $produto['qtd_solicitada'],
                            "qtd_embalagem" => ($produto['quantidade_unidade'] == null || $produto['quantidade_unidade'] == '') ? 1 : $produto['quantidade_unidade'],
                            "id_produto" => $produto['id_produto'],
                            "preco_marca" => $produto['preco_unidade'],
                            "cd_cotacao" => $cotacao_atual,
                            "id_fornecedor" => $this->session->id_fornecedor,
                            "id_fornecedor_logado" => $this->session->id_fornecedor,
                            'filial' => $produto['filial'],
                            "data_cotacao" =>  (isset($post['data_cotacao']) && !empty($post['data_cotacao'])) ? dateFormat($post['data_cotacao'], 'Y-m-d H:i:s') : date('Y-m-d H:i:s'),
                            "id_forma_pagamento" => (isset($forma_pagamento)) ? $forma_pagamento : 1,
                            "prazo_entrega" => isset($prazo_entrega) ? $prazo_entrega : 0,
                            "valor_minimo" => isset($valor_minimo) ? $valor_minimo : 0.00,
                            "nivel" => "1",
                            "cnpj_comprador" => $post['cnpj_comprador'],
                            'cd_produto_comprador' => $prod['cd_comprador'],
                            "controle" => "1",
                            "submetido" => "0",
                            "id_cotacao" => time(),
                            "id_pfv" => $produto['codigo'],
                            "id_marca" => $produto['id_marca'],
                            "obs" => $post['obs'],
                            "obs_produto" => $produto['obs']
                        ];
                    }
                }

                $this->db->insert_batch('cotacoes_produtos', $cotacaoInsert);

                $warning = ['type' => 'success', 'message' => "Rascunho salvo com sucesso!"];

                $this->session->set_userdata("warning", $warning);
                
                redirect("{$this->route}detalhes/{$cotacao_atual}"); 
            } else {

                #busca os produtos
                $prods = $post['produtos'];

                $dadosEmail = [];

                $encontrados = [];
                foreach ($prods as $k => $prod) {
                    foreach ($prod as $p) {
                        if (isset($p['marcado']) and isset($p['preco_oferta'])) {

                            $this->db->select("*");
                            $this->db->where("codigo = {$p['codigo']} and id_fornecedor = {$p['filial']}");
                            $this->db->group_by('codigo');
                            $buscaProdutos = $this->db->get('produtos_catalogo')->row_array();

                            $buscaProdutos['id_produto'] = $p['id_produto'];

                            $buscaProdutos['filial'] = $p['filial'];

                            if (isset($p['preco_oferta'])) {
                                $buscaProdutos['preco_unidade'] = dbNumberFormat($p['preco_oferta']);
                            }
                            
                            if (isset($p['obs'])) {
                                $buscaProdutos['obs'] = $buscaProdutos['nome_comercial'] . ' - ' . $p['obs'];
                            } else {
                                $buscaProdutos['obs'] = $buscaProdutos['nome_comercial'];
                            }


                            $n = "";
                            foreach ($cotacao_session as $produto) {

                                if ($produto['id_produto_sintese'] == $buscaProdutos['id_produto']) {
                                    $buscaProdutos['qtd_solicitada'] = $produto['qt_produto_total'];

                                    $info = [
                                        'ds_produto_comprador' => $produto['ds_produto_comprador'],
                                        'qt_produto_total' => $produto['qt_produto_total'],
                                        'ds_unidade_compra' => $produto['ds_unidade_compra'],
                                    ];
                                }

                                if ( $k == str_replace('.', '', $produto['cd_produto_comprador']) ) {

                                    $n = $produto['cd_produto_comprador'];
                                }
                            }

                            $encontrados[] = [
                                "cd_comprador" => (!empty($n)) ? $n : $k,
                                "produto" => $buscaProdutos
                            ];

                            $dadosEmail[] =  [
                                "cd_comprador" => (!empty($n)) ? $n : $k,
                                'ds_produto_comprador' => $info['ds_produto_comprador'],
                                'qt_produto_total' => $info['qt_produto_total'],
                                'ds_unidade_compra' => $info['ds_unidade_compra'],
                                "produto" => $buscaProdutos
                            ];
                        }
                    }
                }

                #separa os produtos por id_produto e marca
                $produtos_marcas = [];

                $rowsEmail = [];

                if (empty($encontrados)) redirect($this->route);

                foreach ($encontrados as $encontrado) {

                    $produtos_marcas[$encontrado['produto']['id_produto']]['cd_comprador'] = $encontrado['cd_comprador'];
                    $produtos_marcas[$encontrado['produto']['id_produto']]['itens'][] = $encontrado['produto'];
                }

                foreach ($dadosEmail as $row) {

                    $rowsEmail[$row['produto']['id_produto']]['cd_comprador'] = $row['cd_comprador'];
                    $rowsEmail[$row['produto']['id_produto']]['ds_produto_comprador'] = $row['ds_produto_comprador'];
                    $rowsEmail[$row['produto']['id_produto']]['qt_produto_total'] = $row['qt_produto_total'];
                    $rowsEmail[$row['produto']['id_produto']]['ds_unidade_compra'] = $row['ds_unidade_compra'];
                    $rowsEmail[$row['produto']['id_produto']]['itens'][] = $row['produto'];
                }

                $dom = new DOMDocument("1.0", "ISO-8859-1");

                #gerar o codigo
                $dom->formatOutput = true;

                #criando o nó principal (root)
                $root = $dom->createElement("Cotacao");

                #informações do cabeçalho
                $root->appendChild($dom->createElement("Tp_Movimento", 'I'));
                $root->appendChild($dom->createElement("Dt_Gravacao", date("d/m/Y H:i:s", time())));
                $root->appendChild($dom->createElement("Cd_Fornecedor", preg_replace("/\D+/", "", $this->session->cnpj)));
                $root->appendChild($dom->createElement("Cd_Cotacao", $post['id_cotacao']));
                $root->appendChild($dom->createElement("Cd_Condicao_Pagamento", (isset($forma_pagamento) && !empty($forma_pagamento)) ? $forma_pagamento : '1'));
                $root->appendChild($dom->createElement("Nm_Usuario", 'PHARMANEXO'));

                if (isset($post['obs'])) {

                    // $root->appendChild($dom->createElement("Ds_Observacao", $post['obs']));
                    $root->appendChild($dom->createElement("Ds_Observacao_Fornecedor", utf8_encode($post['obs']) ));
                }
                $root->appendChild($dom->createElement("Qt_Prz_Minimo_Entrega", (isset($prazo_entrega) && !empty($prazo_entrega)) ? $prazo_entrega : '5'));
                $root->appendChild($dom->createElement("Vl_Minimo_Pedido", number_format($valor_minimo, 2, ',', '.')));

                $produtos = $dom->createElement("Produtos_Cotacao");


                # Remove produtos da cotação
                $this->db->where('id_fornecedor_logado', $this->session->id_fornecedor);
                $this->db->where('cd_cotacao', $cotacao_atual);
                $this->db->delete('cotacoes_produtos');

                $cotacaoInsert = [];

                $this->db->trans_begin();

                #produtos da cotação
                foreach ($produtos_marcas as $k => $prod) {

                    $produto_cotacao = $dom->createElement("Produto_Cotacao");

                    $id_produto_sintese = $dom->createElement("Id_Produto_Sintese", $k);
                    $cd_produto_comprador = $dom->createElement("Cd_Produto_Comprador", $prod['cd_comprador']);
                    $produto_cotacao->appendChild($id_produto_sintese);
                    $produto_cotacao->appendChild($cd_produto_comprador);

                    $marcas_ofertas = $dom->createElement("Marcas_Oferta");

                    foreach ($prod['itens'] as $produto) {

                        $marca_oferta = $dom->createElement("Marca_Oferta");

                        $id_marca = $dom->createElement("Id_Marca", $produto['id_marca']);
                        $ds_marca = $dom->createElement("Ds_Marca", $produto['marca']);
                        $qt_embalagem = $dom->createElement("Qt_Embalagem", $produto['quantidade_unidade']);
                        $pr_unidade = $dom->createElement("Vl_Preco_Produto", number_format($produto['preco_unidade'], 4, ',', '.'));

                        $cd_produto = $dom->createElement("Cd_ProdutoERP", $produto['codigo']);

                        $marca_oferta->appendChild($id_marca);
                        $marca_oferta->appendChild($ds_marca);
                        $marca_oferta->appendChild($qt_embalagem);
                        $marca_oferta->appendChild($pr_unidade);

                        $ds_obs_fornecedor = $dom->createElement("Ds_Obs_Oferta_Fornecedor", $produto['obs']);
                        $marca_oferta->appendChild($ds_obs_fornecedor);

                        $marca_oferta->appendChild($cd_produto);
                        $marcas_ofertas->appendChild($marca_oferta);

                        #insere no banco de dados
                        $cotacaoInsert[] = [
                            "produto" => $produto['nome_comercial'] . " - " . $produto['apresentacao'],
                            "qtd_solicitada" => $produto['qtd_solicitada'],
                            "qtd_embalagem" => ($produto['quantidade_unidade'] == null || $produto['quantidade_unidade'] == '') ? 1 : $produto['quantidade_unidade'],
                            "id_produto" => $produto['id_produto'],
                            "preco_marca" => $produto['preco_unidade'],
                            "cd_cotacao" => $cotacao_atual,
                            "id_fornecedor" => $this->session->id_fornecedor,
                            "id_fornecedor_logado" => $this->session->id_fornecedor,
                            "data_cotacao" => (isset($post['data_cotacao']) && !empty($post['data_cotacao'])) ? dateFormat($post['data_cotacao'], 'Y-m-d H:i:s') : date('Y-m-d H:i:s'),
                            'filial' => $produto['filial'],
                            "id_forma_pagamento" => (isset($forma_pagamento)) ? $forma_pagamento : 1,
                            "prazo_entrega" => isset($prazo_entrega) ? $prazo_entrega : 0,
                            "valor_minimo" => isset($valor_minimo) ? $valor_minimo : 0.00,
                            "nivel" => "1",
                            "cnpj_comprador" => $post['cnpj_comprador'],
                            'cd_produto_comprador' => $prod['cd_comprador'],
                            "controle" => "1",
                            "submetido" => "1",
                            "id_cotacao" => time(),
                            "id_pfv" => $produto['codigo'],
                            "id_marca" => $produto['id_marca'],
                            "obs" => $post['obs'],
                            "obs_produto" => $produto['obs']
                        ];
                    }

                    $produto_cotacao->appendChild($marcas_ofertas);
                    $produtos->appendChild($produto_cotacao);
                }

                $this->db->insert_batch('cotacoes_produtos', $cotacaoInsert);

                $root->appendChild($produtos);

                $dom->appendChild($root);

                #gerando nome do arquivo
                $cnpj_cliente = preg_replace("/\D+/", "", $cliente['cnpj']);
                $cnpj_fornecedor = preg_replace("/\D+/", "", $this->session->cnpj);

                #retirar os espacos em branco
                $dom->preserveWhiteSpace = false;
                # Para salvar o arquivo, descomente a linha

                $simpleXML = new SimpleXMLElement($dom->saveXML());

                $dom_xml = trim(str_replace("<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>", "", $simpleXML->asXML()));

                $filename = "public/cotacoes_enviadas/{$post['id_cotacao']}.xml";

                if ( file_exists($filename) ) {
                   unlink($filename);
                }

                $fl = fopen($filename, "w+");

                fwrite($fl, $simpleXML->asXML());

                fclose($fl);


                # envio de email para consultores, gerentes...
                $this->DB_COTACAO->where('cd_cotacao', $cotacao_atual);
                $this->DB_COTACAO->where('id_fornecedor', $this->session->id_fornecedor);
                $cot = $this->DB_COTACAO->get('cotacoes')->row_array();

                $dados = [
                    'cliente' => $cliente,
                    'observacao' => $post['obs'],
                    'condicao_pagamento' => $forma_pagamento,
                    'valor_minimo' =>  $valor_minimo,
                    'prazo_entrega' => $prazo_entrega,
                    'rows' => $rowsEmail
                ];

                $msg = $this->createBodyMessage($cot, $dados);


                # Cria o cabeçalho da cotação com o xml e o email
                $this->db->where('cd_cotacao', $cotacao_atual);
                $this->db->where('id_fornecedor', $this->session->id_fornecedor);

                if (  $this->db->get('cotacoes')->num_rows() < 1 ) {

                    $this->db->insert('cotacoes', [
                        'cd_cotacao' => $cotacao_atual,
                        'id_fornecedor' => $this->session->id_fornecedor,
                        'id_cliente' => $cliente['id'],
                        'valor_minimo' => $valor_minimo,
                        'prazo_entrega' => $prazo_entrega,
                        'id_forma_pagamento' => $forma_pagamento,
                        'nivel' => 1,
                        'notificacao' => $msg,
                        'xml' => $dom_xml,
                        'obs' => $post['obs']
                    ]);
                } else {

                    $this->db->where('cd_cotacao', $cotacao_atual);
                    $this->db->where('id_fornecedor', $this->session->id_fornecedor);
                    $this->db->update('cotacoes', [
                        'notificacao' => $msg,
                        'xml' => $dom_xml,
                        'obs' => $post['obs']
                    ]);
                }

                if ($this->db->trans_status() === FALSE) {

                    $warning = ['type' => 'warning', 'message' => 'Erro ao salvar cotação'];
                    $this->db->trans_rollback();

                  
                } else {
                    $warning = ['type' => 'success', 'message' => "Registrado com sucesso!"];

                    $this->db->trans_commit();
                }

                $warning = ['type' => 'success', 'message' => "Registrado com sucesso!"];

                $this->session->set_userdata("warning", $warning);

                redirect("{$this->route}"); 
            }
        }
    }

    /**
     * Cria o layout dos itens enviados da cotação ONCOPROD para mandar por e-mail
     *
     * @return  string
     */
    public function createBodyMessage($cotacao, $dados)
    {
        $i = 1;
        $rows = "";
        foreach ($dados['rows'] as $produto) {

            $row = "
            <table style='width:100%; border: 1px solid #dddddd'>
            <tr>
                <td style='border: 1px solid #dddddd' colspan='3'>{$i}. {$produto['ds_produto_comprador']}</td>
                <td style='border: 1px solid #dddddd' colspan='2'><strong>Qtde Solicitada:</strong> {$produto['qt_produto_total']}</td>
                <td style='border: 1px solid #dddddd' colspan='1'><strong>Und. Compra:</strong> {$produto['ds_unidade_compra']}</td>
            </tr>
            <tr>
                <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Cód. Kraft</th>
                <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Marca</th>
                <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Empresa</th>
                <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Embalagem</th>
                <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Preço</th>
                <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Descrição</th>
            </tr>
            ";

            foreach ($produto['itens'] as $item) {

                $fornecedor = $this->fornecedor->findById($item['id_fornecedor'])['nome_fantasia'];
                $marca = $this->marca->get_row($item['id_marca'])['marca'];
                $preco = number_format($item['preco_unidade'], 4, ",", ".");

                $row .=  "
                    <tr>
                        <td style='border: 1px solid #dddddd; padding-right: 20px'>{$item['codigo']}</td>
                        <td style='border: 1px solid #dddddd; padding-right: 20px'>{$marca}</td>
                        <td style='border: 1px solid #dddddd; padding-right: 20px'>{$fornecedor}</td>
                        <td style='border: 1px solid #dddddd; padding-right: 20px'>{$item['quantidade_unidade']}</td>
                        <td style='border: 1px solid #dddddd; padding-right: 20px'>{$preco}</td>
                        <td style='border: 1px solid #dddddd; padding-right: 20px'>{$item['descricao']}</td>
                    </tr>
                    <tr>
                        <td colspan='6' style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Observações: {$item['obs']}</td>
                    </tr>
                ";
            }

            $row .= "</table>";
            $rows .= $row;
            $i++;
        }

        $data_inicio = date('d/m/Y H:i:s', strtotime($cotacao['dt_inicio_cotacao']));
        $data_fim = date('d/m/Y H:i:s', strtotime($cotacao['dt_fim_cotacao']));
        $data_validade = date('d/m/Y', strtotime($cotacao['dt_validade_preco']));
        $data_envio = date('d/m/Y H:i');
        $valor_minimo = number_format($dados['valor_minimo'], 2, ",", ".");
        $condicao_pagamento = $this->forma_pagamento->findById($dados['condicao_pagamento'])['descricao'];

        $data = "
            <small>
                <p>
                    <label style='margin-right: 20px; font-size: 12px'><strong>Numero da Cotação:</strong> {$cotacao['cd_cotacao']}</label>
                    <label style='margin-right: 20px; font-size: 12px'><strong>Empresa:</strong> {$dados['cliente']['razao_social']}</label>
                    <label style='margin-right: 20px; font-size: 12px'><strong>Situação:</strong> Em Andamento </label>
                </p>
                <p>
                    <label style='margin-right: 20px; font-size: 12px'><strong>Data e Hora de Início:</strong> {$data_inicio} </label>
                    <label style='margin-right: 20px; font-size: 12px'><strong>Data e Hora de Término:</strong> {$data_fim} </label>
                    <label style='margin-right: 20px; font-size: 12px'><strong>Data de Validade:</strong> {$data_validade} </label>
                    <label style='margin-right: 20px; font-size: 12px'><strong>Data de Envio:</strong> {$data_envio} </label>
                </p>
                <hr>
                <strong>Condições de Pagamento: </strong> {$condicao_pagamento} <br>
                <strong>Valor mínimo do pedido por entrega (R$):</strong> {$valor_minimo} <br>
                <strong>Prazo de entrega (dias):</strong> {$dados['prazo_entrega']} <br>
                <strong>Observações:</strong> {$dados['observacao']} <br>
                <hr>
                
                {$rows}
               
            </small>
        ";

        return $data;
    }
}
