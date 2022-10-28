<?php

class UpdateCompradores extends CI_Controller
{

    private $sint;

    public function __construct()
    {
        parent::__construct();

        $this->sint = $this->load->database('sintese', true);

    }

    public function index()
    {
        $compradores = $this->db->query("select c.* from ocs_sintese cp
                                    join compradores c on c.id = cp.id_comprador
                                    where c.id = 15108
                                    group by c.cnpj")->result_array();


        foreach ($compradores as $comprador) {
            $dados = $this->getData($comprador);

            var_dump($dados);
            exit();

            if (!empty($dados['cep'])) {

                $data = [
                    'cep' => $dados['cep'],
                    'estado' => $dados['uf'],
                    'cidade' => $dados['municipio'],
                    'bairro' => $dados['bairro'],
                    'endereco' => $dados['logradouro'],
                    'numero' => $dados['numero'],
                    'complemento' => $dados['complemento'],
                    'email' => $dados['email'],
                    'telefone' => $dados['telefone'],
                ];

                $this->db->where('id', $comprador['id']);
                $this->db->update('compradores', $data);

            }else{
                $data = [
                    'visitado' => 1,
                ];

                $this->db->where('id', $comprador['id']);
                $this->db->update('compradores', $data);
            }
        }
    }

    private function getData($data)
    {
        $cnpj = preg_replace('/[^0-9]/', '', $data['cnpj']);
       // $cnpj = '27342155000162';
        $url = "https://ws.hubdodesenvolvedor.com.br/v2/cnpj2/?cnpj={$cnpj}&token=118488490NATmRKuEnt213927376";


        $data = json_decode(file_get_contents($url), 1);

        return $data;

    }

    /* private function getData($data)
     {
         // Endpoint da API Receita Federal
         $service_url = 'https://www.sintegraws.com.br/api/v1/execute-api.php';

         // Parâmetros utilizados na chamada da API
         $params = array(
             'token' => '099A37B9-AFF1-49B8-BAFB-2938E3655C6F',
             'cnpj' => $data['cnpj'],
             'plugin' => 'ST'
         );
         $service_url = $service_url . '?' . http_build_query($params);

         $curl = curl_init();
         curl_setopt_array($curl, array(
             CURLOPT_URL => $service_url,
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_TIMEOUT => 90,
             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
             CURLOPT_CUSTOMREQUEST => "GET",
         ));

         // Faz a chamada da API
         $response = curl_exec($curl);

         // Aqui fazemos o parse do JSON retornado
         $json = json_decode($response, true);

      /*   if (isset($json['status']) && $json['status'] == 'ERROR' ){
             var_dump($json);
             exit();
         }

         return $json;
     }*/
}
