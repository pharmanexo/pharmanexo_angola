<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Grupos_usuarios extends MY_Controller
{
    private $route;
    private $views;
    private $total;
    private $permitidos;
    private $liberacao;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('/fornecedor/grupos_usuarios/');
        $this->views = 'fornecedor/grupos_usuarios/';

        $this->load->model('grupo_usuario_rota', 'usuario');
        $this->load->model('rota', 'rota');
    }

    public function index()
    {
        $page_title = "Grupos Usuários";

        $data = [
            'header' => $this->template->header([ 'title' => $page_title ]),
            'navbar' => $this->template->navbar(),
            'sidebar' => $this->template->sidebar(),
            'heading' => $this->template->heading([
                'page_title' => $page_title,
                'buttons' => [
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
                ]
            )
        ];
        $data['datatable_src'] = "{$this->route}to_datatable";
        $data['url_update'] = "{$this->route}atualizar";

        $rotas = $this->rota->find("*", "grupo = 1");
        $data['rotas'] = [];

        $rotas_com = $this->usuario->get_routes_fornecedor($this->session->id_fornecedor, 2);
        $rotas_fin = $this->usuario->get_routes_fornecedor($this->session->id_fornecedor, 3);

        $rotas = $this->rota->find($fields = '*', "grupo = 1", FALSE);

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

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Atualiza as rotas do grupo de usuario
     *
     * @return  view
     */
    public function atualizar()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $update = $this->usuario->atualizar($post);

            if ($update) {
                $output = [
                    'type' => 'success',
                    'message' => 'Usuário atualizado com sucesso'
                ];
            } else {
                $output = [
                    'type' => 'warning',
                    'message' => 'Erro ao atualizar usuário'
                ];
            }

            $this->session->set_userdata('warning', $output);

            redirect($this->route);


        } else {
            $this->index();
        }
    }

    public function delete($id)
    {
        if ($this->usuario->delete($id)) {
            $output = [
                'type' => 'success',
                'message' => 'Usuário cadastrado com sucesso'
            ];
        } else {
            $output = [
                'type' => 'warning',
                'message' => 'Erro ao excluir o usuário'
            ];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    public function _form($id = null)
    {
        $data = [];
        $data['form_action'] = "{$this->route}insert";
        if (isset($id)) {
            $data['form_action'] = "{$this->route}atualizar/{$id}";
            $data['url_delete'] = "{$this->route}delete/{$id}";
            $data['url_resetPass'] = "{$this->route}reset_password/{$id}";
            $data['usuario'] = $this->usuario->findById($id);
        }

        $this->load->view("{$this->views}form", $data);
    }


}

/* End of file: Configuracao.php */
