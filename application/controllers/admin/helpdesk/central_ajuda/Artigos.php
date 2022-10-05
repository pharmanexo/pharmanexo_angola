<?php

class Artigos extends MY_Controller
{

    private $views;
    private $route;

    public function __construct()
    {
        parent::__construct();

        $this->views = 'admin/helpdesk/ajuda/';
        $this->route = base_url('/admin/helpdesk/central_ajuda/artigos/');
        $this->load->model('m_helpdesk', 'help');
    }

    public function index()
    {
        $page_title = '';

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'styles' => []
        ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'button',
                    'id' => 'btnInsert',
                    'url' => "{$this->route}/insert",
                    'class' => 'btn-primary',
                    'icone' => 'fa-plus',
                    'label' => 'Criar Artigo'
                ],
            ]
        ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => []
        ]);

        $data['datasource'] = "{$this->route}/datatables";
        $data['url_update'] = "{$this->route}/update/";

        $this->load->view($this->views . 'main', $data, FALSE);
    }

    public function insert()
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();
            $slug = strtolower(str_replace(" ", "-", $post['titulo']));

            $data = [
                'id_categoria' => $post['id_categoria'],
                'titulo' => $post['titulo'],
                'slug' => $slug,
                'conteudo' => $post['conteudo'],
                'keywords' => $post['keywords'],
                'created_at' => date('Y-m-d H:i:s', time())
            ];

            $i = $this->help->insert($data);

            if ($i){
                $id = $this->help->insertId();
                redirect("{$this->route}update/{$id}");
            }else{
                $this->form();
            }

        } else {
            $this->form();
        }
    }

    public function update($id)
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();
            $slug = strtolower(str_replace(" ", "-", $post['titulo']));

            $data = [
                'id_categoria' => $post['id_categoria'],
                'titulo' => $post['titulo'],
                'slug' => $slug,
                'conteudo' => $post['conteudo'],
                'keywords' => $post['keywords'],
            ];
            

            $i = $this->help->update($id, $data);

            if ($i){
                redirect("{$this->route}update/{$id}");
            }else{
                $this->form($id);
            }

        } else {
            $this->form($id);
        }
    }

    private function form($id = null)
    {
        $form_action = "{$this->route}insert";
        $page_title = 'Novo Artigo de Ajuda';
        $data['dados'] = $this->input->post();

        if (isset($id)) {
            $form_action = "{$this->route}update/{$id}";
            $page_title = 'Editando Arquivo';
            $data['dados'] = $this->help->getById($id);
        }

        $data['formAction'] = $form_action;

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'styles' => []
        ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnBack',
                    'url' => "{$this->route}",
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ],
                [
                    'type' => 'submit',
                    'id' => 'btnSave',
                    'form' => 'frmArtigo',
                    'class' => 'btn-primary',
                    'icone' => 'fa-save',
                    'label' => 'Salvar Alterações'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                'https://cdn.tiny.cloud/1/ekz35xfghbn0g71h70z1d7dlu7huzruc9yoe65m8xfsk2yvj/tinymce/5/tinymce.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js'
            ]
        ]);

        $data['categorias'] = $this->help->getCategories();


        $this->load->view($this->views . 'form', $data, FALSE);
    }

    public function datatables()
    {
        $datatables = $this->datatable->exec(
            $this->input->post(),
            'ca_articles car',
            [
                ['db' => 'car.id', 'dt' => 'id'],
                ['db' => 'car.titulo', 'dt' => 'titulo'],
                ['db' => 'cat.nome', 'dt' => 'categoria'],
                ['db' => 'car.created_at', 'dt' => 'created_at'],
                ['db' => 'car.created_at', 'dt' => 'data_criacao', 'formatter' => function ($x) {
                    return date('d/m/Y H:i', strtotime($x));
                }],
            ],
            [
                ['ca_categorias cat', 'cat.id = car.id_categoria']
            ],
            null,
            null,
            'helpdesk'
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }


}