<?php

class Sem_vinculo extends MY_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/relatorios/sem_vinculo');
        $this->views = 'fornecedor/relatorios/sem_vinculo';

        $this->load->model('M_relatorios', 'relatorios');
        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_compradores', 'comprador');
        $this->load->model('m_estados', 'estados');
    }


    public function index()
    {
        $page_title = 'Relatório Sumarizado';

        $data['to_datatable'] = "{$this->route}/getData";


        $data['gerentes'] = $this->relatorios->getEquipeAll(3);
        $data['consultores'] = $this->relatorios->getEquipeAll(2);
        $data['assistentes'] = $this->relatorios->getEquipeAll(1);

        $data['header'] = $this->template->header(['title' => $page_title ]);
        $data['navbar']  = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts();
        $data['heading'] = $this->template->heading(['page_title' => $page_title, 
        'buttons' => [
            [
                'type' => 'a',
                'id' => 'btnVoltar',
               'url' => "javascript:history.back(1)",
                'class' => 'btn-secondary',
                'icone' => 'fa-arrow-left',
                'label' => 'Retornar'
            ]]]);


        $data['clientes'] = $this->comprador->find('id, cnpj, nome_fantasia');
        $data['estados'] = $this->estados->find();

        if ($this->session->has_userdata('id_matriz')) {

            $data['selectMatriz'] = $this->fornecedor->find("id, nome_fantasia", "id_matriz = {$this->session->id_matriz}");
        }

        $this->load->view("{$this->views}/main", $data);
    }


    public function getData($e = null)
    {

        $all = ($e == 1) ? TRUE :  FALSE;
        $export = [];

        $post = $this->input->post();
        $filtros = [
            'id_fornecedor' => $this->session->id_fornecedor,
            'dataini' => date('Y-m-d', strtotime('-5 days')),
            'datafim' => date('Y-m-d', time()),
        ];

        $filtros = array_merge($post, $filtros);

        #busca os produtos das cotações
       /* $consulta = $this->relatorios->getCotacoes($filtros, $all);
        $produtos = $consulta['data'];*/

        $consulta = $this->relatorios->getSemVinculo($filtros, $all);
        $produtos = $consulta['data'];


        foreach ($produtos as $j => $produto) {
            if (empty($produto['nome_fantasia'])){
                $produto['nome_fantasia'] = $produto['razao_social'];
            }

            $produto['dt_inicio_cotacao'] = date("d/m/Y", strtotime($produto['dt_inicio_cotacao']));
            $export[] = $produto;
        }


        if ($e == 1){
            $dados_page = ['dados' => $produtos, 'titulo' => 'Relatório Gerencial'];
            $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        }else{
            $output = [
                "recordsTotal" => $consulta['totalRecords'],
                "recordsFiltered" => $consulta['totalFiltered'],
                "data" => $export
            ];

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }



    }

}
