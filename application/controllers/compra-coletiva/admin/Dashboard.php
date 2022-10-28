<?php
class Dashboard extends ADM_Controller {

	private $route;
	public function __construct()
	{
		parent::__construct();
		$this->route = base_url('admin/dashboard');
	}

	public function index(){

		$data['page_title'] = "Dashboard";

		$compradores = $this->db->query('select cnpj, endereco_empresa from compradores where completo = 1')->result_array();

		$estados = [];
		$countEst = [];
		foreach ($compradores as $comprador){
			$end = json_decode($comprador['endereco_empresa'], true);
			$estados[$end['estado']][] = $comprador['cnpj'];
		}

		foreach ($estados as $k => $estado){
			$countEst[] = [
				 $k,
				count($estados[$k])
			];
		}

		$data['estados'] = json_encode($countEst);
		$data['pendentes'] = $this->db->query('select * from compradores where situacao = 0 order by data_cadastro asc')->result_array();
		$data['incompletos'] = $this->db->query('select * from compradores where situacao = 1 and completo = 0 order by data_cadastro asc')->result_array();
		foreach ($data['pendentes'] as $k => $ped){
			$data['pendentes'][$k]['url'] = base_url("admin/compradores/aprovar/{$ped['id']}");
		}
		$data['header'] = $this->template->header();
		$data['navbar'] = $this->template->navbar();
		$data['heading'] = $this->template->heading([
			'title' => 'PAINEL ADMINISTRATIVO'
		]);
		$data['scripts'] = $this->template->scripts();
		$data['footer'] = $this->template->footer();

		$this->load->view('admin/home', $data);

	}


}
