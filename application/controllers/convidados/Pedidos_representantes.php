<?php


class Pedidos_representantes extends Rep_controller
{

    private $route;
    private $urlselect2;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url("representantes/pedidos_representantes/");
        $this->urlselect2 = base_url("representantes/select2");
        $this->views = "representantes/pedidos/";

        $this->load->model('M_compradores', 'compradores');
        $this->load->model('M_estados', 'estado');
        $this->load->model('m_forma_pagamento', 'forma_pagamento');
        $this->load->model('m_forma_pagamento_fornecedor', 'forma_pagamento_fornecedor');
        $this->load->model('m_prazo_entrega', 'prazo_entrega');
        $this->load->model('m_valor_minimo', 'valor_minimo');
        $this->load->model('m_pedido_rep', 'pedido');
    }

    /**
     * @return object
     */
    public function index()
    {
        $page_title = "Pedidos Realizados";
        $data['header'] = $this->tmp_rep->header(['title' => $page_title ]);
        $data['navbar'] = $this->tmp_rep->navbar();
        $data['sidebar'] = $this->tmp_rep->sidebar();
        $data['heading'] = $this->tmp_rep->heading([
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
        $data['scripts'] = $this->tmp_rep->scripts();
        $data['datatable_src'] = "{$this->route}/to_datatable";
        $data['url_cancel'] = base_url("representantes/pedidos/cancelRequest");
        $data['url_update'] = base_url("representantes/pedidos/open/");
        $data['situacao'] = statusPedidoRepresentante();

        $this->load->view("{$this->views}main", $data);
    }

    public function to_datatable()
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'pedidos_representantes',
            [
                ['db' => 'pedidos_representantes.id', 'dt' => 'id'],
                ['db' => 'pedidos_representantes.prioridade', 'dt' => 'prioridade'],
                ['db' => 'compradores.razao_social', 'dt' => 'razao_social'],
                ['db' => 'compradores.cnpj', 'dt' => 'cnpj'],
                ['db' => 'pedidos_representantes.situacao', 'dt' => 'status_situacao'],
                ['db' => 'pedidos_representantes.id_fornecedor', 'dt' => 'id_fornecedor'],
                [
                    'db' => 'pedidos_representantes.data_abertura', 'dt' => 'data_abertura', 
                    'formatter' => function($d) { return date("d/m/Y H:i:s", strtotime($d)); }
                ],
                [
                    'db' => 'compradores.razao_social', 'dt' => 'razao_social', 
                    'formatter' => function($d, $r){ return "{$d} <br> <small>CNPJ: {$r['cnpj']}</small>"; }
                ],
                [
                    'db' => 'pedidos_representantes.situacao', 'dt' => 'situacao', 
                    'formatter' => function($d){ return statusPedidoRepresentante($d); }
                ],
                [
                    'db' => 'pedidos_representantes.comissao', 'dt' => 'valor_total', 
                    'formatter' => function($d, $r){
                        $soma = $this->db->query("SELECT sum(total) as total from pedidos_representantes_produtos where id_pedido = {$r['id']}")->row_array()['total'];
                        return (isset($soma)) ? number_format($soma, 2, ',', '.') : '0,00';
                    }
                ]
            ],
            [
                ['compradores', 'compradores.id = pedidos_representantes.id_comprador']
            ],
            'pedidos_representantes.id_representante = ' . $this->session->userdata('id_representante') . " and pedidos_representantes.id_fornecedor = {$this->session->id_fornecedor}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function exportar()
    {
        $this->db->select("pr.*, c.razao_social AS comprador");
        $this->db->from("pedidos_representantes pr");
        $this->db->join('compradores c', 'c.id = pr.id_comprador');
        $this->db->where('pr.id_representante', $this->session->id_representante);
        $this->db->where('pr.id_fornecedor', $this->session->id_fornecedor);
        $this->db->order_by('comprador ASC');

        $query = $this->db->get()->result_array();

        if ( count($query) < 1 ) {
            $query[] = [
                'comprador' => '',
                'data_abertura' => '',
                'situacao' => '',
                'valor_total' => '',
            ];
        } else {

            $data = [];
            foreach ($query as $k => $row) {

                $soma = $this->db->query("SELECT sum(total) as total from pedidos_representantes_produtos where id_pedido = {$row['id']}")->row_array()['total'];
                $total = (isset($soma)) ? number_format($soma, 2, ',', '.') : '0,00';
               
                $data[] = [
                    'comprador' => $row['comprador'],
                    'data_abertura' => date("d/m/Y H:i:s", strtotime($row['data_abertura'])),
                    'situacao' => statusPedidoRepresentante($row['situacao']),
                    'valor_total' => $total,
                ];
            }

            $query = $data;
        }

        $dados_page = ['dados' => $query , 'titulo' => 'Pedidos'];

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