<?php
class Home extends CI_Controller{

	private $route;
	public function __construct()
	{
		parent::__construct();
		$this->route = base_url('compra-coletiva/home');
	}

	public function index(){

		$data['header'] = $this->template->header();
		$data['heading'] = $this->template->heading();
		$data['scripts'] = $this->template->scripts();
		$data['footer'] = $this->template->footer();
		$data['form_action'] = "{$this->route}/login";

		$this->load->view('home', $data);

	}

	public function teste(){

		$data['header'] = $this->template->header();
		$data['heading'] = $this->template->heading();
		$data['scripts'] = $this->template->scripts();
		$data['footer'] = $this->template->footer();
		$data['form_action'] = "{$this->route}/login";

		$this->load->view('home2', $data);

	}

	public function logout(){
		unset($_SESSION['validLogin'], $_SESSION['dados']);
		redirect(base_url());
	}

	public function login(){

		if ($this->input->method() == 'post'){

			$post = $this->input->post();

			$comp = $this->db->select('*')->where('cnpj', $post['login'])->get('compradores')->row_array();

			if (!empty($comp)) {
				if ($comp['situacao'] == '1'){
					if (password_verify($post['senha'], $comp['senha'])) {
						unset($comp['senha']);
						$_SESSION['validLogin'] = true;
						$this->session->set_userdata('dados', $comp);

						if ($comp['completo'] == 1){
							redirect(base_url('produtos'));
						}else{
							redirect(base_url('cadastro/dados'));
						}
					} else {
						$warn = [
							'type' => 'error',
							'message' => 'Dados inválidos, tente novamente.'
						];
					}
				}else{
					$warn = [
						'type' => 'warning',
						'message' => 'Seu cadastro está aguardando aprovação do administrador.'
					];
				}
				$this->session->set_userdata('warning', $warn);

				redirect($this->route);

			}else{
				$warn = [
					'type' => 'error',
					'message' => 'Não encontramos este usuário.'
				];

				$this->session->set_userdata('warning', $warn);

				redirect($this->route);
			}
		}


	}



}
