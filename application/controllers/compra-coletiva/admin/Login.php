<?php
class Login extends CI_Controller{

	private $route;
	public function __construct()
	{
		parent::__construct();
		$this->route = base_url('admin/login');
	}

	public function index(){

		$data['header'] = $this->template->header();
		$data['heading'] = $this->template->heading();
		$data['scripts'] = $this->template->scripts();
		$data['footer'] = $this->template->footer();
		$data['form_action'] = "{$this->route}/validar";

		$this->load->view('admin/login', $data);

	}

	public function validar(){

		if ($this->input->method() == 'post'){

			$post = $this->input->post();
			#var_dump($post);exit();

			if ($post['login'] == 'administrador' && $post['senha'] == 'Pharmanexo@1020') {

				$_SESSION['admin'] = 'Pharmanexo';
				$_SESSION['permited'] = true;

				redirect(base_url("admin/dashboard"));
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

	public function logout(){

		unset($_SESSION['admin'],
		$_SESSION['permited']);

		redirect(base_url("admin/login"));

	}

}
