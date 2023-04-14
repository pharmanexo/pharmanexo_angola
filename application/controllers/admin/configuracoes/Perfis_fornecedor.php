<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Perfis_fornecedor extends Admin_controller
{
    private $route;
    private $views;
    private $total;
    private $permitidos;
    private $liberacao;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('/admin/configuracoes/perfis_fornecedor');
        $this->views = 'admin/configuracoes/perfis_fornecedor';

        $this->load->model('grupo_usuario_rota', 'grupo_usuario_rota');
        $this->load->model('rota', 'rota');
    }

    /**
     * View para selecionar fornecedor
     *
     * @return view
     */
    public function index()
    {
        $page_title = "Perfis de Fornecedor - <small> Selecione um fornecedor</small>";

        $data['datasource'] = "{$this->route}/datatables";
        $data['url_update'] = "{$this->route}/atualizar/";
        $data['header'] = $this->template->header([ 'title' => $page_title ]);
        $data['navbar']  = $this->template->navbar();
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
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Formulário para alterar grupo de rotas do fornecedor
     *
     * @param - int id do fornecedor
     * @return  view
     */
    public function atualizar($id)
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $update = $this->grupo_usuario_rota->updateAdmin($post, $id);


            if ($update) {

                $output = ['type' => 'success', 'message' => notify_update ];
            } else {

                $output = ['type' => 'warning', 'message' => notify_failed ];
            }

            $this->session->set_userdata('warning', $output);

            redirect($this->route);
        } else {
            $page_title = "Grupos Usuários";

            $data = [
                'header' => $this->template->header([ 'title' => $page_title ]),
                'navbar' => $this->template->navbar(),
                'sidebar' => $this->template->sidebar(),
                'heading' => $this->template->heading([
                    'page_title' => $page_title,
                    'buttons' => [
                         [
                            'type'  => 'a',
                            'id'    => 'btnBack',
                            'url'   => "{$this->route}",
                            'class' => 'btn-secondary',
                            'icone' => 'fa-arrow-left',
                            'label' => 'Voltar'
                        ],
                        [
                            'type' => 'submit',
                            'id' => 'btnNovo',
                            'form' => "formUpdateUserdata",
                            'class' => 'btn-primary',
                            'icone' => 'fa-check',
                            'label' => 'Salvar Registros'
                        ]
                    ]
                ]),
                'scripts' => $this->template->scripts([
                    'scripts' => [
                        THIRD_PARTY . 'plugins/jquery.form.min.js',
                        THIRD_PARTY . 'plugins/jquery-validation-1.19.1/dist/jquery.validate.min.js'
                    ]
                ])   
            ];
            $data['form_action'] = "{$this->route}/atualizar/{$id}";

            $data['rotas'] = [];

            $rotas_adm = $this->grupo_usuario_rota->get_routes_fornecedor($id, 1);
            $rotas_com = $this->grupo_usuario_rota->get_routes_fornecedor($id, 2);
            $rotas_fin = $this->grupo_usuario_rota->get_routes_fornecedor($id, 3);
            $rotas_dist = $this->grupo_usuario_rota->get_routes_fornecedor($id, 4);

            $rotas = $this->rota->find($fields = '*', "grupo = 1", FALSE);


            foreach ($rotas as $k => $rota){
                foreach ($rotas_dist as $item){
                    if ($rota['id'] == $item['id']){
                        $rota['checked'] = true;
                        break;
                    }
                }

                if (!empty($rota['id_parente'])){
                    $data['rotas']['dist'][$rota['id_parente']]['subrotas'][] = $rota;
                }else{
                    $data['rotas']['dist'][$rota['id']] = $rota;
                }
            }


            foreach ($rotas as $k => $rota){
                foreach ($rotas_adm as $item){
                    if ($rota['id'] == $item['id']){
                        $rota['checked'] = true;
                        break;
                    }
                }

                if (!empty($rota['id_parente'])){
                    $data['rotas']['adm'][$rota['id_parente']]['subrotas'][] = $rota;
                }else{
                    $data['rotas']['adm'][$rota['id']] = $rota;
                }
            }

            foreach ($rotas as $k => $rota){
                foreach ($rotas_fin as $item){
                    if ($rota['id'] == $item['id']){
                        $rota['checked'] = true;
                        break;
                    }
                }

                if (!empty($rota['id_parente'])){
                    $data['rotas']['fin'][$rota['id_parente']]['subrotas'][] = $rota;
                }else{
                    $data['rotas']['fin'][$rota['id']] = $rota;
                }
            }

            foreach ($rotas as $k => $rota){
                foreach ($rotas_com as $item){
                    if ($rota['id'] == $item['id']){
                        $rota['checked'] = true;
                        break;
                    }
                }

                if (!empty($rota['id_parente'])){
                    $data['rotas']['com'][$rota['id_parente']]['subrotas'][] = $rota;
                }else{
                    $data['rotas']['com'][$rota['id']] = $rota;
                }
            }

            $this->load->view("{$this->views}/form", $data);
        }
    }

    /**
     * Exibe a lista de fornecedores
     *
     * @return  json
     */
    public function datatables()
    {
        $datatables = $this->datatable->exec(
            $this->input->post(),
            'fornecedores',
            [
                ['db' => 'fornecedores.id', 'dt' => 'id'],
                ['db' => 'fornecedores.razao_social', 'dt' => 'razao_social'],
                ['db' => 'fornecedores.nome_fantasia', 'dt' => 'nome_fantasia'],
                ['db' => 'fornecedores.cnpj', 'dt' => 'cnpj'],
                ['db' => 'fornecedores.email', 'dt' => 'email']
            ],
            null
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    public function _form($id)
    {
        $data = [];
        if (isset($id)) {
            $data['form_action'] = "{$this->route}/atualizar/{$id}";
            $data['url_delete'] = "{$this->route}/delete/{$id}";
            $data['url_resetPass'] = "{$this->route}/reset_password/{$id}";
            $data['usuario'] = $this->usuario->findById($id);
        }

        $this->load->view("{$this->views}/form", $data);
    }

    /**
     * Gera arquivo excel do datatable
     *
     * @return  downlaod file
     */
    public function exportar()
    {
        $this->db->select("razao_social, cnpj, email");
        $this->db->from("fornecedores");
        $this->db->order_by("razao_social ASC");

        $query = $this->db->get()->result_array();

        if ( count($query) < 1 ) {
            $query[] = [
                'razao_social' => '',
                'cnpj' => '',
                'email' => ''
            ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'fornecedores'];

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