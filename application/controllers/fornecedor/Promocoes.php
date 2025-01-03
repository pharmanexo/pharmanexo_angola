<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Promocoes extends MY_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/promocoes');
        $this->views = 'fornecedor/promocoes';

        $this->load->model("m_venda_diferenciada", "venda_diferenciada");
        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_estados', 'estados');
        $this->load->model('m_catalogo', 'catalogo');
        $this->load->model('m_estoque', 'estoque');
    }

    /**
     * Exibe a tela de promoções
     *
     * @return view
     */
    public function index()
    {
        $page_title = "Promoções";

        $data['to_datatable_estado'] = "{$this->route}/to_datatable_estado/";
        $data['to_datatable_cnpj'] = "{$this->route}/to_datatable_cnpj/";

        $data['url_delete_multiple'] = "{$this->route}/delete_multiple/";
        $data['url_update'] = "{$this->route}/modal";

        $data['header'] = $this->template->header(['title' => 'Promoções',
        ]);

        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();

        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
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
                    'id' => 'btnDeleteMultiple',
                    'url' => $data['url_delete_multiple'],
                    'class' => 'btn-danger',
                    'icone' => 'fa-trash',
                    'label' => 'Excluir Selecionados'
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

        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/jquery.form.min.js',
                THIRD_PARTY . 'plugins/jquery-validation-1.19.1/dist/jquery.validate.min.js'
            ]
        ]);

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Abre modal para cadastrar promoções para o produto (venda diferenciada)
     *
     * @param int Id da promocao
     * @return  /view
     */
    public function modal($id)
    {

        $prod = $this->venda_diferenciada->findById($id);

        $produto = $this->catalogo->find("*", "codigo = {$prod['codigo']} AND id_fornecedor = {$prod['id_fornecedor']}", true);

        $prod['nome_comercial'] = $produto['nome_comercial'];
        $prod['apresentacao'] = $produto['apresentacao'];
        $prod['descricao'] = $produto['descricao'];
        $prod['marca'] = $produto['marca'];
        $prod['unidade'] = $produto['unidade'];
        $prod['quantidade_unidade'] = $produto['quantidade_unidade'];

        if (isset($prod['id_estado'])) {

            $estado = $this->estados->findById($prod['id_estado']);

            $prod['campo'] = $estado['uf'] . " - " . $estado['descricao'];
            $prod['label_campo'] = 'Estado';
        } else {

            $comprador = $this->compradores->findById($prod['id_cliente']);

            $prod['campo'] = $comprador['uf'] . " - " . $comprador['descricao'];
            $prod['label_campo'] = 'CNPJ';
        }

        # Obtem  o lote e validade do produto
        $produto_lote = $this->estoque->getLote($prod['codigo'], $prod['lote'], $prod['id_fornecedor'], 1);

        $prod['estoque'] = (isset($produto_lote)) ? $produto_lote['estoque'] : 0;
        $prod['validade'] = $produto_lote['validade'];
        $prod['lote'] = $prod['lote'];

        # Obtem o preço
        $prod['preco_unitario'] = $this->price->getPrice(['id_fornecedor' => $prod['id_fornecedor'], 'codigo' => $prod['codigo'], 'id_estado' => $this->session->id_estado]);


        $prod['preco_desconto'] = $prod['preco_unitario'] - ($prod['preco_unitario'] * ($prod['desconto_percentual'] / 100));

        $data['title'] = "Atualizar Promoção";
        $data['produto'] = $prod;
        $data['form_action'] = "{$this->route}/save/{$id}";

        $this->load->view("{$this->views}/modal_promocao", $data);
    }

    /**
     * Salva ou atualiza as promoções para o produto (venda diferenciada)
     *
     * @param int Id da promocao
     * @return  json
     */
    public function save($id)
    {
        $post = $this->input->post();


        $vd = $this->db->where('id', $id)->update('vendas_diferenciadas', [
            'desconto_percentual' => dbNumberFormat($post['desconto_percentual']),
            'regra_venda' => $post['regra_venda'],
        ]);

        if ($vd) {

            $output = ['type' => 'success', 'message' => notify_update];
        } else {

            $output = $this->notify->errorMessage();
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    /**
     * obtem as promoções por estado
     *
     * @return json
     */
    public function to_datatable_estado()
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'vendas_diferenciadas vd',
            [
                ['db' => 'vd.id', 'dt' => 'id'],
                ['db' => 'vd.codigo', 'dt' => 'codigo'],
                ['db' => 'vd.desconto_percentual', 'dt' => 'desconto_percentual'],
                ['db' => 'vd.lote', 'dt' => 'lote'],
                ['db' => 'pc.nome_comercial', 'dt' => 'nome_comercial'],
                ['db' => 'e.uf', 'dt' => 'uf'],
                [
                    'db' => 'vd.regra_venda',
                    'dt' => 'regra_venda',
                    'formatter' => function ($value, $row) {
                        return status_regra_venda($value);
                    }
                ],
                [
                    'db' => 'e.descricao',
                    'dt' => 'estado',
                    'formatter' => function ($value, $row) {

                        return "{$row['uf']} - {$value}";
                    }
                ],
                [
                    'db' => 'vd.id',
                    'dt' => 'preco',
                    'formatter' => function ($value, $row) {

                        /*   $preco_unit = $this->price->getPrice([
                               'id_fornecedor' => $this->session->id_fornecedor,
                               'codigo' => $row['codigo'],
                               'id_estado' => $this->session->id_estado
                           ]);

                           return number_format($preco_unit, 4, ',', '.');*/

                        return '';
                    }
                ],
                [
                    'db' => 'vd.id',
                    'dt' => 'preco_desconto',
                    'formatter' => function ($value, $row) {

                        /*$preco_unit = $this->price->getPrice([
                            'id_fornecedor' => $this->session->id_fornecedor,
                            'codigo' => $row['codigo'],
                            'id_estado' => $this->session->id_estado
                        ]);

                        $preco = $preco_unit - ($preco_unit * ($row['desconto_percentual'] / 100));
                        return number_format($preco, 4, ',', '.');*/

                        return '';
                    }
                ]
            ],
            [
                ['produtos_catalogo pc', 'pc.codigo = vd.codigo AND pc.id_fornecedor = vd.id_fornecedor', 'left'],
                ['estados e', "e.id = vd.id_estado"],
                ['produtos_lote pl', 'vd.codigo = pl.codigo AND vd.id_fornecedor = pl.id_fornecedor AND vd.lote = pl.lote', "left"],
            ],
            "vd.promocao = 1 AND 
            vd.id_estado is not null AND
            vd.id_cliente is null AND
            vd.id_fornecedor = {$this->session->userdata('id_fornecedor')} AND
            pl.lote is not null"
        // "pc.codigo, pc.id_fornecedor, pc.id_marca"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    /**
     * obtem as promoções por comprador
     *
     * @return json
     */
    public function to_datatable_cnpj()
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'vendas_diferenciadas vd',
            [
                ['db' => 'vd.id', 'dt' => 'id'],
                ['db' => 'vd.codigo', 'dt' => 'codigo'],
                ['db' => 'vd.desconto_percentual', 'dt' => 'desconto_percentual'],
                ['db' => 'vd.lote', 'dt' => 'lote'],
                ['db' => 'pc.nome_comercial', 'dt' => 'nome_comercial'],
                ['db' => 'c.cnpj', 'dt' => 'cnpj'],
                [
                    'db' => 'vd.regra_venda',
                    'dt' => 'regra_venda',
                    'formatter' => function ($value, $row) {
                        return status_regra_venda($value);
                    }
                ],
                [
                    'db' => 'c.razao_social',
                    'dt' => 'comprador',
                    'formatter' => function ($value, $row) {

                        return "{$row['cnpj']} - {$value}";
                    }
                ],
                [
                    'db' => 'vd.id',
                    'dt' => 'preco',
                    'formatter' => function ($value, $row) {

                        $preco_unit = $this->price->getPrice([
                            'id_fornecedor' => $this->session->id_fornecedor,
                            'codigo' => $row['codigo'],
                            'id_estado' => $this->session->id_estado
                        ]);

                        return number_format($preco_unit, 4, ',', '.');
                    }
                ],
                [
                    'db' => 'vd.id',
                    'dt' => 'preco_desconto',
                    'formatter' => function ($value, $row) {

                        $preco_unit = $this->price->getPrice([
                            'id_fornecedor' => $this->session->id_fornecedor,
                            'codigo' => $row['codigo'],
                            'id_estado' => $this->session->id_estado
                        ]);

                        $preco = $preco_unit - ($preco_unit * ($row['desconto_percentual'] / 100));
                        return number_format($preco, 4, ',', '.');
                    }
                ]
            ],
            [
                ['produtos_catalogo pc', 'pc.codigo = vd.codigo AND pc.id_fornecedor = vd.id_fornecedor', 'left'],
                ['produtos_lote pl', 'vd.codigo = pl.codigo AND vd.id_fornecedor = pl.id_fornecedor AND vd.lote = pl.lote', "left"],
                ['compradores c', "c.id = vd.id_cliente"],
            ],
            "vd.promocao = 1 AND 
            vd.id_cliente is not null AND
            vd.id_estado is null AND
            vd.id_fornecedor = {$this->session->userdata('id_fornecedor')} AND
            pl.validade > NOW()"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    /**
     * Remove os registros de vendas_diferenciadas promocao
     *
     * @param POST array ID venda diferenciada
     * @return json
     */
    public function delete_multiple()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $this->db->trans_begin();

            foreach ($post['el'] as $id) {
                $this->venda_diferenciada->delete($id);
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();

                $output = ['type' => 'warning', 'message' => notify_failed];
            } else {

                $this->db->trans_commit();

                $output = ['type' => 'success', 'message' => notify_delete];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Obtem a lista de compradores ou estados
     *
     * @param - POST - INT type lista (1 => estados, !1 => compradores)
     * @return json
     */
    public function list()
    {
        if ($this->input->is_ajax_request()) {

            $post = $this->input->post();

            if ($post['type'] == 1) {

                $label = "Estados";
                $id = "id_estado";
                $select = $this->estados->find("id, CONCAT(uf, ' - ', descricao) AS value", null, false, 'value ASC');
            } else {

                $label = "CNPJs";
                $id = "id_cliente";
                $select = $this->compradores->find("id, CONCAT(cnpj, ' - ', razao_social) AS value", null, false, 'value ASC');
            }

            $output = ['type' => (isset($select) && !empty($select)) ? 'success' : 'warning', 'data' => $select, 'label' => $label, 'id' => $id];


            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Exporta todos os registros do datatable de promocoes
     *
     * @return file
     */
    public function exportar()
    {
        $this->db->select("
            vd.codigo, 
            vd.id_estado, 
            vd.desconto_percentual, 
            pc.nome_comercial AS produto, 
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
        $this->db->join('estados e', "e.id = vd.id_estado");
        $this->db->join('produtos_catalogo pc', "pc.codigo = vd.codigo AND pc.id_fornecedor = vd.id_fornecedor", "left");
        $this->db->join('produtos_lote pl', 'vd.codigo = pl.codigo AND vd.id_fornecedor = pl.id_fornecedor AND vd.lote = pl.lote', "left");
        $this->db->where('pc.id_fornecedor', $this->session->id_fornecedor);
        $this->db->where('vd.id_fornecedor', $this->session->id_fornecedor);
        $this->db->where('vd.promocao', 1);
        $this->db->where('vd.id_estado is not null');
        $this->db->where('vd.id_cliente is null');
        $this->db->where("pl.validade > NOW()");
        $this->db->group_by("codigo, id_estado");
        $this->db->order_by("produto ASC");

        $query_estados = $this->db->get()->result_array();

        $this->db->select("
            vd.id_cliente, 
            vd.codigo, 
            vd.desconto_percentual, 
            pc.nome_comercial AS produto, 
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
        $this->db->join('compradores c', 'c.id = vd.id_cliente');
        $this->db->join('produtos_catalogo pc', "pc.codigo = vd.codigo", "left");
        $this->db->join('produtos_lote pl', 'vd.codigo = pl.codigo AND vd.id_fornecedor = pl.id_fornecedor AND vd.lote = pl.lote', 'left');
        $this->db->where('pc.id_fornecedor', $this->session->id_fornecedor);
        $this->db->where('vd.id_fornecedor', $this->session->id_fornecedor);
        $this->db->where('vd.promocao', 1);
        $this->db->where('vd.id_cliente is not null');
        $this->db->where('vd.id_estado is null');
        $this->db->where("pl.validade > NOW()");
        $this->db->order_by("produto ASC");

        $query_clientes = $this->db->get()->result_array();

        if (count($query_estados) < 1) {
            $query_estados[] = [
                'id_estado' => '',
                'codigo' => '',
                'desconto_percentual' => '',
                'produto' => '',
                'preco' => '',
                'preco_desconto' => '',
                'estado' => '',
                'regra_venda' => ''
            ];
        } else {

            foreach ($query_estados as $kk => $row) {

                $preco_unit = $this->price->getPrice([
                    'id_fornecedor' => $this->session->id_fornecedor,
                    'codigo' => $row['codigo'],
                    'id_estado' => $this->session->id_estado
                ]);

                $preco_desconto = $preco_unit - ($preco_unit * ($row['desconto_percentual'] / 100));

                $query_estados[$kk]['preco'] = number_format($preco_unit, 4, ',', '.');
                $query_estados[$kk]['preco_desconto'] = number_format($preco_desconto, 4, ',', '.');
            }

        }

        if (count($query_clientes) < 1) {
            $query_clientes[] = [
                'id_cliente' => '',
                'codigo' => '',
                'desconto_percentual' => '',
                'produto' => '',
                'preco' => '',
                'preco_desconto' => '',
                'cliente' => '',
                'regra_venda' => ''
            ];
        } else {

            foreach ($query_clientes as $kk => $row) {

                $preco_unit = $this->price->getPrice([
                    'id_fornecedor' => $this->session->id_fornecedor,
                    'codigo' => $row['codigo'],
                    'id_estado' => $this->session->id_estado
                ]);

                $preco_desconto = $preco_unit - ($preco_unit * ($row['desconto_percentual'] / 100));

                $query_clientes[$kk]['preco'] = number_format($preco_unit, 4, ',', '.');
                $query_clientes[$kk]['preco_desconto'] = number_format($preco_desconto, 4, ',', '.');
            }

        }

        $dados_page1 = ['dados' => $query_estados, 'titulo' => 'Estados'];
        $dados_page2 = ['dados' => $query_clientes, 'titulo' => 'Compradores'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page1, $dados_page2);

        if ($exportar['status'] == false) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }
}

/* End of file: Vendas_diferenciadas.php */
