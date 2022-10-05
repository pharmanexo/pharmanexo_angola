<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cotacoes_abertas extends Admin_controller
{
    private $route, $views, $DB_COTACAO;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('admin/relatorios/cotacoes_abertas');
        $this->views = "admin/relatorios/cotacoes_abertas";

        $this->DB_COTACAO = $this->load->database('sintese', TRUE);

        $this->load->model('m_fornecedor', 'fornecedores');
        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_estados', 'estados');
        $this->load->model('m_encontrados_sintese', 'DEPARA');
        $this->load->model('m_marca', 'marcas');
    }

    public function index()
    {
        $page_title = 'Cotações em aberto';

        $data['datatables'] = "{$this->route}/datatables";
        $data['url_exportar'] = "{$this->route}/exportar";

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

        $data['fornecedores'] = $this->fornecedores->find("*", "sintese = 1", false, 'nome_fantasia ASC');
        $data['compradores'] = $this->compradores->find("*", null, false, 'razao_social ASC');

        $this->load->view("{$this->views}/main", $data);
    }
   
    public function datatables()
    {

        if ( $this->input->is_ajax_request() ) {
            
            $post = $this->input->post();

            $this->DB_COTACAO->select("cot.cd_cotacao, cot.ds_cotacao, 
                cot.dt_inicio_cotacao, cot.dt_fim_cotacao, CONCAT(c.cnpj, ' - ', c.razao_social) AS comprador");
            $this->DB_COTACAO->from("cotacoes cot");
            $this->DB_COTACAO->join('pharmanexo.compradores c', 'c.id = cot.id_cliente');
            $this->DB_COTACAO->where("cot.id_fornecedor", $post['id_fornecedor']);
            $this->DB_COTACAO->where("cot.dt_fim_cotacao > now()");

            if ( isset($post['id_cliente']) && !empty($post['id_cliente']) ) {
                
                $this->DB_COTACAO->where("cot.id_cliente", $post['id_cliente']);
            }
                
            $cotacoes = $this->DB_COTACAO->get()->result_array();

            foreach ($cotacoes as $kk => $row) {
                
                $cotacoes[$kk]['dataini'] = date("d/m/Y H:i", strtotime($row['dt_inicio_cotacao']));
                $cotacoes[$kk]['datafim'] = date("d/m/Y H:i", strtotime($row['dt_fim_cotacao']));
            }
           
            $this->output->set_content_type('application/json')->set_output(json_encode($cotacoes));
        }
    }
   
    public function exportar($id_fornecedor = null, $id_cliente = null)
    {
        if ( isset($id_fornecedor) ) {

            $this->DB_COTACAO->select("
                cot.cd_cotacao AS cotacao, 
                cot.ds_cotacao AS descricao, 
                cot.dt_inicio_cotacao AS data_inicio, 
                cot.dt_fim_cotacao AS data_termino, 
                CONCAT(c.cnpj, ' - ', c.razao_social) AS comprador");
            $this->DB_COTACAO->from("cotacoes cot");
            $this->DB_COTACAO->join('pharmanexo.compradores c', 'c.id = cot.id_cliente');
            $this->DB_COTACAO->where("cot.dt_fim_cotacao > now()");
            $this->DB_COTACAO->where("cot.id_fornecedor", $id_fornecedor);
            $this->DB_COTACAO->where("cot.dt_fim_cotacao > now() ");

            if ( isset($id_cliente) && !empty($id_cliente) ) {
                
                $this->DB_COTACAO->where("cot.id_cliente", $id_cliente);
            }

            $query = $this->DB_COTACAO->get()->result_array();
        } else {

            $query = [];
        }
       
       
        if ( count($query) < 1 ) {
            $query[] = [
                'cotacao' => '',
                'descricao' => '',
                'comprador' => '',
                'data_inicio' => '',
                'data_termino' => ''
            ];
        } else {

            foreach ($query as $kk => $row) {
                
                $cotacoes[$kk]['data_inicio'] = date("d/m/Y H:i", strtotime($row['data_inicio']));
                $cotacoes[$kk]['data_termino'] = date("d/m/Y H:i", strtotime($row['data_termino']));
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