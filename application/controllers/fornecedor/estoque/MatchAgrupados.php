<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MatchAgrupados extends CI_Controller
{

    private $views;
    private $route;

    public function __construct()
    {
        parent::__construct();

        $this->views = "fornecedor/produtos/de_para/";
        $this->route = base_url("fornecedor/estoque/MatchAgrupados/");

        $this->load->model("M_produtos_fornecedores", "produtos_fornecedores");
        $this->load->model("Produto_fornecedor_validade", "pfv");
        $this->load->model("produto_marca_sintese");
        $this->load->model("m_produto_fornecedor_sintese", "pfs");
        $this->load->model("M_match", "mat");
    }


    public function index()
    {
        $page_title = "Catalogo Agrupado";

        //     $data['to_datatable'] = "{$this->route}to_datatables_pre_consolidados/";
        $data['url_update'] = "{$this->route}match/";

        // TEMPLATE
        $data['header'] = $this->template->header([
            'title' => $page_title,
        ]);

        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();

        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
            ]
        ]);

        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/jquery.form.min.js',
                THIRD_PARTY . 'plugins/jquery-validation-1.19.1/dist/jquery.validate.min.js'
            ]
        ]);

        $data['produtos'] = $this->db->query("select codigo, nome_comercial, descricao, count(0) as total from produtos_catalogo where id_fornecedor = {$this->session->id_fornecedor} and ocultar_de_para = 0
                                        group by descricao
                                        having total > 1")->result_array();

        $this->load->view("{$this->views}/depara_agrupados", $data);
    }

    public function ocultar($codigo)
    {
        $codigos = $this->get_codigos($codigo);

        $this->db->trans_begin();

        if (!empty($codigos)) {
            $elementos = $codigos;

            $els = [];
            foreach ($elementos as $elemento) {
                $this->db->where('id_fornecedor', $this->session->id_fornecedor);
                $this->db->where('codigo', $elemento);

                $data = [
                    'ocultar_de_para' => 1,
                    'bloqueado' => 1,
                    'ativo' => 0,
                ];

                $this->db->update('produtos_catalogo', $data);
            }


            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $warn = ['type' => 'warning', 'message' => 'Erro ao realizar a tarefa'];
            } else {
                $this->db->trans_commit();
                $warn = ['type' => 'success', 'message' => count($codigos) . ' - Ocultados com sucesso'];
            }


            $this->output->set_content_type('application/json')->set_output(json_encode($warn));

        }

    }

    public function match($id_pf)
    {

        $dados = $this->db
            ->where('codigo', $id_pf)
            ->where('id_fornecedor', $this->session->id_fornecedor)
            ->get('produtos_catalogo')->row_array();

        $aprese = (!empty($dados['apresentacao'])) ? $dados['apresentacao'] : $dados['descricao'];

        $page_title = "{$dados['nome_comercial']} -  {$aprese}<small><br> Marca: {$dados['marca']}</small>";

        $data['to_datatable'] = "{$this->route}to_datatables_sintese/{$id_pf}";
        $data['url_update'] = "{$this->route}make/{$id_pf}";
        $data['url_ocultar'] = "{$this->route}make/{$id_pf}";

        $data['url_notFound'] = "{$this->route}not_found/{$id_pf}";

        // TEMPLATE
        $data['header'] = $this->template->header([
            'title' => $page_title,
        ]);

        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();

        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnVoltar',
                    'url' => $_SERVER['HTTP_REFERER'],
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar_combinacao/{$id_pf}",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel',
                    'label' => 'Exportar Excel'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnOcultar',
                    'url' => "{$this->route}/ocultar/{$id_pf}",
                    'class' => 'btn-danger',
                    'icone' => 'fa-ban',
                    'label' => 'Ocultar produto'
                ],
            ]
        ]);

        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/jquery.form.min.js',
                THIRD_PARTY . 'plugins/jquery-validation-1.19.1/dist/jquery.validate.min.js'
            ]
        ]);

        $this->load->view("{$this->views}/match", $data);
    }

    public function make($codigo)
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            $codigos = $this->get_codigos($codigo);

            $oncoprod = explode(',', ONCOPROD);

            $this->db->trans_begin();

            if (in_array($this->session->id_fornecedor, $oncoprod)) {

                foreach ($oncoprod as $fornecedor) {

                    $produto = $this->db
                        ->where('codigo', $codigo)
                        ->where('id_fornecedor', $fornecedor)
                        ->get('produtos_catalogo')->row_array();


                    $this->db->trans_begin();

                    $log = [];

                    foreach ($post['produtos'] as $id_sintese) {

                        $this->db->where('id_sintese', $id_sintese);
                        $this->db->where('cd_produto', $produto['id']);
                        $this->db->where('id_fornecedor', $fornecedor);
                        $old = $this->db->get('produtos_fornecedores_sintese')->row_array();

                        // $old = $this->pfs->find("*", "id_sintese = {$id_sintese} and cd_produto = {$produto['id']} and id_fornecedor = {$fornecedor}", true);
                        if (empty($old)) {
                            $data = [
                                "id_sintese" => $id_sintese,
                                "cd_produto" => $codigo,
                                "id_usuario" => $this->session->id_usuario,
                                "id_fornecedor" => $fornecedor
                            ];

                            $this->pfs->insert($data);
                        }
                        $log[] = ['codigo' => $codigo, 'id_fornecedor' => $fornecedor];
                    }

                    if ($this->db->trans_status() === false) {
                        $warning = [
                            "type" => "warning",
                            "message" => "Erro ao combinar produtos"
                        ];

                        $this->db->trans_rollback();
                    } else {

                        $warning = ["type" => "success", "message" => "Combinação de produtos realizada."];

                        $this->db->trans_commit();
                    }
                }
            } else {

                foreach ($codigos as $prod) {
                    $produto = $this->db
                        ->where('codigo', $prod)
                        ->where('id_fornecedor', $this->session->id_fornecedor)
                        ->get('produtos_catalogo')->row_array();

                    $log = [];

                    foreach ($post['produtos'] as $id_sintese) {
                        $old = $this->pfs->find("*", "id_sintese = {$id_sintese} and cd_produto = {$produto['codigo']} and id_fornecedor = {$this->session->id_fornecedor}", true);

                        if (empty($old)) {

                            $data = [
                                "id_sintese" => $id_sintese,
                                "cd_produto" => $produto['codigo'],
                                "id_usuario" => $this->session->id_usuario,
                                "id_fornecedor" => $this->session->id_fornecedor
                            ];

                            $this->pfs->insert($data);

                        }

                        $this->db->where("codigo", $produto['codigo']);
                        $this->db->where("id_fornecedor", $this->session->id_fornecedor);
                        $this->db->update('produtos_catalogo', ['ocultar_de_para' => 1]);

                        $log[] = ['codigo' => $codigo, 'id_fornecedor' => $this->session->id_fornecedor];
                    }
                }

                if ($this->db->trans_status() === false) {
                    $error = $this->db->error()['message'];
                    $warning = ["type" => "warning", "message" => "Erro ao combinar produtos: {$error}"];

                    $this->db->trans_rollback();
                } else {

                    $warning = ["type" => "success", "message" => "Combinação de produtos realizada.", "url" => $this->route];

                    $this->db->trans_commit();
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
    }

    private function get_codigos($codigo)
    {
        $produto = $this->db
            ->where('codigo', $codigo)
            ->where('id_fornecedor', $this->session->id_fornecedor)
            ->get('produtos_catalogo')->row_array();


        $this->db->select('codigo');
        $this->db->where('descricao', $produto['descricao']);
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);
        $codigos = $this->db->get('produtos_catalogo')->result_array();

        $id = [];

        foreach ($codigos as $codigo) {
            $id[] = $codigo['codigo'];
        }

        return $id;

    }

    public function to_datatables_consolidados()
    {
        $r = $this->datatable->exec(
            $this->input->get(),
            'vw_produtos_fornecedores_sintese',
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'id_produto', 'dt' => 'id_produto'],
                ['db' => 'id_sintese', 'dt' => 'id_sintese'],
                ['db' => 'codigo', 'dt' => 'codigo'],
                [
                    'db' => 'nome_comercial',
                    'dt' => 'nome_comercial'
                ],
                [
                    'db' => 'apresentacao',
                    'dt' => 'produto_descricao', "formatter" => function ($d, $r) {
                    $item = $this->db->select("descricao, marca")->where('id_sintese', $r['id_sintese'])->get('produtos_marca_sintese')->row_array();
                    return $r['nome_comercial'] . " - " . $d . "<hr> <strong>Origem: </strong> {$item['descricao']} <br> ";
                }],
                ['db' => 'marca', 'dt' => 'marca'],
                ['db' => 'data_atualizacao', 'dt' => 'data_atualizacao'],
            ],
            null,
            'id_fornecedor = ' . $this->session->userdata('id_fornecedor') . " AND id_sintese <> 0 AND id_produto <> 0",
            "codigo, id_sintese"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function to_datatables_pre_consolidados()
    {
        $r = $this->datatable->exec(
            $this->input->get(),
            'vw_produtos_pre_match',
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'id_produto', 'dt' => 'id_produto'],
                ['db' => 'id_sintese', 'dt' => 'id_sintese'],
                ['db' => 'codigo', 'dt' => 'codigo'],
                [
                    'db' => 'nome_comercial',
                    'dt' => 'nome_comercial'
                ],
                [
                    'db' => 'descricao',
                    'dt' => 'produto_descricao', "formatter" => function ($d, $r) {
                    $item = $this->db->select("descricao, marca")->where('id_sintese', $r['id_sintese'])->get('produtos_marca_sintese')->row_array();
                    return "<strong>Catalogo: </strong> " . $r['nome_comercial'] . " - " . $d . "<hr> <strong>Encontrado: </strong> {$item['descricao']} <br> ";
                }],
                ['db' => 'marca', 'dt' => 'marca'],
                ['db' => 'data_atualizacao', 'dt' => 'data_atualizacao'],
            ],
            null,
            'id_fornecedor = ' . $this->session->userdata('id_fornecedor') . " AND id_sintese <> 0 AND id_produto <> 0",
            "codigo, id_sintese"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function to_datatables_consolidados_agrupados()
    {
        $r = $this->datatable->exec(
            $this->input->get(),
            'vw_pfs_agrupados',
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'id_produto', 'dt' => 'id_produto'],
                ['db' => 'id_sintese', 'dt' => 'id_sintese'],
                ['db' => 'codigo', 'dt' => 'codigo'],
                ['db' => 'produto_descricao', 'dt' => 'produto_descricao'],
                ['db' => 'marca', 'dt' => 'marca'],
                ['db' => 'data_atualizacao', 'dt' => 'data_atualizacao'],
            ],
            null,
            'id_fornecedor = ' . $this->session->userdata('id_fornecedor') . " AND id_sintese <> 0 AND id_produto <> 0 AND id_estado = {$this->session->id_estado}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function to_datatables_sintese($id_pf)
    {
        $get = $this->input->get();
        $dados = $this->db->select("*")->where("codigo = {$id_pf} and id_fornecedor = {$this->session->id_fornecedor}")->get('produtos_catalogo')->row_array();

        $where = NULL;

        if (empty($get['columns'][2]['search']['value']) and empty($get['columns'][3]['search']['value'])) {
            if (isset($dados['descricao']) && !empty($dados['descricao'])) {
                $where .= "descricao like '%{$dados['descricao']}%' AND ";
                $where .= "complemento like '%{$dados['descricao']}%' AND ";
            } else {
                if (isset($dados['nome_comercial']) && !empty($dados['nome_comercial'])) {
                    $exp = explode(' ', $dados['nome_comercial']);
                    $where .= "descricao like '%{$exp[0]}%' AND ";
                    $where .= "complemento like '%{$exp[0]}%' AND ";
                }
            }

            if (isset($dados['marca']) && !empty($dados['marca']) && $dados['marca'] != '0') {
                $exp = explode(' ', $dados['marca']);
                $marca = strtolower($exp[0]);
                $where .= "(LOWER(marca) like '%{$marca}%' OR id_marca = {$dados['id_marca']}) AND ";
            }

            $where = rtrim($where, 'AND ');
        }


        /* if (isset($dados['apresentacao']) && !empty($dados['apresentacao'])) {
             $where .= "MATCH (complemento, descricao) AGAINST ('{$dados['descricao']}') AND ";
         }*/


        $r = $this->datatable->exec(
            $this->input->get(),
            'produtos_marca_sintese',
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'id_sintese', 'dt' => 'id_sintese'],
                ['db' => 'id_produto', 'dt' => 'id_produto'],
                ['db' => 'descricao', 'dt' => 'descricao'],
                ['db' => 'marca', 'dt' => 'marca'],
            ],
            null,
            $where,
            "id_produto"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }


}

/* End of file Controllername.php */
