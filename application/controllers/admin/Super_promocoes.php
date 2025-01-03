<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Super_promocoes extends Admin_controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('admin/super_promocoes');
        $this->views = 'admin/super_promocoes';
        $this->load->model('m_venda_diferenciada', 'venda_diferenciada');
    }

    public function index()
    {
        $page_title = "Promoções Automáticas";

        // $data['datatables'] = "{$this->route}/datatables";
        $data['to_datatable_estado'] = "{$this->route}/to_datatable_estado/";
        $data['to_datatable_cnpj'] = "{$this->route}/to_datatable_cnpj/";
        $data['url_delete_multiple'] = base_url('admin/vendas_diferenciadas/delete_multiple/');


        $data['header'] = $this->template->header([ 'title' => $page_title ]);
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

        $data['fornecedores'] = $this->db->get('fornecedores')->result_array();

        $this->load->view("{$this->views}/main", $data);
    }

    public function redirect()
    {
        $_SESSION['redirect_super'] = 1;

        redirect(base_url('admin/vendas_diferenciadas/insert'));
    }

    // public function to_datatable_estado()
    // {
    //     $r = $this->datatable->exec(
    //         $this->input->post(),
    //         'vendas_diferenciadas',
    //         [
    //             ['db' => 'vendas_diferenciadas.id', 'dt' => 'id'],
    //             ['db' => 'vendas_diferenciadas.codigo', 'dt' => 'codigo'],
    //             ['db' => 'vendas_diferenciadas.id_estado', 'dt' => 'id_estado'],
    //             ['db' => 'vendas_diferenciadas.desconto_percentual', 'dt' => 'desconto_percentual'],
    //             ['db' => 'vendas_diferenciadas.comissao', 'dt' => 'comissao'],
    //             ['db' => 'vendas_diferenciadas.quantidade', 'dt' => 'quantidade'],
    //             ['db' => 'vendas_diferenciadas.lote', 'dt' => 'lote'],
    //             ['db' => 'vendas_diferenciadas.dias', 'dt' => 'dias'],
    //             ['db' => 'produtos_catalogo.nome_comercial', 'dt' => 'produto_descricao'],
    //             ['db' => 'vendas_diferenciadas.id_fornecedor', 'dt' => 'id_fornecedor'],
    //             ['db' => 'vendas_diferenciadas.regra_venda', 'dt' => 'regra_venda'],
    //             ['db' => 'estados.uf', 'dt' => 'uf'],
    //             [
    //                 'db' => 'vw_produtos_precos.preco_unitario',
    //                 'dt' => 'preco',
    //                 'formatter' => function ($value, $row) {

    //                     return number_format($value, 4, ',', '.');
    //                 }
    //             ],
    //             [
    //                 'db' => 'vw_produtos_precos.preco_unitario',
    //                 'dt' => 'preco_desconto',
    //                 'formatter' => function ($value, $row) {

    //                     $preco = $value - ($value * ($row['desconto_percentual'] / 100));
    //                     return number_format($preco, 4, ',', '.');
    //                 }
    //             ],
    //             [
    //                 'db' => 'estados.descricao',
    //                 'dt' => 'descricao',
    //                 'formatter' => function ($value, $row) {
    //                     return "{$row['uf']} - {$value}";
    //                 }
    //             ]
    //         ],
    //         [
    //             ['estados', 'estados.id = vendas_diferenciadas.id_estado', 'LEFT'],
    //             ['produtos_catalogo', 'produtos_catalogo.codigo = vendas_diferenciadas.codigo', 'LEFT'],
    //             ['vw_produtos_precos', 'produtos_catalogo.codigo = vw_produtos_precos.codigo', 'LEFT']
    //         ],
    //         "vendas_diferenciadas.promocao = 0 AND vendas_diferenciadas.id_estado is not null AND regra_venda in (0, 2)",
    //         "codigo, id_estado"
    //     );

    //     $this->output->set_content_type('application/json')->set_output(json_encode($r));
    // }

    // public function to_datatable_cnpj()
    // {
    //     $r = $this->datatable->exec(
    //         $this->input->post(),
    //         'vendas_diferenciadas',
    //         [
    //             ['db' => 'vendas_diferenciadas.id', 'dt' => 'id'],
    //             ['db' => 'vendas_diferenciadas.codigo', 'dt' => 'codigo'],
    //             ['db' => 'vendas_diferenciadas.id_cliente', 'dt' => 'id_cliente'],
    //             ['db' => 'vendas_diferenciadas.desconto_percentual', 'dt' => 'desconto_percentual'],
    //             ['db' => 'vendas_diferenciadas.comissao', 'dt' => 'comissao'],
    //             ['db' => 'vendas_diferenciadas.quantidade', 'dt' => 'quantidade'],
    //             ['db' => 'vendas_diferenciadas.lote', 'dt' => 'lote'],
    //             ['db' => 'vendas_diferenciadas.dias', 'dt' => 'dias'],
    //             ['db' => 'vendas_diferenciadas.id_fornecedor', 'dt' => 'id_fornecedor'],
    //             ['db' => 'produtos_catalogo.nome_comercial', 'dt' => 'produto_descricao' ],
    //             ['db' => 'vendas_diferenciadas.regra_venda', 'dt' => 'regra_venda'],
    //             ['db' => 'compradores.cnpj', 'dt' => 'cnpj'],
    //             [
    //                 'db' => 'vw_produtos_precos.preco_unitario',
    //                 'dt' => 'preco',
    //                 'formatter' => function ($value, $row) {

    //                     return number_format($value, 4, ',', '.');
    //                 }
    //             ],
    //             [
    //                 'db' => 'vw_produtos_precos.preco_unitario',
    //                 'dt' => 'preco_desconto',
    //                 'formatter' => function ($value, $row) {

    //                     $preco = $value - ($value * ($row['desconto_percentual'] / 100));
    //                     return number_format($preco, 4, ',', '.');
    //                 }
    //             ],
    //             [
    //                 'db' => 'compradores.razao_social',
    //                 'dt' => 'razao_social',
    //                 'formatter' => function ($value, $row) {
    //                     return "{$row['cnpj']} - {$value}";
    //                 }
    //             ]
    //         ],
    //         [
    //             ['compradores', 'compradores.id = vendas_diferenciadas.id_cliente', 'LEFT'],
    //             ['produtos_catalogo', 'produtos_catalogo.codigo = vendas_diferenciadas.codigo', 'LEFT'],
    //             ['vw_produtos_precos', 'produtos_catalogo.codigo = vw_produtos_precos.codigo', 'LEFT']
    //         ],
    //         "vendas_diferenciadas.promocao = 0 and vendas_diferenciadas.id_cliente is not null AND regra_venda in (0, 2)",
    //         "codigo, id_cliente"
    //     );

    //     $this->output->set_content_type('application/json')->set_output(json_encode($r));
    // }

    ///////////////

    public function to_datatable_estado()
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'vendas_diferenciadas',
            [
                ['db' => 'vendas_diferenciadas.id', 'dt' => 'id'],
                ['db' => 'vendas_diferenciadas.codigo', 'dt' => 'codigo'],
                ['db' => 'vendas_diferenciadas.id_estado', 'dt' => 'id_estado'],
                ['db' => 'vendas_diferenciadas.desconto_percentual', 'dt' => 'desconto_percentual'],
                ['db' => 'vendas_diferenciadas.id_fornecedor', 'dt' => 'id_fornecedor'],
                ['db' => 'vendas_diferenciadas.comissao', 'dt' => 'comissao'],
                ['db' => 'vendas_diferenciadas.quantidade', 'dt' => 'quantidade'],
                ['db' => 'vendas_diferenciadas.lote', 'dt' => 'lote'],
                ['db' => 'vendas_diferenciadas.dias', 'dt' => 'dias'],
                ['db' => 'produtos_catalogo.nome_comercial', 'dt' => 'produto_descricao'],
                ['db' => 'vendas_diferenciadas.regra_venda', 'dt' => 'regra_venda'],
                ['db' => 'estados.uf', 'dt' => 'uf'],
                [
                    'db' => 'prod.preco_unitario',
                    'dt' => 'preco',
                    'formatter' => function ($value, $row) {

                        return number_format($value, 4, ',', '.');
                    }
                ],
                [
                    'db' => 'prod.preco_unitario',
                    'dt' => 'preco_desconto',
                    'formatter' => function ($value, $row) {

                        $preco = $value - ($value * ($row['desconto_percentual'] / 100));
                        return number_format($preco, 4, ',', '.');
                    }
                ],
                [
                    'db' => 'estados.descricao',
                    'dt' => 'descricao',
                    'formatter' => function ($value, $row) {
                        return "{$row['uf']} - {$value}";
                    }
                ]
            ],
            [
                ['estados', 'estados.id = vendas_diferenciadas.id_estado', 'LEFT'],
                ['produtos_catalogo', 'produtos_catalogo.codigo = vendas_diferenciadas.codigo AND produtos_catalogo.id_fornecedor = vendas_diferenciadas.id_fornecedor', 'LEFT'],
                ['produtos_preco prod', 'produtos_catalogo.codigo = prod.codigo AND produtos_catalogo.id_fornecedor = prod.id_fornecedor', 'LEFT']
            ],
            "vendas_diferenciadas.promocao = 0 AND
            vendas_diferenciadas.id_cliente is null AND 
            regra_venda in (0, 2)
            AND
            ((prod.id_estado is not null 
            AND prod.data_criacao = (SELECT max(pd.data_criacao)
                                    FROM produtos_preco pd
                                    WHERE pd.id_estado is not null AND
                                        pd.codigo = prod.codigo AND
                                        pd.id_estado = prod.id_estado AND
                                        pd.id_fornecedor = prod.id_fornecedor))
            OR
            (isnull(prod.id_estado) AND 
                prod.data_criacao = (SELECT max(pd.data_criacao)
                                    FROM produtos_preco pd
                                    WHERE isnull(pd.id_estado) AND
                                        pd.codigo = prod.codigo AND
                                        pd.id_fornecedor = prod.id_fornecedor)))",
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
                ['db' => 'vendas_diferenciadas.id_cliente', 'dt' => 'id_cliente'],
                ['db' => 'vendas_diferenciadas.desconto_percentual', 'dt' => 'desconto_percentual'],
                ['db' => 'vendas_diferenciadas.id_fornecedor', 'dt' => 'id_fornecedor'],
                ['db' => 'vendas_diferenciadas.comissao', 'dt' => 'comissao'],
                ['db' => 'vendas_diferenciadas.quantidade', 'dt' => 'quantidade'],
                ['db' => 'vendas_diferenciadas.lote', 'dt' => 'lote'],
                ['db' => 'vendas_diferenciadas.dias', 'dt' => 'dias'],
                ['db' => 'produtos_catalogo.nome_comercial', 'dt' => 'produto_descricao', ],
                ['db' => 'vendas_diferenciadas.regra_venda', 'dt' => 'regra_venda'],
                ['db' => 'compradores.cnpj', 'dt' => 'cnpj'],
                [
                    'db' => 'prod.preco_unitario',
                    'dt' => 'preco',
                    'formatter' => function ($value, $row) {

                        return number_format($value, 4, ',', '.');
                    }
                ],
                [
                    'db' => 'prod.preco_unitario',
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
                ]
            ],
            [
                ['compradores', 'compradores.id = vendas_diferenciadas.id_cliente', 'LEFT'],
                ['produtos_catalogo', 'produtos_catalogo.codigo = vendas_diferenciadas.codigo AND produtos_catalogo.id_fornecedor = vendas_diferenciadas.id_fornecedor', 'LEFT'],
                ['produtos_preco prod', 'produtos_catalogo.codigo = prod.codigo AND produtos_catalogo.id_fornecedor = prod.id_fornecedor', 'LEFT']
            ],
            "vendas_diferenciadas.promocao = 0 AND 
            vendas_diferenciadas.id_estado is null AND
            regra_venda in (0, 2)
            AND
            ((prod.id_estado is not null 
            AND prod.data_criacao = (SELECT max(pd.data_criacao)
                                    FROM produtos_preco pd
                                    WHERE pd.id_estado is not null AND
                                        pd.codigo = prod.codigo AND
                                        pd.id_estado = prod.id_estado AND
                                        pd.id_fornecedor = prod.id_fornecedor))
            OR
            (isnull(prod.id_estado) AND 
                prod.data_criacao = (SELECT max(pd.data_criacao)
                                    FROM produtos_preco pd
                                    WHERE isnull(pd.id_estado) AND
                                        pd.codigo = prod.codigo AND
                                        pd.id_fornecedor = prod.id_fornecedor)))",
            "codigo, id_cliente"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }
    ///////////////

    public function delete_multiple()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $this->db->trans_begin();

            if (!isset($post['el'])){
                $newdata = [
                    'type'    => 'warning',
                    'message' => 'Nenhum produto selecionado'
                ];

                $this->output->set_content_type('application/json')->set_output(json_encode($newdata));
                return;
            }

            foreach ($post['el'] as $item) {
                $this->venda_diferenciada->excluir($item);
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();

                $newdata = [
                    'type' => 'warning',
                    'message' => 'Erro ao excluir'
                ];
            } else {
                $this->db->trans_commit();

                $newdata = [
                    'type' => 'success',
                    'message' => 'Excluidos com sucesso'
                ];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($newdata));
        }
    }

    // public function exportar()
    // {
    //     $this->db->select("
    //         vd.id_estado, 
    //         vd.codigo, 
    //         vd.desconto_percentual, 
    //         pc.nome_comercial AS produto, 
    //         FORMAT(vpp.preco_unitario, 4 , 'de_DE') AS preco, 
    //         FORMAT( (vpp.preco_unitario - (vpp.preco_unitario * vd.desconto_percentual / 100)), 4 , 'de_DE') AS preco_desconto,
    //         vd.quantidade, 
    //         vd.dias, 
    //         CONCAT(e.uf, ' - ', e.descricao) AS estado");
    //     $this->db->from("vendas_diferenciadas vd");
    //     $this->db->join('estados e', "e.id = vd.id_estado", "left");
    //     $this->db->join('produtos_catalogo pc', "pc.codigo = vd.codigo", "left");
    //     $this->db->join('vw_produtos_precos vpp', "pc.codigo = vpp.codigo", "left");
    //     $this->db->where('vd.promocao', 0);
    //     $this->db->where('vd.id_estado is not null');
    //     $this->db->where('vd.regra_venda in (0, 2)');
    //     $this->db->group_by("codigo, id_estado");
    //     $this->db->order_by("produto ASC");

    //     $query_estados = $this->db->get()->result_array();

    //     $this->db->select("
    //         vd.id_cliente, 
    //         vd.codigo, 
    //         vd.desconto_percentual, 
    //         pc.nome_comercial AS produto, 
    //         FORMAT(vpp.preco_unitario, 4 , 'de_DE') AS preco, 
    //         FORMAT( (vpp.preco_unitario - (vpp.preco_unitario * vd.desconto_percentual / 100)), 4 , 'de_DE') AS preco_desconto,
    //         vd.quantidade, 
    //         vd.dias,  
    //         CONCAT(c.cnpj, ' - ', c.razao_social), 'c.razao_social' AS cliente");
    //     $this->db->from("vendas_diferenciadas vd");
    //     $this->db->join('compradores c', "c.id = vd.id_cliente", "left");
    //     $this->db->join('produtos_catalogo pc', "pc.codigo = vd.codigo", "left");
    //     $this->db->join('vw_produtos_precos vpp', "pc.codigo = vpp.codigo", "left");
    //     $this->db->where('vd.promocao', 0);
    //     $this->db->where('vd.id_cliente is not null');
    //     $this->db->where('vd.regra_venda in (0, 2)');
    //     $this->db->group_by("codigo, id_cliente");
    //     $this->db->order_by("produto ASC");

    //     $query_clientes = $this->db->get()->result_array();

    //     if ( count($query_estados) < 1 ) {
    //         $query_estados[] = [
    //             'id_estado' => '',
    //             'codigo' => '',
    //             'desconto_percentual' => '',
    //             'produto' => '',
    //             'preco' => '',
    //             'preco_desconto' => '',
    //             'quantidade' => '',
    //             'dias' => '',
    //             'estado' => '',
    //         ];
    //     }

    //     if ( count($query_clientes) < 1 ) {
    //         $query_clientes[] = [
    //             'id_cliente' => '',
    //             'codigo' => '',
    //             'desconto_percentual' => '',
    //             'produto' => '',
    //             'preco' => '',
    //             'preco_desconto' => '',
    //             'quantidade' => '',
    //             'dias' => '',
    //             'cliente' => ''
    //         ];
    //     }

    //     $dados_page1 = ['dados' => $query_estados , 'titulo' => 'Estados'];
    //     $dados_page2 = ['dados' => $query_clientes, 'titulo' => 'Compradores'];

    //     $exportar = $this->export->excel("planilha.xlsx", $dados_page1, $dados_page2);

    //     if ( $exportar['status'] == false ) {

    //         $warning = ['type' => 'warning', 'message' => $exportar['message']];
    //     } else {

    //         $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
    //     }

    //     $this->session->set_userdata('warning', $warning);

    //     redirect($this->route);
    // }

    public function exportar()
    {
        $this->db->select("
            vd.id_estado, 
            vd.codigo, 
            vd.desconto_percentual, 
            pc.nome_comercial AS produto, 
            FORMAT(prod.preco_unitario, 4 , 'de_DE') AS preco, 
            FORMAT( (prod.preco_unitario - (prod.preco_unitario * vd.desconto_percentual / 100)), 4 , 'de_DE') AS preco_desconto,
            vd.quantidade, 
            vd.dias, 
            CONCAT(e.uf, ' - ', e.descricao) AS estado");
        $this->db->from("vendas_diferenciadas vd");
        $this->db->join('estados e', "e.id = vd.id_estado", "left");
        $this->db->join('produtos_catalogo pc', "pc.codigo = vd.codigo AND pc.id_fornecedor = vd.id_fornecedor", "left");
        $this->db->join('produtos_preco prod', "pc.codigo = prod.codigo AND pc.id_fornecedor = prod.id_fornecedor", "left");
        $this->db->where('vd.promocao', 0);
        $this->db->where('vd.id_cliente is null');
        $this->db->where('vd.regra_venda in (0, 2)');
        $this->db->where("
            ((prod.id_estado is not null 
            AND prod.data_criacao = (SELECT max(pd.data_criacao)
                                    FROM produtos_preco pd
                                    WHERE pd.id_estado is not null AND
                                        pd.codigo = prod.codigo AND
                                        pd.id_estado = prod.id_estado AND
                                        pd.id_fornecedor = prod.id_fornecedor))
            OR
            (isnull(prod.id_estado) AND 
                prod.data_criacao = (SELECT max(pd.data_criacao)
                                    FROM produtos_preco pd
                                    WHERE isnull(pd.id_estado) AND
                                        pd.codigo = prod.codigo AND
                                        pd.id_fornecedor = prod.id_fornecedor)))
        ");
        $this->db->group_by("codigo, id_estado");
        $this->db->order_by("produto ASC");

        $query_estados = $this->db->get()->result_array();

        $this->db->select("
            vd.id_cliente, 
            vd.codigo, 
            vd.desconto_percentual, 
            pc.nome_comercial AS produto, 
            FORMAT(prod.preco_unitario, 4 , 'de_DE') AS preco, 
            FORMAT( (prod.preco_unitario - (prod.preco_unitario * vd.desconto_percentual / 100)), 4 , 'de_DE') AS preco_desconto,
            vd.quantidade, 
            vd.dias,  
            CONCAT(c.cnpj, ' - ', c.razao_social), 'c.razao_social' AS cliente");
        $this->db->from("vendas_diferenciadas vd");
        $this->db->join('compradores c', "c.id = vd.id_cliente", "left");
        $this->db->join('produtos_catalogo pc', "pc.codigo = vd.codigo AND pc.id_fornecedor = vd.id_fornecedor", "left");
        $this->db->join('produtos_preco prod', "pc.codigo = prod.codigo AND pc.id_fornecedor = prod.id_fornecedor", "left");
        $this->db->where('vd.promocao', 0);
        $this->db->where('vd.id_estado is null');
        $this->db->where('vd.regra_venda in (0, 2)');
        $this->db->where("
            ((prod.id_estado is not null 
            AND prod.data_criacao = (SELECT max(pd.data_criacao)
                                    FROM produtos_preco pd
                                    WHERE pd.id_estado is not null AND
                                        pd.codigo = prod.codigo AND
                                        pd.id_estado = prod.id_estado AND
                                        pd.id_fornecedor = prod.id_fornecedor))
            OR
            (isnull(prod.id_estado) AND 
                prod.data_criacao = (SELECT max(pd.data_criacao)
                                    FROM produtos_preco pd
                                    WHERE isnull(pd.id_estado) AND
                                        pd.codigo = prod.codigo AND
                                        pd.id_fornecedor = prod.id_fornecedor)))
        ");
        $this->db->group_by("codigo, id_cliente");
        $this->db->order_by("produto ASC");

        $query_clientes = $this->db->get()->result_array();

        if ( count($query_estados) < 1 ) {
            $query_estados[] = [
                'id_estado' => '',
                'codigo' => '',
                'desconto_percentual' => '',
                'produto' => '',
                'preco' => '',
                'preco_desconto' => '',
                'quantidade' => '',
                'dias' => '',
                'estado' => '',
            ];
        }

        if ( count($query_clientes) < 1 ) {
            $query_clientes[] = [
                'id_cliente' => '',
                'codigo' => '',
                'desconto_percentual' => '',
                'produto' => '',
                'preco' => '',
                'preco_desconto' => '',
                'quantidade' => '',
                'dias' => '',
                'cliente' => ''
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