<?php
date_default_timezone_set('America/Sao_Paulo');
error_reporting(0);
ini_set("display_errors", 0);

class Cotacoes_oncoprod extends MY_Controller
{
    private $urlCliente;
    private $host;
    private $client;
    private $location;
    private $route;
    private $views;
    private $oncoprod;
    private $DB_COTACAO;


    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('/fornecedor/cotacoes_oncoprod/');
        $this->views = 'fornecedor/cotacoes_oncoprod/';

        $this->load->model('m_fornecedor', 'fornecedores');
        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_estados', 'estados');
        $this->load->model('m_forma_pagamento', 'forma_pagamento');
        $this->load->model('m_marca', 'marcas');
        $this->load->model('m_restricao_produto_cotacao', 'restricoes_cotacao');
        $this->load->model('produto_marca_sintese', 'pms');
        $this->load->model('m_usuarios', 'usuarios');
        $this->load->model('m_preco_mix', 'preco_mix');
        $this->load->model('m_cotacoes', 'cotacoes');

//        error_reporting(E_ALL);
//        ini_set('display_errors', 1);


        $this->urlCliente = $this->config->item('db_config')['url_client'];

        $this->oncoprod = explode(',', ONCOPROD);
        $this->DB_COTACAO = $this->load->database('sintese', TRUE);

    }

    /**
     * Executa o POST do formulario
     *
     * @param - INT flag para sinalizar POST do modo rascunho
     * @return  json/view
     */
    public function enviar_resposta($rascunho = null)
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            $cd_cotacao = $post['cd_cotacao'];
            $dt_cotacao = $post['dt_inicio_cotacao'];

            # Observação da cotação
            $obs_cotacao = $post['obs'];

            # Obtem o comprador
            $cliente = $this->compradores->get_byCNPJ($post['cnpj_comprador']);

            # Obtem o estado do comprador
            $estado = $this->estados->find("*", "uf = '{$cliente['estado']}'", true);

            # Obtem as regras de venda
            $regras_venda = $this->getSaleRules($post, $cliente['id'], $this->session->id_fornecedor, $estado['id']);

            if ($regras_venda['type'] == 'warning') {

                if (isset($rascunho) && $rascunho == 2) {

                    $id_forma_pagamento = 1;
                    $prazo_entrega = 1;
                } else {

                    $this->session->set_userdata('warning', $regras_venda);
                    redirect("{$this->route}detalhes/{$post['integrador']}/{$cd_cotacao}");
                }
            } else {

                $id_forma_pagamento = $regras_venda['message']['id_forma_pagamento'];
                $prazo_entrega = $regras_venda['message']['prazo_entrega'];
            }

            # Define os Array de produtos de rascunho ou de envio
            if (isset($rascunho)) {

                $encontrados = $this->getProdutos($post['produtos'], $cd_cotacao, $this->session->id_fornecedor, $cliente['id'], $estado['id'])['encontrados'];
            } else {

                $produtos = $this->getProdutos($post['produtos'], $cd_cotacao, $this->session->id_fornecedor, $cliente['id'], $estado['id'], 1);

                # Se existir type no retorno da função, significa que houve erro no valor minimo.
                if (isset($produtos['type'])) {

                    $this->session->set_userdata("warning", $produtos);
                    redirect("{$this->route}detalhes/{$post['integrador']}/{$cd_cotacao}");
                }

                $encontrados = $produtos['encontrados'];
                $produtos_ordenados = $produtos['ordenados'];
            }


            # Remove os produtos rascunho para inserir atualizado
            # Produtos que foram enviados para a sintese e removidos em uma correção não são descartados.
            $this->db->where('id_fornecedor_logado', $this->session->id_fornecedor);
            $this->db->where('cd_cotacao', $cd_cotacao);
            $this->db->where('nivel', 1);
            $this->db->where('controle', 0);
            $this->db->where('submetido', 0);
            $this->db->where('ocultar', 0);
            $this->db->delete('cotacoes_produtos');

            # Se não existir nenhum produto marcado, ele já é redirecionado
            if (empty($encontrados)) {

                if (isset($rascunho) && $rascunho == 2) {

                    # Se o POST for do save automatico ele retorna em JSON
                    $warning = ['type' => 'warning', 'message' => 'Sem dados para registrar'];
                    $this->output->set_content_type('application/json')->set_output(json_encode($warning));
                } elseif (isset($rascunho) && $rascunho == 1) {

                    # Se o POST for rascunho ele permite salvar um rascunho sem produtos
                    $warning = ['type' => 'success', 'message' => "Rascunho salvo com sucesso!"];
                    $this->session->set_userdata("warning", $warning);

                    redirect("{$this->route}detalhes/{$post['integrador']}/{$cd_cotacao}");
                } else {

                    $warning = ['type' => 'warning', 'message' => "Não é possivel enviar uma cotação sem produtos!"];
                    $this->session->set_userdata("warning", $warning);

                    # Se o POST for do envio ele redireciona
                    redirect("{$this->route}detalhes/{$post['integrador']}/{$cd_cotacao}");
                }
            }

            # Obtem uma data para todos os produtos da cotação serem da mesma data
            $dataatual = date('Y-m-d H:i:s', strtotime("-1 hour"));

            # Obtem os dados para inserir em cotacoes_produtos
            $novos_produtos = [];
            foreach ($encontrados as $k => $produto) {

                # Separa os produtos que vão ser atualizados dos novos
                if (isset($produto['nivel'])) {

                    # Atualiza o produto de acordo com seu nivel
                    $this->db->where('id_fornecedor', $produto['id_fornecedor']);
                    $this->db->where('cd_cotacao', $cd_cotacao);
                    $this->db->where('id_pfv', $produto['codigo']);
                    $this->db->where('id_produto', $produto['id_produto']);
                    $this->db->where('cd_produto_comprador', $produto['cd_produto_comprador']);
                    $this->db->where('nivel', $produto['nivel']);
                    $this->db->update('cotacoes_produtos', [
                        "preco_marca" => $produto['preco_unidade'],
                        "id_fornecedor_logado" => intval($this->session->id_fornecedor),
                        "obs" => $post['obs'],
                        "obs_produto" => $produto['obs'],
                        'ocultar' => 0,
                        'data_criacao' => $dataatual
                    ]);
                } else {

                    # Armazena os novos produtos
                    $novos_produtos[] = [
                        "produto" => $produto['nome_comercial'] . " - " . $produto['apresentacao'],
                        "qtd_solicitada" => intval($produto['qtd_solicitada']),
                        "qtd_embalagem" => intval($produto['quantidade_unidade']),
                        "id_produto" => intval($produto['id_produto']),
                        "preco_marca" => $produto['preco_unidade'],
                        "cd_cotacao" => $cd_cotacao,
                        "id_fornecedor_logado" => $this->session->id_fornecedor,
                        "id_fornecedor" => $produto['id_fornecedor'],
                        "id_forma_pagamento" => intval($id_forma_pagamento),
                        "prazo_entrega" => intval($prazo_entrega),
                        "valor_minimo" => $produto['valor_minimo'],
                        "nivel" => 1,
                        "data_cotacao" => $dt_cotacao,
                        "cnpj_comprador" => $post['cnpj_comprador'],
                        "uf_comprador" => $cliente['estado'],
                        "id_cliente" => $cliente['id'],
                        'cd_produto_comprador' => $produto['cd_produto_comprador'],
                        "controle" => 0,
                        "submetido" => 0,
                        "id_cotacao" => time(),
                        "id_pfv" => $produto['codigo'],
                        "obs" => $post['obs'],
                        "obs_produto" => $produto['obs'],
                        'id_usuario' => $this->session->id_usuario,
                        "data_criacao" => $dataatual
                    ];
                }
            }

            if (!empty($novos_produtos)) {

                $this->db->insert_batch('cotacoes_produtos', $novos_produtos);
            }

            if (isset($rascunho)) {

                if ($rascunho == 1) {

                    $warning = ['type' => 'success', 'message' => "Rascunho salvo com sucesso!"];

                    $this->session->set_userdata("warning", $warning);

                    redirect("{$this->route}detalhes/{$post['integrador']}/{$cd_cotacao}");
                } else {

                    $warning = ['type' => 'success', 'message' => "Rascunho salvo com sucesso!"];
                    $this->output->set_content_type('application/json')->set_output(json_encode($warning));
                }
            } else {

                # Adiciona em array informações necessarias para gerar o XML e o espelho
                $info = [
                    'cd_cotacao' => $cd_cotacao,
                    'cliente' => $cliente,
                    'id_forma_pagamento' => $id_forma_pagamento,
                    'prazo_entrega' => $prazo_entrega,
                    'obs' => $obs_cotacao
                ];

                # Cria o XML
                $xml = $this->createXml($produtos_ordenados, $info);

                # Cria o espelho
                $mirror = $this->createMirror($produtos_ordenados, $info);

                $sendSintese = $this->sendSintese($xml, $cd_cotacao);

                if ($sendSintese['type'] == 'success') {

                    # Envia email com o espelho para o comprador
                    $sendEmails = $this->sendEmail($mirror, $cliente, $this->session->id_fornecedor, $cd_cotacao);

                    # Cabeçalho da cotação
                    $headerCotacao = $this->createHeaderCot($cd_cotacao, $this->session->id_fornecedor, $cliente['id'], $id_forma_pagamento, $prazo_entrega, $obs_cotacao);

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
                        'cd_cotacao' => $cd_cotacao,
                        'produtos' => (isset($log_produtos) && !empty($log_produtos)) ? json_encode(['produtos' => $log_produtos]) : null,
                        'restricoes' => (isset($log_restricoes) && !empty($log_restricoes)) ? json_encode(['restricoes' => $log_restricoes]) : null
                    ];

                    $registraLog = $this->db->insert('log_envio_manual', $log);

                    # Atualiza os itens enviados
                    $this->db->where('ocultar', 0);
                    $this->db->where('cd_cotacao', $cd_cotacao);
                    $this->db->where('id_fornecedor_logado', $this->session->id_fornecedor);
                    $this->db->update('cotacoes_produtos', [
                        'controle' => 1,
                        'submetido' => 1
                    ]);

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
                                    'url' => base_url('/fornecedor/cotacoes_oncoprod') . "?uf={$cliente['estado']}",
                                    'class' => 'btn-secondary',
                                    'icone' => 'fa-arrow-left',
                                    'label' => 'Voltar Lista de Cotações'
                                ],
                                [
                                    'type' => 'a',
                                    'id' => 'btnPdf',
                                    'url' => "{$this->route}exportar_pdf/{$cd_cotacao}/{$this->session->id_fornecedor}",
                                    'class' => 'btn-primary',
                                    'icone' => 'fa-file-pdf',
                                    'label' => 'Exportar PDF'
                                ]
                            ]
                        ]),
                        'mirror' => $mirror['html']
                    ];

                    $this->session->set_userdata("warning", $sendSintese);

                    $this->load->view("{$this->views}/mirror", $dataView);
                } else {

                    $this->session->set_userdata("warning", $sendSintese);

                    redirect("{$this->route}detalhes/{$post['integrador']}/{$cd_cotacao}");
                }
            }
        }
    }

    /**
     * Envia o XML de cada fornecedor selecionado para a sintese
     *
     * @param - Array - lista dos XML dos fornecedores
     * @param - String Codigo da cotação
     * @return  jarray
     */
    private function sendSintese($xmls, $cd_cotacao)
    {
        foreach ($xmls as $indice => $xml) {

            $envio = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
                <soapenv:Header/>
                <soapenv:Body>
                <tem:EnviarOfertas>
                <tem:xmlDoc>
                ' . $xml['xml'] . '
                </tem:xmlDoc>
                </tem:EnviarOfertas>
                </soapenv:Body>
                </soapenv:Envelope>';

            $file = "public/cotacoes_enviadas/{$xml['id_fornecedor']}_{$cd_cotacao}.xml";

            if (file_exists($file)) {
                unlink($file);
            }

            $arquivo = fopen($file, 'w');

            fwrite($arquivo, $envio);

            fclose($arquivo);

            $headers = array(
                "Content-type: text/xml;charset=\"utf-8\"",
                "Accept: text/xml",
                "Cache-Control: no-cache",
                "Pragma: no-cache",
                "SOAPAction: http://tempuri.org/EnviarOfertas",
                "Content-length: " . strlen($envio),
            );

            $data = date("d/m/Y H:i:s");

            foreach ($this->urlCliente as $url) {

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
                curl_setopt($ch, CURLOPT_URL, "{$url}?WSDL");
                curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
                #curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
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
                        "to" => "suporte@pharmanexo.com.br, marlon.boecker@pharmanexo.com.br, deivis.guimaraes@pharmanexo.com.br",
                        "greeting" => "",
                        "subject" => "Erro ao enviar COTAÇÃO  #{$cd_cotacao}",
                        "message" => "
                        <b>Fornecedor logado:</b> {$this->session->razao_social} <br>
                        <b>Usuário logado:</b> {$this->session->nome} <br>
                        <b>Fornecedor da cotação:</b> {$xml['id_fornecedor']} <br>
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
                        <b>Fornecedor da cotação:</b> {$xml['id_fornecedor']} <br>
                        <b>Data de Envio:</b> {$data} <br>
                        {$response}"
                    ];

                    $this->notify->send($msgEmail);

                    if (!strpos($response, 'incluídas')) {

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
                        elseif (strpos($response, "Erro na inclusão")) {
                            $type = 'warning';
                        } elseif (strpos($response, "documentação obrigatória")) {
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

                        $fornecedor = $this->fornecedores->findById($xml['id_fornecedor']);


                    } else {

                        $type = 'success';
                    }

                    $warning = ['type' => $type, 'message' => $response];

                    break;
                }
            }
        }

        return $warning;
    }

    /**
     * Envia email com o espelho daa cotação para os emails registrados para o comprador
     *
     * @param - Array - String mirror e filename
     * @param - Array - Objeto do comprador
     * @param - INT ID do fornecedor
     * @param - String codigo da cotação
     * @return  bool
     */
    public function sendEmail($mirror, $cliente, $id_fornecedor, $cd_cotacao)
    {
        if ($this->notify->automaticMessage("MIRROR_MANUAL") != false) {

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

                $this->DB_COTACAO->where('cd_cotacao', $cd_cotacao);
                $this->DB_COTACAO->where('id_fornecedor', $id_fornecedor);
                $cot = $this->DB_COTACAO->get('cotacoes')->row_array();

                $date = date('d/m/Y H:i:s', strtotime($cot['dt_fim_cotacao']));

                $nome_cliente = (!empty($cliente['nome_fantasia'])) ? $cliente['nome_fantasia'] : $cliente['razao_social'];

                # notificar por e-mail
                $notificar = [
                    "to" => $destinatarios,
                    "greeting" => "",
                    "subject" => "COTAÇÃO SÍNTESE #{$cd_cotacao} {$nome_cliente} - {$cliente['estado']} {$date}",
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
     * Exibe a view da lista de cotações
     *
     * @return view
     */
    public function index()
    {
        $page_title = "Cotações em aberto no Brasil";

        $get = $this->input->get();

        $url = "{$this->route}get_cotacoes";

        if (isset($get['uf'])) {

            $uf = strtoupper($get['uf']);

            $page_title = "Cotações em aberto no {$uf}";

            $url = "{$this->route}get_cotacoes/{$uf}";
            $this->session->set_userdata(['uf_cotacoes' => $get['uf']]);
        }

        $data = [
            'header' => $this->template->header(['title' => $page_title]),
            'navbar' => $this->template->navbar(),
            'sidebar' => $this->template->sidebar(),
            'heading' => $this->template->heading([
                'page_title' => $page_title,
                'buttons' => [
                    [
                        'type' => 'a',
                        'id' => 'btnDesocultar',
                        'url' => "{$this->route}index_ocultadas",
                        'class' => 'btn-danger',
                        'icone' => 'fa-eye-slash',
                        'label' => 'Cotações Ocultadas'
                    ]
                ]
            ]),
            'scripts' => $this->template->scripts()
        ];

        $data['url_cotacoes'] = $url;
        $data['url_info'] = "{$this->route}info_cotacao/";
        $data['url_revisar'] = "{$this->route}review/";

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Exibe a view da lista de cotações ocultadas
     *
     * @return view
     */
    public function index_ocultadas()
    {

        $page_title = "Lista de cotações ocultadas em aberto";

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
                ]
            ]),
            'scripts' => $this->template->scripts(),
        ];

        $this->DB_COTACAO->where("oculto", 1);
        $this->DB_COTACAO->where("dt_fim_cotacao > now()");
        $this->DB_COTACAO->where("id_fornecedor", $this->session->id_fornecedor);
        $cotacoes = $this->DB_COTACAO->get('cotacoes')->result_array();

        $data['cotacoes'] = $cotacoes;
        $data['url_desocultar'] = "{$this->route}desocultar/";


        $this->load->view("{$this->views}main_desocultar", $data);
    }

    /**
     * Exibe a view dos produtos da cotação
     *
     * @return view
     */
    public function detalhes($integrador, $cd_cotacao)
    {

        $idIntegrador = 0;
        $integradores = $this->db->get('integradores')->result_array();
        foreach ($integradores as $int) {

            if (strtolower($int['desc']) == strtolower($integrador)) {
                $idIntegrador = $int['id'];
                break;
            }
        }

        $page_title = "Cotação #{$cd_cotacao}";

        $data['integrador'] = $integrador;

        # Array com os dados da cotação, comprador e seus produtos
        $data['cotacao'] = $this->get_item($cd_cotacao);

        # Obtem o comprador
        $cliente = $data['cotacao']['cliente'];

        # Obtem o  estado do comprador
        $estado = $data['cotacao']['estado'];

        # Prazo entrega
        $data['prazo_entrega'] = $this->getPrazoEntrega('', $cliente['id'], $this->session->id_fornecedor, $estado['id']);

        # Condição pagamento
        $data['forma_pagamento'] = $this->getFormaPagamento('', $cliente['id'], $this->session->id_fornecedor, $estado['id']);

        # Verifica se acotação ja foi respondida
        $this->db->where('id_fornecedor_logado', $this->session->id_fornecedor);
        $this->db->where('cd_cotacao', $cd_cotacao);
        $this->db->where("id_forma_pagamento != 0");
        $this->db->where("prazo_entrega != 0");
        $cotacao_respondida = $this->db->get('cotacoes_produtos')->row_array();

        if (isset($cotacao_respondida) && !empty($cotacao_respondida)) {

            $data['prazo_entrega'] = $cotacao_respondida['prazo_entrega'];
            $data['forma_pagamento'] = $cotacao_respondida['id_forma_pagamento'];
            $data['observacao'] = $cotacao_respondida['obs'];
        } else {

            $this->db->where("id_fornecedor", $this->session->id_fornecedor);
            $this->db->where("id_estado", $estado['id']);
            $this->db->where_in("tipo", [2, 3]);
            $obsConfig = $this->db->get("configuracoes_envio")->row_array();

            if (isset($obsConfig) && !empty($obsConfig)) {

                $data['observacao'] = $obsConfig['observacao'];
            }
        }

        # Selects
        $data['select_formas_pagamento'] = "{$this->route}/to_select2_formas_pagamento";
        $data['select_prazo_entrega'] = "{$this->route}/to_select2_prazo_entrega";

        # URLs
        $data['url_historico'] = "{$this->route}get_historico";
        $data['url_ocultar'] = "{$this->route}ocultarCotacao/{$cd_cotacao}/{$this->session->id_fornecedor}";
        $data['url_saveme'] = "{$this->route}enviar_resposta/2";
        $data['url_revisar'] = "{$this->route}review/";
        $data['url_price'] = "{$this->route}setProduct/";
        $data['url_findProduct'] = "{$this->route}findProduct/";
        $data['save_price'] = "{$this->route}savePrice/";

        # form
        $data['form_action'] = "{$this->route}enviar_resposta";

        # Select de fornecedores ONCOPROD
        $data['options_fornecedores'] = $this->select_fornecedores($cd_cotacao);

        $retornar = ($this->session->has_userdata('perfil_comercial')) ? base_url('dashboard') : "{$this->route}?uf={$data['cotacao']['uf_cotacao']}";
        $descarte = "{$this->route}descartar/{$data['cotacao']['cd_cotacao']}/{$integrador}";

        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
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
                    'id' => 'btnDescarte',
                    'url' => $descarte,
                    'class' => 'btn-warning',
                    'icone' => 'fa-ban',
                    'label' => 'Descartar Cotação'
                ],
                [
                    'type' => 'a',
                    'id' => 'btnVoltar',
                    'url' => $retornar,
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
                ],
                [
                    'type' => 'submit',
                    'id' => 'btnRascunho',
                    'form' => "respostaCotacao",
                    'class' => 'btn-primary',
                    'icone' => 'fa-save',
                    'label' => 'Salvar Rascunho'
                ]
            ]
        ]);

        $data['recusa'] = $this->cotacoes->verificaRecusa($cd_cotacao, $this->session->id_fornecedor, $idIntegrador);

        $this->load->view("{$this->views}detail", $data);
    }


    public function descartar($cd_cotacao = null, $integrador = null)
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            // $exist = $this->cotacoes->verificaResposta($post['cotacao'], $this->session->id_fornecedor);

            $updateCot = [
                'motivo_recusa' => $post['motivo'],
                'usuario_recusa' => $this->session->id_usuario,
                'data_recusa' => date('Y-m-d H:i', time()),
                'obs_recusa' => $post['obs'],
                'oculto' => 1
            ];

            $dbCot = null;
            switch ($post['integrador']) {
                case 'SINTESE':
                    $dbCot = $this->load->database('sintese', true);
                    break;
                case 'APOIO':
                    $dbCot = $this->load->database('apoio', true);
                    break;
                case 'BIONEXO':
                    $dbCot = $this->load->database('bionexo', true);
                    break;
            }

            $dbCot->where('cd_cotacao', $post['cotacao']);
            $dbCot->where('id_fornecedor', $this->session->id_fornecedor);
            $r = $dbCot->update('cotacoes', $updateCot);

            if ($r) {
                $output = ['type' => 'success', 'message' => 'Cotação descartada'];
            } else {
                $output = ['type' => 'warning', 'message' => 'Cotação não autoriza descarte.'];
            }


            $this->output->set_content_type('application/json')->set_output(json_encode($output));

        } else {

            $idIntegrador = 0;
            $integradores = $this->db->get('integradores')->result_array();
            foreach ($integradores as $int) {

                if (strtolower($int['desc']) == strtolower($integrador)) {
                    $idIntegrador = $int['id'];
                    break;
                }
            }

            $recusa = $this->cotacoes->verificaRecusa($cd_cotacao, $this->session->id_fornecedor, $idIntegrador);

            $data = [
                'form_action' => "{$this->route}descartar",
                'cd_cotacao' => $cd_cotacao,
                'integrador' => $integrador,
                'recusa' => $recusa
            ];

            $this->load->view("{$this->views}formDescarte", $data);
        }
    }


    /**
     * Monta as informações das cotações para exibir na tela
     *
     * @param - String sigla do estado
     * @return  array
     */
    public function get_cotacoes($uf = null)
    {
        # Gambiarra para resolver o problema da data do servidor
        $now = date('Y-m-d H:i:s', strtotime("-1 hour"));

        # Obtem as cotações que não estão vencidas
        $this->DB_COTACAO->where('id_fornecedor', $this->session->id_fornecedor);
        $this->DB_COTACAO->where("dt_fim_cotacao > '{$now}'");
        $this->DB_COTACAO->where("oculto != 1");

        if (isset($uf)) {
            $this->DB_COTACAO->where('uf_cotacao', strtoupper($uf));
        }

        $this->DB_COTACAO->group_by('cd_cotacao');
        $this->DB_COTACAO->order_by('oferta DESC');
        $this->DB_COTACAO->order_by('dt_fim_cotacao ASC');
        $cotacoes = $this->DB_COTACAO->get('cotacoes')->result_array();

        $data = [];

        foreach ($cotacoes as $cotacao) {

            $cliente = $this->compradores->findById($cotacao['id_cliente']);

            # Busca os produtos
            $this->DB_COTACAO->select("id_produto_sintese");
            $this->DB_COTACAO->select("cd_produto_comprador");
            $this->DB_COTACAO->select("ds_produto_comprador");
            $this->DB_COTACAO->select("ds_unidade_compra");
            $this->DB_COTACAO->select("ds_complementar");
            $this->DB_COTACAO->select("cd_cotacao");
            $this->DB_COTACAO->select("SUM(qt_produto_total) AS qt_produto_total");
            $this->DB_COTACAO->where('cd_cotacao', $cotacao['cd_cotacao']);
            $this->DB_COTACAO->where('id_fornecedor', $this->session->id_fornecedor);
            $this->DB_COTACAO->group_by('id_produto_sintese, cd_produto_comprador, ds_produto_comprador, ds_unidade_compra, ds_complementar, cd_cotacao');
            $produtos_cotacao = $this->DB_COTACAO->get('cotacoes_produtos')->result_array();

            # Verifica se existe algum item respondido
            $this->db->where('cd_cotacao', $cotacao['cd_cotacao']);
            $this->db->where('id_fornecedor', $this->session->id_fornecedor);
            $this->db->where('submetido', 1);
            $cotacao_resp = $this->db->get('cotacoes_produtos')->result_array();


            $cotacao['integrador'] = isset($cotacao['integrador']) ? $cotacao['integrador'] : 'SINTESE';

            $cotacaoArray = [];
            if (!empty($cliente)) {
                $cotacaoArray = [
                    "cd_cotacao" => $cotacao['cd_cotacao'],
                    "cnpj" => mask($cotacao['cd_comprador'], '##.###.###/####-##'),
                    "cliente" => $cliente,
                    "condicao_pagamento" => $this->forma_pagamento->findById($cotacao['cd_condicao_pagamento'])['descricao'],
                    "data_inicio" => date("d/m/Y H:i", strtotime($cotacao['dt_inicio_cotacao'])),
                    "data_fim" => date("d/m/Y H:i", strtotime($cotacao['dt_fim_cotacao'])),
                    "Dt_Validade_Preco" => $cotacao['dt_validade_preco'],
                    "Ds_Entrega" => (isset($cotacao['ds_entrega'])) ? $cotacao['ds_entrega'] : '',
                    "Ds_Filiais" => isset($cotacao['ds_filiais']) ? $cotacao['ds_filiais'] : '',
                    "Ds_Cotacao" => isset($cotacao['ds_cotacao']) ? $cotacao['ds_cotacao'] : '',
                    "itens" => count($produtos_cotacao),
                    "link" => "{$this->route}detalhes/{$cotacao['integrador']}/{$cotacao['cd_cotacao']}",
                    "bolinha" => isset($cotacao['oferta']) ? $cotacao['oferta'] : '',
                    'em_aberto' => (date("Y-m-d H:i:s", strtotime($cotacao['dt_fim_cotacao'])) > date('Y-m-d H:i:s')) ? 1 : 0,
                    'respondido' => (isset($cotacao_resp) && !empty($cotacao_resp)) ? 1 : 0,
                    'revisao' => $cotacao['revisao']
                ];

                array_push($data, $cotacaoArray);
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Monta as informações dos produtos da cotação para serem exibidos na tela
     *
     * @param - String codigo da cotação
     * @return  array
     */
    public function get_item($cd_cotacao)
    {
        # Obtem Cotação
        $this->DB_COTACAO->where('cd_cotacao', $cd_cotacao);
        $this->DB_COTACAO->where('id_fornecedor', $this->session->id_fornecedor);
        $cotacao = $this->DB_COTACAO->get('cotacoes')->row_array();

        if (is_null($cotacao)) {
            $_SESSION['warning'] = [
                'type' => 'warning',
                'message' => 'Cotação não localizada'
            ];

            redirect(base_url('dashboard'));
        }

        # Obtem Cliente
        $cliente = $this->compradores->findById($cotacao['id_cliente']);


        # Obtem o estado do cliente
        $estado = $this->estados->find("*", "uf = '{$cliente['estado']}'", true);

        # Realiza o depara dos produtos, monta com as informações pharmanexo e ordena os registros
        $produtos = $this->matchProducts($cd_cotacao, $this->session->id_fornecedor, $cliente['id'], $estado['id']);


        # Cabeçalho de cada produto
        $data = [
            "cd_cotacao" => $cotacao['cd_cotacao'],
            "oculto" => $cotacao['oculto'],
            "revisao" => $cotacao['revisao'],
            "cnpj" => mask($cotacao['cd_comprador'], '##.###.###/####-##'),
            "cliente" => $cliente,
            "estado" => $estado,
            "condicao_pagamento" => $this->forma_pagamento->findById($cotacao['cd_condicao_pagamento'])['descricao'],
            "cd_condicao_pgto" => $cotacao['cd_condicao_pagamento'],
            "data_inicio" => $cotacao['dt_inicio_cotacao'],
            "data_fim" => $cotacao['dt_fim_cotacao'],
            "uf_cotacao" => $cotacao['uf_cotacao'],
            "Dt_Validade_Preco" => $cotacao['dt_validade_preco'],
            "Ds_Entrega" => $cotacao['ds_entrega'],
            "Ds_Filiais" => $cotacao['ds_filiais'],
            "Ds_Cotacao" => $cotacao['ds_cotacao'],
            "Ds_Observacao" => $cotacao['ds_observacao'],
            "itens" => count($produtos),
            "produtos" => $produtos
        ];

        return $data;
    }

    /**
     * Separa os produtos enviados do POST em arrays com as informações necessárias
     *
     * @param - Array POST de produtos da requisição
     * @param - String codigo da cotação
     * @param - INT ID do fornecedor
     * @param - INT ID do cliente
     * @param - INT ID do estado do cliente
     * @param - INT flag para diferenciar o resultado da função (envios e rascunhos )
     * @return  array
     */
    public function getProdutos($produtos, $cd_cotacao, $id_fornecedor, $id_cliente, $id_estado, $envio = null)
    {
        $produtosEncontrados = [];
        $produtosOrdenados = [];

        # Remove todas as restrições para inseri-las novamente
        $this->restricoes_cotacao->excluir($cd_cotacao, $id_fornecedor);

        $prods = [];
        foreach ($produtos as $kk => $produto) {


            if (isset($produto['restricao']) || isset($produto['ol']) || isset($produto['sem_estoque'])) {

                $this->restricoes_cotacao->gravar([
                    'cd_cotacao' => $cd_cotacao,
                    'cd_produto_comprador' => $kk,
                    'id_produto_sintese' => $produto['id_produto_sintese'],
                    'estoque' => $produto['estoque'],
                    'ol' => (isset($produto['ol'])) ? 1 : 0,
                    'sem_estoque' => (isset($produto['sem_estoque'])) ? 1 : 0,
                    'restricao' => (isset($produto['restricao'])) ? 1 : 0,
                    'id_fornecedor' => $id_fornecedor,
                    'id_usuario' => $this->session->id_usuario
                ]);
            }

            foreach ($produto['marcas'] as $p) {

                if (isset($p['marcado'])) {

                    # Obtem o produto no catalogo
                    $this->db->select("*");
                    $this->db->where("codigo", $p['codigo']);
                    $this->db->where("id_fornecedor", $p['id_fornecedor']);
                    $produto_catalogo = $this->db->get('produtos_catalogo')->row_array();

                    # Valor minimo
                    $valor_minimo = $this->getValorMinimo($id_cliente, $p['id_fornecedor'], $id_estado);

                    # Somente no envio que será notificado se não existe valor minimo
                    if (!isset($valor_minimo) && isset($envio)) {

                        $f = $this->fornecedores->findById($p['id_fornecedor']);

                        $message = "É necessário configurar um valor mínimo para a filial: {$f['nome_fantasia']}, em regras de vendas -> valor minimo";

                        return ["type" => "warning", "message" => $message];
                    } else {

                        # Adiciona no array o valor minimo
                        $produto_catalogo['valor_minimo'] = (isset($valor_minimo) && !empty(isset($valor_minimo))) ? $valor_minimo : 0;
                    }

                    # Adiciona no array o id_produto
                    $produto_catalogo['id_produto'] = intval($p['id_produto']);

                    # Adiciona no array a quantidade solicitada
                    $produto_catalogo['qtd_solicitada'] = intval($p['qt_produto_total']);

                    # Adiciona no Array o cd_produto_comprador
                    $produto_catalogo['cd_produto_comprador'] = $p['cd_produto_comprador'];

                    # Adiciona no Array o ds_unidade_compra
                    $produto_catalogo['ds_unidade_compra'] = $p['ds_unidade_compra'];

                    # Adiciona no Array o ds_produto_comprador
                    $produto_catalogo['ds_produto_comprador'] = $p['ds_produto_comprador'];

                    # Adiciona no array o preço
                    if (isset($p['preco_oferta']) && !empty($p['preco_oferta'])) {

                        $produto_catalogo['preco_unidade'] = dbNumberFormat($p['preco_oferta']);
                    } else {

                        $produto_catalogo['preco_unidade'] = 0;
                    }

                    # Adiciona no array a observação
                    if (isset($p['obs']) && !empty($p['obs'])) {

                        $produto_catalogo['obs'] = $produto_catalogo['nome_comercial'] . ' - ' . $p['obs'];
                    } else {

                        $produto_catalogo['obs'] = $produto_catalogo['nome_comercial'];
                    }

                    # Adiciona o nivel se existir para identifcar os produtos que vão ser atualizados
                    if (isset($p['nivel'])) {

                        $produto_catalogo['nivel'] = $p['nivel'];
                    }

                    $produtosEncontrados[] = $produto_catalogo;

                    # Se o formulario for enviado para sintese, organiza os produtos para gerar XML e espelho
                    if (isset($envio)) {

                        $produtosOrdenados[$produto_catalogo['id_fornecedor']]['valor_minimo'] = $produto_catalogo['valor_minimo'];

                        $produtosOrdenados[$produto_catalogo['id_fornecedor']]['produtos'][$kk] = [
                            'cd_produto_comprador' => $produto_catalogo['cd_produto_comprador'],
                            'ds_produto_comprador' => $produto_catalogo['ds_produto_comprador'],
                            'qt_produto_total' => $produto_catalogo['qtd_solicitada'],
                            'ds_unidade_compra' => $produto_catalogo['ds_unidade_compra'],
                            'id_produto_sintese' => $produto_catalogo['id_produto']
                        ];

                        $prods[] = $produto_catalogo;
                    }
                } else {

                    # Quando o item não está marcado, verificamos se ele ja foi respondido para inserirmos no XML zerado

                    if (isset($p['id_cotacao'])) {

                        $this->db->where('id', $p['id_cotacao']);
                        $updt = $this->db->update('cotacoes_produtos', [
                            'ocultar' => 1,
                            'submetido' => 0,
                            'preco_marca' => 0,
                            'id_fornecedor_logado' => $id_fornecedor
                        ]);

                        # Se o formulario for enviado para sintese, insere no XML os produtos que já foram enviados  mais foram excluidos.
                        if (isset($envio)) {

                            if (isset($produto['restricao'])) {

                                $p['id_fornecedor'] = $this->session->id_fornecedor;
                            }

                            # Busca o produto desmarcado para inserir no XML
                            $this->db->where('id_fornecedor', $p['id_fornecedor']);
                            $this->db->where('codigo', $p['codigo']);
                            $produto_catalogo = $this->db->get('produtos_catalogo')->row_array();

                            # Valor minimo
                            $valor_minimo = $this->getValorMinimo($id_cliente, $p['id_fornecedor'], $id_estado);

                            # Somente itens marcados no envio que serão notificado se não existe valor minimo
                            $produto_catalogo['valor_minimo'] = (!isset($valor_minimo)) ? 0 : $valor_minimo;

                            # Adiciona no array o id_produto
                            $produto_catalogo['id_produto'] = intval($p['id_produto']);

                            # Adiciona no array a quantidade solicitada
                            $produto_catalogo['qtd_solicitada'] = intval($p['qt_produto_total']);

                            # Adiciona no Array o cd_produto_comprador
                            $produto_catalogo['cd_produto_comprador'] = $p['cd_produto_comprador'];

                            # Adiciona no Array o ds_unidade_compra
                            $produto_catalogo['ds_unidade_compra'] = $p['ds_unidade_compra'];

                            # Adiciona no Array o ds_produto_comprador
                            $produto_catalogo['ds_produto_comprador'] = $p['ds_produto_comprador'];

                            # Adiciona no array o preço
                            $produto_catalogo['preco_unidade'] = 0;

                            # Adiciona no Array uma coluna para indicar que determinada marca foi excluida
                            $produto_catalogo['excluido'] = true;

                            # Adiciona no array a observação
                            if (isset($p['obs']) && !empty($p['obs'])) {

                                $produto_catalogo['obs'] = $produto_catalogo['nome_comercial'] . ' - ' . $p['obs'];
                            } else {

                                $produto_catalogo['obs'] = $produto_catalogo['nome_comercial'];
                            }

                            $produtosOrdenados[$produto_catalogo['id_fornecedor']]['valor_minimo'] = $produto_catalogo['valor_minimo'];

                            # Armazena no array para gerar o XML e o espelho da cotação
                            $produtosOrdenados[$produto_catalogo['id_fornecedor']]['produtos'][$kk] = [
                                'cd_produto_comprador' => $produto_catalogo['cd_produto_comprador'],
                                'ds_produto_comprador' => $produto_catalogo['ds_produto_comprador'],
                                'qt_produto_total' => $produto_catalogo['qtd_solicitada'],
                                'ds_unidade_compra' => $produto_catalogo['ds_unidade_compra'],
                                'id_produto_sintese' => $produto_catalogo['id_produto']
                            ];

                            $prods[] = $produto_catalogo;
                        }
                    }
                }
            }
        }

        # Insere os produtos marcados e produtos enviados desmarcados
        if (isset($prods) && !empty($prods)) {

            # Percorre os produtos, para organizar os produtos por fornecedor e cd_produto_comprador
            foreach ($prods as $row) {

                # Se existir indice no array dos produtos com o ID do fornecedor
                if (isset($produtosOrdenados[$row['id_fornecedor']])) {

                    $cd_comprador = str_replace('.', '', $row['cd_produto_comprador']);

                    # Se existir indice no array dos produtos com o cd_produto_comprador
                    if (isset($produtosOrdenados[$row['id_fornecedor']]['produtos'][$cd_comprador])) {

                        # Insere no respectivo indice
                        $produtosOrdenados[$row['id_fornecedor']]['produtos'][$cd_comprador]['marcas'][] = $row;
                    }
                }
            }
        }


        $data = [
            'encontrados' => (isset($produtosEncontrados) && !empty($produtosEncontrados)) ? $produtosEncontrados : null,
            'ordenados' => (isset($produtosOrdenados) && !empty($produtosOrdenados)) ? $produtosOrdenados : null,
        ];

        return $data;
    }

    /**
     * Combina os produtos pharmanexo encontrado no depara com os produtos da SINTESE
     *
     * @param - String codigo da cotação
     * @param - Int ID do fornecedor
     * @param - Int ID do comprador
     * @param - Int ID do estado do comprador
     * @return  function getDetailsProducts
     */
    public function matchProducts($cd_cotacao, $id_fornecedor, $id_cliente, $id_estado)
    {

        # Obtem os produtos da pharmanexo dos produtos da sintese
        $depara = $this->queryDepara($cd_cotacao, $id_fornecedor, " = {$id_estado}");

        # Lista dos produtos da cotação na SINTESE
        $this->DB_COTACAO->select("id_produto_sintese");
        $this->DB_COTACAO->select("TRIM(cd_produto_comprador) AS cd_produto_comprador");
        $this->DB_COTACAO->select("ds_produto_comprador");
        $this->DB_COTACAO->select("ds_unidade_compra");
        $this->DB_COTACAO->select("ds_complementar");
        $this->DB_COTACAO->select("cd_cotacao");
        $this->DB_COTACAO->select("SUM(qt_produto_total) AS qt_produto_total");
        $this->DB_COTACAO->where('cd_cotacao', $cd_cotacao);
        $this->DB_COTACAO->where('id_fornecedor', $id_fornecedor);
        $this->DB_COTACAO->group_by('id_produto_sintese, cd_produto_comprador, ds_produto_comprador, ds_unidade_compra, ds_complementar, cd_cotacao');
        $produtos_cotacao = $this->DB_COTACAO->get('cotacoes_produtos')->result_array();

        $produtos = [];

        # Faz a combinação dos produtos Pharmanexo x Sintese
        foreach ($produtos_cotacao as $produto) {

            $encontrados = [];

            if (isset($depara) && !empty($depara)) {

                foreach ($depara as $prod) {

                    if ($prod['id_produto'] == $produto['id_produto_sintese']) {

                        $encontrados[] = $prod;
                    }
                }
            }

            $produtos[] = [
                'cotado' => $produto,
                'encontrados' => $encontrados,
            ];
        }

        return $this->getDetailsProducts($produtos, $id_cliente, $id_estado);
    }

    /**
     * Obtem informações como preço, estoque e total de envios para os depara encontrados
     *
     * @param - Array de produtos
     * @param - Int ID do comprador
     * @param - Int ID do estado do comprador
     * @return  function OrganizeProducts
     */
    public function getDetailsProducts($produtos, $id_cliente, $id_estado)
    {

        # Lista das filiais da ONCOPROD
        $fornecedores_oncoprod = $this->db
            ->where('id_matriz', 1)
            ->where('bloqueado', 0)
            ->where('sintese', 1)
            ->get('fornecedores')
            ->result_array();


        $produtosSemEstoque = [];

        foreach ($produtos as $kk => $produto) {

            # Variavel para somar o total de estoque das marcas do produto
            $totalEstoque = 0;

            # Verifica Restrições como OL e S.E (sem estoque)
            $restricoes = $this->restricoes_cotacao->find($this->session->id_fornecedor, $produto['cotado']['id_produto_sintese'], $produto['cotado']['cd_produto_comprador'], $produto['cotado']['cd_cotacao']);

            # Verifica Restrição de venda
            $restricao_produto = $this->restricoes_cotacao->find($this->session->id_fornecedor, $produto['cotado']['id_produto_sintese'], $produto['cotado']['cd_produto_comprador'], $produto['cotado']['cd_cotacao'], 1);

            if (isset($restricoes) && !empty($restricoes)) {

                $produtos[$kk]['cotado']['ol'] = ($restricoes['ol'] == 1) ? 1 : 0;
                $produtos[$kk]['cotado']['sem_estoque'] = ($restricoes['sem_estoque'] == 1) ? 1 : 0;
            } else {
                $produtos[$kk]['cotado']['ol'] = 0;
                $produtos[$kk]['cotado']['sem_estoque'] = 0;
            }

            if (isset($restricao_produto) && !empty($restricao_produto)) {

                $produtos[$kk]['cotado']['restricao'] = ($restricao_produto['restricao'] == 1) ? 1 : 0;
            } else {

                $produtos[$kk]['cotado']['restricao'] = 0;
            }


            if (isset($produto['encontrados']) && !empty($produto['encontrados'])) {

                foreach ($produto['encontrados'] as $k => $p) {

                    $estoque = [];

                    # Busca se existe venda diferenciada
                    $vd = $this->getVendaDiferenciada($p['id_fornecedor'], $p['codigo'], $id_cliente, $id_estado);


                    /// busca preços fixo ///

                    $params = [
                        'id_fornecedor' => $p['id_fornecedor'],
                        'id_cliente' => $id_cliente,
                        'codigo' => $p['codigo']
                    ];
                    $pMix = $this->preco_mix->get_item($params);

                    if (isset($pMix['preco_base']) && !empty($pMix['preco_base'])) {
                        $p['preco_unitario'] = $pMix['preco_base'];
                    }


                    ///////////////////////////////////////////////////////////////////////////////////////
                    # PREÇO VERSAO PARA O MYSQL

                    $newPrice = ($p['preco_unitario'] / $p['quantidade_unidade']);

                    if (isset($vd) and !empty($vd['desconto_percentual'])) {

                        $newPrice = $newPrice - ($newPrice * (floatval($vd['desconto_percentual']) / 100));
                    }

                    $produtos[$kk]['encontrados'][$k]['preco_unitario'] = $newPrice;
                    $produtos[$kk]['encontrados'][$k]['preco_caixa'] = $newPrice * $p['quantidade_unidade'];

                    ///////////////////////////////////////////////////////////////////////////////////////

                    # Obtem o preço
                    // $produtos[$kk]['encontrados'][$k]['preco_unitario'] = $this->getPrice($p['codigo'], $p['id_fornecedor'], $id_estado, $vd['desconto_percentual']);

                    # Obtem o estoque
                    // $produtos[$kk]['encontrados'][$k]['estoque'] = $this->getStock($p['codigo'], $p['id_fornecedor']);

                    # Obtem todos os lotes do produto
                    $this->db->where_in('id_fornecedor', [12, 112, 115, 123, 125, 126, 127]);
                    $this->db->where('codigo', $p['codigo']);
                    $estq = $this->db->get('produtos_lote')->result_array();

                    $estoqueFornecedores = [];

                    foreach ($estq as $lote) {
                        if (isset($p['quantidade_unidade']) && $p['quantidade_unidade'] > 0) {

                            $estoqueFornecedores[$lote['id_fornecedor']][$lote['lote']] = intval($lote['estoque']) * intval($p['quantidade_unidade']);
                        } else {

                            $estoqueFornecedores[$lote['id_fornecedor']][$lote['lote']] = intval($lote['estoque']);
                        }
                    }

                    $estoqueTotalForn = [];


                    foreach ($estoqueFornecedores as $l => $estForn) {
                        foreach ($estForn as $j => $est) {

                            if (isset($estoqueTotalForn[$l])) {
                                $estoqueTotalForn[$l] = $estoqueTotalForn[$l] + $est;
                            } else {
                                $estoqueTotalForn[$l] = $est;
                            }


                        }
                    }

                    foreach ($fornecedores_oncoprod as $fornecedor) {

                        $esst = isset($estoqueTotalForn[$fornecedor['id']]) ? $estoqueTotalForn[$fornecedor['id']] : 0;

                        if ($fornecedor['id'] == $this->session->id_fornecedor) {

                            $produtos[$kk]['encontrados'][$k]['estoque'] = $esst;
                            $p['estoque'] = $esst;

                            # Adiciona o estoque na variavel de estoque geral do produto
                            $totalEstoque += $esst;

                        }

                        $estoque[] = ['name' => $fornecedor['nome_fantasia'], 'value' => $esst, 'label' => $fornecedor['id']];

                    }


                    /* # Lista de estoques da ONCOPROD
                     foreach ($fornecedores_oncoprod as $f => $fornecedor) {

                         if (isset($p['quantidade_unidade']) && $p['quantidade_unidade'] > 0) {

                             $this->db->select("( SUM(estoque) * {$p['quantidade_unidade']} )  AS estoque");
                         } else {

                             $this->db->select(" (SUM(estoque)) AS estoque");
                         }

                         $this->db->where('id_fornecedor', $fornecedor['id']);
                         $this->db->where('codigo', $p['codigo']);
                         $estqe = $this->db->get('produtos_lote')->row_array()['estoque'];

                         if (is_null($estqe)) {
                             $estqe = 0;
                         }


                         if ($fornecedor['id'] == $this->session->id_fornecedor) {

                             $produtos[$kk]['encontrados'][$k]['estoque'] = $estqe;
                             $p['estoque'] = $estqe;

                             # Adiciona o estoque na variavel de estoque geral do produto
                             $totalEstoque += $estqe;

                         }



                         $estoque[] = ['name' => $fornecedor['nome_fantasia'], 'value' => $estqe, 'label' => $fornecedor['id']];
                     }*/

                    $produtos[$kk]['encontrados'][$k]['estoques'] = $estoque;


                    # Obtem a marca
                    $produtos[$kk]['encontrados'][$k]['marca'] = $this->getMarca($p['id_marca']);

                    # Verifica se existe restrição para o produto
                    $produtos[$kk]['encontrados'][$k]['restricao'] = $this->getRestricao($p['codigo'], $this->session->id_fornecedor, $id_cliente, $id_estado);


                    # verifica se foi respondido
                    $produto_enviado = $this->db->select("*")
                        ->group_start()
                        ->where("id_fornecedor", $p['id_fornecedor'])
                        ->or_where("id_fornecedor_logado", $this->session->id_fornecedor)
                        ->group_end()
                        ->where("cd_cotacao", $p['cd_cotacao'])
                        ->where("id_pfv", $p['codigo'])
                        ->where("id_produto", $produto['cotado']['id_produto_sintese'])
                        ->where("cd_produto_comprador", $produto['cotado']['cd_produto_comprador'])
                        ->group_start()
                        ->where("nivel", 1)
                        ->or_where("nivel", 2)
                        ->group_end()
                        ->get('cotacoes_produtos')
                        ->row_array();

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

                        # Adiciona a coluna ocultar
                        $produtos[$kk]['encontrados'][$k]['ocultar'] = $produto_enviado['ocultar'];

                        # Somente itens enviados e rascunhos podem sobrescrever as informações
                        if ($produto_enviado['ocultar'] != 1) {

                            # Altera o preço do produto para o preço enviado
                            $produtos[$kk]['encontrados'][$k]['preco_unitario'] = $produto_enviado['preco_marca'];

                            $produtos[$kk]['encontrados'][$k]['preco_caixa'] = $produto_enviado['preco_marca'] * $produto_enviado['qtd_embalagem'];

                            # Exibe a observação enviada
                            if (!empty($produto_enviado['obs_produto'])) {

                                # Separa a observação em dois para identificar o texto da observação sem o nome do produto
                                $obs = explode(' - ', $produto_enviado['obs_produto']);

                                # Verifica se existe observação
                                if (isset($obs[1])) {

                                    $produtos[$kk]['encontrados'][$k]['obs'] = $obs[1];
                                } else {

                                    # Se não existir, manda vazio
                                    $produtos[$kk]['encontrados'][$k]['obs'] = '';
                                }
                            }
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

                    # Adiciona o ultimo preço do produto no comprador ofertado
                    $produtos[$kk]['encontrados'][$k]['ultima_oferta'] = $this->getUltimaOfertaProdutoComprador($p['codigo'], $p['id_fornecedor'], $id_cliente);

                    # Determina qual classe terá a marca na exibição
                    $class = '';

                    # Se não tiver estoque
                    if ($produtos[$kk]['encontrados'][$k]['estoque'] < 1) {

                        $class = 'table-danger';


                        #
                        /*$produtosSemEstoque['cd_cotacao'] = $p['cd_cotacao'];
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

        # Se existir produtos com estoque 0, registra.
        if (!empty($sem_estoque)) {

            $this->db->insert_batch('produtos_sem_estoque', $sem_estoque);
        }

        /*  # Notifica produtos sem estoque
          if (!empty($produtosSemEstoque)) {

              $this->stockNotification($produtosSemEstoque['cd_cotacao'], $produtosSemEstoque['produtos']);
          }*/

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

                $azuis[] = $produto;
            } # Adiciona os produtos que ~tem depara mais não foram enviados
            elseif (isset($produto['encontrados']) && !empty($produto['encontrados']) && !in_array(1, array_column($produto['encontrados'], 'enviado'))) {
                $verdes[] = $produto;
            } # Adiciona os produtos sem depara
            elseif (empty($produto['encontrados'])) {
                $vermelhos[] = $produto;
            } # Adiciona o resto dos produtos
            else {
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
     * Cria o XML da cotação para cada fornecedor do array
     *
     * @param Array dos produtos organizados em fornecedor e produto sintese
     * @param - Array com informções de regras de venda, codigo da cotação, obs da cotação...
     * @return  Array
     */
    public function createXml($produtos_ordenados, $info)
    {
        $files = [];

        # Percorre os fornecedores selecionados para cotar
        foreach ($produtos_ordenados as $id_fornecedor => $produtos_sintese) {

            $fornecedor = $this->fornecedores->findById($id_fornecedor);

            $dom = new DOMDocument("1.0", "ISO-8859-1");
            $dom->formatOutput = true;
            $root = $dom->createElement("Cotacao");

            #informações do cabeçalho
            $root->appendChild($dom->createElement("Tp_Movimento", 'I'));
            $root->appendChild($dom->createElement("Dt_Gravacao", date("d/m/Y H:i:s")));
            $root->appendChild($dom->createElement("Cd_Fornecedor", preg_replace("/\D+/", "", $fornecedor['cnpj'])));
            $root->appendChild($dom->createElement("Cd_Cotacao", $info['cd_cotacao']));
            $root->appendChild($dom->createElement("Cd_Condicao_Pagamento", $info['id_forma_pagamento']));
            $root->appendChild($dom->createElement("Nm_Usuario", 'PHARMAINT321'));
            $root->appendChild($dom->createElement("Ds_Observacao_Fornecedor", utf8_encode($info['obs'])));
            $root->appendChild($dom->createElement("Qt_Prz_Minimo_Entrega", $info['prazo_entrega']));
            $root->appendChild($dom->createElement("Sn_Permite_Alterar_Oferta", "Nao"));
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

                    $quantidade_embalagem = ($p['quantidade_unidade'] == null || $p['quantidade_unidade'] == '') ? 1 : $p['quantidade_unidade'];

                    $id_marca = $dom->createElement("Id_Marca", $p['id_marca']);
                    $ds_marca = $dom->createElement("Ds_Marca", utf8_encode($p['marca']));
                    $qt_embalagem = $dom->createElement("Qt_Embalagem", $quantidade_embalagem);
                    $pr_unidade = $dom->createElement("Vl_Preco_Produto", number_format($p['preco_unidade'], 4, ',', '.'));
                    $cd_produto = $dom->createElement("Cd_ProdutoERP", $p['codigo']);

                    $marca_oferta->appendChild($id_marca);
                    $marca_oferta->appendChild($ds_marca);
                    $marca_oferta->appendChild($qt_embalagem);
                    $marca_oferta->appendChild($pr_unidade);

                    $ds_obs_fornecedor = $dom->createElement("Ds_Obs_Oferta_Fornecedor", $p['obs']);
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

            $filename = "public/cotacoes_enviadas/{$id_fornecedor}_{$info['cd_cotacao']}.xml";

            if (file_exists($filename)) {
                unlink($filename);
            }

            $fl = fopen($filename, "w+");

            fwrite($fl, $simpleXML->asXML());

            fclose($fl);

            chmod($filename, 0777);

            $files[] = ['id_fornecedor' => $id_fornecedor, 'xml' => $dom_xml];
        }

        return $files;
    }

    /**
     * Cria o espelho da cotação
     *
     * @param - Array dos produtos encontrados ordenados por cd_produto_comprador
     * @param - Array com informções de regras de venda, cliente...
     * @return  array
     */
    public function createMirror($produtos_ordenados, $info)
    {
        # Obtem a cotação
        $this->DB_COTACAO->where('cd_cotacao', $info['cd_cotacao']);
        $this->DB_COTACAO->where('id_fornecedor', $this->session->id_fornecedor);
        $cotacao = $this->DB_COTACAO->get('cotacoes')->row_array();


        $output = [];

        $data_inicio = date('d/m/Y H:i:s', strtotime($cotacao['dt_inicio_cotacao']));
        $data_fim = date('d/m/Y H:i:s', strtotime($cotacao['dt_fim_cotacao']));
        $data_validade = date('d/m/Y', strtotime($cotacao['dt_validade_preco']));
        $data_envio = date('d/m/Y H:i', strtotime("-1 hour"));

        $condicao_pagamento = $this->forma_pagamento->findById($info['id_forma_pagamento'])['descricao'];

        $mirror = "";

        $pdfCompleto = new \Mpdf\Mpdf();

        foreach ($produtos_ordenados as $kk => $produtos) {

            $pdfCompleto->AddPage();

            $i = 1;
            $rows = "";

            $fornecedor = $this->fornecedores->findById($kk)['nome_fantasia'];
            $valor_minimo = number_format($produtos['valor_minimo'], 2, ",", ".");

            foreach ($produtos['produtos'] as $produto) {

                # Obtem um array com os excluidos para contabilizar quantas marcas foram excluidas
                $colunaExcluidos = array_column($produto['marcas'], 'excluido');

                # Contabiliza as marcas de cada produto
                $count_produtos = count($produto['marcas']);

                # Subtrai as quantidades para saber se existe alguma marca para exibir no espelho
                $temRegistroParaEnvio = $count_produtos - count($colunaExcluidos);

                if ($temRegistroParaEnvio > 0) {

                    $row = "
                        <table style='width:100%; border: 1px solid #dddddd; border-collapse: collapse'>
                        <tr>
                            <td style='border: 1px solid #dddddd; background-color: #D3D3D3' colspan='2'>{$i}. {$produto['ds_produto_comprador']}</td>
                            <td style='border: 1px solid #dddddd; background-color: #D3D3D3' colspan='2'><strong>Qtde Solicitada:</strong> {$produto['qt_produto_total']}</td>
                            <td style='border: 1px solid #dddddd; background-color: #D3D3D3' colspan='2'><strong>Und. Compra:</strong> {$produto['ds_unidade_compra']}</td>
                        </tr>
                        <tr>
                            <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Código Kraft</th>
                            <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Filial</th>
                            <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Marca</th>
                            <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Embalagem</th>
                            <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Preço</th>
                            <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Descrição</th>
                        </tr>
                    ";

                    foreach ($produto['marcas'] as $item) {

                        # Só insere os itens não excluidos
                        if (!isset($item['excluido'])) {


                            $marca = $this->marcas->get_row($item['id_marca'])['marca'];
                            $preco = number_format($item['preco_unidade'], 4, ",", ".");

                            $row .= "
                                <tr>
                                    <td style='border: 1px solid #dddddd; padding-right: 20px'>{$item['codigo']}</td>
                                    <td style='border: 1px solid #dddddd; padding-right: 20px'>{$fornecedor}</td>
                                    <td style='border: 1px solid #dddddd; padding-right: 20px'>{$marca}</td>
                                    <td style='border: 1px solid #dddddd; padding-right: 20px'>{$item['quantidade_unidade']}</td>
                                    <td style='border: 1px solid #dddddd; padding-right: 20px'>{$preco}</td>
                                    <td style='border: 1px solid #dddddd; padding-right: 20px'>{$item['descricao']}</td>
                                </tr>
                                <tr>
                                    <td colspan='6' style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Observações: {$item['obs']}</td>
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
                        <label style='margin-right: 20px; font-size: 12px'><strong>Numero da Cotação:</strong> {$cotacao['cd_cotacao']}</label><br>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Empresa:</strong> {$info['cliente']['cnpj']} - {$info['cliente']['razao_social']}</label><br>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Comprador:</strong> {$cotacao['nm_usuario']}</label><br>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Situação:</strong> Em Andamento </label><br>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Data e Hora de Início:</strong> {$data_inicio} </label><br>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Data e Hora de Término:</strong> {$data_fim} </label><br>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Data de Validade:</strong> {$data_validade} </label><br>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Data de Envio:</strong> {$data_envio} </label>
                    </p>
                    <hr>
                    <strong>Condições de Pagamento: </strong> {$condicao_pagamento} <br>
                    <strong>Valor mínimo do pedido por entrega (R$):</strong> {$valor_minimo} <br>
                    <strong>Prazo de entrega (dias):</strong> {$info['prazo_entrega']} <br>
                    <strong>Observações:</strong> {$info['obs']} <br>
                    <hr>

                    {$rows}

                </small>
            ";

            $mirror .= $rows;
            $mirror .= "<br><br>";

            # Armazena o arquivo
            $filename = "public/exports/{$kk}_cotacao_{$info['cd_cotacao']}.pdf";

            if (file_exists($filename)) {
                unlink($filename);
            }

            $pdfCompleto->WriteHTML($data);

            $mpdf = new \Mpdf\Mpdf();
            $mpdf->WriteHTML($data);
            $mpdf->Output($filename, 'F');

            $output[$kk] = $filename;
        }

        # Cria um arquivo PDF com os espelhos separados por pagina
        $file = "public/exports/cotacao_{$info['cd_cotacao']}_{$this->session->id_fornecedor}.pdf";
        $output['filename'] = $file;

        if (file_exists($file)) {
            unlink($file);
        }

        $pdfCompleto->Output($file, 'F');


        $output['html'] = "
                <small>
                    <p>
                       <label style='margin-right: 20px; font-size: 12px'><strong>Numero da Cotação:</strong> {$cotacao['cd_cotacao']}</label><br>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Empresa:</strong> {$info['cliente']['cnpj']} - {$info['cliente']['razao_social']}</label><br>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Comprador:</strong> {$cotacao['nm_usuario']}</label><br>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Situação:</strong> Em Andamento </label><br>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Data e Hora de Início:</strong> {$data_inicio} </label><br>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Data e Hora de Término:</strong> {$data_fim} </label><br>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Data de Validade:</strong> {$data_validade} </label><br>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Data de Envio:</strong> {$data_envio} </label>
                    </p>
                    <hr>
                    <strong>Condições de Pagamento: </strong> {$condicao_pagamento} <br>
                    <strong>Valor mínimo do pedido por entrega (R$):</strong> {$valor_minimo} <br>
                    <strong>Prazo de entrega (dias):</strong> {$info['prazo_entrega']} <br>
                    <strong>Observações:</strong> {$info['obs']} <br>
                    <hr>

                    {$mirror}

                </small>
        ";

        return $output;
    }

    /**
     * Gerar PDF da cotação
     *
     * @param int ID do fornecedor
     * @param string $cd_cotacao
     * @param int timestamp de criação do arquivo
     * @return  download/ajax
     */
    public function exportar_pdf($cd_cotacao, $id_fornecedor)
    {

        $filename = "public/exports/cotacao_{$cd_cotacao}_{$id_fornecedor}.pdf";

        force_download($filename, null);
    }

    /**
     * Cria o cabeçalho da cotação
     *
     * @param - String codigo da cotação
     * @param - INT ID do fornecedor
     * @param - INT ID do comprador
     * @param - INT ID da forma de pagamento
     * @param - decimal valor minimo
     * @param - INT prazo de entrega
     * @param - String observação da cotação
     * @return  bool
     */
    public function createHeaderCot($cd_cotacao, $id_fornecedor, $id_cliente, $id_forma_pagamento, $prazo_entrega, $obs)
    {

        $this->db->trans_begin();

        # Deleta os registros antigos da cotaç~do do fornecedor
        $this->db->where('cd_cotacao', $cd_cotacao);
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->delete('cotacoes');

        # Cria o cabeçalho da cotação com o xml e o email
        $this->db->where('cd_cotacao', $cd_cotacao);
        $this->db->where('id_fornecedor', $id_fornecedor);

        $this->db->insert('cotacoes', [
            'cd_cotacao' => $cd_cotacao,
            'id_fornecedor' => $id_fornecedor,
            'id_cliente' => $id_cliente,
            'valor_minimo' => null,
            'prazo_entrega' => $prazo_entrega,
            'id_forma_pagamento' => $id_forma_pagamento,
            'nivel' => 1,
            'obs' => $obs
        ]);

        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();
            return false;
        } else {

            $this->db->trans_commit();
            return true;
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

            $this->db->select("id, cd_cotacao, preco_marca AS preco, data_cotacao");
            $this->db->where('id_fornecedor', $this->session->id_fornecedor);
            $this->db->where('id_produto', $post['id_produto']);
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

            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    /**
     * Faz o depara dos produtos da sintese de uma cotação
     *
     * @param - String codigo da cotação
     * @param - Int ID do fornecedor
     * @return  array
     */
    public function queryDepara($cd_cotacao, $id_Fornecedor, $estado)
    {

        $query = "SELECT 
            cot.cd_cotacao,
            cot_prods.ds_produto_comprador,
            forn_sint.cd_produto codigo,
            pc.id,
            CONCAT(pc.nome_comercial, ' - ', (case when pc.apresentacao is null then pc.descricao else pc.apresentacao end)) produto_descricao,
            pc.marca,
            IF(pc.quantidade_unidade is null, 1, pc.quantidade_unidade) quantidade_unidade,
            pc.id_marca,
            marc_sint.id_produto,
            cot_prods.id_fornecedor,
           10 preco_unitario
            FROM cotacoes_sintese.cotacoes cot
            JOIN cotacoes_sintese.cotacoes_produtos cot_prods
                on cot_prods.id_fornecedor = cot.id_fornecedor
                and cot_prods.cd_cotacao = cot.cd_cotacao
            LEFT join pharmanexo.produtos_marca_sintese marc_sint
                on marc_sint.id_produto = cot_prods.id_produto_sintese
            LEFT JOIN pharmanexo.produtos_fornecedores_sintese forn_sint
                on forn_sint.id_fornecedor = cot.id_fornecedor
                and forn_sint.id_sintese = marc_sint.id_sintese
            JOIN pharmanexo.produtos_catalogo pc
                on pc.codigo = forn_sint.cd_produto
                and pc.id_fornecedor = forn_sint.id_fornecedor
                and pc.ativo = 1
                and pc.bloqueado = 0

            WHERE cot.cd_cotacao = '{$cd_cotacao}'
                and cot_prods.id_fornecedor  = {$id_Fornecedor}  
            GROUP BY cot.cd_cotacao,
                cot_prods.id_produto_sintese,
                forn_sint.cd_produto,
                marc_sint.id_produto,
                CONCAT(pc.nome_comercial, ' - ', (case when pc.apresentacao is null then pc.descricao else pc.apresentacao end)),
                pc.marca,
                pc.id_marca,
                pc.quantidade_unidade

            having forn_sint.cd_produto is not null
            order by cot_prods.ds_produto_comprador ASC, cot_prods.id_fornecedor
        ";

        $data = $this->db->query($query)->result_array();


        foreach ($data as $k => $item) {

            $query_preco = "
                       SELECT pp.preco_unitario
                        FROM pharmanexo.produtos_preco_oncoprod pp
                        where (pp.id_estado {$estado} or pp.id_estado is null)
                          and pp.id_fornecedor = {$id_Fornecedor}
                          and pp.codigo = {$item['codigo']}
                          and pp.data_criacao = (CASE
                                                     WHEN ISNULL(pp.id_estado) then (select max(pp2.data_criacao)
                                                                                     from pharmanexo.produtos_preco_oncoprod pp2
                                                                                     where pp2.id_fornecedor = {$id_Fornecedor}
                                                                                       and pp2.codigo  = {$item['codigo']}
                                                                                       and pp2.id_estado is null)
                                                     ELSE (select max(pp2.data_criacao)
                                                           from pharmanexo.produtos_preco_oncoprod pp2
                                                           where pp2.id_fornecedor = {$id_Fornecedor}
                                                             and pp2.codigo = {$item['codigo']}
                                                             and pp2.id_estado = pp.id_estado) END)                                          
                                                                                         
                                                                                         
                                                                 ";
            $consult_precos = $this->db->query($query_preco)->row_array();


            if (!empty($consult_precos)) {
                $data[$k]['preco_unitario'] = $consult_precos['preco_unitario'];
            } else {
                $data[$k]['preco_unitario'] = 0.00;
            }
        }


        return $data;

    }

    /**
     * Obtem os fornecedores ONCOPROD que possuem registro da cotação para utilizar no select
     *
     * @param String codigo da cotação
     * @return array
     */
    public function select_fornecedores($cd_cotacao)
    {
        # Obtem os fornecedores
        $this->DB_COTACAO->select("cot.id_fornecedor, f.nome_fantasia");
        $this->DB_COTACAO->from("cotacoes cot");
        $this->DB_COTACAO->join("pharmanexo.fornecedores f", "f.id = cot.id_fornecedor");
        $this->DB_COTACAO->where('cd_cotacao', $cd_cotacao);
        $this->DB_COTACAO->where("id_fornecedor in (" . ONCOPROD . ")");
        $lista_fornecedores = $this->DB_COTACAO->get()->result_array();

        $list = [];

        foreach ($lista_fornecedores as $f) {

            $list[] = [
                'id' => $f['id_fornecedor'],
                'fornecedor' => $f['nome_fantasia']
            ];

        }

        return $list;
    }

    /**
     * Gera html com ifnromações da cotação
     *
     * @param string codigo da cotação
     * @param int - tipo de relatório(produtos ou cotação)
     * @return html
     */
    public function info_cotacao($cd_cotacao, $type = null)
    {
        $this->DB_COTACAO->where('cd_cotacao', $cd_cotacao);
        $this->DB_COTACAO->where('id_fornecedor', $this->session->id_fornecedor);
        $cotacao = $this->DB_COTACAO->get('cotacoes')->row_array();

        $this->DB_COTACAO->select("id_produto_sintese");
        $this->DB_COTACAO->select("cd_produto_comprador");
        $this->DB_COTACAO->select("ds_produto_comprador");
        $this->DB_COTACAO->select("ds_unidade_compra");
        $this->DB_COTACAO->select("ds_complementar");
        $this->DB_COTACAO->select("SUM(qt_produto_total) AS qt_produto_total");
        $this->DB_COTACAO->where('cd_cotacao', $cd_cotacao);
        $this->DB_COTACAO->where('id_fornecedor', $this->session->id_fornecedor);
        $this->DB_COTACAO->group_by('id_produto_sintese, cd_produto_comprador, ds_produto_comprador, ds_unidade_compra, ds_complementar');
        $cotacoes_produtos = $this->DB_COTACAO->get('cotacoes_produtos')->result_array();

        $cnpj = mask($cotacao['cd_comprador'], '##.###.###/####-##');

        $cliente = $this->compradores->findById($cotacao['id_cliente']);

        $estado = $this->estados->find("*", "uf = '{$cliente['estado']}'", true);

        $dataini = date("d/m/Y H:i:s", strtotime($cotacao['dt_inicio_cotacao']));
        $datafim = date("d/m/Y H:i:s", strtotime($cotacao['dt_fim_cotacao']));
        $data_validade = date("d/m/Y H:i:s", strtotime($cotacao['dt_validade_preco']));

        $id_forma_pagamento = $this->getFormaPagamento('', $cliente['id'], $this->session->id_fornecedor, $estado['id']);

        $condicao_pagamento = $this->forma_pagamento->find('*', "id = {$id_forma_pagamento}", TRUE)['descricao'];

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
                                    <b>Data de Validade:</b> {$data_validade} <br>
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
                                    <b>Usuário:</b> {$cotacao['nm_usuario']} <br>
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

        $html = $this->load->view("{$this->views}model_details", $dataView, true);

        $this->output->set_content_type('text/html')->set_output($html);
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
            $cotacao = $this->DB_COTACAO->get('cotacoes')->row_array();

            $valor = ($cotacao['oculto'] == 1) ? 0 : 1;

            $this->DB_COTACAO->where('cd_cotacao', $cd_cotacao);
            $this->DB_COTACAO->where('id_fornecedor', $id_fornecedor);
            $update = $this->DB_COTACAO->update('cotacoes', ['oculto' => $valor]);

            if ($update) {

                $warning = ['type' => 'success', 'message' => notify_update];
            } else {

                $warning = $this->notify->errorMessage();
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
    }

    /**
     * Desoculta a cotação
     *
     * @return  json
     */
    public function desocultar()
    {
        if ($this->input->is_ajax_request()) {

            $post = $this->input->post();

            $this->DB_COTACAO->where("cd_cotacao", $post['cd_cotacao']);
            $this->DB_COTACAO->where("id_fornecedor", $this->session->id_fornecedor);
            $upd = $this->DB_COTACAO->update('cotacoes', ['oculto' => 0]);

            if ($upd) {

                $warning = ['type' => 'success', 'message' => notify_update];
            } else {

                $warning = $this->notify->errorMessage();
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
    }

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

        return (isset($oc['Vl_Preco_Produto']) && !empty($oc['Vl_Preco_Produto'])) ? $oc['Vl_Preco_Produto'] : null;
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
            $marca = $this->marcas->get_row($produto['id_marca'])['marca'];

            # Restricao
            $this->db->where('id_fornecedor', $post['id_fornecedor']);
            $this->db->where('id_produto', $post['codigo']);
            $this->db->group_start();
            $this->db->where('id_cliente', $post['id_cliente']);
            $this->db->or_where('id_estado', $post['id_estado']);
            $this->db->group_end();

            $restricao = $this->db->get('restricoes_produtos_clientes')->row_array();

            # Ultima oferta
            $ultima_oferta = $this->getUltimaOfertaProdutoComprador($post['codigo'], $post['id_fornecedor'], $post['id_cliente']);

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
     * Verifica se existe o produto sintese no BD para realizar o depara
     *
     * @return  json
     */
    public function findProduct($id_produto)
    {
        $produto = $this->pms->find('*', "id_produto = {$id_produto}", true);

        if (isset($produto) && !empty($produto)) {

            $output = ['type' => 'success', 'message' => 'Produto encontrado!', 'link' => "{$this->route}index_depara/{$id_produto}"];
        } else {

            $client = new SoapClient("https://ws-sintese.bionexo.com/IntegrationService.asmx?WSDL");
            $location = 'https://ws-sintese.bionexo.com/IntegrationService.asmx';

            $forn = $this->fornecedores->findById(123);

            $function = 'ObterProdutos';
            $arguments = array('ObterProdutos' => array(
                'cnpj' => preg_replace("/\D+/", "", $forn['cnpj']),
                'chave' => $forn['chave_sintese'],
                'codigoProdutoSintese' => $id_produto,
            ));

            /* var_dump([
                 'cnpj' => preg_replace("/\D+/", "", $forn['cnpj']),
                 'chave' => $forn['chave_sintese'],
                 'codigoProdutoSintese' => $id_produto,
             ]);
             exit();*/


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

                        $output = ['type' => 'success', 'message' => 'Produto encontrado!', 'link' => "{$this->route}index_depara/{$id_produto}"];

                    }

                }
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    /**
     * Exbie a tela de produtos para realizar de para
     *
     * @param int - ID produto sintese
     * @return  view
     */
    public function index_depara($id_produto)
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

        $this->load->view("{$this->views}find_product", $data);
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

            $data = [];

            foreach ($this->oncoprod as $fornecedor) {

                $this->db->where('id_usuario', $this->session->id_usuario);
                $this->db->where('id_fornecedor', $fornecedor);
                $this->db->where('cd_produto', $post['cd_produto']);
                $this->db->where('id_sintese', $post['id_sintese']);
                $pfs = $this->db->get('produtos_fornecedores_sintese')->row_array();

                if (empty($pfs)) {

                    $data[] = [
                        'id_usuario' => $this->session->id_usuario,
                        'id_fornecedor' => $fornecedor,
                        'cd_produto' => $post['cd_produto'],
                        'id_sintese' => $post['id_sintese']
                    ];
                }
            }

            if (!empty($data)) {

                $v = $this->db->insert_batch('produtos_fornecedores_sintese', $data);

                if ($v != false) {

                    $warning = ['type' => 'success', 'message' => notify_update];
                } else {

                    $warning = $this->notify->errorMessage();
                }
            } else {

                $warning = ['type' => 'warning', 'message' => 'Combinação de produtos já existente'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
    }

    /**
     * Função que registra a revisão da cotação
     *
     * @return  json
     */
    public function review($cd_cotacao)
    {
        if ($this->input->is_ajax_request()) {

            $post = $this->input->post();

            if (isset($post['status'])) {
                $this->DB_COTACAO->where('id_fornecedor', $this->session->id_fornecedor);
                $this->DB_COTACAO->where('cd_cotacao', $cd_cotacao);
                if ($this->DB_COTACAO->update('cotacoes', ['revisao' => $post['status']])) {

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

        $this->output->set_content_type('application/json')->set_output(json_encode($this->select2->exec(
            array_merge($this->input->get(), $data),
            "formas_pagamento",
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'descricao', 'dt' => 'descricao'],
            ]
        )));
    }

    /**
     * Verifica as regras de venda do fornecedor
     *
     * @param - Array POST da requisição
     * @param - INT ID do comprador
     * @param - INT ID do fornecedor
     * @param - INT ID do estado do comprador
     * @return  array
     */
    public function getSaleRules($post, $id_cliente, $id_fornecedor, $id_estado)
    {
        # Prazo de Entrega
        $prazo_entrega = $this->getPrazoEntrega($post, $id_cliente, $id_fornecedor, $id_estado);

        # Forma de Pagamento
        $id_forma_pagamento = $this->getFormaPagamento($post, $id_cliente, $id_fornecedor, $id_estado);

        # Validações

        if (!isset($prazo_entrega)) {

            return ["type" => "warning", "message" => "É necessário configurar prazo de entrega, em regras de vendas -> prazo de entrega"];
        }

        if (!isset($id_forma_pagamento)) {

            return ["type" => "warning", "message" => "É necessário configurar uma forma de pagamento válida, em regras de vendas -> formas de pagamento"];
        }

        $data = [
            'prazo_entrega' => $prazo_entrega,
            'id_forma_pagamento' => $id_forma_pagamento
        ];

        return ['type' => 'success', 'message' => $data];
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
     * Obtem o ID da forma de pagamento
     *
     * @param - Array POST da requisição
     * @param - INT ID do comprador
     * @param - INT ID do fornecedor
     * @param - INT ID do estado do comprador
     * @return  int/null
     */
    public function getFormaPagamento($post, $id_cliente, $id_fornecedor, $id_estado)
    {

        # Verifica se o usuario informou a forma de pagamento
        if (isset($post['forma_pagto']) && !empty($post['forma_pagto'])) {

            $forma_pagamento = $post['forma_pagto'];
        } else {

            # Se não informou, obtem pelo fornecedor
            $this->db->select("*");
            $this->db->where("id_fornecedor", $id_fornecedor);
            $this->db->group_start();
            $this->db->where('id_cliente', $id_cliente);
            $this->db->or_where('id_estado', $id_estado);
            $this->db->group_end();

            $forma_pagamento = $this->db->get('formas_pagamento_fornecedores')->row_array()['id_forma_pagamento'];
        }

        return (isset($forma_pagamento) && !empty($forma_pagamento)) ? $forma_pagamento : null;
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
     * Obtem o preço de um produto
     *
     * @param - INT codigo do produto
     * @param - INT ID do fornecedor
     * @param - INT ID do estado
     * @param - String desconto Venda diferenciada
     * @return  number
     */
    public function getPrice($codigo, $id_fornecedor, $id_estado, $desconto_vd)
    {

        $preco = $this->price->getPrice([
            'id_fornecedor' => $id_fornecedor,
            'codigo' => $codigo,
            'id_estado' => $id_estado
        ]);

        # Se existir venda diferenciada, aplica o desconto
        if (isset($desconto_vd) and !empty($desconto_vd) && $preco != "0.0000") {

            $preco = $preco - ($preco * (floatval($desconto_vd) / 100));
        }


        return $preco;
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

            $marca = $this->marcas->get_row($id_marca)['marca'];
        } else {

            $marca = "Sem De -> Para de Marca";
        }

        return $marca;
    }

    /** save preço fixo */
    public function savePrice()
    {
        $post = $this->input->post();

        if (isset($post['cd_cotacao']) && isset($post['price']) && isset($post['codigo'])) {
            $this->DB_COTACAO->where('cd_cotacao', $post['cd_cotacao']);
            $this->DB_COTACAO->where('id_fornecedor', $this->session->id_fornecedor);
            $cotacao = $this->DB_COTACAO->get('cotacoes')->row_array();

            $data = [
                'id_cliente' => $cotacao['id_cliente'],
                'preco_base' => dbNumberFormat($post['price']),
                'id_fornecedor' => (isset($post['id_fornecedor']) && !empty($post['id_fornecedor'])) ? $post['id_fornecedor'] : $this->session->id_fornecedor,
                'codigo' => $post['codigo']
            ];

            $get = $this->preco_mix->get_item($data);


            if (empty($get)) {
                $out = $this->preco_mix->insert($data);
            } else {
                if (isset($get['preco_mix']) && $get['preco_mix'] > 0) {
                    $data['preco_mix'] = dbNumberFormat($post['price']);
                };

                $out = $this->preco_mix->update($data);
            }

            if ($out) {
                $warning = [
                    'type' => 'success',
                    'message' => 'Preço salvo com sucesso para o cliente'
                ];
            } else {
                $warning = [
                    'type' => 'warning',
                    'message' => 'Houve um erro ao salvar o preço'
                ];
            }

        } else {
            $warning = [
                'type' => 'warning',
                'message' => 'Alguns campos não foram preenchidos, consulte o suporte'
            ];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($warning));

    }

    public function getValidades($codigo)
    {
        $lotes = $this->db
            ->select('pl.*, f.nome_fantasia')
            ->join('fornecedores f', 'f.id = pl.id_fornecedor')
            ->where_in('pl.id_fornecedor', [12, 111, 112, 115, 120, 126, 125, 127])
            ->where('pl.codigo', $codigo)
            ->get('produtos_lote pl')
            ->result_array();

        foreach ($lotes as $k => $lote) {
            $lotes[$k]['validade'] = date('d/m/Y', strtotime($lote['validade']));
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($lotes));
    }
}
