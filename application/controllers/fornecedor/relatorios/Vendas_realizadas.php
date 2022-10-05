<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Vendas_realizadas extends MY_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/relatorios/vendas_realizadas');
        $this->views = 'fornecedor/relatorios/vendas_realizadas';

        $this->load->model('m_logistica', 'ordem_compra');
        $this->load->model('m_status_ordem_compra', 'status');
    }

    public function index()
    {
        $page_title = 'Vendas Realizadas';

        $data['dataTable'] = "{$this->route}/getDatasource";
        $data['options'] = $this->status->getStatus();
        $data['url_detalhes'] = "{$this->route}/detalhes";

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
                ],
            ]
        ]);

        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();

        $data['heading'] = $this->template->heading([
            'page_title' => $page_title
        ]);

        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/main", $data);
    }

    public function detalhes($id)
    {

        $data['row'] = $this->ordem_compra->getDetails($id);
        $data['produtos'] = $this->ordem_compra->getProdutoOC($id);

        #var_dump($data['produtos']);exit();

        $page_title = "Ordem de Compra: #{$data['row']['ordem_compra']}";
        $data['header'] = $this->template->header([
            'title' => $page_title
        ]);

        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();

        $data['heading'] = $this->template->heading([
            'page_title'=> $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnBack',
                    'url' => $this->route,
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ],
            ]
        ]);

        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/detail", $data);
    }

    public function getDatasource()
    {
        $r = $this->datatable->exec(
            $this->input->get(),
            'ordens_compra',
            [
                ['db' => 'ordens_compra.id', 'dt' => 'id'],
                ['db' => 'ordens_compra.ordem_compra', 'dt' => 'ordem_compra'],
                ['db' => 'compradores.razao_social', 'dt' => 'razao_social'],
                ['db' => 'compradores.cnpj', 'dt' => 'cnpj'],
                ['db' => 'status_ocs.descricao', 'dt' => 'status_ordem_compra'],
                ['db' => 'ordens_compra.valor_total', 'dt' => 'valor_total'],
                ['db' => 'ordens_compra.condicao_pagamento', 'dt' => 'condicao_pagamento'],
                ['db' => 'ordens_compra.data_emissao', 'dt' => 'data_emissao', 'formatter' => function ($d) {
                    return date('d/m/Y', strtotime($d));
                }],
                ['db' => 'ordens_compra.id_status_ordem_compra', 'dt' => 'id_status_ordem_compra']
            ],
            [
                ['compradores', 'ordens_compra.id_fornecedor = compradores.id'],
                ['status_ocs', 'ordens_compra.id_status_ordem_compra = status_ocs.id']
            ],
            'ordens_compra.id_fornecedor = ' . $this->session->userdata('id_fornecedor')
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function exportar()
    {
        $this->db->select("
            oc.ordem_compra,
            c.cnpj,
            c.razao_social,
            oc.valor_total,
            st.descricao AS status,
            oc.condicao_pagamento AS forma_pagamento,
            DATE_FORMAT(oc.data_emissao, '%d/%m/%Y') AS data_emissao
        ");
        $this->db->from("ordens_compra oc");
        $this->db->join("compradores c", 'oc.id_fornecedor = c.id');
        $this->db->join("status_ocs st", 'oc.id_status_ordem_compra = st.id');
        $this->db->where("oc.id_fornecedor = {$this->session->userdata('id_fornecedor')}");


        $query = $this->db->get()->result_array();

        if ( count($query) < 1 ) {
            $query[] = [
                'ordem_compra' => '',
                'cnpj' => '',
                'razao_social' => '',
                'valor_total' => '',
                'status' => '',
                'forma_pagamento' => '',
                'data_emissao' => ''
            ];
        } 

        $dados_page = ['dados' => $query, 'titulo' => 'produtos'];

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

/* End of file: Vendas_realizadas.php */
