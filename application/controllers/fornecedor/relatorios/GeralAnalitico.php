<?php
defined('BASEPATH') or exit('No direct script access allowed');
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

class GeralAnalitico extends CI_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/relatorios/GeralAnalitico');
        $this->views = 'fornecedor/relatorios/geral_analitico';

        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_compradores', 'comprador');
        $this->load->model('m_estados', 'estados');
    }

    public function index()
    {
        $page_title = 'Relatório Geral';

        $data['dataTable'] = "{$this->route}/solicitar";

        $data['header'] = $this->template->header([
            'title' => $page_title,
        ]);

        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();

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
                ]
            ]
        ]);

        $data['scripts'] = $this->template->scripts();
        $data['estados'] = $this->estados->find();
        $data['clientes'] = $this->comprador->find();


        $this->load->view("{$this->views}/main", $data);


    }

    public function solicitar()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();
            $ids_forns = [];

            $data = [
                'fornecedor' => $this->session->id_fornecedor,
                'data_inicio' => $post['dataini'],
                'data_fim' => $post['datafim'],
                'usuario' => $this->session->id_usuario
            ];

            $req = $this->_req($data);

            if ($req['type']) {
                $output = ['type' => 'success', 'message' => "Relatório solicitado com sucesso."];
            } else {
                $output = ['type' => 'error', 'message' => $req['message']];
            }


            $this->output->set_content_type('application/json')->set_output(json_encode($output));

        }


    }

    private function _req($data)
    {
        $url = 'http://reports2.pharmanexo.com.br/cotacoes-by-fornecedor';

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'X-AUTH-TOKEN: pharma@ish#2022!',
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);

            return ['type' => false, 'message' => $error_msg];

        } else {
            return ['type' => true];
        }

        curl_close($curl);

    }

}