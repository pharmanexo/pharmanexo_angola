<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ordens_compra extends Admin_controller
{
    private $route, $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('admin/ordens_compra');
        $this->views = "admin/ordens_compra";

        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('Ordem_Compra', 'oc');
        $this->load->model('m_compradores', 'comprador');
    }

    public function index()
    {
        $page_title = 'Ordens de Compra';

        $data['datasource'] = "{$this->route}/datatables/";
        $data['url_update'] = "{$this->route}/detalhes/";
        $data['url_exportar'] = "{$this->route}/exportar/";

        $data['header'] = $this->template->header([ 'title' => $page_title ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar/",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel', 
                    'label' => 'Exportar Excel'
                ],
            ]
        ]);

        $data['scripts'] = $this->template->scripts();

        $data['fornecedores'] = $this->fornecedor->find("id, nome_fantasia", null, false, 'nome_fantasia ASC');
        $data['compradores'] = $this->comprador->find("id, nome_fantasia, razao_social", null, false, 'nome_fantasia ASC');

        $this->load->view("{$this->views}/main", $data);
    }

    public function detalhes($idOC)
    {
        $page_title = "Ordem de Compra";

        $data['oc'] = $this->getOC($idOC);

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'styles' => [
            ]
        ]);
        $data['navbar']  = $this->template->navbar();
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
                    'id' => 'btnExport',
                    'url' => "{$this->route}/export_details/{$idOC}",
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


        $this->load->view("{$this->views}/detail", $data);
    }

    private function getOC($idOC)
    {
        $oc = $this->oc->findById($idOC);

        if (isset($oc['Tp_Situacao'])){

            switch ($oc['Tp_Situacao']){
                case '1':  $oc['situacao'] = 'Aprovada';
                    break;
                case '3':

                    if (isset($oc['Dt_Resgate']) && !empty($oc['Dt_Resgate'])){
                        $oc['situacao'] = 'Resgatada pelo fornecedor';
                    }else if(strtotime($oc['Dt_Previsao_Entrega']) > time()){
                        $oc['situacao'] = 'Aguardando Entrega em '. date('d/m/Y', strtotime($oc['Dt_Previsao_Entrega']));
                    }else if(strtotime($oc['Dt_Previsao_Entrega']) < time()){
                        $oc['situacao'] = 'Entregue';
                    }

                    break;
                case '4':  $oc['situacao'] = 'Aprovada';
                    break;
                case '12':  $oc['situacao'] = 'Aprovada';
                    break;
            }
        }

        $oc['produtos'] = $this->oc->get_products($idOC);

        $oc['comprador'] = (isset($oc['id_comprador']) && !empty($oc['id_comprador'])) ? $this->comprador->findById($oc['id_comprador']) : 'Comprador não localizado';
        if (isset($oc['Cd_Condicao_Pagamento']) && !empty($oc['Cd_Condicao_Pagamento'])){
            $id = $oc['Cd_Condicao_Pagamento'];
            $select = $this->db->where('id', $id)->get('formas_pagamento')->row_array();

            if (!empty($select)){
                $oc['form_pagamento'] = $select['descricao'];
            }else{
                $oc['form_pagamento'] = $id;
            }

        } 

        foreach ($oc['produtos'] as $kk => $row) {

            if ( empty($row['codigo']) ) {

                $this->db->select("id_pfv AS codigo");
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

                if ( isset($item) && !empty($item) ) {

                    # Atualiza o codigo do produto
                    $this->db->where('id_ordem_compra', $row['id_ordem_compra']);
                    $this->db->where("cd_produto_comprador = '{$row['Cd_Produto_Comprador']}' ");
                    $this->db->where('Id_Produto_Sintese', $row['Id_Produto_Sintese']);
                    $this->db->where('Id_Sintese', $row['Id_Sintese']);
                    $this->db->where("codigo is null");
                    $this->db->update('ocs_sintese_produtos', ['codigo' => $item['codigo']]);

                    $oc['produtos'][$kk]['codigo'] = $item['codigo'];
                } 
            }
        }

        if (isset($oc['Telefones_Ordem_Compra']) && !empty($oc['Telefones_Ordem_Compra'])){
            $oc['Telefones_Ordem_Compra'] = json_decode($oc['Telefones_Ordem_Compra'], true);
        }


        return $oc;
    }

    /**
     * Obtem dados para os datatables de Cotação manuais
     *
     * @param = int ID do fornecedor
     * @return  json
     */
    public function datatables($id_fornecedor)
    {
        $data = $this->datatable->exec(
            $this->input->get(),
            'ocs_sintese ocs',
            [
                ['db' => 'ocs.id', 'dt' => 'id'],
                ['db' => 'ocs.id_comprador', 'dt' => 'id_cliente'],
                ['db' => 'ocs.Cd_Ordem_Compra', 'dt' => 'Cd_Ordem_Compra'],
                ['db' => 'ocs.Cd_Cotacao', 'dt' => 'Cd_Cotacao'],
                ['db' => 'ocs.pendente', 'dt' => 'pendente', 'formatter' => function($value, $row) {

                    return ($value == 1) ? 'Sim' : "Não";
                }],
                ['db' => 'ocs.Hr_Ordem_Compra', 'dt' => 'Hr_Ordem_Compra'],
                ['db' => 'ocs.Dt_Ordem_Compra', 'dt' => 'datatime', 'formatter' => function($d, $r){
                    return date('Y-m-d H:i:s', strtotime("{$d} {$r['Hr_Ordem_Compra']}"));
                }],
                ['db' => 'ocs.Dt_Ordem_Compra', 'dt' => 'Dt_Ordem_Compra', 'formatter' => function($d, $r){
                    return date('d/m/Y H:i:s', strtotime("{$d} {$r['Hr_Ordem_Compra']}"));
                }],

                ['db' => 'ocs.Dt_Previsao_Entrega', 'dt' => 'Dt_Previsao_Entrega', 'formatter' => function($d){
                    return date("d/m/Y", strtotime($d));
                }],
                ['db' => 'compradores.nome_fantasia', 'dt' => 'nome_fantasia'],
                ['db' => 'compradores.razao_social', 'dt' => 'razao_social', 'formatter' => function($value, $row) {

                    $comprador = ( !empty($row['nome_fantasia']) ) ? $row['nome_fantasia'] : $value;
                    return "<small>{$comprador}</small>";
                }],
                ['db' => '(select sum(Qt_Produto * Vl_Preco_Produto) FROM ocs_sintese_produtos WHERE id_ordem_compra = ocs.id)', 'dt' => 'valor', 'formatter' => function($d) {
                    return number_format($d, 4, ',', '.');
                }],
            ],
            [
                ['compradores', 'compradores.id = ocs.id_comprador'],
            ],
            "ocs.id_fornecedor = {$id_fornecedor}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Gera arquivo excel do datatable
     *
     * @return  downlaod file
     */
    public function exportar($id_fornecedor = null)
    {
        if ( isset($id_fornecedor) ) {

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
            $this->db->where("ocs.id_fornecedor", $id_fornecedor);
            $this->db->order_by("Dt_Ordem_Compra DESC");

            $query = $this->db->get()->result_array();
        } else {

            $query = [];
        }
       
       
        if ( count($query) < 1 ) {
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

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
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
        
        if ( count($query) < 1 ) {

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

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }
}