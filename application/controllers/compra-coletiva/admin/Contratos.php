<?php

class Contratos extends ADM_Controller
{

	private $route;

	public function __construct()
	{
		parent::__construct();
		$this->route = base_url('admin/notifications');
		$this->load->model('comprador');
	}

	public function index()
	{

		$data['page_title'] = "Contratos";

		$data['header'] = $this->template->header();
		$data['navbar'] = $this->template->navbar();
		$data['heading'] = $this->template->heading([
			'title' => 'PAINEL ADMINISTRATIVO'
		]);
		$data['scripts'] = $this->template->scripts();
		$data['footer'] = $this->template->footer();

		$this->load->view('admin/notificar', $data);

	}

	public function detalhes($id)
	{


	}



}
