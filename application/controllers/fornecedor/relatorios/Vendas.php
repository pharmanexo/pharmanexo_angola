<?php

class Vendas extends MY_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/relatorios/vendas');
        $this->views = 'fornecedor/relatorios/vendas';

        $this->load->model('M_relatorios', 'relatorios');
        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_compradores', 'comprador');
        $this->load->model('m_estados', 'estados');
    }


    public function index()
    {
        $page_title = 'Demonstrativo de Vendas';

        $post = [];

        if ($this->input->method() == 'post')
        {
            $post = $this->input->post();


            if (empty($post['dataini'])) {
                $post = [
                    'dataini' => date('Y-m-01', time()),
                    'datafim' => date('Y-m-d', time()),
                ];
            }

            if (!isset($post['id_fornecedor']))
            {
                $post['id_fornecedor'] = $this->session->id_fornecedor;
            }else{
                $post['id_fornecedor'] = explode(',', $post['id_fornecedor']);
            }

        }

        $data['to_datatable'] = "{$this->route}";
        $data['urlExport'] = "{$this->route}/export";
        $data = array_merge($data, $this->getData($post));

        $data['filtros'] = $post;


        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                'https://cdn.jsdelivr.net/npm/apexcharts'
            ]
        ]);
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



        if ($this->session->has_userdata('id_matriz')) {

            $data['selectMatriz'] = $this->fornecedor->find("id, nome_fantasia", "id_matriz = {$this->session->id_matriz}");

            foreach ($data['selectMatriz'] as $k => $item) {
                if (!empty($data['filtros']['id_fornecedor'])) {
                    foreach ($data['filtros']['id_fornecedor'] as $forn) {
                        if ($item['id'] == $forn) {
                            $data['selectMatriz'][$k]['select'] = true;
                        }
                    }
                }

            }

        }


        $this->load->view("{$this->views}/main", $data);
    }

    public function export(){

        $post = $this->input->post();

        $consulta = $this->getData($post)['cotacoes'];

        $dados_page = ['dados' => $consulta, 'titulo' => 'Recebidas X Respondidas'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);
    }

    public function getData($e = null)
    {

        $post = $e;
        $filtros = [
            'dataini' => date('Y-m-01', time()),
            'datafim' => date('Y-m-d', time()),
        ];

        $filtros = empty($post) ? $filtros : $post;

        if (empty($filtros['id_fornecedor'])) {
            $filtros['id_fornecedor'] = $this->session->id_fornecedor;
        }

        $data['periodo'] = $this->relatorios->getVendasPeriodo($filtros);
        $data['dia'] = $this->relatorios->getVendasDay($filtros);
        $data['ano'] = $this->relatorios->getVendasYear($filtros);
        $data['ranking'] = $this->relatorios->getRankingVendas($filtros);

        $fornecedores = null;
        if (!empty($filtros['id_fornecedor'])){
            if (is_array($filtros['id_fornecedor'])){
                foreach ($filtros['id_fornecedor'] as $forn){
                    $fornecedores[] = $forn;
                }

                $fornecedores = implode(',', $fornecedores);
            }else{
                $fornecedores = $filtros['id_fornecedor'];
            }
        }

        $abertas = $this->db->query("select  DATE_FORMAT(dt_inicio_cotacao,'%m/%Y') as data, count(distinct cd_cotacao) as abertas
                            from cotacoes_sintese.cotacoes
                            where id_fornecedor in ({$fornecedores})
                              and dt_inicio_cotacao between '{$filtros['dataini']}' and '{$filtros['datafim']}'
                            group by DATE_FORMAT(dt_inicio_cotacao,'%m/%Y')")->result_array();

        $respondidas = $this->db->query("select  DATE_FORMAT(data_criacao,'%m/%Y') as data, count(distinct cd_cotacao) as respondidas 
                                from pharmanexo.cotacoes_produtos
                                where id_fornecedor in ($fornecedores)
                                  and data_criacao between '{$filtros['dataini']}' and '{$filtros['datafim']}'
                                group by DATE_FORMAT(data_criacao,'%m/%Y')")->result_array();

        foreach ($abertas as $k => $aberta){
            foreach ($respondidas as $respondida){
                if ($aberta['data'] == $respondida['data']){
                    $abertas[$k]['respondidas'] = $respondida['respondidas'];

                    $abertas[$k]['percent'] =  intval($respondida['respondidas']) / intval($aberta['abertas']) * 100;

                }
            }
        }

        $data['cotacoes'] = $abertas;

        return $data;

    }

}
