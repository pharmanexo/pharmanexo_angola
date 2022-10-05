<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Combinados extends MY_Controller
{

    private $views;
    private $route;

    public function __construct()
    {
        parent::__construct();

        $this->views = "fornecedor/produtos/combinados/";
        $this->route = base_url("fornecedor/estoque/combinados/");

        $this->load->model("M_produtos_fornecedores", "produtos_fornecedores");
        $this->load->model("Produto_fornecedor_validade", "pfv");
        $this->load->model("produto_marca_sintese");
        $this->load->model("m_produto_fornecedor_sintese", "pfs");
    }

    public function make($codigo)
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            $oncoprod = explode(',', ONCOPROD);

            if ( in_array($this->session->id_fornecedor, $oncoprod) ) {

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

                        if (empty($old)) {
                            $data = [
                                "id_sintese" => $id_sintese,
                                "cd_produto" => $codigo,
                                "id_usuario" => $this->session->id_usuario,
                                "id_fornecedor" => $fornecedor
                            ];

                            $this->db->insert('produtos_fornecedores_sintese', $data);
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
                        $warning = [
                            "type" => "success",
                            "message" => "Combinação de produtos realizada.",
                        ];

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

                foreach ($post['produtos'] as $id_sintese) {
                    $old = $this->pfs->find("*", "id_sintese = {$id_sintese} and cd_produto = {$produto['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
                    if (empty($old)) {
                        $data = [
                            "id_sintese" => $id_sintese,
                            "cd_produto" => $codigo,
                            "id_usuario" => $this->session->id_usuario,
                            "id_fornecedor" => $this->session->id_fornecedor
                        ];

                        $this->pfs->insert($data);
                    }
                    $log[] = ['codigo' => $codigo, 'id_fornecedor' => $this->session->id_fornecedor];
                }

                if ($this->db->trans_status() === false) {
                    $warning = [
                        "type" => "warning",
                        "message" => "Erro ao combinar produtos"
                    ];

                    $this->db->trans_rollback();
                } else {
                    
                    $warning = ["type" => "success", "message" => "Combinação de produtos realizada.", ];

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

            $this->db->trans_begin();

            if ( in_array($this->session->id_fornecedor, $oncoprod) ) {

                foreach ($oncoprod as $fornecedor) {

                    $produtos = $this->pfv->get_itens("*", "codigo = {$codigo} and id_sintese = {$id_sintese} and id_fornecedor = {fornecedor}");

                    foreach ($produtos as $produto) {

                        $data = [
                            "id" => $produto['id'],
                            "id_sintese" => 0,
                            "id_produto" => 0
                        ];

                        $this->pfv->update($data);

                        $this->db->query("DELETE FROM produtos_fornecedores_sintese WHERE id_pfv = {$produto['id']} AND id_sintese = {$produto['id_sintese']}");
                    }

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
            } else {

                $produtos = $this->pfv->get_itens("*", "codigo = {$codigo} and id_sintese = {$id_sintese} and id_fornecedor = {$this->session->id_fornecedor}");

                foreach ($produtos as $produto) {

                    $data = [
                        "id" => $produto['id'],
                        "id_sintese" => 0,
                        "id_produto" => 0
                    ];

                    $this->pfv->update($data);

                    $this->db->query("DELETE FROM produtos_fornecedores_sintese WHERE id_pfv = {$produto['id']} AND id_sintese = {$produto['id_sintese']}");
                }

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
                    'class' => 'btn-primary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ]
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
        $page_title = "Produtos com De/Para realizado";

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

            ]
        ]);

        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/jquery.form.min.js',
                THIRD_PARTY . 'plugins/jquery-validation-1.19.1/dist/jquery.validate.min.js'
            ]
        ]);

        $this->load->view("{$this->views}/main_consolidados_agrupados", $data);
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

            $produto = $this->produtos_fornecedores->get_row($id_prod);
            $encode = json_encode($produto);


            $data_insert = [
                "codigo" => $produto['codigo'],
                "marca" => $produto['marca'],
                "id_fornecedor" => $produto['id_fornecedor'],
            ];

            $this->db->insert("produtos_aguardando_sintese", $data_insert);

            $config = Array(
                'protocol' => 'smtp',
                'smtp_host' => 'smtp.office365.com',
                'smtp_port' => 587,
                'smtp_user' => 'suporte@pharmanexo.com.br',
                'smtp_pass' => 'Pharma_TI_2019',
                'mailtype' => 'html',
                'charset' => 'utf-8',
                'smtp_crypto' => 'tls',
                'wordwrap' => true,
            );

            $to = "marlon.mbes@gmail.com";

            $this->load->library('email', $config);
            $this->email->set_newline("\r\n");
            $this->email->set_crlf("\r\n");

            $this->email->initialize($config);
            $this->email->clear();
            $this->email->from("suporte@pharmanexo.com.br", 'Marlon Boecker');
            $this->email->to($to);

            $template = file_get_contents(base_url('/public/html/template_mail/notifications.html'));
            $subject = "PRODUTO NÃO ENCONTRADO NO DE -> PARA | ID: {$id_prod}";
            $body = "
            <p>Prezado, </p>
            <p>O cliente {$this->session->razao_social} não encontrou o item no De -> Para e enviou a seguinte mensagem. ID: {$id_prod}</p>
            <p>Produto: {$produto['produto_descricao']}</p>
            <p class='small'>{$encode}</p>
            <p>{$this->input->post('mensagem')}</p>
            ";

            $body = str_replace(['%to%', '%subject%', '%body_message%'], [$to, $subject, $body], $template);

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

    public function to_datatables()
    {
        $r = $this->datatable->exec(
            $this->input->get(),
            'vw_produtos_fornecedores',
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'codigo', 'dt' => 'codigo'],
                ['db' => 'produto_descricao', 'dt' => 'produto_descricao'],
                ['db' => 'marca', 'dt' => 'marca'],
            ],
            null,
            'bloqueado = 0 AND id_fornecedor = ' . $this->session->userdata('id_fornecedor') . " AND (id_sintese is null OR id_sintese = 0) AND (id_produto is null OR id_produto = 0) "
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
                ['db' => 'produto_descricao', 'dt' => 'produto_descricao', "formatter" => function ($d, $r) {
                    $item = $this->db->select("descricao, marca")->where('id_sintese', $r['id_sintese'])->get('produtos_marca_sintese')->row_array();
                    return "{$d} <hr> <strong>Origem: </strong> {$item['descricao']} <br> Marca Origem: {$item['marca']}";
                }],
                ['db' => 'marca', 'dt' => 'marca'],
                ['db' => 'data_atualizacao', 'dt' => 'data_atualizacao'],
            ],
            null,
            'id_fornecedor = ' . $this->session->userdata('id_fornecedor') . " AND id_sintese <> 0 AND id_produto <> 0 AND id_estado = {$this->session->id_estado}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }


    public function to_datatables_consolidados_agrupados()
    {
        $r = $this->datatable->exec(
            $this->input->get(),
            'vw_produtos_fornecedores_sintese',
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'id_produto', 'dt' => 'id_produto'],
                ['db' => 'id_sintese', 'dt' => 'id_sintese'],
                ['db' => 'codigo', 'dt' => 'codigo'],
                ['db' => 'produto_descricao', 'dt' => 'produto_descricao'],
                ['db' => 'apresentacao', 'dt' => 'apresentacao'],
                ['db' => 'descricao', 'dt' => 'descricao'],
                ['db' => 'nome_comercial', 'dt' => 'nome_comercial', 'formatter' => function($d, $r){
                return $d . " - ". $r['apresentacao'] . ' ' . $r['descricao'];
                }],
                ['db' => 'marca', 'dt' => 'marca'],
                ['db' => 'data_atualizacao', 'dt' => 'data_atualizacao'],
            ],
            null,
            'id_fornecedor = ' . $this->session->userdata('id_fornecedor') . " AND id_sintese <> 0 AND id_produto <> 0",
            "codigo"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }


    public function to_datatables_sintese($id_pf)
    {
        $get = $this->input->get();
        $dados = $this->db->select("*")->where("codigo = {$id_pf} and id_fornecedor = {$this->session->id_fornecedor}")->get('produtos_catalogo')->row_array();

        $where = NULL;

        if (empty($get['columns'][2]['search']['value']) AND empty($get['columns'][3]['search']['value'])) {
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
