<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//header('Content-Type: application/json;charset=ISO-8859-1');

class RequestApoio extends CI_Controller
{

	/**
	 * @author : Chule Cabral
	 * Data: 25/09/2020
	 */


	public function __construct()
	{
		parent::__construct();

		$this->bio = $this->load->database('apoio', true);

	}

	public function index()
	{
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

		$clientes = [];

		$getCotacao = $this->bio->where('catalogo', 0)
			->get('cotacoes')
			->result_array();

		if (TRUE) {
			foreach ($getCotacao as $keyCot => $item) {

				$updateCot = $this->bio->where('id', $item['id'])
					->set('catalogo', 1)
					->update('cotacoes');

				$id_cotacao = intval($item['id']);

				$getProdsCotacao = $this->bio->where('id_cotacao', $id_cotacao)
					->get('cotacoes_produtos')
					->result_array();

				if(empty($getProdsCotacao))
					continue;

				$getProdsCotacao = arrayFormat($getProdsCotacao);

				foreach ($getProdsCotacao as $prods) {

				    $clientes[] = $item['id_cliente'];

					$arrProds =
						[
							'id_cliente' => $item['id_cliente'],
							'codigo' => $prods['cd_produto_comprador'],
							'descricao' => $prods['ds_produto_comprador'],
							'id_unidade' => $prods['id_unidade'],
							'unidade' => $prods['ds_unidade_compra'],
							'id_categoria' => $prods['id_categoria'],
						];

					$checkProds = $this->bio->where('id_cliente', $arrProds['id_cliente'])
						->where('codigo', $arrProds['codigo'])
						->get('catalogo')
						->row_array();

					if (!IS_NULL($checkProds)) {
						continue;
					} else {

						$this->bio->insert('catalogo', $arrProds);

					}

				}
			}
		}

		foreach ($clientes as $cliente)
        {
            $checkIntegradores = $this->db
                ->where('id_cliente', $cliente)
                ->where('id_integrador', 3)
                ->get('compradores_integrador')
                ->result_array();

            if (empty($checkIntegradores))
                $this->db->insert('compradores_integrador', ['id_integrador' => 3, 'id_cliente' => $cliente]);
        }


	}

} // class

