<?php

require APPPATH . 'libraries/REST_Controller.php';

class API_Controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

    }

}

class MY_Controller extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

        switch (MY_ENV) {

            case 'development':
                $this->db = $this->load->database('pharmahmg', true);
                break;

            case 'production':
                $this->db = $this->load->database('default', true);
                break;

            default:
                $this->db = $this->load->database('pharmahmg', true);
                break;
        }
    }

    protected function sendEmail($array)
    {
        $this->email->from($array['from'], $array['from-name']);
        $this->email->subject($array['assunto']);
        $this->email->reply_to("no-reply@pharmanexo.com.br");
        $this->email->to($array['destinatario']);

        isset($array['c_copia']) ? $this->email->cc($array['c_copia']) : FALSE;
        isset($array['copia_o']) ? $this->email->bcc($array['copia_o']) : FALSE;
        isset($array['anexo']) ? $this->email->attach($array['anexo']) : FALSE;

        $this->email->message($array['msg']);

        $return = $this->email->send();

        if (isset($array['anexo'])) {

            file_exists($array['anexo']) ? unlink($array['anexo']) : FALSE;
        }

        if ($return) {

            return $return;

        } else {

            return $this->email->print_debugger();
        }
    }
}

class MY_Controller_Auth extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

        $api_token = $this->_head_args['API_TOKEN'];
        $api_user = $this->_head_args['API_USER'];
        $api_db = $this->_head_args['API_AMBIENTE'];
        $db_dafault = $this->db = $this->load->database('default', true);

        if (isset($api_token) && isset($api_user)) {

            $token = $db_dafault->where('usuario', $api_user)
                ->where('hash', $api_token)
                ->where('situacao', 1)
                ->get('api_token')
                ->row_array();

            if (!empty($token)) {

                if ($api_db == 'TESTE') {

                    $this->db = $this->load->database('pharmanexo-antigo', true);

                } else if ($api_db == 'PRODUCAO') {

                    $this->db = $this->load->database('default', true);

                } else {

                    header('HTTP/1.0 401 Unauthorized');
                    exit();
                }

            } else {
                header('HTTP/1.0 401 Unauthorized');
                exit();
            }

        } else {
            header('HTTP/1.0 401 Unauthorized');
            exit();
        }
    }
}
