<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

class GetOrdensCompra extends CI_Controller
{

    private $urlCliente;
    private $client;
    private $location;

    public function __construct()
    {
        parent::__construct();

        $this->urlCliente = 'http://integracao.plataformasintese.com/IntegrationService.asmx?WSDL';
        $this->client = new SoapClient($this->urlCliente);
        $this->location = 'http://integracao.plataformasintese.com/IntegrationService.asmx';


    }

    public function index()
    {

        $this->db->insert('log_ordens_compra', ['data_registro' => date("Y-m-d H:i:s", time()), 'tipo' => 1]);

        $log = '';
        $data = date('Y-m-d', strtotime('-20 days', time()));

        $cotacoes = $this->db->select("cd_cotacao, id_fornecedor, cnpj")
//           ->where('cd_cotacao', 'COT2873-610')
            #->where_in('id_fornecedor', '120, 123, 126')
            ->where("date(data_cotacao) > '{$data}'")
            ->get('vw_cotacoes')
            ->result_array();
        $message = '';
        $arguments = [];

        foreach ($cotacoes as $cotacao) {
            #verifica de a Ordem de compra ja existe no banco de dados
            $function = 'ObterTodasOrdensDeCompra';
            $arguments = array('ObterTodasOrdensDeCompra' => array(
                'cnpjFornecedor' => preg_replace("/\D+/", "", $cotacao['cnpj']),
                'codigoCotacaoSintese' => $cotacao['cd_cotacao'],
            ));

            $options = array('location' => $this->location);


            try {
                $result = $this->client->__soapCall($function, $arguments, $options);
            } catch (Exception $e) {
                $msg = "- Erro: \n {$e->getMessage()} \n\r";

                $data = [
                    'cd_cotacao' => $cotacao['cd_cotacao'],
                    'cnpj_fornecedor' => $cotacao['cnpj'],
                    'id_fornecedor' => $cotacao['id_fornecedor'],
                    'message' => $msg,
                ];

               $q = $this->db->insert('log_ordem_compra', $data);

                continue;
            }


            if (strpos($result->ObterTodasOrdensDeCompraResult, 'Nenhuma') !== false || strpos($result->ObterTodasOrdensDeCompraResult, 'IDENTIFICADA') !== false || strpos($result->ObterTodasOrdensDeCompraResult, 'indisponível') !== false) {
                continue;
            }

            $xml = new SimpleXMLElement($result->ObterTodasOrdensDeCompraResult);

            $array = json_encode($xml);
            $array = json_decode($array, true);

            $xml = $array;


            foreach ($xml as $ocs) {

                unset($produtos, $ocParse);

                if (!isset($ocs[0])) {
                    $tmp = $ocs;
                    unset($ocs);
                    $ocs[0] = $tmp;
                }

                foreach ($ocs as $oc) {
                    $ocEx = $this->ocExist($oc['Cd_Ordem_Compra'], $oc['Cd_Fornecedor']);

                    $ocParse = $this->parseToArray($oc);

                    if ($ocEx > 0) {
                        if ($ocParse['Status_OrdemCompra'] > 2){
                            $this->db->where('Cd_Ordem_Compra', $oc['Cd_Ordem_Compra']);
                            $this->db->where('Cd_Fornecedor', $oc['Cd_Fornecedor']);
                            $this->db->where('pendente', 1);
                            $this->db->update('ocs_sintese', ['pendente' => '0']);
                        }
                        continue;
                    }

                    $cnpj = str_replace('X', '0', $ocParse['Cd_Comprador']);
                    $comprador = $this->db->select('id')->where('cnpj', mask($cnpj, '##.###.###/####-##'))->get('compradores')->row_array();
                    $ocParse['id_comprador'] = $comprador['id'];

                    $ocParse['Tp_Logradouro'] = '';
                    $ocParse['Ds_Complemento_Logradouro'] = (is_string($ocParse['Ds_Complemento_Logradouro']) && !empty($ocParse['Ds_Complemento_Logradouro'])) ? json_decode($ocParse['Ds_Complemento_Logradouro']) : '';

                    $produtos = $ocParse['produtos'];
                    #se a OC vier apenas com item, é necessário a correcão abaixa incluindo o indice 0 ao array
                    if (!isset($produtos[0])) {
                        $produtos[0] = $produtos;
                    }
                    unset($ocParse['produtos']);


                    if (empty($produtos)) {
                        continue;
                    }

                    foreach ($ocParse as $jj => $occ) {
                        if (is_array($occ)) {
                            $occ[$jj] = json_encode($occ);
                        }
                    }


                    if($ocParse['Status_OrdemCompra'] <= 2){
                        $ocParse['pendente'] = 1;
                    }


                    $ocParse['id_fornecedor'] = $cotacao['id_fornecedor'];

                    if ($this->db->insert('ocs_sintese', $ocParse)) {
                        $id_oc = $this->db->insert_id();

                        foreach ($produtos as $produto) {

                            unset($produto['Cd_Ordem_Compra'], $produto['id_ordem_compra']);

                            foreach ($produto as $kk => $prd) {
                                if (is_array($prd)) {
                                    $produto[$kk] = json_encode($prd);
                                }
                            }

                            if (isset($produto['Vl_Preco_Produto'])) {
                                /*if ($oc['Cd_Ordem_Compra'] == 'OC9621-580987'){

                                }*/
                                $produto['Vl_Preco_Produto'] = str_replace(',', '.', str_replace('.', '', $produto['Vl_Preco_Produto']));
                            }

                            $produto['Cd_Ordem_Compra'] = $ocParse['Cd_Ordem_Compra'];
                            $produto['id_ordem_compra'] = $id_oc;

                            if (is_numeric($produto['Cd_ProdutoERP'])){
                                $produto['codigo'] = $produto['Cd_ProdutoERP'];
                            }


                            if ($this->db->insert('ocs_sintese_produtos', $produto)) {
                                #  $log .= '<success' . date('d/m/Y H:i:S') . '> Oc registrada ' . $ocParse['Cd_Ordem_Compra'] . "\n";
                            } else {
                                /* var_dump('prod');
                                 var_dump($this->db->last_query());
                                 var_dump($this->db->error());
                                 exit();*/
                                $log .= '<error ' . date('d/m/Y H:i:S') . ' > Erro (' . $this->db->error()['message'] . ') ao importar a OC ' . $ocParse['Cd_Ordem_Compra'] . ' produto ' . $produto['Cd_Produto_Comprador'] . "\n";
                            }
                        }
                    } else {
                        /*  var_dump('oc');
                          var_dump($this->db->last_query());
                          var_dump($this->db->error());
                          exit();*/
                        $log .= '<error ' . date('d/m/Y H:i:S') . ' > Erro (' . $this->db->error()['message'] . ') ao importar a OC ' . $ocParse['Cd_Ordem_Compra'] . "\n";
                    }
                }

            }
            unset($xml);

        }

        $f = fopen('logOrdensCompra.txt', 'w+');
        fwrite($f, $log);
        fclose($f);

    }


