<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends MY_Controller {

    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/seguranca/usuarios');
        $this->views = 'fornecedor/seguranca/usuarios';
    }

    public function index()
	{
		$this->main();
	}

	public function insert(){
        if ($this->input->method() == 'post'){

        }else{

        }
    }

	private function main(){

        $page_title = "Usuários do Sistema";

        $data['dataTable'] = "{$this->route}/getDatasource";
        $data['url_update'] = "{$this->route}/update/";

        $data['header'] = $this->template->header([
            'title' => $page_title
        ]);

        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();

        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnAdicionar',
                    'url' => "{$this->route}/insert",
                    'class' => 'btn-primary',
                    'icone' => 'fa-plus',
                    'label' => 'Novo Registro'
                ]
            ]
        ]);

        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/main", $data);
    }

    private function form(){
        $data = [
            'title' => '',
            'form_action' => ''
        ];

        $this->load->view("{$this->views}/form", $data);
    }

    public function getDatasource()
    {
        $r = $this->datatable->exec(
            $this->input->get(),
            'vw_usuarios',
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'nome', 'dt' => 'nome'],
                ['db' => 'email', 'dt' => 'email'],
                ['db' => 'ativo', 'dt' => 'ativo'],
            ],
            null,
            'id_fornecedor = ' . $this->session->userdata('id_fornecedor')
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }
}

/* End of file Controllername.php */