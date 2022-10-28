<?php

class Customers 
{

	public function __construct()
	{

		$this->CI = &get_instance();
	}

	/**
	 * Obtem objeto do comprador caso exista ou cadastra no BD
	 * 
	 * @param String cnpj
	 * @return  array
	 */
	public function checkComprador($cnpj)
	{
		# Verifica se o CNPJ ja existe
		$v = $this->verifyCNPJ($cnpj);

		if (!isset($v) || empty($v)) {

			# Se não existir comprador, cria via dados do site da receita
			$comprador = $this->create($cnpj);

			if ( $comprador != false ) {
			 	
			 	# Retorna o objeto do comprador
				return $comprador;
			} else {

				return [];
			}
		} else {

			# Retorna o objeto do comprador
			return $v;
		}
	}

	/**
	 * Cria o comprador via dados do site da receita, caso exista algum problema retorna false
	 * 
	 * @param String cnpj formatado
	 * @return  array/false
	 */
	public function create($cnpj)
	{
		try {

			$cnpjDesformatado = preg_replace("/[^0-9]/", "", $cnpj);

			$content = file_get_contents("https://www.receitaws.com.br/v1/cnpj/{$cnpjDesformatado}");

			$content = json_decode($content, true);

			if ( isset($content) && $content['status'] == "OK" ) {
				
				if ($content['situacao'] == 'ATIVA') {

					$cliente = [
						"cnpj" => $content['cnpj'],
						"razao_social" => $content['nome'],
						"nome_fantasia" => $content['fantasia'],
						"cep" => $content['cep'],
						"endereco" => $content['logradouro'],
						"numero" => $content['numero'],
						"bairro" => $content['bairro'],
						"cidade" => $content['municipio'],
						"estado" => $content['uf'],
						"complemento" => $content['complemento'],
						"telefone" => $content['telefone'],
						"email" => $content['email'],
						"complemento" => $content['complemento'],
					];

					if($this->CI->db->insert('compradores', $cliente)) {

						$cliente['id'] = $this->CI->db->insert_id();

						$this->createLog($cnpj, "O CNPJ {$cnpj} - {$cliente['razao_social']} foi cadastrado com sucesso.");

						return $cliente;
					} else {

						$this->createLog($cnpj, $this->CI->db->error()['message']);

						return false;
					}
				} else {

					# Registra o erro no log
					$this->createLog($cnpj, 'O CNPJ está inativo na receita federal, favor consultar.');

					return false;
				}
			} else {

				# Registra o erro no log
				$this->createLog($cnpj, 'CNPJ invalido!');

				return false;
			}
		} catch (Exception $ex) {
			
			var_dump($ex); exit();
		}
	}

	/**
	 * Verifica se existe comprador para o CNPJ informado no BD
	 *	 
	 * @param String - cnpj formatado
	 * @return  array
	 */
	private function verifyCNPJ($cnpj)
	{

		$this->CI->db->where('cnpj', $cnpj);

		return $this->CI->db->get('compradores')->row_array();
	}

	/**
	 * Verifica se existe log para o CNPJ informado no BD, se nao existir cria.
	 *	 
	 * @param String - cnpj formatado
	 * @param String - log
	 * @return  bool
	 */
	private function createLog($cnpj, $mensagem)
	{
		$this->CI->db->where('cnpj', $cnpj);
		$log = $this->CI->db->get('log_compradores')->row_array();

		if ( isset($log) && !empty($log) ) {

			$this->CI->db->insert('log_compradores', ['cnpj' => $cnpj, 'mensagem' => $mensagem]);
			
			return true;
		} else {

			return false;
		}
	}

	/**
	 * Formata o CNPJ ou CPF
	 *
	 * @param String cnpj
	 * @return  string
	 */
	private function formatCnpjCpf($value)
	{
		$cnpj_cpf = preg_replace("/\D/", '', $value);

		if (strlen($cnpj_cpf) === 11) {

			return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
		}

		return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
	}
}