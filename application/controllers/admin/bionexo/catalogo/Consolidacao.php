<?php

class Consolidacao extends MY_Controller
{
    private $views;
    private $route;
    private $bio;

    /**
     * Consolidacao constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->views = "admin/bionexo/de_para/";
        $this->route = base_url("admin/bionexo/catalogo/consolidacao/");

        $this->load->model("m_produto_marca_sintese", 'pms');
        $this->load->model("M_produto_cliente_depara", "pfs");
        $this->load->model("m_compradores", "compradores");
        $this->load->model("m_marca", "marcas");
        $this->load->model("m_responsavel_depara", "resp");

        $this->bio = $this->load->database('bionexo', true);
    }

    /**
     * Verifica se existe a sessão id_cliente)bionexo
     * Redireciona para a tela de compradores caso o usuario tente acessar diretamente a tela de depara da bionexo
     */
    public function getIdClient()
    {
        if ($this->session->has_userdata('id_cliente_bionexo')) {

            return $this->session->id_cliente_bionexo;
        } else {

            $this->session->set_userdata('warning', ['type' => 'warning', 'message' => 'É necessário escolher um cliente em Bionexo -> clientes']);

            redirect(base_url("/admin/bionexo/clientes"));
        }
    }

    /**
     * Exibe a tela inicial de produtos sem de/para
     */
    public function index()
    {
        $id_cliente = $this->getIdClient();
        $sem = count($this->db->select('*')->where('id_cliente', $id_cliente)->get('vw_produtos_clientes_sem_depara')->result_array());
        $ocultos = count($this->bio->select('id')->where('id_cliente', $id_cliente)->where('ocultar', 1)->get('catalogo')->result_array());
        $qtd_sem_depara = intval(($sem - $ocultos));


        $c = $this->compradores->findById($id_cliente);

        $page_title = "De > Para de Produtos Bionexo <br> <small>{$c['cnpj']} - {$c['nome_fantasia']}</small>";

        $data['to_datatable'] = "{$this->route}to_datatables/{$id_cliente}";
        $data['url_update'] = "{$this->route}match/{$id_cliente}/";
        $data['url_block'] = base_url("fornecedor/estoque/produtos/not_found/");


        $aberto = $this->resp->find('*', "id_cliente = {$id_cliente} AND id_usuario = {$this->session->id_usuario} and fim is null and integrador = 2", true);
        $data['depara_iniciado'] = !is_null($aberto);

        $up = 0;
        if (isset($_GET['upgrade']) && $_GET['upgrade'] = 'on'){
            $up = 1;
        }

        $class = ($qtd_sem_depara > 0) ? "" : "";
        if ($data['depara_iniciado']) {
            $button = [
                'type' => 'a',
                'id' => 'btnEnd',
                'url' => ($qtd_sem_depara == 0) ? "{$this->route}close/{$id_cliente}" : "",
                'class' => 'btn-outline-secondary dpr '. $class,
                'icone' => ' fa-times-circle',
                'label' => 'Finalizar de/para no hospital'
            ];
        } else {
            $button = [
                'type' => 'a',
                'id' => 'btnInit',
                'url' => ($qtd_sem_depara > 0) ? "{$this->route}init/{$id_cliente}/{$up}" : "",
                'class' => 'btn-outline-secondary dpr ' . $class,
                'icone' => 'far fa-play-circle',
                'label' => 'Iniciar de/para no hospital'
            ];
        }

        # TEMPLATE
        $data['header'] = $this->template->header(['title' => 'De > Para de Produtos Bionexo']);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [

                [
                    'type' => 'a',
                    'id' => 'btnBack',
                    'url' => base_url("admin/bionexo/clientes"),
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Trocar de Cliente'
                ],
                $button,
                [
                    'type' => 'a',
                    'id' => 'btnOcultados',
                    'url' => "{$this->route}produtosOcultados/{$id_cliente}",
                    'class' => 'btn-outline-danger',
                    'icone' => 'fas fa-list',
                    'label' => 'Lista de produtos Ocultados'
                ],
                [
                    'type' => 'a',
                    'id' => 'btnOcultarSelecionados',
                    'url' => "{$this->route}ocultarTodos/",
                    'class' => 'btn-danger',
                    'icone' => 'fas fa-eye-slash',
                    'label' => 'Ocultar Selecionados'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar/{$id_cliente}",
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

    /**
     * Exibe a tela inicial de produtos com de/para
     */
    public function consolidados()
    {

        $id_cliente = $this->getIdClient();

        $c = $this->compradores->findById($id_cliente);

        $page_title = "PRODUTO COM DE/PARA NO HOSPITAL <br> <small>{$c['cnpj']} - {$c['nome_fantasia']}</small>";

        $data['to_datatable'] = "{$this->route}to_datatables_consolidados/{$id_cliente}";
        $data['url_update'] = "{$this->route}unlink/";

        # TEMPLATE
        $data['header'] = $this->template->header(['title' => 'Produtos que já possuem ID Sintese']);
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

    /**
     * Combina os itens escolhidos na tela de de/para
     */
    public function make($id_cliente, $codigo)
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            $produto = $this->bio->where('codigo', $codigo)->where_in('id_cliente', $id_cliente)->get('catalogo')->row_array();

            $this->db->trans_begin();

            $log = [];

            foreach ($post['produtos'] as $id_sintese) {

                $old = $this->pfs->find("*", "id_produto_sintese = {$id_sintese} and cd_produto = '{$codigo}' and id_integrador = '2' and id_cliente = {$id_cliente}", true);

                if (empty($old)) {
                    $data = [
                        "id_produto_sintese" => $id_sintese,
                        "cd_produto" => $codigo,
                        "id_usuario" => $this->session->id_usuario,
                        "id_integrador" => 2,
                        "id_cliente" => $id_cliente
                    ];

                    $this->pfs->insert($data);


                }
            }

            if ($this->db->trans_status() === false) {

                $warning = ["type" => "warning", "message" => "Erro ao combinar produtos"];

                $this->db->trans_rollback();
            } else {

                $checkLog = $this->db
                    ->where('id_produto', $codigo)
                    ->where('id_cliente', $id_cliente)
                    ->where('id_usuario', $this->session->id_usuario)
                    ->get('log_de_para')
                    ->result_array();

                if (empty($checkLog)) {
                    $log = [
                        'id_produto' => $codigo,
                        'id_cliente' => $id_cliente,
                        'id_usuario' => $this->session->id_usuario
                    ];

                    $this->db->insert('log_de_para', $log);
                }


                $warning = ["type" => "success", "message" => "Combinação de produtos realizada.",];

                $this->db->trans_commit();
            }


            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
    }

    /**
     * Descombina os itens escolhidos na tela de de/para
     */
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

                    $warning = ["type" => "warning", "message" => "Não foi possível"];
                } else {

                    $this->db->trans_commit();

                    $warning = ["type" => "success", "message" => "Produto desvinculado e retornado para a lista de DE -> PARA"];
                }

            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));

        }
    }

    /**
     * Carrega a tela para seleção de produtos que irão ser combinados
     */
    public function match($id_cliente, $id_pf)
    {

        $dados = $this->bio->where('codigo', $id_pf)->where('id_cliente', $id_cliente)->get('catalogo')->row_array();

        $aprese = (!empty($dados['apresentacao'])) ? $dados['apresentacao'] : $dados['descricao'];

        $page_title = strtoupper("{$aprese}");

        $data['to_datatable'] = "{$this->route}to_datatables_sintese/{$id_pf}";
        $data['url_update'] = "{$this->route}make/{$id_cliente}/{$id_pf}";
        $data['url_ocultar'] = "{$this->route}ocultarProduto/{$dados['id']}/";
        $data['url_main'] = "{$this->route}";

        $data['url_notFound'] = "{$this->route}not_found/{$id_pf}";
        $data['urlNewProduct'] = "{$this->route}saveProductSintese/{$dados['id']}";

        // TEMPLATE
        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();

        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnVoltar',
                    'url' => (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : $this->route,
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ],
                [
                    'type' => 'button',
                    'id' => 'btn_ocultar',
                    'url' => "",
                    'class' => ($dados['ocultar'] == 1) ? 'btn-danger' : 'btn-outline-danger',
                    'icone' => ($dados['ocultar'] == 1) ? 'fa-eye' : 'fa-eye-slash',
                    'label' => ($dados['ocultar'] == 1) ? 'Remover Ocultação' : 'Ocultar Produto'
                ]
            ]
        ]);

        $data['dados'] = $dados;

        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/jquery.form.min.js',
                THIRD_PARTY . 'plugins/jquery-validation-1.19.1/dist/jquery.validate.min.js'
            ]
        ]);

        $this->load->view("{$this->views}/match", $data);
    }

    /**
     * Exibe os produtos do catalogo ocultados
     *
     * @param Int ID do produto no catalogo bionexo
     * @return  view
     */
    public function produtosOcultados($id_cliente)
    {

        $page_title = "Lista de produtos ocultados";

        $data['to_datatable'] = "{$this->route}to_datatables_ocultados/{$id_cliente}";
        $data['url_desocultar'] = "{$this->route}/desocultar_multiple";

        // TEMPLATE
        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();

        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnVoltar',
                    'url' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $this->route,
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnDesocultar',
                    'url' => "",
                    'class' => 'btn-primary',
                    'icone' => 'fa-eye-slash',
                    'label' => 'Desocultar Selecionados'
                ],
            ]
        ]);

        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/main_ocultadas", $data);
    }

    /**
     * Oculta/desoculta um produto do catalogo bionexo
     *
     * @param Int ID do produto no catalogo bionexo
     * @param Int flag para ocultar/desocultar
     * @return  json
     */
    public function ocultarProduto($id, $ativar = null)
    {

        $ocultar = (isset($ativar) && $ativar == 1) ? 1 : 0;

        $updt = $this->bio->where('id', $id)->update('catalogo', ['ocultar' => $ocultar]);

        if ($updt) {

            $output = ['type' => 'success', 'message' => notify_update];
        } else {

            $output = ['type' => 'warning', 'message' => notify_failed];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    /**
     * Função que altera o status dos fornecedores selecionados para excluido
     *
     * @return  json
     */
    public function desocultar_multiple()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $this->db->trans_begin();

            foreach ($post['el'] as $row) {

                $this->bio->where("id_cliente", $row['id_cliente'])->where("codigo", $row['codigo'])->update('catalogo', ['ocultar' => 0]);
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();

                $output = $this->notify->errorMessage();
            } else {
                $this->db->trans_commit();

                $output = ['type' => 'success', 'message' => notify_update];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Abre modal que envia um e-mail para o suporte/adm verificar o item não encontrado
     */
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

            $config = array(
                'protocol' => 'smtp',
                'smtp_host' => 'ssl://nuvem54.hoteldaweb.com.br',
                'smtp_port' => 465,
                'smtp_user' => 'suporte@pharmanexo.com.br',
                'smtp_pass' => 'Pharma_TI_2019',
                'validate' => true,
                'mailtype' => 'html',
                'charset' => 'utf-8',
                'newline' => '\r\n',
                'wordwrap' => true,
            );

            $to = "suporte@pharmanexo.com.br";

            $this->load->library('email', $config);
            $this->email->set_newline("\r\n");
            $this->email->set_crlf("\r\n");

            $this->email->initialize($config);
            $this->email->clear();
            $this->email->from("suporte@pharmanexo.com.br", 'Marlon Boecker');
            $this->email->to($to);

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

            $this->email->subject($subject);
            $this->email->message($body);

            $result = $this->email->send();

            $this->output->set_content_type('application/json')->set_output(json_encode(['type' => 'success', 'message' => 'E-mail enviado com sucesso.']));

        } else {
            $data = [
                "title" => 'Enviar mensagem para o administrativo',
                "url_send" => $this->route . "not_found/{$id_prod}"
            ];

            $this->load->view("{$this->views}modal", $data);
        }
    }

    /**
     * Obtem os registros de produtos do catalogo que foram ocultados
     *
     * @param INT ID do comprador
     * @return  json
     */
    public function to_datatables_ocultados($id_cliente)
    {
        $r = $this->datatable->exec(
            $this->input->get(),
            'vw_produtos_clientes_sem_depara',
            [
                ['db' => 'id_cliente', 'dt' => 'id_cliente'],
                ['db' => 'codigo', 'dt' => 'codigo'],
                ['db' => 'UPPER(descricao)', 'dt' => 'descricao'],
                ['db' => 'quantidade_unidade', 'dt' => 'quantidade_unidade'],
                ['db' => 'ativo', 'dt' => 'ativo'],
            ],
            NULL,
            "id_cliente = {$id_cliente} AND ocultar = 1",
            null
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    /**
     * Obtem os registros de produtos sem depara
     *
     * @return  json
     */
    public function to_datatables($id_cliente)
    {
        $r = $this->datatable->exec(
            $this->input->get(),
            'vw_produtos_clientes_sem_depara',
            [
                ['db' => 'id_cliente', 'dt' => 'id_cliente'],
                ['db' => 'codigo', 'dt' => 'codigo'],
                ['db' => 'UPPER(descricao)', 'dt' => 'descricao'],
                ['db' => 'quantidade_unidade', 'dt' => 'quantidade_unidade'],
                ['db' => 'ativo', 'dt' => 'ativo'],
            ],
            NULL,
            "id_cliente = {$id_cliente} AND ocultar = 0",
            null
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function ocultarTodos()
    {
        $post = $this->input->post();

        if (isset($post['elementos'])) {
            $id_cliente = [];
            $codigo = [];

            foreach ($post['elementos'] as $item) {
                $id_cliente[] = $item['id_cliente'];
                $codigo[] = $item['codigo'];

                $updt = $this->bio
                    ->where('codigo', $item['codigo'])
                    ->where('id_cliente', $item['id_cliente'])
                    ->update('catalogo', ['ocultar' => '1']);

            }

            $id_cliente = implode(',', $id_cliente);
            $codigo = implode(',', $codigo);


            if ($updt) {
                $output = ['type' => 'success', 'message' => notify_update];
            } else {

                $output = ['type' => 'warning', 'message' => notify_failed];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }


    }

    /**
     * Obtem os registros de produtos com depara
     *
     * @return  json
     */
    public function to_datatables_consolidados($id_cliente)
    {
        $r = $this->datatable->exec(
            $this->input->get(),
            'vw_produtos_cliente_depara',
            [
                ['db' => 'id_sintese', 'dt' => 'id_sintese'],
                ['db' => 'id_produto', 'dt' => 'id_produto'],
                ['db' => 'codigo_hospital', 'dt' => 'codigo_hospital'],
                ['db' => 'codigo_fornecedor', 'dt' => 'codigo_fornecedor'],
                ['db' => 'produto_bionexo', 'dt' => 'produto_bionexo'],
                ['db' => 'produto_comprador', 'dt' => 'produto_comprador', 'formatter' => function ($d, $t) {
                    return $d . "<br> <small>Origem: {$t['produto_bionexo']}</small>";
                }],

            ],
            null,
            "id_cliente = {$id_cliente}"
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
                ['db' => 'UPPER(produto_descricao)', 'dt' => 'produto_descricao'],
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
        $where = NULL;

        $r = $this->datatable->exec(
            $this->input->get(),
            'produtos_marca_sintese',
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'id_sintese', 'dt' => 'id_sintese'],
                ['db' => 'id_produto', 'dt' => 'id_produto'],
                ['db' => 'UPPER(descricao)', 'dt' => 'descricao'],
                ['db' => 'marca', 'dt' => 'marca'],
            ],
            null,
            $where,
            "id_produto"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    /**
     * Cadastra o produto marca sintese
     *
     * @param - INT ID do catalogo
     * @return  json
     */
    public function saveProductSintese($id_catalogo)
    {
        if ($this->input->is_ajax_request()) {

            $post = $this->input->post();

            $pc = $this->bio->where('id', $id_catalogo)->get('catalogo')->row_array();


            $prod = $this->pms->find("id_produto", "id_produto like '9999%' AND id_produto != 9999 ", true, "id_produto DESC");

            $codigo = (isset($prod) && !empty($prod)) ? intval($prod['id_produto']) + 1 : 99991;

            $data = [
                'id_produto' => $codigo,
                'descricao' => $pc['descricao'],
                'id_sintese' => $codigo,
                'ativo' => 1
            ];

            $save = $this->pms->insert($data);

            if (isset($save)) {

                $output = ['type' => 'success', 'message' => notify_create . '. Utilize a busca novamente!'];
            } else {

                $output = ['type' => 'warning', 'message' => notify_failed];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * cria um arquivo em excel com os depara do comprador
     *
     * @param INt id do comprador
     * @return file excel
     */
    public function exportar($id_cliente)
    {

        $data = $this->datatable->exec(
            $this->input->get(),
            'vw_produtos_clientes_sem_depara',
            [
                ['db' => 'id_cliente', 'dt' => 'id_cliente'],
                ['db' => 'codigo', 'dt' => 'codigo'],
                ['db' => 'UPPER(descricao)', 'dt' => 'descricao'],
                ['db' => 'quantidade_unidade', 'dt' => 'quantidade_unidade'],
                ['db' => 'ativo', 'dt' => 'ativo'],
            ],
            NULL,
            "id_cliente = {$id_cliente}",
            null
        );

        if (!empty($data['data'])) {

            $info = [];

            foreach ($data['data'] as $row) {

                $info[] = [
                    'codigo' => $row['codigo'],
                    'descricao' => $row['descricao']
                ];
            }
        } else {

            $info[] = [
                'codigo' => '',
                'descricao' => ''
            ];
        }

        $dados_page = ['dados' => $info, 'titulo' => 'produtos'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ($exportar['status'] == false) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }

    public function init($idCliente, $upgrade = 0)
    {
        $pedente = $this->resp->find('*', "id_usuario = {$this->session->id_usuario} and fim is null and integrador = 2");
        $aberto = $this->resp->find('*', "id_cliente = {$idCliente} and fim is null and integrador = 2", true);


        if (empty($pedente) || $upgrade = 1)
        {
            if (empty($aberto)) {
                $data = [
                    'id_cliente' => $idCliente,
                    'id_usuario' => $this->session->id_usuario,
                    'integrador' => 2,
                    'inicio' => date('Y-m-d H:i:s', time())
                ];

                $this->resp->insert($data);

                $warning = ['type' => 'success', 'message' => 'De/para iniciado!'];

            } else {
                $warning = ['type' => 'warning', 'message' => 'Existe um de/para iniciado para esse hospital'];
            }
        }else{
            $warning = ['type' => 'warning', 'message' => 'Você precisa finalizar o hospital que está pendente, antes de iniciar outro'];
        }


        $this->output->set_content_type('application/json')->set_output(json_encode($warning));
    }

    public function close($idCliente)
    {
        $sem = count($this->db->select('*')->where('id_cliente', $idCliente)->get('vw_produtos_clientes_sem_depara')->result_array());
        $ocultos = count($this->bio->select('id')->where('id_cliente', $idCliente)->where('ocultar', 1)->get('catalogo')->result_array());
        $qtd_sem_depara = intval(($sem - $ocultos));

        $aberto = $this->resp->find('*', "id_cliente = {$idCliente} AND id_usuario = {$this->session->id_usuario} and fim is null and integrador = 2", true);

        if (!empty($aberto)) {

            if ($qtd_sem_depara == 0)
            {
                if ( $this->resp->update(['id' => $aberto['id'], 'fim' => date('Y-m-d H:i:s', time())]))
                {
                    $warning = ['type' => 'success', 'message' => 'De/para finalizado!'];
                }else{
                    $warning = ['type' => 'warning', 'message' => 'Não existe um de/para iniciado para esse hospital'];
                }
            }else{
                $warning = ['type' => 'warning', 'message' => 'Você precisa fazer o de/para de todos os produtos'];
            }


        } else {
            $warning = ['type' => 'warning', 'message' => 'Não existe um de/para iniciado para esse hospital'];
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($warning));

    }
}

/* End of file Controllername.php */
