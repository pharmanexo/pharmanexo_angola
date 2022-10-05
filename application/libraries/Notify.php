<?php
Class Notify
{

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function send($data)
    {

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
        
        $this->CI->load->library('email');
        $this->CI->email->set_newline("\r\n");
        $this->CI->email->set_crlf("\r\n");

        $this->CI->email->initialize($config);

        $this->CI->email->clear();
        $this->CI->email->from("suporte@pharmanexo.com.br", 'Portal Pharmanexo');
        $this->CI->email->to($data['to']);

        if (isset($data['cco']) && !empty($data['cco']) ) {
            $this->CI->email->bcc($data['cco']);
        }

        if (isset($data['oncoprod'])) {

            $file = base_url('/public/html/template_mail/notify_oncoprod.html');
            $template = file_get_contents($file);

            $body = str_replace(['%body%'], [$data['message']], $template);
        } else {

            $file = base_url('/public/html/template_mail/notify_tmp.html');
            $template = file_get_contents($file);

            $body = str_replace(['%body%', '%subject%', '%greeting%'], [$data['message'], $data['subject'], $data['greeting']], $template);
        }

        $this->CI->email->subject($data['subject']);

        if ( isset($data['attach']) && !empty($data['attach']) ) {
            $this->CI->email->attach($data['attach']);
        }

        $this->CI->email->message($body);

        $m = $this->CI->email->send();

        if ( $m ) {
            return true;
        } else {
            $m = $this->CI->email->send();
            if ( $m ) {
                return true;
            } else {
                $m = $this->CI->email->send();
                if ( $m ) {
                    return true;
                } else {
                    return false;
                }

                #var_dump( $this->CI->email->print_debugger(array('headers')) ); exit();
            }

            #var_dump( $this->CI->email->print_debugger(array('headers')) ); exit();
        }
    }

    public function alert($data)
    {

        if ( !isset($data['id_fornecedor']) || empty($data['id_fornecedor']) ) {

            $data['id_fornecedor'] = null;
        }

        if ( $this->CI->db->insert("notifications", $data) ) {

            return true;
        } else {

            return $this->CI->db->error()['message'];
        }
    }

    public function alertFornecedor($type, $id_usuario, $id_fornecedor, $message, $token = null, $url = null)
    {

        # Verifica se existe registro
        $this->CI->db->select("*");
        $this->CI->db->where('id_usuario', $id_usuario);
        $this->CI->db->where('id_fornecedor', $id_fornecedor);
        $this->CI->db->where('token', $token);
        $alert = $this->CI->db->get('notifications')->row_array();

        # Analisa se o registro existente tem mais de 7 dias
        if ( isset($alert) && !empty($alert) ) {

            $dataCriacao = date_create($alert['data_criacao']);
            $dataAtual = date_create(date('Y-m-d H:i:s'));
            $intervalo = date_diff($dataCriacao, $dataAtual);

            if ( intval($intervalo->format('%a')) > 7 ) {

                $create = 1;

                # Atualiza o registro para exibir somente 1 por modulo
                $this->CI->db->where("id", $alert['id'])->update("notifications", ['status' => 1, 'data_leitura' => date('Y-m-d H:i;s')]);
            }
        } else {

            $create = 1;
        }

        # Se a variavel existir, signifca que o registro pode ser criado
        if ( isset($create) ) {

            $alert = [
                "type" => $type,
                "id_usuario" => $id_usuario,
                "id_fornecedor" => $id_fornecedor,
                "message" => $message,
                "url" => $url,
                'token' => $token,
                'status' => 0
            ];

            return $this->CI->db->insert("notifications", $alert);
        }

        return false;
    }

    public function alertAdmin($type, $id_usuario, $message, $token = null, $url = null)
    {

        # Verifica se existe registro
        $this->CI->db->select("*");
        $this->CI->db->where('id_usuario', $id_usuario);
        $this->CI->db->where('id_fornecedor is null');
        $this->CI->db->where('token', $token);
        $alert = $this->CI->db->get('notifications')->row_array();

        # Analisa se o registro existente tem mais de 7 dias
        if ( isset($alert) && !empty($alert) ) {

            $dataCriacao = date_create($alert['data_criacao']);
            $dataAtual = date_create(date('Y-m-d H:i:s'));
            $intervalo = date_diff($dataCriacao, $dataAtual);

            if ( intval($intervalo->format('%a')) > 7 ) {

                $create = 1;

                # Atualiza o registro para exibir somente 1 por modulo
                $this->CI->db->where("id", $alert['id'])->update("notifications", ['status' => 1, 'data_leitura' => date('Y-m-d H:i;s')]);
            }
        } else {

            $create = 1;
        }

        # Se a variavel existir, signifca que o registro pode ser criado
        if ( isset($create) ) {

            $alert = [
                "type" => $type,
                "id_usuario" => $id_usuario,
                "id_fornecedor" => null,
                "message" => $message,
                "url" => $url,
                'token' => $token,
                'status' => 0
            ];

            return $this->CI->db->insert("notifications", $alert);
        }

        return false;
    }

    /**
     *  Obtem o texto padrao de uma ação executada na plataforma
     *
     * @param String tipo do alerta
     * @return array
     */
    public function formWarning($action)
    {
        switch ($action) {
            case 'create':
                $warning = ['type' => 'success', 'message' => notify_create];
                break;
            case 'update':
                $warning = ['type' => 'success', 'message' => notify_update];
                break;
            case 'delete':
                $warning = ['type' => 'success', 'message' => notify_delete];
                break;
        }

        return $warning;
    }

    public function alertMessage($modulo)
    {
        # Busca algum notificação ativa do modulo
        $this->CI->db->where("modulo", $modulo);
        $this->CI->db->where("ativo", 1);
        $this->CI->db->where("tipo", 0);
        $notify = $this->CI->db->get('modulo_notificacoes')->row_array();

        if ( isset($notify) && !empty($notify) ) {

            return $notify['mensagem'];
        } else {

            return '';
        }
    }

    public function automaticMessage($modulo)
    {
        # Busca algum notificação ativa do modulo
        $this->CI->db->where("modulo", $modulo);
        $this->CI->db->where("ativo", 1);
        $this->CI->db->where("tipo", 1);
        $notify = $this->CI->db->get('modulo_notificacoes')->row_array();

        if ( isset($notify) && !empty($notify) ) {

            return true;
        } else {

            return false;
        }
    }

    public function errorMessage()
    {
        return ['type' => 'warning', 'message' => notify_failed];
    }
}
