<?php

class RequestCotacaoHospGOs extends CI_Controller
{

    /**
     * @author : Eric Lempê
     * Data: 25/09/2020
     */

    private $DB1, $DB2, $oncoprod;

    public function __construct()
    {
        parent::__construct();

        $this->urlCliente = $this->config->item('db_config')['url_client'];

        $this->DB1 = $this->load->database('default', true);
        $this->DB2 = $this->load->database('sintese', true);

        ini_set('display_errors', 0);
        ini_set('display_startup_erros', 10);
        error_reporting(0);
        ini_set('default_socket_timeout', 1800);
    }

    public function connectSintese($fornecedor)
    {
        try {

            foreach ($this->urlCliente as $url) {

                $client = new SoapClient("{$url}?WSDL");

                $function = 'ObterCotacoes';
                $arguments = array('ObterCotacoes' => array('cnpj' => preg_replace("/\D+/", "", $fornecedor['cnpj']),));

                libxml_disable_entity_loader(false);
                $options = array('location' => $url);
                $result = $client->__soapCall($function, $arguments, $options);

                $resposta = $result->ObterCotacoesResult;


                if (strpos($resposta, 'source')) {

                    $data = date("d/m/Y H:i:s");

                    $errorMsg = [
                        "to" => "marlon.boecker@pharmanexo.com.br",
                        "greeting" => "",
                        "subject" => "Erro URL Client cotações Sintese",
                        "message" => "<b>Fornecedor:</b> {$fornecedor['razao_social']} <br>
						URL: {$url}<br>
                        <b>Data de Envio:</b> {$data} <br>
                        "
                    ];

                    $this->notify->send($errorMsg);

                    continue;
                } else {

                    return $resposta;
                }
            }

            return false;
        } catch (Exception $ex) {


            $log = ["mensagem" => $ex, "id_fornecedor" => $fornecedor['id'], "cnpj_fornecedor" => $fornecedor['cnpj']];

            $this->DB1->insert('log_cotacoes_sintese', $log);
        }
    }

