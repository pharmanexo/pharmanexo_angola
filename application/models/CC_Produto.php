<?php

class CC_Produto extends MY_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->table = 'produtos';
        $this->db_ades = $this->load->database('adesao', true);
		
	}

    /**
     * insert
     *
     * @param   array       $data
     * @return  integer
     */
    public function insert($data)
    {
        $this->db_ades->insert($this->table, $data);
        return $this->db_ades->insert_id();
    }

    /**
     * update
     *
     * @param   array       $data
     * @return  integer
     */
    public function update($data)
    {
        if (!isset($data[$this->primary_key])) {
            return false;
        }

        $filter = $this->primary_filter;
        $id = $filter($data[$this->primary_key]);

        unset($data[$this->primary_key]);

        $this->db_ades->where($this->primary_key, $id);
        $this->db_ades->update($this->table, $data);

        return $id;
    }

	public function getPreco($idProd = null, $qtd = null)
	{

		$preco = [];
		$produto = $this->db_ades->where('id', $idProd)->get('produtos')->row_array();

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
