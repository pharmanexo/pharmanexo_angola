<?php

class Compradores_incompletos extends ADM_Controller
{

	private $route;

	public function __construct()
	{
		parent::__construct();
		$this->route = base_url('admin/compradores_incompletos');
		$this->load->model('comprador');
	}

	public function index()
	{

		$data['page_title'] = "Compradores Incompletos";

		$data['compradores'] = $this->comprador->find("id, cnpj, empresa, telefone, celular, email, data_cadastro", "completo = 0");
		foreach ($data['compradores'] as $k => $i) {
			$data['compradores'][$k]['url'] = "{$this->route}/detalhes/{$i['id']}";
		}

		$data['header'] = $this->template->header();
		$data['navbar'] = $this->template->navbar();
		$data['heading'] = $this->template->heading([
			'title' => 'PAINEL ADMINISTRATIVO'
		]);
		$data['scripts'] = $this->template->scripts();
		$data['footer'] = $this->template->footer();

		$this->load->view('admin/compradores_incompletos', $data);

	}


}
