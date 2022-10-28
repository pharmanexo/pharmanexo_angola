<?php
Class JWToken
{

    public function __construct()
    {
        $this->CI = &get_instance();

    }

    public function signature($senha)
    {
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];
        $header = json_encode($header);
        $header = base64_encode($header);

        $payload = [
            'iss' => 'pharmanexo.com.br',
            'name' => 'Pharmanexo',
            'email' => 'dev-security@pharmanexo.com.br'
        ];
        $payload = json_encode($payload);
        $payload = base64_encode($payload);

        $signature = hash_hmac('sha256',"$header.$payload", $senha,true);
        $signature = base64_encode($signature);

        return "$header.$payload.$signature";

    }

    public function assinar()
    {
        echo $this->signature();
    }
}