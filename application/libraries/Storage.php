<?php
Class Storage
{
    private  $base_url;
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->base_url = "http://10.101.70.3:8080/api/";
    }

   public function get($id_fornecedor, $cd_cotacao)
   {

       $data = [
           'cd_cotacao' => $cd_cotacao,
           'id_fornecedor' => $id_fornecedor,
       ];


       $curl = curl_init();

       curl_setopt_array($curl, array(
           CURLOPT_URL => $this->base_url . "cotacao/{$id_fornecedor}/$cd_cotacao",
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => '',
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 0,
           CURLOPT_FOLLOWLOCATION => true,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => 'GET',
           CURLOPT_HTTPHEADER => array(
               'Content-Type: application/json'
           ),
       ));

       $response = curl_exec($curl);

       curl_close($curl);


       return $response;
   }


}
