<?php

class Chamados extends MY_Controller
{

    private $route;
    private $views;
    private $client;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('/admin/helpdesk/chamados');
        $this->views = 'admin/helpdesk/chamados/';
        $this->client = 'http://177.39.233.108/helpdesk';

        $this->load->model('m_fornecedor', 'fornecedor');
    }

    /**
     * Carrega a pagina principal com todos os chamados em aberto
     */
    public function index()
    {
        $this->main();
    }

    /**
     * Abre os detalhes do chamado filtrado a partir do ID informado na ROTA
     */
    public function detalhes($id)
    {
        if (!isset($id)) redirect($this->route);

        $busca = $this->dadosChamados($id, true);

        $chamado = $busca['data'];
        $page_title = "#{$chamado['id']} - {$chamado['assunto']}";

        if ($busca['count'] == '0') {
            $array = array(
                'type' => 'warning',
                'message' => 'Registro não encontrado'
            );

            $this->session->set_userdata('warning', $array);
            redirect($this->route);
        }

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'styles' => [
                THIRD_PARTY . 'theme/css/timeline.css',
                'https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css',
            ]
        ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
        ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                'https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js',

            ]
        ]);
        $data['chamado'] = $chamado;
        $data['status'] = $this->exec("{$this->client}/tickets/getAllStatus")['data'];

        $data['urlHistorico'] = "{$this->route}/getHistoricos/{$id}";
        $data['urlUpdStts'] = "{$this->route}/setStatus/";
        $data['formAction'] = "{$this->route}/responder";

        $this->load->view($this->views . 'detalhes', $data, FALSE);
    }

    /**
     * Insere a resposta no banco de dados via API
     */
    public function responder()
    {
        $post = $this->input->post();

        $post['id_analista'] = $this->session->id_usuario;
        $post['nivel'] = '1';
        $files = [];

        if (!empty($_FILES['anexos']['tmp_name'][0])) {
            foreach ($_FILES['anexos']['tmp_name'] as $k => $file) {

                $post['anexos[' . $k . ']'] = curl_file_create(
                    realpath($file),
                    mime_content_type($file),
                    basename($file)
                );
            }
        }


        $result = $this->exec("{$this->client}/tickets/reply", $post, $files);

        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function setStatus()
    {
        if ($this->input->is_ajax_request()){
            $post = $this->input->post();

            if (isset($post['chamado'])) $data['id_chamado'] = $post['chamado'];
            if (isset($post['id_status'])) $data['id_status'] = $post['id_status'];

            $result = $this->exec("{$this->client}/tickets/updateStatus", $data);

            $this->output->set_content_type('application/json')->set_output(json_encode($result));
        }

    }

    /**
     * Captura todos os chamados na API
     */
    public function getChamados()
    {
        $post = $this->input->post();

        $data = $this->exec("{$this->client}/tickets", $post);

        if (!is_null($data)) {
            foreach ($data['data'] as $k => $item) {
                $id = base64url_encode($item['id']);
                $data['data'][$k]['url'] = "{$this->route}/detalhes/{$id}";
            }
        }else{
            $data = [];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Captura os dados de um chamado a partir do ID
     */
    public function dadosChamados($id, $array = false)
    {
        $id = base64url_decode($id);
        $post['idChamado'] = $id;

        $data = $this->exec("{$this->client}/tickets", $post);

        if ($array) {
            return $data;
        } else {
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    /**
     * Captura todos as respostado do chamado a partir do ID passado na Rota
     */
    public function getHistoricos($id)
    {
        $post['idChamado'] = base64url_decode($id);

        $data = $this->exec("{$this->client}/tickets/getHistory", $post);

        foreach ($data['data'] as $key => $item) {

            $data['data'][$key]['dt_criacao'] = date("d/m/Y H:i", strtotime($item['dt_criacao']));

        }


        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Função privada que carrega a visualização da tela principal
     */
    private function main()
    {
        $page_title = "Chamados de Suporte";

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'styles' => [
                THIRD_PARTY . 'theme/plugins/flatpickr/flatpickr.min.css'
            ]
        ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
        ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'theme/plugins/flatpickr/flatpickr.min.js',
                'https://npmcdn.com/flatpickr/dist/l10n/pt.js'
            ]
        ]);
        $data['fornecedores'] = $this->fornecedor->find("id, nome_fantasia", null, false, 'nome_fantasia ASC');

        $data['urlGetChamado'] = "{$this->route}/getChamados";

        $this->load->view($this->views . 'main', $data, FALSE);
    }

    /**
     * Função privada que chama as rotas da API
     */
    private function exec($url, $post = null, $files = null)
    {
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


        return $data = json_decode($response, true);
    }
}