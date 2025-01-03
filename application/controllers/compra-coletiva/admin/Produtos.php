<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
class Produtos extends ADM_Controller{



	private $route;
	private $views;
	public function __construct()
	{
		parent::__construct();

		$this->route = base_url('admin/produtos');
		$this->views = 'admin/produtos';
		$this->load->model('produto');
	}

	public function index(){
		$data['page_title'] = "Produtos";

		$data['produtos'] = $this->produto->find();

		$data['header'] = $this->template->header();
		$data['navbar'] = $this->template->navbar();
		$data['heading'] = $this->template->heading([
			'title' => 'Produtos',
			'buttons' => [
				[
					'type' => 'a',
					'id' => 'btnInsert',
					'url' => "{$this->route}/insert",
					'class' => 'btn-primary',
					'icone' => 'fa-plus-square',
					'label' => 'Novo Registro'
				],
			]
		]);
		$data['scripts'] = $this->template->scripts();
		$data['footer'] = $this->template->footer();

		$this->load->view("{$this->views}/main", $data);

	}

	public function insert(){
		if ($this->input->method() =='post' ){
			$post = $this->input->post();
			$files = $_FILES;
			if (isset($post['valor'])) $post['valor'] =  dbNumberFormat($post['valor']);

			$post['preco_500'] = $post['valor'];
			$post['preco_1000'] = $post['valor'];
			$post['preco_2000'] = $post['valor'];
			$post['preco_5000'] = $post['valor'];
			$post['preco_10000'] = $post['valor'];

			if ($this->produto->insert($post)){
				$id = $this->db->insert_id();

				#upload foto
				$foto = $this->doUpload('foto',  "contratos/Contrato{$id}");

				$ficha = $this->doUpload('ficha', "contratos/Contrato{$id}",'anexo_1.pdf','pdf');


				$update = [
					'id' => $id,
					'imagem' => $foto['data']['file_name'],
					'contrato' => $id
				];

				$this->produto->update($update);

			}else{

			}



			#cadastra o produto

			#cria a pasta e faz upload dos arquivos

			#atualiza o produtos


		}else{
			$this->form();
		}
	}


	public function update($id){
		if ($this->input->method() =='post' ){

		}else{
			$this->form($id);
		}
	}

	public function delete(){

	}


	private function form($id = null){

		if (isset($id)){
			$title = "Atualização de Produto";
			$data['form_action'] = "{$this->route}/update/{$id}";
		}else{
			$title = "Cadastro de Produto";
			$data['form_action'] = "{$this->route}/insert/";
		}

		$data['vendedores'] = $this->db->select('id, nome_fantasia')->get('distribuidores')->result_array();

		$data['header'] = $this->template->header();
		$data['navbar'] = $this->template->navbar();
		$data['heading'] = $this->template->heading([
			'title' => $title,
			'buttons' => [
				[
					'type' => 'submit',
					'id' => 'btnInsert',
					'form' => "frmProduto",
					'class' => 'btn-primary',
					'icone' => 'fa-save',
					'label' => ' Salvar'
				],
			]
		]);
		$data['scripts'] = $this->template->scripts();
		$data['footer'] = $this->template->footer();

		$this->load->view("{$this->views}/form", $data);

	}

	private function doUpload($filename, $path, $name = null, $type = null)
	{
		unset($config);

		if (!file_exists($path)) {
			mkdir($path, 0755, true);
		}

		$config['upload_path'] = $path;
		$config['allowed_types'] = (isset($type)) ? $type : 'pdf|doc|jpeg|jpg|png|gif|doc|docx';
		$config['max_width'] = 2048;
		$config['max_height'] = 768;

		if (isset($name)){
			$config['file_name'] = 'anexo_1.pdf';
		}else{
			$config['encrypt_name'] = true;
		}

		$this->load->library('upload');

		$this->upload->initialize($config);

		$r = $this->upload->do_upload($filename);

		if (!$r){
			var_dump($this->upload->display_errors());
		}

		return ['result' => $r , 'data' => $this->upload->data()];

	}
}
