<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class M_cotacaoManual extends MY_Model
{
    private $DB_SINTESE;
    private $DB_BIONEXO;
    private $DB_APOIO;
    private $urlCliente_sintese;
    private $urlCliente_bionexo;
    private $urlCliente_apoio;


    public function __construct()
    {
        parent::__construct();

        # Models
        $this->load->model('m_catalogo', 'catalogo');
        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_estados', 'estados');
        $this->load->model('m_forma_pagamento', 'forma_pagamento');
        $this->load->model('m_fornecedor', 'fornecedores');
        $this->load->model('m_marca', 'marca');
        $this->load->model('m_restricao_produto_cotacao', 'restricoes_cotacao');
        $this->load->model('m_usuarios', 'usuarios');
        $this->load->model("m_produto_cliente_depara", "pcd");
        $this->load->model('m_preco_mix', 'preco_mix');

        //        error_reporting(E_ALL);
        //        ini_set("display_errors", 1);
        error_reporting(0);
        ini_set('display_errors', 0);

        # URLs
        $this->urlCliente_sintese = $this->config->item('db_config')['url_client'];
        $this->urlCliente_bionexo = $this->config->item('db_config')['url_client_bionexo'];
        $this->urlCliente_apoio = $this->config->item('db_config')['url_client_apoio'];


        # Schemas
        $this->DB_SINTESE = $this->load->database('sintese', TRUE);
        $this->DB_BIONEXO = $this->load->database('bionexo', TRUE);
        $this->DB_APOIO = $this->load->database('apoio', TRUE);
        $this->DB_HUMA = $this->load->database('huma', TRUE);
    }


    /**
     * Monta as informações dos produtos da cotação para serem exibidos na tela
     *
     * @param - String codigo da cotação
     * @param - INT ID do fornecedor
     * @param - String nome do integrador
     * @return  array
     */
    public function getItem($cd_cotacao, $id_fornecedor, $integrador)
    {

        if (isset($_SESSION['id_matriz'])){
            $forns = $this->db
                ->where('id_matriz', $_SESSION['id_matriz'])
                ->get('fornecedores')
                ->result_array();
            $id_fornecedor = [];

            foreach ($forns as $f){
                $id_fornecedor[] = $f['id'];
            }
        }else{
            if (empty($id_fornecedor)) {
                $id_fornecedor = [$this->session->id_fornecedor];
            }else{
                $id_fornecedor = [$id_fornecedor];
            }
        }

        # Obtem Cotação
        $cotacao = $this->getCotacao($integrador, $cd_cotacao, $id_fornecedor);

        if ($cotacao == false) {
            return false;
        }

        # Obtem Cliente
        $cliente = $this->compradores->findById($cotacao['id_cliente']);

        # Obtem o estado do cliente
        $estado = $this->estados->find("*", "uf = '{$cliente['estado']}'", true);

        # Realiza o depara dos produtos, monta com as informações pharmanexo e ordena os registros
        $produtos = $this->matchProducts($cd_cotacao, $cotacao['id'], $cotacao['id_fornecedor'], $cliente['id'], $estado['id'], $integrador);


        # Condição de pagamento da cotação
        $condicao_pagamento = ($integrador == 'SINTESE') ?
            $this->forma_pagamento->findById($cotacao['cd_condicao_pagamento'])['descricao'] : $cotacao['forma_pagamento'];

        #obs cotação
        $obs = " - ";

        if (isset($cotacao['observacao']) && !empty($cotacao['observacao'])) {
            $obs = "{$cotacao['ds_cotacao']} </br> {$cotacao['observacao']}";
        } elseif (isset($cotacao['ds_observacao']) && !empty($cotacao['ds_observacao'])) {
            $obs = "{$cotacao['ds_cotacao']} </br> {$cotacao['ds_observacao']}";
        }

        # Cabeçalho de cada produto
        $data = [
            "id_cotacao" => $cotacao['id'],
            "cd_cotacao" => $cotacao['cd_cotacao'],
            "oculto" => $cotacao['oculto'],
            "revisao" => $cotacao['revisao'],
            "cnpj" => $cotacao['cd_comprador'],
            "cliente" => $cliente,
            "estado" => $estado,
            "data_inicio" => $cotacao['dt_inicio_cotacao'],
            "data_fim" => $cotacao['dt_fim_cotacao'],
            "uf_cotacao" => $cotacao['uf_cotacao'],
            "Ds_Cotacao" => $cotacao['ds_cotacao'],
            "observacao" => $obs,
            "contato" => isset($cotacao['contato']) ? $cotacao['contato'] : '',
            "condicao_pagamento" => $condicao_pagamento,
            "itens" => count($produtos),
            "produtos" => $produtos
        ];



        return $data;
    }

    /**
     * Monta as informações das cotações para exibir na tela
     *
     * @param - String sigla do estado
     * @return  array
     */
    public function getCotacoes($fields = '*', $uf = null)
    {

        $this->db->select($fields);
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);
        $this->db->where("dt_fim_cotacao > now()");

        if (isset($uf)) {

            $uf = strtoupper($uf);
            $this->db->where("uf_cotacao = '{$uf}' ");
        }

        $this->db->group_by('cd_cotacao');
        $this->db->order_by('cd_cotacao ASC');

        return $this->db->get('vw_cotacoes_integrador')->result_array();
    }

    /**
     * Combina os produtos pharmanexo encontrado no depara com os produtos da SINTESE
     *
     * @param - String codigo da cotação
     * @param - INT ID da cotação
     * @param - Int ID do fornecedor
     * @param - Int ID do comprador
     * @param - Int ID do estado do comprador
     * @param - String nome do integrador
     * @return  function getDetailsProducts
     */
    public function matchProducts($cd_cotacao, $id_cotacao, $id_fornecedor, $id_cliente, $id_estado, $integrador)
    {

        $integrador = strtoupper($integrador);

        # Obtem a condição de estado
        $estado = (is_null($id_estado)) ? 'is null' : $id_estado;

        # Filial não precisa verificar estoque na query
        $exibirEstoque = (in_array($id_fornecedor, explode(',', ONCOPROD)) || in_array($id_fornecedor, explode(',', ONCOEXO))) ? null : 1;

        $produtos = [];

        switch ($integrador) {
            case 'SINTESE':
                $depara = $this->queryDeparaSintese($cd_cotacao, $id_fornecedor, $estado, $exibirEstoque, 1);

                # Lista dos produtos da cotação na SINTESE
                $this->DB_SINTESE->select("id_produto_sintese");
                $this->DB_SINTESE->select("TRIM(cd_produto_comprador) AS cd_produto_comprador");
                $this->DB_SINTESE->select("ds_produto_comprador");
                $this->DB_SINTESE->select("ds_unidade_compra");
                $this->DB_SINTESE->select("ds_complementar");
                $this->DB_SINTESE->select("cd_cotacao");
                $this->DB_SINTESE->select("SUM(qt_produto_total) AS qt_produto_total");
                $this->DB_SINTESE->where('cd_cotacao', $cd_cotacao);
                $this->DB_SINTESE->where('id_fornecedor', $id_fornecedor);
                $this->DB_SINTESE->group_by('id_produto_sintese, cd_produto_comprador');
                $produtos_cotacao = $this->DB_SINTESE->get('cotacoes_produtos')->result_array();


                # Faz a combinação dos produtos Pharmanexo x Sintese
                foreach ($produtos_cotacao as $produto) {

                    $encontrados = [];

                    if (isset($depara) && !empty($depara)) {

                        foreach ($depara as $prod) {

                            if ($prod['id_produto_sintese'] == $produto['id_produto_sintese']) {

                                $encontrados[] = $prod;
                            }
                        }
                    }

                    $produtos[] = [
                        'cotado' => $produto,
                        'encontrados' => $encontrados,
                    ];
                }
                break;
            case 'BIONEXO':
                $depara = $this->queryDeparaBionexo($cd_cotacao, $id_fornecedor, $estado, $exibirEstoque, 1);

                # Lista dos produtos da cotação na BIONEXO
                $this->DB_BIONEXO->select("id");
                $this->DB_BIONEXO->select("id_artigo");
                $this->DB_BIONEXO->select("TRIM(cd_produto_comprador) AS cd_produto_comprador");
                $this->DB_BIONEXO->select("ds_produto_comprador");
                $this->DB_BIONEXO->select("marca_favorita");
                $this->DB_BIONEXO->select("ds_unidade_compra");
                $this->DB_BIONEXO->select("SUM(qt_produto_total) AS qt_produto_total");
                $this->DB_BIONEXO->where('id_cotacao', $id_cotacao);
                $this->DB_BIONEXO->group_by('cd_produto_comprador');
                $produtos_cotacao = $this->DB_BIONEXO->get('cotacoes_produtos')->result_array();


                # Faz a combinação dos produtos Pharmanexo x bionexo
                foreach ($produtos_cotacao as $produto) {

                    $encontrados = [];

                    if (isset($depara) && !empty($depara)) {

                        foreach ($depara as $prod) {

                            if ($prod['cd_produto_comprador'] == $produto['cd_produto_comprador']) {

                                $produto['id_produto_sintese'] = $prod['id_produto_sintese'];
                                $encontrados[] = $prod;
                            }
                        }
                    }

                    $produto['cd_cotacao'] = $cd_cotacao;

                    $produtos[] = [
                        'cotado' => $produto,
                        'encontrados' => $encontrados,
                    ];
                }
                break;
            case 'APOIO':
                $depara = $this->queryDeparaApoio($cd_cotacao, $id_fornecedor, $estado, $exibirEstoque, 1);

                # Lista dos produtos da cotação na APOIO
                $this->DB_APOIO->select("id");
                $this->DB_APOIO->select("id_artigo");
                $this->DB_APOIO->select("TRIM(cd_produto_comprador) AS cd_produto_comprador");
                $this->DB_APOIO->select("ds_produto_comprador");
                $this->DB_APOIO->select("marca_favorita");
                $this->DB_APOIO->select("ds_unidade_compra");
                $this->DB_APOIO->select("SUM(qt_produto_total) AS qt_produto_total");
                $this->DB_APOIO->where('id_cotacao', $id_cotacao);
                $this->DB_APOIO->group_by('cd_produto_comprador');
                $produtos_cotacao = $this->DB_APOIO->get('cotacoes_produtos')->result_array();


                # Faz a combinação dos produtos Pharmanexo x bionexo
                foreach ($produtos_cotacao as $produto) {

                    $encontrados = [];

                    if (isset($depara) && !empty($depara)) {

                        foreach ($depara as $prod) {

                            if ($prod['cd_produto_comprador'] == $produto['cd_produto_comprador']) {

                                $produto['id_produto_sintese'] = $prod['id_produto_sintese'];
                                $encontrados[] = $prod;
                            }
                        }
                    }

                    $produto['cd_cotacao'] = $cd_cotacao;

                    $produtos[] = [
                        'cotado' => $produto,
                        'encontrados' => $encontrados,
                    ];
                }
                break;
            case 'HUMA':
                $depara = $this->queryDeparaHuma($cd_cotacao, $id_fornecedor, $estado, $exibirEstoque, 1);


                # Lista dos produtos da cotação na APOIO
                $this->DB_HUMA->select("id");
                $this->DB_HUMA->select("id_artigo");
                $this->DB_HUMA->select("id_artigo");
                $this->DB_HUMA->select("TRIM(cd_produto_comprador) AS cd_produto_comprador");
                $this->DB_HUMA->select("ds_produto_comprador");
                $this->DB_HUMA->select("marca_favorita");
                $this->DB_HUMA->select("ds_unidade_compra");
                $this->DB_HUMA->select("SUM(qt_produto_total) AS qt_produto_total");
                $this->DB_HUMA->where('id_cotacao', $id_cotacao);
                $this->DB_HUMA->group_by('id_artigo');
                $produtos_cotacao = $this->DB_HUMA->get('cotacoes_produtos')->result_array();


                # Faz a combinação dos produtos Pharmanexo x bionexo
                foreach ($produtos_cotacao as $produto) {

                    $encontrados = [];

                    if (isset($depara) && !empty($depara)) {

                        foreach ($depara as $prod) {

                            if ($prod['cd_produto_comprador'] == $produto['cd_produto_comprador']) {

                                $produto['id_produto_sintese'] = $prod['id_produto_sintese'];
                                $encontrados[] = $prod;
                            }
                        }
                    }

                    $produto['cd_cotacao'] = $cd_cotacao;

                    $produtos[] = [
                        'cotado' => $produto,
                        'encontrados' => $encontrados,
                    ];
                }
                break;
        }


        return $this->getDetailsProducts($produtos, $id_cliente, $id_estado, $id_fornecedor, $integrador, $cd_cotacao);
    }

    /**
     * Obtem informações como preço, estoque e total de envios para os depara encontrados
     *
     * @param - Array de produtos
     * @param - Int ID do comprador
     * @param - Int ID do estado do comprador
     * @param - Int ID do fornecedor
     * @param - String nome do integrador
     * @return  function OrganizeProducts
     */
    public function getDetailsProducts($produtos, $id_cliente, $id_estado, $id_fornecedor, $integrador)
    {

        $produtosSemEstoque = [];

        if (isset($_SESSION['id_matriz']) && !empty($_SESSION['id_matriz'])) {

            # Define qual matriz esta logada
            $matriz = $_SESSION['id_matriz'];

            # Lista das filiais da ONCOPROD
            $fornecedores_filial = $this->fornecedores->find("*", "id_matriz = {$matriz}");
        }


        foreach ($produtos as $kk => $produto) {

            # Variavel para somar o total de estoque das marcas do produto
            $totalEstoque = 0;
            $idSintese = (isset($produto['cotado']['id_produto_sintese']) ? $produto['cotado']['id_produto_sintese'] : '');
            $id_produto = (strtoupper($integrador) == 'SINTESE') ? $idSintese : null;

            # Verifica Restrições como OL e S.E (sem estoque) e a restrição do produto
            $restricao = $this->restricoes_cotacao->find($integrador, $id_fornecedor, $produto['cotado']['cd_cotacao'], $produto['cotado']['cd_produto_comprador'], $id_produto);


            if (isset($restricao) && !empty($restricao)) {

                $produtos[$kk]['cotado']['ol'] = $restricao['ol'];
                $produtos[$kk]['cotado']['sem_estoque'] = $restricao['sem_estoque'];
                $produtos[$kk]['cotado']['restricao'] = $restricao['restricao'];
            } else {

                $produtos[$kk]['cotado']['ol'] = 0;
                $produtos[$kk]['cotado']['sem_estoque'] = 0;
                $produtos[$kk]['cotado']['restricao'] = 0;
            }

            if (isset($produto['encontrados']) && !empty($produto['encontrados'])) {

                foreach ($produto['encontrados'] as $k => $p) {

                    $estoque = [];

                    # Busca se existe venda diferenciada
                    $vd = $this->getVendaDiferenciada($p['id_fornecedor'], $p['codigo'], $id_cliente, $id_estado);

                    // BUSCA PREÇO POR REGIÃO
                    $regiaoCliente = $this->db
                        ->where('id_cliente', $id_cliente)
                        ->where('id_fornecedor', $id_fornecedor)
                        ->get('compradores_regiao')
                        ->row_array();


                    if (!empty($regiaoCliente)) {

                        $coluna = "preco_{$regiaoCliente['col']}";

                        $precoRegiao = $this->db
                            ->where('regiao', $regiaoCliente['numregiao'])
                            ->where('codigo', $p['codigo'])
                            ->where('id_fornecedor', $id_fornecedor)
                            ->get('produtos_precos_regiao')
                            ->row_array();

                        if (!empty($precoRegiao)) {
                            $p['preco_unitario'] = $precoRegiao[$coluna];
                        }
                    }
                    // --------------------------------------------------------- //

                    $newPrice = $this->getPriceUnitary($p['preco_unitario'], $p['quantidade_unidade'], $p['id_fornecedor'], $vd);

                    $produtos[$kk]['encontrados'][$k]['preco_unitario'] = $newPrice;
                    $produtos[$kk]['encontrados'][$k]['preco_caixa'] = $newPrice * $p['quantidade_unidade'];

                    $params = [
                        'id_fornecedor' => $p['id_fornecedor'],
                        'id_cliente' => $id_cliente,
                        'codigo' => $p['codigo']
                    ];
                    $pMix = $this->preco_mix->get_item($params);
                    // $pMix = [];

                    if (isset($pMix['preco_base']) && !empty($pMix['preco_base'])) {
                        $produtos[$kk]['encontrados'][$k]['preco_unitario'] = $pMix['preco_base'];
                        $produtos[$kk]['encontrados'][$k]['preco_caixa'] = $pMix['preco_base'] * $p['quantidade_unidade'];
                    }

                    if (!empty($fornecedores_filial)) {

                        # Lista de estoques da ONCOPROD
                        foreach ($fornecedores_filial as $f => $fornecedor) {

                            if (isset($p['quantidade_unidade']) && $p['quantidade_unidade'] > 0) {

                                $this->db->select("( SUM(estoque) * {$p['quantidade_unidade']} )  AS estoque");
                            } else {

                                $this->db->select(" (SUM(estoque)) AS estoque");
                            }

                            $this->db->where('id_fornecedor', $fornecedor['id']);
                            $this->db->where('codigo', $p['codigo']);
                            $estq = $this->db->get('produtos_lote')->row_array()['estoque'];

                            if (is_null($estq)) {
                                $estq = 0;
                            }


                            if ($fornecedor['id'] == $this->session->id_fornecedor) {

                                $produtos[$kk]['encontrados'][$k]['estoque'] = $estq;
                                $p['estoque'] = $estq;

                                # Adiciona o estoque na variavel de estoque geral do produto
                                $totalEstoque += $estq;
                            }

                            $estoque[] = ['name' => $fornecedor['nome_fantasia'], 'value' => $estq, 'label' => $fornecedor['id']];
                        }

                        $produtos[$kk]['encontrados'][$k]['estoques'] = $estoque;
                    } else {

                        # Adiciona o estoque na variavel de estoque geral do produto
                        $totalEstoque += $produtos[$kk]['encontrados'][$k]['estoque'];
                    }

                    # Obtem a marca
                    $produtos[$kk]['encontrados'][$k]['marca'] = $this->getMarca($p['id_marca']);

                    # Verifica se existe restrição para o produto
                    $produtos[$kk]['encontrados'][$k]['restricao'] = $this->getRestricao($p['codigo'], $this->session->id_fornecedor, $id_cliente, $id_estado);


                    # verifica se foi respondido
                    $this->db->select("*");
                    $this->db->group_start();
                    $this->db->where("id_fornecedor", $p['id_fornecedor']);
                    $this->db->or_where("id_fornecedor_logado", $this->session->id_fornecedor);
                    $this->db->group_end();
                    $this->db->where("cd_cotacao", $p['cd_cotacao']);
                    $this->db->where("id_pfv", $p['codigo']);

                    if ($integrador == 'SINTESE') {

                        $this->db->where("id_produto", $produto['cotado']['id_produto_sintese']);
                        $this->db->where("cd_produto_comprador", $produto['cotado']['cd_produto_comprador']);
                    } else {

                        # $this->db->where("id_produto is null");
                        $this->db->where("cd_produto_comprador", $produto['cotado']['cd_produto_comprador']);
                    }
                    $this->db->where('integrador', $integrador);
                    $this->db->group_start();
                    $this->db->where("nivel", 1);
                    $this->db->or_where("nivel", 2);
                    $this->db->group_end();
                    $produto_enviado = $this->db->get('cotacoes_produtos')->row_array();

                    # Se foi respondido
                    if (isset($produto_enviado) && !empty($produto_enviado)) {

                        $produtos[$kk]['encontrados'][$k]['fornecedor_cotacao'] = $produto_enviado['id_fornecedor'];

                        # Nao exibe a restrição
                        $produtos[$kk]['encontrados'][$k]['restricao'] = 0;

                        # Somente produtos enviados ou produtos enviados ocultados ou produtos enviados ocultados que foram reenviados
                        if ($produto_enviado['submetido'] == 1 || ($produto_enviado['ocultar'] == 1 && $produto_enviado['controle'] == 1) || ($produto_enviado['ocultar'] == 0 && $produto_enviado['controle'] == 1)) {

                            # Adiciona a coluna nivel
                            $produtos[$kk]['encontrados'][$k]['nivel'] = $produto_enviado['nivel'];
                            $produtos[$kk]['encontrados'][$k]['id_cotacao'] = $produto_enviado['id'];
                        }

                        #adiciona informações do usuario que fez a oferta
                        $produtos[$kk]['encontrados'][$k]['usuario'] = $this->db->select('id, nome')->where('id', $produto_enviado['id_usuario'])->get("usuarios")->row_array();

                        # Adiciona a coluna ocultar
                        $produtos[$kk]['encontrados'][$k]['ocultar'] = $produto_enviado['ocultar'];

                        # Somente itens enviados e rascunhos podem sobrescrever as informações
                        if ($produto_enviado['ocultar'] != 1) {

                            # Altera o preço do produto para o preço enviado
                            $produtos[$kk]['encontrados'][$k]['preco_unitario'] = $produto_enviado['preco_marca'];

                            $produtos[$kk]['encontrados'][$k]['preco_caixa'] = $produto_enviado['preco_marca'] * $produto_enviado['qtd_embalagem'];

                            $produtos[$kk]['encontrados'][$k]['obs'] = $produto_enviado['obs_produto'];

                            # Exibe a observação enviada
                            //                            if (!empty($produto_enviado['obs_produto'])) {
                            //
                            //                                # Separa a observação em dois para identificar o texto da observação sem o nome do produto
                            //                                $obs = explode(' - ', $produto_enviado['obs_produto']);
                            //
                            //                                # Verifica se existe observação
                            //                                if (isset($obs[1])) {
                            //
                            //                                    $produtos[$kk]['encontrados'][$k]['obs'] = $obs[1];
                            //                                } else {
                            //
                            //                                    # Se não existir, manda vazio
                            //                                    $produtos[$kk]['encontrados'][$k]['obs'] = '';
                            //                                }
                            //                            }
                        }

                        # Altera as informações caso ja tenha sido enviado para a sintese
                        if ($produto_enviado['submetido'] == 1) {

                            # Adiciona o nivel da oferta
                            $produtos[$kk]['encontrados'][$k]['nivel'] = $produto_enviado['nivel'];

                            $produtos[$kk]['encontrados'][$k]['enviado'] = 1;
                            $produtos[$kk]['encontrados'][$k]['rascunho'] = 0;
                        } else {

                            # Se ocultar estiver ativo, significa que o registro foi mandado para a sintese mas foi removido posteriormente, entao o registro é mantido.
                            if ($produto_enviado['ocultar'] == 1) {

                                $produtos[$kk]['encontrados'][$k]['rascunho'] = 0;
                            } else {

                                $produtos[$kk]['encontrados'][$k]['rascunho'] = 1;
                            }

                            $produtos[$kk]['encontrados'][$k]['enviado'] = 0;
                        }
                    } else {

                        $produtos[$kk]['encontrados'][$k]['enviado'] = 0;
                        $produtos[$kk]['encontrados'][$k]['rascunho'] = 0;
                    }

                    # Adiciona o ultimo preço ganhador ( OC ) do produto pelo comprador
                    $produtos[$kk]['encontrados'][$k]['ultima_oferta'] = $this->getUltimaOfertaProdutoComprador($p['codigo'], $p['id_fornecedor'], $id_cliente);

                    # Determina qual classe terá a marca na exibição
                    $class = '';

                    # Se não tiver estoque
                    if ($produtos[$kk]['encontrados'][$k]['estoque'] < 1) {

                        $class = 'table-danger';

                        /*# Insere todos os produtos que nao possuem estoque no Array
                        $produtosSemEstoque['cd_cotacao'] = $p['cd_cotacao'];
                        $produtosSemEstoque['produtos'][] = [$p['codigo']];

                        # Consulta se existe registro
                        $consultar_sem_estoque = $this->db->select('id')
                            ->where('id_produto', $produto['cotado']['id_produto_sintese'])
                            ->where('codigo', $p['codigo'])
                            ->where('id_fornecedor', $p['id_fornecedor'])
                            ->where('cd_cotacao', $p['cd_cotacao'])
                            ->get('produtos_sem_estoque');


                        # Se não existir, armazena no array para registrar
                        if ($consultar_sem_estoque->num_rows() < 1) {

                            $sem_estoque[] = [
                                'id_produto' => $produto['cotado']['id_produto_sintese'],
                                'codigo' => $p['codigo'],
                                'id_fornecedor' => $p['id_fornecedor'],
                                'cd_cotacao' => $p['cd_cotacao']
                            ];
                        }*/
                    } # Se o estoque for maior que o slicitado
                    elseif ($produtos[$kk]['encontrados'][$k]['estoque'] >= $produto['cotado']['qt_produto_total']) {

                        $class = 'table-success';
                    } # Se o estoque for insuficiente ao solicitado
                    elseif ($produtos[$kk]['encontrados'][$k]['estoque'] > 0 && $produtos[$kk]['encontrados'][$k]['estoque'] < $produto['cotado']['qt_produto_total']) {

                        $class = 'table-warning';
                    }

                    # Adiciona a classe
                    $produtos[$kk]['encontrados'][$k]['class'] = $class;
                }
            } else {

                $produtos[$kk]['encontrados'] = null;
            }

            # Adiciona a soma dos estoque no produto
            $produtos[$kk]['cotado']['encontrados'] = $totalEstoque;
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

        # Organiza o array de produtos
        foreach ($produtos as $kk => $produto) {

            # Organiza os itens de um produto
            if (!empty($produto['encontrados'])) {

                $prods = [];

                $itens_com_estoque = [];
                $itens_com_estoque_insuf = [];
                $itens_sem_estoque = [];


                foreach ($produto['encontrados'] as $k => $item) {

                    # Adiciona as marcas que tem estoque maior que a quantidade solicitada
                    if (intval($item['estoque']) > 0 && intval($item['estoque']) >= $produto['cotado']['qt_produto_total']) {

                        $itens_com_estoque[] = $item;
                    } # Adiciona as marcas que tem estoque mais é insuficente a quantidade solicitada
                    elseif (intval($item['estoque']) > 0 && intval($item['estoque']) < $produto['cotado']['qt_produto_total']) {

                        $itens_com_estoque_insuf[] = $item;
                    } # Adiciona as marcas que não tem estoque
                    else {

                        $itens_sem_estoque[] = $item;
                    }
                }

                # Combina todos os grupos de produtos ordenados
                $produto['encontrados'] = array_merge($itens_com_estoque, $itens_com_estoque_insuf, $itens_sem_estoque);
            }

            # Adiciona os produtos que foram enviados
            if (isset($produto['encontrados']) && in_array(1, array_column($produto['encontrados'], 'enviado'))) {

                $produto['classCard'] = 'enviado';
                $azuis[] = $produto;
            } # Adiciona os produtos que tem depara mais não foram enviados
            elseif (isset($produto['encontrados']) && !empty($produto['encontrados']) && !in_array(1, array_column($produto['encontrados'], 'enviado'))) {

                $produto['classCard'] = 'nenviado';
                $verdes[] = $produto;
            } # Adiciona os produtos sem depara
            elseif (empty($produto['encontrados'])) {

                $produto['classCard'] = 'nencontrado';
                $vermelhos[] = $produto;
            } # Adiciona o resto dos produtos
            else {

                $produto['classCard'] = 'nenviado';
                $verdes[] = $produto;
            }
        }

        # Ordena ASC os produtos de cada grupo pelo nome do produto sintese
        if (!empty($azuis)) {

            foreach ($azuis as $kk => $p) {

                $nome1[$kk] = $p['cotado']['ds_produto_comprador'];
            }

            array_multisort($nome1, SORT_ASC, $azuis);
        }

        if (!empty($verdes)) {

            foreach ($verdes as $kk => $p) {

                $nome2[$kk] = $p['cotado']['ds_produto_comprador'];
            }

            array_multisort($nome2, SORT_ASC, $verdes);
        }

        if (!empty($vermelhos)) {

            foreach ($vermelhos as $kk => $p) {

                $nome3[$kk] = $p['cotado']['ds_produto_comprador'];
            }

            array_multisort($nome3, SORT_ASC, $vermelhos);
        }

        return array_merge($azuis, $verdes, $vermelhos);
    }

    /**
     * Faz o depara dos produtos da sintese de uma cotação
     *
     * @param - String codigo da cotação
     * @param - Int ID do fornecedor
     * @param - String where estado
     * @param - INT flag para inserir coluna estoque na query
     * @param - INT flag para inserir coluna preço na query
     * @param - String
     * @return  array
     */
    public function queryDeparaSintese($cd_cotacao, $id_Fornecedor, $estado, $exibirEstoque = null, $exibirPreco = null)
    {

        $query = "
    		SELECT 
    			cot.cd_cotacao,
	            cot_prods.ds_produto_comprador,
	            cot_prods.id_produto_sintese,
                cot_prods.cd_produto_comprador,
	            pc.codigo,
	            pc.id,
	            CONCAT(pc.nome_comercial, ' - ', (case when (pc.apresentacao is null OR pc.apresentacao = '') then pc.descricao else pc.apresentacao end)) produto_descricao,
	            pc.marca,
	            IF(pc.quantidade_unidade is null, 1, pc.quantidade_unidade) quantidade_unidade,
	            pc.id_marca,
	            cot_prods.id_fornecedor
    	";

        # Add estoque
        if (isset($exibirEstoque)) {

            $query .= ",
    			(SELECT (SUM(pl.estoque) * IF(pc2.quantidade_unidade is null, 1, pc2.quantidade_unidade))
	                FROM pharmanexo.produtos_lote pl
	                JOIN pharmanexo.produtos_catalogo pc2 ON pc2.codigo = pl.codigo
	                    AND pc2.id_fornecedor = pl.id_fornecedor
	                    AND pc2.ativo = 1
	                    AND pc2.bloqueado = 0
	                WHERE pl.codigo = forn_sint.cd_produto
	                    AND pl.id_fornecedor = forn_sint.id_fornecedor) estoque
	    		";
        }


        $query .= " 
            FROM cotacoes_sintese.cotacoes cot
            JOIN cotacoes_sintese.cotacoes_produtos cot_prods
                ON cot_prods.id_fornecedor = cot.id_fornecedor
                AND cot_prods.cd_cotacao = cot.cd_cotacao
            LEFT JOIN pharmanexo.produtos_marca_sintese marc_sint
                ON marc_sint.id_produto = cot_prods.id_produto_sintese
            LEFT JOIN pharmanexo.produtos_fornecedores_sintese forn_sint
                ON forn_sint.id_fornecedor = cot.id_fornecedor
                AND forn_sint.id_sintese = marc_sint.id_sintese
            JOIN pharmanexo.produtos_catalogo pc
                ON pc.codigo = forn_sint.cd_produto
                AND pc.id_fornecedor = forn_sint.id_fornecedor
                AND pc.ativo = 1
                AND pc.bloqueado = 0

            WHERE cot.cd_cotacao = '{$cd_cotacao}'
                AND cot_prods.id_fornecedor  = {$id_Fornecedor}  
            GROUP BY cot.cd_cotacao,
                cot_prods.id_produto_sintese,
                forn_sint.cd_produto,
                marc_sint.id_produto,
                CONCAT(pc.nome_comercial, ' - ', (case when (pc.apresentacao is null OR pc.apresentacao = '') then pc.descricao else pc.apresentacao end)),
                pc.marca,
                pc.id_marca,
                pc.quantidade_unidade

            HAVING forn_sint.cd_produto IS NOT NULL
            ORDER BY cot_prods.ds_produto_comprador ASC, cot_prods.id_fornecedor
        ";


        $data = $this->db->query($query)->result_array();
        foreach ($data as $k => $item) {

            if (is_null($estado)) {
                $query_preco = "
    			  SELECT pp.preco_unitario
                    FROM pharmanexo.produtos_preco_max pp
                    WHERE  pp.id_estado is null
                      AND pp.id_fornecedor = {$item['id_fornecedor']}
                      AND pp.codigo = {$item['codigo']}
                    LIMIT 1";
            } else {
                $query_preco = "
    			  SELECT pp.preco_unitario
                    FROM pharmanexo.produtos_preco_max pp
                    WHERE (pp.id_estado = {$estado} or pp.id_estado is null)
                       AND pp.id_fornecedor = {$item['id_fornecedor']}
                      AND pp.codigo = {$item['codigo']}
                    LIMIT 1";
            }

            $preco = $this->db->query($query_preco)->row_array();

            if (!empty($preco)) {
                $data[$k]['preco_unitario'] = $preco['preco_unitario'];
            } else {
                $data[$k]['preco_unitario'] = 0.00;
            }
        }

        return $data;
    }

    /**
     * Faz o depara dos produtos da bionexo de uma cotação
     *
     * @param - String codigo da cotação
     * @param - Int ID do fornecedor
     * @param - String where estado
     * @param - INT flag para inserir coluna estoque na query
     * @param - INT flag para inserir coluna preço na query
     * @param - String
     * @return  array
     */
    public function queryDeparaBionexo($cd_cotacao, $id_Fornecedor, $estado, $exibirEstoque = null, $exibirPreco = null)
    {

        $query = "
    		SELECT 
    			cot.cd_cotacao,
                cot_prods.ds_produto_comprador,
                cot_prods.cd_produto_comprador,
                depara.id_produto_sintese,
                pc.id,
                forn_sint.cd_produto codigo,
                CONCAT(pc.nome_comercial, ' - ', (case when (pc.apresentacao is null OR pc.apresentacao = '') then pc.descricao else pc.apresentacao end)) produto_descricao,
                pc.marca,
                IF(pc.quantidade_unidade is null, 1, pc.quantidade_unidade) quantidade_unidade,
                pc.id_marca,
                marc_sint.id_produto,
                cot.id_fornecedor
    	";

        # Add estoque
        if (isset($exibirEstoque)) {

            $query .= ",
    			(SELECT (SUM(pl.estoque) * IF(pc2.quantidade_unidade is null, 1, pc2.quantidade_unidade))
	                FROM pharmanexo.produtos_lote pl
	                JOIN pharmanexo.produtos_catalogo pc2 ON pc2.codigo = pl.codigo
	                    AND pc2.id_fornecedor = pl.id_fornecedor
	                    AND pc2.ativo = 1
	                    AND pc2.bloqueado = 0
	                WHERE pl.codigo = forn_sint.cd_produto
	                    AND pl.id_fornecedor = forn_sint.id_fornecedor) estoque
	    		";
        }

        # Add preço
        if (isset($exibirPreco)) {

            if (is_null($estado)) {
                $query .= ",
    			  (SELECT pp.preco_unitario
                    FROM pharmanexo.produtos_preco_max pp
                    WHERE  pp.id_estado is null
                      AND pp.id_fornecedor = forn_sint.id_fornecedor
                      AND pp.codigo = forn_sint.cd_produto
                    LIMIT 1)      preco_unitario
                        ";
            } else {
                $query .= ",
    			  (SELECT pp.preco_unitario
                    FROM pharmanexo.produtos_preco_max pp
                    WHERE (pp.id_estado = {$estado} or pp.id_estado is null)
                      AND pp.id_fornecedor = forn_sint.id_fornecedor
                      AND pp.codigo = forn_sint.cd_produto
                    LIMIT 1)      preco_unitario
                        ";
            }
        }

        $query .= "
            
            FROM cotacoes_bionexo.cotacoes cot
            JOIN cotacoes_bionexo.cotacoes_produtos cot_prods ON cot_prods.id_cotacao = cot.id
            LEFT JOIN pharmanexo.produtos_clientes_depara depara ON depara.cd_produto = cot_prods.cd_produto_comprador AND depara.id_cliente = cot.id_cliente
            LEFT join pharmanexo.produtos_marca_sintese marc_sint ON marc_sint.id_produto = depara.id_produto_sintese
            LEFT JOIN pharmanexo.produtos_fornecedores_sintese forn_sint ON forn_sint.id_fornecedor = cot.id_fornecedor AND forn_sint.id_sintese = marc_sint.id_sintese
            JOIN pharmanexo.produtos_catalogo pc
                ON pc.codigo = forn_sint.cd_produto
                AND pc.id_fornecedor = forn_sint.id_fornecedor
                AND pc.ativo = 1
                AND pc.bloqueado = 0
            WHERE cot.cd_cotacao = '{$cd_cotacao}' AND cot.id_fornecedor  = {$id_Fornecedor}  and depara.id_integrador = 2
            GROUP BY 
               cot.cd_cotacao,
                forn_sint.cd_produto,
                cot_prods.cd_produto_comprador,
                pc.marca,
                pc.id_marca,
                pc.quantidade_unidade
            HAVING forn_sint.cd_produto is not null
            ORDER BY cot_prods.ds_produto_comprador ASC
        ";


        $q = $this->db->query($query)->result_array();

        return $q;
    }

    public function queryDeparaApoio($cd_cotacao, $id_Fornecedor, $estado, $exibirEstoque = null, $exibirPreco = null)
    {

        $query = "
    		SELECT 
    			cot.cd_cotacao,
                cot_prods.ds_produto_comprador,
                cot_prods.cd_produto_comprador,
                depara.id_produto_sintese,
                pc.id,
                forn_sint.cd_produto codigo,
                CONCAT(pc.nome_comercial, ' - ', (case when (pc.apresentacao is null OR pc.apresentacao = '') then pc.descricao else pc.apresentacao end)) produto_descricao,
                pc.marca,
                IF(pc.quantidade_unidade is null, 1, pc.quantidade_unidade) quantidade_unidade,
                pc.id_marca,
                marc_sint.id_produto,
                cot.id_fornecedor
    	";

        # Add estoque
        if (isset($exibirEstoque)) {

            $query .= ",
    			(SELECT (SUM(pl.estoque) * IF(pc2.quantidade_unidade is null, 1, pc2.quantidade_unidade))
	                FROM pharmanexo.produtos_lote pl
	                JOIN pharmanexo.produtos_catalogo pc2 ON pc2.codigo = pl.codigo
	                    AND pc2.id_fornecedor = pl.id_fornecedor
	                    AND pc2.ativo = 1
	                    AND pc2.bloqueado = 0
	                WHERE pl.codigo = forn_sint.cd_produto
	                    AND pl.id_fornecedor = forn_sint.id_fornecedor) estoque
	    		";
        }

        # Add preço
        if (isset($exibirPreco)) {

            if ($estado == 'is null') {
                $query .= ",
    			  (SELECT pp.preco_unitario
                    FROM pharmanexo.produtos_preco_max pp
                    WHERE  pp.id_estado is null
                      AND pp.id_fornecedor = forn_sint.id_fornecedor
                      AND pp.codigo = forn_sint.cd_produto
                    LIMIT 1)      preco_unitario
                        ";
            } else {
                $query .= ",
    			  (SELECT pp.preco_unitario
                    FROM pharmanexo.produtos_preco_max pp
                    WHERE (pp.id_estado = {$estado} or pp.id_estado is null)
                      AND pp.id_fornecedor = forn_sint.id_fornecedor
                      AND pp.codigo = forn_sint.cd_produto
                    LIMIT 1)      preco_unitario
                        ";
            }
        }

        $query .= "
            
            FROM cotacoes_apoio.cotacoes cot
            JOIN cotacoes_apoio.cotacoes_produtos cot_prods ON cot_prods.id_cotacao = cot.id
            LEFT JOIN pharmanexo.produtos_clientes_depara depara ON depara.cd_produto = cot_prods.cd_produto_comprador AND depara.id_cliente = cot.id_cliente
            LEFT join pharmanexo.produtos_marca_sintese marc_sint ON marc_sint.id_produto = depara.id_produto_sintese
            LEFT JOIN pharmanexo.produtos_fornecedores_sintese forn_sint ON forn_sint.id_fornecedor = cot.id_fornecedor AND forn_sint.id_sintese = marc_sint.id_sintese
            JOIN pharmanexo.produtos_catalogo pc
                ON pc.codigo = forn_sint.cd_produto
                AND pc.id_fornecedor = forn_sint.id_fornecedor
                AND pc.ativo = 1
                AND pc.bloqueado = 0
            WHERE cot.cd_cotacao = '{$cd_cotacao}' AND cot.id_fornecedor  = {$id_Fornecedor}  and depara.id_integrador = 3
            GROUP BY 
               cot.cd_cotacao,
                forn_sint.cd_produto,
                CONCAT(pc.nome_comercial, ' - ', (case when (pc.apresentacao is null OR pc.apresentacao = '') then pc.descricao else pc.apresentacao end)),
                pc.marca,
                pc.id_marca,
                pc.quantidade_unidade
            HAVING forn_sint.cd_produto is not null
            ORDER BY cot_prods.ds_produto_comprador ASC
        ";

        return $this->db->query($query)->result_array();
    }

    public function queryDeparaHuma($cd_cotacao, $id_Fornecedor, $estado, $exibirEstoque = null, $exibirPreco = null)
    {

        $query = "
    		SELECT 
    			cot.cd_cotacao,
                cot_prods.ds_produto_comprador,
                cot_prods.cd_produto_comprador,
                depara.id_produto_sintese,
                pc.id,
                forn_sint.cd_produto codigo,
                CONCAT(pc.nome_comercial, ' - ', (case when (pc.apresentacao is null OR pc.apresentacao = '') then pc.descricao else pc.apresentacao end)) produto_descricao,
                pc.marca,
                IF(pc.quantidade_unidade is null, 1, pc.quantidade_unidade) quantidade_unidade,
                pc.id_marca,
                marc_sint.id_produto,
                cot.id_fornecedor
    	";

        # Add estoque
        if (isset($exibirEstoque)) {

            $query .= ",
    			(SELECT (SUM(pl.estoque) * IF(pc2.quantidade_unidade is null, 1, pc2.quantidade_unidade))
	                FROM pharmanexo.produtos_lote pl
	                JOIN pharmanexo.produtos_catalogo pc2 ON pc2.codigo = pl.codigo
	                    AND pc2.id_fornecedor = pl.id_fornecedor
	                    AND pc2.ativo = 1
	                    AND pc2.bloqueado = 0
	                WHERE pl.codigo = forn_sint.cd_produto
	                    AND pl.id_fornecedor = forn_sint.id_fornecedor) estoque
	    		";
        }

        # Add preço
        if (isset($exibirPreco)) {

            if (is_null($estado)) {
                $query .= ",
    			  (SELECT pp.preco_unitario
                    FROM pharmanexo.produtos_preco_max pp
                    WHERE  pp.id_estado is null
                      AND pp.id_fornecedor = forn_sint.id_fornecedor
                      AND pp.codigo = forn_sint.cd_produto
                    LIMIT 1)      preco_unitario
                        ";
            } else {
                $query .= ",
    			  (SELECT pp.preco_unitario
                    FROM pharmanexo.produtos_preco_max pp
                    WHERE (pp.id_estado = {$estado} or pp.id_estado is null)
                      AND pp.id_fornecedor = forn_sint.id_fornecedor
                      AND pp.codigo = forn_sint.cd_produto
                    LIMIT 1)      preco_unitario
                        ";
            }
        }

        $query .= "
            
            FROM cotacoes_huma.cotacoes cot
            JOIN cotacoes_huma.cotacoes_produtos cot_prods ON cot_prods.id_cotacao = cot.id
            LEFT JOIN pharmanexo.produtos_clientes_depara depara ON depara.cd_produto = cot_prods.cd_produto_comprador AND depara.id_cliente = cot.id_cliente
            LEFT join pharmanexo.produtos_marca_sintese marc_sint ON marc_sint.id_produto = depara.id_produto_sintese
            LEFT JOIN pharmanexo.produtos_fornecedores_sintese forn_sint ON forn_sint.id_fornecedor = cot.id_fornecedor AND forn_sint.id_sintese = marc_sint.id_sintese
            JOIN pharmanexo.produtos_catalogo pc
                ON pc.codigo = forn_sint.cd_produto
                AND pc.id_fornecedor = forn_sint.id_fornecedor
                AND pc.ativo = 1
                AND pc.bloqueado = 0
            WHERE cot.cd_cotacao = '{$cd_cotacao}' AND cot.id_fornecedor  = {$id_Fornecedor}  and depara.id_integrador = 4
            GROUP BY 
               cot.cd_cotacao,
                forn_sint.cd_produto,
                CONCAT(pc.nome_comercial, ' - ', (case when (pc.apresentacao is null OR pc.apresentacao = '') then pc.descricao else pc.apresentacao end)),
                pc.marca,
                pc.id_marca,
                pc.quantidade_unidade
            HAVING forn_sint.cd_produto is not null
            ORDER BY cot_prods.ds_produto_comprador ASC
        ";

        return $this->db->query($query)->result_array();
    }

    /**
     * Oculta/Desoculta a cotação
     *
     * @param - String - Nome do integrador
     * @param - String - codigo da cotação
     * @param - INT - ID do fornecedor
     * @return  bool
     */
    public function changeHide($integrador, $cd_cotacao, $id_fornecedor)
    {

        $idForns = [$this->session->id_fornecedor];
        $forn = $this->db
            ->where('id_matriz', $_SESSION['id_matriz'])
            ->get('fornecedores')
            ->result_array();

        if (!empty($forn)) {
            unset($idForns);
            foreach ($forn as $f) {
                $idForns[] = intval($f['id']);
            }
        }

        switch (strtoupper($integrador)) {
            case 'SINTESE':
                $dbcot = $this->DB_SINTESE;
                break;
            case 'BIONEXO':
                $dbcot = $this->DB_BIONEXO;
                break;
            case 'APOIO':
                $dbcot = $this->DB_APOIO;
                break;
            case 'HUMA':
                $dbcot = $this->DB_HUMA;
                break;
        }

        $dbcot->where('cd_cotacao', $cd_cotacao);
        $dbcot->where_in('id_fornecedor', $idForns);
        $cotacao = $dbcot->get('cotacoes')->row_array();

        $valor = ($cotacao['oculto'] == 1) ? 0 : 1;

        $dbcot->where('cd_cotacao', $cd_cotacao);
        $dbcot->where_in('id_fornecedor', $idForns);
        $update = $dbcot->update('cotacoes', ['oculto' => $valor]);

        if ($update) {

            return true;
        } else {

            return false;
        }
    }

    /**
     *  Obtem as formas de pagamento
     *
     * Array INT start, INT length
     * @return  json
     */
    public function selectFormaPagamento($data)
    {

        return $this->select2->exec(
            array_merge($this->input->get(), $data),
            "formas_pagamento",
            [['db' => 'id', 'dt' => 'id'], ['db' => 'descricao', 'dt' => 'descricao']]
        );
    }

    /**
     * Obtem os fornecedores que possuem registro da cotação para utilizar no select
     *
     * @param String nome do integrador
     * @param String codigo da cotação
     * @return array
     */
    public function selectFornecedores($integrador, $cd_cotacao)
    {

        if ($this->session->has_userdata('id_matriz')) {


            switch (strtoupper($integrador)) {
                case 'SINTESE':

                    # Obtem os fornecedores
                    $this->DB_SINTESE->select("cot.id_fornecedor, f.nome_fantasia");
                    $this->DB_SINTESE->from("cotacoes cot");
                    $this->DB_SINTESE->join("pharmanexo.fornecedores f", "f.id = cot.id_fornecedor");
                    $this->DB_SINTESE->where('cot.cd_cotacao', $cd_cotacao);
                    $this->DB_SINTESE->where('f.id_matriz', $this->session->id_matriz);
                    $lista_fornecedores = $this->DB_SINTESE->get()->result_array();

                    break;
                case 'BIONEXO':
                    $this->DB_BIONEXO->select("cot.id_fornecedor, f.nome_fantasia");
                    $this->DB_BIONEXO->from("cotacoes cot");
                    $this->DB_BIONEXO->join("pharmanexo.fornecedores f", "f.id = cot.id_fornecedor");
                    $this->DB_BIONEXO->where('cot.cd_cotacao', $cd_cotacao);
                    $this->DB_BIONEXO->where('f.id_matriz', $this->session->id_matriz);
                    $lista_fornecedores = $this->DB_BIONEXO->get()->result_array();
                    break;
                case 'APOIO':
                    $this->DB_APOIO->select("cot.id_fornecedor, f.nome_fantasia");
                    $this->DB_APOIO->from("cotacoes cot");
                    $this->DB_APOIO->join("pharmanexo.fornecedores f", "f.id = cot.id_fornecedor");
                    $this->DB_APOIO->where('cot.cd_cotacao', $cd_cotacao);
                    $this->DB_APOIO->where('f.id_matriz', $this->session->id_matriz);
                    $lista_fornecedores = $this->DB_APOIO->get()->result_array();
                    break;
            }


            if (isset($lista_fornecedores) && !empty($lista_fornecedores)) {
                $list = [];

                foreach ($lista_fornecedores as $f) {

                    $list[] = [
                        'id' => $f['id_fornecedor'],
                        'fornecedor' => $f['nome_fantasia']
                    ];
                }

                return $list;
            } else {
                return null;
            }
        }

        return null;
    }

    /**
     * Cria um alerta para o fornecedor que a cotação possui produtos sem estoque
     *
     * @param - Array de produtos
     * @return  bool
     */
    public function stockNotification($cd_cotacao, $produtos)
    {

        if (!isset($produtos) || empty($produtos)) {

            return false;
        } else {

            $usuariosFornecedor = $this->usuarios->listarFornecedorUsers($this->session->id_fornecedor);

            foreach ($usuariosFornecedor as $usuario) {

                $total = count($produtos);
                $token = base64_encode("SEM_ESTOQUE@{$usuario['id']}_{$cd_cotacao}_{$this->session->id_fornecedor}");
                $message = "Existem {$total} produtos sem estoque na cotação {$cd_cotacao}! Clique para ver mais";
                $url = base_url("fornecedor/relatorios/sem_estoque/details/{$cd_cotacao}");

                $this->notify->alertFornecedor('warning', $usuario['id'], $this->session->id_fornecedor, $message, $token, $url);
            }

            return true;
        }
    }

    /**
     * Obtem a string da condição de estado para obter preço
     *
     * @param - INT ID do fornecedor
     * @param - Int ID do estado do comprador
     * @return  string
     */
    public function getEstadoFornecedor($id_fornecedor, $id_estado)
    {

        if (in_array($id_fornecedor, explode(',', ONCOPROD)) || in_array($id_fornecedor, explode(',', ONCOEXO))) {

            $estado = " = {$id_estado}";
        } else {

            if ($id_fornecedor == 20) {

                $estado = " is null";
            } elseif ($id_fornecedor == 180) {

                if ($id_estado != 17) {
                    $estado = " is null";
                } else {
                    $estado = " = {$id_estado}";
                }
            } elseif ($id_fornecedor == 15 || $id_fornecedor == 25) {
                if ($id_estado == 15 || $id_estado == 17) {
                    $estado = " = {$id_estado}";
                } else {
                    $estado = " is null";
                }
            } elseif ($id_fornecedor == 5025) {

                $estado = " is null";
            } else {

                $estado = " is null";
            }
        }

        return $estado;
    }

    /**
     * Obtem o ultimo preço ofertado do produto para aquele comprador
     *
     * @param - String codigo da cotação
     * @param - INT ID do fornecedor
     * @param - INT ID do comprador
     * @return  decimal/null
     */
    public function getUltimaOfertaProdutoComprador($codigo, $id_fornecedor, $id_cliente)
    {

        # Verifica se o produto ganhou na OC
        $this->db->select("Vl_Preco_Produto");
        $this->db->from("ocs_sintese ocs");
        $this->db->join("ocs_sintese_produtos prods", "prods.id_ordem_compra = ocs.id");
        $this->db->where("ocs.id_fornecedor", $id_fornecedor);
        $this->db->where("ocs.id_comprador", $id_cliente);
        $this->db->where("prods.codigo", $codigo);
        $this->db->order_by("ocs.Dt_Gravacao DESC");
        $oc = $this->db->get()->row_array();

        if (isset($oc['Vl_Preco_Produto']) && !empty($oc['Vl_Preco_Produto'])) {

            return "Ultima oferta ganhadora: R$ " . number_format($oc['Vl_Preco_Produto'], 4, ',', '.');
        } else {

            # Procura o ultimo preço ofertado
            $this->db->select("preco_marca");
            $this->db->where('id_fornecedor', $id_fornecedor);
            $this->db->where('id_cliente', $id_cliente);
            $this->db->where('id_pfv', $codigo);
            $this->db->order_by("data_criacao DESC");
            $ultimaOferta = $this->db->get('cotacoes_produtos')->row_array();

            if (isset($ultimaOferta['preco_marca']) && !empty($ultimaOferta['preco_marca'])) {

                return "Ultima oferta: R$ " . number_format($ultimaOferta['preco_marca'], 4, ',', '.');
            } else {

                return "Sem oferta";
            }
        }
    }

    /**
     * Obtem a venda diferencia por comprador ou estado
     *
     * @param - int - id do fornecedor
     * @param - int - codigo do produto
     * @param - int - id do cliente
     * @param - int - id do estado
     * @return  objeto
     */
    public function getVendaDiferenciada($id_fornecedor, $codigo, $id_cliente, $id_estado)
    {
        $this->db->select('*');
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('codigo', $codigo);
        $this->db->where_not_in('regra_venda', 2);
        $this->db->group_start();
        $this->db->where('id_cliente', $id_cliente);
        $this->db->or_where('id_estado', $id_estado);
        $this->db->group_end();

        $vd = $this->db->get('vendas_diferenciadas')->row_array();

        return (isset($vd) && !empty($vd)) ? $vd : null;
    }

    /**
     * Busca no sistema a forma de pagamento e caso for bionexo converte
     *
     * @param - String nome do integrador
     * @param - INT ID do comprador
     * @param - INT ID do fornecedor
     * @param - INT ID do estado do comprador
     * @return  int/null
     */
    public function getFormaPagamento($integrador, $id_cliente, $id_fornecedor, $id_estado)
    {

        switch (strtoupper($integrador)) {
            case 'SINTESE':
                # Se não informou, obtem pelo fornecedor
                $this->db->select("*");
                $this->db->where("id_fornecedor", $id_fornecedor);
                $this->db->group_start();
                $this->db->where('id_cliente', $id_cliente);
                $this->db->or_where('id_estado', $id_estado);
                $this->db->group_end();

                $forma_pagamento = $this->db->get('formas_pagamento_fornecedores')->row_array();

                return (isset($forma_pagamento) && !empty($forma_pagamento)) ? $forma_pagamento['id_forma_pagamento'] : null;
                break;
            case 'BIONEXO':
                # Se não informou, obtem pelo fornecedor
                $this->db->select("*");
                $this->db->where("id_fornecedor", $id_fornecedor);
                $this->db->group_start();
                $this->db->where('id_cliente', $id_cliente);
                $this->db->or_where('id_estado', $id_estado);
                $this->db->group_end();

                $forma_pagamento = $this->db->get('formas_pagamento_fornecedores')->row_array();

                if (isset($forma_pagamento) && !empty($forma_pagamento)) {

                    $this->db->where('integrador', 2);
                    $this->db->where('id_forma_pagamento', $forma_pagamento['id_forma_pagamento']);
                    $formaBionexo = $this->db->get('formas_pagamento_depara')->row_array();

                    $forma_pagamento = (isset($formaBionexo) && !empty($formaBionexo)) ? $formaBionexo['cd_forma_pagamento'] : null;
                }

                return $forma_pagamento;
                break;
            case 'APOIO':
                # Se não informou, obtem pelo fornecedor
                $this->db->select("*");
                $this->db->where("id_fornecedor", $id_fornecedor);
                $this->db->group_start();
                $this->db->where('id_cliente', $id_cliente);
                $this->db->or_where('id_estado', $id_estado);
                $this->db->group_end();

                $forma_pagamento = $this->db->get('formas_pagamento_fornecedores')->row_array();

                if (isset($forma_pagamento) && !empty($forma_pagamento)) {

                    $this->db->where('integrador', 3);
                    $this->db->where('id_forma_pagamento', $forma_pagamento['id_forma_pagamento']);
                    $formaBionexo = $this->db->get('formas_pagamento_depara')->row_array();

                    $forma_pagamento = (isset($formaBionexo) && !empty($formaBionexo)) ? $formaBionexo['cd_forma_pagamento'] : null;
                }

                return $forma_pagamento;
                break;
            case 'HUMA':
                # Se não informou, obtem pelo fornecedor
                $this->db->select("*");
                $this->db->where("id_fornecedor", $id_fornecedor);
                $this->db->group_start();
                $this->db->where('id_cliente', $id_cliente);
                $this->db->or_where('id_estado', $id_estado);
                $this->db->group_end();

                $forma_pagamento = $this->db->get('formas_pagamento_fornecedores')->row_array();

                if (isset($forma_pagamento) && !empty($forma_pagamento)) {

                    $this->db->where('integrador', 4);
                    $this->db->where('id_forma_pagamento', $forma_pagamento['id_forma_pagamento']);
                    $formaBionexo = $this->db->get('formas_pagamento_depara')->row_array();

                    $forma_pagamento = (isset($formaBionexo) && !empty($formaBionexo)) ? $formaBionexo['cd_forma_pagamento'] : null;
                }

                return $forma_pagamento;
                break;
        }
    }

    /**
     * Obtem o ID do prazo de entrega
     *
     * @param - Array POST da requisição
     * @param - INT ID do comprador
     * @param - INT ID do fornecedor
     * @param - INT ID do estado do comprador
     * @return  int/null
     */
    public function getPrazoEntrega($post, $id_cliente, $id_fornecedor, $id_estado)
    {

        $prazo_entrega = null;

        # Verifica se o usuario informou o prazo de entrega
        if (isset($post['prazo_entrega']) && !empty($post['prazo_entrega'])) {

            $prazo_entrega = $post['prazo_entrega'];
        } else {

            # Se não informou, obtem pelo fornecedor
            $this->db->select("*");
            $this->db->where("id_fornecedor", $id_fornecedor);
            $this->db->group_start();
            $this->db->where('id_cliente', $id_cliente);
            $this->db->or_where('id_estado', $id_estado);
            $this->db->group_end();

            $prazo_entrega = $this->db->get('prazos_entrega')->row_array()['prazo'];
        }

        return (isset($prazo_entrega) && !empty($prazo_entrega)) ? $prazo_entrega : null;
    }

    /**
     * Obtem o valor minimo
     *
     * @param - INT ID do comprador
     * @param - INT ID do fornecedor
     * @param - INT ID do estado do comprador
     * @return  number/null
     */
    public function getValorMinimo($id_cliente, $id_fornecedor, $id_estado)
    {

        # Obtem o valor Minimo por comprador ou pelo seu estado

        $this->db->select("*");
        $this->db->where("id_fornecedor", $id_fornecedor);
        $this->db->group_start();
        $this->db->where('id_cliente', $id_cliente);
        $this->db->or_where('id_estado', $id_estado);
        $this->db->group_end();

        $valor_minimo = $this->db->get('valor_minimo_cliente')->row_array();

        return (isset($valor_minimo) && !empty($valor_minimo['valor_minimo'])) ? $valor_minimo['valor_minimo'] : null;
    }

    /**
     * Verifica se existe restrição de comprador ou estado para o produto
     *
     * @param - INT codigo do produto
     * @param - INT ID do fornecedor
     * @param - INT ID do comprador
     * @param - INT ID do estado do comprador
     * @return  int
     */
    public function getRestricao($codigo, $id_fornecedor, $id_cliente, $id_estado)
    {
        # Restrição do produto
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('id_produto', $codigo);
        $this->db->group_start();
        $this->db->where('id_cliente', $id_cliente);
        $this->db->or_where('id_estado', $id_estado);
        $this->db->group_end();

        $restricao = $this->db->get('restricoes_produtos_clientes')->row_array();

        return (isset($restricao) && !empty($restricao)) ? 1 : 0;
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

        if (isset($qtd_unidade) && $qtd_unidade > 0) {

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
     * Ajusta o preço do produto para unitario e aplica o desconto da venda diferenciada caso exista
     *
     * @param - number preço produto
     * @param - INT quantidade unidade
     * @param - INT ID do fornecedor
     * @param - Array Venda diferenciada
     * @return  number
     */
    public function getPriceUnitary($preco, $quantidade_unidade, $id_fornecedor, $vd)
    {

        if (in_array($id_fornecedor, explode(',', ONCOPROD))) {

            $preco_unitario = ($preco / $quantidade_unidade);
        } else if (($id_fornecedor == 20) || ($id_fornecedor == 104)) {

            $preco_unitario = ($preco / $quantidade_unidade);
        } else {

            $preco_unitario = $preco;
        }


        if (isset($vd) and !empty($vd['desconto_percentual'])) {

            $preco_unitario = $preco_unitario - ($preco_unitario * (floatval($vd['desconto_percentual']) / 100));
        }

        return $preco_unitario;
    }

    /**
     * Desoculta as cotações marcadas na tela de cotações ocultadas
     *
     * @param - POST - Array(String cd_cotacao, String integrador)
     * @return  bool
     */
    public function unhide($post)
    {
        $this->db->trans_begin();

        if ($this->session->has_userdata('id_matriz')) {
            $fornecedores = $this->db->select('id')->where('id_matriz', $this->session->id_matriz)->get('fornecedores')->result_array();
        }

        foreach ($post as $cotacao) {

            switch (strtoupper($cotacao['integrador'])) {
                case 'SINTESE':
                    $dbcot = $this->DB_SINTESE;
                    break;
                case 'BIONEXO':
                    $dbcot = $this->DB_BIONEXO;
                    break;
                case 'APOIO':
                    $dbcot = $this->DB_APOIO;
                    break;
            }

            if (!empty($fornecedores)) {
                foreach ($fornecedores as $fornecedor) {
                    $dbcot
                        ->where("id_fornecedor", $fornecedor['id'])
                        ->where("cd_cotacao", $cotacao['cd_cotacao'])
                        ->update('cotacoes', [
                            'oculto' => 0,
                            'motivo_recusa' => 0,
                            'data_recusa' => NULL,
                            'usuario_recusa' => NULL,
                            'obs_recusa' => NULL,
                        ]);
                }
            } else {
                $dbcot
                    ->where("id_fornecedor", $this->session->id_fornecedor)
                    ->where("cd_cotacao", $cotacao['cd_cotacao'])
                    ->update('cotacoes', [
                        'oculto' => 0,
                        'motivo_recusa' => 0,
                        'data_recusa' => NULL,
                        'usuario_recusa' => NULL,
                        'obs_recusa' => NULL,
                    ]);
            }
        }

        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();

            return false;
        } else {

            $this->db->trans_commit();

            return true;
        }
    }

    /**
     * Obtem a marca de um produto
     *
     * @param - INT ID da marca
     * @return  string
     */
    public function getMarca($id_marca)
    {
        if (isset($id_marca) && !empty($id_marca)) {

            $marca = $this->marca->get_row($id_marca)['marca'];
        } else {

            $marca = "Sem De -> Para de Marca";
        }

        return $marca;
    }

    /**
     * Obtem a cotação
     *
     * @param - String nome do integrador
     * @param - String codigo da cotação
     * @param - INT ID do fornecedor
     * @return  array/false
     */
    public function getCotacao($integrador, $cd_cotacao, $id_fornecedor)
    {
        switch (strtoupper($integrador)) {
            case 'SINTESE':
                $this->DB_SINTESE->where('cd_cotacao', $cd_cotacao);
                $this->DB_SINTESE->where_in('id_fornecedor', $id_fornecedor);
                $cotacao = $this->DB_SINTESE->get('cotacoes')->row_array();
                break;
            case 'BIONEXO':
                $this->DB_BIONEXO->where('cd_cotacao', $cd_cotacao);
                $this->DB_BIONEXO->where_in('id_fornecedor', $id_fornecedor);
                $cotacao = $this->DB_BIONEXO->get('cotacoes')->row_array();
                break;
            case 'APOIO':

                $this->DB_APOIO->where('cd_cotacao', $cd_cotacao);
                $this->DB_APOIO->where_in('id_fornecedor', $id_fornecedor);
                $cotacao = $this->DB_APOIO->get('cotacoes')->row_array();

                break;
            case 'HUMA':
                $this->DB_HUMA->where('cd_cotacao', $cd_cotacao);
                $this->DB_HUMA->where_in('id_fornecedor', $id_fornecedor);
                $cotacao = $this->DB_HUMA->get('cotacoes')->row_array();
                break;
        }

        if (isset($cotacao) && !empty($cotacao)) {

            return $cotacao;
        } else {

            return false;
        }
    }

    /**
     * Obtem o historico de ofertas de um produto
     *
     * @param - String nome do integrador
     * @param - INT codigo do produto do fornecedor
     * @param - INT ID do fornecedor
     * @param - INT ID do comprador
     * @return  array
     */
    public function getHistory($integrador, $codigo, $id_fornecedor, $id_cliente)
    {
        $this->db->select("id, cd_cotacao, preco_marca AS preco, data_cotacao");
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('id_pfv', $codigo);
        $this->db->where('id_cliente', $id_cliente);
        $this->db->where('integrador', strtoupper($integrador));
        $this->db->order_by("data_cotacao desc");
        $this->db->limit(6);
        $ofertas = $this->db->get('cotacoes_produtos');

        if ($ofertas->num_rows() > 0) {

            $ofertas = $ofertas->result_array();

            foreach ($ofertas as $kk => $row) {

                $ofertas[$kk]['data'] = date("d/m/Y H:i", strtotime($row['data_cotacao']));
                $ofertas[$kk]['preco_marca'] = number_format($row['preco'], 4, ',', '.');
            }

            $soma = array_sum(array_column($ofertas, 'preco'));

            $media = $soma / count($ofertas);

            $data = ['data' => $ofertas, 'media' => round($media, 2)];
        } else {

            $data = ['data' => 0, 'media' => 0];
        }

        return $data;
    }

    /**
     * Cria se não existir o depara do produto (sintese ou bionexo) com o do distribuidor (fornecedor)
     *
     * @param - String nome do integrador
     * @param - Array produtos enviados via POST
     * @return  bool
     */
    public function depara($integrador, $post)
    {

        $this->db->trans_begin();


        switch (strtoupper($integrador)) {
            case 'SINTESE':
                foreach ($post['dados'] as $row) {

                    $this->db->where('id_fornecedor', $row['id_fornecedor']);
                    $this->db->where('cd_produto', $row['cd_produto']);
                    $this->db->where('id_sintese', $row['id_sintese']);
                    $old = $this->db->get('produtos_fornecedores_sintese')->row_array();

                    if (empty($old)) {

                        $produtoSint = $this->db
                            ->where('id_produto', $row['id_sintese'])
                            ->limit(1)
                            ->get('produtos_marca_sintese')
                            ->row_array();

                        if (!empty($produtoSint)) {
                            $data = [
                                "id_sintese" => $produtoSint['id_sintese'],
                                "cd_produto" => $row['cd_produto'],
                                "id_fornecedor" => $row['id_fornecedor'],
                                "id_usuario" => $this->session->id_usuario,
                            ];
                            $this->db->insert('produtos_fornecedores_sintese', $data);
                        } else {
                            $getProd = $this->getProdSintese($row['id_sintese']);
                            if ($getProd != FALSE) {
                                $data = [
                                    "id_sintese" => $getProd['id_sintese'],
                                    "cd_produto" => $row['cd_produto'],
                                    "id_fornecedor" => $row['id_fornecedor'],
                                    "id_usuario" => $this->session->id_usuario,
                                ];
                                $this->db->insert('produtos_fornecedores_sintese', $data);
                            }
                        }
                    }
                }
                break;
            case 'BIONEXO':
                foreach ($post['dados'] as $row) {

                    $produtoForn = $this->db
                        ->where('cd_produto', $row['cd_produto'])
                        ->where('id_fornecedor', $this->session->id_fornecedor)
                        ->limit(1)
                        ->get('produtos_fornecedores_sintese')
                        ->row_array();

                    if (!empty($produtoForn)) {
                        $produtoSint = $this->db
                            ->where('id_sintese', $produtoForn['id_sintese'])
                            ->limit(1)
                            ->get('produtos_marca_sintese')
                            ->row_array();
                    }

                    if (!empty($produtoSint)) {

                        $this->db->where('id_cliente', $row['id_cliente']);
                        $this->db->where('cd_produto', $row['id_sintese']);
                        $this->db->where('id_produto_sintese', $produtoSint['id_produto']);
                        $this->db->where('id_integrador', 2);
                        $old = $this->db->get('produtos_clientes_depara');
                        $data = [
                            "id_produto_sintese" => $produtoSint['id_produto'],
                            "cd_produto" => $row['id_sintese'],
                            "id_usuario" => $this->session->id_usuario,
                            "id_integrador" => 2,
                            "id_cliente" => $row['id_cliente']
                        ];
                        if ($old->num_rows() == 0) {
                            $this->pcd->insert($data);
                        }
                    } else {

                        $this->db->trans_rollback();
                        return false;
                    }
                }
                break;
            case 'APOIO':
                foreach ($post['dados'] as $row) {

                    $produtoForn = $this->db
                        ->where('cd_produto', $row['cd_produto'])
                        ->where('id_fornecedor', $this->session->id_fornecedor)
                        ->limit(1)
                        ->get('produtos_fornecedores_sintese')
                        ->row_array();


                    if (!empty($produtoForn)) {
                        $produtoSint = $this->db
                            ->where('id_sintese', $produtoForn['id_sintese'])
                            ->limit(1)
                            ->get('produtos_marca_sintese')
                            ->row_array();
                    }

                    if (!empty($produtoSint)) {

                        $this->db->where('id_cliente', $row['id_cliente']);
                        $this->db->where('cd_produto', $row['id_sintese']);
                        $this->db->where('id_produto_sintese', $produtoSint['id_produto']);
                        $this->db->where('id_integrador', 3);
                        $old = $this->db->get('produtos_clientes_depara');
                        $data = [
                            "id_produto_sintese" => $produtoSint['id_produto'],
                            "cd_produto" => $row['id_sintese'],
                            "id_usuario" => $this->session->id_usuario,
                            "id_integrador" => 3,
                            "id_cliente" => $row['id_cliente']
                        ];
                        if ($old->num_rows() == 0) {
                            $this->pcd->insert($data);
                        }
                    } else {

                        $this->db->trans_rollback();
                        return false;
                    }
                }
                break;
            case 'HUMA':
                foreach ($post['dados'] as $row) {

                    $produtoForn = $this->db
                        ->where('cd_produto', $row['cd_produto'])
                        ->where('id_fornecedor', $this->session->id_fornecedor)
                        ->limit(1)
                        ->get('produtos_fornecedores_sintese')
                        ->row_array();

                    if (!empty($produtoForn)) {
                        $produtoSint = $this->db
                            ->where('id_sintese', $produtoForn['id_sintese'])
                            ->limit(1)
                            ->get('produtos_marca_sintese')
                            ->row_array();

                        if (!empty($produtoSint)) {

                            $this->db->where('id_cliente', $row['id_cliente']);
                            $this->db->where('cd_produto', $row['id_sintese']);
                            $this->db->where('id_produto_sintese', $produtoSint['id_produto']);
                            $this->db->where('id_integrador', 4);
                            $old = $this->db->get('produtos_clientes_depara');

                            $data = [
                                "id_produto_sintese" => $produtoSint['id_produto'],
                                "cd_produto" => $row['id_sintese'],
                                "id_usuario" => $this->session->id_usuario,
                                "id_integrador" => 4,
                                "id_cliente" => $row['id_cliente']
                            ];

                            if ($old->num_rows() == 0) {
                                $this->pcd->insert($data);
                            }

                        } else {

                            $this->db->trans_rollback();
                            return ['type' => 'error', 'message' => 'Produto base não localizado'];
                        }
                    } else {
                        return ['type' => 'error', 'message' => 'Produto sem De/Para incial. Acesso o menu PRODUTOS -> DEPARA'];
                    }

                }
                break;
        }


        if ($this->db->trans_status() === false) {

            $this->db->trans_rollback();

            return false;
        } else {

            $this->db->trans_commit();

            return true;
        }
    }

    private function getProdSintese($id_produto)
    {

        /* TODO: mudar para config de banco */
        $client = new SoapClient("https://ws-sintese.bionexo.com/IntegrationService.asmx?WSDL");
        $location = 'https://ws-sintese.bionexo.com/IntegrationService.asmx';

        $forn = $this->fornecedor->findById($this->session->id_fornecedor);

        $function = 'ObterProdutos';
        $arguments = array('ObterProdutos' => array(
            'cnpj' => preg_replace("/\D+/", "", $forn['cnpj']),
            'chave' => $forn['chave_sintese'],
            'codigoProdutoSintese' => $id_produto,
        ));


        $options = array('location' => $location);
        $result = $client->__soapCall($function, $arguments, $options);

        $xml = new SimpleXMLElement($result->ObterProdutosResult);

        if (isset($xml->Produto)) {
            $xml = json_decode(json_encode($xml), true);
            $produto = $xml['Produto'];

            $produtosMarca = (isset($produto['Produtos_Marca'][0])) ? $produto['Produtos_Marca'][0]['Produto_Marca'] : $produto['Produtos_Marca']['Produto_Marca'];


            if (!isset($produtosMarca[0])) {
                $aux = $produtosMarca;

                unset($produtosMarca);

                $produtosMarca[0] = $aux;
            }

            if (isset($produtosMarca) && !empty($produtosMarca)) {

                $prod = $produtosMarca[0];

                $data = [
                    'id_produto' => $id_produto,
                    'descricao' => strtoupper(utf8_decode($produto['Ds_Produto'])),
                    'id_grupo' => 999,
                    'id_sintese' => $prod['Id_Produto_Marca'],
                    'ativo' => 1
                ];

                if (!$this->db->insert('produtos_marca_sintese', $data)) {
                    $lista_adms = $this->usuario->listAdmMaster();

                    foreach ($lista_adms as $adm) {

                        # Notifica o ADM master sobre o produto
                        $this->notify->alert([
                            'type' => 'danger',
                            'id_usuario' => $adm['id'],
                            'id_fornecedor' => null,
                            'message' => "Produto sintese não encontrado em nosso banco de dados. #ID PRODUTO: {$id_produto}.",
                            'url' => '',
                            'status' => 0
                        ]);
                    }

                    return false;
                } else {
                    return $data;
                }
            }
        }
    }

    /**
     * Cria o XML da cotação
     *
     * @param - Array ajax form
     * @param - Array das ofertas organizados por fornecedor e cd_produto comprador
     * @return  array
     */
    public function createXML($post, $produtos, $integrador)
    {
        $xmls = [];

        switch (strtoupper($integrador)) {
            case 'SINTESE':
                # Percorre os fornecedores selecionados para cotar
                foreach ($produtos as $id_fornecedor => $produtos_sintese) {

                    $fornecedor = $this->fornecedores->findById($id_fornecedor);

                    $dom = new DOMDocument("1.0", "ISO-8859-1");
                    $dom->formatOutput = true;
                    $root = $dom->createElement("Cotacao");

                    #informações do cabeçalho
                    $root->appendChild($dom->createElement("Tp_Movimento", 'I'));
                    $root->appendChild($dom->createElement("Dt_Gravacao", date("d/m/Y H:i:s")));
                    $root->appendChild($dom->createElement("Cd_Fornecedor", preg_replace("/\D+/", "", $fornecedor['cnpj'])));
                    $root->appendChild($dom->createElement("Cd_Cotacao", $post['cd_cotacao']));
                    $root->appendChild($dom->createElement("Cd_Condicao_Pagamento", $post['id_forma_pagamento']));
                    $root->appendChild($dom->createElement("Nm_Usuario", 'PHARMAINT321'));
                    $root->appendChild($dom->createElement("Ds_Observacao_Fornecedor", utf8_encode($post['obs'])));
                    $root->appendChild($dom->createElement("Qt_Prz_Minimo_Entrega", $post['prazo_entrega']));
                    $root->appendChild($dom->createElement("Sn_Permite_Alterar_Oferta", 'Nao'));
                    $root->appendChild($dom->createElement("Vl_Minimo_Pedido", str_replace(".", ",", $produtos_sintese['valor_minimo'])));

                    $produtos = $dom->createElement("Produtos_Cotacao");

                    # Percorre os produtos da sintese
                    foreach ($produtos_sintese['produtos'] as $k => $produto) {

                        $produto_cotacao = $dom->createElement("Produto_Cotacao");

                        $id_produto_sintese = $dom->createElement("Id_Produto_Sintese", $produto['id_produto_sintese']);
                        $cd_produto_comprador = $dom->createElement("Cd_Produto_Comprador", $produto['cd_produto_comprador']);
                        $produto_cotacao->appendChild($id_produto_sintese);
                        $produto_cotacao->appendChild($cd_produto_comprador);

                        $marcas_ofertas = $dom->createElement("Marcas_Oferta");

                        # Percorre as marcas para cada produto sintese
                        foreach ($produto['marcas'] as $p) {

                            $marca_oferta = $dom->createElement("Marca_Oferta");

                            $catalogo = $this->catalogo->find("marca, id_marca", "codigo = {$p['id_pfv']} AND id_fornecedor = {$p['id_fornecedor']}", true);

                            $id_marca = $dom->createElement("Id_Marca", $catalogo['id_marca']);
                            $ds_marca = $dom->createElement("Ds_Marca", htmlspecialchars($catalogo['marca']));
                            $qt_embalagem = $dom->createElement("Qt_Embalagem", $p['qtd_embalagem']);
                            $pr_unidade = $dom->createElement("Vl_Preco_Produto", number_format($p['preco_marca'], 4, ',', '.'));
                            $cd_produto = $dom->createElement("Cd_ProdutoERP", $p['id_pfv']);

                            $marca_oferta->appendChild($id_marca);
                            $marca_oferta->appendChild($ds_marca);
                            $marca_oferta->appendChild($qt_embalagem);
                            $marca_oferta->appendChild($pr_unidade);

                            $ds_obs_fornecedor = $dom->createElement("Ds_Obs_Oferta_Fornecedor", $p['obs_produto']);
                            $marca_oferta->appendChild($ds_obs_fornecedor);

                            $marca_oferta->appendChild($cd_produto);
                            $marcas_ofertas->appendChild($marca_oferta);
                        }

                        $produto_cotacao->appendChild($marcas_ofertas);
                        $produtos->appendChild($produto_cotacao);
                    }

                    $root->appendChild($produtos);
                    $dom->appendChild($root);

                    $dom->preserveWhiteSpace = false;

                    $simpleXML = new SimpleXMLElement($dom->saveXML());

                    $dom_xml = trim(str_replace("<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>", "", $simpleXML->asXML()));

                    $filename = "public/cotacoes_enviadas/{$id_fornecedor}_{$post['cd_cotacao']}.xml";

                    if (file_exists($filename)) {
                        unlink($filename);
                    }

                    $fl = fopen($filename, "w+");

                    fwrite($fl, $dom_xml);

                    fclose($fl);

                    chmod($filename, 0777);

                    $xmls[] = ['id_fornecedor' => $id_fornecedor, 'xml' => $filename];
                }
                break;
            case 'BIONEXO':
                # Percorre os fornecedores selecionados para cotar
                foreach ($produtos as $id_fornecedor => $produtos_bionexo) {

                    $fornecedor = $this->fornecedores->findById($id_fornecedor);

                    $dom = new DOMDocument();
                    $dom->formatOutput = true;

                    $resposta = $dom->createElement("Resposta");

                    # Adiciona as informações do cabeçalho
                    $cabecalho = $dom->createElement("Cabecalho");
                    $cabecalho->appendChild($dom->createElement("Id_Pdc", $post['cd_cotacao']));
                    $cabecalho->appendChild($dom->createElement("CNPJ_Hospital", mask($post['cnpj_comprador'], '##.###.###/####-##')));
                    $cabecalho->appendChild($dom->createElement("Faturamento_Minimo", $produtos_bionexo['valor_minimo']));
                    $cabecalho->appendChild($dom->createElement("Prazo_Entrega", $post['prazo_entrega']));
                    $cabecalho->appendChild($dom->createElement("Validade_Proposta", date('d/m/Y', strtotime('+8 days', strtotime($post['dt_fim_cotacao'])))));
                    $cabecalho->appendChild($dom->createElement("Id_Forma_Pagamento", $post['id_forma_pagamento']));
                    $cabecalho->appendChild($dom->createElement("Frete", 'CIF'));
                    $cabecalho->appendChild($dom->createElement("Observacoes", $post['obs']));

                    # Cria o elemento dos itens da cotação
                    $itens_pdc = $dom->createElement("Itens_Pdc");

                    # Percorre os produtos inserindo no lemento pai (Itens_Pdc)
                    foreach ($produtos_bionexo['produtos'] as $k => $produto) {

                        $this->DB_BIONEXO->where('id_cotacao', $post['id_cotacao']);
                        $this->DB_BIONEXO->where('cd_produto_comprador', $produto['cd_produto_comprador']);
                        $prod = $this->DB_BIONEXO->get('cotacoes_produtos')->row_array();

                        # Percorre as marcas para cada produto
                        foreach ($produto['marcas'] as $p) {

                            $item = $dom->createElement("Item_Pdc");


                            $catalogo = $this->catalogo->find("marca, nome_comercial, unidade", "codigo = {$p['id_pfv']} AND id_fornecedor = {$p['id_fornecedor']}", true);


                            $item->appendChild($dom->createElement('Sequencia', $prod['sequencia']));
                            $item->appendChild($dom->createElement('Id_Artigo', $prod['id_artigo']));
                            $item->appendChild($dom->createElement('Codigo_Produto', $p['cd_produto_comprador']));
                            $item->appendChild($dom->createElement('Preco_Unitario', $p['preco_marca']));
                            $item->appendChild($dom->createElement('Fabricante', utf8_encode(utf8_decode(htmlspecialchars("{$catalogo['nome_comercial']} - {$catalogo['marca']}")))));
                            $item->appendChild($dom->createElement('Embalagem', $catalogo['unidade']));
                            $item->appendChild($dom->createElement('Quantidade_Embalagem', $p['qtd_embalagem']));
                            $item->appendChild($dom->createElement('Comentario', $p['obs_produto']));

                            $extra = $dom->createElement('Campo_Extra');
                            $extra->appendChild($dom->createElement('Nome', "Codigo_Produto_Fornecedor"));
                            $extra->appendChild($dom->createElement('Valor', $p['id_pfv']));

                            $item->appendChild($extra);

                            $itens_pdc->appendChild($item);
                        }
                    }

                    # Monta o XML
                    $resposta->appendChild($cabecalho);
                    $resposta->appendChild($itens_pdc);
                    $dom->appendChild($resposta);

                    $dom->preserveWhiteSpace = false;

                    $simpleXML = new SimpleXMLElement($dom->saveXML());

                    $dom_xml = $simpleXML->asXML();

                    $filename = "public/cotacoes_enviadas/{$id_fornecedor}_{$post['cd_cotacao']}.xml";

                    if (file_exists($filename)) {
                        unlink($filename);
                    }

                    $fl = fopen($filename, "w+");

                    fwrite($fl, $dom_xml);

                    fclose($fl);

                    chmod($filename, 0777);

                    $xmls[] = ['id_fornecedor' => $id_fornecedor, 'xml' => $filename];
                }
                break;
            case 'APOIO':
                # Percorre os fornecedores selecionados para cotar APOIO
                foreach ($produtos as $id_fornecedor => $produtos_apoio) {

                    $fornecedor = $this->fornecedores->findById($id_fornecedor);

                    $dom = new DOMDocument();
                    $dom->formatOutput = true;

                    $resposta = $dom->createElement("Resposta");

                    # Adiciona as informações do cabeçalho
                    $cabecalho = $dom->createElement("Cabecalho");
                    $cabecalho->appendChild($dom->createElement("Id_Pdc", $post['cd_cotacao']));
                    $cabecalho->appendChild($dom->createElement("CNPJ_Hospital", mask($post['cnpj_comprador'], '##.###.###/####-##')));
                    $cabecalho->appendChild($dom->createElement("Faturamento_Minimo", $produtos_apoio['valor_minimo']));
                    $cabecalho->appendChild($dom->createElement("Prazo_Entrega", $post['prazo_entrega']));
                    $cabecalho->appendChild($dom->createElement("Validade_Proposta", date('d/m/Y', strtotime('+5 days', strtotime($post['dt_fim_cotacao'])))));
                    $cabecalho->appendChild($dom->createElement("Id_Forma_Pagamento", $post['id_forma_pagamento']));
                    $cabecalho->appendChild($dom->createElement("Frete", 'CIF'));
                    $cabecalho->appendChild($dom->createElement("Custo_Estimado_Frete", ' - '));
                    $cabecalho->appendChild($dom->createElement("Observacoes", $post['obs']));


                    # Cria o elemento dos itens da cotação
                    $itens_pdc = $dom->createElement("Itens_Pdc");

                    # Percorre os produtos inserindo no lemento pai (Itens_Pdc)
                    foreach ($produtos_apoio['produtos'] as $k => $produto) {

                        $this->DB_APOIO->where('id_cotacao', $post['id_cotacao']);
                        $this->DB_APOIO->where('cd_produto_comprador', $produto['cd_produto_comprador']);
                        $prod = $this->DB_APOIO->get('cotacoes_produtos')->row_array();

                        # Percorre as marcas para cada produto
                        foreach ($produto['marcas'] as $p) {

                            $item = $dom->createElement("Item_Pdc");


                            $catalogo = $this->catalogo->find("marca, nome_comercial, unidade", "codigo = {$p['id_pfv']} AND id_fornecedor = {$p['id_fornecedor']}", true);


                            $item->appendChild($dom->createElement('Sequencia', $prod['sequencia']));
                            $item->appendChild($dom->createElement('Id_Artigo', $prod['id_artigo']));
                            $item->appendChild($dom->createElement('Codigo_Produto', $p['cd_produto_comprador']));
                            $item->appendChild($dom->createElement('Preco_Unitario', $p['preco_marca']));
                            $item->appendChild($dom->createElement('Preco_Unitario_Liquido', $p['preco_marca']));
                            $item->appendChild($dom->createElement('Classificacao_Anvisa', '-'));
                            $item->appendChild($dom->createElement('NCM', '-'));
                            $item->appendChild($dom->createElement('IVA', '-'));
                            $item->appendChild($dom->createElement('ICMS', '-'));
                            $item->appendChild($dom->createElement('IPI', '-'));
                            $item->appendChild($dom->createElement('Valor_Frete', '-'));
                            $item->appendChild($dom->createElement('Fabricante', utf8_encode(utf8_decode(htmlspecialchars("{$catalogo['marca']}")))));
                            $item->appendChild($dom->createElement('Embalagem', 'unidade'));
                            $item->appendChild($dom->createElement('Quantidade_Embalagem', $p['qtd_embalagem']));
                            $item->appendChild($dom->createElement('Comentario', (isset($p['obs_produto']) && !empty($p['obs_produto'])) ? $p['obs_produto'] : ' - '));
                            $item->appendChild($dom->createElement('Codigo_Rastreabilidade', $p['id_pfv']));
                            $item->appendChild($dom->createElement('Codigo_Fornecedor', $fornecedor['cnpj']));


                            $extra = $dom->createElement('Campo_Extra');
                            $extra->appendChild($dom->createElement('Nome', "Codigo_Produto_Fornecedor"));
                            $extra->appendChild($dom->createElement('Valor', $p['id_pfv']));

                            $item->appendChild($extra);

                            $itens_pdc->appendChild($item);
                        }
                    }

                    # Monta o XML
                    $resposta->appendChild($cabecalho);
                    $resposta->appendChild($itens_pdc);
                    $dom->appendChild($resposta);

                    $dom->preserveWhiteSpace = false;

                    $simpleXML = new SimpleXMLElement($dom->saveXML());

                    $dom_xml = $simpleXML->asXML();

                    $filename = "public/cotacoes_enviadas/{$id_fornecedor}_{$post['cd_cotacao']}.xml";

                    if (file_exists($filename)) {
                        unlink($filename);
                    }

                    $fl = fopen($filename, "w+");

                    fwrite($fl, $dom_xml);

                    fclose($fl);

                    chmod($filename, 0777);

                    $xmls[] = ['id_fornecedor' => $id_fornecedor, 'xml' => $filename];
                }
                break;
        }


        return $xmls;
    }

    /**
     * Cria o espelho da cotação
     *
     * @param - Array ajax form
     * @param - Array das ofertas organizados por fornecedor e cd_produto comprador
     * @return  array
     */
    public function createMirror($post, $produtos)
    {
        $data_inicio = date('d/m/Y H:i:s', strtotime($post['dt_inicio_cotacao']));
        $data_fim = date('d/m/Y H:i:s', strtotime($post['dt_fim_cotacao']));
        $data_envio = date('d/m/Y H:i', strtotime("-1 hour")); // TRATAR HORA QUANDO ESTIVER NO LINUX

        $condicao_pagamento = $this->forma_pagamento->getFormaPagamento($post['integrador'], $post['id_forma_pagamento']);

        $mirror = "";
        $link_fornecedor = [];

        foreach ($produtos as $id_fornecedor => $prods) {

            $fornecedor = $this->fornecedores->findById($id_fornecedor)['nome_fantasia'];
            $valor_minimo = number_format($prods['valor_minimo'], 2, ",", ".");

            $i = 1;
            $rows = "";

            $label_codigo = (in_array($id_fornecedor, explode(',', ONCOPROD))) ? 'Código Kraft' : 'Código';

            foreach ($prods['produtos'] as $produto) {

                switch (strtoupper($post['integrador'])) {
                    case 'SINTESE':
                        $this->DB_SINTESE->where('cd_cotacao', $post['cd_cotacao']);
                        $this->DB_SINTESE->where('id_fornecedor', $id_fornecedor);
                        $this->DB_SINTESE->where('cd_produto_comprador', $produto['cd_produto_comprador']);
                        $this->DB_SINTESE->where('id_produto_sintese', $produto['id_produto_sintese']);
                        $prod = $this->DB_SINTESE->get('cotacoes_produtos')->row_array();
                        break;
                    case 'BIONEXO':
                        $this->DB_BIONEXO->where('id_cotacao', $post['id_cotacao']);
                        $this->DB_BIONEXO->where('cd_produto_comprador', $produto['cd_produto_comprador']);
                        $prod = $this->DB_BIONEXO->get('cotacoes_produtos')->row_array();
                        break;
                    case 'APOIO':
                        $this->DB_APOIO->where('id_cotacao', $post['id_cotacao']);
                        $this->DB_APOIO->where('cd_produto_comprador', $produto['cd_produto_comprador']);
                        $prod = $this->DB_APOIO->get('cotacoes_produtos')->row_array();
                        break;
                    case 'HUMA':
                        $this->DB_HUMA->where('id_cotacao', $post['id_cotacao']);
                        $this->DB_HUMA->where('cd_produto_comprador', $produto['cd_produto_comprador']);
                        $prod = $this->DB_HUMA->get('cotacoes_produtos')->row_array();
                        break;
                }


                # Obtem um array com os excluidos para contabilizar quantas marcas foram excluidas
                $colunaExcluidos = array_column($produto['marcas'], 'ocultar');

                # Contabiliza as marcas de cada produto
                $count_produtos = count($produto['marcas']);

                # Subtrai as quantidades para saber se existe alguma marca para exibir no espelho
                $temRegistroParaEnvio = $count_produtos - count($colunaExcluidos);

                if ($temRegistroParaEnvio > 0) {

                    $row = "
                        <table style='width:100%; border: 1px solid #dddddd; border-collapse: collapse'>
                            <tr>
                                <td style='border: 1px solid #dddddd; background-color: #D3D3D3' colspan='2'>{$i}. {$prod['ds_produto_comprador']}</td>
                                <td style='border: 1px solid #dddddd; background-color: #D3D3D3' colspan='2'><strong>Qtde Solicitada:</strong> {$prod['qt_produto_total']}</td>
                                <td style='border: 1px solid #dddddd; background-color: #D3D3D3' colspan='2'><strong>Und. Compra:</strong> {$prod['ds_unidade_compra']}</td>
                            </tr>
                            <tr>
                                <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>{$label_codigo}</th>
                                <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Marca</th>
                                <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Embalagem</th>
                                <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Preço</th>
                                <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Descrição</th>
                            </tr>
                    ";

                    foreach ($produto['marcas'] as $item) {

                        # Só insere os itens não excluidos
                        if (!isset($item['ocultar'])) {

                            $catalogo = $this->catalogo->find("marca", "codigo = {$item['id_pfv']} AND id_fornecedor = {$item['id_fornecedor']}", true);

                            $preco = number_format($item['preco_marca'], 4, ",", ".");

                            $row .= "
                                <tr>
                                    <td style='border: 1px solid #dddddd; padding-right: 20px'>{$item['id_pfv']}</td>
                                    <td style='border: 1px solid #dddddd; padding-right: 20px'>{$catalogo['marca']}</td>
                                    <td style='border: 1px solid #dddddd; padding-right: 20px'>{$item['qtd_embalagem']}</td>
                                    <td style='border: 1px solid #dddddd; padding-right: 20px'>{$preco}</td>
                                    <td style='border: 1px solid #dddddd; padding-right: 20px'>{$item['produto']}</td>
                                </tr>
                                <tr>
                                    <td colspan='6' style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Observações: {$item['obs_produto']}</td>
                                </tr>
                            ";
                        }
                    }

                    $row .= "</table>";
                    $rows .= $row;
                    $i++;
                }
            }

            $data = "
                <small>
                    <p>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Numero da Cotação:</strong> {$post['cd_cotacao']}</label><br>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Empresa:</strong> {$post['razao_social']}</label><br>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Situação:</strong> Em Andamento </label><br>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Data e Hora de Início:</strong> {$data_inicio} </label><br>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Data e Hora de Término:</strong> {$data_fim} </label><br>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Data de Envio:</strong> {$data_envio} </label>
                    </p>
                    <hr>
                    <strong>Condições de Pagamento: </strong> {$condicao_pagamento} <br>
                    <strong>Prazo de entrega (dias):</strong> {$post['prazo_entrega']} <br>
                    <strong>Observações:</strong> {$post['obs']} <br>
                    <hr>
                    {$rows}
                </small>
            ";

            $mirror .= $rows;
            $mirror .= "<br><br>";

            # Armazena o arquivo
            $filename = "public/exports/{$id_fornecedor}_cotacao_{$post['cd_cotacao']}.pdf";

            if (file_exists($filename)) {
                unlink($filename);
            }

            $mpdf = new \Mpdf\Mpdf();
            $mpdf->WriteHTML($data);
            $mpdf->Output($filename, 'F');

            $link_fornecedor[] = ['id_fornecedor' => $id_fornecedor, 'mirror' => $filename];
        }

        # Adiciona no output um array com todos os ID fornecedor e o link do espelho
        $output['fornecedores'] = $link_fornecedor;

        # Cria um HTML com todos os produtos de 1..N fornecedores

        $output['html'] = "
            <small>
                <p>
                    <label style='margin-right: 20px; font-size: 12px'><strong>Numero da Cotação:</strong> {$post['cd_cotacao']}</label><br>
                    <label style='margin-right: 20px; font-size: 12px'><strong>Empresa:</strong> {$post['razao_social']}</label><br>
                    <label style='margin-right: 20px; font-size: 12px'><strong>Situação:</strong> Em Andamento </label><br>
                    <label style='margin-right: 20px; font-size: 12px'><strong>Data e Hora de Início:</strong> {$data_inicio} </label><br>
                    <label style='margin-right: 20px; font-size: 12px'><strong>Data e Hora de Término:</strong> {$data_fim} </label><br>
                    <label style='margin-right: 20px; font-size: 12px'><strong>Data de Envio:</strong> {$data_envio} </label>
                </p>
                <hr>
                <strong>Condições de Pagamento: </strong> {$condicao_pagamento} <br>
            
                <strong>Prazo de entrega (dias):</strong> {$post['prazo_entrega']} <br>
                <strong>Observações:</strong> {$post['obs']} <br>
                <hr>

                {$mirror}
            </small>
        ";

        # Criar o arquivo
        $filename = "public/mirrors/{$this->session->id_usuario}_{$post['cd_cotacao']}.html";

        $fl = fopen($filename, "w+");
        fwrite($fl, $output['html']);
        fclose($fl);
        chmod($filename, 0777);

        $output['file'] = $filename;

        return $output;
    }

    /**
     * Atualiza a cotação para submetido
     *
     * @param - String Codigo da cotação
     * @return  bool
     */
    public function atualizarEnvioCotacao($cd_cotacao)
    {

        $this->db->where('ocultar', 0);
        $this->db->where('id_fornecedor_logado', $this->session->id_fornecedor);
        $this->db->where('cd_cotacao', $cd_cotacao);
        $this->db->update('cotacoes_produtos', [
            'submetido' => 1,
            'controle' => 1,
            'id_forma_pagamento' => $this->session->cot_manual['id_forma_pagamento'],
            'obs' => $this->session->cot_manual['obs'],
            'prazo_entrega' => $this->session->cot_manual['prazo_entrega']
        ]);

        return true;
    }

    /**
     * Envia email com o espelho da cotação para os emails registrados para o comprador
     *
     * @param - String nome do integrador
     * @param - Array - String mirror e filename
     * @param - INT ID do comprador
     * @param - INT ID do fornecedor
     * @param - String codigo da cotação
     * @return  bool
     */
    public function sendEmail($integrador, $mirror, $id_cliente, $id_fornecedor, $cd_cotacao)
    {

        if ($this->notify->automaticMessage("MIRROR_MANUAL") != false) {

            $cliente = $this->compradores->findById($id_cliente);

            # envio de email para consultores, gerentes...
            $emails = $this->db->where('id_cliente', $cliente['id'])
                ->where('id_fornecedor', $id_fornecedor)
                ->get('email_notificacao')
                ->row_array();

            $destinatarios = [];

            if (isset($this->session->email) && !empty($this->session->email))
                array_push($destinatarios, $this->session->email);

            if (isset($emails) && !empty($emails)) {

                if (isset($emails['gerente']) && !empty($emails['gerente']))
                    array_push($destinatarios, $emails['gerente']);

                if (isset($emails['consultor']) && !empty($emails['consultor']))
                    array_push($destinatarios, $emails['consultor']);

                if (isset($emails['geral']) && !empty($emails['geral']))
                    array_push($destinatarios, $emails['geral']);

                if (isset($emails['grupo']) && !empty($emails['grupo']))
                    array_push($destinatarios, $emails['grupo']);
            }

            if (!empty($destinatarios)) {

                $destinatarios = implode($destinatarios, ', ');

                switch (strtoupper($integrador)) {
                    case 'SINTESE':
                        $this->DB_SINTESE->where('cd_cotacao', $cd_cotacao);
                        $this->DB_SINTESE->where('id_fornecedor', $id_fornecedor);
                        $cot = $this->DB_SINTESE->get('cotacoes')->row_array();
                        break;
                    case 'BIONEXO':
                        $this->DB_BIONEXO->where('cd_cotacao', $cd_cotacao);
                        $this->DB_BIONEXO->where('id_fornecedor', $id_fornecedor);
                        $cot = $this->DB_BIONEXO->get('cotacoes')->row_array();
                        break;
                    case 'APOIO':
                        $this->DB_APOIO->where('cd_cotacao', $cd_cotacao);
                        $this->DB_APOIO->where('id_fornecedor', $id_fornecedor);
                        $cot = $this->DB_APOIO->get('cotacoes')->row_array();
                        break;
                    case 'HUMA':
                        $this->DB_HUMA->where('cd_cotacao', $cd_cotacao);
                        $this->DB_HUMA->where('id_fornecedor', $id_fornecedor);
                        $cot = $this->DB_HUMA->get('cotacoes')->row_array();
                        break;
                }


                $date = date('d/m/Y H:i:s', strtotime($cot['dt_fim_cotacao']));

                $nome_cliente = (!empty($cliente['nome_fantasia'])) ? $cliente['nome_fantasia'] : $cliente['razao_social'];

                # notificar por e-mail
                $notificar = [
                    "to" => $destinatarios,
                    "greeting" => "",
                    "subject" => "COTAÇÃO {$integrador} #{$cd_cotacao} {$nome_cliente} - {$cliente['estado']} {$date}",
                    "message" => $mirror['html'],
                    "oncoprod" => 1,
                    "attach" => $mirror['filename']
                ];

                $enviarEmail = $this->notify->send($notificar);

                if ($enviarEmail == false) {
                    $enviarEmail = $this->notify->send($notificar);
                    if ($enviarEmail) {
                        $sendError = $this->notify->send([
                            "to" => 'marlon.boecker@pharmanexo.com.br',
                            "greeting" => "",
                            "subject" => "Erro ao enviar espelho da cotação {#cd_cotacao}",
                            "message" => $this->email->print_debugger(array('headers')),
                            "oncoprod" => 1,
                        ]);
                    }
                }

                return true;
            }

            return true;
        }

        return false;
    }

    /**
     * Envia o XML de cada fornecedor selecionado para a sintese
     *
     * @param - Array POST
     * @param - String Codigo da cotação
     * @param - INT ID do comprador
     * @return  array
     */
    public function sendSintese($cd_cotacao, $id_cliente)
    {
        $dados = $this->session->cot_manual;

        foreach ($dados['list'] as $dadosFornecedor) {

            $xml = file_get_contents($dadosFornecedor['xml']);

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

            # Cria um novo XML
            $newfile = "public/cotacoes_enviadas/{$dadosFornecedor['id_fornecedor']}_{$cd_cotacao}_sintese.xml";
            if (file_exists($newfile)) {
                unlink($newfile);
            }
            $arquivo = fopen($newfile, 'w');
            fwrite($arquivo, $envio);
            fclose($arquivo);

            # Cria o header
            $headers = array(
                "Content-type: text/xml;charset=\"utf-8\"",
                "Accept: text/xml",
                "Cache-Control: no-cache",
                "Pragma: no-cache",
                "SOAPAction: http://tempuri.org/EnviarOfertas",
                "Content-length: " . strlen($envio),
            );

            $data = date("d/m/Y H:i:s");

            foreach ($this->urlCliente_sintese as $url) {

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
                curl_setopt($ch, CURLOPT_URL, "{$url}?WSDL");
                curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
                // curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false );
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 2);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_TIMEOUT, 1500);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $envio);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $response = strip_tags(curl_exec($ch));


                $errorMessage = curl_error($ch);
                $errorCode = curl_errno($ch);

                # Verifica ERRO no envio para sintese
                if ($errorMessage != "") {

                    # Notifica ERRO
                    $this->notify->send([
                        "to" => 'marlon.boecker@pharmanexo.com.br',
                        "greeting" => "",
                        "subject" => "Erro ao enviar COTAÇÃO  #{$cd_cotacao} - {$this->session->razao_social}",
                        "message" => "
                        <b>Fornecedor logado:</b> {$this->session->razao_social} <br>
                        <b>Usuário logado:</b> {$this->session->nome} <br>
                        <b>Fornecedor da cotação:</b> {$dadosFornecedor['id_fornecedor']} <br>
                        <b>Data de Envio:</b> {$data} <br>
                        <b>URL:</b> {$url} <br>
                        Codigo do erro: {$errorCode} <br>
                        {$errorMessage}"
                    ]);

                    $warning = ['type' => 'danger', 'message' => "Falha ao enviar para sintese. ERRO: {$errorMessage}"];
                } else {

                    # Notifica ENVIO

                    $msgEmail = [
                        "to" => "suporte@pharmanexo.com.br",
                        "greeting" => "",
                        "subject" => "Resposta da COTAÇÃO  #{$cd_cotacao}",
                        "message" => "
                        <b>Fornecedor logado:</b> {$this->session->razao_social} <br>
                        <b>Usuário logado:</b> {$this->session->nome} <br>
                        <b>Fornecedor da cotação:</b> {$dadosFornecedor['id_fornecedor']} <br>
                        <b>Data de Envio:</b> {$data} <br>
                        {$response}"
                    ];

                    $this->notify->send($msgEmail);

                    if (strpos($response, 'sucesso') == false && strpos($response, 'VL_PRECO_PRODUTO') == false) {

                        # Possiveis ERROS retornados pela sintese

                        # ERRO de URL client
                        if (strpos($response, "source")) {

                            curl_close($ch);

                            $type = 'danger';
                            $response = 'Erro na comunicação com a Sintese! Informe ao suporte';
                            continue;
                        }

                        # Condição de pagamento
                        if (strpos($response, "Condição de pagamento")) {
                            $type = 'warning';
                        } # Cotação finalizada
                        elseif (strpos($response, "finalizada")) {
                            $type = 'warning';
                        } # O usuário ofertante do fornecedor não existe na plataforma sintese
                        elseif (strpos($response, "o cadastro desse usuário na plataforma")) {
                            $type = 'warning';
                        } # Não foi possível incluir uma ou mais ofertas da cotação
                        elseif (strpos($response, "Não foi possível incluir uma ou mais ofertas da cotação")) {
                            $type = 'warning';
                        } # Existem ofertas sem quantidade de embalagem informado
                        elseif (strpos($response, "Existem ofertas sem quantidade de embalagem informado")) {
                            $type = 'warning';
                        } # Problema no endereço de envio da sintese
                        elseif (strpos($response, "documentação obrigatória")) {
                            $response = "A plataforma Síntese informa que devido a pendência referente a documentos obrigatórios, nenhuma oferta será inserida até regularização";

                            $type = 'warning';
                        } elseif (strpos($response, "pendente")) {
                            $response = "A plataforma Síntese informa que devido a pendência referente a documentos obrigatórios, nenhuma oferta será inserida até regularização";

                            $type = 'warning';
                        } elseif (strpos($response, "Not Found")) {
                            $type = 'warning';
                        } else {
                            $type = 'warning';
                        }

                        # Para o foreach

                        $fornecedor = $this->fornecedores->findById($dadosFornecedor['id_fornecedor']);

                        // salvar log

                    } else {

                        # Atualiza os itens para submetido
                        $updtCotacao = $this->atualizarEnvioCotacao($cd_cotacao);

                        # Envia email com o espelho para o comprador
                        $sendEmails = $this->sendEmail(
                            'SINTESE',
                            [
                                'html' => file_get_contents($dados['html']),
                                'filename' => $dadosFornecedor['mirror']
                            ],
                            $id_cliente,
                            $dadosFornecedor['id_fornecedor'],
                            $cd_cotacao
                        );

                        $type = 'success';
                        $response = 'As ofertas foram incluidas com sucesso';
                    }

                    $warning = ['type' => $type, 'message' => $response];

                    break;
                }
            }
        }

        return $warning;
    }

    /**
     * Envia o XML de cada fornecedor selecionado para a bionexo
     *
     * @param - Array POST
     * @param - String Codigo da cotação
     * @param - INT ID do comprador
     * @return  array
     */
    public function sendBionexo($cd_cotacao, $id_cliente)
    {

        $dados = $this->session->cot_manual;

        # Percorre os XML de cada fornecedor
        foreach ($dados['list'] as $dadosFornecedor) {

            $this->db->where("id_fornecedor_logado", $dadosFornecedor['id_fornecedor']);
            $this->db->where("cd_cotacao", $cd_cotacao);
            $this->db->where("submetido", 1);
            $total = $this->db->count_all_results('cotacoes_produtos');

            $operacao = ($total > 0) ? 'WHU' : 'WHS';

            $xml = file_get_contents($dadosFornecedor['xml']);


            foreach ($this->urlCliente_bionexo as $url) {

                $client = new SoapClient($url);

                ini_set('default_socket_timeout', 600);

                # Obtem a credencial bionexo do fornecedor
                $f = $this->fornecedores->findById($dadosFornecedor['id_fornecedor']);

                if (isset($f['credencial_bionexo']) && !empty($f['credencial_bionexo'])) {

                    $credencial_bionexo = json_decode($f['credencial_bionexo'], true);

                    $xml = str_replace('<?xml version="1.0"?>', '', $xml);

                    $params = [
                        $credencial_bionexo['login'],
                        $credencial_bionexo['password'],
                        $operacao,
                        'WH',
                        $xml,
                    ];


                    $resp = $client->__soapCall('post', $params);

                    $response = explode(';', $resp);

                    if (intval($response[0]) < 0) {

                        # Lista de ERROs

                        # Não é possível criar resposta: periodo de cotação encerrado
                        # O dia da validade da proposta deve ser 3 dias posterior ao vencimento da cotação
                        # java.lang.NullPointerException
                        # Incorrect login/password
                        # Não é possível criar resposta: pedido [119062878] já foi respondido!
                        # O cliente trabalha com condições comerciais pré-estabelecidas para esta cotação. Para responder, é obrigatório utilizar os seguintes critérios: [Data de validade mínima= 19/11/2020]

                        $type = 'warning';
                        $response = $response[2];
                    } else {

                        # Atualiza os itens para submetido
                        $updt = $this->atualizarEnvioCotacao($cd_cotacao);

                        # Envia email com o espelho para o comprador
                        $sendEmails = $this->sendEmail(
                            'BIONEXO',
                            [
                                'html' => file_get_contents($dados['html']),
                                'filename' => $dadosFornecedor['mirror']
                            ],
                            $id_cliente,
                            $dadosFornecedor['id_fornecedor'],
                            $cd_cotacao
                        );

                        $type = 'success';
                        $response = "Cotação enviada com sucesso";
                    }
                } else {

                    $text = (count($dados['list']) > 1) ?
                        "O fornecedor {$f['nome_fantasia']} não possui credencial Bionexo" :
                        "Fornecedor não possui credencial Bionexo";

                    return ['type' => 'warning', 'message' => $text];
                }

                $warning = ['type' => $type, 'message' => $response];

                break;
            }
        }

        return $warning;
    }

    /**
     * Envia o XML de cada fornecedor selecionado para a bionexo
     *
     * @param - Array POST
     * @param - String Codigo da cotação
     * @param - INT ID do comprador
     * @return  array
     */
    public function sendApoio($cd_cotacao, $id_cliente)
    {

        $dados = $this->session->cot_manual;

        # Percorre os XML de cada fornecedor
        foreach ($dados['list'] as $dadosFornecedor) {

            $this->db->where("id_fornecedor_logado", $dadosFornecedor['id_fornecedor']);
            $this->db->where("cd_cotacao", $cd_cotacao);
            $this->db->where("submetido", 1);
            $total = $this->db->count_all_results('cotacoes_produtos');

            $operacao = ($total > 0) ? 'WHU' : 'WHS';
            //  $operacao = 'WHS';

            $xml = file_get_contents($dadosFornecedor['xml']);
            $xml = simplexml_load_string($xml);
            $xml = json_decode(json_encode($xml), true);

            foreach ($this->urlCliente_apoio as $url) {


                $client = new SoapClient($url . "?WSDL", array('trace' => 1));

                # Obtem a credencial bionexo do fornecedor
                $f = $this->fornecedores->findById($dadosFornecedor['id_fornecedor']);


                if (isset($f['credencial_apoio']) && !empty($f['credencial_apoio'])) {

                    $credencial_apoio = json_decode($f['credencial_apoio'], true);

                    $xml = str_replace('<?xml version="1.0"?>', '', $xml);

                    /*
                                            $params = [
                                                $credencial_apoio['login'],
                                                $credencial_apoio['password'],
                                                $operacao,
                                                'WH',
                                                $xml,
                                            ];*/


                    if (isset($xml['Cabecalho']['Observacoes'])) {
                        if (empty($xml['Cabecalho']['Observacoes'])) {
                            $xml['Cabecalho']['Observacoes'] = '';
                        }
                    }

                    $params = array(
                        'post' => array(
                            'usuario' => $credencial_apoio['login'],
                            'senha' => $credencial_apoio['password'],
                            'operacao' => $operacao,
                            'xml' => [
                                'Resposta' => $xml
                            ],
                        )
                    );


                    $resp = $client->__soapCall('post', $params);

                    /* echo "REQUEST:\n" . $client->__getLastRequest() . "\n";
                     exit();*/

                    //     $response = explode(';', $resp->String);

                    $response = explode(';', $resp->String);


                    /*  if ($this->session->id_fornecedor == 5018){
                          var_dump($response);
                          exit();
                      }*/


                    if (intval($response[0]) < 0) {

                        if (intval($response[0]) < 0)
                            # Lista de ERROs

                            # Não é possível criar resposta: periodo de cotação encerrado
                            # O dia da validade da proposta deve ser 3 dias posterior ao vencimento da cotação
                            # java.lang.NullPointerException
                            # Incorrect login/password
                            # Não é possível criar resposta: pedido [119062878] já foi respondido!
                            # O cliente trabalha com condições comerciais pré-estabelecidas para esta cotação. Para responder, é obrigatório utilizar os seguintes critérios: [Data de validade mínima= 19/11/2020]

                            $type = 'warning';
                        $response = $response[2];
                    } else {

                        # Atualiza os itens para submetido
                        $updt = $this->atualizarEnvioCotacao($cd_cotacao);

                        # Envia email com o espelho para o comprador
                        $sendEmails = $this->sendEmail(
                            'APOIO',
                            [
                                'html' => file_get_contents($dados['html']),
                                'filename' => $dadosFornecedor['mirror']
                            ],
                            $id_cliente,
                            $dadosFornecedor['id_fornecedor'],
                            $cd_cotacao
                        );

                        $type = 'success';
                        $response = "Cotação enviada com sucesso";
                    }
                } else {

                    $text = (count($dados['list']) > 1) ?
                        "O fornecedor {$f['nome_fantasia']} não possui credencial APOIO" :
                        "Fornecedor não possui credencial APOIO";

                    return ['type' => 'warning', 'message' => $text];
                }

                $warning = ['type' => $type, 'message' => $response];

                break;
            }

            return $warning;
        }
    }

    /**
     * Envia o XML de cada fornecedor selecionado para a Huma
     *
     * @param - Array POST
     * @param - String Codigo da cotação
     * @param - INT ID do comprador
     * @return  array
     */
    public function sendHuma($cd_cotacao, $id_cliente)
    {

        $dados = $this->session->cot_manual;
        $user = $this->db->where('id', $this->session->id_usuario)->get('usuarios')->row_array();
        $cliente = $this->db->where('id', $id_cliente)->get('compradores')->row_array();

        $valorMinimo = $this->db
            ->where('id_cliente', $id_cliente)
            ->where('id_fornecedor', $this->session->id_fornecedor)
            ->get('valor_minimo_cliente')->row_array();

        if (empty($valorMinimo)) {
            $estado = $this->db->where('uf', $cliente['estado'])->get('estados')->row_array();
            $valorMinimo = $this->db
                ->where('id_estado', $estado['id'])
                ->where('id_fornecedor', $this->session->id_fornecedor)
                ->get('valor_minimo_cliente')->row_array();
        }

        $cotacao = $this->DB_HUMA
            ->select('c.cd_cotacao, cp.*')
            ->from('cotacoes c')
            ->join('cotacoes_produtos cp', 'cp.id_cotacao = c.id')
            ->where('c.cd_cotacao', $cd_cotacao)
            ->where('c.id_fornecedor', $this->session->id_fornecedor)
            ->get()
            ->result_array();

        $ofertas = $this->db
            ->select('cp.*, pc.marca, pc.id_marca, pc.nome_comercial, pc.apresentacao')
            ->from('cotacoes_produtos cp')
            ->join('produtos_catalogo pc', 'pc.codigo = cp.id_pfv and cp.id_fornecedor = cp.id_fornecedor')
            ->where('cd_cotacao', $cd_cotacao)
            ->where('cp.id_fornecedor', $this->session->id_fornecedor)
            ->get()
            ->result_array();

        foreach ($cotacao as $k => $item) {
            foreach ($ofertas as $oferta) {
                if ($item['id_artigo'] == $oferta['id_produto']) {
                    $cotacao[$k]['ofertas'][] = $oferta;
                }
            }
        }

        $produtosOferta = [];

        foreach ($cotacao as $cot) {
            if (isset($cot['ofertas'])) {
                foreach ($cot['ofertas'] as $oferta) {
                    if ($oferta['preco_marca'] == 0){
                        $precoOferta = number_format(0.00, 2, ',', '.');
                        $naoCotar = true;
                    }else{
                        $precoOferta = number_format($oferta['preco_marca'], 2, ',', '.');
                        $naoCotar = false;
                    }

                    $produtosOferta[] = [
                        'marca' => (isset($oferta['marca'])) ? $oferta['marca'] : '',
                        'naoCotar' => $naoCotar,
                        'observacoes' => (isset($oferta['obs_produto'])) ? $oferta['obs_produto'] : '',
                        'produto' => [
                            'id' => [
                                "codigo" => $oferta['id_produto'],
                                "codigoPessoa" => $cot['codigoPessoa']
                            ]
                        ],
                        "quantidade" => $cot['qt_produto_total'],
                        "quantidadeCotada" => $cot['qt_produto_total'],
                        "referenciaProduto" => 'PHARMA',
                        'unidade' => [
                            'id' => $cot['id_unidade'],
                            'descricao' => $cot['ds_unidade_compra']
                        ],
                        'valorCotacao' => $precoOferta
                    ];
                }
            }else{
                $produtosOferta[] = [
                    'marca' => '',
                    'naoCotar' => TRUE,
                    'observacoes' => '',
                    'produto' => [
                        'id' => [
                            "codigo" => $cot['cd_produto_comprador'],
                            "codigoPessoa" => $cot['codigoPessoa']
                        ]
                    ],
                    "quantidade" => $cot['qt_produto_total'],
                    "quantidadeCotada" => $cot['qt_produto_total'],
                    "referenciaProduto" => 'PHARMA',
                    'unidade' => [
                        'id' => $cot['id_unidade'],
                        'descricao' => $cot['ds_unidade_compra']
                    ],
                    'valorCotacao' => '0,00'
                ];
            }
        }

        $url = "https://pharmanexo.com.br/pharma_api/huma/cotacoes/sendCotacao?id={$this->session->id_fornecedor}";

        $post = [
            'cd_cotacao' => $cd_cotacao,
            'id_fornecedor' => $this->session->id_fornecedor,
            'vendedor' => $user['nickname'] . " - " . $user['email'],
            'obs' => $dados['obs'],
            'prazoEntrega' => $dados['prazo_entrega'],
            'formaPagamento' => $dados['id_forma_pagamento'],
            'valorMinimo' => (isset($valorMinimo['valor_minimo'])) ? $valorMinimo['valor_minimo'] : '0,00',
            'produtos' => $produtosOferta
        ];

        $postdata = json_encode($post);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($ch);
        curl_close($ch);


        $resultArray = json_decode($result, true);

        if (isset($resultArray['type']) && $resultArray['type'] == 'success') {
            # Atualiza os itens para submetido
            $updt = $this->atualizarEnvioCotacao($cd_cotacao);

            foreach ($dados['list'] as $mail){
                $sendEmails = $this->sendEmail(
                    'HUMA',
                    [
                        'html' => file_get_contents($dados['html']),
                        'filename' => $mail['mirror']
                    ],

                    $id_cliente,
                    $mail['id_fornecedor'],
                    $cd_cotacao
                );
            }

            # Envia email com o espelho para o comprador

        }

      return $resultArray;
    }


    /**
     * Registra log das ofertas de envio
     *
     * @param - String Codigo da cotação
     * @param - Array POST
     * @return  bool
     */
    public function createLog($cd_cotacao, $integrador)
    {

        # Log de envio
        $this->db->group_start();
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);
        $this->db->or_where('id_fornecedor_logado', $this->session->id_fornecedor);
        $this->db->group_end();
        $this->db->where('cd_cotacao', $cd_cotacao);
        $log_produtos = $this->db->get('cotacoes_produtos')->result_array();

        $this->db->group_start();
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);
        $this->db->or_where('id_fornecedor_logado', $this->session->id_fornecedor);
        $this->db->group_end();
        $this->db->where('cd_cotacao', $cd_cotacao);
        $log_restricoes = $this->db->get('restricoes_produtos_cotacoes')->result_array();

        $log = [
            'id_usuario' => $this->session->id_usuario,
            'id_fornecedor' => $this->session->id_fornecedor,
            'integrador' => $integrador,
            'cd_cotacao' => $cd_cotacao,
            'produtos' => (isset($log_produtos) && !empty($log_produtos)) ? json_encode(['produtos' => $log_produtos]) : null,
            'restricoes' => (isset($log_restricoes) && !empty($log_restricoes)) ? json_encode(['restricoes' => $log_restricoes]) : null
        ];

        $registraLog = $this->db->insert('log_envio_manual', $log);

        return true;
    }

    /**
     * Atualiza a forma de pagamento da cotação
     */
    public function setFormaPagamento($fp, $cot, $id_forn)
    {

        $q = $this->db
            ->set('id_forma_pagamento', intval($fp))
            ->where('cd_cotacao', $cot)
            ->where('id_fornecedor', intval($id_forn))
            ->update('cotacoes_produtos');


        return $q;
    }

    /**
     * Atualiza a obs da cotação
     */
    public function setObsCotacao($obs, $cot, $id_forn)
    {
        return $this->db
            ->set('obs', $obs)
            ->where('cd_cotacao', $cot)
            ->where('id_fornecedor', $id_forn)
            ->update('cotacoes_produtos');
    }

    /**
     * Atualiza o prazo da cotação
     */
    public function setPrazoEntrega($prazo_entrega, $cot, $id_forn)
    {

        return $this->db
            ->set('prazo_entrega', $prazo_entrega)
            ->where('cd_cotacao', $cot)
            ->where('id_fornecedor', $id_forn)
            ->update('cotacoes_produtos');
    }
}
