<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pedidos extends Admin_controller
{
    private $route, $views;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('admin/representantes/pedidos');
        $this->views = "admin/representantes/pedidos";

        $this->load->model('m_representante', 'rep');
        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_pedido_rep', 'pedido');
        $this->load->model('m_compradores', 'cliente');
    }

    /**
     * Exibe a view fornecedor/representantes/pedidos/main.php
     *
     * @return  view
     */
    public function index()
    {
        $page_title = 'Pedidos Representantes';

        $data['datatables'] = "{$this->route}/datatables/";
        $data['url_detalhes'] = "{$this->route}/detalhes/";
        $data['url_exportar'] = "{$this->route}/exportar/";
        $data['situacao'] = statusPedidoRepresentante();
        $data['fornecedores'] = $this->fornecedor->find("id, nome_fantasia", null, false, 'nome_fantasia ASC');

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'buttons' => [
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel', 
                    'label' => 'Exportar Excel'
                ]
            ]
        ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts();
        $data['heading'] = $this->template->heading([ 'page_title' => $page_title ]);

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Exibe a view com os dados do pedido selecionado
     *
     * @param - int ID do fornecedor
     * @param - int ID do pedido
     * @return  view
     */
    public function detalhes($id_fornecedor, $id_pedido)
    {
        $page_title = 'Itens do Pedido';

        $data['datatables'] = "{$this->route}/datatables_produtos/{$id_pedido}";
        $data['url_reject'] = "{$this->route}/reject_item/{$id_pedido}";

        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts();

        $pedido = $this->pedido->find("*", "id = {$id_pedido}", true);
        
        $buttons = [
            [
                'type'  => 'a',
                'id'    => 'btnBack',
                'url'   => "{$this->route}",
                'class' => 'btn-secondary',
                'icone' => 'fa-arrow-left',
                'label' => 'Voltar'
            ],
            [
                'type' => 'button',
                'id' => 'btnExport',
                'url' => "{$this->route}/exportar_detalhes/{$id_pedido}",
                'class' => 'btn-primary',
                'icone' => 'fa-file-excel', 
                'label' => 'Exportar Excel'
            ],
            [
                'type' => 'button',
                'id' => 'btnReject',
                'url' => "{$this->route}/reject/{$id_pedido}",
                'class' => 'btn-danger',
                'icone' => 'fa-ban',
                'label' => 'Rejeitar pedido'
            ],
            [
                'type' => 'a',
                'id' => 'btnApprove',
                'url' =>  "{$this->route}/approve/{$id_pedido}",
                'class' => 'btn-primary',
                'icone' => 'fa-thumbs-up',
                'label' => 'Aprovar pedido'
            ]
        ];

        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => $buttons
        ]);

        $this->load->view("{$this->views}/details", $data);
    }

    /**
     * Obtem dados para o datatable de admi/representantes/pedidos
     *
     * @param int ID do fornecedor
     * @return  json
     */
    public function datatables($id_fornecedor)
    {
        $datatables = $this->datatable->exec(
            $this->input->post(),
            'pedidos_representantes pr',
            [
                ['db' => 'pr.id', 'dt' => 'id'],
                ['db' => 'pr.id_representante', 'dt' => 'id_representante'],
                ['db' => 'pr.id_fornecedor', 'dt' => 'id_fornecedor'],
                ['db' => 'pr.id_comprador', 'dt' => 'id_comprador'],
                ['db' => 'pr.condicao_pagamento', 'dt' => 'condicao_pagamento'],
                ['db' => 'pr.prazo_entrega', 'dt' => 'prazo_entrega'],
                ['db' => 'pr.valor_minimo', 'dt' => 'valor_minimo'],
                ['db' => 'pr.situacao', 'dt' => 'situacao'],
                ['db' => 'pr.data_abertura', 'dt' => 'data_abertura'],
                ['db' => 'representantes.nome', 'dt' => 'representante'],
                ['db' => 'compradores.razao_social', 'dt' => 'comprador'],
                [
                    'db' => 'pr.situacao', 
                    'dt' => 'status_situacao',
                    'formatter' => function ($value, $row) {
                        return statusPedidoRepresentante($value);
                    }
                ],
                [
                    'db' => 'pr.data_abertura', 
                    'dt' => 'data',
                    'formatter' => function($value, $row) {
                        return date('d/m/Y H:i:s', strtotime($value));
                    }
                ],
            ],
            [
                ['representantes', 'representantes.id = pr.id_representante', 'LEFT'],
                ['compradores', 'compradores.id = pr.id_comprador', 'LEFT'],
            ],
            "pr.id_fornecedor = {$id_fornecedor} AND pr.situacao in (2, 3)",
            null,
            "pr.id ASC"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    /**
     * Obtem dados dos produtos do pedido para datatable
     *
     * @param - int id do pedido
     * @return  json
     */
    public function datatables_produtos($id_pedido)
    {
        $datatables = $this->datatable->exec(
            $this->input->post(),
            'pedidos_representantes_produtos prp',
            [
                ['db' => 'prp.id_pedido', 'dt' => 'id_pedido'],
                ['db' => 'prp.cd_produto_fornecedor', 'dt' => 'cd_produto_fornecedor'],
                ['db' => 'prp.quantidade_solicitada', 'dt' => 'quantidade_solicitada'],
                ['db' => 'pc.nome_comercial', 'dt' => 'nome_comercial'],
                ['db' => 'prp.motivo', 'dt' => 'motivo'],
                ['db' => 'prp.status', 'dt' => 'status'],
                [
                    'db' => 'prp.preco_unidade', 
                    'dt' => 'preco_unidade',
                    'formatter' => function ($value, $row) {
                        return number_format($value, 4, ",", ".");
                    }
                ],
                [
                    'db' => 'prp.desconto', 
                    'dt' => 'desconto',
                    'formatter' => function ($value, $row) {
                        return number_format($value, 4, ",", ".");
                    }
                ],
                [
                    'db' => 'prp.preco_desconto', 
                    'dt' => 'preco_desconto',
                    'formatter' => function ($value, $row) {
                        return number_format($value, 4, ",", ".");
                    }
                ],
                [
                    'db' => 'prp.total', 
                    'dt' => 'total',
                    'formatter' => function ($value, $row) {
                        return number_format($value, 4, ",", ".");
                    }
                ],
            ],
            [
                ['pedidos_representantes', 'pedidos_representantes.id = prp.id_pedido'],
                ['produtos_catalogo pc', 'pc.codigo = prp.cd_produto_fornecedor AND pedidos_representantes.id_fornecedor = pc.id_fornecedor', 'LEFT'],
            ],
            "prp.id_pedido = {$id_pedido}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    /**
     * Aprova os itens com status 0 do pedido
     *
     * @param - int - ID do pedido
     * @return  json
     */
    public function approve($id_pedido)
    {
        if ($this->input->is_ajax_request()){

            $pedido = $this->pedido->find("*", "id = {$id_pedido}", true);
            $representante = $this->rep->findById($pedido['id_representante']);

            $fornecedor = $this->fornecedor->findById($pedido['id_fornecedor']);

            $data = ["status" => 1 ];

            $this->db->where('id_pedido', $id_pedido);
            $this->db->where('status', 0);
        
            # Não existe itens para aprovação
            if ($this->db->get('pedidos_representantes_produtos')->num_rows() < 1) {
                $warning = ["type" => "success", "message" => "Registrado com sucesso"];
            } else {
                if($this->db->update("pedidos_representantes_produtos", $data, "id_pedido = {$id_pedido} AND status = 0")) {

                    $total = $this->pedido->totalPedidoAprovado($pedido['id']);

                    $cliente = $this->cliente->findById($pedido['id_comprador']);

                    $cliente_estado = $this->db->where('uf', $cliente['estado'])->get('estados')->row_array();

                    # Ordem de Compra

                    $oc = [
                        'id_fornecedor' => $fornecedor['id'],
                        'id_cliente' => $pedido['id_comprador'],
                        'id_status_ordem_compra' =>  1,
                        'id_pedido' =>  $pedido['id'],
                        'valor_total' => $total,
                        'id_tipo_Venda' =>  5,
                        'id_estado' => $cliente_estado['id'],
                        'ordem_compra' => 0,
                        'parcelas' => 1,
                        'data_ordem_compra' => date('Y-m-d'),
                        'codigo_cotacao' => $pedido['id']
                    ];

                    $this->db->insert('ordens_compra', $oc);

                    $date = date('d/m/Y');

                    $destinatarios = "marlon.boecker@pharmanexo.com.br, ericlempe1994@gmail.com";

                    # Concatena o email de cada promoção
                    if ( !empty($fornecedor['emails_config']) ) {

                        $emails = json_decode($fornecedor['emails_config']);

                        if ( !empty($emails->representantes) ) {

                            $destinatarios .=  ", {$emails->representantes}";
                        }
                    }

                    #noticar por e-mail
                    $noticar = [
                       "to" => $destinatarios,
                       "greeting" => "{$representante['nome']}",
                       "subject" => "Pedido #{$id_pedido} Aprovado / Representante: {$representante['nome']}",
                       "message" => "O pedido #{$id_pedido} enviado em {$date} foi aprovado!"
                    ];

                    $this->notify->send($noticar);

                    #noticar por notifações pharmanexo
                    $alert = [
                        "id_usuario" => null,
                        "id_fornecedor" => $pedido['id_fornecedor'],
                        "message" => "Pedido #{$id_pedido} do representante {$representante['nome']} foi aprovado. clique para ver mais",
                        "url" => base_url("fornecedor/representantes/pedidos/detalhes/{$id_pedido}")
                    ];

                    $this->notify->alert($alert);

                    $warning = ["type" => "success", "message" => "Registrado com sucesso"];
                } else {

                    $warning = ["type" => "warning", "message" => "Erro ao registrar"];
                }
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($warning));
    }

    /**
     * Rejeita os itens do pedido
     *
     * @param - int - ID do pedido
     * @return  json
     */
    public function reject($id_pedido)
    {
        if ($this->input->is_ajax_request()){

            $pedido = $this->pedido->find("*", "id = {$id_pedido}", true);
            $representante = $this->rep->findById($pedido['id_representante']);

            $data = [ "motivo" => $this->input->post('motivo'), "status" => 9 ];

            if($this->db->update("pedidos_representantes_produtos", $data, "id_pedido = {$id_pedido}")){

                $date = date('d/m/Y');

                $fornecedor = $this->fornecedor->findById($pedido['id_fornecedor']);

                $destinatarios = "marlon.boecker@pharmanexo.com.br, ericlempe1994@gmail.com";

                # Concatena o email de cada promoção
                if ( !empty($fornecedor['emails_config']) ) {

                    $emails = json_decode($fornecedor['emails_config']);

                    if ( !empty($emails->representantes) ) {
                        $destinatarios .=  ", {$emails->representantes}";
                    }
                }

                #noticar por e-mail
                $noticar = [
                   "to" => $destinatarios,
                   "greeting" => "{$representante['nome']}",
                   "subject" => "Pedido #{$id_pedido} Rejeitado / Representante: {$representante['nome']}",
                   "message" => "O pedido #{$id_pedido} enviado em {$date} não pôde ser aprovado: {$data['motivo']}"
                ];

                $this->notify->send($noticar);

                #noticar por notifações pharmanexo
                $alert = [
                    "id_usuario" => null,
                    "id_fornecedor" => $pedido['id_fornecedor'],
                    "message" => "Pedido #{$id_pedido} do representante {$representante['nome']} foi rejeitado. clique para ver mais",
                    "url" => base_url("fornecedor/representantes/pedidos/detalhes/{$id_pedido}")
                ];

                $this->notify->alert($alert);

                $warning = [
                    "type" => "success",
                    "message" => "Registrado com sucesso"
                ];
            }else{
                $warning = [
                    "type" => "warning",
                    "message" => "Erro ao registrar"
                ];
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($warning));
    }

    /**
     * Rejeita o item
     *
     * @param - int - id do pedido.
     * @param - post - codigo do produto (cd_produto_fornecedor)
     * @return  json
     */
    public function reject_item($id_pedido)
    {
        if ($this->input->is_ajax_request()){

            $post = $this->input->post();

            $data = ['status' => 9, 'motivo' => $post['motivo']];
         
            if($this->db->update("pedidos_representantes_produtos", $data, "id_pedido = {$id_pedido} AND cd_produto_fornecedor = {$post['codigo']}")){

                $warning = [
                    "type" => "success",
                    "message" => "Registrado com sucesso"
                ];
            }else{
                $warning = [
                    "type" => "warning",
                    "message" => "Erro ao registrar"
                ];
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($warning));
    }

    public function exportar($id_fornecedor = null)
    {
        if ( isset($id_fornecedor) ) {
                
            $this->db->select("
                pr.id,
                r.nome AS representante,
                c.razao_social AS comprador,
                pr.prazo_entrega,
                data_abertura
                ");
            $this->db->from("pedidos_representantes pr");
            $this->db->join('representantes r', 'r.id = pr.id_representante', 'left');
            $this->db->join('compradores c', 'c.id = pr.id_comprador', 'left');
            $this->db->where('pr.id_fornecedor', $id_fornecedor);
            $this->db->where("pr.situacao in (2, 3)");
            $this->db->order_by("r.id ASC");

            $query = $this->db->get()->result_array();
        } else {
            $query = [];
        }

        if ( count($query) < 1 ) {
            $query[] = [
                'id' => '',
                'representante' => '',
                'comprador' => '',
                'prazo_entrega' => '',
                'data_abertura' => ''
            ];
        } else {

            foreach ($query as $kk => $row) {
                    
                $query[$kk]['data_abertura'] = date("d/m/Y H:i:s", strtotime($row['data_abertura']));
            }
        }

        $dados_page = ['dados' => $query, 'titulo' => 'Pedidos'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }

    public function exportar_detalhes($id_pedido)
    {
        $this->db->select("
            prp.cd_produto_fornecedor AS codigo_produto,
            pc.nome_comercial AS produto,
            prp.quantidade_solicitada,
            prp.preco_unidade AS preco_unidade,
            prp.desconto AS desconto,
            prp.preco_desconto AS preco_desconto,
            prp.total AS total");
        $this->db->from("pedidos_representantes_produtos prp");
        $this->db->join('pedidos_representantes pr', 'pr.id = prp.id_pedido');
        $this->db->join('produtos_catalogo pc', 'pc.codigo = prp.cd_produto_fornecedor and pr.id_fornecedor = pc.id_fornecedor', 'left');
        $this->db->where("prp.id_pedido", $id_pedido);
       

        $query = $this->db->get()->result_array();

         if (count($query) < 1 ) {
           $query[] = [
                'codigo_produto' => '',
                'produto' => '',
                'quantidade_solicitada' => '',
                'preco_unidade' => '',
                'desconto' => '',
                'preco_desconto' => '',
                'total' => ''
           ];
        } else {

            foreach ($query as $kk => $row) {
                
                $query[$kk]['preco_unidade'] = number_format($row['preco_unidade'], 4, ',', '.');
                $query[$kk]['desconto'] = number_format($row['desconto'], 4, ',', '.');
                $query[$kk]['preco_desconto'] = number_format($row['preco_desconto'], 4, ',', '.');
                $query[$kk]['total'] = number_format($row['total'], 4, ',', '.');
            }
        }

        $dados_page = ['dados' => $query, 'titulo' => 'representantes'];

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