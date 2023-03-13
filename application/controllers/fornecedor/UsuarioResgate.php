<?php

class UsuarioResgate extends MY_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/UsuarioResgate');
        $this->views = 'fornecedor/usuario_resgate';

//        $this->load->model('m_controle_cotacoes', 'controle_cotacoes');
//        $this->load->model('m_compradores', 'compradores');
    }

    /**
     * exibe a view fornecedor/regras_vendas/controle_cotacoes/main.php
     *
     * @return view
     */
    public function index()
    {
        $page_title = "Usuários resgate";

        $data['url_datatable'] = "{$this->route}/dataTables/";
        $data['url_delete'] = "{$this->route}/delete/";
        $data['url_update'] = "{$this->route}/openModal";
        $data['url_delete_multiple'] = "{$this->route}/delete_multiple/";

        $data['header'] = $this->template->header([
            'title' => $page_title,
        ]);

        $data['navbar'] = $this->template->navbar();
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
                    'id' => 'btnDeleteMultiple',
                    'url' => "",
                    'class' => 'btn-danger',
                    'icone' => 'fa-trash',
                    'label' => 'Excluir Selecionados'
                ],
                [
                    'type' => 'a',
                    'id' => 'btnAdicionar',
                    'url' => "{$this->route}/openModal",
                    'class' => 'btn-primary',
                    'icone' => 'fa-plus',
                    'label' => 'Novo Registro'
                ]
            ]
        ]);

        $data['scripts'] = $this->template->scripts([

        ]);


        $this->load->view("{$this->views}/main", $data);
    }

    public function dataTables()
    {
        $r = $this->datatable->exec(
            $this->input->get(),
            'usuarios_resgate',
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'nome', 'dt' => 'nome'],
                ['db' => 'usuario', 'dt' => 'usuario'],
            ],
            null,
            null,            //"id_fornecedor in (12,111,112,115,120,123,126)",
            "usuario"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function openModal($id = null)
    {
        $data['form_action'] = "{$this->route}/insert";
        $data['title'] = "Cadastrar novo usuário";

        if (isset($id)) {
            $data['form_action'] = "{$this->route}/update/{$id}";
            $data['title'] = "Editar usuário";
            $data['dados'] = $this->db->where('id', $id)->get('usuarios_resgate')->row_array();
        }

        $this->load->view("{$this->views}/form", $data);

    }

    public function insert()
    {
        if ($this->input->method() == 'post') {

            $newOnco = explode(',', ONCOPROD);

            $dados = $this->input->post();
            $insert = [];

            $findUser = $this->db->where('usuario', $dados['usuario'])->get('usuarios_resgate')->row_array();

            if (empty($findUser)) {
                foreach ($newOnco as $onco) {
                    $dados['id_fornecedor'] = $onco;
                    $insert[] = $dados;
                }
                $gravado = $this->db->insert_batch('usuarios_resgate', $insert);

                if (!$gravado) {
                    $output = ['type' => 'success', 'message' => notify_create];
                } else {
                    $output = ['type' => 'warning', 'message' => notify_failed];
                }
            } else {
                $output = ['type' => 'error', 'message' => 'Usuário já existe'];
            }


            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function update($id)
    {
        if ($this->input->method() == 'post') {

            $dados = $this->input->post();

            $findUser = $this->db->where('id', $id)->get('usuarios_resgate')->row_array();

            if (!is_null($findUser)) {
                unset($dados['id']);

                $updateUser = $this->db->where('usuario', $findUser['usuario'])->update('usuarios_resgate', $dados);

                if ($updateUser) {
                    $output = ['type' => 'success', 'message' => notify_update];
                } else {
                    $output = ['type' => 'warning', 'message' => notify_failed];
                }
            } else {
                $output = ['type' => 'error', 'message' => 'Usuario não encontrado'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function delete_multiple()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $this->db->trans_begin();

            if (!isset($post['el'])) {
                $newdata = [
                    'type' => 'warning',
                    'message' => 'Nenhum usuario selecionado'
                ];

                $this->output->set_content_type('application/json')->set_output(json_encode($newdata));
                return;
            }
            $i = 0;
            foreach ($post['el'] as $item) {

                $this->db->where('nome', $item['nome']);
                $this->db->where('usuario', $item['usuario']);
                $this->db->delete('usuarios_resgate');

                $i++;
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();

                $newdata = [
                    'type' => 'warning',
                    'message' => 'Erro ao excluir usuario'
                ];
            } else {
                $this->db->trans_commit();

                $newdata = ['type' => 'success', 'message' => 'Usuario(s) excluido(s) com sucesso'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($newdata));
        }
    }
}
