<?php

class Chamados extends MY_Controller
{

    private $route;
    private $views;
    private $client;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('/fornecedor/helpdesk/chamados');
        $this->views = 'fornecedor/helpdesk/chamados/';
        $this->client = 'https://pharmanexo.com.br/helpdesk';

        $this->load->model('m_bi', 'BI');
    }

    public function index()
    {
        $this->main();
    }

    public function insert()
    {
        if ($this->input->method() == 'post') {
            $fornecedor = $this->db->where('id', $this->session->id_fornecedor)->get('fornecedores')->row_array();
            $action = 'criar_chamado/';
            $post = $this->input->post();
            $post['id_departamento'] = '06fd855eabbdec93eb27003a1ca700f9';

            $campos['id_fornecedor'] = $this->session->id_fornecedor;
            $campos['id_solicitante'] = $this->session->id_usuario;
            $campos['id_status'] = 0;
            //   $post['campos'] = $campos;

            /* POST FILES */

            if (!empty($_FILES['anexos']['name'][0])) {
                $t = $this->upload_files(time(), $_FILES['anexos']);
            }


            if (!empty($t)) {
                foreach ($t as $k => $file) {

                    $post['anexos[' . $k . ']'] = curl_file_create(
                        realpath($file['full_path']),
                        mime_content_type($file['full_path']),
                        basename($file['full_path'])
                    );


                }
            }


            /* POST FILES */
            //$new_post_array = http_build_query($post);
            $data = $post;

            $r = $this->ticket->post($action, $data, $this->session->id_usuario);


            if ($r['erro'] == false) {
                $r['url'] = "{$this->route}/detalhes/{$r['id_chamado']}";
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($r));

        } else {
            $data = [];
            $data['form_action'] = "{$this->route}/insert";
            $data['title'] = "Novo Chamado";
            $data['categorias'] = $this->getCategories();

            $this->load->view($this->views . 'modal', $data);
        }


    }

    public function detalhes($id)
    {
        if (!isset($id)) redirect($this->route);

        $modulo = "chamado/";

        $busca = $this->ticket->get($modulo, $id);


        if ($busca['erro'] == true) {
            $array = array(
                'type' => 'warning',
                'message' => 'Registro não encontrado'
            );

            $this->session->set_userdata('warning', $array);
            redirect($this->route);
        }

        $chamado = $busca['data'];


        /* var_dump($chamado);
         exit();*/

        $page_title = "#{$chamado['protocolo']} - {$chamado['titulo']}";

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

        $data['urlHistorico'] = "{$this->route}/getHistoricos/{$id}";
        $data['formAction'] = "{$this->route}/responder";

        $this->load->view($this->views . 'detalhes', $data, FALSE);

    }

    public function responder($post = null, $array = false)
    {
        $data = (isset($post)) ? $post : $this->input->post();

        /*  $post['id_usuario'] = $this->session->id_usuario;
          $post['nivel'] = '2';
          $post['email'] = $this->session->email;*/

        $idchamado = $data['id_chamado'];
        unset($post['id_chamado']);


        if (!empty($_FILES['anexos']['name'][0])) {
            $t = $this->upload_files(time(), $_FILES['anexos']);
        }


        if (!empty($t)) {
            foreach ($t as $k => $file) {
                $data['anexos[' . $k . ']'] = curl_file_create(
                    realpath($file['full_path']),
                    mime_content_type($file['full_path']),
                    basename($file['full_path'])
                );
            }
        }

        $action = 'chamado/';

        $r = $this->ticket->reply($idchamado, $data);


        if ($array) {
            return $r;
        } else {
            $this->output->set_content_type('application/json')->set_output(json_encode($r));
        }


    }

    public function getChamados()
    {
        date_default_timezone_set('UTC');
        $post = $this->input->post();

        $data = [
            'idcliente' => $this->session->id_usuario
        ];

        if (isset($post['situacao']) && !empty($post['situacao'])) {
            $data['situacao'] = $post['situacao'];
        }

        if (isset($post['dataini']) && !empty($post['dataini'])) {
            $data['last_creation_upper'] = strtotime($post['dataini']);
        }

        if (isset($post['datafim']) && !empty($post['datafim'])) {
            $data['last_creation_lesser'] = strtotime($post['datafim']);
        }

        $data = $this->ticket->getChamados(1, $data);

        if ($data['erro'] == false) {
            $chamados = $data['data'];

            foreach ($data['data'] as $k => $dt) {
                $data['data'][$k]['prioridadedesc'] = prioridadeTicket($dt['prioridade']);
            }

        } else {
            $data = [];
        }


        $this->output->set_content_type('application/json')->set_output(json_encode($data));

    }

    public function dadosChamados($id, $array = false)
    {
        $id = base64url_decode($id);
        $post['id_fornecedor'] = $this->getFornecedor();
        $post['idChamado'] = $id;

        $data = $this->exec("{$this->client}/tickets", $post);

        if ($array) {
            return $data;
        } else {
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

    }

    public function getHistoricos($id)
    {
        $post['idChamado'] = base64url_decode($id);

        $data = $this->exec("{$this->client}/tickets/getHistory", $post);

        foreach ($data['data'] as $key => $item) {

            $data['data'][$key]['dt_criacao'] = date("d/m/Y H:i", strtotime($item['dt_criacao']));

        }


        $this->output->set_content_type('application/json')->set_output(json_encode($data));

    }


    public function create_cliente()
    {
        $action = 'criar_cliente/';

        $usuarios = $this->db->get('usuarios')->result_array();

        foreach ($usuarios as $usuario) {

            $data = [
                'nome' => $usuario['nome'],
                'identificador' => $usuario['id'],
                'criarchamados' => true
            ];

            $r = $this->ticket->post($action, $data);


        }


        $warning = [
            'type' => 'success',
            'message' => "Cliente cadastrado com sucesso"
        ];


        if ($r['erro'] == true) {
            $warning = [
                'type' => 'error',
                'message' => $r['mensagem']
            ];
        }

        //   $this->output->set_content_type('application/json')->set_output(json_encode($warning));

    }

    private function getCategories()
    {
        $action = 'departamentos/';


        $r = $this->ticket->get($action);

        if (isset($r['data'][0]['categorias'])) {
            return $r['data'][0]['categorias'];
        } else {
            return [];
        }


    }


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
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnInsert',
                    'url' => "{$this->route}/insert",
                    'class' => 'btn-primary',
                    'icone' => 'fa-plus',
                    'label' => 'Abrir novo Chamado'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'theme/plugins/flatpickr/flatpickr.min.js',
                'https://npmcdn.com/flatpickr/dist/l10n/pt.js'
            ]
        ]);

        $data['urlGetChamado'] = "{$this->route}/getChamados";

        $this->load->view($this->views . 'main', $data, FALSE);
    }

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

        var_dump($response);
        exit();

        return json_decode($response, true);
    }

    private function getFornecedor()
    {
        $post_id = false;
        if ($this->session->has_userdata('id_matriz') && !empty($post['id_fornecedor'])) {

            $post_id = true;
        }
        $matriz = $this->BI->matrizFilial(FALSE, $post_id, $this->session->id_fornecedor);

        foreach ($matriz as $key => $item) {
            $fornecedor = $item;
        }

        if (empty($matriz)) {
            return $this->session->id_fornecedor;
        } else {
            return $fornecedor;
        }
    }

    private function upload_files($title, $files)
    {
        $config = array(
            'upload_path' => realpath(APPPATH . '../uploads/tickets'),
            'allowed_types' => 'jpg|gif|png|jpge|doc|docx|pdf',
            'overwrite' => 1,
        );


        $this->load->library('upload', $config);

        $anexos = [];

        foreach ($files['name'] as $key => $image) {
            $_FILES['images']['name'] = $files['name'][$key];
            $_FILES['images']['type'] = $files['type'][$key];
            $_FILES['images']['tmp_name'] = $files['tmp_name'][$key];
            $_FILES['images']['error'] = $files['error'][$key];
            $_FILES['images']['size'] = $files['size'][$key];

            $config['file_name'] = $title . '_' . $image;

            $this->upload->initialize($config);

            if ($this->upload->do_upload('images')) {
                $anexos[] = $this->upload->data();
            } else {
                var_dump($this->upload->display_errors());
                exit();
            }
        }

        return $anexos;


    }
}