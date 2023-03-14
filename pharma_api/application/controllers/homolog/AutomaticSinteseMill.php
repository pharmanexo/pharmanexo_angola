<?php

class AutomaticSinteseMill extends CI_Controller
{
	/**
	 * @author : Chule Cabral
	 * Data: 01/11/2020
	 *
	 * Crontab => 30 7-23 * * * wget https://pharmanexo.com.br/pharma_api/API/AutomaticSintese
	 */

	private $configs;
	private $sint;
	private $logs;

	public function __construct()
	{
		parent::__construct();

		$this->load->model('hmg/AutomaticsEngine', 'Engine');

		$this->sint = $this->load->database('sintese', true);

		$this->configs =
			[
				'turnOn' => TRUE,
				'integrador' => 'SINTESE',
				'fornecedorById' =>
					[
						'status' => FALSE,
						'id' => 180
					],
				'cotacaoById' =>
					[
						'status' => true,
						'cd_cotacao' => 'COT9742-971',
					],
				'checkDataFimCotacao' => FALSE,
				'checkEnabledAuto' => TRUE,
				'checkPrdCotRestriction' => TRUE,
				'checkVendaDif' => TRUE,
				'checkValorMinimo' => TRUE,
				'checkFormaPagamento' => TRUE,
				'checkPrazoEntrega' => TRUE,
				'checkPrdRestriction' => TRUE,
				'checkClientRestriction' => TRUE,
				'checkPrdStock' => TRUE,
				'checkPrdSent' => FALSE,
				'checkValorTotalCot' => FALSE,
				'setDescontoFinal' => TRUE,
				'submitSintese' => false,
				'sendEmail' => false,
				'sendEmailAnexo' => false,
				'sendEmailDestiny' => false,
				'saveProdsOferta' => TRUE,
				'saveLogs' => false
			];
	}

	public function testeMail()
    {
        $this->Engine->sendEmail([
            'from' => 'no-reply@pharmanexo.com.br',
            'from-name' => 'MArlon',
            'destinatario' => 'marlon.mbes@gmail.com',
            'assunto' => 'marlon.mbes@gmail.com',
            'msg' => 'Teste',
        ]);
    }

	private function getFornecedores()
	{

		$fornecedores = $this->db->select('id, margem_estoque, cnpj, config')
			->where_in('tipo_venda', [2, 3])
			->where_in('id', [5032,5033,5034])
			->order_by('id')
			->get('fornecedores')
			->result_array();

		if ($this->configs['fornecedorById']['status']) {

			$id = intval($this->configs['fornecedorById']['id']);

			foreach ($fornecedores as $key => $fornecedor) {

				if ($fornecedor['id'] == $id) {

					return
						[
							'status' => TRUE,
							'result' => [$key => $fornecedor]
						];
				}
			}
			return ['status' => FALSE];
		}

		return
			[
				'status' => TRUE,
				'result' => $fornecedores
			];
	}

	private function getProdsCots($params)
	{

		$fields = "id_produto_sintese, cd_produto_comprador, ds_produto_comprador, qt_produto_total, ds_unidade_compra";

		$result = $this->sint->select($fields)
			->where('cd_cotacao', $params['cd_cotacao'])
			->where('id_fornecedor', $params['id_fornecedor'])
			->group_by($fields)
			->get('cotacoes_produtos')
			->result_array();

		if (empty($result))
			return ['status' => FALSE];

		return
			[
				'status' => TRUE,
				'result' => $result
			];
	}

	private function getFormaPagamento($params)
	{

		$bool = TRUE;

		$id_forma_pagamento = 5;

		if ($this->configs['checkFormaPagamento']) {

			$result = $this->db->where('id_fornecedor', $params['id_fornecedor'])
				->group_start()
				->where('id_cliente', $params['id_cliente'])
				->or_where('id_estado', $params['id_estado'])
				->group_end()
				->limit(1)
				->get('formas_pagamento_fornecedores')
				->row_array()['id_forma_pagamento'];

			if (IS_NULL($result)) {

				$bool = FALSE;

			} else {
				$id_forma_pagamento = $result;
			}
		}

		$arrResult =
			['id_forma_pagamento' => $id_forma_pagamento];

		return
			[
				'status' => $bool,
				'result' => $arrResult
			];
	}

