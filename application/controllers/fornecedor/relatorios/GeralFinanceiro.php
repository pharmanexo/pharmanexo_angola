<?php
defined('BASEPATH') or exit('No direct script access allowed');
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

class GeralFinanceiro extends CI_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/relatorios/GeralFinanceiro');
        $this->views = 'fornecedor/relatorios/geral_financeiro';

        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_compradores', 'comprador');
        $this->load->model('m_estados', 'estados');
    }

    public function index()
    {
        $page_title = 'Relatório Geral Financeiro';

        $data['dataTable'] = "{$this->route}/solicitar";

        $data['header'] = $this->template->header([
            'title' => $page_title,
        ]);

        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();

        $data['heading'] = $this->template->heading([
            'page_title' => $page_title
        ]);

        $data['scripts'] = $this->template->scripts();
        $data['estados'] = $this->estados->find();
        $data['clientes'] = $this->comprador->find();

        $data['reportsHistory'] = $this->getHistoryReports();

      /*  var_dump($data['reportsHistory']);
        exit();*/

        $this->load->view("{$this->views}/main", $data);


    }

    public function solicitar()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();
            $ids_forns = [];

            if (isset($_SESSION['id_matriz']) && $_SESSION['id_matriz'] > 0) {
                $fornecedores = $this->db
                    ->select('id')
                    ->where('id_matriz', $_SESSION['id_matriz'])
                    ->get('fornecedores')
                    ->result_array();

                foreach ($fornecedores as $f) {
                    $ids_forns[] = $f['id'];
                }
            } else {
                $ids_forns[] = $this->session->id_fornecedor;
            }


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

        $url = 'http://reports2.pharmanexo.com.br/oncoprod-geral-produtos';

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

    private function getHistoryReports()
    {
        $url = "http://reports2.pharmanexo.com.br/search/by-fornecedor/{$this->session->id_fornecedor}";

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
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'X-AUTH-TOKEN: pharma@ish#2022!',
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);

    }

}