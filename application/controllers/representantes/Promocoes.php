<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Promocoes extends Rep_controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('representantes/promocoes');
        $this->views = 'representantes/promocoes';
    }

    public function index()
    {
        $page_title = "Promoções";

        $data['to_datatable'] = "{$this->route}/to_datatable/";
        $data['url_update'] = "{$this->route}/update";
        $data['header'] = $this->tmp_rep->header([ 'title' => 'Promoções',]);
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

    public function to_datatable()
    {

        if (in_array($this->session->id_fornecedor, [12, 111, 115, 123, 120, 15, 180, 25])) {
            $where =  "produtos_preco.id_estado = {$this->session->id_estado}";
        } elseif ($this->session->id_fornecedor == 112) {
            $where = " produtos_preco.id_estado = 9";
        } else {
            $where =  "produtos_preco.id_estado is null";
        }

        $r = $this->datatable->exec(
            $this->input->post(),
            'vendas_diferenciadas',
            [
                [ 'db' => 'vendas_diferenciadas.id', 'dt' => 'id' ],
                [ 'db' => 'vendas_diferenciadas.id_produto', 'dt' => 'id_produto' ],
                [ 'db' => 'vendas_diferenciadas.codigo', 'dt' => 'codigo' ],
                [ 'db' => 'vendas_diferenciadas.desconto_percentual', 'dt' => 'desconto_percentual' ],
                [ 'db' => 'vendas_diferenciadas.comissao', 'dt' => 'comissao' ],
                [ 'db' => 'vendas_diferenciadas.quantidade', 'dt' => 'quantidade' ],
                [ 'db' => 'vendas_diferenciadas.lote', 'dt' => 'lote' ],
                [ 'db' => 'produtos_catalogo.nome_comercial', 'dt' => 'nome_comercial' ],
                [ 'db' => 'vendas_diferenciadas.dias', 'dt' => 'dias' ],
                [ 
                    'db' => 'vendas_diferenciadas.regra_venda', 
                    'dt' => 'regra_venda',
                    'formatter' => function ($value, $row) {
                         return status_regra_venda($value);
                    }
                ],
                [
                    'db' => 'produtos_catalogo.apresentacao',
                    'dt' => 'produto_descricao', "formatter" => function ($d, $r) {
                    return $r['nome_comercial'] . " - " . $d;
                }],
                [
                    'db' => 'produtos_preco.preco_unitario',
                    'dt' => 'preco',
                    'formatter' => function ($value, $row) {
                        return number_format($value, 4, ',', '.');
                    }
                ],
                [
                    'db' => 'produtos_preco.preco_unitario',
                    'dt' => 'preco_desconto',
                    'formatter' => function ($value, $row) {
                        $preco = $value - ($value * ($row['desconto_percentual'] / 100));
                        return number_format($preco, 4, ',', '.');
                    }
                ]
            ],
            [
                ['produtos_catalogo', 'produtos_catalogo.codigo = vendas_diferenciadas.codigo AND produtos_catalogo.id_fornecedor = vendas_diferenciadas.id_fornecedor'],
                ['produtos_preco', 'produtos_catalogo.codigo = produtos_preco.codigo AND produtos_catalogo.id_fornecedor = produtos_preco.id_fornecedor'],
                ['produtos_lote', 'vendas_diferenciadas.codigo = produtos_lote.codigo AND 
                                    vendas_diferenciadas.id_fornecedor = produtos_lote.id_fornecedor AND 
                                    vendas_diferenciadas.lote = produtos_lote.lote'],
            ],
            "{$where} AND vendas_diferenciadas.promocao = 1 and 
            vendas_diferenciadas.id_fornecedor = {$this->session->userdata('id_fornecedor')} and
            produtos_lote.validade > NOW()",
            "produtos_catalogo.codigo, produtos_catalogo.id_fornecedor, produtos_catalogo.id_marca"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function exportar()
    {
        if (in_array($this->session->id_fornecedor, [12, 111, 115, 123, 120, 15, 180, 25])) {
            $where =  "pp.id_estado = {$this->session->id_estado}";
        } elseif ($this->session->id_fornecedor == 112) {
            $where = "pp.id_estado = 9";
        } else {
            $where =  "pp.id_estado is null";
        }

        $this->db->select("
            vd.codigo, 
            vd.desconto_percentual, 
            CASE WHEN pc.descricao is null THEN CONCAT(pc.nome_comercial, ' - ', pc.apresentacao) ELSE CONCAT(pc.nome_comercial, ' - ', pc.descricao) END  AS produto, 
            FORMAT(pp.preco_unitario, 4 , 'de_DE') AS preco, 
            FORMAT( (pp.preco_unitario - (pp.preco_unitario * vd.desconto_percentual / 100)), 4 , 'de_DE') AS preco_desconto,
            vd.quantidade, 
            vd.dias, 
            vd.lote, 
            (CASE 
                WHEN vd.regra_venda = 0 THEN 'Todos'
                WHEN vd.regra_venda = 1 THEN 'Manual'
                WHEN vd.regra_venda = 2 THEN 'Automático'
                WHEN vd.regra_venda = 3 THEN 'Manual e Automático'
                WHEN vd.regra_venda = 4 THEN 'Distribuidor x Distribuidor'
                WHEN vd.regra_venda = 5 THEN 'Distribuidor x Manual'
                WHEN vd.regra_venda = 6 THEN ' Distribuidor x Automático' END) regra_venda,
            pc.id_fornecedor,
            pc.id_marca");
        $this->db->from("vendas_diferenciadas vd");
        $this->db->join('produtos_catalogo pc', "pc.codigo = vd.codigo AND pc.id_fornecedor = vd.id_fornecedor");
        $this->db->join('produtos_preco pp', "pc.codigo = pp.codigo AND pc.id_fornecedor = pp.id_fornecedor");
        $this->db->join('produtos_lote pl', "vd.codigo = pl.codigo AND vd.id_fornecedor = pl.id_fornecedor AND vd.lote = pl.lote");
        $this->db->where('vd.id_fornecedor', $this->session->id_fornecedor);
        $this->db->where('vd.promocao', 1);
        $this->db->where("{$where}");
        $this->db->where("pl.validade > NOW()");
        $this->db->group_by("vd.codigo, pc.id_fornecedor, pc.id_marca");
        $this->db->order_by("produto ASC");


        if ( count($query) < 1 ) {
            $query[] = [
                'codigo' => '',
                'desconto' => '',
                'produto' => '',
                'preco' => '',
                'preco_desconto' => '',
                'quantidade' => '',
                'dias' => '',
                'lote' => ''
            ];
        } 

        $dados_page = ['dados' => $query , 'titulo' => 'Produtos'];

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

/* End of file: Promocoes.php */
