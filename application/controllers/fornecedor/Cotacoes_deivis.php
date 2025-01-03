<?php
date_default_timezone_set('America/Sao_Paulo');

class Cotacoes_deivis extends MY_Controller
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

        // $this->urlCliente = 'https://plataformasintese.com:444/IntegrationService.asmx?WSDL';
        // $this->client = new SoapClient($this->urlCliente);
        // $this->location = 'https://plataformasintese.com:444/IntegrationService.asmx';

        $this->load->model('m_cotacoes_produtos', 'cotacao');
        $this->load->model('m_venda_diferenciada', 'venda_dif');
        $this->load->model('m_forma_pagamento', 'forma_pagamento');
        $this->load->model('m_forma_pagamento_fornecedor', 'forma_pagamento_fornecedor');
        $this->load->model('m_prazo_entrega', 'prazo_entrega');
        $this->load->model('m_valor_minimo', 'valor_minimo');
        $this->load->model('m_usuarios', 'usuario');
        $this->load->model('produto_fornecedor_validade', 'pfv');
        $this->load->model('m_compradores', 'compradores');
        $this->load->model('admin/m_fornecedor', 'fornecedor');
        $this->load->model('m_estados', 'estado');
        $this->load->model('m_estoque', 'estoque');
        $this->load->model('produto_marca_sintese', 'pms');
        $this->load->model('m_encontrados_sintese', 'encontrados');

        $this->route = base_url('/fornecedor/cotacoes_deivis/');
        $this->views = 'fornecedor/cotacoes/';

        $this->oncoprod = [12, 112, 115, 120, 123];
        $this->DB_COTACAO = $this->load->database('sintese', TRUE);
    }

    public function index()
    {
        $page_title = "Cotações em aberto no Brasil";

        if (isset($_GET['uf'])) {
            $uf = strtoupper($_GET['uf']);
            $page_title = "Cotações em aberto no {$uf}";
        }else{
            $data = date('Y-m-d H:i:s', strtotime('-1hour'));
        }

        $url = $this->route . "get_cotacoes";
        if (isset($_GET['uf'])) {
            $url = $this->route . "get_cotacoes/{$_GET['uf']}";
        }

        $data = [
            'header' => $this->template->header(['title' => $page_title]),
            'navbar' => $this->template->navbar(),
            'sidebar' => $this->template->sidebar(),
            'heading' => $this->template->heading(['page_title' => $page_title,]),
            'scripts' => $this->template->scripts(
                []
            ),
            'url_cotacoes' => $url
        ];

        $this->load->view("{$this->views}/main", $data);
    }

    public function detalhes($id)
    {
        $page_title = "Cotação #{$id}";

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
                        'url' => (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '',
                        'class' => 'btn-secondary',
                        'icone' => 'fa-arrow-left',
                        'label' => 'Retornar'
                    ]
                ]
            ]),
            'scripts' => $this->template->scripts(
                []
            ),
            'url_cotacoes' => $this->route . "get_cotacoes"
        ];

        $data['url_historico'] = "{$this->route}get_historico";
        $data['cd_cotacao'] = $id;
        $data['id_fornecedor'] = $this->session->id_fornecedor;
        $data['url_findProduct'] = "{$this->route}findProduct/";

        if (in_array($this->session->id_fornecedor, $this->oncoprod)) {
            $data['descricao_codigo'] = 'Código Kraft';
            $data['descricao_produto'] = 'Descrição Padrão do Produto';
            $data['oncoprod'] = 1;
            $data['cotacao'] = $this->get_item_oncoprod($id);
            $data['form_action'] = "{$this->route}enviar_resposta_oncoprod";

            $_SESSION['cotacao_atual'] = $data['cotacao']['id_cotacao'];

            $this->load->view("{$this->views}/detalhes_oncoprod", $data);
        } else {
            $data['descricao_produto'] = 'Produto';
            $data['descricao_codigo'] = 'Código';
            $data['cotacao'] = $this->get_item($id);
            $data['form_action'] = "{$this->route}enviar_resposta";

            $_SESSION['cotacao_atual'] = $data['cotacao']['id_cotacao'];

            $this->load->view("{$this->views}/detalhes", $data);
        }
    }

    public function enviar_resposta()
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            $cotacao_atual = $this->session->userdata('cotacao_atual');

            $this->DB_COTACAO->select('*');
            $this->DB_COTACAO->where('cd_cotacao', $cotacao_atual);
            $this->DB_COTACAO->where('id_fornecedor', $this->session->id_fornecedor);
            $cotacao_session = $this->DB_COTACAO->get('cotacoes_produtos')->result_array();

            // $urlProdutos = "https://pharmanexo.com.br/pharma_api/API/Cotacoes/findProducts";
            // $cotacao_session = CallAPI('POST', $urlProdutos, ["id_fornecedor" => $this->session->id_fornecedor, "cd_cotacao" => $cotacao_atual]);

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
            $prazo_entrega = $this->prazo_entrega->find("*", "id_cliente = {$cliente['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
            if (empty($prazo_entrega)) {
                $prazo_entrega = $this->prazo_entrega->find("*", "id_estado = {$estado['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
            }
            $prazo_entrega = $prazo_entrega['prazo'];


            #condição pagamento
            $forma_pagamento = $this->forma_pagamento_fornecedor->find("*", "id_cliente = {$cliente['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
            if (empty($forma_pagamento)) {
                $forma_pagamento = $this->forma_pagamento_fornecedor->find("*", "id_estado = {$estado['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
            }
            $forma_pagamento = $forma_pagamento['id_forma_pagamento'];

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

            #busca os produtos
            $prods = $post['produtos'];

            $encontrados = [];
            foreach ($prods as $k => $prod) {
                foreach ($prod as $p) {
                    if (isset($p['id_sintese']) and isset($p['preco_oferta'])) {
                        $buscaProdutos = $this->db->select("*")->where("codigo = {$p['codigo']} and id_fornecedor = {$this->session->id_fornecedor}")->group_by('codigo')->get('vw_produtos_fornecedores_sintese')->row_array();

                        if (isset($p['preco_oferta'])) {
                            $buscaProdutos['preco_unidade'] = dbNumberFormat($p['preco_oferta']);
                        }
                        if (isset($p['obs'])) {
                            $buscaProdutos['obs'] = $p['obs'];
                        }

                        foreach ($cotacao_session as $produto) {

                            if ($produto['id_produto_sintese'] == $buscaProdutos['id_produto']) {
                                $buscaProdutos['qtd_solicitada'] = $produto['qt_produto_total'];
                            }
                        }

                        $encontrados[] = [
                            "cd_comprador" => $k,
                            "produto" => $buscaProdutos
                        ];
                    }
                }
            }

            #separa os produtos por id_produto e marca
            $produtos_marcas = [];

            if (empty($encontrados)) redirect($this->route);

            foreach ($encontrados as $encontrado) {

                $produtos_marcas[$encontrado['produto']['id_produto']]['cd_comprador'] = $encontrado['cd_comprador'];
                $produtos_marcas[$encontrado['produto']['id_produto']]['itens'][] = $encontrado['produto'];
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

                $root->appendChild($dom->createElement("Ds_Observacao", $post['obs']));
                $root->appendChild($dom->createElement("Ds_Observacao_Fornecedor", $post['obs']));
            }
            $root->appendChild($dom->createElement("Qt_Prz_Minimo_Entrega", (isset($prazo_entrega) && !empty($prazo_entrega)) ? $prazo_entrega : '5'));
            $root->appendChild($dom->createElement("Vl_Minimo_Pedido", number_format($valor_minimo, 2, ',', '.')));


            $produtos = $dom->createElement("Produtos_Cotacao");
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
                    if (isset($produto['obs']) && !empty($produto['obs'])) {
                        $ds_obs_fornecedor = $dom->createElement("Ds_Obs_Oferta_Fornecedor", $produto['obs']);
                        $marca_oferta->appendChild($ds_obs_fornecedor);
                    }

                    $marca_oferta->appendChild($cd_produto);


                    $marcas_ofertas->appendChild($marca_oferta);

                    $item = $this->cotacao->find("*", "id_pfv = {$produto['id']} AND id_fornecedor = {$this->session->id_fornecedor} AND cd_cotacao = '{$post['id_cotacao']}' and id_produto = {$produto['id_produto']} and id_sintese = {$produto['id_sintese']}");


                    if (!empty($item)) {

                        foreach ($item as $i) {

                            #insere no banco de dados
                            $cotacaoInsert = [
                                "id" => $i['id'],
                                "produto" => $i['produto'],
                                "qtd_solicitada" => $produto['qtd_solicitada'],
                                "qtd_embalagem" => $produto['qtd_embalagem'],
                                "id_sintese" => $produto['id_sintese'],
                                "id_produto" => $produto['id_produto'],
                                "preco_marca" => $produto['preco_unidade'],
                                "data_cotacao" => date('Y-m-d H:i:s', time()),
                                "id_fornecedor" => $this->session->id_fornecedor,
                                "id_forma_pagamento" => (isset($forma_pagamento)) ? $forma_pagamento : 1,
                                "prazo_entrega" => isset($prazo_entrega) ? $prazo_entrega : 0,
                                "valor_minimo" => isset($valor_minimo) ? $valor_minimo : 0.00,
                                "nivel" => "1",
                                "cnpj_comprador" => $post['cnpj_comprador'],
                                "submetido" => "1",
                                "id_cotacao" => time(),
                                "id_pfv" => $i['codigo'],
                            ];


                            $this->cotacao->update($cotacaoInsert);
                        }
                    } else {

                        #insere no banco de dados
                        $cotacaoInsert = [
                            "produto" => $produto['nome_comercial'] . " - " . $produto['apresentacao'],
                            "qtd_solicitada" => $produto['qtd_solicitada'],
                            "qtd_embalagem" => $produto['quantidade_unidade'],
                            "id_sintese" => $produto['id_sintese'],
                            "id_produto" => $produto['id_produto'],
                            "preco_marca" => $produto['preco_unidade'],
                            "data_cotacao" => date('Y-m-d H:i:s', time()),
                            "cd_cotacao" => $post['id_cotacao'],
                            "id_fornecedor" => $this->session->id_fornecedor,
                            "id_forma_pagamento" => (isset($forma_pagamento)) ? $forma_pagamento : 1,
                            "prazo_entrega" => isset($prazo_entrega) ? $prazo_entrega : 0,
                            "valor_minimo" => isset($valor_minimo) ? $valor_minimo : 0.00,
                            "nivel" => "1",
                            "cnpj_comprador" => $post['cnpj_comprador'],
                            "submetido" => "1",
                            "id_cotacao" => time(),
                            "id_pfv" => $produto['codigo'],
                        ];

                        $this->cotacao->insert($cotacaoInsert);

                    }
                }

                $produto_cotacao->appendChild($marcas_ofertas);
                $produtos->appendChild($produto_cotacao);
            }


            $root->appendChild($produtos);

            $dom->appendChild($root);

            #gerando nome do arquivo
            $cnpj_cliente = preg_replace("/\D+/", "", $cliente['cnpj']);
            $cnpj_fornecedor = preg_replace("/\D+/", "", $this->session->cnpj);

            $filename = "{$post['id_cotacao']}_{$cnpj_cliente}_{$cnpj_fornecedor}.xml";

            #retirar os espacos em branco
            $dom->preserveWhiteSpace = false;
            # Para salvar o arquivo, descomente a linha

            $simpleXML = new SimpleXMLElement($dom->saveXML());

            $dom_xml = trim(str_replace("<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>", "", $simpleXML->asXML()));

            $fl = fopen("public/cotacoes_enviadas/{$post['id_cotacao']}.xml", "w+");

            fwrite($fl, $simpleXML->asXML());

            fclose($fl);

            // $retorno = $this->sendSintese($dom_xml, $post['id_cotacao']);
            $message = (array)$retorno->EnviarOfertasResponse->EnviarOfertasResult;

            $warning = ['type' => 'success', 'message' => $message[0]];
            $this->session->set_userdata("warning", $warning);

            // redirect($_SERVER['HTTP_REFERER']);

            #$dom->save(COTACOES_PATH . $filename);
        }
    }

    public function get_item($cd_cotacao)
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
        $this->DB_COTACAO->where('cd_cotacao', $cd_cotacao);
        $this->DB_COTACAO->where('id_fornecedor', $this->session->id_fornecedor);
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

        $sem_estoque = [];

        foreach ($produtos_cotacao as $produto) {
            $prod = $produto;
            $prod['ds_complementar'] = isset($prod['ds_complementar']) ? $prod['ds_complementar'] : '';
            $where = '';

            $encontrados = [];

            if (isset($prod['id_produto_sintese']) && !empty($prod['id_produto_sintese'])) {

                $ids_sintese = $this->db->query("SELECT id_sintese FROM produtos_marca_sintese WHERE id_produto = {$prod['id_produto_sintese']} ")->result_array();
                $ids = [];

                if (!empty($ids_sintese)) {

                    foreach ($ids_sintese as $item) {
                        $ids[] = $item['id_sintese'];
                    }
                    $ids = implode(',', $ids);


                    if (!empty($ids)) {

                        $where .= "id_sintese in ({$ids}) AND ";
                    }

                    if ($this->session->has_userdata('id_fornecedor')) {
                        $where .= "id_fornecedor = {$this->session->id_fornecedor} AND ";
                    }

                    $where = rtrim($where, 'AND ');


                    $encontrados = $this->db->select("*")
                        ->where($where)
                        ->group_by('codigo, id_marca')
                        ->get('vw_produtos_fornecedores_sintese')
                        ->result_array();


                    foreach ($encontrados as $kk => $encontrado) {

                        $arrVend = [
                            "codigo" => $encontrado['codigo'],
                            "produto" => $encontrado['id_produto']
                        ];

                        if (isset($estado)) {
                            $estado_id = $estado;
                        } else {
                            $estado_id = 'is null';
                        }

                        // por cliente
                        $venda_dif = $this->verificaVendaDiferenciada($cliente['id'], $estado_id, $this->session->id_fornecedor, $arrVend);


                        // procura preço

                        $preco = $this->estoque->getPreco($encontrado['codigo'], $estado_id, $this->session->id_fornecedor);

                        if (empty($preco) || is_null($preco)) {
                            $preco = $this->estoque->getPreco($encontrado['codigo'], NULL, $this->session->id_fornecedor);
                        }

                        $encontrado['preco_unidade'] = $preco['preco_unitario'];


                        if (isset($venda_dif) and !empty($venda_dif)) {
                            $valor = floatval($encontrado['preco_unidade']);
                            $encontrados[$kk]['preco_unidade'] = $valor - ($valor * (floatval($venda_dif['desconto_percentual']) / 100));
                        } else {
                            $encontrados[$kk]['preco_unidade'] = floatval($encontrado['preco_unidade']);
                        }

                        $total = 0;

                        $lotes = $this->estoque->allLotes($encontrado['codigo'], $this->session->id_fornecedor);
                        foreach ($lotes as $lote) {
                            $total = $total + (intval($encontrado['quantidade_unidade'] * $lote['estoque']));
                        }
                        $encontrados[$kk]['estoque'] = $total;

                    }

                    #$encontrados = $this->pfv->get_itens("id, id_produto, codigo, id_sintese, validade, id_marca, marca, preco_unidade, estoque, quantidade_unidade", $where, 'preco_unidade ASC');

                    $estoqueCont = 0;

                    foreach ($encontrados as $j => $pp) {

                        $estoque = intval($pp['estoque']);

                        $encontrados[$j]['estoque'] = $estoque;

                        // Obtem os Produtos da cotação
                        $cotacoes_produtos = $this->db->select("id_pfv")
                            ->where("id_fornecedor", $this->session->id_fornecedor)
                            ->where("cd_cotacao", $cd_cotacao)
                            ->where("id_produto", $pp['id_produto'])
                            ->get('cotacoes_produtos')
                            ->result_array();

                        // Separa os id_pfv removendo as repetições
                        $listaId_pfv = array_unique(array_column($cotacoes_produtos, 'id_pfv'));

                        // Verifica se o id(equivalente a id_pfv) existe no array de id_pfv
                        if (in_array($pp['codigo'], $listaId_pfv)) {

                            $encontrados[$j]['enviado'] = 1;
                        } else {

                            $encontrados[$j]['enviado'] = 0;
                        }

                        $estoqueCont = (intval($estoqueCont) + $estoque);
                        $class = '';

                        if ($estoque < 1) {

                            $class = 'table-danger';

                            // Consulta se existe registro
                            $consultar_sem_estoque = $this->db->select('id')
                                ->where('id_produto', $pp['id_produto'])
                                ->where('codigo', $pp['codigo'])
                                ->where('id_fornecedor', $this->session->id_fornecedor)
                                ->where('cd_cotacao', $cd_cotacao)
                                ->get('produtos_sem_estoque');

                            // Se não existir, armazena no array para registrar
                            if ($consultar_sem_estoque->num_rows() < 1) {

                                $sem_estoque[] = [
                                    'id_produto' => $pp['id_produto'],
                                    'codigo' => $pp['codigo'],
                                    'id_fornecedor' => $this->session->id_fornecedor,
                                    'cd_cotacao' => $cd_cotacao
                                ];

                            }
                        } else if ($estoque >= $prod['qt_produto_total']) {
                            $class = 'table-success';
                        } else if ($estoque > 0 && $estoque < $prod['qt_produto_total']) {
                            $class = 'table-warning';
                        }

                        $encontrados[$j]['class'] = $class;
                    }

                    $prod['encontrados'] = $estoqueCont;
                }

            }

            $produtos[] = [
                'cotado' => $prod,
                'encontrados' => (isset($encontrados) && !empty($encontrados)) ? $encontrados : [],
            ];
        }

        if (isset($produtos)) {
            foreach ($produtos as $pro) {

                $prods = [];

                foreach ($pro['encontrados'] as $value) {

                    if (intval($value['estoque']) > 0) {
                        array_unshift($prods, $value);
                    } else {
                        array_push($prods, $value);
                    }
                }

                $pro['encontrados'] = $prods;

                if (isset($pro['cotado']['encontrados']) && intval($pro['cotado']['encontrados']) > 0) array_unshift($data['produtos'], $pro);
                else array_push($data['produtos'], $pro);
            }

            // Se existir produtos com estoque 0, registra.
            if (!empty($sem_estoque)) {
                $this->db->insert_batch('produtos_sem_estoque', $sem_estoque);

            }
        }

        return $data;
    }

    public function get_cotacoes($uf = null)
    {
        try {
            if (isset($uf)) {

                // $url = "https://pharmanexo.com.br/pharma_api/API/Cotacoes/allStates/1";

                // $xml = CallAPI('POST', $url, ["id_fornecedor" => $this->session->id_fornecedor, "state" => strtoupper($uf)]);

                $this->DB_COTACAO->where('uf_cotacao', strtoupper($uf));
                $this->DB_COTACAO->where('id_fornecedor', $this->session->id_fornecedor);
                $this->DB_COTACAO->where("date(dt_inicio_cotacao) > '2019-12-15'");
                $this->DB_COTACAO->order_by('oferta DESC');
                $this->DB_COTACAO->order_by('dt_fim_cotacao ASC');
                $cotacao = $this->DB_COTACAO->get('cotacoes')->result_array();

            } else {
                // $url = "https://pharmanexo.com.br/pharma_api/API/Cotacoes/all/1";

                // $xml = CallAPI('POST', $url, ["id_fornecedor" => $this->session->id_fornecedor]);

                $this->DB_COTACAO->where('id_fornecedor', $this->session->id_fornecedor);
                $this->DB_COTACAO->where("date(dt_inicio_cotacao) > '2019-12-15'");
                $this->DB_COTACAO->order_by('oferta DESC');
                $this->DB_COTACAO->order_by('dt_fim_cotacao ASC');
                $cotacao = $this->DB_COTACAO->get('cotacoes')->result_array();

            }

            $data = [];

            foreach ($cotacao as $response) {
                $item = $response;
                $cnpj = mask($item['cd_comprador'], '##.###.###/####-##');
                $cliente = $this->compradores->get_byCNPJ($cnpj);


                # busca produtos na api
                $urlProdutos = "https://pharmanexo.com.br/pharma_api/API/Cotacoes/findProducts/{$item['cd_cotacao']}";
                $produtos_cotacao = CallAPI('POST', $urlProdutos, ["id_fornecedor" => $this->session->id_fornecedor, "cd_cotacao" => $item['cd_cotacao']]);

                $cotacaoArray = [];
                if (!empty($cliente)) {
                    $cotacaoArray = [
                        "id_cotacao" => $item['cd_cotacao'],
                        "cnpj" => $cnpj,
                        "cliente" => $cliente,
                        "condicao_pagamento" => $this->forma_pagamento->findById($item['cd_condicao_pagamento'])['descricao'],
                        "data_inicio" => date("d/m/Y H:i", strtotime($item['dt_inicio_cotacao'])),
                        "data_fim" => date("d/m/Y H:i", strtotime($item['dt_fim_cotacao'])),
                        "Dt_Validade_Preco" => $item['dt_validade_preco'],
                        "Ds_Entrega" => (isset($item['ds_entrega'])) ? $item['ds_entrega'] : '',
                        "Ds_Filiais" => isset($item['ds_filiais']) ? $item['ds_filiais'] : '',
                        "Ds_Cotacao" => isset($item['ds_cotacao']) ? $item['ds_cotacao'] : '',
                        "itens" => count($produtos_cotacao),
                        "link" => "{$this->route}detalhes/{$item['cd_cotacao']}",
                        "bolinha" => isset($item['oferta']) ? $item['oferta'] : '',
                    ];

                    array_push($data, $cotacaoArray);
                }


            }
        } catch (Exception $e) {
            var_dump($e);
        }


        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    private function sendSintese($xml, $cd_cotacao)
    {
        $envio = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
        <soapenv:Header/>
        <soapenv:Body>
        <tem:EnviarOfertas>
        <tem:xmlDoc>
        ' . $xml . '
        </tem:xmlDoc>
        </tem:EnviarOfertas>
        </soapenv:Body>
        </soapenv:Envelope>';

        $soapUrl = $this->urlCliente; // asmx URL of WSDL
        // xml post structure

        $date = time();

        //criamos o arquivo
        $file = "public/cotacoes_enviadas/{$cd_cotacao}_{$date}.xml";
        $arquivo = fopen($file, 'w');
        fwrite($arquivo, $envio);
        //Fechamos o arquivo após escrever nele
        fclose($arquivo);


        // var_dump("para"); exit();

        $headers = array(
            "Host: plataformasintese.com:8085",
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: http://tempuri.org/EnviarOfertas",
            "Content-length: " . strlen($envio),
        ); //SOAPAction: your op URL


        $url = $soapUrl;

        // PHP cURL  for https connection with auth
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        # curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword); // username and password - declared at the top of the doc
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $envio); // the SOAP request
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // converting
        $response = curl_exec($ch);
        curl_close($ch);

        // converting
        $response1 = str_replace("<soap:Body>", "", $response);
        $response2 = str_replace("</soap:Body>", "", $response1);

        // convertingc to XML
        return simplexml_load_string($response2);
        // user $parser to get your data out of XML response and to display it.
    }

    public function verificaVendaDiferenciada($cliente, $estado, $fornecedor, $array)
    {
        $query = $this->db->select('*')
            ->from('vendas_diferenciadas')
            ->where('id_cliente', $cliente)
            ->where('id_fornecedor', $fornecedor)
            ->where_not_in('regra_venda', 2)
            ->group_start()
            ->where('id_produto', $array['produto'])
            ->or_where('codigo', $array['codigo'])
            ->group_end()
            ->limit(1)
            ->get()
            ->row_array();


        if (IS_NULL($query)) {
            $query = $this->db->select('*')
                ->from('vendas_diferenciadas')
                ->where('id_estado', $estado)
                ->where('id_fornecedor', $fornecedor)
                ->where_not_in('regra_venda', 2)
                ->group_start()
                ->where('id_produto', $array['produto'])
                ->or_where('codigo', $array['codigo'])
                ->group_end()
                ->limit(1)
                ->get()
                ->row_array();
        }

        return $query;
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
            $this->db->select("id, cd_cotacao, preco_marca, DATE_FORMAT(data_cotacao, '{$format}') as data");
            $this->db->where('id_fornecedor', $post['id_fornecedor']);
            $this->db->where('filial', $post['filial']);
            $this->db->where('id_produto', $post['id_produto']);
            // $this->db->group_by();
            $this->db->order_by("data_cotacao desc");
            $this->db->limit(6);
            $ofertas = $this->db->get('cotacoes_produtos');

            if ($ofertas->num_rows() > 0) {
                $data = $ofertas->result_array();
            } else {
                $data = 0;
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

            $post['id_usuario'] = $this->session->id_usuario;

            // var_dump($post); exit();

            if ($this->db->insert('produtos_fornecedores_sintese', $post)) {
                $warning = ['type' => 'success', 'message' => 'Registrado com sucesso'];
            } else {
                $warning = ['type' => 'warning', 'message' => 'Erro ao registrar'];
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
     * @param - int -id do cliente
     * @param - int - id do estado
     * @return  objeto
     */
    public function verificaVendaDiferenciada_oncoprod($fornecedor, $codigo, $cliente = null, $estado = null)
    {
        if (isset($cliente) && !isset($estado)) {

            $query = $this->db->select('*')
                ->from('vendas_diferenciadas')
                ->where('id_cliente', $cliente)
                ->where('id_fornecedor', $fornecedor)
                ->where('codigo', $codigo)
                ->where_not_in('regra_venda', 2)
                ->limit(1)
                ->get()
                ->row_array();
        } else {
            $query = $this->db->select('*')
                ->from('vendas_diferenciadas')
                ->where("id_estado {$estado}")
                ->where('id_fornecedor', $fornecedor)
                ->where('codigo', $codigo)
                ->where_not_in('regra_venda', 2)
                ->limit(1)
                ->get()
                ->row_array();
        }

        return $query;
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
        $this->DB_COTACAO->where('cd_cotacao', $cd_cotacao);
        $this->DB_COTACAO->where('id_fornecedor', $this->session->id_fornecedor);
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

        # Obtem Fornecedor
        if (in_array($this->session->id_fornecedor, $this->oncoprod)) {
            $fornecedor = implode(',', $this->oncoprod);
        } else {
            $fornecedor = $this->session->id_fornecedor;
        }

        # Query bruta que obtem os produtos encontrados com preço e estoque definido
        $produtos_encontrados = $this->encontrados->getProdutosByFornecedor($cd_cotacao, $fornecedor, $estado);

        # Agrupa os produtos encontrados com seus respectivo produto da cotação
        foreach ($produtos_cotacao as $produto) {

            $encont = [];
            foreach ($produtos_encontrados as $prod) {
                if ($produto['id_produto_sintese'] == $prod['id_produto']) {
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

                    $produtos[$kk]['encontrados'][$kj]['nome_fantasia'] = $this->fornecedor->findById($pr['id_fornecedor'])['nome_fantasia'];

                    $totalEstoque += $pr['estoque'];

                    $pr['estoque'] = intval($pr['estoque']);

                    # Venda Diferenciada por cliente
                    $venda_dif = $this->verificaVendaDiferenciada_oncoprod($pr['id_fornecedor'], $pr['codigo'], $cliente['id']);

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
                    $cotacoes_produtos = $this->db->select("id_pfv")
                        ->where("id_fornecedor", $this->session->id_fornecedor)
                        ->where("filial", $pr['id_fornecedor'])
                        ->where("cd_cotacao", $cd_cotacao)
                        ->where("id_produto", $pr['id_produto'])
                        ->get('cotacoes_produtos')
                        ->result_array();

                    // Separa os id_pfv removendo as repetições
                    $listaId_pfv = array_unique(array_column($cotacoes_produtos, 'id_pfv'));

                    // Verifica se o id(equivalente a id_pfv) existe no array de id_pfv
                    if (in_array($pr['codigo'], $listaId_pfv)) {

                        $produtos[$kk]['encontrados'][$kj]['enviado'] = 1;
                    } else {

                        $produtos[$kk]['encontrados'][$kj]['enviado'] = 0;
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
            }

            $produtos[$kk]['cotado']['encontrados'] = $totalEstoque;
        }

        # Se existir produtos com estoque 0, registra.
        if (!empty($sem_estoque)) {
            $this->db->insert_batch('produtos_sem_estoque', $sem_estoque);

        }

        # Organiza array de produtos
        if (isset($produtos)) {

            $prod = [];
            foreach ($produtos as $kk => $p) {

                # Organiza os itens de um produto
                if (!empty($p['encontrados']) ) {

                    $prods = [];
             
                    foreach ($p['encontrados'] as $kj => $item) {

                        if (intval($item['estoque']) > 0 && intval($item['id_fornecedor']) == $this->session->id_fornecedor) {
                            array_unshift($prods, $item);
                        } else {
                            array_push($prods, $item);
                        }
                    }

                    $p['encontrados'] = $prods;
                }


                # Ordena os produtos por  existir itens encontrados
                if (isset($p['cotado']['encontrados']) && intval($p['cotado']['encontrados']) > 0) {

                    array_unshift($prod, $p);
                } else {

                   array_push($prod, $p);
                }   
            }
        }


        $data['produtos'] = $prod;

        return $data;
    }

    /**
     * Envia cotação para sintese, versao oncoprod
     *o
     * @return  xml
     */
    public function enviar_resposta_oncoprod()
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            $cotacao_atual = $this->session->userdata('cotacao_atual');

            $this->DB_COTACAO->select('*');
            $this->DB_COTACAO->where('cd_cotacao', $cotacao_atual);
            $this->DB_COTACAO->where('id_fornecedor', $this->session->id_fornecedor);
            $cotacao_session = $this->DB_COTACAO->get('cotacoes_produtos')->result_array();


            // $urlProdutos = "https://pharmanexo.com.br/pharma_api/API/Cotacoes/findProducts";
            // $cotacao_session = CallAPI('POST', $urlProdutos, ["id_fornecedor" => $this->session->id_fornecedor, "cd_cotacao" => $cotacao_atual]);

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
            $prazo_entrega = $this->prazo_entrega->find("*", "id_cliente = {$cliente['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
            if (empty($prazo_entrega)) {
                $prazo_entrega = $this->prazo_entrega->find("*", "id_estado = {$estado['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
            }
            $prazo_entrega = $prazo_entrega['prazo'];

            #condição pagamento
            $forma_pagamento = $this->forma_pagamento_fornecedor->find("*", "id_cliente = {$cliente['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
            if (empty($forma_pagamento)) {
                $forma_pagamento = $this->forma_pagamento_fornecedor->find("*", "id_estado = {$estado['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
            }
            $forma_pagamento = $forma_pagamento['id_forma_pagamento'];


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

            #busca os produtos
            $prods = $post['produtos'];

            $encontrados = [];
            foreach ($prods as $k => $prod) {
                foreach ($prod as $p) {
                    if (isset($p['marcado']) and isset($p['preco_oferta'])) {
                        $this->db->select("*");
                        $this->db->where("codigo = {$p['codigo']} and id_fornecedor = {$p['id_fornecedor']}");
                        $this->db->group_by('codigo');
                        $buscaProdutos = $this->db->get('produtos_catalogo')->row_array();

                        $buscaProdutos['id_produto'] = $p['id_produto'];

                        if (isset($p['preco_oferta'])) {
                            $buscaProdutos['preco_unidade'] = dbNumberFormat($p['preco_oferta']);
                        }
                        if (isset($p['obs'])) {
                            $buscaProdutos['obs'] = $p['obs'];
                        }

                        foreach ($cotacao_session as $produto) {
                            if ($produto['id_produto_sintese'] == $buscaProdutos['id_produto']) {
                                $buscaProdutos['qtd_solicitada'] = $produto['qt_produto_total'];
                            }
                        }

                        $encontrados[] = [
                            "cd_comprador" => $k,
                            "produto" => $buscaProdutos
                        ];
                    }
                }
            }

            #separa os produtos por id_produto e marca
            $produtos_marcas = [];

            #var_dump($encontrados);exit();

            if (empty($encontrados)) redirect($this->route);

            foreach ($encontrados as $encontrado) {

                // $getIdProduto = $this->db->query("SELECT id_produto FROM produtos_marca_sintese WHERE id_sintese = {$encontrado['produto']['id_sintese']}")->row_array();

                $produtos_marcas[$encontrado['produto']['id_produto']]['cd_comprador'] = $encontrado['cd_comprador'];
                $produtos_marcas[$encontrado['produto']['id_produto']]['itens'][] = $encontrado['produto'];
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

                $root->appendChild($dom->createElement("Ds_Observacao", $post['obs']));
                $root->appendChild($dom->createElement("Ds_Observacao_Fornecedor", $post['obs']));
            }
            $root->appendChild($dom->createElement("Qt_Prz_Minimo_Entrega", (isset($prazo_entrega) && !empty($prazo_entrega)) ? $prazo_entrega : '5'));
            $root->appendChild($dom->createElement("Vl_Minimo_Pedido", number_format($valor_minimo, 2, ',', '.')));


            $produtos = $dom->createElement("Produtos_Cotacao");
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
                    if (isset($produto['obs']) && !empty($produto['obs'])) {
                        $ds_obs_fornecedor = $dom->createElement("Ds_Obs_Oferta_Fornecedor", $produto['obs']);
                        $marca_oferta->appendChild($ds_obs_fornecedor);
                    }

                    $marca_oferta->appendChild($cd_produto);


                    $marcas_ofertas->appendChild($marca_oferta);

                    $item = $this->cotacao->find("*", "id_pfv = {$produto['id']} AND id_fornecedor = {$this->session->id_fornecedor} AND filial = {$produto['id_fornecedor']} AND cd_cotacao = '{$post['id_cotacao']}' and id_produto = {$produto['id_produto']}");


                    if (!empty($item)) {

                        foreach ($item as $i) {

                            #insere no banco de dados
                            $cotacaoInsert = [
                                "id" => $i['id'],
                                "produto" => $i['produto'],
                                "qtd_solicitada" => $produto['qtd_solicitada'],
                                "qtd_embalagem" => $produto['qtd_embalagem'],
                                // "id_sintese" => $produto['id_sintese'],
                                "id_produto" => $produto['id_produto'],
                                "preco_marca" => $produto['preco_unidade'],
                                "data_cotacao" => date('Y-m-d H:i:s', time()),
                                "id_fornecedor" => $this->session->id_fornecedor,
                                'filial' => $produto['id_fornecedor'],
                                "id_forma_pagamento" => (isset($forma_pagamento)) ? $forma_pagamento : 1,
                                "prazo_entrega" => isset($prazo_entrega) ? $prazo_entrega : 0,
                                "valor_minimo" => isset($valor_minimo) ? $valor_minimo : 0.00,
                                "nivel" => "1",
                                "cnpj_comprador" => $post['cnpj_comprador'],
                                "submetido" => "1",
                                "id_cotacao" => time(),
                                "id_pfv" => $i['codigo'],
                            ];

                            $this->cotacao->update($cotacaoInsert);
                        }
                    } else {

                        #insere no banco de dados
                        $cotacaoInsert = [
                            "produto" => $produto['nome_comercial'] . " - " . $produto['apresentacao'],
                            "qtd_solicitada" => $produto['qtd_solicitada'],
                            "qtd_embalagem" => $produto['quantidade_unidade'],
                            // "id_sintese" => $produto['id_sintese'],
                            "id_produto" => $produto['id_produto'],
                            "preco_marca" => $produto['preco_unidade'],
                            "data_cotacao" => date('Y-m-d H:i:s', time()),
                            "cd_cotacao" => $post['id_cotacao'],
                            "id_fornecedor" => $this->session->id_fornecedor,
                            'filial' => $produto['id_fornecedor'],
                            "id_forma_pagamento" => (isset($forma_pagamento)) ? $forma_pagamento : 1,
                            "prazo_entrega" => isset($prazo_entrega) ? $prazo_entrega : 0,
                            "valor_minimo" => isset($valor_minimo) ? $valor_minimo : 0.00,
                            "nivel" => "1",
                            "cnpj_comprador" => $post['cnpj_comprador'],
                            "submetido" => "1",
                            "id_cotacao" => time(),
                            "id_pfv" => $produto['codigo'],
                        ];

                        $this->cotacao->insert($cotacaoInsert);
                    }
                }

                $produto_cotacao->appendChild($marcas_ofertas);
                $produtos->appendChild($produto_cotacao);
            }


            $root->appendChild($produtos);

            $dom->appendChild($root);

            #gerando nome do arquivo
            $cnpj_cliente = preg_replace("/\D+/", "", $cliente['cnpj']);
            $cnpj_fornecedor = preg_replace("/\D+/", "", $this->session->cnpj);

            $filename = "{$post['id_cotacao']}_{$cnpj_cliente}_{$cnpj_fornecedor}.xml";

            #retirar os espacos em branco
            $dom->preserveWhiteSpace = false;
            # Para salvar o arquivo, descomente a linha

            $simpleXML = new SimpleXMLElement($dom->saveXML());

            $dom_xml = trim(str_replace("<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>", "", $simpleXML->asXML()));

            $fl = fopen("public/cotacoes_enviadas/{$post['id_cotacao']}.xml", "w+");

            fwrite($fl, $simpleXML->asXML());

            fclose($fl);

            $retorno = $this->sendSintese($dom_xml, $post['id_cotacao']);
            $message = (array)$retorno->EnviarOfertasResponse->EnviarOfertasResult;

        /*    $warning = ['type' => 'success', 'message' => $message[0]];
            $this->session->set_userdata("warning", $warning);*/

            redirect($_SERVER['HTTP_REFERER']);

            #$dom->save(COTACOES_PATH . $filename);
        }
    }
}
