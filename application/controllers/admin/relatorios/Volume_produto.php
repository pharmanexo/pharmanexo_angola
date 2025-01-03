<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Volume_produto extends Admin_controller
{
    private $route, $views, $DB_SINTESE, $DB_BIONEXO;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('admin/relatorios/volume_produto');
        $this->views = "admin/relatorios/volume_produto";

        $this->DB_SINTESE = $this->load->database('sintese', TRUE);
        $this->DB_BIONEXO = $this->load->database('bionexo', TRUE);

        $this->load->model('m_fornecedor', 'fornecedores');
        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_estados', 'estados');
        $this->load->model('m_marca', 'marcas');
        $this->load->model('m_produto_marca_sintese', 'pms');
    }

    /**
     * Exibe a tela de selecioanr produtos para o relatorio de volume
     *r
     * @return view
     */
    public function index()
    {
        $page_title = 'Relatório - Volume de produto<br><small>Selecionar produtos</small>';

        $data['to_datatable'] = "{$this->route}/datatables";
        $data['url_produtos'] = "{$this->route}/getProdutos";

        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['header'] = $this->template->header(['title' => 'Volume de produto']);
        $data['scripts'] = $this->template->scripts();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'submit',
                    'id' => 'btnAdicionar',
                    'form' => 'formVolume',
                    'class' => 'btn-primary',
                    'icone' => 'fa-arrow-right',
                    'label' => 'Visualizar Volume'
                ]
            ]
        ]);

        $this->load->view("{$this->views}/main", $data); 
    }

    
    /**
     * Exibe a tela de volume de produtos
     *r
     * @return view
     */
    public function listar()
    {
        $page_title = 'Volume por produto';

        $data['datatables'] = "{$this->route}/datatables";
        $data['url_exportar'] = "{$this->route}/exportar";
        $data['url_produtos'] = "{$this->route}/getProdutos";

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'styles' => [
                THIRD_PARTY . 'theme/plugins/flatpickr/flatpickr.min.css'
        ]
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

        $data['fornecedores'] = $this->fornecedores->find("*", "sintese = 1", false, 'nome_fantasia ASC');
        $data['compradores'] = $this->compradores->find("*", null, false, 'razao_social ASC');

        $this->load->view("{$this->views}/list", $data);
    }
    
    /**
     * Obtem a lista de cotações em aberto da sintese
     *
     * @param - POST - INT ID do fornecedor
     * @param - POST - INT ID do comprador
     * @return json
     */
    public function datatables()
    {

        $datatables = $this->datatable->exec(
            $this->input->post(),
            'produtos_marca_sintese',
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'descricao', 'dt' => 'descricao', 'formatter' => function($value, $row) {

                    if ( empty($row['complemento']) ) {
                        
                        return $value;
                    } else {

                        return "{$value} <br> <small><b>Complemento: </b> {$row['complemento']}</small>";
                    }
                }],
                ['db' => 'complemento', 'dt' => 'complemento'],
                ['db' => 'id_grupo', 'dt' => 'id_grupo'],
                ['db' => 'id_produto', 'dt' => 'id_produto'],
                ['db' => 'id_sintese', 'dt' => 'id_sintese'],
                ['db' => 'id_marca', 'dt' => 'id_marca'],
                ['db' => 'marca', 'dt' => 'marca'],
                ['db' => 'grupo', 'dt' => 'grupo']
            ],
            NULL,
            "ativo = 1",
            "id_produto"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }
    
    /**
     * Cria uma arquivo excel das cotações em aberto da sintese
     *
     * @param INT ID do fornecedor
     * @param INT ID do comprador
     * @return file
     */
    public function exportar($integrador, $id_fornecedor = null, $id_cliente = null)
    {
        if ( isset($id_fornecedor) ) {

            if ( strtoupper($integrador) == 'SINTESE' ) {
                
                $this->DB_SINTESE->select("
                    cot.cd_cotacao, 
                    cot.ds_cotacao,  
                    DATE_FORMAT(cot.dt_inicio_cotacao, '%d/%m/%Y %H:%i') as dt_inicio_cotacao,
                    DATE_FORMAT(cot.dt_fim_cotacao, '%d/%m/%Y %H:%i') as dt_fim_cotacao,
                    CONCAT(c.cnpj, ' - ', c.razao_social) AS comprador");
                $this->DB_SINTESE->from("cotacoes cot");
                $this->DB_SINTESE->join('pharmanexo.compradores c', 'c.id = cot.id_cliente');
                $this->DB_SINTESE->where("cot.id_fornecedor", $id_fornecedor);
                $this->DB_SINTESE->where("cot.dt_fim_cotacao > now()");

                if ( isset($id_cliente) && !empty($id_cliente) ) {
                    
                    $this->DB_SINTESE->where("cot.id_cliente", $id_cliente);
                }
                    
                $cotacoes = $this->DB_SINTESE->get()->result_array();
            } else {

                $this->DB_BIONEXO->select("
                    cot.cd_cotacao, 
                    cot.ds_cotacao,  
                    DATE_FORMAT(cot.dt_inicio_cotacao, '%d/%m/%Y %H:%i') as dt_inicio_cotacao,
                    DATE_FORMAT(cot.dt_fim_cotacao, '%d/%m/%Y %H:%i') as dt_fim_cotacao,
                    CONCAT(c.cnpj, ' - ', c.razao_social) AS comprador");
                $this->DB_BIONEXO->from("cotacoes cot");
                $this->DB_BIONEXO->join('pharmanexo.compradores c', 'c.id = cot.id_cliente');
                $this->DB_BIONEXO->where("cot.id_fornecedor", $id_fornecedor);
                $this->DB_BIONEXO->where("cot.dt_fim_cotacao > now()");

                if ( isset($id_cliente) && !empty($id_cliente) ) {
                    
                    $this->DB_BIONEXO->where("cot.id_cliente", $id_cliente);
                }
                    
                $cotacoes = $this->DB_BIONEXO->get()->result_array();
            }
        } else {

            $cotacoes = [];
        }
       
       
        if ( count($cotacoes) < 1 ) {
            $cotacoes[] = [
                'cotacao' => '',
                'descricao' => '',
                'comprador' => '',
                'data_inicio' => '',
                'data_termino' => ''
            ];
        }

        $dados_page = ['dados' => $cotacoes, 'titulo' => 'Cotacoes'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }

    public function getProdutos()
    {

        $data = $this->pms->find("id_produto, descricao, complemento", "ativo = 1", false, "descricao ASC", 'id_produto');

        $this->output->set_content_type('application/json')->set_output(json_encode($data));   
    }
}