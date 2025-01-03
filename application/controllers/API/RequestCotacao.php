<?php

class RequestCotacao extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->urlCliente = 'http://plataformasintese.com/IntegrationService.asmx?WSDL';
        $this->client = new SoapClient($this->urlCliente);
        $this->location = 'http://plataformasintese.com/IntegrationService.asmx';

        $this->load->model('Fornecedor', 'fornecedor');

        ini_set('display_errors',1);
        ini_set('display_startup_erros',1);
        error_reporting(E_ALL);
    }

    public function index()
    {
        $DB2 = $this->load->database('sintese', true);

        // Limpa as tabelas para uma nova operação
        $DB2->from('cotacoes')->truncate();
        $DB2->from('cotacoes_produtos')->truncate();

        
        $fornecedores = $this->fornecedor->find('*', 'sintese = 1');

        foreach ($fornecedores as $fornecedor) {


            $encontrados = [];

            $function = 'ObterCotacoes';
            $arguments = array('ObterCotacoes' => array(
                'cnpj' => preg_replace("/\D+/", "", $fornecedor['cnpj']),
            ));
            $options = array('location' => $this->location);
            $result = $this->client->__soapCall($function, $arguments, $options);

            $xml = new SimpleXMLElement($result->ObterCotacoesResult);

            // Foreach de cotações
            foreach ($xml as $cotacao) {
                
                $cotacao2 = (array)$cotacao;

                // Dados da cotação
                $dataCotacao = [
                    'tp_movimento' => "1",
                    'cd_cotacao' => $cotacao2['Cd_Cotacao'],
                    'cd_comprador' => $cotacao2['Cd_Comprador'],
                    'cd_condicao_pagamento' => $cotacao2['Cd_Condicao_Pagamento'],
                    'dt_inicio_cotacao' => $cotacao2['Dt_Inicio_Cotacao'],
                    'dt_fim_cotacao' => $cotacao2['Dt_Fim_Cotacao'],
                    'dt_validade_preco' => $cotacao2['Dt_Validade_Preco'],
                    'ds_entrega' => $cotacao2['Ds_Entrega'],
                    'ds_filiais' => $cotacao2['Ds_Filiais'],
                    'ds_cotacao' => $cotacao2['Ds_Cotacao'],
                    'nm_usuario' => $cotacao2['Nm_Usuario'],
                    'ds_observacao' => $cotacao2['Ds_Observacao'],
                    'id_fornecedor' => $fornecedor['id'],
                ];

                $DB2->insert('cotacoes', $dataCotacao);
                
                foreach ($cotacao->Produtos_Cotacao->Produto_Cotacao as $produto) {

                    var_dump($produto); exit();
                }

            }

        }
    }
}