    /**
     * Consome os dados da SINTESE e armazena cotações e seus produtos
     *
     * @return  view
     */
    public function index()
    {
        # Obtem fornecedores
        //$fornecedores = $this->DB1->where('sintese', 1)->get('fornecedores')->result_array();
        $fornecedores = $this->DB1->where('id', 5046)->where('sintese', 1)->get('fornecedores')->result_array();


        foreach ($fornecedores as $fornecedor) {

            try {

                $result = $this->connectSintese($fornecedor);
                $result = str_replace('&#x2;', '', $result);

                if ($result != false) {

                    $xml = simplexml_load_string($result);

                    libxml_use_internal_errors(true);
                    $xml = simplexml_load_string($result);

                    if ($xml == false) {
                        $errors = libxml_get_errors();

                        $log1 = [
                            "mensagem" => "Erro  no XML - " . json_encode($errors),
                            "id_fornecedor" => $fornecedor['id'],
                            "cnpj_fornecedor" => $fornecedor['cnpj'],
                        ];

                        $this->DB1->insert('log_cotacoes_sintese', $log1);
                        continue;
                    }

                    # Foreach de cotações
                    foreach ($xml as $cotacao) {

                        $cotacao2 = (array)$cotacao;

                        $cnpj = mask($cotacao2['Cd_Comprador'], '##.###.###/####-##');

                        $comprador = $this->customers->checkComprador($cnpj);

                        if (!empty($comprador)) {

                            $this->DB2->where('cd_cotacao', $cotacao2['Cd_Cotacao']);
                            $this->DB2->where('id_fornecedor', $fornecedor['id']);
                            $cotacao_existente = $this->DB2->get('cotacoes');

                            if ($cotacao_existente->num_rows() < 1) {

                                $produtos = $cotacao->Produtos_Cotacao->Produto_Cotacao;

                                // Dados da cotação
                                $dataCotacao = [
                                    'tp_movimento' => "1",
                                    'cd_cotacao' => $cotacao2['Cd_Cotacao'],
                                    'cd_comprador' => rtrim($cotacao2['Cd_Comprador']),
                                    "id_cliente" => $comprador['id'],
                                    'cd_condicao_pagamento' => $cotacao2['Cd_Condicao_Pagamento'],
                                    'dt_inicio_cotacao' => date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $cotacao2['Dt_Inicio_Cotacao']))),
                                    'dt_fim_cotacao' => date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $cotacao2['Dt_Fim_Cotacao']))),
                                    'dt_validade_preco' => date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $cotacao2['Dt_Validade_Preco']))),
                                    'ds_entrega' => $cotacao2['Ds_Entrega'],
                                    'ds_filiais' => $cotacao2['Ds_Filiais'],
                                    'ds_cotacao' => utf8_decode($cotacao2['Ds_Cotacao']),
                                    'nm_usuario' => utf8_decode($cotacao2['Nm_Usuario']),
                                    'ds_observacao' => utf8_decode($cotacao2['Ds_Observacao']),
                                    'id_fornecedor' => $fornecedor['id'],
                                    'uf_cotacao' => $comprador['estado'],
                                    'total_itens' => count($produtos)
                                ];

                                $this->DB2->insert('cotacoes', $dataCotacao);

                                foreach ($produtos as $produto) {

                                    $produto2 = (array)$produto;

                                    $dataCotacaoProdutos = [
                                        'id_produto_sintese' => $produto2['Id_Produto_Sintese'],
                                        'id_fornecedor' => $fornecedor['id'],
                                        'cd_produto_comprador' => $produto2['Cd_Produto_Comprador'],
                                        'ds_produto_comprador' => utf8_decode($produto2['Ds_Produto_Comprador']),
                                        'ds_unidade_compra' => utf8_decode($produto2['Ds_Unidade_Compra']),
                                        'ds_complementar' => utf8_decode($produto2['Ds_Complementar']),
                                        'qt_produto_total' => $produto2['Qt_Produto_Total'],
                                        'cd_cotacao' => $cotacao2['Cd_Cotacao'],
                                    ];

                                    $this->DB2->insert('cotacoes_produtos', $dataCotacaoProdutos);
                                }
                            } else {

                                $dataExistente = $cotacao_existente->row_array()['dt_fim_cotacao'];

                                if ($dataExistente != $cotacao2['Dt_Fim_Cotacao']) {

                                    $update = [
                                        'dt_fim_cotacao' => date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $cotacao2['Dt_Fim_Cotacao']))),
                                        'data_atualizacao' => date("Y-m-d H:i:s")
                                    ];

                                    $this->DB2->where('cd_cotacao', $cotacao2['Cd_Cotacao']);
                                    $this->DB2->where('id_fornecedor', $fornecedor['id']);
                                    $this->DB2->update('cotacoes', $update);
                                }
                            }
                        } else {

                            $comp = (isset($cotacao2['Cd_Comprador'])) ? $cotacao2['Cd_Comprador'] : 'sem registro';
                            $cot = (isset($cotacao2['Cd_Cotacao'])) ? $cotacao2['Cd_Cotacao'] : 'sem registro';

                            $log1 = [
                                "mensagem" => "Comprador com CNPJ {$comp} da cotação {$cot}  não foi identificado.",
                                "id_fornecedor" => $fornecedor['id'],
                                "cnpj_fornecedor" => $fornecedor['cnpj'],
                                "cnpj_comprador" => $comp
                            ];

                            $this->DB1->insert('log_cotacoes_sintese', $log1);
                        }
                    }
                } else {

                    $data = date('d/m/Y H:i:s');

                    $errorMsg = [
                        "to" => "marlon.boecker@pharmanexo.com.br",
                        "greeting" => "",
                        "subject" => "Erro URL Client cotações Sintese",
                        "message" => "<b>Fornecedor:</b> {$fornecedor['razao_social']} <br>
                        <b>Data de Envio:</b> {$data} <br>
                        "
                    ];

                    $this->notify->send($errorMsg);
                }
            } catch (Exception $ex) {

                if (isset($xml) && !$xml) {

                    $errors = libxml_get_errors();

                    if ($errors != "" && $errors != null) {
                        $message = "<br>* " . implode('<br> ', array_column($errors, 'message'));

                        libxml_clear_errors();

                        $data = date('d/m/Y H:i:s');

                        $email = [
                            "to" => "marlon.boecker@pharmanexo.com.br",
                            "greeting" => "",
                            "subject" => "Erro ao ler XML",
                            "message" => "<b>Fornecedor:</b> {$fornecedor['razao_social']} <br>
                            <b>Data de Envio:</b> {$data} <br>
                            Não foi possivel ler o arquivo XML devido os seguintes erros: {$message}"
                        ];

                        $this->notify->send($email);
                    }
                }

                if (isset($result) && !empty($result)) {

                    $log = [
                        "mensagem" => $result,
                        "id_fornecedor" => $fornecedor['id'],
                        "cnpj_fornecedor" => $fornecedor['cnpj'],
                    ];

                    $this->DB1->insert('log_cotacoes_sintese', $log);
                }
            }
        }
    }

    /**
     * Verifica se o ultimo registro de cotação tem mais de 20 minutos
     *
     * @return  bool
     */
    public function checkRecordSintese()
    {
        $this->DB2->select('data_criacao');
        $this->DB2->order_by('data_criacao DESC');
        $this->DB2->limit(1);
        $ultimo_registro = $this->DB2->get('cotacoes')->row_array()['data_criacao'];

        $datetime1 = date_create($ultimo_registro);
        #$datetime2 = date_create(date("Y-m-d H:i:s", strtotime("-1 hour")));
        $datetime2 = date_create(date("Y-m-d H:i:s", time()));

        $interval = date_diff($datetime1, $datetime2);

        # Somente notifica de segunda a sexta das 07 as 20
        if ((($interval->format("%h") > 0 && $interval->format("%i") > 30) || $interval->format("%h") > 1) && date('N') < 6 && intval(date('G')) >= 7 && intval(date('G')) < 20) {

            # , deivis.guimaraes@pharmanexo.com.br, jorge@sintese.net

            $email = [
                "to" => "marlon.boecker@pharmanexo.com.br, administracao@pharmanexo.com.br",
                "greeting" => "",
                "subject" => "Recebimento de cotações",
                "message" => "Estamos há {$interval->format('%d dias %h horas %i minutos')} sem receber novas cotações!"
            ];

            $this->notify->send($email);
        }

        return true;
    }

    public function checkData()
    {
        echo date("Y-m-d H:i:s", time());
    }
}
