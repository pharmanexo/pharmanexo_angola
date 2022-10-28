<?php

class M_email extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function sendEmail($params)
    {
        /**
         * Envia o e-mail.
         */

        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtplw.com.br',
            'smtp_port' => 587,
            'smtp_user' => 'pharmanexo',
            'smtp_pass' => 'AzqvIbuZ5038',
            'smtp_timeout' => 20,
            'validate' => true,
            'smtp_crypto' => false,
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => '\r\n',
            'wordwrap' => true,
            'bcc_batch_mode' => FALSE,
            'bcc_batch_size' => 200
        );


        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->set_crlf("\r\n");

        $this->email->initialize($config);

        $this->email->clear(true);

        $this->email->from($params['from'], $params['from-name']);
        $this->email->subject($params['assunto']);
        $this->email->reply_to("suporte@pharmanexo.com.br");
        $this->email->to($params['destinatario']);

        isset($params['c_copia']) ? $this->email->cc($params['c_copia']) : FALSE;
        isset($params['copia_o']) ? $this->email->bcc($params['copia_o']) : FALSE;
        isset($params['anexo']) ? $this->email->attach($params['anexo']) : FALSE;

        $this->email->message($params['msg']);

        $return = $this->email->send();

        if (isset($params['anexo'])) {

            file_exists($params['anexo']) ? unlink($params['anexo']) : FALSE;
        }

        if ($return) {

            return $return;

        } else {

            return $this->email->print_debugger();
        }

    }

}
