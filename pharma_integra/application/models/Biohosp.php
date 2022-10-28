<?php

class Biohosp extends MY_Model
{

    /**
     * @author : Chule Cabral
     * Data: 24/09/2020
     */

    private $linkBase;
    private $linkBase2;
    private $linkLogin;
    private $linkLoad;
    private $linkNota;

    public function __construct()
    {
        parent::__construct();

        /**
         * Dados conexão - Biohosp
         */

        $this->linkBase = 'https://biohosp-sky.com.br/mge/service.sbr?serviceName=';
        $this->linkBase2 = 'https://biohosp-sky.com.br/mgecom/service.sbr?serviceName=';
        $this->linkLogin = 'MobileLoginSP.login';
        $this->linkLoad = 'CRUDServiceProvider.loadRecords&mgeSession=';
        $this->linkNota = 'CACSP.incluirNota&mgeSession=';

        $this->load->model('Engine');
    }

    private function mountArrayProds($produtos)
    {

        /**
         * Monta o XML com os dados dos Produtos da Ordem de Compra.
         */

        $string = '';

        foreach ($produtos as $produto) {

            $quantidade = intval($produto['quantidade']) / intval($produto['qtd_emb']);

            $preco = (floatval($produto['preco']) * intval($produto['qtd_emb']));

            $valor_unitario = str_replace(['.', ','], '.', $preco);

            $valor_total = (floatval($produto['preco']) * intval($produto['qtd_emb']));

            $string .= '<item>
						<NUNOTA/>
						<SEQUENCIA/>
						<CODPROD>' . $produto['codigo'] . '</CODPROD>
						<PERCDESC>0</PERCDESC>
						<CODVOL><![CDATA[' . $produto['cod_vol'] . ']]></CODVOL>
                        <CODLOCALORIG>1020000</CODLOCALORIG>
                        <CONTROLE/>
                        <QTDNEG>' . $quantidade . '</QTDNEG>
                        <VLRUNIT>' . $valor_unitario . '</VLRUNIT>
                        <VLRTOT>' . strval($valor_total) . '</VLRTOT>
						</item>';

        }

        return $string;
    }

    private function createSession()
    {

        /**
         * Cria a autenticação - Biohosp
         */

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->linkBase . $this->linkLogin);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '<?xml version="1.0" encoding="ISO-8859-1"?><serviceRequest serviceName="MobileLoginSP.login"><requestBody><NOMUSU>pharmanexo</NOMUSU><INTERNO>Phar!@#2020</INTERNO></requestBody></serviceRequest>');
        $result = curl_exec($ch);

        preg_match('/<jsessionid>(.*?)<\/jsessionid>/s', $result, $matches);

        $session = $matches[1];

        curl_close($ch);

        return $session;
    }

    private function createCodParceiro($session, $cnpj)
    {
        /**
         * Resgate o código do Parceiro.
         */

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->linkBase . $this->linkLoad . $session);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml', 'Cookie: JSESSIONID=' . $session));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '<serviceRequest serviceName="CRUDServiceProvider.loadRecords">
												<requestBody>
												<dataSet rootEntity="Parceiro">
													<entity path="">
														<fieldset list="CODPARC"/>
													</entity>
												<criteria>
														<expression>CGC_CPF = ?</expression>
														<parameter type="S">' . $cnpj . '</parameter>
													</criteria>
												</dataSet>
												</requestBody>
											</serviceRequest>
');
        $result = curl_exec($ch);

        // print($result);

        preg_match('/<f0>(.*?)<\/f0>/s', $result, $matches);

        $codparc = $matches[1];

        curl_close($ch);

        return $codparc;
    }
    
    private function envCurl($session, $codparc, $array, $produtos, $oc)
    {

        /**
         * Envia o XML com os dados dos Produtos Ordem de Compra.
         */

        $data_neg = $dt_cotacao = date('d/m/Y', strtotime(str_replace('/', '-', $array['data'])));

        $body = '<serviceRequest serviceName="CACSP.incluirNota">
												<requestBody>
													<nota>
														<cabecalho>
															<NUNOTA/>
															<TIPMOV>P</TIPMOV>
															<DTNEG>' . $data_neg . '</DTNEG>
															<CODTIPVENDA>101</CODTIPVENDA>
															<!--<Parceiro.CGC_CPF>16676520000159</Parceiro.CGC_CPF>-->
															<CODPARC>' . $codparc . '</CODPARC>
															<CODTIPOPER>256</CODTIPOPER>
															<CODEMP>1</CODEMP>
															<CODVEND>80</CODVEND>
															<CODCENCUS>10601</CODCENCUS>
															<CODPROJ>1001001</CODPROJ>
															<CODNAT>1010101</CODNAT>
															<OBSERVACAO><![CDATA[pedido incluido via integracao - Data Entrega: ' . $array['data_entrega'] . ']]></OBSERVACAO>
														</cabecalho>
											
														<itens INFORMARPRECO="True">
															' . $produtos . '
													</itens>
													</nota>
												</requestBody>
											</serviceRequest>';


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->linkBase2 . $this->linkNota . $session);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

        curl_setopt($ch, CURLINFO_HEADER_OUT, true);

        $result = curl_exec($ch);
        //  print($result);

        /* if(curl_errno($ch))
             print curl_error($ch);
         else
             curl_close($ch);



         $info = curl_getinfo($ch);
         print_r($info['request_header']);*/

        preg_match('/<statusMessage><!\[CDATA\[(.*?)\]\]><\/statusMessage>/s', $result, $matches);

        //    $resultado = base64_decode($matches[1]);

        $xml_result = simplexml_load_string($result);

        $xml_encode = json_encode($xml_result);

        $xml_decode = json_decode($xml_encode, true);

        //var_dump($xml_decode);

        //var_dump($matches[1]);

        $transactionId = $xml_decode['@attributes']['transactionId'];

        if (isset($xml_decode['responseBody']['pk']['NUNOTA'])) {

            $nota = $xml_decode['responseBody']['pk']['NUNOTA'];

            $this->db->where('Cd_Ordem_Compra', $oc)
                ->set('nota', $nota)
                ->set('pendente', 0)
                ->set('transaction_id', $transactionId)
                ->update('ocs_sintese');

            $this->Engine->outPutOc('success', 'Pedido resgatado com sucesso!');

        } else {

            $this->Engine->outPutOc('error', 'Houve um erro ao resgatar o pedido!');

            // var_dump($xml_decode);

        }
        curl_close($ch);
    }

    public function index_biohosp($data)
    {

        $dados = $data;

        $oc = $dados['cod_oc'];

        /**
         * Verifica se a Ordem de Compra já foi resgatada.
         */
        $verify_oc = $this->Engine->verifyOc($oc);

        if (!IS_NULL($verify_oc)) {

            $this->Engine->outPutOc('error', 'Houve um erro ao resgatar o pedido! Pedido ja resgatado.');

            exit();
        }

        /**
         * Cria a sessão para autenticação na Biohosp
         */
        $session = $this->createSession();

        /**
         * Resgatada o código do Parceiro, conforme os dados de autenticação.
         */
        $codparc = $this->createCodParceiro($session, $dados['cnpj']);

        /**
         * Monta o XML com os dados dos Produtos da Ordem de Compra.
         */
        $produtos = $this->mountArrayProds($dados['products']);

        /**
         * Envia o XML com os dados dos Produtos da Ordem de COmpra.
         */
        $this->envCurl($session, $codparc, ['data' => $dados['data'], 'data_entrega' => $dados['data_entrega']], $produtos, $oc);

    }
}
