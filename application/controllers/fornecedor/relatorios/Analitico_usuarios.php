<?php

class Analitico_usuarios extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/relatorios/analitico_usuarios');
        $this->views = 'fornecedor/relatorios/analitico_usuarios';

        $this->load->model('M_relatorios', 'relatorios');
        $this->load->model('m_fornecedor', 'fornecedor');

    }

    public function index()
    {
        $this->main();
    }

    public function pesquisar()
    {
        $post = $this->input->post();

        $this->session->set_userdata('f_rel_user', $post);
        $this->main($post);

    }

    public function limparFiltros()
    {
        unset($_SESSION['f_rel_user']);

        return redirect($this->route);
    }

    private function main($post = null)
    {
        $page_title = 'Relatório Analítico Usuários';

        $data['url_detalhes'] = "{$this->route}/detalhes/";
        $data['form_action'] = "{$this->route}/pesquisar/";

        if (isset($_SESSION['f_rel_user'])){
            $post = $_SESSION['f_rel_user'];
        }

        $data['header'] = $this->template->header(['title' => $page_title ]);
        $data['navbar']  = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts();
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
                    'url' => "{$this->route}/exportar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel',
                    'label' => 'Exportar Excel'
                ]
            ]
        ]);

        $data['dados'] = $this->getData($post);

        $data['post'] = $post;



        $this->load->view("{$this->views}/main", $data);
    }

    public function getData($data)
    {
        return $this->relatorios->getAnaliticoUsuarios($data);
    }

    public function detalhes($id_usuario)
    {
        $page_title = "Detalhamento do Usuário";
        $filter = $_GET;
        $filter = array_merge($filter, ['usuario' => $id_usuario]);
        $data['post'] = $filter;
        $usuarios = $this->relatorios->getAnaliticoUsuarios($filter);
        $data['dados'] = [];

        foreach ($usuarios as $k => $usuario){
            $data['dados'][$usuario['usuario']][] =  $usuario;
        }

        $filterString = http_build_query($filter);
        if (!empty($filterString)){
            $urlExport = "{$this->route}/exportarDetails?{$filterString}";
        }else{
            $urlExport = "{$this->route}/exportarDetails";
        }

        $data['header'] = $this->template->header(['title' => $page_title ]);
        $data['navbar']  = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => $urlExport,
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel',
                    'label' => 'Exportar Excel'
                ],
                [
                    'type'  => 'a',
                    'id'    => 'btnBack',
                    'url'   => $this->route,
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ],
            ]
        ]);

        $data['url_detalhes'] = "{$this->route}/listCotacoes/";

        $this->load->view("{$this->views}/detalhes", $data);

    }

    public function listCotacoes($id_usuario){

        $data = $this->input->get();
        $data = array_merge($data, ['usuario' => $id_usuario]);
        $usuario = $this->db->where('id', $id_usuario)->get('usuarios')->row_array();

        $data['dados'] = $this->relatorios->getAnaliticoUsuariosCotacoes($data);
        $page_title = "Detalhamento das cotaçãoes | {$usuario['nome']}";

        $data['header'] = $this->template->header(['title' => $page_title ]);
        $data['navbar']  = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportarDetails",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel',
                    'label' => 'Exportar Excel'
                ],
                [
                    'type'  => 'a',
                    'id'    => 'btnBack',
                    'url'   => (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : $this->route,
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ],
            ]
        ]);


        $this->load->view("{$this->views}/list", $data);

    }

    public function exportar()
    {
        $post = $this->input->post();

        $query = $this->relatorios->getAnaliticoUsuarios($post);

        foreach ($query as $k => $row) {

            $query[$k]['estados'] = implode(',', $row['estados']);
        }

        $exportData = [];

        foreach ($query as $item){
            $exportData[] = $item;
        }


        $dados_page = ['dados' => $exportData, 'titulo' => 'Analítico Usuário'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }

    public function exportarDetails()
    {

        $filter = $_GET;

        $usuarios = $this->relatorios->getAnaliticoUsuarios($filter);
        foreach ($usuarios as $k => $usuario){
            unset($usuarios[$k]['id']);
        }


        if ( count($usuarios) < 1 ) {
            $usuarios[] = [
                'usuario' => '',
                'estado' => '',
                'qtd_cotacoes' => '',
                'qtd_itens_ofertados' => '',
                'qtd_pedidos_convertidos' => '',
                'qts_itens_covertidos' => '',
                'total_vendido' => '',
            ];
        }

        $dataIni = (isset($filter['dataini'])) ? date("d/m/Y", strtotime($filter['dataini'])) : '';
        $dataFim = (isset($filter['datafim'])) ? date("d/m/Y", strtotime($filter['datafim'])) : '';


        $dados_page = ['dados' => $usuarios, 'titulo' => "Analítico Usuários"];

        $exportar = $this->export->excel("Rel_Analitico_User_{$dataIni}_{$dataFim}.xlsx", $dados_page);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }

}