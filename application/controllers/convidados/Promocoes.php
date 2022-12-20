<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Promocoes extends Conv_controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('convidados/promocoes');
        $this->views = 'convidados/promocoes';
    }

    public function index()
    {
        $page_title = "Promoções";

        $data['to_datatable'] = "{$this->route}/to_datatable/";
        $data['url_update'] = "{$this->route}/update";
        $data['header'] = $this->tmp_conv->header(['title' => 'Promoções',]);
        $data['navbar'] = $this->tmp_conv->navbar();
        $data['sidebar'] = $this->tmp_conv->sidebar();
        $data['heading'] = $this->tmp_conv->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'button',
                    'id' => 'btnPedido',
                    'url' => base_url("convidados/pedidos"),
                    'class' => 'btn-primary',
                    'icone' => 'fa-cart',
                    'label' => 'Ver Pedido'
                ],
            ]
        ]);
        $data['scripts'] = $this->tmp_conv->scripts();

        $pedidosAbertos = $this->db
            ->where('id_comprador', $_SESSION['dados']['id'])
            ->where('fechado', '0')
            ->get('conv_pedidos');

        if ($pedidosAbertos->num_rows() > 0) {
            $data['pedidoAberto'] = true;
        }

        $this->load->view("{$this->views}/main", $data);
    }

    public function to_datatable()
    {

        $r = $this->datatable->exec(
            $this->input->post(),
            'conv_promocoes pc',
            [
                ['db' => 'pc.id', 'dt' => 'id'],
                ['db' => 'pc.codigo', 'dt' => 'codigo'],
                ['db' => 'pc.descricao', 'dt' => 'descricao', 'formatter' => function ($d, $r) {
                    return "{$d} <br> <small>Ofertado por: {$r['fornecedor']}</small>";
                }],
                ['db' => 'pc.unidade', 'dt' => 'unidade'],
                ['db' => 'pc.marca', 'dt' => 'marca'],
                ['db' => 'pc.quantidade', 'dt' => 'quantidade'],
                ['db' => 'pc.lote', 'dt' => 'lote'],
                ['db' => 'pc.validade', 'dt' => 'validade', 'formatter' => function ($d) {
                    return date("d/m/Y", strtotime($d));
                }],
                ['db' => 'pc.preco', 'dt' => 'preco'],
                ['db' => 'pc.data_cadastro', 'dt' => 'data_cadastro', 'formatter' => function ($d) {
                    return date("d/m/Y", strtotime($d));
                }],
                ['db' => 'pc.situacao', 'dt' => 'situacao'],
                ['db' => 'f.id', 'dt' => 'id_fornecedor'],
                ['db' => 'f.razao_social', 'dt' => 'razao_social'],
                ['db' => 'f.nome_fantasia', 'dt' => 'fornecedor'],
            ],
            [
                ["fornecedores f", "f.id = pc.id_fornecedor"]
            ],
            "situacao = 1",
            "pc.codigo, pc.id_fornecedor"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

}

/* End of file: Promocoes.php */
