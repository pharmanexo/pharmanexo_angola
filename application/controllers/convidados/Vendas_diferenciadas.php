<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Vendas_diferenciadas extends Rep_controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('representantes/vendas_diferenciadas');
        $this->views = 'representantes/vendas_diferenciadas';
    }

    /**
     * exibe a view fornecedor/vendas_diferenciadas/main.php
     *
     * @return view
     */
    public function index()
    {
        $page_title = "Vendas Diferenciadas";

        $data['to_datatable_estado'] = "{$this->route}/to_datatable_estado/";
        $data['to_datatable_cnpj'] = "{$this->route}/to_datatable_cnpj/";
        $data['header'] = $this->tmp_rep->header([ 'title' => 'Vendas Diferenciadas' ]);
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

        $this->load->view("{$this->views}/main", $data);
    }

    public function to_datatable_estado()
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'vendas_diferenciadas',
            [
                ['db' => 'vendas_diferenciadas.id', 'dt' => 'id'],
                ['db' => 'vendas_diferenciadas.codigo', 'dt' => 'codigo'],
                ['db' => 'vendas_diferenciadas.desconto_percentual', 'dt' => 'desconto_percentual'],
                ['db' => 'vendas_diferenciadas.comissao', 'dt' => 'comissao'],
                ['db' => 'vendas_diferenciadas.quantidade', 'dt' => 'quantidade'],
                ['db' => 'vendas_diferenciadas.lote', 'dt' => 'lote'],
                ['db' => 'vendas_diferenciadas.dias', 'dt' => 'dias'],
                ['db' => 'produtos_catalogo.nome_comercial', 'dt' => 'produto_descricao'],
                ['db' => 'vendas_diferenciadas.regra_venda', 'dt' => 'regra_venda'],
                ['db' => 'vendas_diferenciadas.id_fornecedor', 'dt' => 'id_fornecedor'],
                ['db' => 'estados.id', 'dt' => 'id_estado'],
                [
                    'db' => 'vw_produtos_precos.preco_unitario',
                    'dt' => 'preco',
                    'formatter' => function ($value, $row) {

                        return number_format($value, 4, ',', '.');
                    }
                ],
                [
                    'db' => 'vw_produtos_precos.preco_unitario',
                    'dt' => 'preco_desconto',
                    'formatter' => function ($value, $row) {

                        $preco = $value - ($value * ($row['desconto_percentual'] / 100));
                        return number_format($preco, 4, ',', '.');
                    }
                ],
                ['db' => 'estados.uf', 'dt' => 'uf'],
                [
                    'db' => 'estados.descricao',
                    'dt' => 'descricao',
                    'formatter' => function ($value, $row) {
                        return "{$row['uf']} - {$value}";
                    }
                ],
                [
                    'db' => 'vendas_diferenciadas.regra_venda',
                    'dt' => 'status_regra_venda',
                    'formatter' => function ($value, $row) {
                        return status_regra_venda($value);
                    }
                ]
            ],
            [
                ['estados', 'estados.id = vendas_diferenciadas.id_estado', 'LEFT'],
                ['produtos_catalogo', 'produtos_catalogo.codigo = vendas_diferenciadas.codigo', 'LEFT'],
                ['vw_produtos_precos', 'produtos_catalogo.codigo = vw_produtos_precos.codigo', 'LEFT']
            ],
            "produtos_catalogo.id_fornecedor = {$this->session->userdata('id_fornecedor')} AND vendas_diferenciadas.promocao = 0 
            and vendas_diferenciadas.id_estado is not null AND vendas_diferenciadas.id_fornecedor = {$this->session->userdata('id_fornecedor')}",
            "codigo, id_estado"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function to_datatable_cnpj()
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'vendas_diferenciadas',
            [
                ['db' => 'vendas_diferenciadas.id', 'dt' => 'id'],
                ['db' => 'vendas_diferenciadas.codigo', 'dt' => 'codigo'],
                ['db' => 'vendas_diferenciadas.desconto_percentual', 'dt' => 'desconto_percentual'],
                ['db' => 'vendas_diferenciadas.comissao', 'dt' => 'comissao'],
                ['db' => 'vendas_diferenciadas.quantidade', 'dt' => 'quantidade'],
                ['db' => 'vendas_diferenciadas.lote', 'dt' => 'lote'],
                ['db' => 'vendas_diferenciadas.dias', 'dt' => 'dias'],
                ['db' => 'produtos_catalogo.nome_comercial', 'dt' => 'produto_descricao', ],
                ['db' => 'vendas_diferenciadas.regra_venda', 'dt' => 'regra_venda'],
                ['db' => 'compradores.cnpj', 'dt' => 'cnpj'],
                [
                    'db' => 'vw_produtos_precos.preco_unitario',
                    'dt' => 'preco',
                    'formatter' => function ($value, $row) {

                        return number_format($value, 4, ',', '.');
                    }
                ],
                [
                    'db' => 'vw_produtos_precos.preco_unitario',
                    'dt' => 'preco_desconto',
                    'formatter' => function ($value, $row) {

                        $preco = $value - ($value * ($row['desconto_percentual'] / 100));
                        return number_format($preco, 4, ',', '.');
                    }
                ],
                [
                    'db' => 'compradores.razao_social',
                    'dt' => 'razao_social',
                    'formatter' => function ($value, $row) {
                        return "{$row['cnpj']} - {$value}";
                    }
                ],
                [
                    'db' => 'vendas_diferenciadas.regra_venda',
                    'dt' => 'status_regra_venda',
                    'formatter' => function ($value, $row) {
                        return status_regra_venda($value);
                    }
                ]
            ],
            [
                ['compradores', 'compradores.id = vendas_diferenciadas.id_cliente', 'LEFT'],
                ['produtos_catalogo', 'produtos_catalogo.codigo = vendas_diferenciadas.codigo', 'LEFT'],
                ['vw_produtos_precos', 'produtos_catalogo.codigo = vw_produtos_precos.codigo', 'LEFT']
            ],
            "produtos_catalogo.id_fornecedor = {$this->session->userdata('id_fornecedor')} AND vendas_diferenciadas.promocao = 0 
            and vendas_diferenciadas.id_cliente is not null AND vendas_diferenciadas.id_fornecedor = {$this->session->userdata('id_fornecedor')} AND vw_produtos_precos.id_estado is null",
            "codigo, id_cliente"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function exportar()
    {
        $this->db->select("
            vd.id_estado, 
            vd.codigo, 
            vd.desconto_percentual, 
            pc.nome_comercial AS produto, 
            FORMAT(vpp.preco_unitario, 4 , 'de_DE') AS preco, 
            FORMAT( (vpp.preco_unitario - (vpp.preco_unitario * vd.desconto_percentual / 100)), 4 , 'de_DE') AS preco_desconto,
            vd.quantidade, 
            vd.dias, 
            CONCAT(e.uf, '-', e.descricao) AS estado,
            (CASE 
                WHEN vd.regra_venda = 0 THEN 'Todos'
                WHEN vd.regra_venda = 1 THEN 'Manual'
                WHEN vd.regra_venda = 2 THEN 'Automático'
                WHEN vd.regra_venda = 3 THEN 'Manual e Automático'
                WHEN vd.regra_venda = 4 THEN 'Distribuidor x Distribuidor'
                WHEN vd.regra_venda = 5 THEN 'Distribuidor x Manual'
                WHEN vd.regra_venda = 6 THEN ' Distribuidor x Automático' END) regra_venda");
        $this->db->from("vendas_diferenciadas vd");
        $this->db->join('estados e', "e.id = vd.id_estado", "left");
        $this->db->join('produtos_catalogo pc', "pc.codigo = vd.codigo", "left");
        $this->db->join('vw_produtos_precos vpp', "pc.codigo = vpp.codigo", "left");
        $this->db->where('pc.id_fornecedor', $this->session->id_fornecedor);
        $this->db->where('vpp.id_fornecedor', $this->session->id_fornecedor);
        $this->db->where('vd.id_fornecedor', $this->session->id_fornecedor);
        $this->db->where('vd.promocao', 0);
        $this->db->where('vd.id_estado is not null');
        $this->db->group_by("codigo, id_estado");
        $this->db->order_by("produto ASC");

        $query_estados = $this->db->get()->result_array();

        $this->db->select("
            vd.id_cliente, 
            vd.codigo, 
            vd.desconto_percentual, 
            pc.nome_comercial AS produto, 
            FORMAT(vpp.preco_unitario, 4 , 'de_DE') AS preco, 
            FORMAT( (vpp.preco_unitario - (vpp.preco_unitario * vd.desconto_percentual / 100)), 4 , 'de_DE') AS preco_desconto,
            vpp.preco_unitario AS preco_desconto, 
            vd.quantidade, 
            vd.dias,  
            CONCAT(c.cnpj, '-', c.razao_social), 'c.razao_social' AS cliente,
            (CASE 
                WHEN vd.regra_venda = 0 THEN 'Todos'
                WHEN vd.regra_venda = 1 THEN 'Manual'
                WHEN vd.regra_venda = 2 THEN 'Automático'
                WHEN vd.regra_venda = 3 THEN 'Manual e Automático'
                WHEN vd.regra_venda = 4 THEN 'Distribuidor x Distribuidor'
                WHEN vd.regra_venda = 5 THEN 'Distribuidor x Manual'
                WHEN vd.regra_venda = 6 THEN ' Distribuidor x Automático' END) regra_venda");
        $this->db->from("vendas_diferenciadas vd");
        $this->db->join('compradores c', "c.id = vd.id_cliente", "left");
        $this->db->join('produtos_catalogo pc', "pc.codigo = vd.codigo", "left");
        $this->db->join('vw_produtos_precos vpp', "pc.codigo = vpp.codigo", "left");
        $this->db->where('pc.id_fornecedor', $this->session->id_fornecedor);
        $this->db->where('vpp.id_fornecedor', $this->session->id_fornecedor);
        $this->db->where('vd.id_fornecedor', $this->session->id_fornecedor);
        $this->db->where('vd.promocao', 0);
        $this->db->where('vd.id_cliente is not null');
        $this->db->group_by("codigo, id_cliente");
        $this->db->order_by("produto ASC");

        $query_clientes = $this->db->get()->result_array();

        if ( count($query_estados) < 1 ) {
            $query_estados[] = [
                'desconto_percentual' => '',
                'produto' => '',
                'preco' => '',
                'preco_desconto' => '',
                'quantidade' => '',
                'dias' => '',
                'estado' => '',
                'regra_venda' => ''
            ];
        }

        if ( count($query_clientes) < 1 ) {
            $query_clientes[] = [
                'desconto_percentual' => '',
                'produto' => '',
                'preco' => '',
                'preco_desconto' => '',
                'quantidade' => '',
                'dias' => '',
                'cliente' => '',
                'regra_venda' => ''
            ];
        }

        $dados_page1 = ['dados' => $query_estados , 'titulo' => 'Estados'];
        $dados_page2 = ['dados' => $query_clientes, 'titulo' => 'Compradores'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page1, $dados_page2);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }
}

/* End of file: Vendas_diferenciadas.php */
