<?php

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent:: __construct();
        // $this->db = $this->load->database('teste_pharmanexo', true);
        unset($_SESSION['id_usuario'], $_SESSION['id_fornecedor'], $_SESSION['cnpj'], $_SESSION['auth']);
        $headers = getallheaders();
        $token = (isset($headers['API_TOKEN'])) ? $headers['API_TOKEN'] : '';

        $arrayInfos = [];

        if (isset($headers['cnpj']) && !empty($headers['cnpj'])) {
            $resultDB = $this->db->where('cnpj', $headers['cnpj'])->get('fornecedores')->row_array();
        }else{
            $resultDB = null;
        }

        if (is_null($resultDB) || $token != $resultDB['api_token']) {

            $arrayInfos['auth'] = false;
            header('HTTP/1.0 401 Unauthorized');

            $log = [
                'origem' => $this->get_client_ip(),
                'dispositivo' => $_SERVER['HTTP_USER_AGENT'],
                'data_acesso' => date('Y-m-d H:i:s'),
                'dados' => json_encode(array_merge($arrayInfos, $headers))
            ];

            $this->db->insert('api_log', $log);

            die();
        } else {
            $arrayInfos = [
                'id_fornecedor' => $resultDB['id'],
                'cnpj' => $resultDB['cnpj'],
                'auth' => true
            ];

            $log = [
                'origem' => $this->get_client_ip(),
                'dispositivo' => $_SERVER['HTTP_USER_AGENT'],
                'data_acesso' => date('Y-m-d H:i:s'),
                'dados' => json_encode(array_merge($arrayInfos, $headers))
            ];

            $this->db->insert('api_log', $log);


        }
        $this->session->set_userdata($arrayInfos);
    }

    function get_client_ip()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

}