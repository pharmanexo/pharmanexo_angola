<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produtos_cotacao extends MY_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/relatorios/produtos_cotacao');
        $this->views = 'fornecedor/relatorios/produtos_cotacao';
    }

    public function index()
    {
        $page_title = 'Relatório de cotações respondidas com restrições';

        $data['url_details'] = "{$this->route}/details";
        $data['cotacoes'] = $this->datatable_cotacoes();

        $data['header'] = $this->template->header(['title' => $page_title ]);
        $data['navbar']  = $this->template->navbar();
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
                    'url' => "{$this->route}/exportar_cotacoes",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel', 
                    'label' => 'Exportar Excel'
                ],
            ]
        ]);
        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/main", $data);
    }

    public function details($cd_cotacao)
    {
        $page_title = "Produtos com restrições da cotação #{$cd_cotacao}";

        $data['to_datatable_se'] = "{$this->route}/datatables/{$cd_cotacao}/1";
        $data['to_datatable_ol'] = "{$this->route}/datatables/{$cd_cotacao}/2";
        $data['to_datatable_res'] = "{$this->route}/datatables/{$cd_cotacao}/3";

        $data['header'] = $this->template->header(['title' => $page_title ]);
        $data['navbar']  = $this->template->navbar();
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
                    'url' => "{$this->route}/exportar_itens/{$cd_cotacao}",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel', 
                    'label' => 'Exportar Excel'
                ],
            ]
        ]);
        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/details", $data);
    }

    public function datatable_cotacoes()
    {
        $this->db->select("cd_cotacao, DATE_FORMAT(data_criacao, '%d/%m/%Y %H:%i') AS data, DATE_FORMAT(data_criacao, '%Y/%m/%d %H:%i') AS data_criacao");
        $this->db->from('restricoes_produtos_cotacoes');
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);
        $this->db->or_where('id_fornecedor_logado', $this->session->id_fornecedor);
        $this->db->group_by('cd_cotacao');

        return $this->db->get()->result_array();
    }

    public function datatables($cd_cotacao, $type)
    {
        # $type == 1 - sem estoque, 2 - ol, 3 - restrição

        if ($type == 1) { 

            $r = $this->datatable->exec(
                $this->input->post(),
                'restricoes_produtos_cotacoes RPC',
                [
                    ['db' => 'RPC.cd_cotacao', 'dt' => 'cd_cotacao'],
                    ['db' => 'RPC.estoque', 'dt' => 'estoque'],
                    ['db' => 'RPC.preco_marca', 'dt' => 'preco_marca', 'formatter' => function($value, $row) {
                        if ( isset($value) ) {
                            return number_format($value, 4, ',', '.');
                        }

                        return '';
                    }],

                    ['db' => 'RPC.id_marca', 'dt' => 'id_marca', 'formatter' => function($value, $row) {

                        if ( isset($value) ) {

                            $marca = $this->db->select('marca')->where('id', $value)->get('marcas')->row_array();
                            return ( isset($marca['marca']) && !empty($marca['marca'])) ? $marca['marca'] : '';
                        }

                        return '';
                    }],
          
                    ['db' => 'CP.ds_produto_comprador', 'dt' => 'ds_produto_comprador'],
                    ['db' => 'RPC.data_criacao', 'dt' => 'data'],
                    ['db' => 'RPC.data_criacao', 'dt' => 'data_criacao', 'formatter' => function($value, $row) {

                        return date('d/m/Y H:i', strtotime($value));
                    }],
                ],
                [
                    [
                        'cotacoes_sintese.cotacoes_produtos CP', 
                            'RPC.id_fornecedor = CP.id_fornecedor AND 
                            RPC.cd_cotacao = CP.cd_cotacao AND
                            RPC.id_produto_sintese = CP.id_produto_sintese AND
                            RPC.cd_produto_comprador = CP.cd_produto_comprador'
                    ],
                ],
                "RPC.cd_cotacao = '{$cd_cotacao}' 
                AND CP.cd_produto_comprador != 0 
                AND CP.id_produto_sintese is not null 
                AND RPC.sem_estoque = 1 
                AND RPC.id_fornecedor = {$this->session->id_fornecedor}"
            );
        } elseif( $type == 2) {

            $r = $this->datatable->exec(
                $this->input->post(),
                'restricoes_produtos_cotacoes RPC',
                [
                    ['db' => 'RPC.cd_cotacao', 'dt' => 'cd_cotacao'],
                    ['db' => 'RPC.estoque', 'dt' => 'estoque'],
                    ['db' => 'RPC.preco_marca', 'dt' => 'preco_marca', 'formatter' => function($value, $row) {
                        if ( isset($value) ) {
                            return number_format($value, 4, ',', '.');
                        }

                        return '';
                    }],

                    ['db' => 'RPC.id_marca', 'dt' => 'id_marca', 'formatter' => function($value, $row) {

                        if ( isset($value) ) {

                            $marca = $this->db->select('marca')->where('id', $value)->get('marcas')->row_array();
                            return ( isset($marca['marca']) && !empty($marca['marca'])) ? $marca['marca'] : '';
                        }

                        return '';
                    }],
          
                    ['db' => 'CP.ds_produto_comprador', 'dt' => 'ds_produto_comprador'],
                    ['db' => 'RPC.data_criacao', 'dt' => 'data'],
                    ['db' => 'RPC.data_criacao', 'dt' => 'data_criacao', 'formatter' => function($value, $row) {

                        return date('d/m/Y H:i', strtotime($value));
                    }],
                ],
                [
                    [
                        'cotacoes_sintese.cotacoes_produtos CP', 
                            'RPC.id_fornecedor = CP.id_fornecedor AND 
                            RPC.cd_cotacao = CP.cd_cotacao AND
                            RPC.id_produto_sintese = CP.id_produto_sintese AND
                            RPC.cd_produto_comprador = CP.cd_produto_comprador'
                    ],
                ],
                "RPC.cd_cotacao = '{$cd_cotacao}' 
                AND CP.cd_produto_comprador != 0 
                AND CP.id_produto_sintese is not null 
                AND RPC.ol = 1 
                AND RPC.id_fornecedor = {$this->session->id_fornecedor}"
            );
        } else {

            if ($type == 3) {

                $r = $this->datatable->exec(
                    $this->input->post(),
                    'restricoes_produtos_cotacoes RPC',
                    [
                        ['db' => 'RPC.estoque', 'dt' => 'estoque'],
                        ['db' => 'CP.ds_produto_comprador', 'dt' => 'ds_produto_comprador'],
                    ],
                    [
                        [
                            'cotacoes_sintese.cotacoes_produtos CP', 
                                'RPC.id_fornecedor_logado = CP.id_fornecedor AND 
                                RPC.cd_cotacao = CP.cd_cotacao AND
                                RPC.id_produto_sintese = CP.id_produto_sintese AND
                                RPC.cd_produto_comprador = CP.cd_produto_comprador'
                        ],
                    ],
                    "RPC.cd_cotacao = '{$cd_cotacao}' 
                    AND CP.cd_produto_comprador != 0 
                    AND CP.id_produto_sintese is not null 
                    AND RPC.restricao = 1 
                    AND RPC.id_fornecedor_logado = {$this->session->id_fornecedor}"
                );
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function exportar_cotacoes()
    {
        
        $this->db->select("cd_cotacao, DATE_FORMAT(data_criacao, '%d/%m/%Y %H:%i') AS data, DATE_FORMAT(data_criacao, '%Y/%m/%d %H:%i') AS data_criacao");
        $this->db->from('restricoes_produtos_cotacoes');
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);
        $this->db->or_where('id_fornecedor_logado', $this->session->id_fornecedor);
        $this->db->group_by('cd_cotacao');

        $query = $this->db->get()->result_array();

        if ( count($query) < 1 ) {
            $query[] = [
                'cotacao' => '',
                'registrado_em' => ''
            ];
        } else {

            foreach ($query as $kk => $row) {
                
                $query[$kk]['registrado_em'] = date('d/m/Y H:i', strtotime($row['data_criacao']));
                unset($query[$kk]['data_criacao']);
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

    public function exportar_itens($cd_cotacao)
    {
        
        $this->db->select("
            CP.ds_produto_comprador AS produto,
            RPC.preco_marca,
            RPC.id_marca,
            RPC.estoque");
        $this->db->from("restricoes_produtos_cotacoes RPC");
        $this->db->join("cotacoes_sintese.cotacoes_produtos CP", 
            'RPC.id_fornecedor = CP.id_fornecedor AND 
            RPC.cd_cotacao = CP.cd_cotacao AND
            RPC.id_produto_sintese = CP.id_produto_sintese AND
            RPC.cd_produto_comprador = CP.cd_produto_comprador');
        $this->db->where("RPC.id_fornecedor", $this->session->id_fornecedor);
        $this->db->where("RPC.sem_estoque", 1);
        $this->db->where("RPC.cd_cotacao", $cd_cotacao);
        $this->db->order_by('CP.ds_produto_comprador', 'ASC');

        $query_semestoque = $this->db->get()->result_array();

        $this->db->select("
            CP.ds_produto_comprador AS produto,
            RPC.estoque");
        $this->db->from("restricoes_produtos_cotacoes RPC");
        $this->db->join("cotacoes_sintese.cotacoes_produtos CP", 
            'RPC.id_fornecedor_logado = CP.id_fornecedor AND 
            RPC.cd_cotacao = CP.cd_cotacao AND
            RPC.id_produto_sintese = CP.id_produto_sintese AND
            RPC.cd_produto_comprador = CP.cd_produto_comprador');
        $this->db->where("RPC.id_fornecedor_logado", $this->session->id_fornecedor);
        $this->db->where("RPC.restricao", 1);
        $this->db->where("RPC.cd_cotacao", $cd_cotacao);
        $this->db->order_by('RPC.data_criacao', 'DESC');

        $query_restricao = $this->db->get()->result_array();

        $this->db->select("
            CP.ds_produto_comprador AS produto,
            RPC.preco_marca,
            RPC.id_marca,
            RPC.estoque");
        $this->db->from("restricoes_produtos_cotacoes RPC");
        $this->db->join("cotacoes_sintese.cotacoes_produtos CP", 
            'RPC.id_fornecedor = CP.id_fornecedor AND 
            RPC.cd_cotacao = CP.cd_cotacao AND
            RPC.id_produto_sintese = CP.id_produto_sintese AND
            RPC.cd_produto_comprador = CP.cd_produto_comprador');
        $this->db->where("RPC.id_fornecedor", $this->session->id_fornecedor);
        $this->db->where("RPC.ol", 1);
        $this->db->where("RPC.cd_cotacao", $cd_cotacao);
        $this->db->order_by('CP.ds_produto_comprador', 'ASC');

        $query_ol = $this->db->get()->result_array();

        if ( count($query_semestoque) < 1 ) {
            $query_semestoque[] = [
                'produto' => '',
                'marca' => '',
                'preco' => '',
                'estoque' => '',
            ];
        } else {

            foreach ($query_semestoque as $kk => $row) {
               
                $marca = $this->db->select('marca')->where('id', $row['id_marca'])->get('marcas')->row_array();
                $query_semestoque[$kk]['marca'] = $marca['marca'];
            }            
        }

        if ( count($query_ol) < 1 ) {
            $query_ol[] = [
                'produto' => '',
                'marca' => '',
                'preco' => '',
                'estoque' => '',
            ];
        } else {

            foreach ($query_ol as $kk => $row) {
               
                $marca = $this->db->select('marca')->where('id', $row['id_marca'])->get('marcas')->row_array();
                $query_ol[$kk]['marca'] = $marca['marca'];
            }   
        }

        if ( count($query_restricao) < 1 ) {
            $query_restricao[] = [
                'produto' => '',
                'estoque' => ''
            ];
        } 

        $dados_page1 = ['dados' => $query_semestoque, 'titulo' => 'produtos_sem_estoque'];
        $dados_page2 = ['dados' => $query_restricao, 'titulo' => 'produtos_restricao'];
        $dados_page3 = ['dados' => $query_ol, 'titulo' => 'produtos_ol'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page1, $dados_page2, $dados_page3);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }
}
