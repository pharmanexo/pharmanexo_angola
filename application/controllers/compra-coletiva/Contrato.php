<?php
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
require_once 'application/libraries/Number.php';

use WGenial\NumeroPorExtenso\NumeroPorExtenso;


class Contrato extends CI_Controller
{
	private $route;
	private $views;


	public function __construct()
	{
		parent::__construct();
		$this->load->library('Notify', 'notify');
    $this->db = $this->load->database('adesao', true);
		$this->load->model('compra_coletiva/produto');
		$this->route = base_url('compra-coletiva/contrato');
		$this->views = 'compra-coletiva/';


		if (isset($_SESSION['dados'])) {
			if ($_SESSION['dados']['completo'] != 1) {
				redirect(base_url('compra-coletiva/cadastro/dados'));

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

		$data['produtos'] = $this->db->order_by('data_cadastro DESC')->get('produtos')->result_array();

		$this->load->view($this->views . 'products', $data);


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

		$data['produtos'] = $this->db->order_by('data_cadastro DESC')->get('produtos')->result_array();

		$this->load->view($this->views . 'products_new', $data);


	}

	public function gerar()
	{

		$tipo = $this->input->post('id');
		$qtd = $this->input->post('quantidade');

		$produto = $this->db->where('id', $tipo)->get('produtos')->row_array();

		$dados = $_SESSION['dados'];
		if (isset($dados['endereco_comercial'])) $dados['endereco_comercial'] = json_decode($dados['endereco_comercial'], true);
		if (isset($dados['endereco_empresa'])) $dados['endereco_empresa'] = json_decode($dados['endereco_empresa'], true);
		$cnpj = soNumero($dados['cnpj']);
		$fileName = "{$cnpj}_{$tipo}.pdf";
		$dados['quantidade'] = dbNumberFormat($qtd);

		$dados['preco'] = ($produto['minimo'] == 'Consulte') ? $this->produto->getPreco($tipo, $qtd)['valor'] : $produto['valor'];
		$dados['id_produto'] = $tipo;
		$dados['contrato'] = $produto['contrato'];
		$dados['produto'] = $produto['descricao'];
		$dados['vendedor'] = $this->db->where('id', $produto['id_vendedor'])->get('distribuidores')->row_array();

		#delete antigo
		$this->db
			->where('cnpj', $dados['cnpj'])
			->where('tipo_contrato', $tipo)
			->delete('contratos');


		if (file_exists("public/compra_coletiva/contratos/{$fileName}")) {

		}

		$this->gerarContrato($dados, $fileName, $tipo);


		$data['header'] = $this->tmp_cc->header();
		$data['heading'] = $this->tmp_cc->heading([
			'title' => 'ACEITE DE CONTRATO'
		]);
		$data['navbar'] = $this->tmp_cc->navbar();
		$data['scripts'] = $this->tmp_cc->scripts();
		$data['footer'] = $this->tmp_cc->footer();

		$data['contrato'] = $this->load->view($this->views.'contrato', $data, true);

		$data['file'] = CONTRATOS_PATH . "{$fileName}";
		$data['urlAceite'] = "{$this->route}/aceite";
		$data['urlAnexos'] = base_url("contratos/Contrato{$produto['id']}/");;


		$this->load->view($this->views . 'read_contrato', $data);

	}

	public function aceite()
	{

		$contrato = $this->session->contrato;
		$cliente = $this->db->where('cnpj', $contrato['cnpj'])->get('compradores')->row_array();
		$cliente_endereco = (isset($cliente)) ? json_decode($cliente['endereco_empresa'], true) : null;

		$aprovado = [
			'data_aprovacao' => date('Y-m-d H:i:s'),
			'situacao' => '1'
		];

		$this->db->where('id', $contrato['id']);
		$this->db->where('situacao', 0);
		if ($this->db->update('contratos', $aprovado)) {


			$tmp_cc = file_get_contents('https://www.pharmanexo.com.br/adesao/tmp_ccs/email.html');

			$fiels = ['{url_contrato}', '{cnpj}', '{empresa}', '{cpf}', '{nome}', '{logradouro}', '{numero}', '{bairro}', '{cidade}', '{estado}', '{cep}', '{telefone}', '{celular}'];
			$values = [$contrato['url'], $contrato['cnpj'], $cliente['empresa'], $cliente['cpf'], $cliente['nome'], $cliente_endereco['logradouro'], $cliente_endereco['numero'],
				$cliente_endereco['bairro'], $cliente_endereco['localidade'], $cliente_endereco['estado'], $cliente_endereco['cep'], $cliente['telefone'], $cliente['celular']];


			$body = str_replace($fiels, $values, $tmp_cc);


			$this->notify->send("marlon.boecker@pharmanexo.com.br, administracao@pharmanexo.com.br, {$cliente['email']}", 'Novo Contrato de Adesão', $body);

		}

		redirect("{$this->route}/aprovado");
	}

	public function aprovado()
	{

		$data['contrato'] = $_SESSION['contrato'];
		$dados = $_SESSION['dados'];


		$data['header'] = $this->tmp_cc->header();
		$data['heading'] = $this->tmp_cc->heading();
		$data['scripts'] = $this->tmp_cc->scripts();
		$data['footer'] = $this->tmp_cc->footer();

		$this->load->view($this->views . 'aprovado', $data);


	}

	public function meus_contratos()
	{

		$dados = $_SESSION['dados'];

		$data['contratos'] = $this->db
			->select('c.*, p.descricao as produto')
			->join('produtos p', 'p.id = c.id_produto')
			->where('c.cnpj', $dados['cnpj'])
			->get('contratos c')
			->result_array();


		/*foreach ($data['contratos'] as $k => $contrato) {

			$data['contratos'][$k]['tipo'] = tipoContrato($contrato['tipo_contrato']);

		}*/


		$data['header'] = $this->tmp_cc->header();
		$data['navbar'] = $this->tmp_cc->navbar();
		$data['heading'] = $this->tmp_cc->heading([
			'title' => 'Meus Contratos'
		]);
		$data['scripts'] = $this->tmp_cc->scripts();
		$data['footer'] = $this->tmp_cc->footer();

		$this->load->view($this->views . 'meus_contratos', $data);

	}

	private function gerarContrato($dados, $fileName, $tipo)
	{
		$hash = md5($dados['cnpj'] . $dados['cpf']);
		$number = number_format($dados['quantidade'], 0, ',', '.');
		$n = new NumeroPorExtenso;
		$extenso = $n->converter($dados['quantidade']);
		$total = ($dados['preco'] * $dados['quantidade']);
		$total_ext = $n->converter($total, true);

		$preco = number_format($dados['preco'], '2', ',', '.');
		$preco_unit_ext = $n->converter($dados['preco'], true);



		$contrato = ($this->load->view("{$this->views}modelos/contrato", [], true));


		$fields = [
			"{contrato}",
			"{empresa}",
			"{cnpj}",
			"{logradouro}",
			"{numero}",
			"{bairro}",
			"{complemento}",
			"{cidade}",
			"{estado}",
			"{cep}",
			"{representante}",
			"{cpf}",
			"{assinatura_comprador}",
			"{data}",
			"{quantidade}",
			"{quantidade_descricao}",
			"{hash}",
			"{total}",
			"{total_ext}",
			"{valor_unit}",
			"{valor_unit_desc}",
			"{produto}",
			"{cnpj_vendedor}",
			"{razao_social}",
			"{nome_fantasia}",
			"{endereco_vendedor}",
			"{cpf_vendedor}",
			"{nome_vendedor}",
			"{banco}",
			"{agencia}",
			"{conta}",
		];

		$values = [
			$dados['contrato'],
			$dados['empresa'],
			$dados['cnpj'],
			$dados['endereco_empresa']['logradouro'],
			$dados['endereco_empresa']['numero'],
			$dados['endereco_empresa']['bairro'],
			$dados['endereco_empresa']['complemento'],
			$dados['endereco_empresa']['localidade'],
			$dados['endereco_empresa']['estado'],
			$dados['endereco_empresa']['cep'],
			$dados['nome'],
			$dados['cpf'],
			strtoupper($dados['empresa']),
			strtoupper(strftime('%d de %B de %Y', strtotime('today'))),
			$number,
			$extenso,
			$hash,
			number_format($total, '2', ',', '.'),
			$total_ext,
			$preco,
			$preco_unit_ext,
			strtoupper($dados['produto']),
			$dados['vendedor']['cnpj'],
			strtoupper($dados['vendedor']['razao_social']),
			strtoupper($dados['vendedor']['nome_fantasia']),
			strtoupper($dados['vendedor']['endereco']),
			$dados['vendedor']['cpf'],
			strtoupper($dados['vendedor']['responsavel']),
			$dados['vendedor']['banco'] . " - " . $dados['vendedor']['banco_nome'],
			$dados['vendedor']['agencia'],
			$dados['vendedor']['conta'],


		];

		$out = str_replace($fields, $values, $contrato);


		$mpdf = new \Mpdf\Mpdf(['setAutoTopMargin' => 'pad', 'autoMarginPadding' => 6]);;
		$mpdf->SetDisplayMode('fullpage');
		#$css = file_get_contents("css/estilo.css");
		#$mpdf->WriteHTML($css,1);

		$mpdf->WriteHTML($out);

		$content = $mpdf->Output("public/compra_coletiva/contratos/{$fileName}", \Mpdf\Output\Destination::FILE);



		$insert = [
			'cnpj' => $dados['cnpj'],
			'tipo_contrato' => $tipo,
			'url' => base_url("/public/compra_coletiva/contratos/{$fileName}"),
			'quantidade' => $dados['quantidade'],
			'hash' => $hash,
			'origin' => $_SERVER['REMOTE_ADDR'],
			'id_produto' => $dados['id_produto']
		];

		if ($this->db->insert('contratos', $insert)) {
			$insert['id'] = $this->db->insert_id();

			$this->session->set_userdata('contrato', $insert);
		}


	}



}
