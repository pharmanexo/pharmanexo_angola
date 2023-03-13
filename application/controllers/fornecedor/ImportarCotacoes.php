<?php

class ImportarCotacoes extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('/fornecedor/ImportarCotacoes/');
        $this->views = 'fornecedor/cotacoes/';

        $this->DB_SINTESE = $this->load->database('sintese', TRUE);
        $this->DB_BIONEXO = $this->load->database('bionexo', TRUE);
        $this->DB_APOIO = $this->load->database('apoio', TRUE);

    }

    public function index()
    {
        $page_title = 'Importar Cotação';
        # Template
        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnVoltar',
                   'url' => "javascript:history.back(1)",
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Retornar'
                ],

            ]
        ]);

        $data['portais'] = $this->db->get('integradores')->result_array();
        $data['formAction'] = "{$this->route}/buscar";

        $this->load->view("{$this->views}/importarCotacoes", $data);
    }

    public function buscar()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();
            $id_fornecedor = $this->session->id_fornecedor;

            switch ($post['integrador']) {
                case 1:
                    $data['cotacao'] = $this->getCotacaoSintese($post);
                    $data['cotacao']['urlImport'] = "https://pharmanexo.com.br/pharma_api/API/Request/Bionexo?id={$id_fornecedor}&cotacao={$post['cotacao']}";
                    break;
                case 2:
                {
                    $data['cotacao'] = $this->getCotacaoBionexo($post);
                    $data['cotacao']['urlImport'] = "https://pharmanexo.com.br/pharma_api/API/Request/Bionexo?id={$id_fornecedor}&cotacao={$post['cotacao']}";
                    break;
                }

                case 3:
                    $data['cotacao'] = $this->getCotacaoApoio($post);
                    $data['cotacao']['urlImport'] = "https://pharmanexo.com.br/pharma_api/apoio/DownloadCotacoes?id={$id_fornecedor}&cotacao={$post['cotacao']}";
                    break;
            }

        }

        $page_title = 'Importando cotação';
        # Template
        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [

            ]
        ]);

        $data['portais'] = $this->db->get('integradores')->result_array();
        $data['formAction'] = "{$this->route}/buscar";

        $this->load->view("{$this->views}/importarCotacoes", $data);

    }

    private function getCotacaoApoio($data)
    {
        $fornecedor = $this->db->where('id', $this->session->id_fornecedor)->get('fornecedores')->row_array();

        if (!empty($fornecedor['credencial_apoio'])) {

            $credenciais = json_decode($fornecedor['credencial_apoio'], true);

            if (!empty($credenciais['login']) && !empty($credenciais['password'])) {

                $ch = curl_init("https://pharmanexo.com.br/pharma_api/apoio/DownloadCotacoes/getCotacao?id={$fornecedor['id']}&cotacao={$data['cotacao']}");


                curl_setopt_array($ch, [

                    // Equivalente ao -X:
                    CURLOPT_CUSTOMREQUEST => 'GET',

                    // Permite obter o resultado
                    CURLOPT_RETURNTRANSFER => 1,
                ]);

                $resposta = json_decode(curl_exec($ch), true);

                curl_close($ch);


                if ((isset($resposta[0]['Cabecalho']))) {
                    $item = $resposta[0]['Cabecalho'];
                    $data = [
                        'Id_Pdc' => $item['Id_Pdc'],
                        'CNPJ_Hospital' => $item['CNPJ_Hospital'],
                        'Nome_Hospital' => $item['Nome_Hospital'],
                        'Data_Envio_Mercado' => $item['Data_Envio_Mercado'],
                        'Data_Vencimento' => $item['Data_Vencimento'],
                    ];
                } else {
                    $data = null;
                }


                return $data;
            }

        }


    }

    private function getCotacaoBionexo($data)
    {
        $fornecedor = $this->db->where('id', $this->session->id_fornecedor)->get('fornecedores')->row_array();

        if (!empty($fornecedor['credencial_apoio'])) {

            $credenciais = json_decode($fornecedor['credencial_apoio'], true);

            if (!empty($credenciais['login']) && !empty($credenciais['password'])) {

                $ch = curl_init("https://pharmanexo.com.br/pharma_api/API/Request/Bionexo/getCotacao?id={$fornecedor['id']}&cotacao={$data['cotacao']}");


                curl_setopt_array($ch, [

                    // Equivalente ao -X:
                    CURLOPT_CUSTOMREQUEST => 'GET',

                    // Permite obter o resultado
                    CURLOPT_RETURNTRANSFER => 1,
                ]);

                $resposta = json_decode(curl_exec($ch), true);

                curl_close($ch);


                if (!empty($resposta)) {
                    $data = [
                        'Id_Pdc' => $resposta['Id_Pdc'],
                        'CNPJ_Hospital' => $resposta['CNPJ_Hospital'],
                        'Nome_Hospital' => $resposta['Nome_Hospital'],
                        'Data_Envio_Mercado' => $resposta['Data_Vencimento'] . " {$resposta['Hora_Vencimento']}",
                        'Data_Vencimento' => $resposta['Data_Vencimento'] . " {$resposta['Hora_Vencimento']}",
                    ];
                } else {
                    $data = null;
                }

                return $data;
            }

        }


    }

    private function getCotacaoSintese($data)
    {
        $fornecedor = $this->db->where('id', $this->session->id_fornecedor)->get('fornecedores')->row_array();

        $url = $this->config->item('db_config')['url_client'];
        if (isset($url['principal'])) $url = $url['principal'];

        $client = new SoapClient("{$url}?WSDL");

        $function = 'ObterCotacoes';
        $arguments = array(
            'ObterCotacoes' => array(
                'cnpj' => preg_replace("/\D+/", "", $fornecedor['cnpj']),
                'codigoCotacao' => $data['cotacao']
            )
        );

        libxml_disable_entity_loader(false);
        $options = array('location' => $url);
        $result = $client->__soapCall($function, $arguments, $options);

        $result = $result->ObterCotacoesResult;

        var_dump($result);
        exit();

        $xml = simplexml_load_string($result);

        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($result);


        var_dump($xml);
        exit();

    }

}
