<?php

header('Content-Type: application/json;charset=ISO-8859-1');

ini_set('default_socket_timeout', 600);

class PedidosCancelados extends CI_Controller
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
        $this->login = [
            "PONTAMED" =>
                [
                    'id_fornecedor' => 5018,
                    'user' => 'PONTAMED',
                    'password' => 'Nhy67ujm'
                ],
        ];
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
        $this->wsdl = 'http://ws.homologacao.apoiocotacoes.com.br/app/fornecedores/WSFornecedores?wsdl';
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
                        $WGG = $this->request('WJC', [
                            #'DT_BEGIN' => $dtBegin,
                            #'DT_END' => $dtEnd,
                            #'REGION' => 'SP',
                            'ID' => 204643,
                            #'LAYOUT' => 'WGG',
                            #'TOKEN' => 202344657,
                            'ISO' => 0,
                        ]);

                        if (!$WGG) continue;


                        $pedidos = $WGG['data']['Cancelado'];

                        if (!isset($pedidos[0])) {
                            $a = $pedidos;
                            unset($pedidos);
                            $pedidos[0] = $a;
                        }


                        // percorre os pedidos
                        foreach ($pedidos as $pedido) {

                            $idPedido = $pedido['Cabecalho']['Id_Pdc'];
                            $oc = $this->db
                                ->where('Cd_Ordem_Compra', $idPedido)
                                ->where('id_fornecedor', $login['id_fornecedor'])
                                ->get('ocs_sintese')->row_array();

                            if (!empty($oc)) {
                                if (!empty($pedido['Itens_Cancelados'])) {
                                    foreach ($pedido['Itens_Cancelados'] as $item) {

                                        $getItem = $this->db
                                            ->where('id_ordem_compra', $oc['id'])
                                            ->where('Id_Produto_Sintese', $item['Id_Artigo'])
                                            ->get('ocs_sintese_produtos')->row_array();


                                        if (!empty($getItem)) {

                                            $data = [
                                                'situacao' => 9,
                                                'data_situacao' => date('Y-m-d H:i:s', strtotime($item['Data_Cancelamento']))
                                            ];

                                            $this->db
                                                ->where('id', $getItem['id'])
                                                ->update('ocs_sintese_produtos', $data);

                                        }


                                    }
                                }
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
