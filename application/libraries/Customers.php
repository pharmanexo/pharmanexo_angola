<?php

class Customers{

	public function __construct()
	{
		$this->CI = &get_instance();
	}

	public function create($cnpj){

	    $cnpj = preg_replace("/[^0-9]/", "", $cnpj);

        $content = file_get_contents("https://www.receitaws.com.br/v1/cnpj/{$cnpj}");

        #verifica se o CNPJ ja existe
        $v = $this->verifyCNPJ($cnpj);
        if (!isset($v) || empty($v)){

            #converte em array
            $content = json_decode($content, true);


            #verifica situação ativa
            if ($content['situacao'] == 'ATIVA'){
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

                if($this->CI->db->insert('compradores', $cliente)){

                    $data = $this->CI->db->select('*')->where('id', $this->CI->db->insert_id())->get('compradores')->row_array();

                    return [
                        'type' => 'success',
                        'message' => "O CNPJ {$cnpj} - {$content['nome']} foi cadastrado com sucesso",
                        'data' => $data
                    ];
                }else{
                    return [
                        'type' => 'error',
                        'message' => 'Houve um erro ao inserir o CNPJ no banco de dados'
                    ];
                }

            }else{

                return [
                    'type' => 'error',
                    'message' => 'O CNPJ está inativo na receita federal, favor consultar.'
                ];

            }


        }else{

            return [
                'type' => 'error',
                'message' => 'O CNPJ informado já está cadastrado.'
            ];


        }


	}

	private function verifyCNPJ($cnpj){

	    $cnpj = $this->formatCnpjCpf($cnpj);

	    $this->CI->db->where('cnpj', $cnpj);
	    return $this->CI->db->get('compradores')->row_array();


    }

    private  function formatCnpjCpf($value)
    {
        $cnpj_cpf = preg_replace("/\D/", '', $value);

        if (strlen($cnpj_cpf) === 11) {
            return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
        }

        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
    }


}
