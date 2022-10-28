<?php


class AutomaticBionexoHMG extends CI_Controller
{
    /**
     * @author : Chule Cabral
     * Data: 01/11/2020
     *
     * Crontab => 30 7-23 * * * wget https://pharmanexo.com.br/pharma_api/API/AutomaticBionexo
     */

    private $configs;
    private $bio;
    private $logs;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('AutomaticsEngine', 'Engine');

        $this->bio = $this->load->database('bionexo', TRUE);

        $this->configs =
            [
                'integrador' => 'BIONEXO',
                'IdIntegrador' => '2',
                'fornecedorById' =>
                    [
                        'status' => false,
                        'id' => 20
                    ],
                'cotacaoById' =>
                    [
                        'status' => false,
                        'cd_cotacao' => "192092140",
                    ],
                'checkDataFimCotacao' => TRUE,
                'checkEnabledAuto' => TRUE,
                'checkPrdCotRestriction' => TRUE,
                'checkVendaDif' => TRUE,
                'checkValorMinimo' => TRUE,
                'checkFormaPagamento' => TRUE,
                'checkPrazoEntrega' => TRUE,
                'checkPrdRestriction' => TRUE,
                'checkClientRestriction' => TRUE,
                'checkPrdStock' => TRUE,
                'checkPrdSent' => TRUE,
                'checkValorTotalCot' => false,
                'setDescontoFinal' => TRUE,
                'submitBionexo' => TRUE,
                'sendEmail' => TRUE,
                'sendEmailAnexo' => TRUE,
                'sendEmailDestiny' => TRUE,
                'saveProdsOferta' => TRUE,
                'saveLogs' => TRUE
            ];
    }

    private function getFornecedores()
    {
        $data = [
            "MILLENIUM_ES" =>
                [
                    'id_fornecedor' => 5032,
                    'user' => 'ws_millenium_es',
                    'password' => '1h8pxwla'
                ],
            "MILLENIUM_RJ" =>
                [
                    'id_fornecedor' => 5033,
                    'user' => 'ws_millenium_rj',
                    'password' => '3fwnt1tt'
                ],
            "HOSPIDROGAS" =>
                [
                    'id_fornecedor' => 20,
                    'user' => 'ws_hospidrogas_pharm',
                    'password' => '3fjwk3dm'
                ],
        ];

        if ($this->configs['fornecedorById']['status']) {

            $id = intval($this->configs['fornecedorById']['id']);

            //foreach (loginBionexo() as $key => $fornecedor) {
            foreach ($data as $key => $fornecedor) {

                if ($fornecedor['id_fornecedor'] === $id) {

                    return
                        [
                            'status' => TRUE,
                            'result' => [$key => $fornecedor]
                        ];
                }
            }
            return ['status' => FALSE];
        }

        return
            [
                'status' => TRUE,
                'result' => $data
            ];
    }

    private function getProdsCots($params)
    {

        $result = $this->bio->where('id_cotacao', $params['id_cotacao'])
            #->where_in('id_categoria', [100, 200, 300, 700, 1500])
            ->get('cotacoes_produtos')
            ->result_array();

        if (empty($result))
            return ['status' => FALSE];

        return
            [
                'status' => TRUE,
                'result' => $result
            ];
    }

    private function getFormaPagamento($params)
    {

        $bool = TRUE;

        $cd_forma_pagamento = 5;

        if ($this->configs['checkFormaPagamento']) {

            $checkFormaPagamento = $this->db->where('id_fornecedor', $params['id_fornecedor'])
                ->where('id_cliente', $params['id_cliente'])
                ->limit(1)
                ->get('formas_pagamento_fornecedores')
                ->row_array()['id_forma_pagamento'];

            if (empty($checkFormaPagamento)) {
                $checkFormaPagamento = $this->db->where('id_fornecedor', $params['id_fornecedor'])
                    ->where('id_estado', $params['id_estado'])
                    ->limit(1)
                    ->get('formas_pagamento_fornecedores')
                    ->row_array()['id_forma_pagamento'];
            }


            if (IS_NULL($checkFormaPagamento))
                return ['status' => FALSE];

            $result = $this->db->where('integrador', 2)
                ->where('ativo', 1)
                ->where('id_forma_pagamento', $checkFormaPagamento)
                ->limit(1)
                ->get('formas_pagamento_depara')
                ->row_array()['cd_forma_pagamento'];

            if (IS_NULL($result)) {

                $bool = FALSE;

            } else {

                $cd_forma_pagamento = $result;
            }
        }

        $arrResult =
            ['cd_forma_pagamento' => $cd_forma_pagamento];


        return
            [
                'status' => $bool,
                'result' => $arrResult
            ];
    }

    private function checkProductSent($params)
    {
        if ($this->configs['checkPrdSent']) {

            /**
             * Verifica se o produto da cotação já foi enviado.
             * Se já foi enviado, a automática não envia novamente.
             */

            $result = $this->db->where('id_fornecedor', $params['id_fornecedor'])
                ->where('cd_cotacao', $params['cd_cotacao'])
                ->where('cd_produto_comprador', $params['cd_produto_comprador'])
                ->where('integrador', "BIONEXO")
                ->limit(1)
                ->get('cotacoes_produtos')
                ->row_array();

            if (IS_NULL($result))
                return FALSE;

            return TRUE;
        }
        return FALSE;
    }

    private function getProdMenorPreco($produtos)
    {

        $sortArr = [];

        foreach ($produtos as $key => $produto)
            $sortArr[$key] = $produto['preco_oferta'];

        asort($sortArr);

        foreach ($sortArr as $key => $value)
            return $produtos[$key];
    }

    private function prodsEncontrados($params, $prodsCotacao)
    {
        $produtos = [];

        foreach ($prodsCotacao as $key => $prod) {

            $params = array_merge($params,
                [
                    'cd_produto_comprador' => $prod['cd_produto_comprador'],
                    'ds_produto_comprador' => $prod['ds_produto_comprador'],
                    'qt_produto_total' => $prod['qt_produto_total'],
                    'ds_unidade_compra' => $prod['ds_unidade_compra']
                ]);

            $this->logs['PRODS-COT'][$key] =

                [
                    'cd_produto_comprador' => $params['cd_produto_comprador'],
                    'ds_produto_comprador' => $params['ds_produto_comprador']
                ];

            if ($this->checkProductSent($params)) {

                $this->logs['PRODS-COT'][$key]['productSent'] = TRUE;

                continue;
            }

            $resultIdsProdutos = $this->db->select('id_produto_sintese')
                ->where('cd_produto', $params['cd_produto_comprador'])
                ->where('id_cliente', $params['id_cliente'])
                ->get('produtos_clientes_depara')
                ->result_array();

            if (empty($resultIdsProdutos)) {

                $this->logs['PRODS-COT'][$key]['produtos_clientes_depara'] = FALSE;

                continue;
            }

            $ids_produto = [];

            foreach ($resultIdsProdutos as $value) {

                $id_produto = intval($value['id_produto_sintese']);

                if (!in_array($id_produto, $ids_produto))
                    array_push($ids_produto, $id_produto);

            }

            $resultIdsSintese = $this->db->select('id_sintese')
                ->where_in('id_produto', $ids_produto)
                ->get('produtos_marca_sintese')
                ->result_array();

            if (empty($resultIdsSintese)) {

                $this->logs['PRODS-COT'][$key]['produtos_marca_sintese'] = FALSE;

                continue;
            }

            $ids_sintese = [];

            foreach ($resultIdsSintese as $value) {

                $id_sintese = intval($value['id_sintese']);

                if (!in_array($id_sintese, $ids_sintese))
                    array_push($ids_sintese, $id_sintese);

            }

            $select = "cat.codigo, cat.descricao, cat.apresentacao, cat.unidade,
					   cat.nome_comercial, cat.marca, cat.quantidade_unidade";

            $resultDepara = $this->db->select($select)
                ->distinct()
                ->where_in('pfs.id_sintese', $ids_sintese)
                ->where('pfs.id_fornecedor', $params['id_fornecedor'])
                ->where('cat.ativo', 1)
                ->where('cat.bloqueado', 0)
                ->from('produtos_fornecedores_sintese AS pfs')
                ->join('produtos_catalogo AS cat', 'cat.codigo = pfs.cd_produto AND cat.id_fornecedor = pfs.id_fornecedor')
                ->get()
                ->result_array();

            if (empty($resultDepara)) {

                $this->logs['PRODS-COT'][$key]['produtos_fornecedores_sintese'] = FALSE;

                continue;
            }

            $newArr = [];

            foreach ($resultDepara as $keyProd => $value) {

                $params = array_merge($params,
                    [
                        'codigo' => intval($value['codigo']),
                        'qtd_unidade' => IS_NULL($value['quantidade_unidade']) ? 1 : intval($value['quantidade_unidade'])
                    ]);

                $this->logs['PRODS-COT'][$key]['produtos_fornecedor'][$keyProd] =
                    [
                        'codigo' => $params['codigo'],
                        'qtd_unidade' => $params['qtd_unidade'],
                        'descricao' => $value['descricao'],
                        'apresentacao' => $value['apresentacao'],
                        'nome_comercial' => $value['nome_comercial'],
                        'marca' => $value['marca']
                    ];

                $checkVendDif = $this->Engine->vendaDif($params, $this->configs);

                if (!$checkVendDif['status']) {

                    $this->logs['PRODS-COT'][$key]['produtos_fornecedor'][$keyProd]
                    ['restricao']['vendaDif'] = FALSE;

                    continue;
                }

                $desconto_percentual = floatval($checkVendDif['result']['desconto_percentual']);

                if ($this->Engine->productRestriction($params, $this->configs)) {

                    $this->logs['PRODS-COT'][$key]['produtos_fornecedor'][$keyProd]
                    ['restricao']['productRestriction'] = TRUE;

                    continue;
                }

                $params = array_merge($params, ['desconto_percentual' => $desconto_percentual]);

                $qtd_solicitada = $params['qt_produto_total'];

                $checkEstoque = $this->Engine->getEstoque($params, $this->configs);

                if (!$checkEstoque['status']) {

                    $this->logs['PRODS-COT'][$key]['produtos_fornecedor'][$keyProd]
                    ['restricao']['productStock'] = FALSE;

                    continue;
                }

                $estoque_unidade = ($checkEstoque['result']['total'] * intval($params['qtd_unidade']));

                $validade = $checkEstoque['result']['validade'];

                $obsProd = "";

                if (isset($params['confValidade'])) {

                    if (boolval($params['confValidade']))
                        $obsProd = "Validade: {$validade}";
                }

                $qtd_aceitavel = $qtd_solicitada;

                if (!IS_NULL($params['margem_estoque']))
                    $qtd_aceitavel = (floatval($params['margem_estoque']) / 100) * $qtd_solicitada;

                if ($qtd_aceitavel > $estoque_unidade) {
                    $obsProd .= " - Produto atendido parcialmente!";
                }

                $checkPrice = $this->Engine->getPriceProd($params, $this->configs);

                if (!$checkPrice['status']) {

                    $this->logs['PRODS-COT'][$key]['produtos_fornecedor'][$keyProd]
                    ['restricao']['productPrice'] = FALSE;

                    continue;
                }


                $arr =
                    [
                        'codigo' => $params['codigo'],
                        'id_artigo' => $prod['id_artigo'],
                        'sequencia' => $prod['sequencia'],
                        'qtd_solicitada' => $qtd_solicitada,
                        'ds_unidade_compra' => $prod['ds_unidade_compra'],
                        'descricao' => $value['descricao'],
                        'apresentacao' => $value['apresentacao'],
                        'nome_comercial' => $value['nome_comercial'],
                        'marca' => $value['marca'],
                        'unidade' => $value['unidade'],
                        'validade' => $validade,
                        'qtd_unidade' => $params['qtd_unidade'],
                        'estoque' => $checkEstoque['result']['total'],
                        'estoque_unidade' => $estoque_unidade,
                        'desconto_padrao' => $params['desconto_padrao'],
                        'desconto_vendaDif' => $params['desconto_percentual'],
                        'tabela_precos' => $checkPrice['priceTabela'],
                        'preco_unitario' => $checkPrice['priceTabela'],
                        'preco_oferta' => $checkPrice['priceOferta'],
                        'tipo_desconto_aplicado' => $checkPrice['tipoDesconto'],
                        'vl_desconto_aplicado' => $checkPrice['descontoAplicado'],
                        'vl_desconto_final' => $checkPrice['descontoAplicado'],
                        'obs_produto' => $obsProd
                    ];

                $newArr[] = $arr;

                $this->logs['PRODS-COT'][$key]['produtos_fornecedor'][$keyProd] = $arr;

                $this->logs['PRODS-COT'][$key]['produtos_fornecedor'][$keyProd]
                ['oferta']['submitSintese'] = TRUE;

            }

            if (empty($newArr))
                continue;

            $produtos[] =
                [
                    'cd_produto_comprador' => $params['cd_produto_comprador'],
                    'ds_produto_comprador' => $params['ds_produto_comprador'],
                    'qt_produto_total' => $params['qt_produto_total'],
                    'ds_unidade_compra' => $params['ds_unidade_compra'],
                    'marcas_encontradas' => $newArr
                ];
        }

        if (empty($produtos))
            return [];

        unset(
            $params['codigo'],
            $params['cd_produto_comprador'],
            $params['ds_produto_comprador'],
            $params['ds_unidade_compra'],
            $params['qt_produto_total'],
            $params['qtd_unidade'],
            $params['desconto_percentual']
        );

        return $array['cotacao'] =
            [
                'dados' => $params,
                'produtos_fornecedor' => $produtos
            ];

    }

    private function createObject($objCotacao)
    {
        $prodsOferta = [];

        $vlTtotalCotacao = 0;

        $dom = new DOMDocument();
        $dom->formatOutput = TRUE;

        $resposta = $dom->createElement("Resposta");

        # Adiciona as informações do cabeçalho
        $header = $dom->createElement("Cabecalho");
        $header->appendChild($dom->createElement("Id_Pdc", $objCotacao['dados']['cd_cotacao']));
        $header->appendChild($dom->createElement("CNPJ_Hospital", $objCotacao['dados']['cnpj_cliente']));
        $header->appendChild($dom->createElement("Faturamento_Minimo", str_replace(".", ",", $objCotacao['dados']['valor_minimo'])));
        $header->appendChild($dom->createElement("Prazo_Entrega", $objCotacao['dados']['prazo_entrega']));
        $header->appendChild($dom->createElement("Validade_Proposta", date('d/m/Y', strtotime('+5 days', strtotime($objCotacao['dados']['dt_fim_cotacao'])))));
        $header->appendChild($dom->createElement("Id_Forma_Pagamento", $objCotacao['dados']['forma_pagamento']));
        $header->appendChild($dom->createElement("Frete", 'CIF'));

        $obs_fornecedor = "-";

        if (isset($objCotacao['dados']['obsFornecedor'])) {

            if (!IS_NULL($objCotacao['dados']['obsFornecedor']))
                $obs_fornecedor = $objCotacao['dados']['obsFornecedor'];
        }

        $header->appendChild($dom->createElement("Observacoes", $obs_fornecedor));

        $itens_pdc = $dom->createElement("Itens_Pdc");

        $produtos_escolihos = $objCotacao['produtos_fornecedor'];

        foreach ($objCotacao['produtos_fornecedor'] as $key => $produtos) {


            $cd_prd_comprador = $produtos['cd_produto_comprador'];

            $produtoEscolhido = $this->getProdMenorPreco($produtos['marcas_encontradas']);

            $produtoEscolhido = array_merge($produtoEscolhido, ['id_produto' => NULL]);

            $produtoEscolhido['obs'] = $obs_fornecedor;

            unset($produtos_escolihos[$key]['marcas_encontradas']);

            $produtos_escolihos[$key]['marcas_encontradas'][0] = $produtoEscolhido;

            $item = $dom->createElement("Item_Pdc");

            $item->appendChild($dom->createElement('Sequencia', $produtoEscolhido['sequencia']));
            $item->appendChild($dom->createElement('Id_Artigo', $produtoEscolhido['id_artigo']));
            $item->appendChild($dom->createElement('Codigo_Produto', $cd_prd_comprador));
            $item->appendChild($dom->createElement('Preco_Unitario', $produtoEscolhido['preco_oferta']));
            $item->appendChild($dom->createElement('Fabricante', $produtoEscolhido['nome_comercial'] . " / " . $produtoEscolhido['marca']));
            $item->appendChild($dom->createElement('Embalagem', $produtoEscolhido['unidade']));
            $item->appendChild($dom->createElement('Quantidade_Embalagem', $produtoEscolhido['qtd_unidade']));
            $item->appendChild($dom->createElement('Comentario', $produtoEscolhido['obs_produto']));

            $extra = $dom->createElement('Campo_Extra');
            $extra->appendChild($dom->createElement('Nome', "Codigo_Produto_Fornecedor"));
            $extra->appendChild($dom->createElement('Valor', $produtoEscolhido['codigo']));

            $item->appendChild($extra);

            $itens_pdc->appendChild($item);

            $prodsOferta[] = $this->Engine->prodsOferta(
                [
                    'type' => $objCotacao['dados']['type'],
                    'dadosCotacao' => $objCotacao['dados'],
                    'produtoOferta' => $produtoEscolhido,
                    'cd_produto_comprador' => $cd_prd_comprador
                ]);

            $vlTtotalCotacao += ($produtoEscolhido['qtd_solicitada'] * $produtoEscolhido['preco_oferta']);
        }


        $resposta->appendChild($header);
        $resposta->appendChild($itens_pdc);
        $dom->appendChild($resposta);

        $dom->preserveWhiteSpace = FALSE;

        if ($this->configs['checkValorTotalCot']) {

            if ($vlTtotalCotacao < floatval($objCotacao['dados']['valor_minimo'])) {

                $this->logs['MSG'] = "Valor Total da Cotacao menor que o valor minimo!";

                return ['status' => FALSE];
            }
        }

        if ($vlTtotalCotacao == 0) {

            $this->logs['MSG'] = "Valor Total Zerado!";

            return ['status' => FALSE];
        }

        return
            [
                'status' => TRUE,
                'valorTotalCotacao' => $vlTtotalCotacao,
                'prodsOferta' => $prodsOferta,
                'prodsEspelho' => $produtos_escolihos,
                'xml' => $dom->saveXML(),
            ];

    }

    private function submitBionexo($params)
    {
        if ($this->configs['submitBionexo']) {

            $temp_xml = new DOMDocument();
            $temp_xml->loadXML($params['xml']);
            $your_xml = $temp_xml->saveXML($temp_xml->documentElement);

            $params['xml'] = $your_xml;

            $client = new SoapClient("https://ws.bionexo.com.br/BionexoBean?wsdl");

            $newParams = //WHU cotação já tem no BANCO, porem é novo produto
                [
                    $params['user'],
                    $params['password'],
                    'WHS',
                    'WH',
                    $params['xml']
                ];


            $resp = $client->__soapCall('post', $newParams);

            $response = explode(';', $resp);

            if (intval($response[0]) < 0) {

                # Lista de ERROS

                # Não é possível criar resposta: periodo de cotação encerrado
                # O dia da validade da proposta deve ser 3 dias posterior ao vencimento da cotação
                # java.lang.NullPointerException
                # Incorrect login/password
                # Não é possível criar resposta: pedido [119062878] já foi respondido!
                # O cliente trabalha com condições comerciais pré-estabelecidas para esta cotação. Para responder, é obrigatório utilizar os seguintes critérios: [Data de validade mínima= 19/11/2020]

                return
                    [
                        'status' => FALSE,
                        'result' => $response[2]
                    ];
            }
        }
        return
            [
                'status' => TRUE,
                'xml' => $params['xml']
            ];
    }

    private function getEstados($id)
    {
        return $this->db->distinct()->select('e.uf')->where('c.id_estado > 0')->where('c.regra_venda > 0')->where('c.id_fornecedor', $id)
            ->from('controle_cotacoes c')
            ->join('estados e', 'e.id = c.id_estado')
            ->get()
            ->result_array();
    }

    private function getCompradores($id)
    {
        return $this->db->distinct()->select('id_cliente')->where('id_cliente > 0')->where('regra_venda > 0')->where('id_fornecedor', $id)->get('controle_cotacoes')->result_array();
    }

    public function index()
    {

        $getFornecedores = $this->getFornecedores();

        if (!$getFornecedores['status'])
            exit();

        foreach ($getFornecedores['result'] as $fornecedor) {

            $margem_estoque = $this->db->where('id', $fornecedor['id_fornecedor'])
                ->get('fornecedores')
                ->row_array()['margem_estoque'];


            $estados = $this->getEstados($fornecedor['id_fornecedor']);
            $clientes = $this->getCompradores($fornecedor['id_fornecedor']);

            if (!empty($estados)) {
                $diff = [];
                foreach ($estados as $estado) {
                    $diff[] = $estado['uf'];
                }

                $estados = $diff;
            }


            if (!empty($clientes)) {
                $diff = [];
                foreach ($clientes as $cliente) {
                    $diff[] = $cliente['id_cliente'];
                }

                $clientes = $diff;
            }

            if (empty($estados) && empty($clientes)) {
                continue;
            }


            $getCotacoes = $this->Engine->getCotsFornecedor(
                [
                    'db' => $this->bio,
                    'configs' => $this->configs,
                    'id_fornecedor' => $fornecedor['id_fornecedor'],
                    'estados' => $estados,
                    'clientes' => $clientes
                ]
            );

            var_dump(count($getCotacoes['result']));
            exit();

            if (!$getCotacoes['status'])
                continue;

            foreach ($getCotacoes['result'] as $cotacao) {

                $params =
                    [
                        'type' => 'BIONEXO',
                        'integrador' => 2,
                        'id_fornecedor' => $fornecedor['id_fornecedor'],
                        'margem_estoque' => $margem_estoque,
                        'id_cotacao' => intval($cotacao['id']),
                        'cd_cotacao' => $cotacao['cd_cotacao'],
                        'dt_inicio_cotacao' => $cotacao['dt_inicio_cotacao'],
                        'dt_fim_cotacao' => $cotacao['dt_fim_cotacao'],
                        'id_cliente' => intval($cotacao['id_cliente']),
                        'cnpj_cliente' => mask($cotacao['cd_comprador'], '##.###.###/####-##'),
                        'uf_cotacao' => $cotacao['uf_cotacao'],
                    ];


                $id_estado = $this->db->where('uf', $params['uf_cotacao'])
                    ->get('estados')
                    ->row_array()['id'];

                $params = array_merge($params, ['id_estado' => intval($id_estado)]);

                if (!empty($this->logs))
                    $this->Engine->saveLogs($this->logs, $params, $this->configs);

                $configsEnvio = $this->Engine->getConfigsEnvio($params);

                if ($configsEnvio['status']) {

                    $params = array_merge($params,
                        [
                            'obsFornecedor' => $configsEnvio['result']['observacao'],
                            'confValidade' => $configsEnvio['result']['validade']
                        ]);
                }


                $this->logs = [];

                if (!$this->Engine->enabledAutomatic($params, $this->configs)) {

                    $this->logs['CONFIGS-COT'] =
                        [
                            'enabledAutomatic' => FALSE
                        ];

                    continue;
                }


                if ($this->Engine->clientRestriction($params, $this->configs)) {

                    $this->logs['CONFIGS-COT'] =
                        [
                            'clientRestriction' => TRUE
                        ];

                    continue;
                }

                $checkVlMinimo = $this->Engine->valorMinimo($params, $this->configs);

                if (!$checkVlMinimo['status']) {

                    $this->logs['CONFIGS-COT'] =
                        [
                            'valorMinimo' => FALSE
                        ];

                    continue;
                }

                $checkFormaPagamento = $this->getFormaPagamento($params);

                if (!$checkFormaPagamento['status']) {

                    $this->logs['CONFIGS-COT'] =
                        [
                            'formaPagamento' => FALSE
                        ];

                    continue;
                }

                $checkPrazoEntrega = $this->Engine->prazoEntrega($params, $this->configs);

                if (!$checkPrazoEntrega['status']) {

                    $this->logs['CONFIGS-COT'] =
                        [
                            'prazoEntrega' => $checkPrazoEntrega['status']
                        ];

                    continue;
                }


                $params = array_merge($params,
                    [
                        'valor_minimo' => $checkVlMinimo['result']['valor_minimo'],
                        'desconto_padrao' => floatval($checkVlMinimo['result']['desconto_padrao']),
                        'forma_pagamento' => $checkFormaPagamento['result']['cd_forma_pagamento'],
                        'prazo_entrega' => $checkPrazoEntrega['result']['prazo_entrega']
                    ]);

                $getProdsCots = $this->getProdsCots($params);


                if (!$getProdsCots['status']) {

                    $this->logs['CONFIGS-COT'] =
                        [
                            'produtosCotacao' => FALSE
                        ];

                    continue;
                }


                $dataCot = $this->prodsEncontrados($params, $getProdsCots['result']);

                if (empty($dataCot)) {

                    $this->logs['MSG'] = "Nenhum produto ofertado!";

                    continue;
                }

                $createObject = $this->createObject($dataCot);

                if ($createObject['status']) {

                    $this->logs['VALOR-TOTAL'] = $createObject['valorTotalCotacao'];

                    $submitBionexo = $this->submitBionexo(
                        [
                            'user' => $fornecedor['user'],
                            'password' => $fornecedor['password'],
                            'xml' => $createObject['xml']
                        ]);


                    if ($submitBionexo['status']) {

                        $this->Engine->saveProdsOferta($createObject['prodsOferta'], $this->configs);

                        $params = array_merge($params, ['xml' => $createObject['xml']]);

                        $this->Engine->saveLogs($this->logs, $params, $this->configs);

                        $this->logs = [];

                        $this->Engine->submitEmail(
                            [
                                'dadosCotacao' => $dataCot['dados'],
                                'prodsEspelho' => $createObject['prodsEspelho']
                            ],
                            $this->configs);

                        continue;
                    }

                    $this->logs['MSG'] = "Erro de envio para Bionexo - Error: {$submitBionexo['result']}";

                    $this->Engine->saveLogs($this->logs, $params, $this->configs);

                    $this->logs = [];
                }
            }
        }
    }
}
