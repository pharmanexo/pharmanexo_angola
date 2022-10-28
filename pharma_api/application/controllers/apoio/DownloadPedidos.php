<?php

header('Content-Type: application/json;charset=ISO-8859-1');

ini_set('default_socket_timeout', 600);

class DownloadPedidos extends CI_Controller
{

    private $wsdl;
    private $login = [];
    private $user;
    private $password;
    private $time;
    private $bio;


    public function __construct()
    {
        parent::__construct();

        $this->load->model('m_apoio', 'apoio');
        $this->load->model('m_compradores', 'comprador');
        $this->login = loginApoio();
        $this->bio = $this->load->database('apoio', true);

        /* switch (ENV) {

             case 'development';
                 $this->wsdl = 'http://homologacao.apoiocotacoes.com.br/app/fornecedores/WSFornecedores?wsdl';
                 break;

             default:
                 $this->wsdl = 'http://ws.apoiocotacoes.com.br/app/fornecedores/WSFornecedores?wsdl';
                 break;
         }*/

        $this->wsdl = 'http://ws.apoiocotacoes.com.br/app/fornecedores/WSFornecedores?wsdl';
    }

    private function exec(string $type = 'request', string $operation, array $params = NULL, string $xml = NULL)
    {

        $param = "";

        foreach ($params as $k => $v) {
            $param .= "{$k}=$v;";
        }
        if ($param !== '') $param = rtrim($param, ';');

        $client = new SoapClient($this->wsdl, array());

        $p = [
            $this->user,
            $this->password,
            $operation,
            $param,
            $xml
        ];

        if ($type == 'request') {
            $resp = $this->request_curl($p);
        } else {
            $resp = $client->__soapCall($type, $p);
        }

        $strxml = substr($resp, strpos($resp, '<?xml'));


        $valida_xml = substr($strxml, 0, 5);

        if ($valida_xml != "<?xml")
            return false;

        $data = explode(';', $resp);
        $array = NULL;

        if ($strxml !== false) {
            $xml = simplexml_load_string($strxml);

            $json = json_encode($xml);
            $array = json_decode($json, TRUE);
        }


        $data = [
            'data' => $array,
            'status' => $data[0],
            'message' => $data[1]
        ];


        return $data;
    }

    private function request(string $operation, array $params)
    {
        return $this->exec('request', $operation, $params);
    }

    private function request_curl($p)
    {
        $xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:web="http://cotacao.fornecedores.client.webService.apoio.com.br/">
                               <soapenv:Header/>
                               <soapenv:Body>
                                  <web:request>
                                     <usuario>' . $p[0] . '</usuario>
                                         <senha>' . $p[1] . '</senha>
                                         <operacao>' . $p[2] . '</operacao>
                                         <parametros>' . $p[3] . '</parametros>
                                  </web:request>
                               </soapenv:Body>
                            </soapenv:Envelope>';

        $headers = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "Content-length: " . strlen($xml_post_string),
        ); //SOAPAction: your op URL

        $url = str_replace("?wsdl", "", $this->wsdl);

        // PHP cURL  for https connection with auth
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // converting
        $response = curl_exec($ch);
        curl_close($ch);

        $xml = html_entity_decode($response);

        $xmlInit = "<?xml version='1.0' encoding='UTF-8'?>";
        $xml = str_replace($xmlInit, "", $xml);

        $init = '<S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"><S:Body><ns2:requestResponse xmlns:ns2="http://cotacao.fornecedores.client.webService.apoio.com.br/"><String>';
        $xml = str_replace($init, "", $xml);

        $end = "</String></ns2:requestResponse></S:Body></S:Envelope>";
        $xml = str_replace($end, "", $xml);


        return $xml;

