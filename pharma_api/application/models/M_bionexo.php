<?php

class M_bionexo extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getProdutoRespondidos($data)
    {
        if (isset($data['cd_cotacao'])) {
            $this->db->where('cd_cotacao', $data['cd_cotacao']);
        }

        if (isset($data['id_cliente'])) {
            $this->db->where('id_cliente', $data['id_cliente']);
        }

        if (isset($data['id_fornecedor'])) {
            $this->db->where('id_fornecedor', $data['id_fornecedor']);
        }

        return $this->db->get('cotacoes_produtos')->result_array();
    }

    public function getOC($data)
    {
        if (empty($data)) return false;

        if (isset($data['cd_ordem_compra'])) {
            $this->db->where('Cd_Ordem_Compra', $data['cd_ordem_compra']);
        }

        if (isset($data['id_cliente'])) {
            $this->db->where('id_comprador', $data['id_cliente']);
        }

        if (isset($data['id_fornecedor'])) {
            $this->db->where('id_fornecedor', $data['id_fornecedor']);
        }

        $r =$this->db->get('ocs_sintese')->row_array();

        return $r ;
    }


    public function getProdPed($data)
    {
        if (empty($data)) return false;

        if (isset($data['id_ordem_compra'])) {
            $this->db->where('id_ordem_compra', $data['id_ordem_compra']);
        }

        if (isset($data['id_confirmacao'])) {
            $this->db->where('id_confirmacao', $data['id_confirmacao']);
        }

        if (isset($data['id_artigo'])) {
            $this->db->where('Id_Produto_Sintese', $data['id_artigo']);
        }

        $r =$this->db->get('ocs_sintese_produtos')->result_array();

        return $r ;
    }

    public function insertCabecalho($data)
    {
        if (empty($data)) return false;
        return $this->db->insert('ocs_sintese', $data);
    }

    public function insertProds($data)
    {
        if (empty($data)) return false;
        return $this->db->insert_batch('ocs_sintese_produtos', $data);
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
