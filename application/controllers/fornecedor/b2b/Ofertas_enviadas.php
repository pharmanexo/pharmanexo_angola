<?php

class Ofertas_enviadas extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('fornecedor/b2b/ofertas_enviadas');
        $this->views = 'fornecedor/b2b/ofertas_enviadas/';

        $this->load->model('ofertas_b2b');
        $this->load->model('ofertas_b2b_itens');
        $this->load->model('m_representante', 'representante');
        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_usuarios', 'usuario');
        $this->load->model('m_estoque', 'estoque');

    }

    public function index()
    {
        $page_title = "Ofertas Enviadas";

        $data['datatables'] = "{$this->route}/datatables";
        $data['url_detalhes'] = "{$this->route}/detalhes/";

        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnVoltar',
                    'url' => "ofertas",
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Retornar'
                ],
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

        $data['dados'] = $this->db->where('id_solicitacao', $id_solicitacao)->get('vw_ofertas_b2b')->row_array();
        $data['itens'] = $this->db->where('id_solicitacao', $id_solicitacao)->where('aprovado_em is not null')->get('ofertas_b2b_itens')->row_array();

        $data['interessado'] =

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
                    'label' => 'Exportar Excel'
                ]
            ]
        ]);

        $this->load->view("{$this->views}/details", $data);
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
                    }],
            ],
            null,
            "vw_ofertas_b2b.id_fornecedor_interessado = {$this->session->id_fornecedor}"
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
            "ofertas_b2b_itens.id_solicitacao = {$id_solicitacao}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function exportar()
    {
        $this->db->select(" 
            id_solicitacao AS data_oferta,
            razao_social AS fornecedor_interessado,
            cnpj,
            estado,
            quantidade,
            itens AS total_itens");
        $this->db->from("vw_ofertas_b2b");
        $this->db->where('id_fornecedor_interessado', $this->session->id_fornecedor);

        $query = $this->db->get()->result_array();

        if ( count($query) < 1 ) {
            $query[] = [
                'data_oferta' => '',
                'fornecedor_interessado' => '',
                'cnpj' => '',
                'estado' => '',
                'quantidade' => '',
                'total_itens' => ''
            ];
        } else {

            foreach ($query as $k => $oferta) {
            
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
            FORMAT(obi.valor_maximo, 4, 'de_DE') AS valor_maximo
            ");
        $this->db->from("ofertas_b2b_itens obi");
        $this->db->join('produtos_catalogo pc', "pc.codigo = obi.codigo AND pc.id_fornecedor = obi.id_fornecedor_oferta");
        $this->db->join('vw_formas_pagamento_fornecedores vff', "vff.id = obi.id_forma_pagamento", 'LEFT');
        $this->db->join('prazos_entrega', "prazos_entrega.id = obi.id_prazo_entrega", 'LEFT');
        $this->db->where('obi.id_solicitacao', $id_solicitacao);

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