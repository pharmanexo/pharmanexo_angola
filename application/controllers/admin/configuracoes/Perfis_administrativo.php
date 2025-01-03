<?php

class Perfis_administrativo extends Admin_controller
{
    private $route, $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('admin/configuracoes/perfis_administrativo');
        $this->views = "admin/configuracoes/perfis_administrativo";
    }

    public function index()
    {
        $page_title = "Perfis Administrativo";

        $data['datatable'] = "{$this->route}/datatables";
        $data['url_update'] = "{$this->route}/atualizar";
        $data['url_delete_multiple'] = "{$this->route}/delete_multiple";

        $data['header'] = $this->template->header([ 'title' => 'Perfis' ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([ 
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'button',
                    'id' => 'btnDeleteMultiple',
                    'url' => "",
                    'class' => 'btn-danger',
                    'icone' => 'fa-trash',
                    'label' => 'Excluir Selecionados'
                ],
                [
                    'type' => 'a',
                    'id' => 'btnAdicionar',
                    'url' => "{$this->route}/criar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-plus',
                    'label' => 'Novo Registro'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/main", $data);
    }

    public function criar()
    {

        $page_title = "Novo Perfil";

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
                        'form' => "formPerfil",
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

        $rotas = $this->db->where('grupo', 0)->order_by('id_parente ASC, posicao ASC')->get('rotas')->result_array();
            
        $data['form_action'] = "{$this->route}/save";
        $data['url_return'] = "{$this->route}/index";

        $data['rotas'] = [];

        foreach ($rotas as $k => $rota){

            if (!empty($rota['id_parente'])) {

                $data['rotas'][$rota['id_parente']]['subrotas'][] = $rota;
            } else {

                $data['rotas'][$rota['id']] = $rota;
            }
        }

        $this->load->view("{$this->views}/form", $data);
    }

    public function atualizar($id)
    {
        $page_title = "Atualizar Perfil";

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
                        'form' => "formPerfil",
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

        $rotas = $this->db->where('grupo', 0)->order_by('id_parente ASC, posicao ASC')->get('rotas')->result_array();
            
        $data['form_action'] = "{$this->route}/update/{$id}";
        $data['url_return'] = "{$this->route}/index";

        $data['rotas'] = [];

        $data['perfil'] = $this->db->where('id', $id)->get('perfis')->row_array();

        $rotas_perfil = explode(',', $data['perfil']['id_rotas']);

        foreach ($rotas as $k => $rota) {

            foreach ($rotas_perfil as $id_rota) {

                if ($rota['id'] == $id_rota) {
                    $rota['checked'] = true;
                    break;
                }
            }

            if (!empty($rota['id_parente'])) {

                $data['rotas'][$rota['id_parente']]['subrotas'][] = $rota;
            } else {

                $data['rotas'][$rota['id']] = $rota;
            }
        }

        $this->load->view("{$this->views}/form", $data);
    }

    public function datatables()
    {
        $datatables = $this->datatable->exec(
            $this->input->post(),
            'perfis',
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'titulo', 'dt' => 'titulo'],
                ['db' => 'id_rotas', 'dt' => 'id_rotas', 'formatter' => function($value, $row) {

                    $array = array();

                    foreach (explode(',', $value) as $id_rota) {

                        $rota = $this->db->where('id', $id_rota)->get('rotas')->row_array()['rotulo'];

                        $array[] = "<span class='badge badge-primary mt-1'>{$rota}</span>";
                    }

                    return implode($array, ' ');
                }],
                ['db' => 'data_criacao', 'dt' => 'data_criacao', 'formatter' => function($value, $row) {
                    return date("d/m/Y H:i", strtotime($value));
                }]
            ]
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    public function save()
    {
        if ( $this->input->is_ajax_request() ) {
            
            $post = $this->input->post();

            $post['id_rotas'] = implode(',', $post['rotas']);
            unset($post['rotas']);

            # Verifica se já existe registro para o modulo selecionado
            if ( $this->db->where('titulo', $post['titulo'])->count_all_results('perfis') < 1 ) {

                if ( $this->db->insert('perfis', $post) ) {
                    
                    $output = ['type' => 'success', 'message' => notify_create];
                } else {

                    $output = ['type' => 'warning', 'message' => notify_failed];
                }
            } else {

                $output = ['type' => 'warning', 'message' => 'Este perfil já possui registro.'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function update($id)
    {
        if ( $this->input->is_ajax_request() ) {
            
            $post = $this->input->post();

            $post['id_rotas'] = implode(',', $post['rotas']);
            unset($post['rotas']);

            $updt = $this->db->where('id', $id)->update('perfis', $post);

            if ( $updt ) {
                    
                $output = ['type' => 'success', 'message' => notify_update];
            } else {

                $output = ['type' => 'warning', 'message' => notify_failed];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function delete_multiple()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $this->db->trans_begin();

            foreach ($post['el'] as $id) {
               
                $this->db->where('id', $id)->delete('perfis');
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();

                $output = ['type' => 'warning', 'message' => 'Erro ao excluir'];
            } else {
                $this->db->trans_commit();

                $output = ['type' => 'success', 'message' => 'Excluidos com sucesso'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }
}
