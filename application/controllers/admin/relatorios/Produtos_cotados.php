<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produtos_cotados extends Admin_controller
{
    private $route;
    private $views;
    private $oncoprod;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('admin/relatorios/produtos_cotados');
        $this->views = 'admin/relatorios/produtos_cotados';

        $this->load->model('m_fornecedor', 'fornecedor');
        $this->oncoprod = explode(',', ONCOPROD);
    }

    public function index()
    {
        $page_title = 'Relatório de Produtos mais cotados';

        $data['fornecedores'] = $this->fornecedor->find('*');
        $data['url_mcotados'] = "{$this->route}/mais_cotados";
        $data['url_exportar'] = "{$this->route}/exportar/";
        $data['header'] = $this->template->header(['title' => $page_title ]);
        $data['navbar']  = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel',
                    'label' => 'Exportar Excel'
                ],
            ]
        ]);
        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/main", $data);
    }


    public function mais_cotados()
    {
        if ( $this->input->is_ajax_request() ) {

            $fornecedor = $this->input->post()['id_fornecedor'];

            if ( in_array($fornecedor, $this->oncoprod) ) {

                $oncoprod = implode(',', $this->oncoprod);
                $where = "id_fornecedor in ({$oncoprod})";
            } else {

                $where = "id_fornecedor = {$fornecedor}";
            }


            $query = "SELECT id_pfv, produto, FORMAT(preco_unit, 4 , 'de_DE') preco_unit, total, FORMAT(preco_total, 4 , 'de_DE') preco_total, qtd_total
                FROM `vw_produtos_cotados`
                WHERE {$where}
                order by total desc
                LIMIT 20";

            $resp = $this->db->query($query)->result_array();



            if ( !empty($resp) ) {

                $warning = ['type' => 'success', 'data' => $resp];
            } else {

                $warning = ['type' => 'warning', 'data' => null];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
    }

    /**
     * Gera arquivo excel do datatable
     *
     * @return  downlaod file
     */
    public function exportar($id_fornecedor = null)
    {

        if ( isset($id_fornecedor)) {

            if ( in_array($id_fornecedor, $this->oncoprod) ) {

                $oncoprod = implode(',', $this->oncoprod);
                $where = "id_fornecedor in ({$oncoprod})";
            } else {

                $where = "id_fornecedor = {$id_fornecedor}";
            }

            $this->db->select("
                id_pfv AS codigo,
                produto,
                total,
                FORMAT(preco_total, 4 , 'de_DE') AS preco_total,
                qtd_total AS qtd_solicitada_total");
            $this->db->from("vw_produtos_cotados");
            $this->db->where("{$where}");
            $this->db->order_by("total desc");
            $this->db->limit(20);

            $query = $this->db->get()->result_array();

        } else {

            $query = [];
        }

        if ( count($query) < 1 ) {
            $query[] = [
                'codigo' => '',
                'produto' => '',
                'total' => '',
                'preco_total' => '',
                'qtd_solicitada_total' => ''
            ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'Produtos'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }
}

/* End of file: Produtos_cotados.php */
