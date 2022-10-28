<?php


class Cadastro extends Adesao

{

	private $route;

	public function __construct()
	{
		parent::__construct();
		$this->load->library('upload');
		$this->load->model('compra_coletiva/Comprador', 'cmp');
		$this->route = base_url('compra-coletiva/cadastro');
		$this->views = 'compra-coletiva/';

	}

	public function index()
	{

		$data['header'] = $this->template->header();
		$data['heading'] = $this->template->heading();
		$data['scripts'] = $this->template->scripts();
		$data['footer'] = $this->template->footer();

		$data['form_action'] = "{$this->route}/cadastrar";
		$data['urlVerificaCNPJ'] = "{$this->route}/verificarCNPJ";


		$this->load->view($this->views. 'cadastro', $data);


	}

	public function consultaCNPJ($cnpj){

		// create curl resource
		$ch = curl_init();

		// set url
		curl_setopt($ch, CURLOPT_URL, "https://www.receitaws.com.br/v1/cnpj/{$cnpj}");

		//return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// $output contains the output string
		$output = curl_exec($ch);

		// close curl resource to free up system resources
		curl_close($ch);


		$this->output->set_content_type('application/json')->set_output($output);
	}

	public function cadastrar()
	{

		if ($this->input->method() == 'post') {
			$post = $this->input->post();
			$senha = generatePassword();
			$post['senha'] = password_hash($senha, PASSWORD_DEFAULT);
			if (isset($post['end'])){
				$post['endereco_empresa'] = json_encode($post['end']);
				unset($post['end']);
			}
			$validate = $this->validarDados($post);

			if ($this->cmp->insert($post)) {
				$phone = soNumero($post['celular']);
				$link = base_url("cadastro/aprovar/{$this->db->insert_id()}");
				$this->ntf->sendSMS($phone, "PHARMANEXO INFORMA: Cadastro realizado! Utilize a senha: {$senha}");
				$this->ntf->sendSMS(soNumero('27 99942-9900'), "PHARMANEXO INFORMA: CADASTRO AGUARDANDO APROVAÇÃO {$post['cnpj']} - {$post['empresa']} - ABRE O LINK PARA APROVAR: {$link}");
				$this->ntf->sendSMS(soNumero('27 99299-4049'), "PHARMANEXO INFORMA: CADASTRO AGUARDANDO APROVAÇÃO {$post['cnpj']} - {$post['empresa']} - ABRE O LINK PARA APROVAR: {$link}");
				$warn = [
					'type' => 'success',
					'message' => 'Seu cadastro foi realizado com sucesso, em alguns instantes voce receberá um SMS com sua senha.'
				];

				$this->session->set_userdata('warning', $warn);

				redirect(base_url());

			} else {
				$warn = [
					'type' => 'error',
					'message' => 'Houve um erro ao realizar seu cadastro, tente novamente.'
				];

				$this->session->set_userdata('warning', $warn);
				redirect($this->route);
			}

		}

	}

	public function aprovar($id = null)
	{

		if ($this->input->method() == 'post') {
			$post = $this->input->post();
			$cadastro = $this->cmp->findById($post['id']);


			if ($cadastro['situacao'] == 0 && $post['senha'] == 'pharma1020') {
				$this->db->update('compradores', ['situacao' => 1], "id = {$post['id']}");
				$this->ntf->sendSMS(soNumero($cadastro['celular']), "PHARMANEXO INFORMA: Seu cadastro foi aprovado, acesse o a plataforma para concluir a sua adesão.");
				$warn = [
					'type' => 'success',
					'message' => 'Cadastro aprovado.'
				];

				$this->session->set_userdata('warning', $warn);

				redirect(base_url());
			}
		} else {
			$data['cadastro'] = $this->cmp->findById($id);

			$data['header'] = $this->template->header();
			$data['heading'] = $this->template->heading();
			$data['scripts'] = $this->template->scripts();
			$data['footer'] = $this->template->footer();
			$data['form_action'] = "{$this->route}/aprovar";
			$this->load->view('aprovar', $data);
		}


	}

