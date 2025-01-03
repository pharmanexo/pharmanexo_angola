<?php

class Dashboard extends Rep_controller
{

    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url("pharma/dashboard/");
        $this->views = "pharma/";

        $this->load->model('m_pedido_rep', 'ped');
        $this->load->model('m_pedido_pharma_prod', 'pedido');
        $this->load->model('m_produto', 'produto');
        $this->load->model('m_bi', 'BI');
    }

    public function index()
    {

        $data['header'] = $this->tmp_rep->header(['title' => 'Portal do Representante']);
        $data['navbar'] = $this->tmp_rep->navbar();
        $data['sidebar'] = $this->tmp_rep->sidebar();
        $data['heading'] = $this->tmp_rep->heading(['page_title' => 'Portal do Representante']);
        $data['scripts'] = $this->tmp_rep->scripts([
            'scripts' => [
                'https://cdn.jsdelivr.net/npm/apexcharts'
            ]
        ]);

        $data['total_pedidos_abertos'] = $this->pedido->totalPedidosAbertos();
        $data['total_pedidos_enviados'] = $this->pedido->totalPedidosEnviados();
        $data['total_pedidos_faturados'] = $this->pedido->totalPedidosFaturados();
        $data['total_pedidos_cancelados'] = $this->pedido->totalPedidosCancelados();

        $data['to_datatable_promocoes'] = "{$this->route}to_datatable_promocoes";
        $data['to_datatable_estados'] = "{$this->route}to_datatable_estados";
        $data['to_datatable_cnpjs'] = "{$this->route}to_datatable_cnpjs";

        $data['url_grafico'] = "{$this->route}/charts";
        $data['url_exportar_clientes_estados'] = "{$this->route}/exportar_clientes_estados";
        $data['url_promocoes'] = "{$this->route}/exportar_promocoes";
        $data['url_add_prod'] = "{$this->route}/addtoorder";
        $data['url_open'] = base_url("/pharma/pedidos/open/");

        $this->load->view("{$this->views}dashboard", $data);
    }

    public function addtoorder()
    {
        $post = $this->input->post();
        $post['id_representante'] = 4;
        $post['id_cliente'] = $this->session->id_cliente;
        $post['id_fornecedor'] = $this->session->id_fornecedor;


        $get_old = $this->ped->find('*', "id_fornecedor = {$post['id_fornecedor']} and id_representante = {$post['id_representante']} and id_comprador = {$post['id_cliente']} and situacao = 1", true);


        $data['preco_unitario'] = $this->price->getPrice([
            'id_fornecedor' => $this->session->id_fornecedor,
            'codigo' => $post['codigo'],
            'id_estado' => $this->session->id_estado
        ]);


        if (!empty($get_old)){

            // pegar dados do produto
            $produto = [
                'id_pedido' => $get_old['id'],
                'cd_produto_fornecedor' => $post['codigo'],
                'preco_desconto' => dbNumberFormat($post['price']),
                'preco_unidade' => $data['preco_unitario'],
                'quantidade_solicitada' => $post['quantidade'],
                'total' => intval($post['quantidade']) * dbNumberFormat($post['price'])
            ];

            $this->db->where('id_pedido', $get_old['id']);
            $this->db->where('cd_produto_fornecedor', $post['codigo']);
            $produto_existente = $this->db->get('pedidos_representantes_produtos');


            if ($produto_existente->num_rows() > 0) {

                # Atualiza produto existente somando as quantidades existente com as novas
                $prod = $produto_existente->row_array();
               /* if (intval($prod['quantidade_solicitada'] ) > 0){
                    $produto['quantidade_solicitada'] = intval($prod['quantidade_solicitada'] ) + $produto['quantidade_solicitada'];
                    $produto['total'] = intval($produto['quantidade_solicitada']) * dbNumberFormat($post['price']);
                }*/

                $this->db->where('id_pedido', $get_old['id']);
                $this->db->where('cd_produto_fornecedor', $post['codigo']);

                $operacao = $this->db->update('pedidos_representantes_produtos', $produto);
                $success = "atualizado";
                $error = "atualizar";

            } else {

                # Novo produto

                $operacao = $this->db->insert('pedidos_representantes_produtos', $produto);
                $success = "cadastrado";
                $error = "cadastrar";
            }

        }

        if ($operacao) {

            $warning = ['type' => 'success', 'message' => "Produto {$success}", 'id_pedido' => $get_old['id']];

        } else {
            $warning = ['type' => 'warning', 'message' => "Erro ao {$error} produto"];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($warning));

        // verifica se existe um pedido em aberto, caso exista insere o item no pedido


        // se não existir um pedido, cria, adiciona os itens

    }

    public function charts()
    {

        $data['chartProdutosVencer'] = $this->createChartProdutosVencer();
        $data['chartMeta'] = $this->createChartMeta();

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function createChartProdutosVencer()
    {

        $intervalo1 = $this->BI->valorTotalProdutosPorValidade($this->session->id_fornecedor, $this->session->id_estado, date('Y-m-d'), date('Y-m-d', strtotime('+3months')));
        $intervalo2 = $this->BI->valorTotalProdutosPorValidade($this->session->id_fornecedor, $this->session->id_estado, date('Y-m-d', strtotime('+3months')), date('Y-m-d', strtotime('+6months')));
        $intervalo3 = $this->BI->valorTotalProdutosPorValidade($this->session->id_fornecedor, $this->session->id_estado, date('Y-m-d', strtotime('+6months')), date('Y-m-d', strtotime('+9months')));
        $intervalo4 = $this->BI->valorTotalProdutosPorValidade($this->session->id_fornecedor, $this->session->id_estado, date('Y-m-d', strtotime('+9months')), date('Y-m-d', strtotime('+12months')));
        $intervalo5 = $this->BI->valorTotalProdutosPorValidade($this->session->id_fornecedor, $this->session->id_estado, date('Y-m-d', strtotime('+12months')), date('Y-m-d', strtotime('+18months')));

        $data['format'] = [
            number_format($intervalo1, 4, ',', '.'),
            number_format($intervalo2, 4, ',', '.'),
            number_format($intervalo3, 4, ',', '.'),
            number_format($intervalo4, 4, ',', '.'),
            number_format($intervalo5, 4, ',', '.'),
        ];

        $data['value'] = [['name' => 'Total', 'data' => [
            $intervalo1,
            $intervalo2,
            $intervalo3,
            $intervalo4,
            $intervalo5,
        ]]];

        return $data;
    }

    public function createChartMeta()
    {
        $this->db->where('id_representante', $this->session->id);
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);
        $rep = $this->db->get('representantes_fornecedores')->row_array();

        $meta = floatval($rep['meta']);

        $total_pedidos_faturados = $this->pedido->totalPedidosFaturados();

        $valor = (isset($total_pedidos_faturados)) ? $total_pedidos_faturados : 0;

        $data['valor'] = ($valor == 0) ? 0 : ($valor * 100) / $meta;
        $data['meta'] = number_format($meta, 2, ',', '.');

        return $data;

    }

    public function to_datatable_promocoes()
    {

        $estado = $this->db->where('uf', $this->session->estado)->get('estados')->row_array();

        $where = " (produtos_preco.id_estado = {$estado['id']} or produtos_preco.id_estado is null) ";

        $r = $this->datatable->exec(
            $this->input->post(),
            'vendas_diferenciadas',
            [
                ['db' => 'vendas_diferenciadas.id', 'dt' => 'id'],
                ['db' => 'vendas_diferenciadas.id_produto', 'dt' => 'id_produto'],
                ['db' => 'vendas_diferenciadas.codigo', 'dt' => 'codigo'],
                ['db' => 'vendas_diferenciadas.desconto_percentual', 'dt' => 'desconto_percentual'],
                ['db' => 'vendas_diferenciadas.comissao', 'dt' => 'comissao'],
                ['db' => 'vendas_diferenciadas.quantidade', 'dt' => 'quantidade'],
                ['db' => 'vendas_diferenciadas.lote', 'dt' => 'lote'],
                ['db' => 'produtos_catalogo.nome_comercial', 'dt' => 'nome_comercial'],
                ['db' => 'produtos_catalogo.ean', 'dt' => 'ean'],
                ['db' => 'vendas_diferenciadas.dias', 'dt' => 'dias'],
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
                                    vendas_diferenciadas.id_fornecedor = produtos_lote.id_fornecedor '],
            ],
            "{$where} AND 
            vendas_diferenciadas.id_fornecedor = 5037 and
            produtos_lote.validade > NOW() and vendas_diferenciadas.regra_venda = 7",
            "produtos_catalogo.codigo, produtos_catalogo.id_fornecedor, produtos_catalogo.id_marca"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function to_datatable_estados()
    {
        $datatables = $this->datatable->exec(
            $this->input->post(),
            'estados',
            [
                ['db' => 'estados.id', 'dt' => 'id'],
                ['db' => 'estados.uf', 'dt' => 'uf'],
                ['db' => 'estados.descricao', 'dt' => 'descricao'],
            ]
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    public function to_datatable_cnpjs()
    {
        $datatables = $this->datatable->exec(
            $this->input->post(),
            'compradores',
            [
                ['db' => 'compradores.id', 'dt' => 'id'],
                ['db' => 'compradores.status', 'dt' => 'status'],
                ['db' => 'compradores.razao_social', 'dt' => 'razao_social'],
                ['db' => 'compradores.cnpj', 'dt' => 'cnpj'],
                ['db' => 'compradores.email', 'dt' => 'email'],
                ['db' => 'compradores.telefone', 'dt' => 'telefone']
            ],
            null,
            'compradores.status != 3'
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    public function exportar_clientes_estados()
    {
        $this->db->select("uf, descricao AS estado");
        $this->db->from("estados");
        $this->db->order_by("uf ASC");

        $query_estados = $this->db->get()->result_array();

        $this->db->select("cnpj, razao_social");
        $this->db->from("compradores");
        $this->db->where('status != 3');
        $this->db->order_by("razao_social ASC");

        $query_clientes = $this->db->get()->result_array();

        if (count($query_estados) < 1) {
            $query_estados[] = [
                'uf' => '',
                'estado' => ''
            ];
        }

        if (count($query_clientes) < 1) {
            $query_clientes[] = [
                'cnpj' => '',
                'razao_social' => ''
            ];
        }

        $dados_page1 = ['dados' => $query_clientes, 'titulo' => 'Clientes'];
        $dados_page2 = ['dados' => $query_estados, 'titulo' => 'Estados'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page1, $dados_page2);

        if ($exportar['status'] == false) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }

    public function exportar_promocoes()
    {

        if (in_array($this->session->id_fornecedor, [12, 111, 115, 123, 120, 15, 180, 25])) {
            $where = "pp.id_estado = {$this->session->id_estado}";
        } elseif ($this->session->id_fornecedor == 112) {
            $where = "pp.id_estado = 9";
        } else {
            $where = "pp.id_estado is null";
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

        $query = $this->db->get()->result_array();

        if (count($query) < 1) {
            $query[] = [
                'codigo' => '',
                'desconto_percentual' => '',
                'produto' => '',
                'preco' => '',
                'preco_desconto' => '',
                'quantidade' => '',
                'dias' => '',
                'lote' => '',
                'regra_venda' => ''
            ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'Promoções'];

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