	private function checkProductSent($params)
	{
		if ($this->configs['checkPrdSent']) {

			/**
			 * Verifica se o produto da cotação já foi enviado.
			 * Se já foi enviado, a automática não envia novamente.
			 */

			$result = $this->db->where('id_fornecedor', $params['id_fornecedor'])
				->where('cd_cotacao', $params['cd_cotacao'])
				->where('id_pfv', $params['codigo'])
				->where('cd_produto_comprador', $params['cd_produto_comprador'])
				->where('integrador', "SINTESE")
				->limit(1)
				->get('cotacoes_produtos')
				->row_array();

			if (IS_NULL($result))
				return FALSE;

			return TRUE;
		}
		return FALSE;
	}

	private function prodsEncontrados($params, $prodsCotacao)
	{
		$produtos = [];

		foreach ($prodsCotacao as $key => $prod) {

			$params = array_merge($params,
				[
					'cd_produto_comprador' => $prod['cd_produto_comprador'],
					'id_produto_sintese' => $prod['id_produto_sintese'],
					'ds_produto_comprador' => $prod['ds_produto_comprador'],
					'qt_produto_total' => intval($prod['qt_produto_total']),
					'ds_unidade_compra' => $prod['ds_unidade_compra']
				]);

			$this->logs['PRODS-COT'][$key] =

				[
					'id_produto_sintese' => $params['id_produto_sintese'],
					'cd_produto_comprador' => $params['cd_produto_comprador'],
					'ds_produto_comprador' => $params['ds_produto_comprador']
				];

			$checkProductCotRestriction = $this->Engine->productCotRestriction($params, $this->configs);

			if ($checkProductCotRestriction) {

				$this->logs['PRODS-COT'][$key]['productCotRestriction'] = TRUE;

				continue;
			}

			$resultIdsSintese = $this->db->select('id_sintese')
				->where('id_produto', $params['id_produto_sintese'])
				->get('produtos_marca_sintese')
				->result_array();

			if (empty($resultIdsSintese)) {

				$this->logs['PRODS-COT'][$key]['produtos_marca_sintese'] = FALSE;

				continue;
			}

			$ids_sintese = [];

			foreach ($resultIdsSintese as $value) {

				$id_sintese = intval($value['id_sintese']);

				if (!in_array($id_sintese, $ids_sintese))
					array_push($ids_sintese, $id_sintese);

			}

			$select = "cat.codigo, cat.descricao, cat.apresentacao, cat.unidade,
					   cat.nome_comercial, cat.marca, cat.id_marca, cat.quantidade_unidade";

			$resultDepara = $this->db->select($select)
				->distinct()
				->where_in('pfs.id_sintese', $ids_sintese)
				->where('pfs.id_fornecedor', $params['id_fornecedor'])
				->where('cat.ativo', 1)
				->where('cat.bloqueado', 0)
				->from('produtos_fornecedores_sintese AS pfs')
				->join('produtos_catalogo AS cat', 'cat.codigo = pfs.cd_produto AND cat.id_fornecedor = pfs.id_fornecedor')
				->get()
				->result_array();

			if (empty($resultDepara)) {

				$this->logs['PRODS-COT'][$key]['produtos_fornecedores_sintese'] = FALSE;

				continue;
			}

			$newArr = [];

			$checkIdMarca = FALSE;

			foreach ($resultDepara as $keyProd => $value) {

				$params = array_merge($params,
					[
						'codigo' => intval($value['codigo']),
						'qtd_unidade' => IS_NULL($value['quantidade_unidade']) ? 1 : intval($value['quantidade_unidade'])
					]);

				$this->logs['PRODS-COT'][$key]['produtos_fornecedor'][$keyProd] =
					[
						'codigo' => $params['codigo'],
						'qtd_unidade' => $params['qtd_unidade'],
						'descricao' => $value['descricao'],
						'apresentacao' => $value['apresentacao'],
						'nome_comercial' => $value['nome_comercial'],
						'id_marca' => intval($value['id_marca']),
						'marca' => $value['marca']
					];

				if (intval($value['id_marca']) == 0) {

					$this->logs['PRODS-COT'][$key]['produtos_fornecedor'][$keyProd]
					['restricao']['DeParaMarca'] = FALSE;

					$checkIdMarca = TRUE;

					continue;
				}

				if ($this->checkProductSent($params)) {

					$this->logs['PRODS-COT'][$key]['produtos_fornecedor'][$keyProd]
					['restricao']['productSent'] = TRUE;

					continue;
				}

				$checkVendDif = $this->Engine->vendaDif($params, $this->configs);


				if (!$checkVendDif['status']) {

					$this->logs['PRODS-COT'][$key]['produtos_fornecedor'][$keyProd]
					['restricao']['vendaDif'] = FALSE;

					continue;
				}

				$desconto_percentual = floatval($checkVendDif['result']['desconto_percentual']);


				if ($this->Engine->productRestriction($params, $this->configs)) {

					$this->logs['PRODS-COT'][$key]['produtos_fornecedor'][$keyProd]
					['restricao']['productRestriction'] = TRUE;

					continue;
				}

				$params = array_merge($params, ['desconto_percentual' => $desconto_percentual]);

				$qtd_solicitada = $params['qt_produto_total'];

				$checkEstoque = $this->Engine->getEstoque($params, $this->configs);

				if (!$checkEstoque['status']) {

					$this->logs['PRODS-COT'][$key]['produtos_fornecedor'][$keyProd]
					['restricao']['productStock'] = FALSE;

					continue;
				}

				$estoque_unidade = ($checkEstoque['result']['total'] * intval($params['qtd_unidade']));

				$validade = $checkEstoque['result']['validade'];

				$obsProd = "-";

				if (isset($params['confValidade'])) {

					if ($params['confValidade'])
						$obsProd = "Validade: {$validade} - ";
				}

				$qtd_aceitavel = $qtd_solicitada;

				if (!IS_NULL($params['margem_estoque']))
					$qtd_aceitavel = (floatval($params['margem_estoque']) / 100) * $qtd_solicitada;

				if ($qtd_aceitavel > $estoque_unidade) {
					$obsProd .= "Produto atendido parcialmente!";
				}

				$checkPrice = $this->Engine->getPriceProd($params, $this->configs);


				if (!$checkPrice['status']) {

					$this->logs['PRODS-COT'][$key]['produtos_fornecedor'][$keyProd]
					['restricao']['productPrice'] = FALSE;

					continue;
				}

				$arr =
					[
						'codigo' => $params['codigo'],
						'id_produto' => $params['id_produto_sintese'],
						'qtd_solicitada' => $qtd_solicitada,
						'ds_unidade_compra' => $prod['ds_unidade_compra'],
						'descricao' => $value['descricao'],
						'apresentacao' => $value['apresentacao'],
						'nome_comercial' => $value['nome_comercial'],
						'id_marca' => intval($value['id_marca']),
						'marca' => $value['marca'],
						'unidade' => $value['unidade'],
						'validade' => $validade,
						'qtd_unidade' => $params['qtd_unidade'],
						'estoque' => $checkEstoque['result']['total'],
						'estoque_unidade' => $estoque_unidade,
						'desconto_padrao' => $params['desconto_padrao'],
						'desconto_vendaDif' => $params['desconto_percentual'],
						'tabela_precos' => $checkPrice['tabelaPrecos'],
						'tipo_preco' => $checkPrice['typePrice'],
						'preco_tabela' => $checkPrice['priceTabela'],
						'acres_tab_mix' => $checkPrice['acrescimoTabMix'],
						'preco_oferta' => $checkPrice['priceOferta'],
						'tipo_desconto_aplicado' => $checkPrice['tipoDesconto'],
						'vl_desconto_aplicado' => $checkPrice['descontoAplicado'],
						'obs_produto' => $obsProd
					];


				$newArr[] = $arr;

				$this->logs['PRODS-COT'][$key]['produtos_fornecedor'][$keyProd] = $arr;

				$this->logs['PRODS-COT'][$key]['produtos_fornecedor'][$keyProd]
				['oferta']['submitSintese'] = TRUE;
			}

			if ($checkIdMarca)
				$this->db->insert('notifications',
					[
						'type' => 'warning',
						'id_fornecedor' => $params['id_fornecedor'],
						'message' => "Existem produtos sem depara de marca na cotação: {$params['cd_cotacao']}.",
						'envia_email' => 1
					]);

			if (empty($newArr))
				continue;

			$produtos[] =
				[
					'cd_produto_comprador' => $params['cd_produto_comprador'],
					'id_produto_sintese' => $params['id_produto_sintese'],
					'ds_produto_comprador' => $params['ds_produto_comprador'],
					'qt_produto_total' => $params['qt_produto_total'],
					'ds_unidade_compra' => $params['ds_unidade_compra'],
					'marcas_encontradas' => $newArr
				];

		}

		if (empty($produtos))
			return [];

		unset(
			$params['codigo'],
			$params['cd_produto_comprador'],
			$params['ds_produto_comprador'],
			$params['qt_produto_total'],
			$params['ds_unidade_compra'],
			$params['id_produto_sintese'],
			$params['qtd_unidade'],
			$params['desconto_percentual']
		);

		return $array['cotacao'] =
			[
				'dados' => $params,
				'produtos_fornecedor' => $produtos
			];

	}

