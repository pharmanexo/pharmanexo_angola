<?php

class Pedidos extends Rep_controller
{

    private $route;
    private $urlselect2;
    private $views;

    public function estoque()
    {
        $produtos = $this->db->select('codigo')->where('id_fornecedor', '5037')->get('produtos_catalogo')->result_array();
        $data = [];
        foreach ($produtos as $produto){
            $data[] = [
                'id_fornecedor' => 5037,
                'codigo' => $produto['codigo'],
                'lote' => 'XYZS',
                'estoque' => rand(100, 9999),
                'validade' => '2023-02-23'
            ];


        }

        $this->db->insert_batch('produtos_lote', $data);
    }


    public function __construct()
    {
        parent::__construct();

        $this->route = base_url("pharma/pedidos/");
        $this->urlselect2 = base_url("pharma/select2");
        $this->views = "pharma/pedidos/";

        $this->load->model('M_compradores', 'compradores');
        $this->load->model('M_estados', 'estado');
        $this->load->model('m_forma_pagamento', 'forma_pagamento');
        $this->load->model('m_forma_pagamento_fornecedor', 'forma_pagamento_fornecedor');
        $this->load->model('m_prazo_entrega', 'prazo_entrega');
        $this->load->model('m_valor_minimo', 'valor_minimo');

        $this->load->model('m_pedido_rep', 'pedido');
        $this->load->model('m_representante', 'rep');
        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_produto', 'produto');
    }

    /**
     * Redireciona para a função form
     *
     * @return redirect
     */
    public function index()
    {

        $this->form();
    }

    /**
     * Registra o pedido de compra
     *
     * @return json
     */
    public function saveOC()
    {
        $post = $this->input->post();

        $post['id_representante'] = 4;

        $get_old = $this->pedido->find('*', "id_fornecedor = {$post['id_fornecedor']} and id_representante = {$post['id_representante']} and id_comprador = {$post['id_comprador']} and situacao = 1", true);

        if (empty($get_old)) {

            if ($this->pedido->insert($post)) {

                $id = $this->db->insert_id();

                $warning = ['type' => 'success', 'id_pedido' => $id];
            } else {

                $warning = ['type' => 'warning', 'message' => 'Erro ao registrar pedido!'];
            }
        } else {
            $warning = ['type' => 'warning', 'message' => 'Já existe um pedido em aberto para esse comprador', 'url' => "{$this->route}open/{$get_old['id']}"];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($warning));
    }

    /**
     * Redireciona para a função form
     *
     * @param - int id_pedido
     * @return redirect
     */
    public function open($id_pedido)
    {

        $this->form($id_pedido);
    }

    /**
     * Salva novo produto ou atualiza de um pedido do representante
     *
     * @param request post form
     * @return json
     */
    public function save()
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            $post['preco_unidade'] = dbNumberFormat($post['preco_unidade']);

            $total = dbNumberFormat($post['quantidade_solicitada']) * $post['preco_unidade'];

            $post['total'] = $total;

            if (isset($post['desconto'])) $post['desconto'] = dbNumberFormat($post['desconto']);
            $post['preco_desconto'] = (isset($post['preco_desconto'])) ? dbNumberFormat($post['preco_desconto']) : $post['preco_unidade'];

            $this->db->where('id_pedido', $post['id_pedido']);
            $this->db->where('cd_produto_fornecedor', $post['cd_produto_fornecedor']);
            $produto_existente = $this->db->get('pedidos_representantes_produtos');

            if ($produto_existente->num_rows() > 0) {

                # Atualiza produto existente

                $this->db->where('id_pedido', $post['id_pedido']);
                $this->db->where('cd_produto_fornecedor', $post['cd_produto_fornecedor']);

                $operacao = $this->db->update('pedidos_representantes_produtos', $post);
                $success = "atualizado";
                $error = "atualizar";
            } else {

                # Novo produto

                $operacao = $this->db->insert('pedidos_representantes_produtos', $post);
                $success = "cadastrado";
                $error = "cadastrar";
            }

