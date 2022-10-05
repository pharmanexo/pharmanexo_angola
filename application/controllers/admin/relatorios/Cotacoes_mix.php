<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cotacoes_mix extends Admin_controller
{
    private $route, $views;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('admin/relatorios/cotacoes_mix');
        $this->views = "admin/relatorios/cotacoes_mix";
        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_compradores', 'compradores');
    }

    /**
     * Exibe a view admin/cotacoes_mix/main.php
     *
     * @return  view
     */
    public function index()
    {
        $page_title = 'Relatório de Cotações Mix';

        $data['dataTable'] = "{$this->route}/datatables/";
        $data['url_detalhes'] = "{$this->route}/detalhes/";
        $data['url_exportar'] = "{$this->route}/exportar/";

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'styles' => [THIRD_PARTY . 'theme/plugins/flatpickr/flatpickr.min.css']
        ]);
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
                ],
            ]
        ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'theme/plugins/flatpickr/flatpickr.min.js',
                'https://npmcdn.com/flatpickr/dist/l10n/pt.js'
            ]
        ]);

        $data['fornecedores'] = $this->fornecedor->find("id, nome_fantasia", null, false, 'nome_fantasia ASC');

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Exibe a view admin/cotacoes_mix/detail.php
     *
     * @param   string  codigo da cotacao
     * @param   int  id do fornecedor
     * @return  view
     */
    public function detalhes($id_fornecedor, $cd_cotacao)
    {
        $data = [];

        //Obtem a cotação
        $this->db->where('cd_cotacao', $cd_cotacao);
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('nivel', 3);
        $data['cotacao'] = $this->db->get('vw_cotacoes', '1')->row_array();

        //Obtem o cliente(comprador)
        $data['comprador'] = $this->compradores->find('*', ['cnpj' => $data['cotacao']['cnpj_comprador']], true);

        // Obtem Valor total de todos os produtos
        $valor_total = $this->db
            ->select("SUM( (preco_marca * qtd_solicitada) ) AS valor_total")
            ->where('id_fornecedor', $id_fornecedor)
            ->where('cd_cotacao', $cd_cotacao)
            ->where('nivel', 3)
            ->get('cotacoes_produtos')
            ->row_array();

        $data['valor_total_produtos'] = $valor_total['valor_total'];
        
        // Obtem a qnt de produtos de cada cotação
        $this->db->where('cd_cotacao', $cd_cotacao);
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('nivel', 3);
        $data['total_itens'] = $this->db->count_all_results('cotacoes_produtos');

        $page_title = "Produtos da cotação {$cd_cotacao}";

        $data['dataTable'] = "{$this->route}/datatables_detalhes/{$cd_cotacao}/{$id_fornecedor}";

        $data['header'] = $this->template->header([ 'title' => $page_title ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnBack',
                    'url' => $this->route,
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar_detalhes/{$cd_cotacao}/{$id_fornecedor}",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel', 
                    'label' => 'Exportar Excel'
                ],
            ]
        ]);
        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/detail", $data);
    }

    /**
     * Obtem dados para os datatables de Cotação manuais
     *
     * @param = int ID do fornecedor
     * @return  json
     */
    public function datatables($id_fornecedor)
    {

        if ( $id_fornecedor == 'oncoprod' ) {

            $where = "id_fornecedor in (" . ONCOPROD . ")";
        } elseif ($id_fornecedor == 'oncoexo') {

            $where = "id_fornecedor in (" . ONCOEXO . ")";
        } else {

            $where = "id_fornecedor = {$id_fornecedor}";
        }

        $data = $this->datatable->exec(
            $this->input->post(),
            'vw_cotacoes',
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'id_cotacao', 'dt' => 'id_cotacao'],
                ['db' => 'cd_cotacao', 'dt' => 'cd_cotacao'],
                ['db' => 'data_cotacao', 'dt' => 'data_cotacao', 'formatter' => function ($d) {
                    return date('d/m/Y H:i:s', strtotime($d));
                }],
                ['db' => 'razao_social', 'dt' => 'razao_social', 'formatter' => function($value, $row) {
                    return "<small>{$value}</small>";
                }],
                ['db' => 'cnpj_comprador', 'dt' => 'cnpj_comprador'],
                ['db' => 'submetido', 'dt' => 'submetido'],
                ['db' => 'uf_comprador', 'dt' => 'uf_comprador'],
                ['db' => 'id_fornecedor', 'dt' => 'id_fornecedor'],
                ['db' => 'total_itens', 'dt' => 'total_itens'],
                ['db' => 'valor_total', 'dt' => 'valor_total', 'formatter' => function ($d) {

                    return number_format($d, 4, ",", ".");
                }]
            ],
            null,
            "nivel = 3 AND submetido = 1 AND {$where}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
      
    /**
     * Obtem dados dos produtos da cotação
     *
     * @param = String codigo da cotação
     * @param = int ID do fornecedor
     * @return  json
     */
    public function datatables_detalhes($cd_cotacao, $id_fornecedor)
    {
    
       $data = $this->datatable->exec(
            $this->input->post(),
            'cotacoes_produtos cp',
            [
                ['db' => 'cp.id', 'dt' => 'id'],
                ['db' => 'cp.produto', 'dt' => 'produto'],
                ['db' => 'cp.preco_marca', 'dt' => 'preco_marca'],
                ['db' => 'pc.marca', 'dt' => 'marca'],
                ['db' => 'cp.qtd_solicitada', 'dt' => 'qtd_solicitada'],
                ['db' => 'cp.submetido', 'dt' => 'submetido'],
            ],
            [
                ['produtos_catalogo pc', 'pc.codigo = cp.id_pfv AND pc.id_fornecedor = cp.id_fornecedor']
            ],
            "cp.cd_cotacao = '{$cd_cotacao}' AND cp.id_fornecedor = {$id_fornecedor} AND cp.nivel = 3 and cp.submetido = 1"
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

            if ( $id_fornecedor == 'oncoprod' ) {

                $where = "id_fornecedor in (" . ONCOPROD . ")";
            } elseif ($id_fornecedor == 'oncoexo') {

                $where = "id_fornecedor in (" . ONCOEXO . ")";
            } else {

                $where = "id_fornecedor = {$id_fornecedor}";
            }

            $this->db->select("
                cd_cotacao AS cotacao,
                data_cotacao,
                cnpj_comprador,
                razao_social AS fornecedor,
                uf_comprador,
                total_itens,
                valor_total");
            $this->db->from("vw_cotacoes");
            $this->db->where("nivel", 3);
            $this->db->where($where);
            $this->db->where("submetido", 1);
            $this->db->order_by("cotacao ASC");

            $query = $this->db->get()->result_array();
        } else {

            $query = [];
        }
       
       
        if ( count($query) < 1 ) {
            $query[] = [
                'cotacao' => '',
                'data_cotacao' => '',
                'cnpj_comprador' => '',
                'fornecedor' => '',
                'uf_comprador' => '',
                'total_itens' => '',
                'valor_total' => ''
            ];
        } else {

            foreach ($query as $kk => $row) {
                
                $query[$kk]['data_cotacao'] = date("d/m/Y", strtotime($row['data_cotacao']));
                $query[$kk]['valor_total'] = number_format($row['valor_total'], 4, ',', '.');
            }
        }

        $dados_page = ['dados' => $query, 'titulo' => 'Cotacoes'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }

    /**
     * Gera arquivo excel do datatable de detalhes
     *
     * @param String da cotação
     * @param int ID do fornecedor
     * @return  downlaod file
     */
    public function exportar_detalhes($cd_cotacao, $id_fornecedor)
    {
        $this->db->select("
            cp.produto,
            pc.marca,
            FORMAT(cp.preco_marca, 4, 'de_DE') AS preco_marca,
            cp.qtd_solicitada AS quantidade
        ");
        $this->db->from("cotacoes_produtos cp");
        $this->db->join("produtos_catalogo pc", "pc.codigo = cp.id_pfv AND pc.id_fornecedor = cp.id_fornecedor");
        $this->db->where("cp.cd_cotacao", $cd_cotacao);
        $this->db->where("cp.id_fornecedor", $id_fornecedor);
        $this->db->where("cp.nivel", 3);
        $this->db->where("cp.submetido", 1);
        $this->db->order_by("cp.produto asc");

        $query = $this->db->get()->result_array();
       
        if ( count($query) < 1 ) {
            $query[] = [
                'produto' => '',
                'marca' => '',
                'preco_marca' => '',
                'quantidade' => ''
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
}