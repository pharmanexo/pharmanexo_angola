<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Promocoes extends Admin_controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('admin/promocoes');
        $this->views = 'admin/promocoes';

        $this->load->model('m_promocoes', 'promocoes');
        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_produtos_fornecedores', 'produtos_fornecedores');
        $this->load->model('m_produto', 'produto');
        $this->load->model('produto_fornecedor_validade');
        $this->load->model('m_estados', 'estado');
        $this->load->model("produto_fornecedor_validade", "pfv");
    }

    public function index()
    {
        $page_title = "Promoções";
        $data['to_datatable'] = "{$this->route}/to_datatable/";
        $data['url_delete'] = "{$this->route}/delete/";
        $data['url_delete_multiple'] = "{$this->route}/delete_multiple/";
        // $data['url_update'] = base_url('fornecedor/estoque/produtos_vencer/add_regra');
        $data['url_update'] = "{$this->route}/update";
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
                    'url' =>   $data['url_delete_multiple'],
                    'class' => 'btn-danger',
                    'icone' => 'fa-trash',
                    'label' => 'Excluir Selecionados'
                ]
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

    public function delete($codigo)
    {
        if ($this->promocoes->excluir($codigo)) {

            //log
            $this->auditor->setLog('delete', 'admin/promocoes', ['codigo' => $codigo]);

            $newdata = [
                'type' => 'success',
                'message' => 'Excluido com sucesso'
            ];
        } else {
            $newdata = [
                'type' => 'warning',
                'message' => 'Erro ao excluir'
            ];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($newdata));
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

                //log
                $this->auditor->setLog('delete', 'admin/promocoes', ['codigos' => $post['el']]);

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

                //log
                $this->auditor->setlog('update', 'admin/promocoes', $post);

            }else{
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

    public function to_datatable()
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'vendas_diferenciadas promocoes',
            [
                [ 'db' => 'promocoes.id', 'dt' => 'id' ],
                [ 'db' => 'promocoes.id_produto', 'dt' => 'id_produto' ],
                [ 'db' => 'promocoes.codigo', 'dt' => 'codigo' ],
                [ 'db' => 'promocoes.desconto_percentual', 'dt' => 'desconto_percentual' ],
                [ 'db' => 'promocoes.comissao', 'dt' => 'comissao' ],
                [ 'db' => 'promocoes.quantidade', 'dt' => 'quantidade' ],
                [ 'db' => 'promocoes.lote', 'dt' => 'lote' ],
                [ 'db' => 'produtos_catalogo.nome_comercial', 'dt' => 'nome_comercial' ],
                [ 'db' => 'promocoes.dias', 'dt' => 'dias' ],
                [
                    'db' => 'produtos_catalogo.apresentacao',
                    'dt' => 'produto_descricao', "formatter" => function ($d, $r) {
                    return $r['nome_comercial'] . " - " . $d;
                }],
                [
                    'db' => 'produtos_catalogo.preco_unidade',
                    'dt' => 'preco',
                    'formatter' => function ($value, $row) {
                        return number_format($value, 4, ',', '.');
                    }
                ],
                [
                    'db' => 'produtos_catalogo.preco_unidade',
                    'dt' => 'preco_desconto',
                    'formatter' => function ($value, $row) {
                        $preco = $value - ($value * ($row['desconto_percentual'] / 100));
                        return number_format($preco, 4, ',', '.');
                    }
                ]
            ],
            [
                ['produtos_catalogo', 'produtos_catalogo.codigo = promocoes.codigo', 'LEFT']
            ],
            "promocao = 1",
            "produtos_catalogo.codigo, produtos_catalogo.id_fornecedor, produtos_catalogo.id_marca"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
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

}

/* End of file: Vendas_diferenciadas.php */
