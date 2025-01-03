<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RankingProdutosVendidos extends CI_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/relatorios/RankingProdutosVendidos');
        $this->views = 'fornecedor/relatorios/ranking_produtos';

        $this->load->model('m_pedido', 'pedido');
        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_status_ordem_compra', 'status');
        $this->load->model('m_estados', 'estados');
    }

    public function index()
    {
        $page_title = 'Ranking de produtos mais vendidos';

        $data['dataTable'] = "{$this->route}/getDatasource";
        $data['options'] = $this->status->getStatus();
        $data['url_detalhes'] = "{$this->route}/detalhes/";
        $data['url_export'] = "{$this->route}/exportar";

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnVoltar',
                   'url' => "javascript:history.back(1)",
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Retornar'
                ],
                [
                    'type' => 'submit',
                    'id' => 'btnSave',
                    'form' => 'formFilter',
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel',
                    'label' => 'Exportar Excel'
                ]
            ]
        ]);

        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();

        $data['heading'] = $this->template->heading([
            'page_title' => $page_title
        ]);

        $data['scripts'] = $this->template->scripts();

        $data['estados'] = $this->estados->find();

        $this->load->view("{$this->views}/main", $data);
    }


    public function getDatasource()
    {

        $post = $this->input->post();

        $where = "os.id_fornecedor = {$this->session->id_fornecedor} AND ";
        if ($this->session->has_userdata('id_matriz')) {
            if ($this->session->has_userdata('id_matriz')) {
                $lojas = $this->fornecedor->find("id, nome_fantasia", "id_matriz = {$this->session->id_matriz}");
                $arrLojas = [];
                foreach ($lojas as $loja) {
                    $arrLojas[] = $loja['id'];
                }
                $forn = implode(",", $arrLojas);

                $where = "os.id_fornecedor in ({$forn}) AND ";

            }
        }

        $where .= "osp.codigo is not null AND ";

        if (isset($post['data_ini']) && isset($post['data_fim'])) {
            $dataini = dbDateFormat($post['data_ini']);
            $datafim = dbDateFormat($post['data_fim']);

            $where .= "date(os.Dt_Ordem_Compra) between '{$dataini}' and '{$datafim}' AND ";
        }

        if (isset($post['estados']) && $post['estados'] != "") {
            $estados = explode(",", $post['estados']);
            $estadosWhere = "";

            foreach ($estados as $estado){
                $estadosWhere .= "'{$estado}', ";
            }

            $estadosWhere = rtrim($estadosWhere, ', ');
            $where .= "c.estado in ({$estadosWhere}) AND ";
        }

        $where = rtrim($where, 'AND ');

        $r = $this->datatable->exec(
            $post,
            'ocs_sintese_produtos osp',
            [
                ['db' => 'pc.codigo', 'dt' => 'codigo'],
                ['db' => 'pc.nome_comercial', 'dt' => 'nome_comercial'],
                ['db' => 'pc.marca', 'dt' => 'marca'],
                ['db' => 'count(0)', 'dt' => 'qtd_produtos'],
                ['db' => 'os.id_fornecedor', 'dt' => 'id_fornecedor'],
                ['db' => 'sum(osp.Qt_Produto)', 'dt' => 'total_vendido'],
            ],
            [
                ['ocs_sintese os', 'osp.id_ordem_compra = os.id'],
                ['compradores c', 'c.id = os.id_comprador'],
                ['produtos_catalogo pc', 'os.id_fornecedor = pc.id_fornecedor and pc.codigo = osp.codigo'],
                ['fornecedores f', 'f.id = pc.id_fornecedor'],
            ],
            $where,
            "pc.codigo, pc.id_fornecedor"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function exportar()
    {

        $post = $this->input->post();


        $where = "os.id_fornecedor = {$this->session->id_fornecedor} AND ";
        if ($this->session->has_userdata('id_matriz')) {
            if ($this->session->has_userdata('id_matriz')) {
                $lojas = $this->fornecedor->find("id, nome_fantasia", "id_matriz = {$this->session->id_matriz}");
                $arrLojas = [];
                foreach ($lojas as $loja) {
                    $arrLojas[] = $loja['id'];
                }
                $forn = implode(",", $arrLojas);

                $where = "os.id_fornecedor in ({$forn}) AND ";

            }
        }

        if (isset($post['estados']) && $post['estados'] != "") {
            $estados = explode(",", $post['estados']);
            $estadosWhere = "";

            foreach ($estados as $estado){
                $estadosWhere .= "'{$estado}', ";
            }

            $estadosWhere = rtrim($estadosWhere, ', ');
            $where .= "c.estado in ({$estadosWhere}) AND ";
        }


        if (isset($post['data_ini']) && isset($post['data_fim'])) {
            $dataini = dbDateFormat($post['data_ini']);
            $datafim = dbDateFormat($post['data_fim']);

            $where .= "date(os.Dt_Ordem_Compra) between '{$dataini}' and '{$datafim}' AND ";
        }


        $where = rtrim($where, 'AND ');


        $this->db->select("
           pc.codigo         AS codigo,
           pc.nome_comercial AS nome_comercial,
           pc.marca          AS marca,
           count(0)              AS qtd_produtos,
           os.id_fornecedor  AS id_fornecedor,
           sum(osp.Qt_Produto)   AS total_vendido");

        $this->db->from("ocs_sintese_produtos osp");
        $this->db->join('ocs_sintese os', 'osp.id_ordem_compra = os.id');
        $this->db->join('produtos_catalogo pc', 'os.id_fornecedor = pc.id_fornecedor and pc.codigo = osp.codigo');
        $this->db->join('fornecedores f', 'ON f.id = pc.id_fornecedor');
        $this->db->join('compradores c', 'ON c.id = os.id_comprador');

        $this->db->where($where);
        $this->db->group_by("pc.codigo, pc.id_fornecedor");
        $this->db->order_by("sum(osp.Qt_Produto) DESC");

        $query = $this->db->get()->result_array();
        

        if (count($query) < 1) {
            $query[] = [
                'codigo' => '',
                'nome_comercial' => '',
                'marca' => '',
                'qtd_produtos' => '',
                'total_vendido' => ''
            ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'Ranking de produtos vendidos'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ($exportar['status'] == false) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }
}

/* End of file: Vendas_realizadas.php */
