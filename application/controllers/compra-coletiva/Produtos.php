<?php
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
require_once 'application/libraries/Number.php';

use WGenial\NumeroPorExtenso\NumeroPorExtenso;

class Produtos extends Adesao
{
	private $route, $db_ades;

	public function __construct()
	{
		parent::__construct();
        $this->db_ades = $this->load->database('adesao', true);
		$this->load->library('Notify', 'notify');
        $this->load->model('CC_Produto', 'produto');

		$this->route = base_url('produtos');

		if (isset($_SESSION['dados'])) {
			if ($_SESSION['dados']['completo'] != 1) {
				base_url('cadastro/dados');
			}
		}

	}

	public function index()
	{
		$data['header'] = $this->tmp_cc->header();
		$data['navbar'] = $this->tmp_cc->navbar();
		$data['heading'] = $this->tmp_cc->heading([
			'title' => 'Produtos'
		]);
		$data['scripts'] = $this->tmp_cc->scripts();
		$data['footer'] = $this->tmp_cc->footer();
		$data['urlDetalhes'] = $this->route . "/detalhes/";
		$data['produtos'] = $this->db_ades->where('ativo', '1')->order_by('data_cadastro DESC')->get('produtos')->result_array();

		$this->load->view('compra-coletiva/products_new', $data);

	}

	public function produtos()
	{
		$data['header'] = $this->tmp_cc->header();
		$data['navbar'] = $this->tmp_cc->navbar();
		$data['heading'] = $this->tmp_cc->heading([
			'title' => 'Produtos'
		]);
		$data['scripts'] = $this->tmp_cc->scripts();
		$data['footer'] = $this->tmp_cc->footer();

		$data['urlDetalhes'] = $this->route . "/detalhes/";

		$data['produtos'] = $this->db_ades->order_by('data_cadastro DESC')->get('produtos')->result_array();

		$this->load->view('products_new', $data);

	}

	public function detalhes($id)
	{
		$data['produto'] = $this->db_ades->where('id', $id)->get('produtos')->row_array();

		$data['header'] = $this->tmp_cc->header();
		$data['navbar'] = $this->tmp_cc->navbar();
		$data['heading'] = $this->tmp_cc->heading([
			'title' => $data['produto']['descricao']
		]);
		$data['scripts'] = $this->tmp_cc->scripts();
		$data['footer'] = $this->tmp_cc->footer();

		$data['produtos'] = $this->db_ades->where('id <>', $id)->where('ativo','1')->order_by('data_cadastro DESC')->get('produtos')->result_array();

		$data['urlPreco'] = "{$this->route}/getPreco";

		$this->load->view('products_details', $data);

	}

	public function getPreco($idProd = null, $qtd = null){

		if ($this->input->is_ajax_request()){

			$post = $this->input->post();
			$qtd = $post['quantidade'];

			$preco = $this->produto->getPreco($post['idProduto'], $qtd);
			$preco['total'] =  number_format($preco['valor'] * $qtd, '2', ',', '.');

			$this->output->set_content_type('application/json')->set_output(json_encode($preco));
		}




	}



}
