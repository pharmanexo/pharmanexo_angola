<?php

class Compradores extends ADM_Controller
{

	private $route;

	public function __construct()
	{
		parent::__construct();
		$this->route = base_url('admin/compradores');
		$this->load->model('comprador');
	}

	public function index()
	{

		$data['page_title'] = "Compradores";

		$data['compradores'] = $this->comprador->find("id, cnpj, empresa, telefone, celular, email, data_cadastro", "completo = 1");
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

		$this->load->view('admin/compradores', $data);

	}

	public function detalhes($id)
	{
		$data['dados'] = $this->comprador->findById($id);
		$cnpj = soNumero($data['dados']['cnpj']);
		if (isset($data['dados']['endereco_comercial']) && !empty($data['dados']['endereco_comercial'])){
			$data['dados']['endereco_comercial'] = json_decode($data['dados']['endereco_comercial'], true);
		}

		if (isset($data['dados']['endereco_empresa']) && !empty($data['dados']['endereco_empresa'])){
			$data['dados']['endereco_empresa'] = json_decode($data['dados']['endereco_empresa'], true);
		}

		if (isset($data['dados']['url_alvara']) && !empty($data['dados']['url_alvara'])){
			$data['dados']['url_alvara'] = base_url("uploads/{$cnpj}/{$data['dados']['url_alvara']}");
		}

		if (isset($data['dados']['url_cnpj']) && !empty($data['dados']['url_cnpj'])){
			$data['dados']['url_cnpj'] = base_url("uploads/{$cnpj}/{$data['dados']['url_cnpj']}");
		}

		/*$contrato = "contratos/{$cnpj}.pdf";

		if (file_exists($contrato)){
			$data['dados']['contrato'] = base_url($contrato);
		}else{
			var_dump($contrato); exit();
		}*/

		$data['contratos'] = $this->db->where('cnpj', $data['dados']['cnpj'])->get('contratos')->result_array();

		foreach ($data['contratos'] as $k => $contrato){
			$data['contratos'][$k]['tipo'] = tipoContrato($contrato['tipo_contrato']);
			$data['contratos'][$k]['data_aprovacao'] = (!empty($contrato['data_aprovacao'])) ? date('d/m/Y H:i', strtotime($contrato['data_aprovacao'])) : 'Aguardando';
			$data['contratos'][$k]['data_criacao'] = date('d/m/Y H:i', strtotime($contrato['data_criacao']));
		}

		$data['header'] = $this->template->header();
		$data['navbar'] = $this->template->navbar();
		$data['heading'] = $this->template->heading([
			'title' => 'PAINEL ADMINISTRATIVO'
		]);
		$data['scripts'] = $this->template->scripts();
		$data['footer'] = $this->template->footer();

		$this->load->view('admin/cadastro_completo', $data);

	}

	public function aprovar($id)
	{
		$cadastro = $this->comprador->findById($id);

		$this->db->update('compradores', ['situacao' => 1], "id = {$id}");
		$this->ntf->sendSMS(soNumero($cadastro['celular']), "PHARMANEXO INFORMA: Seu cadastro foi aprovado, acesse o a plataforma para concluir a sua adesão.");
		$warn = [
			'type' => 'success',
			'message' => 'Cadastro aprovado.'
		];

		$this->output->set_content_type('application/json')->set_output(json_encode($warn));


	}

}
