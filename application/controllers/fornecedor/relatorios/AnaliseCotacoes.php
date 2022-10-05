<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AnaliseCotacoes extends CI_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/relatorios/CompradorePortal');
        $this->views = 'fornecedor/relatorios/compradores_portal';

        $this->load->model('m_pedido', 'pedido');
        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_status_ordem_compra', 'status');
        $this->load->model('m_estados', 'estados');
    }

    public function index()
    {

        $abertas = $this->db->query("select count(distinct cd_cotacao) as abertas, DATE_FORMAT(dt_inicio_cotacao,'%m/%Y') as data
                            from cotacoes_sintese.cotacoes
                            where id_fornecedor in (12, 111, 112, 115, 120, 123, 126)
                              and dt_inicio_cotacao between '2022-01-01' and now()
                            group by DATE_FORMAT(dt_inicio_cotacao,'%m/%Y')")->result_array();

       $respondidas = $this->db->query("select count(distinct cd_cotacao) as respondidas, DATE_FORMAT(data_criacao,'%m/%Y') as data 
                                from pharmanexo.cotacoes_produtos
                                where id_fornecedor in (12, 111, 112, 115, 120, 123, 126)
                                  and data_criacao between '2022-01-01' and now()
                                group by DATE_FORMAT(data_criacao,'%m/%Y')")->result_array();

       foreach ($abertas as $k => $aberta){
           foreach ($respondidas as $respondida){
               if ($aberta['data'] == $respondida['data']){
                   $abertas[$k]['respondidas'] = $respondida['respondidas'];

                   $abertas[$k]['percent'] =  intval($respondida['respondidas']) / intval($aberta['abertas']) * 100;

               }
           }
       }

       var_dump($abertas);
       exit();


        $page_title = 'Compradores por portal';

        $data['dataTable'] = "{$this->route}/getDatasource";
        $data['options'] = $this->status->getStatus();
        $data['url_detalhes'] = "{$this->route}/detalhes/";
        $data['url_export'] = "{$this->route}/exportar";

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'buttons' => [
                [
                    'type' => 'submit',
                    'id' => 'btnSave',
                    'form' => 'formFilter',
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel',
                    'label' => 'Exportar Excel'
                ]
            ]
        ]);

        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();

        $data['heading'] = $this->template->heading([
            'page_title' => $page_title
        ]);

        $data['scripts'] = $this->template->scripts();

        $data['estados'] = $this->estados->find();
        $data['portais'] = $this->db->get('integradores')->result_array();

        $this->load->view("{$this->views}/main", $data);
    }


    public function getDatasource()
    {

        $post = $this->input->post();
        $where = '';

        /*$where = "os.id_fornecedor = {$this->session->id_fornecedor} AND ";
        if ($this->session->has_userdata('id_matriz')) {
            if ($this->session->has_userdata('id_matriz')) {
                $lojas = $this->fornecedor->find("id, nome_fantasia", "id_matriz = {$this->session->id_matriz}");
                $arrLojas = [];
                foreach ($lojas as $loja) {
                    $arrLojas[] = $loja['id'];
                }
                $forn = implode(",", $arrLojas);

                $where = "os.id_fornecedor in ({$forn}) AND ";

            }
        }*/


        if (isset($post['estados']) && $post['estados'] != "") {
            $estados = explode(",", $post['estados']);
            $estadosWhere = "";

            foreach ($estados as $estado) {
                $estadosWhere .= "'{$estado}', ";
            }

            $estadosWhere = rtrim($estadosWhere, ', ');
            $where .= "c.estado in ({$estadosWhere}) AND ";
        }

        if (isset($post['portais']) && $post['portais'] != "") {

            $where .= "i.id in ({$post['portais']}) AND ";
        }

        $where = rtrim($where, 'AND ');

        if (empty($where)) $where = NULL;


        $r = $this->datatable->exec(
            $post,
            'compradores c',
            [
                ['db' => 'c.id', 'dt' => 'id'],
                ['db' => 'c.cnpj', 'dt' => 'cnpj'],
                ['db' => 'c.nome_fantasia', 'dt' => 'nome_fantasia'],
                ['db' => 'c.razao_social', 'dt' => 'razao_social'],
                ['db' => 'c.estado', 'dt' => 'estado'],
                ['db' => 'i.desc', 'dt' => 'integrador'],
            ],
            [
                ['compradores_integrador ci', ' ci.id_cliente = c.id'],
                ['integradores i', 'i.id = ci.id_integrador'],
            ],
            $where,
            "ci.id_cliente"
        );

        # _________________________________________________________________________________________________________ #

        $where2 = NULL;
        $where2 = "id_fornecedor = {$this->session->id_fornecedor} AND ";

        if ($this->session->has_userdata('id_matriz')) {
            if ($this->session->has_userdata('id_matriz')) {
                $lojas = $this->fornecedor->find("id, nome_fantasia", "id_matriz = {$this->session->id_matriz}");
                $arrLojas = [];
                foreach ($lojas as $loja) {
                    $arrLojas[] = $loja['id'];
                }
                $forn = implode(",", $arrLojas);

                $where2 = "id_fornecedor in ({$forn}) AND ";

            }
        }

        $where2 = rtrim($where2, 'AND ');


        $compradoresDistribuidor = $this->db->distinct()->select('id_cliente')->where($where2)->get('cotacoes_produtos')->result_array();
        $idArray = [];
        if (!empty($compradoresDistribuidor)) {
            foreach ($compradoresDistribuidor as $comprador) {
                $idArray[] = $comprador['id_cliente'];
            }
        }

        foreach ($r['data'] as $k => $item) {
            if (in_array($item['id'], $idArray)) {
                $r['data'][$k]['venda'] = 1;
            }else{
                $r['data'][$k]['venda'] = 0;
            }
        }


        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function exportar()
    {

        $post = $this->input->post();

        $where = "os.id_fornecedor = {$this->session->id_fornecedor} AND ";
        if ($this->session->has_userdata('id_matriz')) {
            if ($this->session->has_userdata('id_matriz')) {
                $lojas = $this->fornecedor->find("id, nome_fantasia", "id_matriz = {$this->session->id_matriz}");
                $arrLojas = [];
                foreach ($lojas as $loja) {
                    $arrLojas[] = $loja['id'];
                }
                $forn = implode(",", $arrLojas);

                $where = "os.id_fornecedor in ({$forn}) AND ";

            }
        }

        if (isset($post['data_ini']) && isset($post['data_fim'])) {
            $dataini = dbDateFormat($post['data_ini']);
            $datafim = dbDateFormat($post['data_fim']);

            $where .= "date(os.Dt_Ordem_Compra) between '{$dataini}' and '{$datafim}' AND ";
        }


        $where = rtrim($where, 'AND ');

        $this->db->select("
           pc.codigo         AS codigo,
           pc.nome_comercial AS nome_comercial,
           pc.marca          AS marca,
           count(0)              AS qtd_produtos,
           os.id_fornecedor  AS id_fornecedor,
           sum(osp.Qt_Produto)   AS total_vendido");

        $this->db->from("ocs_sintese_produtos osp");
        $this->db->join('ocs_sintese os', 'osp.id_ordem_compra = os.id');
        $this->db->join('produtos_catalogo pc', 'os.id_fornecedor = pc.id_fornecedor and pc.codigo = osp.codigo');
        $this->db->join('fornecedores f', 'ON f.id = pc.id_fornecedor');

        $this->db->where($where);
        $this->db->group_by("pc.codigo, pc.id_fornecedor");
        $this->db->order_by("sum(osp.Qt_Produto) DESC");

        $query = $this->db->get()->result_array();


        if (count($query) < 1) {
            $query[] = [
                'codigo' => '',
                'nome_comercial' => '',
                'marca' => '',
                'qtd_produtos' => '',
                'total_vendido' => ''
            ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'Ranking de produtos vendidos'];

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

/* End of file: Vendas_realizadas.php */