    private function parseToArray($oc)
    {

        if (isset($oc['Telefones_Ordem_Compra']) && !empty($oc['Telefones_Ordem_Compra'])) {
            $oc['Telefones_Ordem_Compra'] = (array)$oc['Telefones_Ordem_Compra'];
            foreach ($oc['Telefones_Ordem_Compra'] as $k => $telefone) {
                $oc['Telefones_Ordem_Compra'][$k] = (array)$telefone;
            }

            $oc['Telefones_Ordem_Compra'] = json_encode($oc['Telefones_Ordem_Compra']);
        }

        if (isset($oc['Dt_Gravacao'])) {
            $oc['Dt_Gravacao'] = date("Y-m-d H:i:s", time());
        }


        if (isset($oc['Dt_Previsao_Entrega'])) {
            $oc['Dt_Previsao_Entrega'] = dbDateFormat($oc['Dt_Previsao_Entrega']);
        }

        if (isset($oc['Dt_Ordem_Compra'])) {
            $oc['Dt_Ordem_Compra'] = dbDateFormat($oc['Dt_Ordem_Compra']);
        }


        if (isset($oc['Produtos_Ordem_Compra']) && !empty($oc['Produtos_Ordem_Compra'])) {
            $oc['Produtos_Ordem_Compra'] = (array)$oc['Produtos_Ordem_Compra'];

            foreach ($oc['Produtos_Ordem_Compra'] as $k => $produto) {

                if (!isset($produto[0])) {
                    $oc['produtos'][] = $produto;
                } else {
                    $oc['produtos'] = $produto;
                }
            }

            unset($oc['Produtos_Ordem_Compra']);
        }

        return $oc;
    }

    private function ocExist($oc, $cnpj_fornecedor)
    {

        $ocFound = $this->db->where("Cd_Ordem_Compra = '{$oc}' and Cd_Fornecedor = '{$cnpj_fornecedor}'")
            ->get('ocs_sintese')
            ->result_array();


        return count($ocFound);

    }

    public function updateCotacoes()
    {
        $ocs = $this->db->get('ocs_sintese')->result_array();

        foreach ($ocs as $oc) {

            $this->db->where('cd_cotacao', $oc['Cd_Cotacao']);
            $this->db->where('id_fornecedor', $oc['id_fornecedor']);

            $this->db->update('cotacoes_produtos', ['codigo_oc' => $oc['Cd_Ordem_Compra']]);
        }

    }

}
