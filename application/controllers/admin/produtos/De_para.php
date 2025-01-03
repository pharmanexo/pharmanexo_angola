<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class De_para extends Admin_controller
{

    private $views;
    private $route;

    public function __construct()
    {
        parent::__construct();

        $this->views = "admin/produtos/de_para";
        $this->route = base_url("admin/produtos/de_para");

        $this->load->model("m_fornecedor", "fornecedor");
    }

    /**
     * Exibe a tela de produtos sem DE -> PARA
     *
     * @return view
     */
    public function index()
    {
        $page_title = "Produtos sem DE -> PARA";

        $data['to_datatable'] = "{$this->route}/to_datatable_semdepara/";

        $data['url_update'] = "{$this->route}/index_match/";
        $data['url_exportar'] = "{$this->route}/exportar_semdepara/";
        $data['fornecedores'] = $this->fornecedor->find("id, nome_fantasia", null, false, 'nome_fantasia ASC');

        $data['header'] = $this->template->header(['title' => $page_title, ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar_semdepara",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel', 
                    'label' => 'Exportar Excel'
                ],
                [
                    'type' => 'a',
                    'id' => 'btnConsolidados',
                    'url' => "{$this->route}/index_consolidados",
                    'class' => 'btn-primary',
                    'icone' => 'fa-arrow-right',
                    'label' => 'Produtos com DE->PARA'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts(['scripts' => [] ]);

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Exibe a tela de produtos com DE -> PARA
     *
     * @return view
     */
    public function index_consolidados()
    {
        $page_title = "Produtos com DE -> PARA";

        $data['to_datatable'] = "{$this->route}/to_datatable_comdepara/";

        $data['url_update'] = "{$this->route}/unlink";
        $data['url_exportar'] = "{$this->route}/exportar_comdepara/";
        $data['fornecedores'] = $this->fornecedor->find("id, nome_fantasia", null, false, 'nome_fantasia ASC');

        $data['header'] = $this->template->header(['title' => $page_title, ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar_comdepara",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel', 
                    'label' => 'Exportar Excel'
                ],
                [
                    'type' => 'a',
                    'id' => 'btnSemDepara',
                    'url' => "{$this->route}",
                    'class' => 'btn-primary',
                    'icone' => 'fa-arrow-right',
                    'label' => 'Produtos sem DE->PARA'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts(['scripts' => [] ]);

        $this->load->view("{$this->views}/main_consolidados", $data);
    }

    /**
     * Exibe a tela de combinar produto
     *
     * @param - Int codigo do produto
     * @param - Int ID do fornecedor
     * @return view
     */
    public function index_match($codigo, $id_fornecedor)
    {
        $this->db->select("CASE WHEN descricao is null THEN CONCAT(nome_comercial, ' - ', apresentacao) ELSE CONCAT(nome_comercial, ' - ', descricao) END  AS descricao");
        $this->db->select("marca");
        $this->db->where('codigo', $codigo);
        $this->db->where('id_fornecedor', $id_fornecedor);
        $produto = $this->db->get('produtos_catalogo')->row_array();

        $page_title = "{$produto['descricao']} <br><small><b>Marca: </b> {$produto['marca']}</small>";

        $data['to_datatable'] = "{$this->route}/to_datatable_match/{$codigo}/{$id_fornecedor}";

        $data['url_update'] = "{$this->route}/make/{$codigo}/{$id_fornecedor}";
        $data['url_notFound'] = "{$this->route}/not_found/{$codigo}/{$id_fornecedor}";


        $data['header'] = $this->template->header(['title' => $page_title ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                 [
                    'type' => 'a',
                    'id' => 'btnVoltar',
                    'url' => $this->route,
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts(['scripts' => [] ]);

        $this->load->view("{$this->views}/main_match", $data);
    }

    /**
     * Remove o DE -> PARA do produto
     *
     * @param - POST request
     * @return json
     */
    public function unlink()
    {
        if ( $this->input->is_ajax_request() ) {

            $post = $this->input->post();

            $oncoprod = explode(',', ONCOPROD);

            if ( in_array($post['id_fornecedor'], $oncoprod) ) {

                $this->db->trans_begin();

                foreach ($oncoprod as $fornecedor) {


                    $this->db->where('cd_produto', $post['codigo']);
                    $this->db->where('id_sintese', $post['id_sintese']);
                    $this->db->where('id_fornecedor', $fornecedor);
                    $this->db->delete('produtos_fornecedores_sintese');

                    if ($this->db->trans_status() == false) {

                        $this->db->trans_rollback();

                        $output = ["type" => "warning", "message" => $this->db->error()];
                    } else {

                        $this->db->trans_commit();

                        $output = ["type" => "success", "message" => "Produto desvinculado e retornado para a lista de sem DE -> PARA"];
                    }
                }
            } else {

                $this->db->trans_begin();

                $this->db->where('cd_produto', $post['codigo']);
                $this->db->where('id_sintese', $post['id_sintese']);
                $this->db->where('id_fornecedor', $post['id_fornecedor']);
                $this->db->delete('produtos_fornecedores_sintese');

                if ($this->db->trans_status() == false) {

                    $this->db->trans_rollback();
                    $output = ["type" => "warning", "message" => "Não foi possível"];
                } else {

                    $this->db->trans_commit();
                    $output = ["type" => "success", "message" => "Produto desvinculado e retornado para a lista sem de DE -> PARA"];
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Cria o DE -> PARA do produto
     *
     * @param - INT codigo do produto
     * @param - INT ID do fornecedor
     * @return json
     */
    public function make($codigo, $id_fornecedor)
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            $oncoprod = explode(',', ONCOPROD);

            if ( in_array($id_fornecedor, $oncoprod) ) {

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

                        $warning = ["type" => "success", "message" => "Combinação de produtos realizada." ];

                        $this->db->trans_commit();
                    }
                }
            } else {

                $produto = $this->db
                ->where('codigo', $codigo)
                ->where('id_fornecedor', $id_fornecedor)
                ->get('produtos_catalogo')->row_array();


                $this->db->trans_begin();

                $log = [];

                foreach ($post['produtos'] as $id_sintese) {
                    $old = $this->pfs->find("*", "id_sintese = {$id_sintese} and cd_produto = {$produto['id']} and id_fornecedor = {$id_fornecedor}", true);
                    if (empty($old)) {
                        $data = [
                            "id_sintese" => $id_sintese,
                            "cd_produto" => $codigo,
                            "id_usuario" => $this->session->id_usuario,
                            "id_fornecedor" => $id_fornecedor
                        ];

                        $this->pfs->insert($data);
                    }
                    $log[] = ['codigo' => $codigo, 'id_fornecedor' => $id_fornecedor];
                }

                if ($this->db->trans_status() === false) {

                    $warning = ["type" => "warning", "message" => "Erro ao combinar produtos"];

                    $this->db->trans_rollback();
                } else {
                    $warning = ["type" => "success", "message" => "Combinação de produtos realizada.", ];

                    $this->db->trans_commit();
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
    }

    /**
     * Envia email  ao suporte pharmanexo
     *
     * @param - int - Codigo do produto
     * @param - int - ID do fornecedor
     * @return json
     */
    public function not_found($codigo, $id_fornecedor)
    {
        if ($this->input->method() == 'post') {

            $this->db->where('codigo', $codigo);
            $this->db->where('id_fornecedor', $id_fornecedor);
            $produto = $this->db->get('produtos_catalogo')->row_array();

            # obtem o fornecedor
            $fornecedor = $this->fornecedor->findById($id_fornecedor);

            $encode = json_encode($produto);

            $data_insert = [
                "codigo" => $produto['codigo'],
                "marca" => $produto['marca'],
                "id_fornecedor" => $produto['id_fornecedor'],
                'id_usuario' => $this->session->id_usuario
            ];

            $this->db->insert("produtos_aguardando_sintese", $data_insert);

            // Atualiza o produto no catalogo
            $this->db->where('codigo', $codigo);
            $this->db->where('id_fornecedor', $id_fornecedor);
            $this->db->update('produtos_catalogo', ['ocultar_de_para' => 1]);

            $config = Array(
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
            $subject = "PRODUTO NÃO ENCONTRADO NO DE -> PARA | ID: {$codigo}";
            $body = "
            <p>Prezado, </p>
            <p>O fornecedor ({$id_fornecedor}) {$fornecedor['razao_social']} não encontrou o item no De -> Para e enviou a seguinte mensagem. ID: {$codigo}</p>
            <p>CNPJ: {$fornecedor['cnpj']}</p>
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
                "url_send" => "{$this->route}/not_found/{$codigo}/{$id_fornecedor}"
            ];

            $this->load->view("{$this->views}/modal", $data);
        }
    }

    public function to_datatable_match($codigo, $id_fornecedor)
    {
        $get = $this->input->get();


        $dados = $this->db->select("*")->where("codigo = {$codigo} and id_fornecedor = {$id_fornecedor}")->get('produtos_catalogo')->row_array();

        $where = NULL;

        # Se o usuario não preencheu os campos de busca descricao e marca
        if (empty($get['columns'][2]['search']['value']) AND empty($get['columns'][3]['search']['value'])) {

            # Filtra a descrição se existir
            if (isset($dados['descricao']) && !empty($dados['descricao'])) {

                $where .= "descricao like '%{$dados['descricao']}%' AND ";
                $where .= "complemento like '%{$dados['descricao']}%' AND ";
            } else {

                # Se não filtra pela descrição do nome_comercial
                if (isset($dados['nome_comercial']) && !empty($dados['nome_comercial'])) {

                    $exp = explode(' ', $dados['nome_comercial']);
                    $where .= "descricao like '%{$exp[0]}%' AND ";
                    $where .= "complemento like '%{$exp[0]}%' AND ";
                }
            }

            # Verifica se existe marca e se o ID da marca não é zero(0)
            if ( isset($dados['marca']) && !empty($dados['marca']) && $dados['id_marca'] != "0" ) {

                $exp = explode(' ', $dados['marca']);
                $marca = strtolower($exp[0]);
                $where .= "(LOWER(marca) like '%{$marca}%' OR id_marca = {$dados['id_marca']}) AND ";
            }

            $where = rtrim($where, 'AND ');
        }


        $r = $this->datatable->exec(
            $this->input->post(),
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

    public function to_datatable_semdepara($id_fornecedor)
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'vw_catalogo_sem_sintese',
            [
                ['db' => 'vw_catalogo_sem_sintese.id_fornecedor', 'dt' => 'id_fornecedor'],
                ['db' => 'vw_catalogo_sem_sintese.codigo', 'dt' => 'codigo'],
                [
                    'db' => 'vw_catalogo_sem_sintese.nome_comercial',
                    'dt' => 'nome_comercial'
                ],
                [
                    'db' => 'vw_catalogo_sem_sintese.apresentacao',
                    'dt' => 'produto_descricao', "formatter" => function ($d, $r) {
                    return $r['nome_comercial'] . " - " . $d;
                }],
                ['db' => 'vw_catalogo_sem_sintese.marca', 'dt' => 'marca']
            ], 
            null, 
            "id_fornecedor = {$id_fornecedor}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function to_datatable_comdepara($id_fornecedor)
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'vw_produtos_fornecedores_sintese',
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'id_produto', 'dt' => 'id_produto'],
                ['db' => 'id_sintese', 'dt' => 'id_sintese'],
                ['db' => 'codigo', 'dt' => 'codigo'],
                ['db' => 'nome_comercial', 'dt' => 'nome_comercial'],
                [
                    'db' => 'apresentacao',
                    'dt' => 'produto_descricao', "formatter" => function ($d, $r) {

                    $item = $this->db->select("descricao, marca")->where('id_sintese', $r['id_sintese'])->get('produtos_marca_sintese')->row_array();
                    return "<small>{$r['nome_comercial']} - {$d} <hr> <strong>Origem: </strong> {$item['descricao']}</small>";
                }],
                ['db' => 'marca', 'dt' => 'marca'],
                ['db' => 'data_atualizacao', 'dt' => 'data_atualizacao'],
            ],
            null,
            "id_fornecedor = {$id_fornecedor} AND id_sintese <> 0 AND id_produto <> 0",
            "codigo, id_sintese"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function exportar_semdepara($id_fornecedor = null)
    {
        
        if ( isset($id_fornecedor) ) {
            
            $this->db->select("codigo, CONCAT(nome_comercial, ' - ', apresentacao) AS descricao");
            $this->db->from("vw_catalogo_sem_sintese");
            $this->db->where("id_fornecedor", $id_fornecedor);
            $this->db->order_by("nome_comercial ASC");

            $query = $this->db->get()->result_array();
        } else {

            $query = [];
        }

        if ( count($query) < 1 ) {
            $query[] = [
                'codigo' => '',
                'descricao' => ''
            ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'Produtos'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }

    public function exportar_comdepara($id_fornecedor = null)
    {
        
        if ( isset($id_fornecedor) ) {
            
            $this->db->select("codigo");
            $this->db->select("id_produto");
            $this->db->select("id_sintese");
            $this->db->select("CONCAT(nome_comercial, ' - ', apresentacao) AS descricao");
            $this->db->from("vw_produtos_fornecedores_sintese");
            $this->db->where("id_fornecedor", $id_fornecedor);
            $this->db->where("id_sintese <> 0");
            $this->db->where("id_produto <> 0");
            $this->db->group_by("codigo, id_sintese");
            $this->db->order_by("descricao ASC");

            $query = $this->db->get()->result_array();
        } else {

            $query = [];
        }

        if ( count($query) < 1 ) {
            $query[] = [
                'codigo' => '',
                'id_produto' => '',
                'id_sintese' => '',
                'descricao' => ''
            ];
        } else {

            foreach ($query as $kk => $row) {
                    
                $this->db->select("descricao, marca");
                $this->db->where('id_sintese', $row['id_sintese']);
                $item = $this->db->get('produtos_marca_sintese')->row_array();

                if ( isset($item) && !empty($item) ) {
                 
                    $query[$kk]['descricao'] .=  ".  Origem: {$item['descricao']}";
                }
            }
        }

        $dados_page = ['dados' => $query, 'titulo' => 'Produtos'];

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