<?php

class Contato extends CI_Controller
{
    private $oncoprod = [12, 111, 112, 115, 120, 123];

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        $data['header'] = $this->template->homeheader([]);
        $data['navbar'] = $this->template->homenavbar([]);
        $data['banner'] = $this->template->homebanner([]);
        $data['scripts'] = $this->template->homescripts([]);
        $data['footer'] = $this->template->homefooter([]);

        $files = scandir('public/assets/marcas/');

        unset($files[0], $files[1]);

        $data['marcas'] = $files;
        $data['faq'] = $this->db->get('faq_questions')->result_array();

        /* $mes = date('Y-m', time());
         $data['totalCotacoesMes'] = $this->db
             ->select('count(distinct cot.cd_cotacao) as total')
             ->where("date_format(cot.data_cotacao, '%Y-%m') = '$mes'")
             ->get('cotacoes_produtos cot')
             ->row_array();
         $data['totalItensMes'] = $this->db
             ->select('count(cot.cd_cotacao) as total')
             ->where("date_format(cot.data_cotacao, '%Y-%m') = '{$mes}'")
             ->get('cotacoes_produtos cot')
             ->row_array();

         $data['totalEstoque'] = $this->views($this->getTotal()['total']);*/

        $this->load->view('home2', $data, FALSE);
    }

    public function sendMessage()
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $data = array('secret' => '6LcSlLkUAAAAACT-qSeWEd0nrNRzgYJaUqwHuZkR', 'response' => $post['g-recaptcha-response']);

            $options = array(
                'http' => array(
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method' => 'POST',
                    'content' => http_build_query($data)
                )
            );
            $context = stream_context_create($options);
            $response = file_get_contents($url, false, $context);
            $responseKeys = json_decode($response, true);
            header('Content-type: application/json');

            if ($responseKeys["success"]) {

                $score = $responseKeys['score'];

                if ($score > 0.5) {

                    unset($post['token']);

                    $data = date('d/m/Y H:i', time());
                    $message = "
            Olá, <br></br>
            Recebemos a solicitação de contato abaixo. <br><br>
            Nome: {$post['nome']} <br>
            Telefone: {$post['telefone']} <br>
            E-mail: {$post['email']} <br>
            Messagem: {$post['mensagem']} <br><br>
            Enviada em: {$data}
            ";


                    $sendError = $this->notify->send([
                        "to" => 'marlon.boecker@pharmanexo.com.br, administracao@pharmanexo.com.br, felipe.lima@pharmanexo.com.br',
                        "greeting" => "",
                        "subject" => "Solicitação de Contato do Site",
                        "message" => $message,
                        "oncoprod" => 1,
                    ]);

                    if ($sendError) {
                        $warning = [
                            'type' => 'success',
                            'message' => "Enviamos sua solicitação com sucesso, em breve alguém de nosso time entrará em contato"
                        ];
                    } else {
                        $warning = [
                            'type' => 'error',
                            'message' => "Houve um erro ao enviar sua mensagem, nos comunique por helpdesk@pharmanexo.com.br"
                        ];
                    }
                } else {
                    $warning = ['type' => 'error', 'message' => 'No Score'];
                }
            } else {
                $warning = ['type' => 'error', 'message' => 'No captch'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
    }
}
