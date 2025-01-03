<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RankingCompradores extends CI_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/relatorios/rankingCompradores');
        $this->views = 'fornecedor/relatorios/rankingCompradores';

        $this->load->model('m_pedido', 'pedido');
        $this->load->model('m_status_ordem_compra', 'status');
        $this->load->model('M_relatorios', 'relatorios');
        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_compradores', 'comprador');
        $this->load->model('m_estados', 'estados');
    }

    public function index()
    {

        $page_title = 'Ranking maiores compradores';

        $data['dataTable'] = "{$this->route}/getData";
        $data['url_detalhes'] = "{$this->route}/detalhes/";

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
                ],
            ]
        ]);

        $data['scripts'] = $this->template->scripts();
        $data['estados'] = $this->estados->find();

        if ($this->session->has_userdata('id_matriz')) {

            $data['selectMatriz'] = $this->fornecedor->find("id, nome_fantasia", "id_matriz = {$this->session->id_matriz}");
        }

        $this->load->view("{$this->views}/main", $data);


    }


    public function getData($e = null)
    {
        $export = [];
        $post = $this->input->post();


        $consulta = $this->consulta($post);

        $page_title = 'Ranking maiores compradores';

        $data['dataTable'] = "{$this->route}/getData";
        $data['url_export'] = "{$this->route}/export/";

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

        if ($this->session->has_userdata('id_matriz')) {

            $data['selectMatriz'] = $this->fornecedor->find("id, nome_fantasia", "id_matriz = {$this->session->id_matriz}");
        }

        $data['consulta'] = $consulta;
        $data['post'] = $post;
        $ufSelect = (isset($post['estados'])) ? explode(',', $post['estados']) : [];

        foreach ($data['estados'] as $k => $estado){
            foreach ($ufSelect as $item){
                if ($estado['uf'] == $item){
                    $data['estados'][$k]['selected'] = true;
                }
            }
        }


        $this->load->view("{$this->views}/main", $data);

    }

    public function export()
    {
        $post = $this->input->post();

        $consulta = $this->consulta($post);


        $dados_page = ['dados' => $consulta, 'titulo' => 'Ranking de compradores'];
        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

    }

    private function consulta($post)
    {

        $export = [];

        $filtros = [
            'id_fornecedor' => [$this->session->id_fornecedor],
            'dataini' => date('Y-m-d', strtotime('-30 days')),
            'datafim' => date('Y-m-d', time()),
        ];


        if (isset($post['id_fornecedor']) && $post['id_fornecedor'] == 'ALL') {
            if ($this->session->has_userdata('id_matriz')) {
                $lojas = $this->fornecedor->find("id, nome_fantasia", "id_matriz = {$this->session->id_matriz}");
                $arrLojas = [];
                foreach ($lojas as $loja) {
                    $arrLojas[] = $loja['id'];
                }

                if (!empty($arrLojas)) {
                    $filtros['id_fornecedor'] = $arrLojas;
                }
            }
        }

        if (isset($post['dataini']) && !empty($post['dataini'])) {
            unset($filtros['dataini']);
        }

        if (isset($post['datafim']) && !empty($post['datafim'])) {
            unset($filtros['datafim']);
        }

        if (isset($post['estados']) && !empty($post['estados'])){
            $estados = explode(",", $post['estados']);
           $filtros['estados'] = $estados;
        }

        $filtros = array_merge($post, $filtros);

        return $consulta = $this->relatorios->getRankingVendasCompradores($filtros);
    }
}