<?php

class Notifications extends ADM_Controller
{

	private $route;

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Notify', 'notify');
		$this->route = base_url('admin/notifications');
		$this->load->model('comprador');
	}

	public function index()
	{

		$data['page_title'] = "Enviar uma mensagem";

		$data['header'] = $this->template->header();
		$data['navbar'] = $this->template->navbar();
		$data['heading'] = $this->template->heading([
			'title' => 'PAINEL ADMINISTRATIVO'
		]);
		$data['scripts'] = $this->template->scripts();
		$data['footer'] = $this->template->footer();

		$this->load->view('admin/notificar', $data);

	}

	public function cadIncompleto()
	{
		$compradores = $this->comprador->find('celular, email', 'completo = 0');
		$destinos = ['27992994049'];
		$mails = ['marlon.mbes@gmail.com'];

		foreach ($compradores as $celular){
			$n = soNumero($celular['celular']);
			$mails[] = $celular['email'];
			if (strlen($n) == 11){
				$destinos[] = $n;
			}else{
				$destinos[] = $this->insertInPosition($n, 2, '9');
			}

		}

		foreach ($destinos as $d){
			$this->ntf->sendSMS($d, "PHARMANEXO INFORMA: conclua seu cadastro e aproveite as ofertas. Prazo final amanhã (22/05/2020). Acesse: https://bit.ly/36mSCGA");
		}

		$template = file_get_contents('https://www.pharmanexo.com.br/adesao/templates/email-padrao.html');
		$fiels = ['{body}'];


		$body = "<p>Prezado (a), <br> Ainda não foi concluido o cadastro da empresa no Portal de Adesão Pharmanexo.</p>
				<p>O prazo final é amanhã (22/05/2020), não perca essa oportunidade.</p>
				<br><br>
				
";

		$values = [$body];

		$body = str_replace($fiels, $values, $template);

		$this->notify->send(implode(',', $mails), 'Portal Pharmanexo - Notificação Importante', $body);

	}

	public function contratoPendente()
	{
		$compradores = [];
		$contratos = $this->db->select('cnpj')->where('data_aprovacao is null')->group_by('cnpj')->get('contratos')->result_array();

		foreach ($contratos as $contrato){
			$compradores[] = $this->comprador->find('celular, email', "cnpj = '{$contrato['cnpj']}'", true);
		}

		$destinos = ['27992994049'];
		$mails = ['marlon.mbes@gmail.com'];

		foreach ($compradores as $celular){
			$n = soNumero($celular['celular']);
			$mails[] = $celular['email'];
			if (strlen($n) == 11){
				$destinos[] = $n;
			}else{
				$destinos[] = $this->insertInPosition($n, 2, '9');
			}

		}


		foreach ($destinos as $d){
			$this->ntf->sendSMS($d, "PHARMANEXO INFORMA: Existem contratos pendentes de assinatura no portal, o prazo final amanhã (22/05/2020). Acesse: https://www.pharmanexo.com.br/adesao/");
		}

		$template = file_get_contents('https://www.pharmanexo.com.br/adesao/templates/email-padrao.html');
		$fiels = ['{body}'];


		$body = "<p>Prezado (a), <br><br> Existem contratos pendentes de assinatura no portal, o prazo final amanhã (22/05/2020).</p>
				<br><br>
				
";

		$values = [$body];

		$body = str_replace($fiels, $values, $template);

		$this->notify->send(implode(',', $mails), 'Portal Pharmanexo - Notificação Importante', $body);

	}

	function insertInPosition($str, $pos, $c){
		return substr($str, 0, $pos) . $c . substr($str, $pos);
	}


}
