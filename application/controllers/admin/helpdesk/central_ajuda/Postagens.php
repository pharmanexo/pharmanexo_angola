<?php

class Postagens extends Ajuda_controller
{

    private $views;
    private $route;

    public function __construct()
    {
        parent::__construct();

        $this->views = 'admin/helpdesk/postagens/';
        $this->route = base_url('/admin/helpdesk/central_ajuda/postagens/');
        $this->load->model('m_helpdesk', 'help');
    }

    public function index()
    {
        $page_title = 'Guia Pharmanexo';

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'styles' => []
        ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
        ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => []
        ]);

        $data['url_update'] = "{$this->route}/read/";

        $data['posts'] = $this->help->find("h.id, h.titulo, h.id_categoria, h.created_at, h.updated_at, cat.nome");

        $menu = [];
        foreach ($data['posts'] as $post) {

            $menu[$post['nome']][] = $post['titulo'];
        }

        $data['menu'] = $menu;

        $this->load->view($this->views . 'main', $data, FALSE);
    }

    public function read($id)
    {
        $post = $this->help->getById($id);

        $page_title = "Guia: " . $post['titulo'];

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
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [],
        ]);

        $data['post'] = $post;

        $this->load->view($this->views . 'read', $data, FALSE);
    }


}