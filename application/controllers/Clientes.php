<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes extends CI_Controller {

	public function index()
	{
		$clientes = $this->db->select('id, cnpj')->where("cep", "")->get('compradores')->result_array();

		foreach ($clientes as $cliente){
		    $cnpj = preg_replace("/\D+/", "", $cliente['cnpj']);

            // Iniciamos a função do CURL:
            $ch = curl_init("https://www.receitaws.com.br/v1/cnpj/{$cnpj}");

            curl_setopt_array($ch, [

                // Equivalente ao -X:
                CURLOPT_CUSTOMREQUEST => 'GET',

                // Permite obter o resultado
                CURLOPT_RETURNTRANSFER => 1,
            ]);

            $resposta = json_decode(curl_exec($ch), true);

            $endereco = [
                "endereco" => $resposta['logradouro'],
                "bairro" => $resposta['bairro'],
                "cidade" => $resposta['municipio'],
                "numero" => $resposta['numero'],
                "cep" => $resposta['cep'],
                "complemento" => $resposta['complemento'],
                "telefone" => $resposta['telefone'],
                "email" => $resposta['email'],
            ];

            $this->db->where("id", $cliente['id']);
            $this->db->update('compradores', $endereco);

        }



	}

}

/* End of file Controllername.php */