	public function dados()
	{
		if ($this->input->method() == 'post') {

			$post = $this->input->post();
			$quantidade = $post['quantidade'];
			$cnpj = soNumero($post['cnpj']);
			$post['completo'] = 1;
			$post['endereco_empresa'] = (isset($post['end']['comercial'])) ? json_encode($post['end']['comercial']) : '';
			$post['endereco_comercial'] = (isset($post['end']['pessoal'])) ? json_encode($post['end']['pessoal']) : '';

			unset($post['end']);

			#faz o primeiro upload
			$upload1 = $this->doUpload("doc_alvara", "uploads/{$cnpj}/");

			$post['url_alvara'] = $upload1['data']['file_name'];
			$upload2 = [];
			if (!$upload1['result']) {
				$warn = [
					'type' => 'warning',
					'message' => "Erro de Upload: " . $this->upload->display_errors()
				];

				$this->session->set_userdata('warning', $warn);
				redirect("{$this->route}/dados");
			} else {
				#faz o segundo upload
				$upload2 = $this->doUpload('doc_cnpj', "uploads/{$cnpj}/");
				$post['url_cnpj'] = $upload2['data']['file_name'];
				if (!$upload2['result']) {
					$warn = [
						'type' => 'warning',
						'message' => "Erro de Upload: " . $this->upload->display_errors()
					];

					$this->session->set_userdata('warning', $warn);
					redirect("{$this->route}/dados");

				} else {

					#insere no banco
					if ($this->cmp->update($post)) {
						$dados = $this->cmp->findById($post['id']);
						unset($dados['senha']);
						$_SESSION['dados'] = $dados;

						$warn = [
							'type' => 'success',
							'message' => 'Seus dados foram atualizados'
						];

						$this->session->set_userdata('warning', $warn);


						redirect(base_url('produtos'));
					} else {
						$warn = [
							'type' => 'warning',
							'message' => "Erro de banco: " . $this->db->error()
						];

						$this->session->set_userdata('warning', $warn);
						redirect("{$this->route}/dados");
					}
				}


				$this->load->view($this->views . 'upload_success', []);

			}


		} else {

			if (!isset($_SESSION['dados'])) redirect(base_url());


			$data['header'] = $this->tmp_cc->header();
      $data['navbar'] = $this->tmp_cc->navbar();
			$data['heading'] = $this->tmp_cc->heading();
			$data['scripts'] = $this->tmp_cc->scripts();
			$data['footer'] = $this->tmp_cc->footer();


			$data['form_action'] = "{$this->route}/dados";
			$data['dados'] = $this->session->dados;

			if (isset($data['dados']['endereco_comercial']) && !empty($data['dados']['endereco_comercial'])){
				$data['dados']['endereco_comercial'] = json_decode($data['dados']['endereco_comercial'], true);
			}

			if (isset($data['dados']['endereco_empresa']) && !empty($data['dados']['endereco_empresa'])){
				$data['dados']['endereco_empresa'] = json_decode($data['dados']['endereco_empresa'], true);
			}



			$this->load->view($this->views . 'cadastro_completo', $data);

		}
	}

	public function verificarCNPJ()
	{
		if ($this->input->method() == 'post') {
			$cnpj = $this->input->post('cnpj');

			$comp = $this->db->select('*')->where('cnpj', $cnpj)->get('compradores')->row_array();

			if (!empty($comp)) {
				$warn = ['type' => 'error', 'message' => 'CNPJ já se encontra cadastrado.'];
			} else {
				$warn = ['type' => 'success', 'message' => 'Cadastro liberado'];
			}

			$this->output->set_content_type('application/json')->set_output(json_encode($warn));

		}
	}

	private function validarDados($dados)
	{

		if (!isset($dados['cnpj']) || !strlen($dados['cnpj']) == '18') {
			return ['type' => 'error', 'message' => 'CNPJ inválido ou não informado'];
		}

		if (!isset($dados['cpf']) || !strlen($dados['cpf']) == '14') {
			return ['type' => 'error', 'message' => 'CPF inválido ou não informado'];
		}

		if (!isset($dados['celular']) || !strlen($dados['celular']) == '14') {
			return ['type' => 'error', 'message' => 'CELULAR inválido ou não informado'];
		}

		if (!isset($dados['end']['comercial']['cep'])) {
			return ['type' => 'error', 'message' => 'CEP inválido ou não informado'];
		}

		return true;

	}

	private function doUpload($filename, $path)
	{

		if (!file_exists($path)) {
			mkdir($path, 0755, true);
		}

		$config['upload_path'] = $path;
		$config['allowed_types'] = 'pdf|doc|jpeg|jpg|png|gif|doc|docx';
		$config['max_size'] = 2056;
		$config['max_width'] = 1024;
		$config['max_height'] = 768;
		$config['encrypt_name'] = true;

		$this->upload->initialize($config);


		return ['result' => $this->upload->do_upload($filename), 'data' => $this->upload->data()];

	}

	public function getDadosCPF(){
		if ($this->input->method() == 'post'){
			// Seu token de acesso
			$token = "099A37B9-AFF1-49B8-BAFB-2938E3655C6F";
			$cpf = $this->input->post('cpf');
			$data_nascimento = $this->input->post('data_nascimento');
			$plugin = "CPF";

			//URL do serviço
			$service_url = "https://sintegraws.com.br/api/v1/execute-api.php?token=" . $token . "&cpf=" . $cpf . "&data-nascimento=" . $data_nascimento . "&plugin=" . $plugin;

			$response = file_get_contents($service_url);

			var_dump($response);exit();

			//Aqui fazemos o parse do json retornado
			$json = json_decode($response);

			//Aqui exibimos uma mensagem caso tenha ocorrido algum erro
			if ($json->code != '0')
			{
				die("Erro " . $json->code . ": " . $json->message);
			}else{
				$this->output->set_content_type('application/json')->set_output($response);
			}

		}

	}
}
