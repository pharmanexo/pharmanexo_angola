<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Promocoes extends Admin_controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('admin/produtos/promocoes');
        $this->views = 'admin/produtos/promocoes';

        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_estados', 'estado');
        $this->load->model('m_venda_diferenciada', 'venda_diferenciada');
        $this->load->model('m_promocoes', 'promocoes');
        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_produto', 'produto');
        $this->load->model("produto_fornecedor_validade", "pfv");
    }

    public function index()
    {
        $page_title = "Promoções";
    
        $data['to_datatable_estado'] = "{$this->route}/to_datatable_estado/";
        $data['to_datatable_cnpj'] = "{$this->route}/to_datatable_cnpj/";
        $data['url_delete_multiple'] = "{$this->route}/delete_multiple";
        $data['url_exportar'] = "{$this->route}/exportar/";

        $data['header'] = $this->template->header([ 'title' => 'Promoções',
        ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons'    => [
                [
                    'type' => 'button',
                    'id' => 'btnDeleteMultiple',
                    'url' =>   "{$this->route}/delete_multiple",
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

            'scripts' => []
        ]);

        $data['fornecedores'] = $this->fornecedor->find("id, nome_fantasia", null, false, 'nome_fantasia ASC');

        $this->load->view("{$this->views}/main", $data);
    }

    public function delete_multiple()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $this->db->trans_begin();

            foreach ($post['el'] as $item) {
                $this->promocoes->excluir($item);
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

    public function update($id_prod)
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $post['id_fornecedor'] = $this->session->id_fornecedor;
            $post['promocao'] = 1;

            $promocao = $this->promocoes->find("*", "id_produto = {$post['id_produto']} AND id_estado = {$post['id_estado']} and id_fornecedor = {$this->session->id_fornecedor}", true);

            $post['id'] = $promocao['id'];

            if ( $this->promocoes->update($post) ){

                $warning = ["type" => "success", "message" => ["Promoção atualizada com sucesso"]];
            }else {

                $warning = ["type" => "warning", "message" => ["Houve um erro ao atualizar a promoção. ({$this->db->error()['message']})"]];
            }

            $warning['route'] = $this->route;

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));


        } else {

            $old = $this->promocoes->find("*", "id_produto = {$id_prod} and id_fornecedor = {$this->session->id_fornecedor}", true);

            if (empty($old)) $old = [];

            $data = [
                "title" => "Configurar Promoção",
                "produto" => array_merge($old, $this->pfv->findById($id_prod)),
                "form_action" => "{$this->route}/update/{$id_prod}",
            ];

            $this->load->view("admin/produtos_vencer/modal_venda_diferenciada", $data);
        }
    }

    public function to_datatable_estado($id_fornecedor)
    {
        $fornecedor = $this->fornecedor->findById($id_fornecedor);
        $id_estado = $this->estado->find('id', "uf = '{$fornecedor['estado']}' ", TRUE)['id'];

        $data = $this->datatable->exec(
            $this->input->post(),
            'vendas_diferenciadas vd',
            [
                ['db' => 'vd.id', 'dt' => 'id'],
                ['db' => 'vd.desconto_percentual', 'dt' => 'desconto_percentual'],
                ['db' => 'pc.nome_comercial', 'dt' => 'produto_descricao'],
                ['db' => 'pc.marca', 'dt' => 'marca'],
                ['db' => 'vd.quantidade', 'dt' => 'quantidade'],
                ['db' => 'vd.dias', 'dt' => 'dias'],
                ['db' => 'vd.codigo', 'dt' => 'codigo'],
                ['db' => 'e.uf', 'dt' => 'uf'],
                [
                    'db' => 'vd.id_estado',
                    'dt' => 'preco',
                    'formatter' => function ($value, $row) use ($id_fornecedor, $id_estado) {

                        $preco_unit = $this->price->getPrice([
                            'id_fornecedor' => $id_fornecedor,
                            'codigo' => $row['codigo'],
                            'id_estado' => $id_estado
                        ]);

                        return number_format($preco_unit, 4, ',', '.');
                    }
                ],
                [
                    'db' => 'vd.id_cliente',
                    'dt' => 'preco_desconto',
                    'formatter' => function ($value, $row) use ($id_fornecedor, $id_estado) {

                        $preco_unit = $this->price->getPrice([
                            'id_fornecedor' => $id_fornecedor,
                            'codigo' => $row['codigo'],
                            'id_estado' => $id_estado
                        ]);

                        $preco = $preco_unit - ($preco_unit * ($row['desconto_percentual'] / 100));
                        return number_format($preco, 4, ',', '.');
                    }
                ],
                [
                    'db' => 'e.descricao',
                    'dt' => 'descricao',
                    'formatter' => function ($value, $row) {
                        return "{$row['uf']} - {$value}";
                    }
                ],
                [
                    'db' => 'vd.regra_venda',
                    'dt' => 'regra_venda',
                    'formatter' => function ($value, $row) {
                        return status_regra_venda($value);
                    }
                ]
            ],
            [
                ['produtos_catalogo pc', 'pc.codigo = vd.codigo AND pc.id_fornecedor = vd.id_fornecedor', 'left'],
                ['estados e', "e.id = vd.id_estado"],
                ['produtos_lote pl', 'vd.codigo = pl.codigo AND vd.id_fornecedor = pl.id_fornecedor AND vd.lote = pl.lote', "left"]
            ],
            "vd.promocao = 1 AND 
            vd.id_estado is not null AND
            vd.id_cliente is null AND
            vd.id_fornecedor = {$id_fornecedor} AND
            pl.validade > NOW()"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function to_datatable_cnpj($id_fornecedor)
    {

        $fornecedor = $this->fornecedor->findById($id_fornecedor);
        $id_estado = $this->estado->find('id', "uf = '{$fornecedor['estado']}' ", TRUE)['id'];

        $data = $this->datatable->exec(
            $this->input->post(),
            'vendas_diferenciadas vd',
            [
                ['db' => 'vd.id', 'dt' => 'id'],
                ['db' => 'vd.desconto_percentual', 'dt' => 'desconto_percentual'],
                ['db' => 'pc.nome_comercial', 'dt' => 'produto_descricao'],
                ['db' => 'pc.marca', 'dt' => 'marca'],
                ['db' => 'vd.quantidade', 'dt' => 'quantidade'],
                ['db' => 'vd.dias', 'dt' => 'dias'],
                ['db' => 'vd.codigo', 'dt' => 'codigo'],
                ['db' => 'c.cnpj', 'dt' => 'cnpj'],
                [
                    'db' => 'vd.id_estado',
                    'dt' => 'preco',
                    'formatter' => function ($value, $row) use ($id_fornecedor, $id_estado) {

                        $preco_unit = $this->price->getPrice([
                            'id_fornecedor' => $id_fornecedor,
                            'codigo' => $row['codigo'],
                            'id_estado' => $id_estado
                        ]);

                        return number_format($preco_unit, 4, ',', '.');
                    }
                ],
                [
                    'db' => 'vd.id_cliente',
                    'dt' => 'preco_desconto',
                    'formatter' => function ($value, $row) use ($id_fornecedor, $id_estado) {

                        $preco_unit = $this->price->getPrice([
                            'id_fornecedor' => $id_fornecedor,
                            'codigo' => $row['codigo'],
                            'id_estado' => $id_estado
                        ]);

                        $preco = $preco_unit - ($preco_unit * ($row['desconto_percentual'] / 100));
                        return number_format($preco, 4, ',', '.');
                    }
                ],
                [
                    'db' => 'c.razao_social',
                    'dt' => 'razao_social',
                    'formatter' => function ($value, $row) {
                        return "{$row['cnpj']} - {$value}";
                    }
                ],
                [
                    'db' => 'vd.regra_venda',
                    'dt' => 'regra_venda',
                    'formatter' => function ($value, $row) {
                        return status_regra_venda($value);
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
            vd.id_fornecedor = {$id_fornecedor} AND
            pl.validade > NOW()"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function openModal($option)
    {
        switch ($option) {
            case 'ESTADOS':
                $data['title'] = 'Selecione os Estados';
                $data['options'] = $this->estado->getList();
                $this->load->view("{$this->views}/modal_estados", $data);
                break;

            case 'CLIENTES':
                $data['title'] = 'Selecione os Compradores';
                $data['datatable_url'] = "{$this->route}/display_datatable_clientes";
                $this->load->view("{$this->views}/modal_clientes", $data);
                break;
        }
    }

    public function openUpdateModal($id)
    {
        $data = [
            'dados' => $this->promocoes->getById($id),
            'title' => "Vendas Diferenciadas",
            'lotes' => $this->get_lotes(),
            'form_action' => "{$this->route}/update/{$id}"
        ];

        $this->load->view("{$this->views}/update", $data);
    }

    /**
     * Gera arquivo excel do datatable
     *
     * @return  downlaod file
     */
    public function exportar($id_fornecedor = null)
    {
        if ( isset($id_fornecedor) ) {

            $fornecedor = $this->fornecedor->findById($id_fornecedor);
            $id_estado = $this->estado->find('id', "uf = '{$fornecedor['estado']}' ", TRUE)['id'];

            $this->db->select("
                vd.codigo, 
                vd.desconto_percentual, 
                pc.nome_comercial AS produto, 
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
            $this->db->join('estados e', "e.id = vd.id_estado");
            $this->db->join('produtos_catalogo pc', "pc.codigo = vd.codigo AND pc.id_fornecedor = vd.id_fornecedor", "left");
            $this->db->join('produtos_lote pl', 'vd.codigo = pl.codigo AND vd.id_fornecedor = pl.id_fornecedor AND vd.lote = pl.lote', "left");
            $this->db->where('pc.id_fornecedor', $id_fornecedor);
            $this->db->where('vd.id_fornecedor', $id_fornecedor);
            $this->db->where('vd.promocao', 1);
            $this->db->where('vd.id_estado is not null');
            $this->db->where('vd.id_cliente is null');
            $this->db->where("pl.validade > NOW()");
            $this->db->order_by("produto ASC");

            $query_estados = $this->db->get()->result_array();

            $this->db->select("
                vd.codigo, 
                vd.desconto_percentual, 
                pc.nome_comercial AS produto, 
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
            $this->db->join('compradores c', 'c.id = vd.id_cliente');
            $this->db->join('produtos_catalogo pc', "pc.codigo = vd.codigo", "left");
            $this->db->join('produtos_lote pl', 'vd.codigo = pl.codigo AND vd.id_fornecedor = pl.id_fornecedor AND vd.lote = pl.lote', 'left');
            $this->db->where('pc.id_fornecedor', $id_fornecedor);
            $this->db->where('vd.id_fornecedor', $id_fornecedor);
            $this->db->where('vd.promocao', 1);
            $this->db->where('vd.id_cliente is not null');
            $this->db->where('vd.id_estado is null');
            $this->db->where("pl.validade > NOW()");
            $this->db->order_by("produto ASC");

            $query_clientes = $this->db->get()->result_array();
        } else {

            $query_estados = [];
            $query_clientes = [];
        }

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
        } else {

            foreach ($query_estados as $kk => $row) {

                $preco_unit = $this->price->getPrice([
                    'id_fornecedor' => $id_fornecedor,
                    'codigo' => $row['codigo'],
                    'id_estado' => $id_estado
                ]);

                unset($query_estados[$kk]['codigo']);

                $preco_desconto = $preco_unit - ($preco_unit * ($row['desconto_percentual'] / 100));

                $query_estados[$kk]['preco'] = number_format($preco_unit, 4, ',', '.');
                $query_estados[$kk]['preco_desconto'] = number_format($preco_desconto, 4, ',', '.');
            }
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
        } else {

            foreach ($query_clientes as $kk => $row) {

                $preco_unit = $this->price->getPrice([
                    'id_fornecedor' => $id_fornecedor,
                    'codigo' => $row['codigo'],
                    'id_estado' => $id_estado
                ]);

                unset($query_clientes[$kk]['codigo']);

                $preco_desconto = $preco_unit - ($preco_unit * ($row['desconto_percentual'] / 100));

                $query_clientes[$kk]['preco'] = number_format($preco_unit, 4, ',', '.');
                $query_clientes[$kk]['preco_desconto'] = number_format($preco_desconto, 4, ',', '.');
            }
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
