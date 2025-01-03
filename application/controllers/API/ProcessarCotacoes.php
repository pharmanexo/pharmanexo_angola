<?php

class ProcessarCotacoes extends CI_Controller
{
    private $db1;
    private $db2;

    private $urlClient;

    public function __construct()
    {
        parent::__construct();

        //$this->urlClient = 'http://plataformasintese.com/IntegrationService.asmx?WSDL';

        $this->urlClient = 'http://187.103.68.165:8081/IntegrationService.asmx?WSDL';

        $this->db1 = $this->load->database('default', true);
        $this->db2 = $this->load->database('sintese', true);

        $this->load->model('m_forma_pagamento_fornecedor', 'forma_pagamento_fornecedor');
        $this->load->model('m_prazo_entrega', 'prazo_entrega');
        $this->load->model('m_valor_minimo', 'valor_minimo');
        $this->load->model('m_venda_diferenciada', 'venda_dif');
        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_estados', 'estado');
        $this->load->model('Fornecedor', 'fornecedor');
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

        $soapUrl = $this->urlClient; // asmx URL of WSDL
        // xml post structure
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

    private function verificaCotacaoAutomatica($id_fornecedor, $param, $option)
    {
        $this->db1->select('*');
        $this->db1->from('controle_cotacoes');

        if ($option === 'CNPJ') {

            $this->db1->where('id_cliente', $param);

        } elseif ($option === 'ESTADO') {

            $this->db1->where('id_estado', $param);
        }

        $this->db1->where('id_fornecedor', $id_fornecedor);
        $this->db1->where('automatico', 1);

        $x = $this->db1->get();

        $return = $x->row_array();

        return ((isset($return) && !empty($return)) || !is_null($return)) ? true : false;
    }

    private function verificaVendaDiferenciada($produto, $param, $option, $fornecedor)
    {
        return $this->venda_dif->verificarSeExisteVenda($produto, $param, $option, $fornecedor);
    }

    private function verificaValorMinimo($key1, $key2, $option)
    {
        $var = '';

        if ($option == 1) {

            $var = 'id_cliente';

        } elseif ($option == 2) {

            $var = 'id_estado';
        }

        return $this->valor_minimo->find("*", "{$var} = {$key1} and id_fornecedor = {$key2}", true);
    }

    private function verificaRetricoes($id_produto, $id_fornecedor, $param, $option)
    {
        $this->db1->select('*');
        $this->db1->from('restricoes_produtos_clientes');

        if ($option === 'CNPJ') {

            $this->db1->where('id_cliente', $param);

        } elseif ($option === 'ESTADO') {

            $this->db1->where('id_estado', $param);
        }

        $this->db1->where('id_produto', $id_produto);
        $this->db1->where('id_fornecedor', $id_fornecedor);

        $x = $this->db1->get();

        return $x->result_array();
    }

    private function verificaPrecoEspecial($codigo_produto, $id_estado)
    {
        return $this->db1->select('valor')
            ->where('codigo', $codigo_produto)
            ->where('id_estado', $id_estado)
            ->where('tipo', 0)
            ->or_where('tipo', 2)
            ->get('precos_especiais')
            ->row_array()['valor'];
    }

    private function verificaCotacaoEnviada($cd_cotacao)
    {

        return $this->db1->select('cd_cotacao')
            ->where('cd_cotacao', $cd_cotacao)
            ->get('cotacoes_produtos')
            ->result_array();

        return ((isset($return) && !empty($return)) || !is_null($return)) ? true : false;

    }

    private function toolsLog()
    {
        $date = Date('Ymd');
        return $tools = [
            "date" => $date,
            "time" => Date('d m Y H:i:s'),
            "hour" => Date('H'),
            "folder" => $_SERVER['DOCUMENT_ROOT'] . '/public/cotacoes/automaticas/' . $date
        ];
    }

    private function processaCotacao($cd_cotacao, $log, $warning = NULL, $xml = NULL)
    {
        $folder = $this->toolsLog()['folder'];

        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        if (!IS_NULL($warning)) {

            $endLog[$cd_cotacao]["NOT-SENT"][$this->toolsLog()["time"]]["log"] = $log;

            $endLog[$cd_cotacao]["NOT-SENT"][$this->toolsLog()["time"]]["warning"] = $warning;

        } else {

            $this->sendSintese($xml, $cd_cotacao);

            $endLog[$cd_cotacao]["SENT"][$this->toolsLog()["time"]]["log"] = $log;

            $sendXml = fopen("{$this->toolsLog()["folder"]}/{$cd_cotacao}.xml", "w+");

            fwrite($sendXml, $xml);

            fclose($sendXml);
        }

        //   var_dump($endLog); exit();
        return $endLog;
    }

    public function index()
    {
        $logCotacaos = [];

        $warning = [];

        $log = [];

        $fornecedores = $this->fornecedor->find('*', 'sintese = 1');

        //  var_dump($fornecedores); exit();

        foreach ($fornecedores as $fornecedor) {

            $encontrados = [];

            $function = 'ObterCotacoes';
            $arguments = array('ObterCotacoes' => array(
                'cnpj' => preg_replace("/\D+/", "", $fornecedor['cnpj']),
            ));

            $cotacoes = $this->db2->select('*')
                ->where('id_fornecedor', $fornecedor['id'])
                ->get('cotacoes')
                ->result_array();

            // var_dump($cotacoes); exit();

            foreach ($cotacoes as $cotacao) {

                unset($log);
                unset($warning);

                $item = $cotacao;

                //  print_r("Processando o id: {$item['id']}, Cotacao: {$item['cd_cotacao']}." . PHP_EOL);

                $log["id_fornecedor"] = $fornecedor['id'];

                ($this->verificaCotacaoEnviada($item['cd_cotacao'])) ? $warning["cotacao_atendida"] = "Atendida!" : '';

                $produtos = $this->db2->select('*')
                    ->where('cd_cotacao', $item['cd_cotacao'])
                    ->where('id_fornecedor', $fornecedor['id'])
                    ->get('cotacoes_produtos')
                    ->result_array();

                $cnpj = mask($item['cd_comprador'], '##.###.###/####-##');

                $cliente = $this->compradores->get_byCNPJ($cnpj);

                $log["id_cliente"] = $cliente['id'];

                unset($encontrados);
                unset($arrayProdutos);
                unset($produtos_fornecedor);

                $valores_minimos = [];

                if (!empty($cliente)) {

                    #valor minimo
                    $vl_min_cnpj = $this->verificaValorMinimo($cliente['id'], $fornecedor['id'], 1);

                    (isset($cliente['estado']) && !empty($cliente['estado'])) ?
                        $estado = $this->estado->find("id", "uf = '{$cliente['estado']}'", true) : $warning["estado"] = "Nao localizado!";

                    $log["id_estado"] = $estado['id'];

                    $vl_min_uf = $this->verificaValorMinimo($estado['id'], $fornecedor['id'], 2);

                    $valores_minimos = [
                        "vl_min_cnpj" => floatval($vl_min_cnpj['valor_minimo']),
                        "desconto_padrao_cnpj" => floatval($vl_min_cnpj['desconto_padrao']),
                        "vl_min_uf" => floatval($vl_min_uf['valor_minimo']),
                        "desconto_padrao_uf" => floatval($vl_min_uf['desconto_padrao']),
                    ];

                } else {
                    $warning = [
                        "cliente" => "Nao localizado!",
                    ];
                }

                $cotacaoAutomatica = $this->verificaCotacaoAutomatica($fornecedor['id'], $cliente['id'], 'CNPJ');

                (!$cotacaoAutomatica) ? $this->verificaCotacaoAutomatica($fornecedor['id'], $estado['id'], 'ESTADO') : '';

                $log["cotacao_automatica"] = $cotacaoAutomatica;

                if ($cotacaoAutomatica) {

                    $warning["cotacao_automatica"] = "Nao habilitado!";
                }

                $valor_minimo = '';
                $desconto_padrao = '';

                if (isset($valores_minimos['vl_min_cnpj'])
                    && !empty($valores_minimos['vl_min_cnpj'])
                    && ($valores_minimos['vl_min_cnpj'] != 0)) {

                    $valor_minimo = $valores_minimos['vl_min_cnpj'];

                } else {

                    $valor_minimo = $valores_minimos['vl_min_uf'];
                }

                if (isset($valores_minimos['desconto_padrao_cnpj'])
                    && !empty($valores_minimos['desconto_padrao_cnpj'])
                    && ($valores_minimos['desconto_padrao_cnpj'] != 0)) {

                    $desconto_padrao = $valores_minimos['desconto_padrao_cnpj'];

                } else {
                    $desconto_padrao = $valores_minimos['desconto_padrao_uf'];
                }

                $log["valor_minimo"] = $valor_minimo;
                $log["desconto_padrao"] = $desconto_padrao;

                // validaçoes para o pagamento ...

                #prazo entrega
                $prazo_entrega = $this->prazo_entrega->find("*", "id_cliente = {$cliente['id']} and id_fornecedor = {$fornecedor['id']}", true);

                if (empty($prazo_entrega)) {
                    $prazo_entrega = $this->prazo_entrega->find("*", "id_estado = {$estado['id']} and id_fornecedor = {$fornecedor['id']}", true);
                }
                $prazo_entrega = $prazo_entrega['prazo'];

                #condição pagamento
                $forma_pagamento = $this->forma_pagamento_fornecedor->find("*", "id_cliente = {$cliente['id']} and id_fornecedor = {$fornecedor['id']}", true);
                if (empty($forma_pagamento)) {
                    $forma_pagamento = $this->forma_pagamento_fornecedor->find("*", "id_estado = {$estado['id']} and id_fornecedor = {$fornecedor['id']}", true);
                }
                $forma_pagamento = $forma_pagamento['id_forma_pagamento'];

                #validações para o LOG

                $log["forma_pagamento"] = true;
                $log["prazo_entrega"] = true;

                if (!isset($forma_pagamento) || empty($forma_pagamento)) {
                    $warning["forma_pagamento"] = "Nao habilitado!";
                    $log["forma_pagamento"] = false;
                }

                if (!isset($valor_minimo) || empty($valor_minimo)) {
                    $warning["valor_minimo"] = "Nao configurado!";
                }

                if (!isset($prazo_entrega) || empty($prazo_entrega)) {
                    $warning["prazo_entrega"] = "Nao configurado!";
                    $log["prazo_entrega"] = false;
                }

                if (isset($warning) && !empty($warning)) {

                    $logCotacaos["{$item['id']}"] = $this->processaCotacao($item['cd_cotacao'], $log, $warning);
                    continue;
                }

                $countProd = count($produtos);

                foreach ($produtos as $i => $produto) {

                    $ids_sintese = $this->db1->select('id_sintese')
                        ->where('id_produto', $produto['id_produto_sintese'])
                        ->get('produtos_marca_sintese')
                        ->result_array();

                    $ids = [];

                    if (empty($ids_sintese) || IS_NULL($ids_sintese)) {

                        if (($countProd - 1) != $i) continue;
                    }

                    foreach ($ids_sintese as $item_ids) {
                        $ids[] = $item_ids['id_sintese'];
                    }

                    $ids = implode(',', $ids);

                    $log["id_produto_sintese"][$produto['id_produto_sintese']]["ids_sintese"] = $ids;

                    //  var_dump($ids); exit();

                    $produtos_fornecedor =
                        $this->db1->select('id, id_produto, codigo, id_sintese, id_marca, marca, validade, preco_unidade, estoque, quantidade_unidade')
                            ->where_in('id_sintese', $ids)
                            ->where('id_estado', $estado['id'])
                            ->where('id_fornecedor', $fornecedor['id'])
                            ->where('validade > NOW()')
                            ->order_by('validade ASC')
                            ->get('vw_produtos_fornecedores_sintese')
                            ->result_array();

                    //var_dump($produtos_fornecedor); exit();

                    if (!empty($produtos_fornecedor)) {
                        $encontrados[$produto['id_produto_sintese']] = $produtos_fornecedor;

                    } else {

                        $warning["id_produto_sintese"][] = "{$produto['id_produto_sintese']} - nao localizado! ";

                        if (($countProd - 1) != $i) continue;

                        if (!isset($encontradros) || empty($encontrados)) {

                            $warning["produtos_fornecedor"] = "Nenhum produto foi localizado para essa cotacao!";

                            $logCotacaos["{$item['id']}"] = $this->processaCotacao($item['cd_cotacao'], $log, $warning);

                            continue 2;
                        }
                    }
                } // Produto


                $arrayProdutos = [];

                $campos = [];

                if (!empty($encontrados)) {

                    //var_dump($encontrados);

                    foreach ($encontrados as $key => $pp) {
                        foreach ($pp as $k => $ppp) {

                            $idVendaDif = $this->verificaVendaDiferenciada($ppp['id_produto'], $cliente['id'], 'CLIENTE', $fornecedor['id']);

                            if (IS_NULL($idVendaDif)) {

                                $idVendaDif = $this->verificaVendaDiferenciada($ppp['id_produto'], $estado['id'], 'ESTADOS', $fornecedor['id']);

                                //  echo 'Entrei no Estado';

                            } elseif (IS_NULL($idVendaDif)) {

                                $idVendaDif = $this->verificaVendaDiferenciada($ppp['id_produto'], $ppp['codigo'], 'CODIGO', $fornecedor['id']);

                                //  echo 'Entrei no Código';
                            }

                            $restricoes = $this->verificaRetricoes($ppp['id_produto'], $fornecedor['id'], $cliente['id'], 'CNPJ');

                            if (IS_NULL($restricoes)) {

                                $this->verificaRetricoes($ppp['id_produto'], $fornecedor['id'], $estado['id'], 'ESTADO');
                            }

                            // var_dump($encontrados); exit();

                            $log["restricoes"] = false;

                            if (isset($restrições) && !empty($restrições)) {
                                //pensar no encadeamento de restricoes por produto
                                $warning["restricoes"] = "Existem restricoes!";

                                $log["restricoes"] = true;

                                unset($pp[$key]);
                            }

                            //var_dump($restricoes); exit();

                            //var_dump($idVendaDif); exit();

                            $valor = floatval($ppp['preco_unidade']);
                            $newValor = 0;
                            $desconto = 0;

                            // var_dump($desconto_padrao); exit();

                            if (!IS_NULL($idVendaDif)) {

                                $x = $this->db1->select('desconto_percentual')
                                    ->where('id', intval($idVendaDif))
                                    ->get('vendas_diferenciadas')
                                    ->row_array();

                                $desconto = floatval($x['desconto_percentual']);

                                $newValor = $valor - ($valor * ($desconto / 100));

                                $ppp['preco_unidade'] = strval($newValor);

                                // var_dump($ppp); exit();

                                $log["id_venda_diferenciada"] = $idVendaDif;

                            } elseif (!IS_NULL($desconto_padrao) && $desconto_padrao != 0) {

                                $newValor = $valor - ($valor * ($desconto_padrao / 100));

                                $ppp['preco_unidade'] = strval($newValor);
                            }

                            $preco_especial = floatval($this->verificaPrecoEspecial($ppp['codigo'], $estado['id']));

                            // var_dump($preco_especial); exit();

                            $log["preco_especial"] = false;

                            if ($preco_especial != 0) {

                                $ppp['preco_unidade'] = strval($preco_especial);

                                $log["preco_especial"] = true;
                            }

                            $arrayProdutos[$key][$ppp['codigo']][] = $ppp;
                        }
                    }

                    //var_dump($arrayProdutos);

                    $dom = new DOMDocument("1.0", "ISO-8859-1");

                    #gerar o codigo
                    $dom->formatOutput = true;

                    #criando o nó principal (root)
                    $root = $dom->createElement("Cotacao");

                    #informações do cabeçalho
                    $root->appendChild($dom->createElement("Tp_Movimento", '1'));
                    $root->appendChild($dom->createElement("Dt_Gravacao", date("d/m/Y H:i:s", time())));
                    $root->appendChild($dom->createElement("Cd_Fornecedor", preg_replace("/\D+/", "", $fornecedor['cnpj'])));
                    $root->appendChild($dom->createElement("Cd_Cotacao", $item['cd_cotacao']));
                    // $root->appendChild($dom->createElement("Cd_Cotacao", $cotacao['Cd_Cotacao']));
                    $root->appendChild($dom->createElement("Cd_Condicao_Pagamento", (isset($forma_pagamento) && !empty($forma_pagamento)) ? $forma_pagamento : '1'));
                    $root->appendChild($dom->createElement("Nm_Usuario", "PHARMANEXO"));
                    $root->appendChild($dom->createElement("Ds_Observacao", '-'));
                    $root->appendChild($dom->createElement("Qt_Prz_Minimo_Entrega", (isset($prazo_entrega) && !empty($prazo_entrega)) ? $prazo_entrega : '5'));
                    $root->appendChild($dom->createElement("Vl_Minimo_Pedido", number_format($valor_minimo, 2, ',', '.')));

                    $root->appendChild($dom->createElement("Ds_Observacao_Fornecedor", 'Observacao'));

                    $produtosXML = $dom->createElement("Produtos_Cotacao");

                    //  var_dump($arrayProdutos); exit();

                    $valorTotalCotacao = 0;

                    foreach ($arrayProdutos as $j => $prodsJ) {

                        foreach ($prodsJ as $k => $prodsK) {

                            $estoque_da_unidade = 0;

                            $produtoXML = $dom->createElement("Produto_Cotacao");

                            $produtoXML->appendChild($dom->createElement("Id_Produto_Sintese", $j));
                            $produtoXML->appendChild($dom->createElement("Cd_Produto_Comprador", $k));

                            $marcas_ofertas = $dom->createElement("Marcas_Oferta");

                            foreach ($prodsK as $i => $pdd) {

                                $estoque_da_unidade += floatval($pdd['estoque']) * floatval($pdd['quantidade_unidade']);

                                if ($i == 0) {

                                    $valorTotalCotacao += (floatval($pdd['quantidade_unidade']) * floatval($pdd['preco_unidade']));
                                }
                            }

                            $obs = "Validade: {$prodsK[0]['validade']}";

                            $marca = $dom->createElement("Marca_Oferta");

                            $marca->appendChild($dom->createElement("Id_Marca", $prodsK[0]['id_marca']));
                            $marca->appendChild($dom->createElement("Ds_Marca", $prodsK[0]['marca']));
                            $marca->appendChild($dom->createElement("Qt_Embalagem", $prodsK[0]['quantidade_unidade']));
                            $marca->appendChild($dom->createElement("Vl_Preco_Produto", $prodsK[0]['preco_unidade']));

                            ($estoque_da_unidade >= intval($produto['qt_produto_total'])) ? '' : $obs .= " - Produto atendido parcialmente !";

                            $marca->appendChild($dom->createElement("Ds_Obs_Oferta_Fornecedor", $obs));
                            $marca->appendChild($dom->createElement("Cd_produtoERP", $prodsK[0]['codigo']));

                            $marcas_ofertas->appendChild($marca);

                            $produtoXML->appendChild($marcas_ofertas);
                            $produtosXML->appendChild($produtoXML);
                        }
                    }
                }

                $log["valor_total_catacao"] = $valorTotalCotacao;

                if ($valorTotalCotacao <= $valor_minimo) {
                    $warning["regra_vl_minimo"] = "O total da cotacao nao esta atentendo o valor minimo necessario!";
                }

                /* ENVIAR COTACAO AUTOMATICA */

                if (isset($warning) && !empty($warning)) {

                    $logCotacaos["{$item['id']}"] = $this->processaCotacao($item['cd_cotacao'], $log, $warning);

                    continue;

                } else {

                    $root->appendChild($produtosXML);

                    $root->appendChild($produtoXML);

                    $dom->appendChild($root);

                    $dom->preserveWhiteSpace = false;

                    $simpleXML = new SimpleXMLElement($dom->saveXML());

                    $logCotacaos["{$item['id']}"] = $this->processaCotacao($item['cd_cotacao'], $log, NULL, $dom->saveXML());
                }
            } // cotacao
            //exit();
        } // fornecedor

        $logRegister = fopen("{$this->toolsLog()["folder"]}/{$this->toolsLog()["hour"]}-log.json", "a+");

        fwrite($logRegister, json_encode($logCotacaos));

        fclose($logRegister);

    } // index

} // class