	private function createObject($objCotacao)
	{
		$arrProdsOferta = [];

		$vlTtotalCotacao = 0;

		$dom = new DOMDocument("1.0", "ISO-8859-1");
		$dom->formatOutput = TRUE;

		$root = $dom->createElement("Cotacao");

		# Adiciona as informações do cabeçalho
		$root->appendChild($dom->createElement("Tp_Movimento", '1'));
		$root->appendChild($dom->createElement("Dt_Gravacao", date("d/m/Y H:i:s", time())));
		$root->appendChild($dom->createElement("Cd_Fornecedor", preg_replace("/\D+/", "", $objCotacao['dados']['cnpj_fornecedor'])));
		$root->appendChild($dom->createElement("Cd_Cotacao", $objCotacao['dados']['cd_cotacao']));
		$root->appendChild($dom->createElement("Cd_Condicao_Pagamento", $objCotacao['dados']['forma_pagamento']));
		$root->appendChild($dom->createElement("Nm_Usuario", "PHARMAINT321"));
		$root->appendChild($dom->createElement("Ds_Observacao", '-'));
		$root->appendChild($dom->createElement("Qt_Prz_Minimo_Entrega", $objCotacao['dados']['prazo_entrega']));
		$root->appendChild($dom->createElement("Vl_Minimo_Pedido", str_replace(".", ",", $objCotacao['dados']['valor_minimo'])));

		$obs_fornecedor = "-";

		if (isset($objCotacao['dados']['obsFornecedor'])) {

			if (!IS_NULL($objCotacao['dados']['obsFornecedor']))
				$obs_fornecedor = $objCotacao['dados']['obsFornecedor'];
		}

		$root->appendChild($dom->createElement("Ds_Observacao_Fornecedor", utf8_encode($obs_fornecedor)));

		$produtosOferta = $dom->createElement("Produtos_Cotacao");

		foreach ($objCotacao['produtos_fornecedor'] as $prodsOferta) {

			$produtoOferta = $dom->createElement("Produto_Cotacao");

			$produtoOferta->appendChild($dom->createElement("Id_Produto_Sintese", $prodsOferta['id_produto_sintese']));
			$produtoOferta->appendChild($dom->createElement("Cd_Produto_Comprador", $prodsOferta['cd_produto_comprador']));

			$marcasOferta = $dom->createElement("Marcas_Oferta");

			foreach ($prodsOferta['marcas_encontradas'] as $prodsMarca) {

				$marca = $dom->createElement("Marca_Oferta");

				$marca->appendChild($dom->createElement("Id_Marca", $prodsMarca['id_marca']));
				$marca->appendChild($dom->createElement("Ds_Marca", utf8_encode($prodsMarca['marca'])));
				$marca->appendChild($dom->createElement("Qt_Embalagem", $prodsMarca['qtd_unidade']));
				$marca->appendChild($dom->createElement("Vl_Preco_Produto", number_format($prodsMarca['preco_oferta'], 4, ',', '.')));
				$marca->appendChild($dom->createElement("Ds_Obs_Oferta_Fornecedor", utf8_encode($prodsMarca['obs_produto'])));
				$marca->appendChild($dom->createElement("Cd_produtoERP", $prodsMarca['codigo']));

				$marcasOferta->appendChild($marca);

				$produtoOferta->appendChild($marcasOferta);
				$produtosOferta->appendChild($produtoOferta);

				$vlTtotalCotacao += ($prodsMarca['qtd_solicitada'] * $prodsMarca['preco_oferta']);

				$arrProdsOferta[] = $this->Engine->prodsOferta(
					[
						'type' => $objCotacao['dados']['type'],
						'dadosCotacao' => $objCotacao['dados'],
						'produtoOferta' => $prodsMarca,
						'cd_produto_comprador' => $prodsOferta['cd_produto_comprador']
					]);
			}
		}

		$root->appendChild($produtosOferta);

		$dom->appendChild($root);

		$dom->preserveWhiteSpace = FALSE;

		if ($this->configs['checkValorTotalCot']) {

			if ($vlTtotalCotacao < floatval($objCotacao['dados']['valor_minimo'])) {

				$this->logs['MSG'] = "Valor Total da Cotacao menor que o valor minimo!";

				return ['status' => FALSE];
			}
		}

		if ($vlTtotalCotacao == 0) {

			$this->logs['MSG'] = "Valor Total Zerado!";

			return ['status' => FALSE];
		}

		return
			[
				'status' => TRUE,
				'valorTotalCotacao' => $vlTtotalCotacao,
				'prodsOferta' => $arrProdsOferta,
				'prodsEspelho' => $objCotacao['produtos_fornecedor'],
				'xml' => $dom->saveXML()
			];
	}

