<?php
    class Auth extends MY_Controller
    {
        public function __construct()
        {
            parent:: __construct();
//           $this->db = $this->load->database('teste_pharmanexo', true);
        }

        public function index()
        {
            $this->output->set_content_type('application/json')->set_status_header(401)->set_output(json_encode(
                [
                    'status'=> 'error',
                    'message'=> 'Não autorizado'
                ]
            ));
        }

    }