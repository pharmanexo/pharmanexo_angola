<?php
date_default_timezone_set('America/Sao_Paulo');

class Cotacoes extends MY_Controller
{

    private $route;
    private $views;
    private $DB_SINTESE;
    private $DB_BIONEXO;
    private $DB_APOIO;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('m_catalogo', 'catalogo');

        $this->load->model('m_marca', 'marca');
        $this->load->model('m_usuarios', 'usuario');
        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_estados', 'estados');
        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_forma_pagamento', 'forma_pagamento');
        $this->load->model('produto_marca_sintese', 'pms');
        $this->load->model('m_cotacoes', 'cotacoes');
        $this->load->model('m_cotacaoManual', 'COTACAO_MANUAL');
        $this->load->model('m_restricao_produto_cotacao2', 'restricao_cotacao');

        error_reporting(E_ALL);
        ini_set("display_errors", 1);
        /* error_reporting(0);
         ini_set('display_errors', 0);*/

        $this->route = base_url('/fornecedor/cotacoes/');
        $this->views = 'fornecedor/cotacoes/';

        $this->DB_SINTESE = $this->load->database('sintese', TRUE);
        $this->DB_BIONEXO = $this->load->database('bionexo', TRUE);
        $this->DB_APOIO = $this->load->database('apoio', TRUE);
    }

    /**
     * Função que verifica se o fornecedor logado possui filial
     *
     * @return  bool
     */
    public function checkFilial()
    {
        return $this->session->has_userdata('id_matriz') ? true : false;
    }

    /**
     * Exibe a view da lista de cotações
     *
     * @return view
     */
    public function index()
    {
        # Verifica se o fornecedor logado tem filial
        $data['checkFilial'] = $this->checkFilial();

        $get = $this->input->get();

        if (isset($get['uf'])) {
            $uf = strtoupper($_GET['uf']);
        } else {
            $uf = null;
        }

        $page_title = (isset($get['uf'])) ? "Cotações em aberto no {$uf}" : "Cotações em aberto no Brasil";

        # Selects
        $data['estados'] = $this->estados->find("uf, CONCAT(uf, ' - ', descricao) AS estado", null, FALSE, 'estado ASC');
        $data['compradores'] = $this->compradores->find("id, CONCAT(cnpj, ' - ', razao_social) AS comprador", null, FALSE, 'comprador ASC');
        $data['cotacoes'] = $this->COTACAO_MANUAL->getCotacoes('cd_cotacao', $uf);

        # URLs
        $data['to_datatable'] = (isset($uf)) ? "{$this->route}/datatables_cotacoes/{$uf}" : "{$this->route}/datatables_cotacoes";
        $data['url_cotacao'] = "{$this->route}detalhes/";
        $data['url_info'] = "{$this->route}info_cotacao/";
        $data['url_ocultar'] = "{$this->route}changeHide/";
        $data['url_filtros'] = "{$this->route}filtros/";

        # Template
        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnDesocultar',
                    'url' => "{$this->route}ocultadas",
                    'class' => 'btn-secondary',
                    'icone' => 'fa-eye-slash',
                    'label' => 'Cotações Ocultadas'
                ]
            ]
        ]);

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Exibe a view dos produtos da cotação
     *
     * @return view
     */
    public function detalhes($integrador, $cd_cotacao = NULL)
    {

        # Remove a session de gerar espelho
        if ($this->session->has_userdata('cot_manual')) {
            unset($_SESSION['cot_manual']);
        }

        # Verifica se o fornecedor logado tem filial
        $data['checkFilial'] = $this->checkFilial();

        $page_title = "Cotação #{$cd_cotacao}";

        $data['integrador'] = $integrador;

        # Array com os dados da cotação, comprador e seus produtos
        $data['cotacao'] = $this->COTACAO_MANUAL->getItem($cd_cotacao, $this->session->id_fornecedor, $data['integrador']);

        # Verifica se a cotação não existe
        if ($data['cotacao'] == false) {

            $warn = ["type" => "warning", "message" => "Cotação não localizada, aguarde a próxima atualização ou comunique o suporte."];

            $this->session->set_userdata('warning', $warn);
            redirect(base_url('/dashboard'));
        }

        # Obtem o comprador
        $cliente = $data['cotacao']['cliente'];

        # Obtem o  estado do comprador
        $estado = $data['cotacao']['estado'];

        # Prazo entrega
        $data['prazo_entrega'] = $this->COTACAO_MANUAL->getPrazoEntrega('', $cliente['id'], $this->session->id_fornecedor, $estado['id']);

        # Condição pagamento
        $data['forma_pagamento'] = $this->COTACAO_MANUAL->getFormaPagamento($data['integrador'], $cliente['id'], $this->session->id_fornecedor, $estado['id']);

        /* # Verifica se acotação ja foi respondida
         $this->db->where('id_fornecedor', $this->session->id_fornecedor);
         $this->db->where('cd_cotacao', $cd_cotacao);
         $this->db->order_by('data_criacao DESC');
         $cotacao_respondida = $this->db->get('cotacoes_produtos')->row_array();

         if (isset($cotacao_respondida) && !empty($cotacao_respondida)) {

             $data['prazo_entrega'] = $cotacao_respondida['prazo_entrega'];
             $data['observacao'] = $cotacao_respondida['obs'];
             $data['forma_pagamento'] = $cotacao_respondida['id_forma_pagamento'];
         } else {

             $this->db->where("id_fornecedor", $this->session->id_fornecedor);
             $this->db->where_in("tipo", [2, 3]);
             $this->db->group_start();
             $this->db->where("id_estado", $estado['id']);
             $this->db->or_where("id_estado", 0);
             $this->db->group_end();

             $obsConfig = $this->db->get("configuracoes_envio")->row_array();

             if (isset($obsConfig) && !empty($obsConfig)) {

                 $data['observacao'] = $obsConfig['observacao'];
             }
         }*/

        # Selects
        $data['select_formas_pagamento'] = $this->forma_pagamento->listar($data['integrador']);

        # URLs
        $data['url_historico'] = "{$this->route}getHistory/{$data['integrador']}";
        $data['url_ocultar'] = "{$this->route}changeHide/{$data['integrador']}/{$cd_cotacao}";
        $data['url_saveItem'] = "{$this->route}saveItem";
        $data['url_findProduct'] = "{$this->route}findProduct/{$data['integrador']}/";
        $data['url_revisar'] = "{$this->route}review/";
        $data['url_price'] = "{$this->route}setProduct/";
        $data['url_restricao'] = "{$this->route}deleteRestriction";
        $data['datatables'] = "{$this->route}datatable_catalogo";
        $data['url_combinar'] = "{$this->route}combinar_produto_marca/{$integrador}";
        $data['url_removeDePara'] = "{$this->route}removeDePara/{$integrador}";

        if ($data['checkFilial']) {

            # Select de fornecedores ONCOPROD
            $data['options_fornecedores'] = $this->COTACAO_MANUAL->selectFornecedores($data['integrador'], $cd_cotacao);
        }

        $data['label_codigo'] = (in_array($this->session->id_fornecedor, explode(',', ONCOPROD))) ? 'Código Kraft' : 'Código';

        # form
        $data['form_action'] = "{$this->route}enviar_resposta";

        $retornar = ($this->session->has_userdata('perfil_comercial')) ? base_url('dashboard') : "{$this->route}?uf={$data['cotacao']['uf_cotacao']}";

        $data['url_lista'] = $retornar;

        $btns = [
            [
                'type' => 'a',
                'id' => 'btnCount',
                'url' => "javascript:void(0)",
                'class' => 'btn-outline-info mr-3',
                'icone' => '',
                'label' => 'Produto(s) Selecionado(s)'
            ],
            [
                'type' => 'a',
                'id' => 'btnVoltar',
                // 'url' => $retornar,
                //'url' => "{$this->route}?uf={$data['cotacao']['uf_cotacao']}",
                'url' => ($this->session->grupo == 2) ? base_url('/dashboard') : base_url('/fornecedor/cotacoes/'),
                'class' => 'btn-secondary',
                'icone' => 'fa-arrow-left',
                'label' => 'Retornar'
            ],
            [
                'type' => 'button',
                'id' => 'btn_ocultar',
                'url' => "",
                'class' => ($data['cotacao']['oculto'] == 1) ? 'btn-danger' : 'btn-outline-danger',
                'icone' => ($data['cotacao']['oculto'] == 1) ? 'fa-eye' : 'fa-eye-slash',
                'label' => ($data['cotacao']['oculto'] == 1) ? 'Remover Ocultação' : 'Ocultar Cotação'
            ]
        ];

        # Validação de exibir botão de envio

        // TRATAR HORA QUANDO ESTIVER NO LINUX
        if ($data['cotacao']['data_fim'] > date('Y-m-d H:i:s', strtotime("-1 hour")) || $_SESSION['id_usuario'] == 187) {

            $btns[] = [
                'type' => 'button',
                'id' => 'btnEnviar',
                'url' => "",
                'class' => 'btn-primary',
                'icone' => 'fa-tasks',
                'label' => 'Gerar Espelho de ofertas'
            ];
        }

        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => $btns
        ]);

        $this->load->view("{$this->views}detail", $data);
    }

    /**
     * Salva a oferta do produto da cotação
     *
     * @return json
     */
    public function saveItem()
    {
        $post = $this->input->post();

        # Valida forma de pagamento
        if (!isset($post['id_forma_pagamento']) || empty($post['id_forma_pagamento'])) {

            $warning = ['type' => 'warning', 'message' => 'É necessário configurar forma de pagamento'];
            return $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }

        # Validade prazo de entrega
        if (!isset($post['id_forma_pagamento']) || empty($post['prazo_entrega'])) {

            $warning = ['type' => 'warning', 'message' => 'É necessário configurar prazo de entrega'];
            return $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }

        $this->db->trans_begin();

        # remove a restrição caso houver e insere a nova caso também houver
        $restricao = $this->restricao_cotacao->renewProductRestriction($post, $this->session->id_fornecedor);

        foreach ($post['marcas'] as $marca) {

            $marca['preco_oferta'] = dbNumberFormat($marca['preco_oferta']);

            $f = $this->fornecedor->findById($marca['id_fornecedor']);

            # Verifica se esta marcado
            if (isset($marca['marcado'])) {

                # Validade valor minimo
                $valor_minimo = $this->COTACAO_MANUAL->getValorMinimo($post['id_cliente'], $marca['id_fornecedor'], $post['id_estado']);

                if (!isset($valor_minimo)) {

                    $this->db->trans_rollback();

                    $warning = ['type' => 'warning', 'message' => "É necessário configurar um valor minimo para a filial: {$f['nome_fantasia']}"];
                    return $this->output->set_content_type('application/json')->set_output(json_encode($warning));
                }

                # Verifica se ja foi submetido ou é rascunho
                if (isset($marca['nivel'])) {

                    $this->db->where('id', $marca['id_cotacao']);
                    $updt = $this->db->update('cotacoes_produtos', [
                        "preco_marca" => $marca['preco_oferta'],
                        "id_fornecedor" => $marca['id_fornecedor'],
                        "id_fornecedor_logado" => $this->session->id_fornecedor,
                        "obs" => $post['obs'],
                        "obs_produto" => $marca['obs'],
                        'ocultar' => 0
                    ]);
                } else {

                    # Verifica se não existe rascunho
                    $this->db->where("integrador", $post['integrador']);
                    $this->db->where("id_fornecedor_logado", $this->session->id_fornecedor);
                    $this->db->where("cd_cotacao", $post['cd_cotacao']);
                    $this->db->where("id_pfv", $marca['codigo']);
                    $this->db->where("cd_produto_comprador", $post['cd_produto_comprador']);

                    if ($post['integrador'] == 'SINTESE') {

                        $this->db->where("id_produto", $post['id_produto_sintese']);
                    }

                    $this->db->delete("cotacoes_produtos");

                    # insere como rascunho
                    $novoProduto = [
                        'integrador' => $post['integrador'],
                        "cd_cotacao" => $post['cd_cotacao'],
                        "id_cliente" => $post['id_cliente'],
                        "id_produto" => intval($post['id_produto_sintese']),
                        'cd_produto_comprador' => $post['cd_produto_comprador'],
                        "data_cotacao" => $post['dt_inicio_cotacao'],
                        "uf_comprador" => $post['uf_cotacao'],
                        "uf_fornecedor_oferta" => $f['estado'],
                        "cnpj_comprador" => $post['cnpj_comprador'],
                        "qtd_solicitada" => intval($post['qt_produto_total']),
                        "obs" => $post['obs'],
                        "id_forma_pagamento" => intval($post['id_forma_pagamento']),
                        "prazo_entrega" => intval($post['prazo_entrega']),
                        "valor_minimo" => $valor_minimo,
                        "produto" => $marca['produto_descricao'],
                        "id_pfv" => $marca['codigo'],
                        "id_fornecedor" => $marca['id_fornecedor'],
                        "qtd_embalagem" => intval($marca['quantidade_unidade']),
                        "preco_marca" => $marca['preco_oferta'],
                        "obs_produto" => $marca['obs'],
                        "nivel" => 1,
                        "submetido" => 0,
                        "controle" => 0,
                        "id_fornecedor_logado" => $this->session->id_fornecedor,
                        'id_usuario' => $this->session->id_usuario,
                        "id_cotacao" => time()
                    ];

                    $this->db->insert('cotacoes_produtos', $novoProduto);
                }
            } else {

                # Verifica se ja foi submetido ou é rascunho
                if (isset($marca['nivel'])) {

                    # Mantem o registro, mas zera o preço
                    $this->db->where('id', $marca['id_cotacao']);
                    $updt = $this->db->update('cotacoes_produtos', [
                        'ocultar' => 1,
                        'submetido' => 0,
                        'preco_marca' => 0,
                        'id_fornecedor_logado' => $this->session->id_fornecedor
                    ]);
                } else {

                    # Remove o registro
                    $this->db->where("id_fornecedor_logado", $this->session->id_fornecedor);
                    $this->db->where("cd_cotacao", $post['cd_cotacao']);
                    $this->db->where("id_pfv", $marca['codigo']);
                    $this->db->where("integrador", $post['integrador']);
                    $this->db->where("cd_produto_comprador", $post['cd_produto_comprador']);

                    if ($post['integrador'] == 'SINTESE') {

                        $this->db->where("id_produto", $post['id_produto_sintese']);
                    }

                    $this->db->delete("cotacoes_produtos");
                }
            }
        }

        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();

            $warning = ['type' => 'warning', 'message' => 'Erro ao salvar oferta! Tente novamente'];
        } else {

            $this->db->trans_commit();

            $warning = ['type' => 'success', 'message' => 'Oferta salva com sucesso!'];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($warning));
    }

    /**
     * Deleta o item em De/Para da cotação
     *
     * @return json
     */
    public function removeDePara($integrador)
    {
        $post = $this->input->post();

        switch (strtoupper($integrador)) {
            case 'SINTESE':

                $this->db->where('id_produto', $post['dados']['sintese']);
                $marcaSintese = $this->db->select('id_sintese')->get('produtos_marca_sintese')->result_array();
                $idSintese = [];
                foreach ($marcaSintese as $s) {
                    $idSintese[] = $s['id_sintese'];
                }
                if (count($idSintese) > 0) {
                    $deleteDePara = $this->db->where_in('id_sintese', $idSintese)
                        ->where('cd_produto', $post['dados']['cod_prod'])
                        ->where('id_fornecedor', $this->session->id_fornecedor)
                        ->delete('produtos_fornecedores_sintese');
                    if ($deleteDePara) {
                        $retorno = ['type' => 'success', 'message' => 'Produto removido'];
                    } else {
                        $retorno = ['type' => 'error', 'message' => 'Erro ao remover'];
                    }
                    $this->output->set_content_type('application/json')->set_output(json_encode($retorno));
                }
                break;

            case 'BIONEXO':
                $deleteDePara = $this->db->where('id_produto_sintese', $post['dados']['id_produto_sintese'])
                    ->where('id_fornecedor', $this->session->id_fornecedor)
                    ->where('id_integrador', 2)
                    ->where('cd_produto', $post['dados']['cod_prod'])
                    ->delete('produtos_cliente_depara');
                if ($deleteDePara) {
                    $retorno = ['type' => 'success', 'message' => 'Produto removido'];
                } else {
                    $retorno = ['type' => 'error', 'message' => 'Erro ao remover'];
                }
                $this->output->set_content_type('application/json')->set_output(json_encode($retorno));
                break;

            case 'APOIO':
                $deleteDePara = $this->db->where('id_produto_sintese', $post['dados']['id_produto_sintese'])
                    ->where('id_fornecedor', $this->session->id_fornecedor)
                    ->where('id_integrador', 3)
                    ->where('cd_produto', $post['dados']['cod_prod'])
                    ->delete('produtos_cliente_depara');
                if ($deleteDePara) {
                    $retorno = ['type' => 'success', 'message' => 'Produto removido'];
                } else {
                    $retorno = ['type' => 'error', 'message' => 'Erro ao remover'];
                }
                $this->output->set_content_type('application/json')->set_output(json_encode($retorno));
                break;
        }
    }

    /**
     * Processa as ofertas da cotação e gera XML e espelho
     *
     * @param POST ajax form
     * @return  json
     */
    public function enviar_resposta()
    {
        $post = $this->input->post();

        $this->removeProdutoDuplicados($post['cd_cotacao'], $_SESSION['id_fornecedor']);

        $produtosOrdenados = $this->organizeOffers($post);

        # Criar espelho
        $mirror = $this->COTACAO_MANUAL->createMirror($post, $produtosOrdenados, $post['integrador']);


        # Criar XML
        $xml = $this->COTACAO_MANUAL->createXML($post, $produtosOrdenados, $post['integrador']);

        # Combinar espelho e XMl
        $dados = $this->combineMirrorXml($mirror['fornecedores'], $xml);


        # Cria a session
        $this->session->set_userdata([
            'cot_manual' => [
                'html' => $mirror['file'],
                'list' => $dados,
                'id_forma_pagamento' => $post['id_forma_pagamento'],
                'obs' => $post['obs'],
                'prazo_entrega' => $post['prazo_entrega']
            ]
        ]);

        # atualiza forma de pagamento da cotação
        $this->COTACAO_MANUAL->setFormaPagamento($post['id_forma_pagamento'], $post['cd_cotacao'], $this->session->id_fornecedor);

        # atualiza a obs cotação
        $this->COTACAO_MANUAL->setObsCotacao($post['obs'], $post['cd_cotacao'], $this->session->id_fornecedor);

        # atualiza o prazo de entrega da cotação
        $this->COTACAO_MANUAL->setPrazoEntrega($post['prazo_entrega'], $post['cd_cotacao'], $this->session->id_fornecedor);

        $rota = "{$this->route}espelho/{$post['integrador']}/{$post['cd_cotacao']}/{$post['id_cliente']}";

        $warning = ['type' => 'success', 'message' => notify_create, 'route' => $rota];
        $this->output->set_content_type('application/json')->set_output(json_encode($warning));
    }

    /**
     * Combina os links do espelho e do xml gerados pra cada fornecedor
     *
     * @param array espelhos
     * @param array xmls
     * @return  array
     */
    public function combineMirrorXml($mirror, $xml)
    {
        # O mirror e o XML sempre vão ter quantidade iguais
        $total = count($mirror);

        $resp = [];

        for ($i = 0; $i < count($mirror); $i++) {

            $resp[] = array_merge($mirror[$i], $xml[$i]);
        }


        return $resp;
    }

    /**
     * Exibe a tela de espelho da cotação
     *
     * @param - String nome do integrador
     * @param - String codigo da cotação
     * @param - INT ID do comprador
     * @return  view
     */
    public function espelho($integrador, $cd_cotacao, $id_cliente)
    {

        $dataView = [
            'header' => $this->template->header(['title' => '']),
            'navbar' => $this->template->navbar(),
            'sidebar' => $this->template->sidebar(),
            'scripts' => $this->template->scripts(),
            'heading' => $this->template->heading([
                'page_title' => '',
                'buttons' => [
                    [
                        'type' => 'a',
                        'id' => 'btnBack',
                        'url' => "{$this->route}detalhes/{$cd_cotacao}",
                        'class' => 'btn-secondary',
                        'icone' => 'fa-arrow-left',
                        'label' => 'Voltar'
                    ],
                    [
                        'type' => 'a',
                        'id' => '',
                        'url' => "",
                        'class' => 'btn-primary btnEnvioSintese',
                        'icone' => 'fa-paper-plane',
                        'label' => 'Transmitir'
                    ]
                ]
            ]),
            'mirror' => file_get_contents($this->session->cot_manual['html']),
            'form_action' => "{$this->route}sendCotacao/{$integrador}/{$cd_cotacao}/{$id_cliente}"
        ];

        $this->load->view("{$this->views}/mirror", $dataView);
    }

    /**
     * Envia o XML da cotação
     *
     * @param - String nome do integrador
     * @param - String codigo da cotação
     * @param - INT ID do comprador
     * @return  array
     */
    public function sendCotacao($integrador, $cd_cotacao, $id_cliente)
    {
        if ($this->input->method() == 'post') {
            switch (strtoupper($integrador)) {
                case 'SINTESE':
                    $warning = $this->COTACAO_MANUAL->sendSintese($cd_cotacao, $id_cliente);
                    break;
                case 'BIONEXO':
                    $warning = $this->COTACAO_MANUAL->sendBionexo($cd_cotacao, $id_cliente);
                    break;
                case 'APOIO':
                    $warning = $this->COTACAO_MANUAL->sendApoio($cd_cotacao, $id_cliente);
                    break;
            }

            if (file_exists($this->session->cot_manual['html'])) {
                unlink($this->session->cot_manual['html']);
            }


            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
    }


    /**
     * Organiza as ofertas por fornecedor e produto
     *
     * @param - Array ajax post
     * @return  array
     */
    public function organizeOffers($post)
    {

        # Obtem as ofertas da cotação
        $this->db->where("id_fornecedor_logado", $this->session->id_fornecedor);
        $this->db->where("cd_cotacao", $post['cd_cotacao']);
        $ofertas = $this->db->get('cotacoes_produtos')->result_array();

        $produtosOrdenados = [];

        # Agrupa as ofertas por fornecedor e por produto
        foreach ($ofertas as $oferta) {

            if ($oferta['ocultar'] == 0) {

                unset($oferta['ocultar']);
            }

            # Adiciona o valor minimo por fornecedor
            $produtosOrdenados[$oferta['id_fornecedor']]['valor_minimo'] = $oferta['valor_minimo'];
            $produtosOrdenados[$oferta['id_fornecedor']]['produtos'][$oferta['cd_produto_comprador']]['cd_produto_comprador'] = $oferta['cd_produto_comprador'];

            if ($post['integrador'] == 'SINTESE') {

                # Adiciona o ID produto sintese e cd produto comprador
                $produtosOrdenados[$oferta['id_fornecedor']]['produtos'][$oferta['cd_produto_comprador']]['id_produto_sintese'] = $oferta['id_produto'];
            }

            $produtosOrdenados[$oferta['id_fornecedor']]['produtos'][$oferta['cd_produto_comprador']]['marcas'][] = $oferta;
        }

        return $produtosOrdenados;
    }

    /**
     * Exibe a view da lista de cotações ocultadas
     *
     * @return view
     */
    public function ocultadas()
    {

        $page_title = "Lista de cotações ocultadas e descartadas";

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
                        'url' => (isset($this->session->uf_cotacoes)) ? "{$this->route}?uf={$this->session->uf_cotacoes}" : $this->route,
                        'class' => 'btn-secondary',
                        'icone' => 'fa-arrow-left',
                        'label' => 'Retornar'
                    ],
                    [
                        'type' => 'button',
                        'id' => 'btnDesocultar',
                        'url' => "",
                        'class' => 'btn-primary',
                        'icone' => 'fa-eye',
                        'label' => 'Remover ocultação'
                    ]
                ]
            ]),
            'scripts' => $this->template->scripts(),
        ];

        $data['to_datatable'] = "{$this->route}datatable_ocultadas";
        $data['url_desocultar'] = "{$this->route}desocultar/";

        $this->load->view("{$this->views}main_desocultar", $data);
    }

    /**
     * Exbie a tela de produtos para realizar de para
     *
     * @param String nome do integrador
     * @param String identificação do produto no integrador
     * @param INT ID do comprador
     * @return  view
     */
    public function index_depara($integrador, $codigo_produto, $id_cliente, $cdProdutoCliente = null)
    {

        switch (strtoupper($integrador)) {
            case 'SINTESE':
                $produtoCot = $this->DB_SINTESE->query("select ds_produto_comprador from cotacoes_sintese.cotacoes_produtos cp
                            join cotacoes c on cp.id_fornecedor = c.id_fornecedor and cp.cd_cotacao = c.cd_cotacao
                            where cp.cd_produto_comprador = '{$cdProdutoCliente}' and c.id_cliente = {$id_cliente}
                            group by cd_produto_comprador, id_cliente")->row_array();

                $produto = $this->pms->find('*', "id_produto = {$codigo_produto}", true);
                $datatable = "{$this->route}datatable_catalogo";

                $produto['solicitado'] = (!empty($produtoCot)) ? $produtoCot['ds_produto_comprador'] : '';

                $view = "sintese";
                break;
            case 'BIONEXO':
                $produto = $this->DB_BIONEXO->where('codigo', $codigo_produto)->where_in('id_cliente', $id_cliente)->get('catalogo')->row_array();
                $datatable = "{$this->route}datatable_sintese";
                $view = "bionexo";
                break;
            case 'APOIO':
                $produto = $this->DB_APOIO->where('codigo', $codigo_produto)->where_in('id_cliente', $id_cliente)->get('catalogo')->row_array();
                $datatable = "{$this->route}datatable_sintese";
                $view = "apoio";
                break;
        }

        if (isset($produto['solicitado'])) {
            $page_title = strtoupper("Produto: {$produto['descricao']} <br> <small>DESCRIÇÃO Comprador: {$produto['solicitado']}</small>");
        } else {
            $page_title = strtoupper("Produto: {$produto['descricao']}");
        }


        $data = [
            'header' => $this->template->header(['title' => $page_title]),
            'navbar' => $this->template->navbar(),
            'sidebar' => $this->template->sidebar(),
            'heading' => $this->template->heading([
                'page_title' => $page_title,
                'buttons' => [
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
        $data['id_cliente'] = $id_cliente;
        $data['id_prod_cot'] = $cdProdutoCliente;
        $data['datatables'] = $datatable;
        $data['url_combinar'] = "{$this->route}combinar_produto_marca/{$integrador}";

        $this->load->view("{$this->views}/find_product_{$view}", $data);
    }

    /**
     * Gera html com ifnromações da cotação
     *
     * @param string nome do integrador
     * @param string codigo da cotação
     * @param int - tipo de relatório(produtos ou cotação)
     * @return html
     */
    public function info_cotacao($integrador, $cd_cotacao, $type = null)
    {

        if ($integrador == 'SINTESE') {

            $this->DB_SINTESE->where('cd_cotacao', $cd_cotacao);
            $this->DB_SINTESE->where('id_fornecedor', $this->session->id_fornecedor);
            $cotacao = $this->DB_SINTESE->get('cotacoes')->row_array();

            $this->DB_SINTESE->select("id_produto_sintese");
            $this->DB_SINTESE->select("cd_produto_comprador");
            $this->DB_SINTESE->select("ds_produto_comprador");
            $this->DB_SINTESE->select("ds_unidade_compra");
            $this->DB_SINTESE->select("ds_complementar");
            $this->DB_SINTESE->select("SUM(qt_produto_total) AS qt_produto_total");
            $this->DB_SINTESE->where('cd_cotacao', $cd_cotacao);
            $this->DB_SINTESE->where('id_fornecedor', $this->session->id_fornecedor);
            $this->DB_SINTESE->group_by('id_produto_sintese, cd_produto_comprador, ds_produto_comprador, ds_unidade_compra, ds_complementar');
            $cotacoes_produtos = $this->DB_SINTESE->get('cotacoes_produtos')->result_array();

            $data_validade = date("d/m/Y H:i:s", strtotime($cotacao['dt_validade_preco']));
            $data_validade = " <b>Data de Validade:</b> {$data_validade} <br>";
            $nome_usuario = $cotacao['nm_usuario'];
            $cnpj = mask($cotacao['cd_comprador'], '##.###.###/####-##');
        } else {

            $this->DB_BIONEXO->where('cd_cotacao', $cd_cotacao);
            $this->DB_BIONEXO->where('id_fornecedor', $this->session->id_fornecedor);
            $cotacao = $this->DB_BIONEXO->get('cotacoes')->row_array();

            $this->DB_BIONEXO->select("cd_produto_comprador");
            $this->DB_BIONEXO->select("ds_produto_comprador");
            $this->DB_BIONEXO->select("ds_unidade_compra");
            $this->DB_BIONEXO->select("SUM(qt_produto_total) AS qt_produto_total");
            $this->DB_BIONEXO->where('id_cotacao', $cotacao['id']);
            $this->DB_BIONEXO->group_by('cd_produto_comprador');
            $cotacoes_produtos = $this->DB_BIONEXO->get('cotacoes_produtos')->result_array();

            $data_validade = "";
            $nome_usuario = $cotacao['contato'];
            $cnpj = $cotacao['cd_comprador'];
        }

        $cliente = $this->compradores->get_byCNPJ($cnpj);

        $estado = $this->estados->find("*", "uf = '{$cliente['estado']}'", true);

        $dataini = date("d/m/Y H:i:s", strtotime($cotacao['dt_inicio_cotacao']));
        $datafim = date("d/m/Y H:i:s", strtotime($cotacao['dt_fim_cotacao']));

        # Condição pagamento
        $condicao_pagamento = $this->COTACAO_MANUAL->getFormaPagamento($integrador, $cliente['id'], $this->session->id_fornecedor, $estado['id']);

        $nome = (!empty($cliente['nome_fantasia'])) ? $cliente['nome_fantasia'] : $cliente['razao_social'];

        $row = "";

        if (isset($type)) {

            $row .= "
                <div class='card'>
                    <div class='card-header'> <h4 class='card-title'>Dados da Cotação</h4></div>
                    <div class='card-body'>
                        <div class='row'>
                            <div class='col-xs-4 col-sm-4 col-md-4 col-lg-4'>
                                <p>
                                    <b>Número da Cotação:</b> {$cd_cotacao} <br>
                                    <b>Data e Hora de início:</b> {$dataini} <br>
                                    <b>Tipo de Frete:</b> CIF
                                </p>
                            </div>
                            <div class='col-xs-4 col-sm-4 col-md-4 col-lg-4'>
                                <p>
                                    <b>Situação:</b> EM ANDAMENTO <br>
                                    <b>Data e Hora do Término:</b> {$datafim} <br>
                                    <b>Condição de Pagamento:</b> {$condicao_pagamento}
                                </p>
                            </div>
                            <div class='col-xs-4 col-sm-4 col-md-4 col-lg-4'>
                                <p>
                                    <b>Comprador:</b> {$cliente['razao_social']} <br>
                                    {$data_validade}
                                    &nbsp;
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <table class='table table-bordered table-hover table-sm' style='border-collapse: collapse'>
                    <thead>
                         <tr>
                            <th>Descrição</th>
                            <th>Unidade</th>
                            <th>Quantidade Solicitada</th>
                        </tr>
                    </thead>
                    <tbody>
            ";


            foreach ($cotacoes_produtos as $produto) {
                $comp = (!empty($produto['ds_complementar'])) ? " - {$produto['ds_complementar']}" : "";

                $row .= "
                    <tr>
                        <td>{$produto['ds_produto_comprador']}{$comp}</td>
                        <td>{$produto['ds_unidade_compra']}</td>
                        <td>{$produto['qt_produto_total']}</td>
                    </tr>
                ";
            }

            $row .= "
                    </tbody>
                </table>
            ";
        } else {

            $row .= "
                <div class='card'>
                        <div class='card-header'> <h4 class='card-title'>Dados da Cotação</h4></div>
                        <div class='card-body'>
                            <div class='row'>
                                <div class='col-xs-4 col-sm-4 col-md-4 col-lg-4'>
                                    <p>
                                        <b>Número da Cotação:</b> {$cd_cotacao} <br>
                                        <b>Data e Hora de início:</b> {$dataini} <br>
                                        <b>Tipo de Frete:</b> CIF<br>
                                        <br>Observações:</b>
                                    </p>
                                </div>
                                <div class='col-xs-4 col-sm-4 col-md-4 col-lg-4'>
                                    <p>
                                        <b>Situação:</b> EM ANDAMENTO <br>
                                        <b>Data e Hora do Término:</b> {$datafim} <br>
                                        <b>Condição de Pagamento:</b> {$condicao_pagamento}
                                    </p>
                                </div>
                                <div class='col-xs-4 col-sm-4 col-md-4 col-lg-4'>
                                    <p>
                                        <b>Data de Validade:</b> {$data_validade} <br>
                                        &nbsp; <br>
                                        &nbsp;
                                    </p>
                                </div>
                            </div>
                        </div>
                </div>
                <div class='card'>
                    <div class='card-header'> <h4 class='card-title'>Dados do Comprador</h4></div>
                    <div class='card-body'>
                        <div class='row'>
                            <div class='col-xs-4 col-sm-4 col-md-4 col-lg-4'>
                                <p>
                                    <b>CNPJ:</b> {$cnpj} <br>
                                    <b>Logradouro:</b> {$cliente['cidade']} <br>
                                    <b>Fones:</b> {$cliente['telefone']}
                                </p>
                            </div>
                            <div class='col-xs-4 col-sm-4 col-md-4 col-lg-4'>
                                <p>
                                    <b>Nome Fantasia:</b> {$nome} <br>
                                    <b>Complemento:</b> {$cliente['complemento']} <br>
                                    <b>CEP:</b> {$cliente['cep']}
                                </p>
                            </div>
                            <div class='col-xs-4 col-sm-4 col-md-4 col-lg-4'>
                                <p>
                                    <b>Usuário:</b> {$nome_usuario} <br>
                                    <b>Bairro:</b> {$cliente['bairro']} <br>
                                    <b>UF:</b> {$cliente['estado']}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            ";
        }

        $dataView = [
            'header' => $this->template->header(['title' => '']),
            'scripts' => $this->template->scripts(),
            'msg' => $row
        ];

        $html = $this->load->view("{$this->views}/model_details", $dataView, true);

        $this->output->set_content_type('text/html')->set_output($html);
    }

    # JSON

    /**
     * Exclui a restrição de um produto
     *
     * @param - Array POST
     * @return  json
     */
    public function deleteRestriction()
    {

        if ($this->input->is_ajax_request()) {

            $post = $this->input->post();

            $rest = $this->restricao_cotacao->deleteProductRestriction($post['integrador'], $post['cd_cotacao'], $this->session->id_fornecedor, $post['cd_produto_comprador'], $post['id_produto_sintese']);

            if ($rest) {

                $output = ['type' => 'success', 'message' => notify_delete];
            } else {

                $output = ['type' => 'warning', 'message' => notify_failed];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Verifica se existe o produto sintese no BD para realizar o depara
     *
     * @param - String nome do integrador
     * @param - String codigo do produto
     * @param - INT ID do comprador
     * @return  json
     */
    public function findProduct($integrador, $codigo_produto, $id_cliente, $cdProdutoCliente = null)
    {

        switch (strtoupper($integrador)) {
            case 'SINTESE':
                $id_produto = $codigo_produto;
                $produto = $this->pms->find('*', "id_produto = {$id_produto}", true);

                if (isset($produto) && !empty($produto)) {

                    $output = ['type' => 'success', 'message' => 'Produto encontrado!', 'link' => "{$this->route}index_depara/{$integrador}/{$id_produto}/{$id_cliente}/{$cdProdutoCliente}"];
                } else {

                    $client = new SoapClient("http://integracao.plataformasintese.com/IntegrationService.asmx?WSDL");
                    $location = 'http://integracao.plataformasintese.com/IntegrationService.asmx';

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

                                $output = ["type" => 'warning', 'message' => "Produto sintese não encontrado no nosso banco de dados."];
                            } else {

                                $output = ['type' => 'success', 'message' => 'Produto encontrado!', 'link' => "{$this->route}index_depara/{$integrador}/{$id_produto}/{$id_cliente}/{$cdProdutoCliente}"];
                            }
                        }
                    }
                }
                break;
            case 'BIONEXO':
                $cd_produto_comprador = $codigo_produto;

                $produto = $this->DB_BIONEXO->where('codigo', $cd_produto_comprador)->where_in('id_cliente', $id_cliente)->get('catalogo')->row_array();

                $cliente = $this->compradores->findById($id_cliente);

                if (isset($produto) && !empty($produto)) {

                    $output = ['type' => 'success', 'message' => 'Produto encontrado!', 'link' => "{$this->route}index_depara/{$integrador}/{$cd_produto_comprador}/{$id_cliente}"];
                } else {

                    $lista_adms = $this->usuario->listAdmMaster();

                    foreach ($lista_adms as $usuario) {


                        $token = base64_encode("sem_DEPARA@{$usuario['id']}_{$cd_produto_comprador}_{$id_cliente}");
                        $message = "Produto bionexo não encontrado em nosso banco de dados. #COD. do Produto: {$cd_produto_comprador}, 
                        Comprador: {$cliente['cnpj']} - {$cliente['razao_social']}
                    ";

                        $this->notify->alertAdmin('danger', $usuario['id'], $message, $token);
                    }

                    $output = ["type" => 'warning', 'message' => "Produto bionexo não encontrado em nosso banco de dados."];
                }
                break;
            case 'APOIO':
                $cd_produto_comprador = $codigo_produto;

                $produto = $this->DB_APOIO->where('codigo', $cd_produto_comprador)->where_in('id_cliente', $id_cliente)->get('catalogo')->row_array();

                $cliente = $this->compradores->findById($id_cliente);

                if (isset($produto) && !empty($produto)) {

                    $output = ['type' => 'success', 'message' => 'Produto encontrado!', 'link' => "{$this->route}index_depara/{$integrador}/{$cd_produto_comprador}/{$id_cliente}"];
                } else {

                    $lista_adms = $this->usuario->listAdmMaster();

                    foreach ($lista_adms as $usuario) {


                        $token = base64_encode("sem_DEPARA@{$usuario['id']}_{$cd_produto_comprador}_{$id_cliente}");
                        $message = "Produto apoio não encontrado em nosso banco de dados. #COD. do Produto: {$cd_produto_comprador}, 
                        Comprador: {$cliente['cnpj']} - {$cliente['razao_social']}
                    ";

                        $this->notify->alertAdmin('danger', $usuario['id'], $message, $token);
                    }

                    $output = ["type" => 'warning', 'message' => "Produto apoio não encontrado em nosso banco de dados."];
                }
                break;
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    /**
     * Função que registra a revisão da cotação
     *
     * @param - GET - String codigo da cotação
     * @param - POST - int status (1 -> revisado, 0 -> sem revisao)
     * @param - POST - String nome do integrador
     * @return  json
     */
    public function review($cd_cotacao)
    {
        if ($this->input->is_ajax_request()) {

            $post = $this->input->post();

            if (isset($post['status'])) {

                switch (strtoupper($post['integrador'])) {
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
                $dbcot->where('id_fornecedor', $this->session->id_fornecedor);
                $dbcot->where('cd_cotacao', $cd_cotacao);
                $updt = $dbcot->update('cotacoes', ['revisao' => $post['status']]);
                if ($updt) {

                    $warning = ['type' => 'success', 'message' => notify_update];
                } else {

                    $warning = ['type' => 'warning', 'message' => notify_failed];
                }
            } else {

                $warning = ['type' => 'warning', 'message' => "Erro ao enviar status!"];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
    }

    /**
     * Oculta/Desoculta a cotação
     *
     * @param - String - nome do integrador
     * @param - String - codigo da cotação
     * @return  json
     */
    public function changeHide($integrador, $cd_cotacao)
    {
        if ($this->input->is_ajax_request()) {

            $post = $this->input->post();

            $ocultarCot = $this->COTACAO_MANUAL->changeHide($integrador, $cd_cotacao, $this->session->id_fornecedor);

            if ($ocultarCot) {

                $warning = ['type' => 'success', 'message' => notify_update];
            } else {

                $warning = $this->notify->errorMessage();
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
    }

    /**
     * Função para combinar produto com marca
     *
     * @return  json
     */
    public function combinar_produto_marca($integrador)
    {
        if ($this->input->is_ajax_request()) {

            $post = $this->input->post();

            $depara = $this->COTACAO_MANUAL->depara($integrador, $post);

            if ($depara['type']) {

                $warning = ["type" => "success", "message" => $depara['message']];
            } else {

                $warning = ["type" => "warning", "message" => $depara['message']];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
    }

    /**
     * Desoculta a cotação
     * @param - POST - Array (cd_cotacao, integrador)
     * @return  json
     */
    public function desocultar()
    {
        if ($this->input->is_ajax_request()) {

            $post = $this->input->post();

            $updt = $this->COTACAO_MANUAL->unhide($post['dados']);

            if ($updt) {

                $warning = ['type' => 'success', 'message' => notify_update];
            } else {

                $warning = $this->notify->errorMessage();
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
    }

    /**
     * obtem histórico de ofertas de um produto
     *
     * @param - GET - String nome do integrador
     * @param - POST - String numero do produto
     * @return  json
     */
    public function getHistory($integrador)
    {
        if ($this->input->is_ajax_request()) {

            $post = $this->input->post();

            $data = $this->COTACAO_MANUAL->getHistory($integrador, $post['codigo'], $this->session->id_fornecedor, $post['id_cliente']);

            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    /**
     * Função que altera o preço, marca do produto e verifica restrição ao trocar de fornecedor
     *
     * @return  json
     */
    public function setProduct()
    {
        if ($this->input->is_ajax_request()) {

            $post = $this->input->post();

            # Produto
            $this->db->where('codigo', $post['codigo']);
            $this->db->where('id_fornecedor', $post['id_fornecedor']);
            $produto = $this->db->get("produtos_catalogo")->row_array();

            # Marca
            $marca = $this->marca->get_row($produto['id_marca'])['marca'];

            # Restricao
            $this->db->where('id_fornecedor', $post['id_fornecedor']);
            $this->db->where('id_produto', $post['codigo']);
            $this->db->group_start();
            $this->db->where('id_cliente', $post['id_cliente']);
            $this->db->or_where('id_estado', $post['id_estado']);
            $this->db->group_end();

            $restricao = $this->db->get('restricoes_produtos_clientes')->row_array();

            # Ultima oferta
            $ultima_oferta = $this->COTACAO_MANUAL->getUltimaOfertaProdutoComprador($post['codigo'], $post['id_fornecedor'], $post['id_cliente']);

            # Preco
            $preco_unit = $this->price->getPrice([
                'id_fornecedor' => $post['id_fornecedor'],
                'codigo' => $post['codigo'],
                'id_estado' => $post['id_estado']
            ]);

            $quantidade_unidade = (isset($produto['quantidade_unidade']) && $produto['quantidade_unidade'] != 0) ? $produto['quantidade_unidade'] : 1;


            if (is_null($restricao) || empty($restricao)) {

                $preco_caixa = ($preco_unit != '0.0000') ? $preco_unit * $quantidade_unidade : 0;

                $data = [
                    'preco_unidade' => number_format($preco_unit, 4, ",", "."),
                    'preco_caixa' => number_format($preco_caixa, 4, ",", "."),
                    'ultima_oferta' => number_format($ultima_oferta, 4, ",", "."),
                    'qtd' => $quantidade_unidade,
                    'marca' => $marca
                ];


                $type = 'success';
            } else {

                $data = null;

                $type = 'warning';
            }

            $warning = ['type' => $type, 'data' => $data];

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
    }

    /**
     * Salva os filtros em session da lista de cotações
     *
     * @return void
     */
    public function filtros()
    {
        $post = $this->input->post();

        $_SESSION['filtros'] = $post;
    }

    # Datatables / Selects

    /**
     * Exibe a lista de cotações sintese e bionexo
     *
     * @param - String sigla do estado
     * @return  json
     */
    public function datatables_cotacoes($uf = null)
    {

        $datatables = $this->cotacoes->cotacoesEmAberto($uf);

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    /**
     * Exibe o catalogo de produtos do fornecedor
     *
     * @return  json
     */
    public function datatable_catalogo()
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

                        return strtoupper("{$row['nome_comercial']} - {$row['descricao']}");
                    }
                    return strtoupper("{$row['nome_comercial']} - {$row['apresentacao']}");
                }],
            ],
            null,
            "id_fornecedor = {$this->session->id_fornecedor}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    /**
     * Exibe o catalogo da sintese
     *
     * @return  json
     */
    public function datatable_sintese()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $datatables = $this->datatable->exec(
            $this->input->get(),
            'produtos_marca_sintese',
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'id_sintese', 'dt' => 'id_sintese'],
                ['db' => 'id_produto', 'dt' => 'id_produto'],
                ['db' => "UPPER(descricao)", 'dt' => 'descricao'],
                ['db' => 'marca', 'dt' => 'marca'],
            ],
            null,
            null,
            "id_produto"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    /**
     * Exibe as cotações em aberto que foram ocultadas
     *
     * @return  json
     */
    public function datatable_ocultadas()
    {
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
                ['db' => 'cot.nome', 'dt' => 'nome'],
                ['db' => 'cot.dt_fim_cotacao', 'dt' => 'dt_fim_cotacao'],
                ['db' => 'c.cnpj', 'dt' => 'cnpj'],
                ['db' => 'cot.ds_cotacao', 'dt' => 'ds_cotacao', 'formatter' => function ($value, $row) {
                    return "<small>{$value}</small>";
                }],
                ['db' => 'c.razao_social', 'dt' => 'comprador', 'formatter' => function ($value, $row) {

                    return "<small>{$row['cnpj']} - {$value}</small>";
                }],
                ['db' => 'cot.dt_fim_cotacao', 'dt' => 'datafim', 'formatter' => function ($value, $row) {

                    return date('d/m/Y H:i', strtotime($value));
                }],
                ['db' => 'cot.dt_inicio_cotacao', 'dt' => 'dataini', 'formatter' => function ($value, $row) {

                    return date('d/m/Y H:i', strtotime($value));
                }],
                ['db' => 'cot.motivo_recusa', 'dt' => 'motivo_recusa'],
                ['db' => 'cot.motivo_recusa', 'dt' => 'motivo_recusa_text', 'formatter' => function ($value, $row) {
                    return (intval($value) > 0) ? getMotivosRecusa($value) : '';
                }],
            ],
            [
                ['compradores c', 'c.id = cot.id_cliente ', 'left']
            ],
            "cot.id_fornecedor = {$this->session->id_fornecedor} AND (cot.oculto = 1 OR cot.motivo_recusa > 0)"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    /**
     *  Obtem as formas de pagamento
     *
     * @return  json
     */
    public function to_select2_formas_pagamento()
    {
        $data = [];
        if (isset($_GET['page'])) {

            $page = $this->input->get('page');
            $length = 50;
            $data = [
                "start" => (($page - 1) * 50),
                "length" => $length
            ];
        }

        $dados = $this->COTACAO_MANUAL->selectFormaPagamento($data);

        $this->output->set_content_type('application/json')->set_output(json_encode($dados));
    }

    private function removeProdutoDuplicados($cd_cotacao, $id_fornecedor)
    {
        //remove itens com preço zero que não foram enviados

        $produtos = $this->db->select('cd_cotacao, cd_produto_comprador, count(0) as total')
            ->where('cd_cotacao', $cd_cotacao)
            ->where('id_fornecedor', $id_fornecedor)
            ->from('cotacoes_produtos')
            ->group_by('cd_cotacao, cd_produto_comprador')
            ->get()->result_array();


        foreach ($produtos as $produto) {
            $total = intval($produto['total']);
            if ($total > 1) {

                $this->db
                    ->where('cd_cotacao', $cd_cotacao)
                    ->where('id_fornecedor', $id_fornecedor)
                    ->where('cd_produto_comprador', $produto['cd_produto_comprador'])
                    ->where('integrador', "BIONEXO")
                    ->where('preco_marca = 0')
                    ->delete('cotacoes_produtos');
            }
        }
    }
}