        /* $t = substr($xml,stripos($xml, '<?xml'));

         $x = simplexml_load_string($t);*/


    }

    public function index()
    {

        $log = [];
        $dtBegin = date("d/m/Y 14:00:00", time());
        $dtEnd = date("d/m/Y 14:15:00", time());

        try {

            foreach ($this->login as $login) {

                $cots = $this->db->distinct()->select('cd_cotacao')
                    ->where('integrador', 'APOIO')
                    ->where('codigo_oc is null')
                    ->where('id_fornecedor', $login['id_fornecedor'])->get('cotacoes_produtos')->result_array();


                if (!empty($cots)) {
                    foreach ($cots as $cot) {
                        $this->user = $login['user'];
                        $this->password = $login['password'];
                        //	$ws = new Bionexo('ws_exomed_pe', 'Bionexo123');

                        # Operação WGG - Recuperar solicitação de cotação
                        # Recupera solicitação de cotação gerada(s) pela(s) empresa(s) compradora(s).
                        $WGG = $this->request('WJG', [
                            #'DT_BEGIN' => $dtBegin,
                            #'DT_END' => $dtEnd,
                            #'REGION' => 'SP',
                            'ID' => $cot['cd_cotacao'],
                            #'LAYOUT' => 'WGG',
                            #'TOKEN' => 202344657,
                            'ISO' => 0,
                        ]);

                        if (!$WGG) continue;

                        $pedidos = $WGG['data']['Confirmado'];


                        if (!isset($pedidos[0])) {
                            $a = $pedidos;
                            unset($pedidos);
                            $pedidos[0] = $a;
                        }

                        // percorre os pedidos
                        foreach ($pedidos as $pedido) {

                            $insert = true;
                            $dadosOC = [];

                            if (!isset($pedido['Cabecalho'])) {
                                $log[] = [
                                    'data' => date('Y-m-d H:i:s', time()),
                                    'type' => 'error',
                                    'mensagem' => 'XML mal formatad',
                                    'data' => $pedidos
                                ];
                                exit($pedido['Cabecalho']);
                            }


                            // cabeçalho do pedido
                            $cabecalho = $pedido['Cabecalho'];
                            #if ($cabecalho['Id_Pdc'] != '173367354') continue;

                            // prudutos do pedido
                            $produtos = $pedido['Itens_Confirmados']['Item_Confirmado'];

                            //corrige o array quando vem apenas 1 produto incluindo o indice zero
                            if (!isset($produtos[0])) {
                                $aux = $produtos;
                                $produtos = [];
                                $produtos[0] = $aux;
                            }

                            //busca os dados do comprador
                            $comp = $this->comprador->getByCNPJ($cabecalho['CNPJ_Hospital']);

                            // prepara OC para insert  no banco
                            $oc = [
                                'Dt_Gravacao' => date('Y-m-d H:i:s', time()),
                                'Tp_Movimento' => 1,
                                'Cd_Fornecedor' => '',
                                'Cd_Condicao_Pagamento' => $cabecalho['Forma_Pagamento'],
                                'Cd_Cotacao' => $cabecalho['Id_Pdc'],
                                'Cd_Ordem_Compra' => $cabecalho['Id_Pdc'],
                                'Dt_Ordem_Compra' => date('Y-m-d', time()),
                                'Hr_Ordem_Compra' => date('H:i:s', time()),
                                'Cd_Comprador' => preg_replace('/[^0-9]/', '', $cabecalho['CNPJ_Hospital']),
                                'id_comprador' => $comp['id'],
                                'Nm_Aprovador' => $cabecalho['Contato'],
                                'Ds_Observacao' => isset($cabecalho['Observacao']) ? $cabecalho['Observacao'] : '',
                                'endereco_entrega' => isset($cabecalho['Endereco_Entrega']) ? $cabecalho['Endereco_Entrega'] : '',
                                'id_fornecedor' => $login['id_fornecedor'],
                                'Status_OrdemCompra' => 1,
                                'integrador' => 3,
                                'pendente' => 1
                            ];

                            $oc_produtos = [];

                            // parametros para busca de oc ja existente
                            $data = [
                                'cd_ordem_compra' => $cabecalho['Id_Pdc'],
                                'cd_cotacao' => $cabecalho['Id_Pdc'],
                                'id_cliente' => $comp['id'],
                                'id_fornecedor' => $login['id_fornecedor']
                            ];

                            // se exisitir, pula para o proximo pedido
                            $dadosOC = $this->apoio->getOC($data);
                            if (!is_null($dadosOC)) $insert = false;

                            // verifica os produtos  resposndidos na cotação
                            $prodsCot = $this->apoio->getProdutoRespondidos($data);

                            // combina os produtos respondidos com os produtos do pedido
                            foreach ($prodsCot as $j => $pct) {
                                $p_cat = $this->db->where('id_fornecedor', $login['id_fornecedor'])->where('codigo', $pct['id_pfv'])->get('produtos_catalogo')->row_array();
                                $prodsCot[$j]['ean'] = $p_cat['ean'];
                            }


                            // inicia transação de banco de dados
                            $this->db->trans_start();


                            if ($insert) {
                                //  insere a OC
                                $this->apoio->insertCabecalho($oc);
                                $id = $this->db->insert_id();
                            } else {
                                $id = $dadosOC['id'];
                            }
                            unset($oc_produtos);

                            //  prepara o array de produtos
                            foreach ($produtos as $k => $produto) {


                                $data['id_artigo'] = $produto['Id_Artigo'];
                                $data['id_ordem_compra'] = $id;

                                $prodsPed = $this->apoio->getProdPed($data);

                                if (count($prodsPed) > 0) continue;

                                if (isset($produto['Campo_Extra'])) {
                                    $produto['Campo_Extra'] = arrayFormat($produto['Campo_Extra']);
                                    foreach ($produto['Campo_Extra'] as $extra) {
                                        $produto[$extra['Nome']] = $extra['Valor'];
                                    }
                                }

                                foreach ($prodsCot as $pCot) {
                                    if ($produto['Id_Artigo'] == $pCot['id_produto']) {

                                        $produto['codigo'] = $pCot['id_pfv'];
                                        $produto['ean'] = $pCot['ean'];
                                    }
                                }

                                $oc_produtos[] = [
                                    'id_ordem_compra' => $id,
                                    'Cd_Produto_Comprador' => $produto['Codigo_Produto'],
                                    'Ds_Unidade_Compra' => $produto['Unidade_Medida'],
                                    'Ds_Produto_Comprador' => $produto['Descricao_Produto'],
                                    'Ds_Observacao_Produto' => $produto['Fabricante'],
                                    'Cd_Ordem_Compra' => $oc['Cd_Ordem_Compra'],
                                    'Id_Produto_Sintese' => $produto['Id_Artigo'],
                                    'codigo' => isset($produto['codigo']) ? $produto['codigo'] : '',
                                    'ean' => isset($produto['ean']) ? $produto['ean'] : '',
                                    'Qt_Produto' => isset($produto['Quantidade']) ? $produto['Quantidade'] : '',
                                    'Vl_Preco_Produto' => $produto['Valor_Unitario'],
                                    'id_confirmacao' => null,
                                ];

                            }

                            if (empty($oc_produtos)) continue;
                            //insere os produtos
                            $this->apoio->insertProds($oc_produtos);

                            $this->db->where('cd_cotacao', $cabecalho['Id_Pdc']);
                            $this->db->where('id_fornecedor', $login['id_fornecedor']);
                            $this->db->update('cotacoes_produtos', ['codigo_oc' => $id]);

                            if ($this->db->trans_status() === FALSE) {
                                var_dump($this->db->error());
                                $this->db->trans_rollback();
                            } else {
                                $this->db->trans_commit();

                                //atualiza produtos ofertados

                                $this->db
                                    ->where('cd_cotacao', $cot['cd_cotacao'])
                                    ->where('id_fornecedor', $login['id_fornecedor'])
                                    ->update('cotacoes_produtos', ['codigo_oc' => $cabecalho['Id_Pdc']]);

                            }

                        }
                    }
                }


            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

    }

}
