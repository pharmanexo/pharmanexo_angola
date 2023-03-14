<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pedidos extends MY_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/relatorios/pedidos');
        $this->views = 'fornecedor/relatorios/pedidos';

        $this->load->model('m_pedido', 'pedido');
        $this->load->model('m_status_ordem_compra', 'status');
    }

    public function index()
    {
        $page_title = 'Relatórios de Pedidos';

        $data['dataTable']    = "{$this->route}/getDatasource";
        $data['options']      = $this->status->getStatus();
        $data['url_detalhes'] = "{$this->route}/detalhes/";

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
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel', 
                    'label' => 'Exportar Excel'
                ],
            ]
        ]);

        $data['navbar']  = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();

        $data['heading'] = $this->template->heading([
            'page_title' => $page_title
        ]);

        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/main", $data);
    }

    public function detalhes($id)
    {
        $data['row']      = $this->pedido->get_row($id);
        $data['produtos'] = $this->pedido->get_itens("*", "id_pedido = {$id}");

        #var_dump($data['produtos']);exit();

        $page_title = "Pedido: #{$data['row']['id']}";

        $data['header'] = $this->template->header([
            'title' => $page_title
        ]);

        $data['navbar']  = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();

        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type'  => 'button',
                    'id'    => 'btnPrintAll',
                    'url'   => '#',
                    'class' => 'btn-primary',
                    'icone' => 'fa-print',
                    'label' => 'Imprimir'
                ],
                [
                    'type'  => 'a',
                    'id'    => 'btnBack',
                    'url'   => $this->route,
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ],
            ]
        ]);

        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/detail", $data);
    }

    public function getDatasource()
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'vw_ordens_compra',
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'Dt_Ordem_Compra', 'dt' => 'Dt_Ordem_Compra', 'formatter' => function ($d) {
                    return date('d/m/Y', strtotime($d));
                }],
                ['db' => 'Cd_Ordem_Compra',         'dt' => 'Cd_Ordem_Compra'],
                ['db' => 'cnpj',         'dt' => 'cnpj'],
                ['db' => 'razao_social', 'dt' => 'razao_social'],
                ['db' => 'cidade',       'dt' => 'cidade'],
                ['db' => 'estado',           'dt' => 'estado'],
                ['db' => 'total_itens',  'dt' => 'total_itens'],
                ['db' => 'id_integrador',  'dt' => 'id_integrador'],
                ['db' => 'integrador',  'dt' => 'integrador'],
                ['db' => 'total',        'dt' => 'total', 'formatter' => function($d){
                    return 'R$ ' . number_format($d, '2', ',', '.');
                }],
            ],
            null,
            'id_fornecedor = ' . $this->session->userdata('id_fornecedor')
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function exportar()
    {
        $this->db->select("
            DATE_FORMAT(data_criacao, '%d/%m/%Y') AS data_criacao, 
            cnpj,
            razao_social,
            total AS total_pedido,
            status");
        $this->db->from("vw_pedidos");
        $this->db->where("id_fornecedor", $this->session->id_fornecedor);

        $query = $this->db->get()->result_array();

        if ( count($query) < 1 ) {
            $query[] = [
                'data_criacao' => '',
                'cnpj' => '',
                'razao_social' => '',
                'total_pedido' => '',
                'status' => ''
            ];
        } else {

            foreach ($query as $k => $row) {
                
                $query[$k]['status'] = getStatusPedidos($row['status']);
            }
        }

        $dados_page = ['dados' => $query, 'titulo' => 'pedidos'];

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
