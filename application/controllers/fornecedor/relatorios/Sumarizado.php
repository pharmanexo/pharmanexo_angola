<?php

class Sumarizado extends MY_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/relatorios/sumarizado');
        $this->views = 'fornecedor/relatorios/sumarizado';

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

        $data['clientes'] = $this->comprador->find('id, cnpj, nome_fantasia');
        $data['estados'] = $this->estados->find();

        if ($this->session->has_userdata('id_matriz')) {

            $data['selectMatriz'] = $this->fornecedor->find("id, nome_fantasia", "id_matriz = {$this->session->id_matriz}");
        }

        $this->load->view("{$this->views}/main", $data);
    }

    public function bio()
    {

        $page_title = 'Relatório Sumarizado';

        $data['to_datatable'] = "{$this->route}/getData/0/BIONEXO";


        $data['gerentes'] = $this->relatorios->getEquipeAll(3);
        $data['consultores'] = $this->relatorios->getEquipeAll(2);
        $data['assistentes'] = $this->relatorios->getEquipeAll(1);

        $data['header'] = $this->template->header(['title' => $page_title ]);
        $data['navbar']  = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts();

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

        if (isset($post['dataini']) && !empty($post['dataini'])){
            unset($filtros['dataini']);
        }

        if (isset($post['datafim']) && !empty($post['datafim'])){
            unset($filtros['datafim']);
        }

        $filtros = array_merge($post, $filtros);

        #busca os produtos das cotações
       /* $consulta = $this->relatorios->getCotacoes($filtros, $all);
        $produtos = $consulta['data'];*/

        $consulta = $this->relatorios->getSumarizado($filtros, $all, $post['integrador']);
        $produtos = $consulta['data'];

        foreach ($produtos as $j => $produto) {

            $produto['gerente'] = isset($produto['gerente']) && !empty($produto['gerente']) ? $this->relatorios->getPessoaEquipe($produto['gerente'])['nome'] : '';

            $produto['total'] = (!is_null($produto['total'])) ? number_format($produto['total'], 2, ',', '.')  : '0,00' ;
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
