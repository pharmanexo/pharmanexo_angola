<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cotacoes_periodo extends Admin_controller
{
    private $route, $views, $DB_COTACAO;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('admin/relatorios/cotacoes_periodo');
        $this->views = "admin/relatorios/cotacoes_periodo";

        $this->DB_COTACAO = $this->load->database('sintese', TRUE);

        $this->load->model('m_fornecedor', 'fornecedores');
        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_cotacoes_produtos', 'cotacoes_produtos');
    }

    public function index()
    {
        $page_title = 'Cotações respondidas por periodo';

        $data['datatables'] = "{$this->route}/datatables";
        $data['url_exportar'] = "{$this->route}/exportar";
        $data['url_cotacoes'] = "{$this->route}/listarCotacoes/";

        $dataatual = date('Y-m-d');

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
                    'url' => "{$this->route}/exportar/{$dataatual}/{$dataatual}",
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

        $data['fornecedores'] = $this->fornecedores->find("*", "sintese = 1", false, 'nome_fantasia ASC');

        $this->load->view("{$this->views}/main", $data);
    }

    public function listarCotacoes($dataini, $datafim, $id_fornecedor)
    {
        $fornecedor = $this->fornecedores->findById($id_fornecedor);

        if ( $dataini != $datafim ) {

            $periodo = "no periodo " . date('d/m/Y', strtotime($dataini)) . " - " . date('d/m/Y', strtotime($datafim));
        } else {

            $periodo = "do dia " . date('d/m/Y', strtotime($dataini));
        }

        $page_title = "Cotações {$periodo} <br> <b>Fornecedor: </b> {$fornecedor['cnpj']} - {$fornecedor['nome_fantasia']}";

        $data['url_cotacao'] = "{$this->route}/cotacao/{$id_fornecedor}/";
        $data['datatables'] = "{$this->route}/getCotacoesPeriodo/{$dataini}/{$datafim}/{$id_fornecedor}";

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
            ]
        ]);
        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/main_cotacoes", $data);
    }

    public function cotacao($id_fornecedor, $cd_cotacao)
    {
        
        $data = [];

        # Obtem a cotação
        $data['cotacao'] = $this->DB_COTACAO->where('cd_cotacao', $cd_cotacao)->where('id_fornecedor', $id_fornecedor)->get('cotacoes')->row_array();

        //Obtem o cliente(comprador)
        $data['comprador'] = $this->compradores->findById($data['cotacao']['id_cliente']);

        # Valor total respondido
        $this->db->select("SUM(preco_marca * qtd_solicitada) AS valor_total");
        $this->db->where('cd_cotacao', $cd_cotacao);
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('submetido', 1);
        $data['valor_total_produtos'] = $this->db->get('cotacoes_produtos')->row_array()['valor_total'];

        
        # total de itens respondidos
        $this->db->where('cd_cotacao', $cd_cotacao);
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('submetido', 1);
        $data['total_itens'] = $this->db->count_all_results('cotacoes_produtos');

        $page_title = "Produtos da cotação #{$cd_cotacao}";

        $data['dataTable'] = "{$this->route}/getCotacao/{$cd_cotacao}/{$id_fornecedor}";

        $data['header'] = $this->template->header([ 'title' => $page_title ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnBack',
                    'url' => $_SERVER['HTTP_REFERER'],
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ],
            ]
        ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
            ]
        ]);

        $this->load->view("{$this->views}/main_cotacao", $data);
    }
   
    public function datatables()
    {
        if ( $this->input->is_ajax_request() ) {

            $post = $this->input->post();

            $dataini = ( !empty($post['dataini']) ) ? $post['dataini'] : date('Y-m-d');
            $datafim = ( !empty($post['datafim']) ) ? $post['datafim'] : date('Y-m-d');

            $method = ( isset($post['id_fornecedor']) && !empty($post['id_fornecedor']) ) ? 'row_array' : 'result_array';

            $this->db->select("f.nome_fantasia AS fornecedor, f.id, COUNT(DISTINCT cp.cd_cotacao) AS qtd_total, SUM(cp.preco_marca * cp.qtd_solicitada) AS valor_total");
            $this->db->from("cotacoes_produtos cp");
            $this->db->join("fornecedores f", "f.id = cp.id_fornecedor");
            $this->db->where("DATE(cp.data_cotacao) BETWEEN '{$dataini}' AND '{$datafim}' ");
            $this->db->where("cp.submetido", 1);

            if ( isset($post['nivel']) && !empty($post['nivel']) ) {

                $this->db->where("cp.nivel", $post['nivel']);
            }

            if ( isset($post['id_fornecedor']) && !empty($post['id_fornecedor']) ) {
                
                $this->db->where("cp.id_fornecedor", $post['id_fornecedor']);
            } else {

                $this->db->group_by("cp.id_fornecedor");
            }

            $this->db->order_by("f.nome_fantasia ASC");


            $data = $this->db->get()->$method();

            $data = array_format($data);

            foreach ($data as $kk => $row) {
               
                $data[$kk]['valor_total'] = number_format($row['valor_total'], 4, ',', '.');
            }

           $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function getCotacoesPeriodo($dataini, $datafim, $id_fornecedor)
    {
        
        $data = $this->datatable->exec(
            $this->input->get(),
            'cotacoes_produtos cp',
            [
                ['db' => 'cp.id', 'dt' => 'id'],
                ['db' => 'cp.cd_cotacao', 'dt' => 'cd_cotacao'],
                ['db' => 'cot.ds_cotacao', 'dt' => 'ds_cotacao', 'formatter' => function ($value, $row) {

                    return "<small>{$value}</small>";
                }],
                ['db' => 'cot.uf_cotacao', 'dt' => 'uf_cotacao'],
                ['db' => 'c.cnpj', 'dt' => 'cnpj'],
                ['db' => 'c.razao_social', 'dt' => 'comprador', 'formatter' => function ($value, $row) {

                    return "<small>{$row['cnpj']} - {$value}</small>";
                }],
                [
                    'db' => "(SELECT COUNT(0) FROM cotacoes_produtos cp2 WHERE cp2.cd_cotacao = cp.cd_cotacao 
                        AND cp2.id_fornecedor = cp.id_fornecedor AND cp2.submetido = 1)", 
                    'dt' => 'total_itens_respondidos'
                ],
                [
                    'db' => "(SELECT SUM(cp2.preco_marca * cp2.qtd_solicitada) FROM cotacoes_produtos cp2 WHERE cp2.cd_cotacao = cp.cd_cotacao 
                        AND cp2.id_fornecedor = cp.id_fornecedor AND cp2.submetido = 1)", 
                    'dt' => 'valor_total_respondido', 
                    'formatter' => function ($value, $row) {

                        return number_format($value, 4, ",", ".");
                    }
                ],
            ],
            [
                ['cotacoes_sintese.cotacoes cot', "cp.cd_cotacao = cot.cd_cotacao AND cp.id_fornecedor = cot.id_fornecedor"],
                ['compradores c', "c.id = cp.id_cliente", 'left'],
            ],
            "cp.id_fornecedor = {$id_fornecedor} AND 
            cp.submetido = 1 AND 
            DATE(cp.data_cotacao) BETWEEN '{$dataini}' AND '{$datafim}'",
            "cd_cotacao"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function getCotacao($cd_cotacao, $id_fornecedor)
    {
        $data = $this->datatable->exec(
            $this->input->post(),
            'cotacoes_produtos cp',
            [
                ['db' => 'cp.id_pfv', 'dt' => 'id_pfv'],
                ['db' => 'cp.produto', 'dt' => 'produto'],
                ['db' => 'cp.qtd_solicitada', 'dt' => 'qtd_solicitada'],
                ['db' => 'cp.qtd_embalagem', 'dt' => 'qtd_embalagem'],
                ['db' => 'pc.marca', 'dt' => 'marca'],
                ['db' => 'cp.preco_marca', 'dt' => 'preco_marca', 'formatter' => function ($value, $row) {

                    return number_format($value, 4, ",", ".");
                }],
            ],
            [
                ['produtos_catalogo pc', 'pc.codigo = cp.id_pfv AND pc.id_fornecedor = cp.id_fornecedor'],
            ],
            "cp.submetido = 1 AND 
            cp.id_fornecedor = {$id_fornecedor} AND cp.cd_cotacao = '{$cd_cotacao}'"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function exportar($dataini, $datafim, $id_fornecedor = null, $nivel = null)
    {

        $dataini = ( !empty($dataini) ) ? $dataini : date('Y-m-d');
        $datafim = ( !empty($datafim) ) ? $datafim : date('Y-m-d');

        $method = ( isset($id_fornecedor) && !empty($id_fornecedor) ) ? 'row_array' : 'result_array';
        
        $this->db->select("f.nome_fantasia AS fornecedor, COUNT(DISTINCT cp.cd_cotacao) AS qtd_total, SUM(cp.preco_marca * cp.qtd_solicitada) AS valor_total");
        $this->db->from("cotacoes_produtos cp");
        $this->db->join("fornecedores f", "f.id = cp.id_fornecedor");
        $this->db->where("DATE(cp.data_cotacao) BETWEEN '{$dataini}' AND '{$datafim}' ");
        $this->db->where("cp.submetido", 1);

        if ( isset($nivel) && !empty($nivel) ) {

            $this->db->where("cp.nivel", $nivel);
        }

        if ( isset($id_fornecedor) && !empty($id_fornecedor) ) {
            
            $this->db->where("cp.id_fornecedor", $id_fornecedor);
        } else {

            $this->db->group_by("cp.id_fornecedor");
        }

        $this->db->order_by("f.nome_fantasia ASC");

        $query = $this->db->get()->$method();
       
        if ( count($query) < 1 ) {
            $query[] = [
                'fornecedor' => '',
                'total_cotacoes' => '',
                'valor_total' => ''
            ];
        } else {

            $query = array_format($query);

            foreach ($query as $kk => $row) {
                
                $query[$kk]['valor_total'] = number_format($row['valor_total'], 4, ',', '.');
            }
        }

        $dados_page = ['dados' => $query, 'titulo' => 'cotacoes'];

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