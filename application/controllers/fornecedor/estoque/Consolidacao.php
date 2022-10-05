<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Consolidacao extends MY_Controller
{

    private $views;
    private $route;

    public function __construct()
    {
        parent::__construct();

        $this->views = "fornecedor/produtos/de_para/";
        $this->route = base_url("fornecedor/estoque/consolidacao/");

        $this->load->model("M_produtos_fornecedores", "produtos_fornecedores");
        $this->load->model("Produto_fornecedor_validade", "pfv");
        $this->load->model("produto_marca_sintese");
        $this->load->model("m_produto_fornecedor_sintese", "pfs");
        $this->load->model("M_match", "mat");
    }

    public function make($codigo)
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            $oncoprod = explode(',', ONCOPROD);

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

                $produto = $this->db
                    ->where('codigo', $codigo)
                    ->where('id_fornecedor', $this->session->id_fornecedor)
                    ->get('produtos_catalogo')->row_array();


                $this->db->trans_begin();

                $log = [];
                $usuariosMatch = [421, 387, 15];
                $id_usuario = $this->session->id_usuario;

                foreach ($post['produtos'] as $id_sintese) {
                    $old = $this->pfs->find("*", "id_sintese = {$id_sintese} and cd_produto = {$produto['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
                    if (empty($old)) {
                        $data = [
                            "id_sintese" => $id_sintese,
                            "cd_produto" => $codigo,
                            "id_usuario" => $this->session->id_usuario,
                            "id_fornecedor" => $this->session->id_fornecedor
                        ];

                        $in = $this->pfs->insert($data);

                        if (array_search($id_usuario, $usuariosMatch) ){

                            $dt['id_usuario'] = $id_usuario;
                            $dt['id_produto'] = $codigo;
                            $dt['id_cliente'] = $this->session->id_fornecedor;
                            $dt['distribuidor'] = $this->session->id_fornecedor;
                            $dt['integrador'] = 1;

                            $this->db->insert('log_de_para', $dt);

                        }
                    }
                    $log[] = ['codigo' => $codigo, 'id_fornecedor' => $this->session->id_fornecedor];
                }

                if ($this->db->trans_status() === false) {

                    $warning = ["type" => "warning", "message" => "Erro ao combinar produtos"];

                    $this->db->trans_rollback();
                } else {

                    $warning = ["type" => "success", "message" => "Combinação de produtos realizada.", 'url' => ''];

                    $this->db->trans_commit();
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
    }

    public function unlink($codigo, $id_sintese)
    {
        if ($this->input->method() == 'post') {

            $oncoprod = explode(',', ONCOPROD);

            if (in_array($this->session->id_fornecedor, $oncoprod)) {

                $this->db->trans_begin();

                foreach ($oncoprod as $fornecedor) {


                    $this->db->where('cd_produto', $codigo);
                    $this->db->where('id_sintese', $id_sintese);
                    $this->db->where('id_fornecedor', $fornecedor);
                    $this->db->delete('produtos_fornecedores_sintese');

                    #$this->db->query("DELETE FROM produtos_fornecedores_sintese WHERE cd_produto = {$codigo} AND id_sintese = {$id_sintese} and id_fornecedor = {fornecedor}");

                    if ($this->db->trans_status() == false) {

                        $this->db->trans_rollback();

                        $warning = ["type" => "warning", "message" => $this->db->error()];
                    } else {

                        $this->db->trans_commit();

                        $warning = ["type" => "success", "message" => "Produto desvinculado e retornado para a lista de DE -> PARA"];
                    }
                }
            } else {

                $this->db->trans_begin();

                $this->db->where('cd_produto', $codigo);
                $this->db->where('id_sintese', $id_sintese);
                $this->db->where('id_fornecedor', $this->session->id_fornecedor);
                $this->db->delete('produtos_fornecedores_sintese');

                #$this->db->query("DELETE FROM produtos_fornecedores_sintese WHERE cd_produto = {$codigo} AND id_sintese = {$id_sintese} and id_fornecedor = {$this->session->id_fornecedor}");

                if ($this->db->trans_status() == false) {
                    $this->db->trans_rollback();
                    $warning = [
                        "type" => "warning",
                        "message" => "Não foi possível"
                    ];
                } else {
                    $this->db->trans_commit();
                    $warning = [
                        "type" => "success",
                        "message" => "Produto desvinculado e retornado para a lista de DE -> PARA"
                    ];
                }

            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));

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
                /* [
                     'type' => 'button',
                     'id' => 'btnOcultar',
                     'url' => "{$this->route}/ocultar/{$id_pf}",
                     'class' => 'btn-danger',
                     'icone' => 'fa-ban',
                     'label' => 'Ocultar produto'
                 ],*/
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

    public function index()
    {
        $page_title = "De -> Para";

        $data['to_datatable'] = "{$this->route}to_datatables/";
        $data['url_update'] = "{$this->route}match/";
        $data['url_block'] = base_url("fornecedor/estoque/produtos/not_found/");

        // TEMPLATE
        $data['header'] = $this->template->header([
            'title' => $page_title,
        ]);

        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();

        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                // [
                //     'type' => 'a',
                //     'id' => 'btnCombinados',
                //     'url' => "{$this->route}combinados",
                //     'class' => 'btn-info',
                //     'icone' => 'fa-check',
                //     'label' => 'Revisar De/Para Realizados'
                // ],
                [
                    'type' => 'button',
                    'id' => 'ocultarSelecionados',
                    'url' => "{$this->route}/ocultar",
                    'class' => 'btn-warning',
                    'icone' => 'fa-ban',
                    'label' => 'Ocultar Selecionados'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel',
                    'label' => 'Exportar Excel'
                ],
                [
                    'type' => 'a',
                    'id' => 'btnConsolidados',
                    'url' => "{$this->route}consolidados",
                    'class' => 'btn-primary',
                    'icone' => 'fa-arrow-right',
                    'label' => 'Produtos com DE->PARA'
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

    public function consolidados()
    {
        $page_title = "Produtos que já possuem ID Sintese";

        $data['to_datatable'] = "{$this->route}to_datatables_consolidados/";
        $data['url_update'] = "{$this->route}unlink/";

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
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar_consolidados",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel',
                    'label' => 'Exportar Excel'
                ],
                [
                    'type' => 'a',
                    'id' => 'bdnDePAra',
                    'url' => "{$this->route}",
                    'class' => 'btn-primary',
                    'icone' => 'fa-arrow-right',
                    'label' => 'Produtos sem DE->PARA'
                ]
            ]
        ]);

        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/jquery.form.min.js',
                THIRD_PARTY . 'plugins/jquery-validation-1.19.1/dist/jquery.validate.min.js'
            ]
        ]);

        $this->load->view("{$this->views}/main_consolidados", $data);
    }

    public function preMatch()
    {
        $page_title = "Produtos combinados automaticamente";

        $data['to_datatable'] = "{$this->route}to_datatables_pre_consolidados/";
        $data['url_update'] = "{$this->route}unlink/";

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
                    'id' => 'btnRejeitar',
                    'url' => "{$this->route}rejeitar",
                    'class' => 'btn-warning',
                    'icone' => 'fa-ban',
                    'label' => 'Rejeitar Selecionados'
                ],
                [
                    'type' => 'a',
                    'id' => 'btnAprovar',
                    'url' => "{$this->route}aprovar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-check',
                    'label' => 'Aprovar Selecionados'
                ]
            ]
        ]);

        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/jquery.form.min.js',
                THIRD_PARTY . 'plugins/jquery-validation-1.19.1/dist/jquery.validate.min.js'
            ]
        ]);

        $this->load->view("{$this->views}/main_pre_consolidados", $data);
    }

    public function agrupados()
    {
        $page_title = "Catalogo Agrupado";

        //     $data['to_datatable'] = "{$this->route}to_datatables_pre_consolidados/";
        $data['url_update'] = "{$this->route}unlink/";

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

        $data['produtos'] = $this->db->query("select id, descricao, count(0) as total from produtos_catalogo where id_fornecedor = {$this->session->id_fornecedor} and ocultar_de_para = 0
                                        group by descricao
                                        having total > 1")->result_array();

        $this->load->view("{$this->views}/depara_agrupados", $data);
    }

    public function aprovar()
    {
        if ($this->input->method() == 'post') {
            $id_fornecedor = $this->session->id_fornecedor;
            $post = $this->input->post();
            if (isset($post['el'])) {
                if ($this->mat->doMatch($post['el'], $this->session->id_usuario)) {
                    $warning = [
                        'type' => 'success',
                        'message' => 'Aprovados com sucesso!'
                    ];
                } else {
                    $warning = [
                        'type' => 'warning',
                        'message' => 'Houve um erro ao aprovar os registros'
                    ];
                }
            } else {
                $warning = [
                    'type' => 'warning',
                    'message' => 'Nenhum produto foi selecionado'
                ];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
    }

    public function rejeitar()
    {
        if ($this->input->method() == 'post') {
            $id_fornecedor = $this->session->id_fornecedor;
            $post = $this->input->post();

            if (isset($post['el'])) {
                if ($this->mat->undoMatch($post['el'])) {
                    $warning = [
                        'type' => 'success',
                        'message' => 'Retornados a lista do de/para'
                    ];
                } else {
                    $warning = [
                        'type' => 'warning',
                        'message' => 'Houve um erro ao gravar os registros'
                    ];
                }
            } else {
                $warning = [
                    'type' => 'warning',
                    'message' => 'Nenhum produto foi selecionado'
                ];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
    }

    public function combinados()
    {
        $page_title = "Produtos que já possuem ID Sintese";

        $data['to_datatable'] = "{$this->route}to_datatables_consolidados_agrupados/";
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

                [
                    'type' => 'a',
                    'id' => 'bdnDePAra',
                    'url' => "{$this->route}",
                    'class' => 'btn-primary',
                    'icone' => 'fa-arrow-right',
                    'label' => 'Produtos sem DE->PARA'
                ]
            ]
        ]);

        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/jquery.form.min.js',
                THIRD_PARTY . 'plugins/jquery-validation-1.19.1/dist/jquery.validate.min.js'
            ]
        ]);

        $this->load->view("{$this->views}main_consolidados_agrupados", $data);
    }

    public function not_found($id_prod = null)
    {
        if ($this->input->method() == 'post') {

            $produto = $this->db
                ->where('codigo', $id_prod)
                ->where('id_fornecedor', $this->session->id_fornecedor)
                ->get('produtos_catalogo')->row_array();
            $encode = json_encode($produto);


            $data_insert = [
                "codigo" => $produto['codigo'],
                "marca" => $produto['marca'],
                "id_fornecedor" => $produto['id_fornecedor'],
                'id_usuario' => $this->session->id_usuario
            ];

            $this->db->insert("produtos_aguardando_sintese", $data_insert);

            // Atualiza o produto no catalogo
            $this->db->where('codigo', $id_prod);
            $this->db->where('id_fornecedor', $this->session->id_fornecedor);
            $this->db->update('produtos_catalogo', ['ocultar_de_para' => 1]);

            $template = file_get_contents(base_url('/public/html/template_mail/not_found_de_para.html'));
            $subject = "PRODUTO NÃO ENCONTRADO NO DE -> PARA | ID: {$id_prod}";
            $body = "
            <p>Prezado, </p>
            <p>O fornecedor ({$this->session->id_fornecedor}) {$this->session->razao_social} não encontrou o item no De -> Para e enviou a seguinte mensagem. ID: {$id_prod}</p>
            <p>CNPJ: {$this->session->cnpj}</p>
            <p>Código: {$produto['codigo']}</p>
            <p>Produto: {$produto['nome_comercial']} {$produto['descricao']} - {$produto['apresentacao']}</p>
            <p>Usuário: {$this->session->nome}</p>
            <hr>
            <p>{$this->input->post('mensagem')}</p>
            ";

            $body = str_replace(['%body%'], [$body], $template);


            $notificar = [
                "to" => "suporte@pharmanexo.com.br, marlon.boecker@pharmanexo.com.br",
                "greeting" => "",
                "subject" => $subject,
                "message" => $body,
            ];

            $enviarEmail = $this->notify->send($notificar);

            $this->output->set_content_type('application/json')->set_output(json_encode(['type' => 'success', 'message' => 'E-mail enviado com sucesso.']));

        } else {
            $data = [
                "title" => 'Enviar mensagem para o administrativo',
                "url_send" => $this->route . "not_found/{$id_prod}"
            ];

            $this->load->view("{$this->views}modal", $data);
        }
    }

    public function ocultar()
    {
        $post = $this->input->post();

        $this->db->trans_begin();

        if (isset($post['el'])) {
            $elementos = $post['el'];

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
                $warn = ['type' => 'success', 'message' => count($post['el']) . ' - Ocultados com sucesso'];
            }


            $this->output->set_content_type('application/json')->set_output(json_encode($warn));

        }

    }

    public function ocultarProd($codigo)
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

    public function to_datatables()
    {
        $r = $this->datatable->exec(
            $this->input->get(),
            'vw_catalogo_sem_sintese',
            [
                ['db' => 'vw_catalogo_sem_sintese.id', 'dt' => 'id'],
                ['db' => 'vw_catalogo_sem_sintese.codigo', 'dt' => 'codigo'],
                [
                    'db' => 'vw_catalogo_sem_sintese.nome_comercial',
                    'dt' => 'nome_comercial'
                ],
                [
                    'db' => 'vw_catalogo_sem_sintese.descricao',
                    'dt' => 'descricao'
                ],
                [
                    'db' => 'vw_catalogo_sem_sintese.apresentacao',
                    'dt' => 'produto_descricao', "formatter" => function ($d, $r) {
                    return $r['nome_comercial'] . " - " . (empty($d) ? $r['descricao'] : $d);
                }],
                ['db' => 'vw_catalogo_sem_sintese.marca', 'dt' => 'marca']
            ],
            null,
            "id_fornecedor = {$this->session->id_fornecedor}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
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

    public function exportar()
    {
        $this->db->select("codigo, 
        CASE WHEN descricao is null THEN CONCAT(nome_comercial, ' - ', apresentacao) ELSE CONCAT(nome_comercial, ' - ', descricao) END AS descricao");
        $this->db->from("vw_catalogo_sem_sintese");
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);
        $this->db->order_by('codigo');

        $query = $this->db->get()->result_array();

        if (count($query) < 1) {
            $query[] = [
                'codigo' => '',
                'descricao' => ''
            ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'Produtos'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ($exportar['status'] == false) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }

    public function exportar_consolidados()
    {
        $this->db->select("codigo, 
            id_produto,
            id_sintese,
            CONCAT(nome_comercial, ' - ', produto_descricao) AS descricao");
        $this->db->from("vw_produtos_fornecedores_sintese");
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);
        $this->db->where("id_sintese <> 0 AND id_produto <> 0");
        $this->db->group_by('codigo, id_sintese');

        $query = $this->db->get()->result_array();

        if (count($query) < 1) {
            $query[] = [
                'codigo' => '',
                'id_produto' => '',
                'id_sintese' => '',
                'descricao' => ''
            ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'Produtos'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ($exportar['status'] == false) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }

    public function exportar_combinacao($id_pf)
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


        $this->db->select("id_produto, descricao");
        $this->db->from("produtos_marca_sintese");
        $this->db->where("{$where}");
        $this->db->group_by('id_produto');

        $query = $this->db->get()->result_array();

        if (count($query) < 1) {
            $query[] = [
                'id_produto' => '',
                'descricao' => ''
            ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'Produtos'];

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

/* End of file Controllername.php */