	private function submitSintese($xml)
	{
		if ($this->configs['submitSintese']) {

			$myXml = trim(str_replace("<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>", "", $xml));
			/*
			 * Função disponibilizada pela Sintese para enviar o envelope via SOAP o XML da cotação.
			 * Responspavel: Jorge Cruz da Sintese.
			 */
			$envio = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
                       <soapenv:Header/>
                       <soapenv:Body>
                          <tem:EnviarOfertas>
                             <tem:xmlDoc>
                             ' . $myXml . '
                             </tem:xmlDoc>
                          </tem:EnviarOfertas>
                       </soapenv:Body>
                    </soapenv:Envelope>';

			$soapUrl = 'https://ws-sintese.bionexo.com/IntegrationService.asmx?WSDL';
			// xml post structure
			$headers = array(
				"Host: integracao.plataformasintese.com",
				"Content-type: text/xml;charset=\"utf-8\"",
				"Accept: text/xml",
				"Cache-Control: no-cache",
				"Pragma: no-cache",
				"SOAPAction: http://tempuri.org/EnviarOfertas",
				"Content-length: " . strlen($envio),
			); //SOAPAction: your op URL

			$url = $soapUrl;

			// PHP cURL  for https connection with auth
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			# curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword); // username and password - declared at the top of the doc
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $envio); // the SOAP request
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			// converting
			$response = strip_tags(curl_exec($ch));

			curl_close($ch);

			if (!strpos($response, 'incluídas')) {

				return
					[
						'status' => FALSE,
						'error' => $response
					];
			}

			return ['status' => TRUE];
		}
		return
			['status' => TRUE];
	}

	public function index()
	{
		if (!$this->configs['turnOn'])
			exit();

		$getFornecedores = $this->getFornecedores();

		if (!$getFornecedores['status'])
			exit();

		foreach ($getFornecedores['result'] as $fornecedor) {

			$this->logs = [];

			$getCotacoes = $this->Engine->getCotsFornecedor(
				[
					'db' => $this->sint,
					'configs' => $this->configs,
					'id_fornecedor' => intval($fornecedor['id'])
				]
			);

			if (!$getCotacoes['status'])
				continue;

			foreach ($getCotacoes['result'] as $cotacao) {

				$this->logs = [];

				$params =
					[
						'type' => $this->configs['integrador'],
						'id_fornecedor' => intval($fornecedor['id']),
						'cnpj_fornecedor' => $fornecedor['cnpj'],
						'margem_estoque' => $fornecedor['margem_estoque'],
						'id_cotacao' => intval($cotacao['id']),
						'cd_cotacao' => $cotacao['cd_cotacao'],
						'dt_inicio_cotacao' => $cotacao['dt_inicio_cotacao'],
						'dt_fim_cotacao' => $cotacao['dt_fim_cotacao'],
						'id_cliente' => intval($cotacao['id_cliente']),
						'cnpj_cliente' => mask($cotacao['cd_comprador'], '##.###.###/####-##'),
						'uf_cotacao' => $cotacao['uf_cotacao'],
					];

				$id_estado = $this->db->where('uf', $params['uf_cotacao'])
					->get('estados')
					->row_array()['id'];

				$params = array_merge($params, ['id_estado' => intval($id_estado)]);

				$configsFornecedor = $fornecedor['config'];

				if (!IS_NULL($configsFornecedor)) {

					$arrConfigsFornecedor = json_decode($configsFornecedor, true);

					$confValidade = isset($arrConfigsFornecedor['envia_validade']) ? boolval($arrConfigsFornecedor['envia_validade']) : false;

					$params = array_merge($params, ['confValidade' => $confValidade ]);

					if (isset($arrConfigsFornecedor['envia_revisada']) && !$arrConfigsFornecedor['envia_revisada']) {

						if ((boolval($cotacao['revisao']))) {

							$this->logs['MSG'] = "Cotacao revisada!";

							continue;
						}
					}
				}

				$configsEnvio = $this->Engine->getConfigsEnvio($params);

				if ($configsEnvio['status']) {

					$params = array_merge($params, ['obsFornecedor' => $configsEnvio['result']['observacao']]);
				}

				if (!$this->Engine->enabledAutomatic($params, $this->configs)) {

					$this->Engine->saveLogs($arr['CONFIGS-COT'] =
						[
							'enabledAutomatic' => FALSE
						], $params, $this->configs);

					continue;
				}

				if ($this->Engine->clientRestriction($params, $this->configs)) {

					$this->Engine->saveLogs($arr['CONFIGS-COT'] =
						[
							'clientRestriction' => TRUE
						], $params, $this->configs);

					continue;
				}

				$checkVlMinimo = $this->Engine->valorMinimo($params, $this->configs);

				if (!$checkVlMinimo['status']) {

					$this->Engine->saveLogs($arr['CONFIGS-COT'] =
						[
							'valorMinimo' => FALSE
						], $params, $this->configs);

					continue;
				}

				$checkFormaPagamento = $this->getFormaPagamento($params);

				if (!$checkFormaPagamento['status']) {

					$this->Engine->saveLogs($arr['CONFIGS-COT'] =
						[
							'formaPagamento' => FALSE
						], $params, $this->configs);

					continue;
				}

				$checkPrazoEntrega = $this->Engine->prazoEntrega($params, $this->configs);

				if (!$checkPrazoEntrega['status']) {

					$this->Engine->saveLogs($arr['CONFIGS-COT'] =
						[
							'prazoEntrega' => FALSE
						], $params, $this->configs);

					continue;
				}

				$params = array_merge($params,
					[
						'valor_minimo' => $checkVlMinimo['result']['valor_minimo'],
						'desconto_padrao' => floatval($checkVlMinimo['result']['desconto_padrao']),
						'forma_pagamento' => $checkFormaPagamento['result']['id_forma_pagamento'],
						'prazo_entrega' => $checkPrazoEntrega['result']['prazo_entrega']
					]);

				$getProdsCots = $this->getProdsCots($params);

				if (!$getProdsCots['status']) {

					$this->Engine->saveLogs($arr['CONFIGS-COT'] =
						[
							'produtosCotacao' => FALSE
						], $params, $this->configs);

					continue;
				}

				$dataCot = $this->prodsEncontrados($params, $getProdsCots['result']);

				if (empty($dataCot)) {

					$this->logs['MSG'] = "Nenhum produto ofertado!";

					$this->Engine->saveLogs($this->logs, $params, $this->configs);

					$this->logs = [];

					continue;
				}

				$createObject = $this->createObject($dataCot);

				if ($createObject['status']) {

					$this->logs['VALOR-TOTAL'] = $createObject['valorTotalCotacao'];

					$submitSintese = $this->submitSintese($createObject['xml']);

					if ($submitSintese['status']) {

						$this->Engine->saveProdsOferta($createObject['prodsOferta'], $this->configs);

						$params = array_merge($params, ['xml' => $createObject['xml']]);

						$this->Engine->saveLogs($this->logs, $params, $this->configs);

						$this->logs = [];

						$this->Engine->submitEmail(
							[
								'dadosCotacao' => $dataCot['dados'],
								'prodsEspelho' => $createObject['prodsEspelho']
							],
							$this->configs);

						continue;
					}

					$this->logs['MSG'] = "Erro de envio para Sintese - Error: {$submitSintese['error']}";

					$this->Engine->saveLogs($this->logs, $params, $this->configs);

					$this->logs = [];
				}
			}
		}
	}
}
