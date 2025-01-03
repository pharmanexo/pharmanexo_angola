<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Vendas_sumarizado extends Admin_controller
{
    private $route, $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('admin/relatorios/vendas_sumarizado');
        $this->views = "admin/relatorios/vendas_sumarizada";

        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_compradores', 'compradores');
    }

    public function index()
    {
        $page_title = 'Relatório de vendas realizadas';

        $data['dataTable'] = "{$this->route}/datatables/";
        $data['url_exportar'] = "{$this->route}/exportar/";

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
                ],
            ]
        ]);
        $data['scripts'] = $this->template->scripts();

        $data['fornecedores'] = $this->fornecedor->find("id, nome_fantasia", null, false, 'nome_fantasia ASC');
        $data['matrizes'] = $this->db->get('fornecedores_matriz')->result_array();


        $data['vendas'] = $this->getData();

        $this->load->view("{$this->views}/main", $data);
    }

    private function getData()
    {
        $this->db->select("month(Dt_Ordem_Compra) as mes, count(os.id) as qtd, year(Dt_Ordem_Compra) as ano,
       sum(osp.Qt_Produto * osp.Vl_Preco_Produto)                 as total,
       f.nome_fantasia");

        $this->db->from("ocs_sintese os");
        $this->db->join("ocs_sintese_produtos osp", "os.id = osp.id_ordem_compra");
        $this->db->join("fornecedores f", "f.id = os.id_fornecedor");
       // $this->db->where("year (Dt_Ordem_Compra) = '2021'");
        $this->db->group_by("month (Dt_Ordem_Compra), year (Dt_Ordem_Compra), id_fornecedor");
        $this->db->order_by("month (Dt_Ordem_Compra), year (Dt_Ordem_Compra)");

        $data = $this->db->get()->result_array();
        $dataF = [];
        foreach ($data as $k => $item) {
            $item['name_mes'] = nameMonth($item['mes']);
            $dataF[$item['ano']][$item['name_mes']][] = $item;
        }


        return $dataF;

    }

    public function exportar($id_fornecedor = null)
    {
        if (isset($id_fornecedor)) {
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


        if (count($query) < 1) {
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

        if ($exportar['status'] == false) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }
}
