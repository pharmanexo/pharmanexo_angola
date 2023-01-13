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


                return (isset($resposta[0]['Cabecalho'])) ? $resposta[0]['Cabecalho'] : null;
            }

        }


    }

}
