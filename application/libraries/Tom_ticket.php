<?php

class Tom_ticket
{
    private $base_url;
    private $token;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->base_url = "https://api.tomticket.com/";
        $this->token = 'b5f9e9a791cc0701a91446e34dba8863';

    }


    public function get($action, $cliente = null)
    {

        if (!is_null($cliente)) {
            $url = $this->base_url . $action . $this->token . "/{$cliente}";
        } else {
            $url = $this->base_url . $action . $this->token;
        }


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        if (isset($post)) {
            curl_setopt($ch, CURLOPT_POST, 1);                //0 for a get request
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        $response = curl_exec($ch);

        return json_decode($response, true);

    }

    public function getChamados($page, $params)
    {

        $url = $this->base_url . "chamados/" . $this->token . "/{$page}" . "?" . http_build_query($params);


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        if (isset($post)) {
            curl_setopt($ch, CURLOPT_POST, 1);                //0 for a get request
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        $response = curl_exec($ch);

        return json_decode($response, true);

    }

    public function post($action, $post, $cliente = null)
    {
        if (!is_null($cliente)) {
            $url = $this->base_url . $action . $this->token . "/{$cliente}";
        } else {
            $url = $this->base_url . $action . $this->token;
        }

        //  $post = http_build_query($post);


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);


        curl_setopt($ch, CURLOPT_POST, 1);                //0 for a get request
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);


        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        $response = curl_exec($ch);


        return json_decode($response, true);

    }

    public function reply($idchamado, $post)
    {

        $url = $this->base_url . 'chamado/' . $this->token . "/{$idchamado}/responder";
        //  $post = http_build_query($post);


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);


        curl_setopt($ch, CURLOPT_POST, 1);                //0 for a get request
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);


        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        $response = curl_exec($ch);


        return json_decode($response, true);

    }


}
