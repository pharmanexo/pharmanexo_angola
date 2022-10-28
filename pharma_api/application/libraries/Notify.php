<?php
Class Notify
{

    public function __construct()
    {
        $this->CI = &get_instance();


    }

    public function send($data){

        return true;

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

        $this->CI->email->clear(true);

        $this->CI->email->from("suporte@pharmanexo.com.br", 'Portal Pharmanexo');
        $this->CI->email->to($data['to']);

//        $file = base_url('notify_tmp.html');

		$file = "http://" . $_SERVER['HTTP_HOST'] . '/pharma_api/notify_tmp.html';

        $template = file_get_contents($file);

        $body = str_replace(['%body%', '%subject%', '%greeting%'], [$data['message'], $data['subject'], $data['greeting']], $template);

        $this->CI->email->subject($data['subject']);
        $this->CI->email->message($body);

        if (isset($data['anexos'])){
            if (is_array($data['anexos'])){
                foreach ($data['anexos'] as $anexo){
                    $this->CI->email->attach($anexo);
                }
            }
        }

        $send = $this->CI->email->send(false);

        if (!$send){
        	#var_dump($this->CI->email->print_debugger());
        	#exit();
            return $send;
		}else{
        	return $send;
		}


    }

    public function alert($data){

        if (isset($data['id_usuario']) && isset($data['id_fornecedor']) && isset($data['message'])){
            if (!empty($data['id_usuario']) && !empty($data['id_fornecedor']) && !empty($data['message'])){

                $this->CI->db->insert("notifications", $data);

            }
        } else {

            $this->CI->db->insert("notifications", $data);
        }

    }

    public function sendSMS($number, $msg){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.smsdev.com.br/send?key=BG9I6PU42E5497829TQXVZRY&type=9&number={$number}}&msg=".urlencode("{$msg}"),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return false;
        } else {
            return true;
        }
    }

}
