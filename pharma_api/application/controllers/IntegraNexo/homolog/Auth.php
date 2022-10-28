<?php

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent:: __construct();
        //   $this->load->library('JWToken');

    }

    public function index()
    {
        $this->output->set_content_type('application/json')->set_status_header(401)->set_output(json_encode(
            [
                'status' => 'error',
                'message' => 'Não autorizado'
            ]
        ));
    }

    public function crateToken()
    {
        $post = $this->input->post();

        if (isset($post['cnpj'])) {

            $forn = $this->db->where('cnpj', $post['cnpj'])->get('fornecedores')->row_array();
            if (empty($forn['api_token'])) {

                $token = $this->jwt->signature($forn['cnpj']);

                $this->db->where('id', $forn['id'])->update('fornecedores', ['api_token' => $token]);


                $this->output->set_content_type('application/json')->set_status_header(401)->set_output(json_encode(
                    [
                        'status' => 'success',
                        'message' => 'Token emitido: ' . $token
                    ]
                ));

            }

        }
    }
}