            if ($operacao) {

                $warning = ['type' => 'success', 'message' => "Produto {$success}", 'id_pedido' => $post['id_pedido']];

            } else {
                $warning = ['type' => 'warning', 'message' => "Erro ao {$error} produto"];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
    }

    public function get_produtos($id_pedido)
    {
        $this->db->where('id_pedido', $id_pedido);
        $result = $this->db->get('pedidos_representantes_produtos')->result_array();

        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    private function form($id = null)
    {
        $view = 'form';
        $page_title = 'Novo Orçamento';

        $data['select_formas_pagamento'] = "{$this->urlselect2}/to_select2_formas_pagamento";
        $data['select_produtos'] = "{$this->urlselect2}/get_produtos/";
        $data['select_clientes'] = "{$this->urlselect2}/to_select2_compradores";
        $data['id_fornecedor'] = $this->session->id_fornecedor;
        $data['form_action'] = "{$this->route}saveOC";
        $data['form_action_produtos'] = "{$this->route}save";
        $data['url_datatable'] = "{$this->route}datatables_produtos/";
        $data['url_finalize'] = "{$this->route}finalizeRequest";
        $data['url_cancel'] = "{$this->route}cancelRequest";
        $data['url_preco'] = "{$this->route}getPrice";
        $data['url_historico'] = "{$this->route}get_historico";
        $data['id_fornecedor'] = $this->session->id_fornecedor;


        if (isset($id)) {
            $data['dados'] = $this->pedido->findById($id);
        }

        $buttons = [
            [
                'type' => 'button',
                'id' => 'btnCancel',
                'url' => "{$this->route}cancelRequest",
                'class' => 'btn-danger',
                'icone' => 'fa-ban',
                'label' => 'Cancelar Pedido'
            ],
            [
                'type' => 'button',
                'id' => 'btnInsert',
                'url' => "{$this->route}finalizeRequest",
                'class' => 'btn-primary',
                'icone' => 'fa-check',
                'label' => 'Finalizar Pedido'
            ]
        ];

        if (isset($data['dados']['situacao']) && $data['dados']['situacao'] != '1') {
            $buttons = [];
        }

        $dados = $this->get_info($_SESSION['id_cliente'], false);
        if (!empty($dados)) {
            $dados['forma_pagamento'] = $this->forma_pagamento->findbyid($dados['condicao']);
        }

        $data['cliente'] = $dados;


        if (isset($data['dados']['situacao']) && $data['dados']['situacao'] == 7) {
            $view = 'aprovacao';
            $page_title = 'Aprovação de Orgamento';
            $buttons = [
                [
                    'type' => 'button',
                    'id' => 'btnInsert',
                    'url' => "{$this->route}finalizeRequest",
                    'class' => 'btn-primary',
                    'icone' => 'fa-check',
                    'label' => 'Aprovar Orçamento'
                ]
            ];
        }


        $data['header'] = $this->tmp_rep->header([
            'title' => $page_title,
            'buttons' => $buttons
        ]);
        $data['navbar'] = $this->tmp_rep->navbar();
        $data['sidebar'] = $this->tmp_rep->sidebar();
        $data['heading'] = $this->tmp_rep->heading();
        $data['scripts'] = $this->tmp_rep->scripts();


        $this->load->view("{$this->views}{$view}", $data);
    }

    public function get_info($id_cliente, $json = true)
    {


        $cliente = $this->compradores->findById($id_cliente);
        $estado = $this->estado->find("*", "uf = '{$cliente['estado']}'", true);

        #valor minimo
        $valor_minimo = $this->valor_minimo->find("*", "id_cliente = {$cliente['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
        if (empty($valor_minimo)) {
            $valor_minimo = $this->valor_minimo->find("*", "id_estado = {$estado['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
        }

        $valor_minimo = $valor_minimo['valor_minimo'];

        #prazo entrega
        $prazo_entrega = $this->prazo_entrega->find("*", "id_cliente = {$cliente['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
        if (empty($prazo_entrega)) {
            $prazo_entrega = $this->prazo_entrega->find("*", "id_estado = {$estado['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
        }

        #condição pagamento
        $forma_pagamento = $this->forma_pagamento_fornecedor->find("*", "id_cliente = {$cliente['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
        if (empty($forma_pagamento)) {
            $forma_pagamento = $this->forma_pagamento_fornecedor->find("*", "id_estado = {$estado['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
        }

        $formapgto_desc = $this->forma_pagamento->findById($forma_pagamento['id_forma_pagamento']);

        $forma_pagamento = $forma_pagamento['id_forma_pagamento'];

        $output = [
            "cliente" => $cliente,
            "valor_minimo" => (isset($valor_minimo) and $valor_minimo > 0) ? number_format($valor_minimo, 2, ',', '.') : '0,00',
            "prazo_entrega" => (isset($prazo_entrega['prazo']) and $prazo_entrega['prazo'] > 0) ? $prazo_entrega['prazo'] : 'A Vista',
            "id_prazo_entrega" => (isset($prazo_entrega['id']) and $prazo_entrega['id'] > 0) ? $prazo_entrega['id'] : '1',
            "condicao" => (isset($forma_pagamento) and $forma_pagamento > 0) ? $forma_pagamento : '1',
            "condicao_desc" => (isset($formapgto_desc) and !empty($formapgto_desc)) ? $formapgto_desc['descricao'] : '',
        ];

        if ($json) {
            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        } else {
            return $output;
        }


    }

    /**
     * obtem histórico de ofertas de um produto
     *
     * @return  json
     */
    public function get_historico()
    {
        if ($this->input->is_ajax_request()) {

            $post = $this->input->post();

            $format = "%d/%m/%Y %H:%i";
            $this->db->select("id, preco_desconto, DATE_FORMAT(data_criacao, '{$format}') as data");
            $this->db->where('id_fornecedor', $this->session->id_fornecedor);
            $this->db->where('cd_produto_fornecedor', $post['codigo']);
            $this->db->where("id_pedido != {$post['id_pedido']}");
            $this->db->from('pedidos_representantes_produtos as prp');
            $this->db->join("pedidos_representantes as pr", "pr.id = prp.id_pedido");
            $ofertas = $this->db->get();

            if ($ofertas->num_rows() > 0) {
                $data = $ofertas->result_array();
            } else {
                $data = 0;
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    /**
     * Exibe os produtos de um novo pedido
     *
     * @param int id_pedido
     * @return json
     */
    public function datatables_produtos($id_pedido)
    {

        $data = $this->datatable->exec(
            $this->input->post(),
            'pedidos_representantes_produtos prod',
            [
                ['db' => 'prod.id_pedido', 'dt' => 'id_pedido'],
                ['db' => 'prod.status', 'dt' => 'status'],
                ['db' => 'prod.cd_produto_fornecedor', 'dt' => 'cd_produto_fornecedor'],
                ['db' => 'prod.quantidade_solicitada', 'dt' => 'quantidade_solicitada'],
                ['db' => 'prod.desconto', 'dt' => 'desconto'],
                ['db' => 'pc.nome_comercial', 'dt' => 'nome_comercial'],
                ['db' => 'pc.ean', 'dt' => 'ean'],
                ['db' => 'prod.preco_unidade', 'dt' => 'preco_unidade', 'formatter' => function ($value, $row) {

                    return number_format($value, 4, ',', '.');
                }],
                ['db' => 'prod.preco_desconto', 'dt' => 'preco_desconto', 'formatter' => function ($value, $row) {

                    return number_format($value, 4, ',', '.');
                }],
                ['db' => '(prod.preco_desconto * prod.quantidade_solicitada)', 'dt' => 'total', 'formatter' => function ($value, $row) {

                    return number_format($value, 4, ',', '.');
                }],
            ],
            [
                ['pedidos_representantes pedido', 'pedido.id = prod.id_pedido'],
                ['produtos_catalogo pc', 'pc.codigo = prod.cd_produto_fornecedor AND pc.id_fornecedor = pedido.id_fornecedor']
            ],
            "prod.id_pedido = {$id_pedido}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Muda o status do pedido para enviado para faturamento
     *
     * @param post - int id_pedido
     * @return json
     */
    public function finalizeRequest()
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            $id_pedido = $post['id_pedido'];

            $pedido = $this->pedido->findById($id_pedido);
            $situacao = 1;
            if ($pedido['situacao'] == 1){
                $situacao = 2;
            }else{
                $situacao = 3;
            }

            $soma = $this->db->query("SELECT sum(total) as total from pedidos_representantes_produtos where id_pedido = {$id_pedido}")->row_array()['total'];

            // verificar valor minimo
            if ($soma < $pedido['valor_minimo']) {

                $output = ['type' => 'warning', 'message' => 'Valor minimo não atendido!'];
            } else {

                $this->db->where('id', $id_pedido);
                $update = $this->db->update('pedidos_representantes', ['situacao' => $situacao]);

                if ($update) {

                    $representante = $this->rep->findById($pedido['id_representante']);
                    $fornecedor = $this->fornecedor->findById($pedido['id_fornecedor']);

                    if (!empty($fornecedor['emails_config'])) {

                        $emails = json_decode($fornecedor['emails_config']);

                        if (!empty($emails->representantes)) {
                            $destinatarios = "{$emails->representantes}";

                            # notificar por e-mail
                            $notificar = [
                                "to" => $destinatarios,
                                "greeting" => "{$fornecedor['razao_social']}",
                                "subject" => "Pedido enviado - {$id_pedido}",
                                "message" => "O pedido #{$id_pedido} foi enviado para faturamento."
                            ];

                            $this->notify->send($notificar);
                        }
                    }

                    #notificar por notifações pharmanexo
                    $alert = [
                        "id_usuario" => NULL,
                        "id_fornecedor" => $pedido['id_fornecedor'],
                        "message" => "Pedido #{$id_pedido} do representante {$representante['nome']} foi enviado para faturamento. clique para ver mais",
                        "url" => base_url("fornecedor/pharma/pedidos/detalhes/{$id_pedido}")
                    ];

                    $this->notify->alert($alert);

                    $output = [
                        'type' => 'success',
                        'message' => 'Pedido finalizado com sucesso.',
                        'url' => base_url('pharma/pedidos_realizados')
                    ];
                } else {

                    $output = ['type' => 'warning', 'message' => 'Erro ao finalizar pedido.'];
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Cancela pedido
     *
     *
     * @param post - int id_pedido
     * @return json
     */
    public function cancelRequest()
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            $this->db->where('id', $post['id_pedido']);
            $update = $this->db->update('pedidos_representantes', ['situacao' => 5]);

            if ($update) {

                $output = [
                    'type' => 'success',
                    'message' => 'Pedido Cancelado com sucesso.',
                    'url' => base_url('pharma/pedidos_representantes')
                ];
            } else {

                $output = ['type' => 'warning', 'message' => 'Erro ao cancelar pedido.'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function getPrice()
    {

        $post = $this->input->post();

        $data['preco_unitario'] = $this->price->getPrice([
            'id_fornecedor' => $this->session->id_fornecedor,
            'codigo' => $post['codigo'],
            'id_estado' => $this->session->id_estado
        ]);

        if (!empty($data['preco_unitario'])) {
            $data['preco_unitario'] = number_format($data['preco_unitario'], 4, ',', '.');
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function exportar($id_pedido)
    {
        $this->db->select("
            cd_produto_fornecedor AS codigo_produto,
            CASE
                WHEN descricao is null THEN nome_comercial 
                ELSE CONCAT(nome_comercial, ' - ', descricao) END AS produto,
            FORMAT(preco_unidade, 4, 'de_DE') AS preco_unid,
            quantidade_solicitada AS quantidade,
            FORMAT(desconto, 4, 'de_DE') AS desconto,
            FORMAT(preco_desconto, 4, 'de_DE') AS preco_desconto,
            FORMAT(total, 4, 'de_DE') AS total
        ");
        $this->db->from("vw_pedidos_rep_prods");
        $this->db->where('id_pedido', $id_pedido);
        $this->db->order_by('codigo_produto ASC');

        $query = $this->db->get()->result_array();

        if (count($query) < 1) {
            $query[] = [
                'codigo_produto' => '',
                'produto' => '',
                'preco_unid' => '',
                'quantidade' => '',
                'desconto' => '',
                'preco_desconto' => '',
                'total' => ''
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
