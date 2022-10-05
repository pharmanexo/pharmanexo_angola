<?php

class Ofertas_recebidas extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('fornecedor/b2b/ofertas_recebidas');
        $this->views = 'fornecedor/b2b/ofertas_recebidas/';

        $this->load->model('ofertas_b2b');
        $this->load->model('ofertas_b2b_itens');
        $this->load->model('m_representante', 'representante');
        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_usuarios', 'usuario');
        $this->load->model('m_estoque', 'estoque');
    }

    public function index()
    {
        $page_title = "Ofertas Recebidas";

        $data['datatables'] = "{$this->route}/datatables";
        $data['url_detalhes'] = "{$this->route}/detalhes/";

        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
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
        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/main", $data);
    }

    public function detalhes($id_solicitacao)
    {
        $page_title = 'Itens da Oferta';

        $data['datatables'] = "{$this->route}/datatables_itens_oferta/{$id_solicitacao}";
        $data['url_rejeitar'] = "{$this->route}/rejeitar_item/{$id_solicitacao}/";

        $data['dados'] = $this->db->where('id_solicitacao', $id_solicitacao)
            ->where('id_fornecedor_oferta', $this->session->id_fornecedor)
            ->get('vw_ofertas_b2b')->row_array();
        $data['itens'] = $this->db
            ->where('id_fornecedor_oferta', $this->session->id_fornecedor)
            ->where('id_solicitacao', $id_solicitacao)->where('aprovado_em is not null')->get('ofertas_b2b_itens')->row_array();


        if ( $data['dados']['id_fornecedor_oferta'] != $this->session->id_fornecedor) {
            $warning = [
                "type" => "error",
                "message" => "Distribuidor não autorizado!"
            ];

            $this->session->userdata('warning', $warning);

            redirect(base_url('dashboard'));
        }

        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnBack',
                    'url' => "{$this->route}",
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar_itens/{$id_solicitacao}",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel', 
                    'label' => 'Exportar'
                ],
                [
                    'type' => 'a',
                    'id' => 'btnRejeitar',
                    'url' => "{$this->route}/rejeitar_todos/{$id_solicitacao}",
                    'class' => 'btn-outline-danger btn_rejeitar',
                    'icone' => 'fa-ban',
                    'label' => 'Rejeitar toda proposta'
                ],
                [
                    'type' => 'a',
                    'id' => 'btnAceitar',
                    'url' => "{$this->route}/aprovar/{$id_solicitacao}",
                    'class' => 'btn-primary btn_aceitar',
                    'icone' => 'fa-check',
                    'label' => 'Aceitar e Gerar OC'
                ]
            ]
        ]);

        $this->load->view("{$this->views}/details", $data);
    }

    public function rejeitar_todos($id_solicitacao)
    {

        if ($this->input->is_ajax_request()) {

            $solicitacao = $this->ofertas_b2b->find("*", "id_solicitacao = {$id_solicitacao}", true);
            $solicitante = $this->fornecedor->findById($solicitacao['id_fornecedor_interessado']);
            $usuario = $this->usuario->findById($solicitacao['id_usuario']);

            $data = [
                "motivo" => $this->input->post('motivo'),
                "status" => 9
            ];

            if ($this->db->update("ofertas_b2b_itens", $data, "id_solicitacao = {$id_solicitacao}")) {

                $date = date('d/m/Y', $id_solicitacao);

                #noticar por e-mail
                $noticar = [
                    "to" => "marlon.boecker@pharmanexo.com.br",
                    "greeting" => "Distribuidor x Distribuidor",
                    "subject" => "Proposta não atendida - {$id_solicitacao}",
                    "message" => "A proposta #{$id_solicitacao} enviada em {$date} não pôde ser atendida: {$data['motivo']}"
                ];

                $this->notify->send($noticar);

                #noticar por notifações pharmanexo
                $alert = [
                    "id_usuario" => $usuario['id'],
                    "id_fornecedor" => $solicitante['id'],
                    "message" => "Prosposta #{$id_solicitacao} Distribuidor x Distribuidor não atendida. clique para ver mais",
                    "url" => base_url("fornecedor/b2b/ofertas_enviadas/{$id_solicitacao}")
                ];

                $this->notify->alert($alert);

                $warning = [
                    "type" => "success",
                    "message" => "Registrado com sucesso"
                ];
            } else {
                $warning = [
                    "type" => "warning",
                    "message" => "Erro ao registrar"
                ];
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($warning));
    }

    public function rejeitar_item($id_solicitacao, $codigo)
    {

        if ($this->input->is_ajax_request()) {

            $solicitacao = $this->ofertas_b2b->find("*", "id_solicitacao = {$id_solicitacao}", true);
            $solicitante = $this->fornecedor->findById($solicitacao['id_fornecedor_interessado']);
            $usuario = $this->usuario->findById($solicitacao['id_usuario']);
            $item = $this->estoque->getProdCat($codigo, $solicitacao['id_fornecedor_oferta']);

            $data = [
                "motivo" => $this->input->post('motivo'),
                "status" => 9
            ];

            if ($this->db->update("ofertas_b2b_itens", $data, "id_solicitacao = {$id_solicitacao} and codigo = {$codigo}")) {

                $date = date('d/m/Y', $id_solicitacao);

                #noticar por e-mail
                $noticar = [
                    "to" => "marlon.boecker@pharmanexo.com.br",
                    "greeting" => "Distribuidor x Distribuidor",
                    "subject" => "O item {$codigo} - {$item['nome_comercial']} da proposta  - {$id_solicitacao} foi rejeitado",
                    "message" => "O item {$item['nome_comercial']} - {$item['apresentacao']} da prosposta #{$id_solicitacao} Distribuidor x Distribuidor não pôde ser atendido.  <br> Motivo: {$data['motivo']}"
                ];

                $this->notify->send($noticar);

                #noticar por notifações pharmanexo
                $alert = [
                    "id_usuario" => $usuario['id'],
                    "id_fornecedor" => $solicitante['id'],
                    "message" => "O item {$item['nome_comercial']} - {$item['apresentacao']} da prosposta #{$id_solicitacao} Distribuidor x Distribuidor não pôde ser atendido. clique para ver mais",
                    "url" => base_url("fornecedor/b2b/ofertas_enviadas/{$id_solicitacao}")
                ];

                $this->notify->alert($alert);

                $warning = [
                    "type" => "success",
                    "message" => "Registrado com sucesso"
                ];
            } else {
                $warning = [
                    "type" => "warning",
                    "message" => "Erro ao registrar"
                ];
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($warning));
    }

    public function aprovar($id_solicitacao)
    {

        $solicitacao = $this->ofertas_b2b->find("*", "id_solicitacao = {$id_solicitacao}", true);
        $solicitante = $this->fornecedor->findById($solicitacao['id_fornecedor_interessado']);
        $usuario = $this->usuario->findById($solicitacao['id_usuario']);
        $dist = $this->fornecedor->findById($solicitacao['id_fornecedor_oferta']);
        $logado = $this->usuario->findById($this->session->id_usuario);

        $data = [
            "status" => 1,
            "aprovado_em" => date('Y-m-d H:i:s', time())
        ];

        $destinatarios = "marlon.boecker@pharmanexo.com.br, ericlempe1994@gmail.com";

        if ( !empty($solicitante['emails_config']) ) {

            $emails = json_decode($solicitante['emails_config']);

            if ( !empty($emails->distribuidor_distribuidor) ) {
                $destinatarios .=  ", {$emails->distribuidor_distribuidor}";
            }
        }

        if ($this->db->update("ofertas_b2b_itens", $data, "id_solicitacao = {$id_solicitacao} and status = 0")) {

            $date = date('d/m/Y', $id_solicitacao);

            #noticar por e-mail
            $noticar = [
                "to" => $destinatarios,
                "greeting" => "Distribuidor x Distribuidor",
                "subject" => "Proposta aprovada - {$id_solicitacao}",
                "message" => "A proposta #{$id_solicitacao} enviada em {$date} foi aprovada. 
                                <br><br> Entre em contato com o distribuidor: 
                                <br> Telefone: {$dist['telefone']} - {$dist['celular']} 
                                <br> E-mail: {$dist['email']} <br><br> Aprovado por: {$logado['nome']} - {$logado['telefone']}  {$logado['celular']} <br> E-mail: {$logado['email']}"
            ];

            $this->notify->send($noticar);

            #noticar por notifações pharmanexo
            $alert = [
                "id_usuario" => $usuario['id'],
                "id_fornecedor" => $solicitante['id'],
                "message" => "Prosposta #{$id_solicitacao} Distribuidor x Distribuidor foi aprovada. clique para ver mais",
                "url" => base_url("fornecedor/b2b/ofertas_enviadas/{$id_solicitacao}")
            ];

            $this->notify->alert($alert);


            $warning = [
                "type" => "success",
                "message" => "Registrado com sucesso"
            ];
        } else {
            $warning = [
                "type" => "warning",
                "message" => "Erro ao registrar"
            ];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($warning));
    }

    public function datatables()
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'vw_ofertas_b2b',
            [
                ['db' => 'vw_ofertas_b2b.id_solicitacao', 'dt' => 'id_solicitacao'],
                ['db' => 'vw_ofertas_b2b.id_forma_pagamento', 'dt' => 'id_forma_pagamento'],
                ['db' => 'vw_ofertas_b2b.valor_maximo', 'dt' => 'valor_maximo'],
                ['db' => 'vw_ofertas_b2b.id_prazo_entrega', 'dt' => 'id_prazo_entrega'],
                ['db' => 'vw_ofertas_b2b.quantidade', 'dt' => 'quantidade'],
                ['db' => 'vw_ofertas_b2b.codigo', 'dt' => 'codigo'],
                ['db' => 'vw_ofertas_b2b.id_fornecedor_interessado', 'dt' => 'id_fornecedor_interessado'],
                ['db' => 'vw_ofertas_b2b.id_fornecedor_oferta', 'dt' => 'id_fornecedor_oferta'],
                ['db' => 'vw_ofertas_b2b.cnpj', 'dt' => 'cnpj'],
                ['db' => 'vw_ofertas_b2b.razao_social', 'dt' => 'razao_social'],
                ['db' => 'vw_ofertas_b2b.estado', 'dt' => 'estado'],
                ['db' => 'vw_ofertas_b2b.itens', 'dt' => 'itens'],
                [
                    'db' => 'vw_ofertas_b2b.id_solicitacao',
                    'dt' => 'data',
                    'formatter' => function ($value, $row) {
                        return date('d/m/Y', $value);
                    }
                ],
                [
                    'db' => 'vw_ofertas_b2b.id_solicitacao',
                    'dt' => 'status',
                    'formatter' => function ($value, $row) {
                       
                        return $this->getStatus($value, $row['itens']);


                    }
                ],
            ],
            null,
            "vw_ofertas_b2b.id_fornecedor_oferta = {$this->session->id_fornecedor}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function datatables_itens_oferta($id_solicitacao)
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'ofertas_b2b_itens',
            [
                ['db' => 'ofertas_b2b_itens.id_solicitacao', 'dt' => 'id_solicitacao'],
                ['db' => 'ofertas_b2b_itens.status', 'dt' => 'status'],
                ['db' => 'ofertas_b2b_itens.quantidade', 'dt' => 'quantidade'],
                ['db' => 'ofertas_b2b_itens.codigo', 'dt' => 'codigo'],
                ['db' => 'ofertas_b2b_itens.id_fornecedor_interessado', 'dt' => 'id_fornecedor_interessado'],
                ['db' => 'ofertas_b2b_itens.id_fornecedor_oferta', 'dt' => 'id_fornecedor_oferta'],
                ['db' => 'produtos_catalogo.nome_comercial', 'dt' => 'nome_comercial'],
                ['db' => 'produtos_catalogo.apresentacao', 'dt' => 'apresentacao'],
                ['db' => 'vw_formas_pagamento_fornecedores.descricao', 'dt' => 'id_forma_pagamento'],
                ['db' => 'prazos_entrega.prazo', 'dt' => 'id_prazo_entrega'],
                [
                    'db' => 'produtos_catalogo.descricao',
                    'dt' => 'descricao',
                    'formatter' => function ($value, $row) {
                        if (!empty($row['descricao'])) {
                            return "{$row['nome_comercial']} - {$row['descricao']}";
                        }
                        return "{$row['nome_comercial']} - {$row['apresentacao']}";
                    }
                ],
                [
                    'db' => 'ofertas_b2b_itens.valor_maximo',
                    'dt' => 'valor_maximo',
                    'formatter' => function ($value, $row) {
                        return number_format($value, 4, ",", ".");
                    }
                ],
            ],
            [
                ['produtos_catalogo', 'produtos_catalogo.codigo = ofertas_b2b_itens.codigo AND produtos_catalogo.id_fornecedor = ofertas_b2b_itens.id_fornecedor_oferta'],
                ['vw_formas_pagamento_fornecedores', 'vw_formas_pagamento_fornecedores.id = ofertas_b2b_itens.id_forma_pagamento', 'LEFT'],
                ['prazos_entrega', 'prazos_entrega.id = ofertas_b2b_itens.id_prazo_entrega', 'LEFT'],
            ],
            "ofertas_b2b_itens.id_solicitacao = {$id_solicitacao} and ofertas_b2b_itens.id_fornecedor_oferta = {$this->session->id_fornecedor}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function getStatus($id_solicitacao, $total)
    {
        $this->db->select("SUM(IF( (status = 1) , 1, 0)) as 'aprovados', 
                           SUM(IF( (status = 9) , 1, 0)) as 'rejeitados',
                           SUM(IF( (status = 0) , 1, 0)) as 'resto'");
        $this->db->where('id_solicitacao', $id_solicitacao);
        $this->db->where('id_fornecedor_oferta', $this->session->id_fornecedor);
        $ofertas = $this->db->get('ofertas_b2b_itens')->row_array();

        $status = "";

        if ($ofertas['aprovados'] == $total) {
            $status = "Aprovado";
        } elseif($ofertas['rejeitados'] == $total) {
            $status = "Rejeitado";
        } elseif($ofertas['resto'] == $total) {
            $status = "Aguardando";
        } else {
            if ($ofertas['aprovados'] > 0 && $ofertas['rejeitados'] > 0) {
               $status = "Aprovado Parcialmente";
            }
        }
       
        return $status;
    }

    public function exportar()
    {
        $this->db->select("
            id_solicitacao AS data_oferta,
            id_solicitacao AS status,
            razao_social AS fornecedor_interessado,
            cnpj,
            estado,
            itens AS total_itens");
        $this->db->from("vw_ofertas_b2b");
        $this->db->where('id_fornecedor_oferta', $this->session->id_fornecedor);

        $query = $this->db->get()->result_array();

        if ( count($query) < 1 ) {
           
            $query[] = [
                'data_oferta' => '',
                'status' => '',
                'fornecedor_interessado' => '',
                'cnpj' => '',
                'estado' => '',
                'total_itens' => ''
            ];
        } else {

            foreach ($query as $k => $oferta) {

                $status = $this->getStatus($oferta['status'], $oferta['total_itens']);

                $query[$k]['status'] = $status;
                $query[$k]['data_oferta'] = date('d/m/Y',  $oferta['data_oferta']);
            }
        }

        $dados_page = ['dados' => $query , 'titulo' => 'Ofertas'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }

    public function exportar_itens($id_solicitacao)
    {
        $this->db->select(" 
            CASE WHEN pc.descricao is null THEN CONCAT(pc.nome_comercial, ' - ', pc.apresentacao) ELSE CONCAT(pc.nome_comercial, ' - ', pc.descricao) END  AS produto,
            vff.descricao AS forma_pagamento,
            prazos_entrega.prazo AS prazo_entrega,
            obi.quantidade,
            FORMAT(obi.valor_maximo, 4, 'de_DE') AS valor_maximo");
        $this->db->from("ofertas_b2b_itens obi");
        $this->db->join('produtos_catalogo pc', "pc.codigo = obi.codigo AND pc.id_fornecedor = obi.id_fornecedor_oferta");
        $this->db->join('vw_formas_pagamento_fornecedores vff', "vff.id = obi.id_forma_pagamento", 'LEFT');
        $this->db->join('prazos_entrega', "prazos_entrega.id = obi.id_prazo_entrega", 'LEFT');
        $this->db->where('obi.id_solicitacao', $id_solicitacao);
        $this->db->where('obi.id_fornecedor_oferta', $this->session->id_fornecedor);

        $query = $this->db->get()->result_array();

        if ( count($query) < 1 ) {
           
            $query[] = [
                'produto' => '',
                'forma_pagamento' => '',
                'prazo_entrega' => '',
                'quantidade' => '',
                'valor_maximo' => ''
            ];
        } 

        $dados_page = ['dados' => $query , 'titulo' => 'Ofertas'];

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