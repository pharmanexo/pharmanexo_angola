<?php

class Resgatadas extends MY_Controller
{

    private $views;
    private $route;
    private $mirrorView;

    public function __construct()
    {
        parent::__construct();

        $this->views = 'fornecedor/ordens_compra/resgatadas';
        $this->route = base_url('fornecedor/ordens_compra/resgatadas');
        $this->mirrorView = 'fornecedor/ordens_compra';

        $this->load->model('Ordem_Compra', 'oc');
        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('M_compradores', 'comp');
    }

    public function index()
    {
        $this->main();
    }

    private function main()
    {

        $page_title = "Ordens de Compra Resgatadas";

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'styles' => [
            ]
        ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnVoltar',
                   'url' => "javascript:history.back(1)",
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Retornar'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/export",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel',
                    'label' => 'Exportar Excel'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
            ]
        ]);

        $data['urlDatatables'] = "{$this->route}/to_datatable";
        $data['urlDetalhes'] = "{$this->route}/detalhes/";
        $data['integradores'] = $this->db->get('integradores')->result_array();
        $data['compradores'] = $this->comp->find("id, CONCAT(cnpj, ' - ', razao_social) AS comprador", null, FALSE, 'comprador ASC');


        $this->load->view("{$this->views}/main", $data);
    }

    public function detalhes($idOC)
    {
        $page_title = "Ordem de Compra";

        $data['oc'] = $this->getOC($idOC);
        $data['oc']['cancelada'] = false;
        $status = $this->db->where('cancel', '1')->where('codigo', $data['oc']['Status_OrdemCompra'])->get('ocs_sintese_status')->result_array();


        if (!empty($status)) $data['oc']['cancelada'] = true;


        $fp = $this->oc->getCotFormaPagamento($data['oc']['id_fornecedor'], $data['oc']['Cd_Cotacao'], $data['oc']['integrador']);

        if ($fp !== false) {
            $data['oc']['fp'] = $fp;
        } else {
            $data['oc']['fp'] = 'Não informado';
        }

        $data['usuario_resgate'] = $this->db->select('id, nome')->where('id', $data['oc']['id_usuario_resgate'])->get('usuarios')->row_array();

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'styles' => [
            ]
        ]);
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
                    'label' => 'Retornar'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnCancel',
                    'url' => "{$this->route}/cancel/{$idOC}",
                    'class' => 'btn-outline-danger',
                    'icone' => 'fa-ban',
                    'label' => 'Cancelar OC'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/export_details/{$idOC}",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel',
                    'label' => 'Exportar Excel'
                ],
                [
                    'type' => 'a',
                    'id' => 'btnMirror',
                    'url' => "{$this->route}/espelho/{$idOC}",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-pdf',
                    'label' => 'Gerar Espelho'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
            ]
        ]);


        $this->load->view("{$this->views}/detalhes", $data);
    }

    public function to_datatable()
    {

        $id_fornecedor = [$this->session->id_fornecedor];

        if (isset($_SESSION['id_matriz'])) {
            $fornecedores = $this->db
                ->select('id')
                ->where('id_matriz', $_SESSION['id_matriz'])
                ->get('fornecedores')->result_array();
            if (!empty($fornecedores)) {

                $id_fornecedor = [];

                foreach ($fornecedores as $fornecedor) {
                    $id_fornecedor[] = $fornecedor['id'];
                }
            }
        }

        if (!empty($id_fornecedor)){
            $id_fornecedor = implode(",", $id_fornecedor);
        }

        $r = $this->datatable->exec(
            $this->input->post(),
            'ocs_sintese',
            [
                ['db' => 'ocs_sintese.id', 'dt' => 'id'],
                ['db' => 'ocs_sintese.Cd_Ordem_Compra', 'dt' => 'Cd_Ordem_Compra'],
                ['db' => 'ocs_sintese.Cd_Cotacao', 'dt' => 'Cd_Cotacao'],
                ['db' => 'ocs_sintese.Hr_Ordem_Compra', 'dt' => 'Hr_Ordem_Compra'],
                ['db' => 'ocs_sintese.Dt_Ordem_Compra', 'dt' => 'Dt_Ordem_Compra'],
                ['db' => 'ocs_sintese.Dt_Ordem_Compra', 'dt' => 'data', 'formatter' => function ($d, $r) {
                    return date('d/m/Y H:i:s', strtotime("{$d} {$r['Hr_Ordem_Compra']}"));
                }],

                ['db' => 'ocs_sintese.Dt_Previsao_Entrega', 'dt' => 'Dt_Previsao_Entrega', 'formatter' => function ($d) {
                    return date("d/m/Y", strtotime($d));
                }],
                ['db' => 'compradores.razao_social', 'dt' => 'razao_social'],
                ['db' => '(select sum(Qt_Produto * Vl_Preco_Produto) from ocs_sintese_produtos where id_ordem_compra = ocs_sintese.id)', 'dt' => 'valor', 'formatter' => function ($d) {
                    return number_format($d, 4, ',', '.');
                }],
                ['db' => 'compradores.id', 'dt' => 'id_cliente'],
                ['db' => 'ocs_sintese.integrador', 'dt' => 'id_integrador'],
                ['db' => 'f.nome_fantasia', 'dt' => 'loja'],
            ],
            [
                ['compradores', 'compradores.id = ocs_sintese.id_comprador'],
                ['fornecedores f', 'f.id = ocs_sintese.id_fornecedor'],
            ],
            "ocs_sintese.pendente = 0 and id_fornecedor in ({$id_fornecedor})"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    private function getOC($idOC)
    {
        $oc = $this->oc->findById($idOC);
        $total = 0;

        $oferta = $this->db->select("prazo_entrega, id_forma_pagamento, valor_minimo, id_usuario")
            ->where('id_fornecedor', $oc['id_fornecedor'])
            ->where('Cd_Cotacao', $oc['Cd_Cotacao'])
            ->get('cotacoes_produtos')
            ->row_array();

        $oferta['condicao_pagto'] = $this->db->where('id', $oferta['id_forma_pagamento'])->get('formas_pagamento')->row_array()['descricao'];

        $oc['oferta'] = $oferta;

        $status = $this->db->where('codigo', $oc['Status_OrdemCompra'])->get('ocs_sintese_status')->row_array();
        $oc['situacao'] = $status['descricao'];

        $oc['produtos'] = $this->oc->get_products($idOC);

        $oc['comprador'] = (isset($oc['id_comprador']) && !empty($oc['id_comprador'])) ? $this->comp->findById($oc['id_comprador']) : 'Comprador não localizado';

        if (isset($oc['Cd_Condicao_Pagamento']) && !empty($oc['Cd_Condicao_Pagamento'])) {
            $id = $oc['Cd_Condicao_Pagamento'];

            if (is_numeric($id)) {
                $select = $this->db->where('id', $id)->get('formas_pagamento')->row_array();

                if (!empty($select)) {
                    $oc['form_pagamento'] = $select['descricao'];
                } else {
                    $oc['form_pagamento'] = $id;
                }
            } else {
                $oc['form_pagamento'] = $id;
            }

        }

        foreach ($oc['produtos'] as $kk => $row) {

            $total = $total + ($row['Qt_Produto'] * $row['Vl_Preco_Produto']);

            if (empty($row['codigo'])) {

                $this->db->select("id_pfv AS codigo, obs_produto");
                $this->db->where('cd_cotacao', $oc['Cd_Cotacao']);
                $this->db->where('id_fornecedor', $oc['id_fornecedor']);
                $this->db->group_start();
                $this->db->where("cd_produto_comprador = '{$row['Cd_Produto_Comprador']}' ");
                $this->db->where('id_produto', $row['Id_Produto_Sintese']);
                $this->db->or_group_start();
                $this->db->where('id_produto', $row['Id_Produto_Sintese']);
                $this->db->group_end();
                $this->db->group_end();

                $item = $this->db->get('cotacoes_produtos')->row_array();

                if (isset($item) && !empty($item)) {

                    # Atualiza o codigo do produto
                    $this->db->where('id_ordem_compra', $row['id_ordem_compra']);
                    $this->db->where('Cd_Produto_Comprador', $row['Cd_Produto_Comprador']);
                    $this->db->where('Id_Produto_Sintese', $row['Id_Produto_Sintese']);
                    $this->db->where('Id_Sintese', $row['Id_Sintese']);
                    $this->db->where("codigo is null");
                    $this->db->update('ocs_sintese_produtos', ['codigo' => $item['codigo']]);

                    $oc['produtos'][$kk]['codigo'] = $item['codigo'];

                    $oc['produtos'][$kk]['obs_cot_produto'] = $item['obs_produto'];
                }
            }else{

                $this->db->select("id_pfv AS codigo, obs_produto");
                $this->db->where('cd_cotacao', $oc['Cd_Cotacao']);
                $this->db->where('id_fornecedor', $oc['id_fornecedor']);
                $this->db->group_start();
                $this->db->where("cd_produto_comprador = '{$row['Cd_Produto_Comprador']}' ");
                $this->db->where('id_produto', $row['Id_Produto_Sintese']);
                $this->db->where('id_pfv', $row['codigo']);
                $this->db->or_group_start();
                $this->db->where('id_produto', $row['Id_Produto_Sintese']);
                $this->db->group_end();
                $this->db->group_end();

                $item = $this->db->get('cotacoes_produtos')->row_array();

                if (isset($item) && !empty($item)) {
                    $oc['produtos'][$kk]['obs_cot_produto'] = $item['obs_produto'];
                }

            }

            if (!empty($oc['produtos'][$kk]['codigo'])) {

                $codigo = $oc['produtos'][$kk]['codigo'];

            } else if (!empty($row['codigo'])) {
                $codigo = $row['codigo'];
            }


            if (!empty($codigo)) {
                $prod = $this->db->select('nome_comercial, apresentacao, marca')
                    ->where('id_fornecedor', $oc['id_fornecedor'])
                    ->where('codigo', $codigo)
                    ->get('produtos_catalogo')
                    ->row_array();
                $oc['produtos'][$kk]['produto_catalogo'] = "{$prod['nome_comercial']} {$prod['apresentacao']}";
                $oc['produtos'][$kk]['Ds_Marca'] = "{$prod['marca']}";
            }


            if (isset($row['programacao']) && !empty($row['programacao'])) {
                $oc['produtos'][$kk]['programacao'] = json_decode($row['programacao'], true);
            }

        }


        if (isset($oc['Telefones_Ordem_Compra']) && !empty($oc['Telefones_Ordem_Compra'])) {
            $oc['Telefones_Ordem_Compra'] = json_decode($oc['Telefones_Ordem_Compra'], true);
        }


        $oc['total'] = $total;

        return $oc;
    }

    public function export()
    {
        $this->db->select("ocs.id");
        $this->db->select("ocs.Dt_Ordem_Compra");
        $this->db->select("ocs.Hr_Ordem_Compra");
        $this->db->select("ocs.Cd_Ordem_Compra");
        $this->db->select("c.razao_social AS empresa");
        $this->db->select("ocs.Dt_Previsao_Entrega");
        $this->db->select("ocs.Cd_Cotacao");
        $this->db->from("ocs_sintese ocs");
        $this->db->join("compradores c", "c.id = ocs.id_comprador");
        $this->db->where("ocs.pendente", 0);
        $this->db->where("ocs.id_fornecedor", $this->session->id_fornecedor);
        $this->db->order_by("Dt_Ordem_Compra DESC");

        $query = $this->db->get()->result_array();

        if (count($query) < 1) {

            $query[] = [
                'data_criacao' => '',
                'ordem_compra' => '',
                'empresa' => '',
                'valor' => '',
                'entrega_acordada' => '',
                'cotacao' => ''
            ];
        } else {

            $data = [];

            foreach ($query as $kk => $row) {

                $this->db->select("sum(Qt_Produto * Vl_Preco_Produto) as value");
                $this->db->where("id_ordem_compra", $row['id']);
                $valor = $this->db->get('ocs_sintese_produtos')->row_array();

                $data[] = [
                    'data_criacao' => date('d/m/Y H:i:s', strtotime("{$row['Dt_Ordem_Compra']} {$row['Hr_Ordem_Compra']}")),
                    'ordem_compra' => $row['Cd_Ordem_Compra'],
                    'empresa' => $row['empresa'],
                    'valor' => number_format($valor['value'], 4, ',', '.'),
                    'entrega_acordada' => date("d/m/Y", strtotime($row['Dt_Previsao_Entrega'])),
                    'cotacao' => $row['Cd_Cotacao']
                ];
            }

            $query = $data;
        }

        $dados_page = ['dados' => $query, 'titulo' => 'ordens_compra'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ($exportar['status'] == false) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }

    public function cancel($idOC)
    {

        if ($this->input->method() == 'post') {
            $oc = $this->getOC($idOC);
            $user = $this->db->where('id', $this->session->id_usuario)->get('usuarios')->row_array();
            $post = $this->input->post();


            if (isset($post['id_status']) && isset($post['id'])) {
                $data = [
                    'Status_OrdemCompra' => $post['id_status'],
                    'motivo_cancelamento' => ((isset($post['obs'])) ? $post['obs'] : '') . " <br> Usuário do cancelamento: {$user['id']} - {$user['nome']} "
                ];

                $this->db->where('id', $post['id']);
                $t = $this->db->update('ocs_sintese', $data);

                if ($t) {
                    $warning = ['type' => 'success', 'message' => 'Ordem de compra cancelada com sucesso.'];
                } else {
                    $warning = ['type' => 'success', 'message' => 'Ordem de compra cancelada com sucesso.'];
                }
            } else {
                $warning = ['type' => 'warning', 'message' => 'Erro ao cancelar.'];
            }


            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        } else {

            $data['dados'] = [
                'form_action' => "{$this->route}/cancel/{$idOC}",
                'id' => $idOC,
                'motivos' => $this->db->where('cancel', '1')->get('ocs_sintese_status')->result_array()
            ];

            $this->load->view("{$this->views}/form", $data);
        }

    }

    public function export_details($idOC)
    {
        $oc = $this->getOC($idOC);

        $query = [];

        foreach ($oc['produtos'] as $kk => $produto) {

            $query[] = [
                'codigo' => $produto['codigo'],
                'produto' => $produto['Ds_Produto_Comprador'],
                'marca' => $produto['Ds_Marca'],
                'unidade' => $produto['Ds_Unidade_Compra'],
                'qtd_embalagem' => $produto['Qt_Embalagem'],
                'qtd_produto' => $produto['Qt_Produto'],
                'preco' => number_format($produto['Vl_Preco_Produto'], 4, ',', '.')
            ];
        }

        if (count($query) < 1) {

            $query[] = [
                'codigo' => '',
                'produto' => '',
                'marca' => '',
                'unidade' => '',
                'qtd_embalagem' => '',
                'qtd_produto' => '',
                'preco' => ''
            ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'produtos'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ($exportar['status'] == false) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }

    public function espelho($id_ordem_compra)
    {
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['header'] = $this->template->header([
            'title' => '',
            'styles' => [

            ]
        ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [

            ]
        ]);
        $data['heading'] = $this->template->heading([
            'page_title' => 'Espelho Ordem de Compra',
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnVoltar',
                    'url' => "{$this->route}/detalhes/{$id_ordem_compra}",
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Retornar'
                ],
                [
                    'type' => 'a',
                    'id' => 'btnPrint',
                    'url' => "",
                    'class' => 'btn-primary',
                    'icone' => 'fa-print',
                    'label' => 'Imprimir'
                ],
            ]
        ]);

        $data['ordem_compra'] = $this->getOC($id_ordem_compra);


     /*   if ($this->session->id_fornecedor == 5037){
            var_dump($data['ordem_compra']);
            exit();
        }*/

        $data['fornecedor'] = $this->fornecedor->findById($this->session->id_fornecedor);

        $this->load->view("{$this->mirrorView}/mirror", $data);
    }
}
