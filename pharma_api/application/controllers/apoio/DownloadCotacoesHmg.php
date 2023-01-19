<?php

header('Content-Type: text/html; charset=utf-8');
error_reporting(1);
@ini_set('display_errors', E_ALL);
ini_set('default_socket_timeout', 600);
date_default_timezone_set("America/Fortaleza");


class DownloadCotacoesHmg extends CI_Controller
{

    /**
     * @author : Chule Cabral
     * Data: 25/09/2020
     *
     * Crontab => 30 * * * * wget https://pharmanexo.com.br/pharma_api/API/Bionexo
     */

    private $wsdl;

    private $login = [];

    private $user;

    private $password;

    private $time;

    private $apoio;

    public function __construct()
    {
        parent::__construct();

        $this->login = $this->getFornecedores();

        $this->time = '60 minutes';

        $this->apoio = $this->load->database('apoio', true);

        $this->wsdl = 'http://ws.homologacao.apoiocotacoes.com.br/app/fornecedores/WSFornecedores?wsdl';

    }

    private function getFornecedores(){
       $fornecedores = $this->db
           ->select('id, nome_fantasia, credencial_apoio')
           ->where('credencial_apoio is not null')
           ->where('id = 5018')
           ->get('fornecedores')->result_array();


       foreach ($fornecedores as $k => $fornecedor){
           $json = json_decode($fornecedor['credencial_apoio'], true);

           if (empty($json['login']) || empty($json['password'])){
               unset($fornecedores[$k]);
           }else{
               $fornecedores[$k]['credencial_apoio'] = $json;
           }

       }


       return $fornecedores;

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
            exit();


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

        $xml = utf8_decode(html_entity_decode($response));

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

    private function post(string $operation, array $params, string $xml)
    {
        return $this->exec('post', $operation, $params, $xml);
    }

    public function index()
    {

        try {

            /*  $dt_now = new DateTime();
              $dt_end = date('d/m/Y 23:59:00', time());
              $dt_begin = date_sub($dt_now, date_interval_create_from_date_string($this->time));
              $dt_begin = $dt_begin->format('d/m/Y H:i:00');*/


            $dt_begin = date("07/12/2022 11:00:00", time());
            $dt_end = date("07/12/2022 11:10:00", time());


            foreach ($this->login as $login) {


                $this->user = $login['credencial_apoio']['login'];
                $this->password = $login['credencial_apoio']['password'];
                //	$ws = new Bionexo('ws_exomed_pe', 'Bionexo123');


                # Operação WGG - Recuperar solicitação de cotação
                # Recupera solicitação de cotação gerada(s) pela(s) empresa(s) compradora(s).
                $WGG = $this->request('WGG', [
                    'DT_BEGIN' => $dt_begin,
                    'DT_END' => $dt_end,
                    #'REGION' => 'SP',
                    //'ID' => 536737,
                    #'LAYOUT' => 'WGG',
                    #'TOKEN' => 202344657,
                    'ISO' => 0,
                ]);


                $cotacoes = arrayFormat($WGG['data']['Pedido']);


                foreach ($cotacoes as $cotacao) {

                    $checkIntegradores = [];

                    $cotAuterada = FALSE;

                    $arrCabecalho = [];

                    $cabecalho = $cotacao["Cabecalho"];
                    $data_final = $cabecalho["Data_Vencimento"];


                    $data_fim = str_replace('/', '-', $data_final);

                    $dt_fim = date('Y-m-d H:i:s', strtotime($data_fim));

                    $data_begin = str_replace('/', '-', $dt_begin);

                    $dt_bg = date('Y-m-d H:i:s', strtotime($data_begin));

                    $cnpj_hosp = $cabecalho["CNPJ_Hospital"];

                    $sintese = TRUE;


                    $comprador = $this->db->where('cnpj', $cnpj_hosp)
                        ->limit(1)
                        ->get('compradores')
                        ->row_array();

                    $id_cliente = $comprador['id'];

                    $id_cliente = intval($id_cliente);


                    if ($id_cliente == 0) {

                        $sintese = FALSE;

                        $client = $this->getCnpjReceita(preg_replace('/[^0-9]/', '', $cnpj_hosp));

                        $arr =
                            [
                                'razao_social' => $cabecalho["Nome_Hospital"],
                                'nome_fantasia' => $cabecalho["Nome_Hospital"],
                                'cnpj' => $cnpj_hosp,
                                'estado' => $client->uf,
                                'cidade' => $client->municipio,
                                'endereco' => $client->logradouro,
                                'responsavel' => $cabecalho["Contato"]
                            ];

                        $insertCliente = $this->db->insert('compradores', $arr);

                        $id_cliente = $this->db->insert_id();

                        $comprador['estado'] = $client->uf;
                        $comprador['cidade'] = $client->municipio;

                    }

                    $integrador[] =
                        [
                            'id_integrador' => 3,
                            'id_cliente' => $id_cliente
                        ];

                    if ($sintese) {

                        array_push($integrador, [
                            'id_integrador' => 1,
                            'id_cliente' => $id_cliente
                        ]);
                    }

                    $checkIntegradores = $this->db->where('id_cliente', $id_cliente)
                        ->get('compradores_integrador')
                        ->result_array();

                    if (empty($checkIntegradores))
                        $this->db->insert_batch('compradores_integrador', $integrador);

                    $cd_cotacao = $cabecalho["Id_Pdc"];


                    $cnpj_format = str_replace('.', '', str_replace('-', '', str_replace('/', '', $cabecalho["CNPJ_Hospital"])));

                    $itens = $cotacao['Itens']["Item"];

                    $itens = arrayFormat($itens);

                    $total_itens = count($itens);

                    $arrCabecalho =
                        [
                            "id_fornecedor" => $login['id'],
                            "id_cliente" => $id_cliente,
                            "cd_cotacao" => $cd_cotacao,
                            "ds_cotacao" => $cabecalho["Titulo_Pdc"],
                            "dt_inicio_cotacao" => $dt_bg,
                            "dt_fim_cotacao" => $dt_fim,
                            "nome_hospital" => $cabecalho["Nome_Hospital"],
                            "cd_comprador" => $cnpj_format,
                            "uf_cotacao" => $comprador['estado'],
                            "cidade" => $comprador['cidade'],
                            "endereco" => $cabecalho["Endereco_Entrega"],
                            "contato" => $cabecalho["Contato"],
                            "id_forma_pagamento" => $cabecalho["Id_Forma_Pagamento"],
                            "total_itens" => $total_itens,
                           // "forma_pagamento" => (is_array($cabecalho["Condicoes"])) ? json_encode($cabecalho["Condicoes"]) : $cabecalho["Condicoes"],
                            "observacao" => (is_array($cabecalho["Observacao"])) ? json_encode($cabecalho["Observacao"]) : $cabecalho["Observacao"]
                        ];


                    $this->apoio->trans_begin();

                    $checkCotacao = $this->apoio
                        ->where('cd_cotacao', $cd_cotacao)
                        ->where('id_fornecedor', $login['id'])
                        ->limit(1)
                        ->get('cotacoes')
                        ->row_array();

                    $id_cotacao = 0;

                    $insert_cot = FALSE;

                    if (!IS_NULL($checkCotacao)) {

                        $id_cotacao = $checkCotacao['id'];

                        $cotAuterada = TRUE;

                        if ($checkCotacao['dt_fim_cotacao'] != $dt_fim) {

                            $this->apoio->where('id', $checkCotacao['id'])
                                ->set('dt_fim_cotacao', $dt_fim)
                                ->update('cotacoes');

                        }

                    } else {

                        $insert_cot = $this->apoio->insert('cotacoes', $arrCabecalho);
                    }


                    if ($insert_cot)
                        $id_cotacao = $this->apoio->insert_id();

                    $arrItens = [];

                    $qtd_itens = $this->apoio->select('COUNT(DISTINCT cd_produto_comprador, id_cotacao) AS total')
                        ->where('id_cotacao', $id_cotacao)
                        ->get('cotacoes_produtos')
                        ->row_array();

                    $qtd_itens = intval($qtd_itens['total']);

                    $processaProds = FALSE;

                    if ($cotAuterada) {

                        if ($total_itens != $qtd_itens) {

                            $this->apoio->where('id_cotacao', $id_cotacao)
                                ->delete('cotacoes_produtos');
                        } else {
                            $processaProds = TRUE;
                        }
                    }

                    foreach ($itens as $keyItem => $item) {

                        $arrItens[$keyItem] =
                            [
                                "id_cotacao" => $id_cotacao,
                                "sequencia" => $item['Sequencia'],
                                "id_artigo" => $item['Id_Artigo'],
                                "cd_produto_comprador" => $item['Codigo_Produto'],
                                "ds_produto_comprador" => $item['Descricao_Produto'],
                                "qt_produto_total" => $item['Quantidade'],
                                "ds_unidade_compra" => $item['Unidade_Medida'],
                                "id_unidade" => $item['Id_Unidade_Medida'],
                                "marca_favorita" => is_array($item['Marca_Favorita']) ? json_encode($item['Marca_Favorita']) : $item['Marca_Favorita'],
                                "id_categoria" => isset($item['Id_Categoria']) ? $item['Id_Categoria'] : ''
                            ];

                        if ($processaProds) {

                            $verifyProduto = $this->apoio->where('id_cotacao', $id_cotacao)
                                ->where('cd_produto_comprador', $arrItens[$keyItem]['cd_produto_comprador'])
                                ->limit(1)
                                ->get('cotacoes_produtos')
                                ->row_array();

                            $arrItensTemp =
                                [
                                    "id_cotacao" => $verifyProduto['id_cotacao'],
                                    "sequencia" => $verifyProduto['sequencia'],
                                    "id_artigo" => $verifyProduto['id_artigo'],
                                    "cd_produto_comprador" => $verifyProduto['cd_produto_comprador'],
                                    "ds_produto_comprador" => $verifyProduto['ds_produto_comprador'],
                                    "qt_produto_total" => $verifyProduto['qt_produto_total'],
                                    "ds_unidade_compra" => $verifyProduto['ds_unidade_compra'],
                                    "id_unidade" => $verifyProduto['id_unidade'],
                                    "marca_favorita" => $verifyProduto['marca_favorita'],
                                    "id_categoria" => $verifyProduto['id_categoria']
                                ];

                            if ($arrItens[$keyItem] != $arrItensTemp) {

                                $this->apoio->where('id_cotacao', $id_cotacao)
                                    ->where('cd_produto_comprador', $arrItens[$keyItem]['cd_produto_comprador'])
                                    ->update('cotacoes_produtos', $arrItensTemp);

                            }

                            unset($arrItens[$keyItem]);
                            continue;
                        }

                        $arrMarcas = [];

                        if (isset($item['Marcas']['Marca'])) {

                            $marcas = arrayFormat($item['Marcas']['Marca']);

                            foreach ($marcas as $marca) {

                                $verifyMarca = $this->apoio->where('codigo_produto', $item['Codigo_Produto'])
                                    ->where('codigo_marca', $marca['Codigo_Marca'])
                                    ->where('id_cotacao', $id_cotacao)
                                    ->where('id_cliente', $id_cliente)
                                    ->limit(1)
                                    ->get('produtos_marcas')
                                    ->row_array();

                                if (IS_NULL($verifyMarca)) {

                                    $arrMarcas[] =
                                        [
                                            'codigo_produto' => $item['Codigo_Produto'],
                                            'codigo_marca' => $marca['Codigo_Marca'],
                                            'marca' => $marca['Nome_Marca'],
                                            'id_cotacao' => $id_cotacao,
                                            'id_cliente' => $id_cliente
                                        ];

                                }
                            }

                            if (!empty($arrMarcas))
                                $insert_marca = $this->apoio->insert_batch('produtos_marcas', $arrMarcas);

                            /*if ($insert_marca === FALSE)
                                $this->apoio->trans_rollback();*/
                        }
                    }

                    if (!empty($arrItens))
                        $insert_itens = $this->apoio->insert_batch('cotacoes_produtos', $arrItens);

                    /*	if ($insert_itens === FALSE)
                            $this->apoio->trans_rollback();*/


                    if ($this->apoio->trans_status() !== FALSE) {

                        $this->apoio->trans_commit();

                    } else {

                        $this->apoio->trans_rollback();

                    }
                }
            }

        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getCnpjReceita($cnpj)
    {

        $content = file_get_contents("https://www.receitaws.com.br/v1/cnpj/{$cnpj}");
        return json_decode($content);

    }

                private
                function strip_bom($str)
                {
                    return preg_replace('/^(\x00\x00\xFE\xFF|\xFF\xFE\x00\x00|\xFE\xFF|\xFF\xFE|\xEF\xBB\xBF)/', "", $str);
                }
            } // class

