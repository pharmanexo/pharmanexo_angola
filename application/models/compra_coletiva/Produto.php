<?php

class Produto extends MY_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->table = 'produtos';
        $this->db = $this->load->database('adesao', true);
	}

	public function getPreco($idProd = null, $qtd = null)
	{

		$preco = [];
		$produto = $this->db->where('id', $idProd)->get('produtos')->row_array();

		if ($qtd <= 500) {
			$preco = [
				'precoFormatado' => number_format($produto['valor'], '2', ',', '.'),
				'valor' => $produto['valor']
			];
		} else if ($qtd > 500) {
			$preco = [
				'precoFormatado' => number_format($produto['preco_500'], '2', ',', '.'),
				'valor' => $produto['preco_500']
			];
		} else if ($qtd > 1000) {
			$preco = [
				'precoFormatado' => number_format($produto['preco_1000'], '2', ',', '.'),
				'valor' => $produto['preco_1000']
			];
		} else if ($qtd > 2000) {
			$preco = [
				'precoFormatado' => number_format($produto['preco_2000'], '2', ',', '.'),
				'valor' => $produto['preco_2000']
			];
		} else if ($qtd > 5000) {
			$preco = [
				'precoFormatado' => number_format($produto['preco_5000'], '2', ',', '.'),
				'valor' => $produto['preco_5000']
			];
		} else if ($qtd > 10000) {
			$preco = [
				'precoFormatado' => number_format($produto['preco_10000'], '2', ',', '.'),
				'valor' => $produto['preco_10000']
			];
		}

		return $preco;
	}

}
