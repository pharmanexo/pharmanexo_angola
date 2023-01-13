<?php

class RequestCotacaoOncoprodSP extends CI_Controller
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
        echo ('hello world');
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
