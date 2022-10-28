<?php

header('Content-Type: application/json;charset=ISO-8859-1');

ini_set('default_socket_timeout', 600);

class DownloadPedidosTeste extends CI_Controller
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

        $this->load->model('m_bionexo', 'bionexo');
        $this->load->model('m_compradores', 'comprador');
        $this->login = loginBionexo();
        $this->login = [
            "HOSPIDROGAS" =>
                [
                    'id_fornecedor' => 20,
                    'user' => 'ws_hospidrogas_pharm',
                    'password' => '3fjwk3dm'
                ],
        ];

        $this->bio = $this->load->database('bionexo', true);

        switch (ENV) {

            case 'development';
                $this->wsdl = 'http://sandbox.bionexo.com.br/ws2/BionexoBean?wsdl';
                break;

            default:
                $this->wsdl = 'https://ws.bionexo.com.br/BionexoBean?wsdl';
                break;
        }
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
        $xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:web="http://webservice.bionexo.com/">
                               <soapenv:Header/>
                               <soapenv:Body>
                                  <web:request>
                                     <login>' . $p[0] . '</login>
                                         <password>' . $p[1] . '</password>
                                         <operation>' . $p[2] . '</operation>
                                         <parameters>' . $p[3] . '</parameters>
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

        $xml = str_replace("<env:Envelope xmlns:env='http://schemas.xmlsoap.org/soap/envelope/'><env:Header></env:Header><env:Body><web:requestResponse xmlns:web='http://webservice.bionexo.com/'><return>", "", $xml);
        $xml = str_replace("</return></web:requestResponse></env:Body></env:Envelope>", "", $xml);


        return $xml;

        /* $t = substr($xml,stripos($xml, '<?xml'));

         $x = simplexml_load_string($t);*/


    }

    public function index()
    {

        $log = [];
        $dtBegin = date("d/m/Y 00:00:00", strtotime("-1day"));
        $dtEnd = date("d/m/Y 23:59:59", strtotime("+1day"));

        try {

            foreach ($this->login as $login) {

                $this->user = $login['user'];
                $this->password = $login['password'];
                //	$ws = new Bionexo('ws_exomed_pe', 'Bionexo123');

                # Operação WGG - Recuperar solicitação de cotação
                # Recupera solicitação de cotação gerada(s) pela(s) empresa(s) compradora(s).
                $WGG = $this->request('WJG', [
                    # 'DT_BEGIN' => $dtBegin,
                    #'DT_END' => $dtEnd,
                    #'REGION' => 'SP',
                    'ID' => 208568785,
                    'LAYOUT' => 'WJ',
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

                    // prepara OC para insert no banco
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
                        'Ds_Observacao' => $cabecalho['Observacao'],
                        'endereco_entrega' => $cabecalho['Endereco_Entrega'],
                        'termos' => (is_array($cabecalho['Termos_Condicoes'])) ? json_encode($cabecalho['Termos_Condicoes']) : $cabecalho['Termos_Condicoes'],
                        'id_fornecedor' => $login['id_fornecedor'],
                        'forma_pagamento' => $cabecalho['Forma_Pagamento'],
                        'Status_OrdemCompra' => 1,
                        'pendente' => 1,
                        'integrador' => 2
                    ];

                    $oc_produtos = [];

                    // parametros para busca de oc ja existente
                    $data = [
                        'cd_ordem_compra' => $cabecalho['Id_Pdc'],
                        'cd_cotacao' => $cabecalho['Id_Pdc'],
                        'id_cliente' => $comp['id'],
                        'id_fornecedor' => $login['id_fornecedor']
                    ];
                    // inicia transação de banco de dados
                    $this->db->trans_start();

                    // se exisitir, pula para o proximo pedido
                    $dadosOC = $this->bionexo->getOC($data);

                    if (empty($dadosOC)) {

                        $i = $this->bionexo->insertCabecalho($oc);
                        $id = $this->db->insert_id();

                    } else {
                        $id = $dadosOC['id'];

                    }

                    // verifica os produtos  resposndidos na cotação
                    $prodsCot = $this->bionexo->getProdutoRespondidos($data);


                    unset($oc_produtos);

                    //  prepara o array de produtos
                    foreach ($produtos as $k => $produto) {

                        //verifica se o produto ja foi inserido
                        $checkProduto = $this->db
                            ->where('Id_Produto_Sintese', $produto['Id_Artigo'])
                            ->where('Cd_Produto_Comprador', $produto['Codigo_Produto'])
                            ->where('id_ordem_compra', $id)
                            ->get('ocs_sintese_produtos')
                            ->row_array();

                        if (!empty($checkProduto)) {
                            continue;
                        }


                        // combina os produtos respondidos com os produtos do pedido
                        foreach ($prodsCot as $j => $pct) {

                            if ($pct['cd_produto_comprador'] == $produto['Codigo_Produto']) {
                                $p_cat = $this->db->where('id_fornecedor', $login['id_fornecedor'])->where('codigo', $pct['id_pfv'])->get('produtos_catalogo')->row_array();

                                $produto['ean'] = $p_cat['ean'];
                                $produto['codigo'] = $p_cat['codigo'];
                                $produto['Qt_Embalagem'] = $p_cat['quantidade_unidade'];
                            }
                        }

                        $data['id_confirmacao'] = $produto['Id_Confirmacao'];
                        $data['id_artigo'] = $produto['Id_Artigo'];
                        $data['id_ordem_compra'] = $id;

                        $prodsPed = $this->bionexo->getProdPed($data);

                        if (count($prodsPed) > 0) continue;

                        if (isset($produto['Campo_Extra'])) {
                            foreach ($produto['Campo_Extra'] as $extra) {
                                $produto[$extra['Nome']] = $extra['Valor'];
                            }
                        }

                        if (isset($produto['Programacao_Entrega'])) {
                            $produto['programacao'] = json_encode($produto['Programacao_Entrega']);
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
                            'Qt_Produto' => isset($produto['Quantidade']) ? intval($produto['Quantidade']) : '',
                            'Vl_Preco_Produto' => $produto['Valor_Unitario'],
                            'id_confirmacao' => $produto['Id_Confirmacao'],
                            'programacao' => isset($produto['programacao']) ? $produto['programacao'] : '',
                            'Qt_Embalagem' => isset($produto['Qt_Embalagem']) ? $produto['Qt_Embalagem'] : '',
                        ];

                    }


                    if (empty($oc_produtos)) continue;


                    //insere os produtos
                    $ins = $this->bionexo->insertProds($oc_produtos);
                    if ($ins){
                        $this->db->where('id', $id)
                            ->update('ocs_sintese', ['pendente' => 1]);
                    }


                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                    } else {
                        $this->db->trans_commit();
                    }

                }

            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

    }

}
