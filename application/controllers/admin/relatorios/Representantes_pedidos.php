<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Representantes_pedidos extends Admin_controller
{
    private $route, $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('admin/relatorios/representantes_pedidos');
        $this->views = 'admin/relatorios/representantes_pedidos';

        $this->load->model('m_representante', 'representante');
        $this->load->model('m_pedido_rep', 'pedidos');
        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_compradores', 'comprador');
    }

    public function index()
    {
        $page_title = "Representantes Pedidos";

        $data['datasource'] = "{$this->route}/datatables";
        $data['fornecedores'] = $this->fornecedor->find('id, cnpj, razao_social');
        $data['representantes'] = $this->representante->find('*');
        $data['header'] = $this->template->header([ 'title' => $page_title ]);
        $data['navbar']  = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title
        ]);
        $data['scripts'] = $this->template->scripts();
        $data['url_update'] = $this->route . '/detalhes/'; 

        $this->load->view("{$this->views}/main", $data);
    }

    public function detalhes($id_pedido)
    {
        $page_title = "Pedido #{$id_pedido}";

        $data['datatables'] = "{$this->route}/datatables_produtos/{$id_pedido}";
        $data['header'] = $this->template->header([ 'title' => $page_title ]);
        $data['navbar']  = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnback',
                    'url' => $this->route,
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ]
            ]
        ]);

        $data['scripts'] = $this->template->scripts();

        $data['pedido'] = $this->pedidos->findById($id_pedido);

        $data['pedido']['valor_minimo'] = number_format($data['pedido']['valor_minimo'], 4, ',', '.');

        $data['pedido']['total'] = $this->db->select('SUM(total) as total')
            ->where('id_pedido = 92')
            ->get('pedidos_representantes_produtos')
            ->row_array()['total'];

        $data['pedido']['total'] = number_format($data['pedido']['total'], 4, ',', '.');

        $data['fornecedor'] = $this->fornecedor->findById($data['pedido']['id_fornecedor']);
        $data['representante'] = $this->representante->findById($data['pedido']['id_representante']);
        $data['comprador'] = $this->comprador->findById($data['pedido']['id_comprador']);

        $this->load->view("{$this->views}/detalhes", $data);
    }

    public function datatables()
    {
        $d = $this->datatable->exec(
            $this->input->post(),
            'pedidos_representantes pr', 
            [
                ['db' => 'pr.id', 'dt' => 'id'],
                ['db' => 'rep.id', 'dt' => 'id_representante'],
                ['db' => 'forn.id', 'dt' => 'id_fornecedor'],
                ['db' => 'rep.nome', 'dt' => 'representante'],
                ['db' => 'comp.cnpj', 'dt' => 'cnpj_comprador'],
                [
                    'db' => 'comp.razao_social',
                    'dt' => 'comprador',
                    'formatter' => function($value, $row) {
                        return "{$row['cnpj_comprador']} - {$value}";
                    }
                ],
                ['db' => 'forn.cnpj', 'dt' => 'cnpj_fornecedor'],
                [
                    'db' => 'forn.razao_social',
                    'dt' => 'fornecedor',
                    'formatter' => function($value, $row) {
                        return "{$row['cnpj_fornecedor']} - {$value}";
                    }
                ],
                ['db' => 'pr.situacao', 'dt' => 'situacao', ],
                [
                    'db' => 'pr.situacao',
                    'dt' => 'lbl_situacao',
                    'formatter' => function($value){
                        return statusPedidoRepresentante($value);
                    }
                ],
                ['db' => 'pr.uf_comprador', 'dt' => 'uf_comprador'],
            ],
            [
                ['representantes rep', 'rep.id = pr.id_representante'],
                ['compradores comp', 'comp.id = pr.id_comprador'],
                ['fornecedores forn', 'forn.id = pr.id_fornecedor'],
            ]
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($d));
    }

    public function datatables_produtos($id_pedido)
    {
        $d = $this->datatable->exec(
            $this->input->post(),
            'pedidos_representantes_produtos prp', 
            [
                ['db' => 'prp.id_pedido', 'dt' => 'id_pedido'],
                ['db' => 'prp.cd_produto_fornecedor', 'dt' => 'cd_produto_fornecedor'],
                ['db' => 'pc.descricao', 'dt' => 'descricao'],
                [
                    'db' => 'prp.preco_unidade',
                    'dt' => 'preco_unidade',
                    'formatter' => function($value, $row) {
                        return number_format($value, 4, ',', '.');
                    }
                ],
                ['db' => 'prp.quantidade_solicitada', 'dt' => 'quantidade_solicitada'],
                ['db' => 'prp.desconto', 'dt' => 'desconto'],
                [
                    'db' => 'prp.preco_desconto',
                    'dt' => 'preco_desconto',
                    'formatter' => function($value, $row) {
                        return number_format($value, 4, ',', '.');
                    }
                ],
                [
                    'db' => 'prp.total',
                    'dt' => 'total',
                    'formatter' => function($value, $row) {
                        return number_format($value, 4, ',', '.');
                    }
                ],
            ],
            [
                ['pedidos_representantes pr', 'pr.id = prp.id_pedido'],
                ['produtos_catalogo pc', 'pc.codigo = prp.cd_produto_fornecedor AND pc.id_fornecedor=pr.id_fornecedor', 'left'],
            ],

            "prp.id_pedido={$id_pedido}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($d));
    }
}

/* End of file: Clientes.php */
