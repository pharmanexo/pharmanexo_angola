<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produtos_cotados extends MY_Controller
{
    private $route;
    private $views;
    private $oncoprod;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/relatorios/produtos_cotados');
        $this->views = 'fornecedor/relatorios/produtos_cotados';

        $this->oncoprod = explode(',', ONCOPROD);
    }

    public function index()
    {
        $page_title = 'Relatório de Produtos mais cotados';

        $data['to_datatable'] = "{$this->route}/datatables";

        if ( in_array($this->session->id_fornecedor, $this->oncoprod) ) {

            $oncoprod = implode(',', $this->oncoprod);
            $where = "id_fornecedor in ({$oncoprod})";
        } else {

            $where = "id_fornecedor = {$this->session->id_fornecedor}";
        }

        $query = "SELECT id_pfv, produto, total, FORMAT(preco_total, 4 , 'de_DE') preco_total, qtd_total
            FROM `vw_produtos_cotados`
            WHERE {$where}
            order by total desc
            LIMIT 20";

        $data['mais_cotados'] = $this->db->query($query)->result_array();
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

    public function datatables()
    {
        if ( in_array($this->session->id_fornecedor, $this->oncoprod) ) {

            $oncoprod = implode(',', $this->oncoprod);
            $where = "id_fornecedor in ({$oncoprod})";
        } else {

            $where = "id_fornecedor = {$this->session->id_fornecedor}";
        }

        $r = $this->datatable->exec(
            $this->input->post(),
            'vw_produtos_cotados',
            [
                ['db' => 'id_pfv', 'dt' => 'id_pfv'],
                ['db' => 'produto', 'dt' => 'produto'],
                ['db' => 'total', 'dt' => 'total'],
                ['db' => 'qtd_total', 'dt' => 'qtd_total'],
                ['db' => 'id_fornecedor', 'dt' => 'id_fornecedor'],
                ['db' => 'preco_total', 'dt' => 'preco_total', 'formatter' => function ($value, $row) {
                    
                    return number_format($value, 4, ",", ".");
                }]
            ],
            null,
            "{$where}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function exportar()
    {
        if ( in_array($this->session->id_fornecedor, $this->oncoprod) ) {

            $oncoprod = implode(',', $this->oncoprod);
            $where = "id_fornecedor in ({$oncoprod})";
        } else {

            $where = "id_fornecedor = {$this->session->id_fornecedor}";
        }

        $this->db->select("
            id_pfv AS codigo,
            produto,
            total,
            FORMAT(preco_total, 4, 'de_DE') AS preco_total,
            qtd_total AS qtd_slicitada_total
            ");
        $this->db->from("vw_produtos_cotados");
        $this->db->where("{$where}");
        $this->db->limit(20);


        $query = $this->db->get()->result_array();

        if ( count($query) < 1 ) {
            $query[] = [
                'codigo' => '',
                'produto' => '',
                'total' => '',
                'preco_total' => '',
                'qtd_slicitada_total' => ''
                
            ];
        } 

        $dados_page = ['dados' => $query, 'titulo' => 'produtos'];

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

/* End of file: Vendas_realizadas.php */
