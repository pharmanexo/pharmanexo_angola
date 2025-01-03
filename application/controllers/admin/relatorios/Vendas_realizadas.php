<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Vendas_realizadas extends Admin_controller
{
    private $route, $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('admin/relatorios/vendas_realizadas');
        $this->views = "admin/relatorios/vendas_realizadas";

        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_compradores', 'compradores');
    }

    public function index()
    {
        $page_title = 'Relatório de compras realizadas';

        $data['dataTable'] = "{$this->route}/datatables/";
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
                    'url' => "{$this->route}/exportar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel', 
                    'label' => 'Exportar Excel'
                ],
            ]
        ]);
        $data['scripts'] = $this->template->scripts();

        $data['fornecedores'] = $this->fornecedor->find("id, nome_fantasia", null, false, 'nome_fantasia ASC');

        $this->load->view("{$this->views}/main", $data);
    }

    public function datatables($id_fornecedor)
    {

        $data = $this->datatable->exec(
            $this->input->post(),
            'ocs_sintese_produtos oc_prod',
            [
                ['db' => 'oc_prod.Ds_Produto_Comprador', 'dt' => 'produto'],
                ['db' => 'oc_prod.Ds_marca', 'dt' => 'marca'],
                ['db' => 'oc_prod.Ds_Unidade_Compra', 'dt' => 'unidade'],
                ['db' => 'SUM(oc_prod.Qt_Produto)', 'dt' => 'qtd_total'],
                ['db' => 'SUM(oc_prod.Vl_Preco_Produto)', 'dt' => 'valor_total', 'formatter' => function ($value, $row) {

                    return number_format($value, 4, ",", ".");
                }]
            ],
            [
                ['pharmanexo.ocs_sintese oc', 'oc.id = oc_prod.id_ordem_compra']
            ],
            "oc.id_fornecedor = {$id_fornecedor}
            AND oc.pendente = 0
            AND oc_prod.Id_Produto_Sintese is not null
            AND oc_prod.Cd_Produto_Comprador is not null",
            "oc_prod.Ds_Produto_Comprador, oc_prod.Ds_Marca, oc_prod.Ds_Unidade_Compra"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
   
    public function exportar($id_fornecedor = null)
    {
        if ( isset($id_fornecedor) ) {
            $this->db->select("
                oc_prod.Ds_Produto_Comprador AS produto,
                oc_prod.Ds_Marca AS marca,
                oc_prod.Ds_Unidade_Compra AS unidade,
                SUM(oc_prod.Qt_Produto) AS qtde_total_solicitada,
                SUM(oc_prod.Vl_Preco_Produto) AS valor_total");
            $this->db->from("ocs_sintese_produtos oc_prod");
            $this->db->join("ocs_sintese oc", "oc.id = oc_prod.id_ordem_compra");
            $this->db->where("id_fornecedor", $id_fornecedor);
            $this->db->where("pendente", 0);
            $this->db->where("oc_prod.Id_Produto_Sintese is not null");
            $this->db->where("oc_prod.Cd_Produto_Comprador is not null");
            $this->db->group_by("oc_prod.Ds_Produto_Comprador, oc_prod.Ds_Marca, oc_prod.Ds_Unidade_Compra");
            $this->db->order_by("oc_prod.Ds_Produto_Comprador ASC");

            $query = $this->db->get()->result_array();
        } else {

            $query = [];
        }
       
       
        if ( count($query) < 1 ) {
            $query[] = [
                'produto' => '',
                'marca' => '',
                'unidade' => '',
                'qtde_total_solicitada' => '',
                'valor_total' => ''
            ];
        } else {

            foreach ($query as $kk => $row) {
                
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
}