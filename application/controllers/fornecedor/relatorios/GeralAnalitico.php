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

        $this->route = base_url('fornecedor/relatorios/geralanalitico');
        $this->views = 'fornecedor/relatorios/geral_analitico';

        $this->load->model('m_pedido', 'pedido');
        $this->load->model('m_status_ordem_compra', 'status');
        $this->load->model('M_relatorios', 'relatorios');
        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_compradores', 'comprador');
        $this->load->model('m_estados', 'estados');
    }

    public function index()
    {
        $page_title = 'Relatório Geral';

        $data['dataTable'] = "{$this->route}/getData";
        $data['url_detalhes'] = "{$this->route}/detalhes/";

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

      /*  if ($this->session->has_userdata('id_matriz')) {

            $data['selectMatriz'] = $this->fornecedor->find("id, nome_fantasia", "id_matriz = {$this->session->id_matriz}");
        }*/

        $this->load->view("{$this->views}/main", $data);


    }


    public function getData($e = null)
    {

        $post = $this->input->post();

        $filtros = [
            'dataini' => isset($post['dataini']) ? $post['dataini'] : '',
            'datafim' =>  isset($post['datafim']) ? $post['datafim'] : '',
            'id_cliente' =>  isset($post['id_clientes']) ? $post['id_clientes'] : '',
            'page' => 1
        ];


        $data = $this->relatorios->getRelGeral($filtros);

        $dados_page = ['dados' => $data, 'titulo' => 'Relatório Gerencial'];
        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

    }

    public function export()
    {


    }

    private function consulta($post)
    {


    }
}