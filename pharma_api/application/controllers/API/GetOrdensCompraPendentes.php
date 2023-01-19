<?php

/*ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);*/

error_reporting(E_ERROR | E_PARSE);

class GetOrdensCompraPendentes extends CI_Controller
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

        #url de teste
        /* $this->urlCliente = 'https://integracao.plataformasintese.com:8082/IntegrationService.asmx?WSDL';
         $this->client = new SoapClient($this->urlCliente);
         $this->location = 'https://integracao.plataformasintese.com:8082/IntegrationService.asmx';
 */

        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);

    }

    public function index()
    {

        $this->db->insert('log_ordens_compra', ['data_registro' => date("Y-m-d H:i:s", time()), 'tipo' => 2]);

        $log = '';
        $fornecedores = $this->db->select('id, cnpj')
            ->where('sintese', 1)
            #  ->where('id', 115)
            ->get('fornecedores')->result_array();


        foreach ($fornecedores as $fornecedor) {

            try {
                #verifica de a Ordem de compra ja existe no banco de dados
                $function = 'ObterOrdensDeCompraPendentes';
                $arguments = array('ObterOrdensDeCompraPendentes' => array(
                    'cnpjFornecedor' => preg_replace("/\D+/", "", $fornecedor['cnpj']),
                ));
                $options = array('location' => $this->location);
                $result = $this->client->__soapCall($function, $arguments, $options);

                var_dump($result);
                exit();

                if (strpos($result->ObterOrdensDeCompraPendentesResult, 'Nenhuma') > 0 || strpos($result->ObterOrdensDeCompraPendentesResult, 'indisponível') > 0 || strpos($result->ObterOrdensDeCompraPendentesResult, 'intervalo') > 0) {
                    continue;
                }

                $xml = new SimpleXMLElement($result->ObterOrdensDeCompraPendentesResult);
                $array = json_encode($xml);
                $array = json_decode($array, true);

                $xml = $array;

                foreach ($xml as $ocs) {

                    if (!isset($ocs[0])) {
                        $o = $ocs;
                        unset($ocs);
                        $ocs[0] = $o;
                    }

                    foreach ($ocs as $oc) {


                        unset($produtos);
                        if ($this->ocExist($oc['Cd_Ordem_Compra'], $oc['Cd_Fornecedor']) > 0) {
                            $log .= '<error ' . date('d/m/Y H:i:S') . ' > Erro ao importar a OC ' . $oc['Cd_Ordem_Compra'] . " Já existe essa OC" . "\n";
                            continue;
                        }

                        /*   if ($this->cotExist($oc['Cd_Cotacao'], $fornecedor['id']) == 0) {
                               $log .= '<error ' . date('d/m/Y H:i:S') . ' > Erro ao importar a OC ' . $oc['Cd_Ordem_Compra'] . " Cotação não localizada"."\n";
                               continue;
                           }*/


                        $oc = $this->parseToArray($oc);

                        $cnpj = str_replace('X', '0', $oc['Cd_Comprador']);
                        $comprador = $this->db->select('id')->where('cnpj', mask($cnpj, '##.###.###/####-##'))->get('compradores')->row_array();
                        $oc['id_comprador'] = $comprador['id'];


                        $produtos = $oc['produtos'];
                        #se a OC vier apenas com item, é necessário a correcão abaixa incluindo o indice 0 ao array
                        if (!isset($produtos[0])) {
                            $produtos[0] = $produtos;
                        }
                        unset($oc['produtos']);

                        if (empty($produtos)) {
                            continue;
                        }

                        foreach ($oc as $jj => $occ) {
                            if (is_array($occ)) {
                                $occ[$jj] = json_encode($occ);
                            }
                        }

                        $oc['id_fornecedor'] = $fornecedor['id'];
                        $oc['pendente'] = 1;

                        if (isset($oc['Ds_Complemento_Logradouro'])) {
                            $oc['Ds_Complemento_Logradouro'] = json_encode($oc['Ds_Complemento_Logradouro']);
                        }

                        if (isset($oc['Tp_Logradouro'])) {
                            $oc['Tp_Logradouro'] = json_encode($oc['Tp_Logradouro']);
                        }


                        if ($this->db->insert('ocs_sintese', $oc)) {
                            $id_oc = $this->db->insert_id();

                            foreach ($produtos as $produto) {

                                foreach ($produto as $kk => $prd) {
                                    if (is_array($prd)) {
                                        $produto[$kk] = json_encode($prd);
                                    }
                                }

                                $produto['Cd_Ordem_Compra'] = $oc['Cd_Ordem_Compra'];
                                $produto['id_ordem_compra'] = $id_oc;

                                if (is_numeric($produto['Cd_ProdutoERP'])) {
                                    $produto['codigo'] = $produto['Cd_ProdutoERP'];
                                }

                                if (isset($produto['Vl_Preco_Produto'])) {
                                    $produto['Vl_Preco_Produto'] = str_replace(',', '.', str_replace('.', '', $produto['Vl_Preco_Produto']));
                                }

                                if ($this->db->insert('ocs_sintese_produtos', $produto)) {
                                    #  $log .= '<success' . date('d/m/Y H:i:S') . '> Oc registrada ' . $oc['Cd_Ordem_Compra'];
                                } else {
                                    $log .= '<error ' . date('d/m/Y H:i:S') . ' > Erro ao importar a OC ' . $oc['Cd_Ordem_Compra'] . ' produto ' . $produto['Cd_Produto_Comprador'] . "\n";
                                }
                            }
                        } else {
                            /*  var_dump($oc);
                              var_dump($this->db->error()); exit();*/
                            $log .= '<error ' . date('d/m/Y H:i:S') . ' > Erro ao importar a OC ' . $oc['Cd_Ordem_Compra'] . "\n";
                        }
                    }


                }


            } catch (Exception $e) {
               continue;
            }

        }

        $f = fopen('logOrdensCompraPendentes.txt', 'w+');
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

    private function cotExist($cot, $idForn)
    {
        $ocFound = $this->db->where("cd_cotacao = '{$cot}' and id_fornecedor = '{$idForn}'")
            ->get('cotacoes_produtos')
            ->result_array();


        return count($ocFound);

    }
}