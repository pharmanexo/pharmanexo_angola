<?php

class GetOrdensCompra extends CI_Controller
{
	/**
	 * @author : Chule Cabral
	 * Data: 08/01/2021
	 *
	 * Crontab =>
	 */

	private $urlClient;
	private $location;

	public function __construct()
	{
		parent::__construct();

		$this->sint = $this->load->database('sintese', true);

		$this->urlClient = 'https://ws-sintese.bionexo.com/IntegrationService.asmx?WSDL';

		$this->location = 'https://ws-sintese.bionexo.com/IntegrationService.asmx';

	}

	private function getFornecedoresOc()
	{

//		$result = $this->db->query('SELECT x.id_fornecedor, f.cnpj
//            FROM pharmanexo.cotacoes_produtos x
//            JOIN pharmanexo.fornecedores f
//                ON f.id = x.id_fornecedor
//            GROUP BY x.id_fornecedor, f.cnpj')
//        ->result_array();
//		return $result;
		
		$this->db->select("x.id_fornecedor, f.cnpj");
        $this->db->from("cotacoes_produtos x");
        $this->db->join("fornecedores f", "f.id = x.id_fornecedor");
        $this->db->group_by("x.id_fornecedor, f.cnpj");

        return $this->db->get()->result_array();
	}

	private function checkOc($params)
	{

		$result = $this->db->where('Cd_Ordem_Compra', $params['Cd_Ordem_Compra'])
			->where('Cd_Fornecedor', $params['Cd_Fornecedor'])
			->limit(1)
			->get('ocs_sintese')
			->row_array();

		if (IS_NULL($result))
			return TRUE;

		return FALSE;
	}

	private function checkCot($params)
	{

		$result = $this->db->select('cd_cotacao')
			->where('cd_cotacao', $params['Cd_Cotacao'])
			->where('id_fornecedor', $params['id_fornecedor'])
			->group_by('cd_cotacao')
			->get('cotacoes_produtos')
			->row_array();

		if (IS_NULL($result))
			return TRUE;

		return FALSE;

	}

	public function index()
	{

		$soapClient = new SoapClient($this->urlClient,
			['trace' => true, 'location' => $this->location]);

		$fornecedores = $this->getFornecedoresOc();

		foreach ($fornecedores as $fornecedor) {

			$id_fornecedor = intval($fornecedor['id_fornecedor']);

			$result = $soapClient->ObterOrdensDeCompraPendentes(
				[
					'cnpjFornecedor' => preg_replace("/\D+/", "", $fornecedor['cnpj'])
				]);

			$xmlResult = $result->ObterOrdensDeCompraPendentesResult;

			if (strpos($xmlResult, 'xml') == FALSE)
				continue;

			$xml = new SimpleXMLElement($xmlResult);

			$xmlArray = json_encode($xml);
			$xmlArray = json_decode($xmlArray, true);

			$ordensCompra = arrayFormat($xmlArray);

			foreach ($ordensCompra as $oc) {

				$value = $oc['Ordem_Compra'];

				$params =
					[
						'Cd_Ordem_Compra' => $value['Cd_Ordem_Compra'],
						'Cd_Fornecedor' => $value['Cd_Fornecedor'],
						'Cd_Cotacao' => $value['Cd_Cotacao'],
						'id_fornecedor' => $id_fornecedor
					];

				if (!$this->checkOc($params))
					continue;

				if ($this->checkCot($params))
					continue;

				$cd_comprador = str_replace('X', '0', $value['Cd_Comprador']);

				$cnpj_comprador = mask($cd_comprador, '##.###.###/####-##');

				$id_comprador = $this->db->where('cnpj', $cnpj_comprador)
					->get('compradores')
					->row_array()['id'];

				var_dump($value); exit();

				var_dump($id_comprador);
				exit();


			}

		}
	}
}
