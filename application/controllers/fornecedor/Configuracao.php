<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Configuracao extends MY_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('/fornecedor/configuracao/');
        $this->views = 'fornecedor/configuracao/';

        $this->load->model('m_usuarios', 'usuario');
        $this->load->model('m_compradores', 'cliente');
        $this->load->model('m_fornecedor', 'fornecedor');
    }

    public function index()
    {
        $page_title = "Configurações";

        $id_fornecedor = $this->session->id_fornecedor;

        $fornecedor = $this->fornecedor->findById($id_fornecedor);

        if (isset($fornecedor['logo']) && !empty($fornecedor['logo'])) {
            $logo = base_url("/public/fornecedores/{$id_fornecedor}/{$fornecedor['logo']}");

            $file_headers = @get_headers($logo);
            if (stripos($file_headers[0], "404 Not Found") > 0 || (stripos($file_headers[0], "302 Found") > 0 && stripos($file_headers[7], "404 Not Found") > 0)) {
                $logo = base_url("/images/usuarios/no-user.png");
            }
        } else {

            $logo = base_url("/images/usuarios/no-user.png");
        }

        $fornecedor['logo'] = $logo;
        $data = [
            'header' => $this->template->header(['title' => $page_title]),
            'navbar' => $this->template->navbar(),
            'sidebar' => $this->template->sidebar(),
            'heading' => $this->template->heading([
                'page_title' => $page_title,
                'buttons' => [

                    [
                        'type' => 'a',
                        'id' => 'btnVoltar',
                       'url' => "javascript:history.back(1)",
                        'class' => 'btn-secondary',
                        'icone' => 'fa-arrow-left',
                        'label' => 'Retornar'
                    ],
                ]
            ]),
            'scripts' => $this->template->scripts([
                    'scripts' => [
                        THIRD_PARTY . 'plugins/jquery.form.min.js',
                        THIRD_PARTY . 'plugins/jquery-validation-1.19.1/dist/jquery.validate.min.js'
                    ]
                ]
            ),
            'url_update' => "{$this->route}update",
            'url_update_emails' => "{$this->route}update_emails",
            'fornecedor' => $fornecedor,
            'modal_change_password' => "{$this->route}open_modal",
            'emails' => json_decode($fornecedor['emails_config'], true)
        ];


        $this->load->view("{$this->views}/main", $data);
    }

    public function new_password($id = null)
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();
            if ($post['senha'] === $post['c_senha']) {

                # a criptacao em MD5 é feita no model durante o UPDATE
                if ($this->usuario->update_password($post['id'], $post['c_senha'])) {
                    $warning = [
                        'type' => 'success',
                        'message' => 'Senha atualizada com sucesso.'
                    ];
                } else {
                    $warning = [
                        'type' => 'error',
                        'message' => 'Houve um erro ao atualizar a senha, tente novamente.'
                    ];
                }
            } else {
                $warning = [
                    'type' => 'error',
                    'message' => 'As senhas não conferem, tente novamente.'
                ];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));

        } else {
            $data['title'] = "Alterar Senha";
            $data['id'] = $id;
            $data['url_change_password'] = "{$this->route}new_password";

            $this->load->view("{$this->views}modal", $data);
        }
    }

    public function update()
    {
        $this->load->library('upload');

        $post = $this->input->post();

        $post['emails_config'] = null;

        if (isset($post['login']) && isset($post['password'])) {

            $post['credencial_bionexo'] = json_encode(['login' => $post['login'], 'password' => $post['password']]);
        }

        unset($post['login']);
        unset($post['password']);

        $config['upload_path'] = "public/fornecedores/{$this->session->id_fornecedor}";
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 100;
        $config['max_width'] = 1024;
        $config['max_height'] = 768;
        $config['encrypt_name'] = TRUE;


        if (!file_exists($config['upload_path'])) mkdir($config['upload_path'], 0777);

        $this->upload->initialize($config);

        if (isset($_FILES['imagem']['name']) && !empty($_FILES['imagem']['name'])) {
            if (!$this->upload->do_upload('imagem')) {

                $error = $this->upload->display_errors();
                $warning = ["type" => 'warning', "message" => $error];

                $this->session->set_userdata('warning', $warning);

                redirect($this->route);

            } else {
                $data = $this->upload->data();

                $post['logo'] = $data['file_name'];
            }
        }

        $result = $this->fornecedor->update($post);

        if ($result) {

            # Se cadastrou logo, atualiza a session para exibir imagem no perfil
            if (isset($post['logo']))
                $this->session->set_userdata('logo', $post['logo']);


            $warning = ["type" => 'success', "message" => notify_update];
        } else {
            $warning = ["type" => 'warning', "message" => $result['message']];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }

    public function update_emails()
    {

        $post = $this->input->post();



        $data['emails_config'] = json_encode($post);
        $data['id'] = $this->session->id_fornecedor;


        $result = $this->fornecedor->update($data);

        if ($result) {

            $warning = ["type" => 'success', "message" => notify_update];
        } else {
            $warning = ["type" => 'warning', "message" => $result['message']];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }


    public function check_phone_number($str)
    {
        $str = trim($str);

        if ($str == '') {
            return TRUE;
        } else {
            if (preg_match('/^\(\d{2}\) (9|)[\d{4}]\d{3}-\d{4}$/', $str)) {
                return TRUE;
            } else {
                $this->form_validation->set_message('check_phone_number', 'Telefone com formato inválido.');
                return FALSE;
            }
        }
    }

    public function importar()
    {

        $file = fopen('email_oncoprod.csv', 'r');

        $data = [];
        $semCliente = [];

        while (($line = fgetcsv($file, null, ';')) !== false) {

            $cliente = $this->cliente->find("*", "cnpj = '{$line[0]}'", true);

            if (strstr($line[2], "@")) {

                if (strstr($line[2], "(")) {

                    $primeiroParenteses = stripos($line[2], "(");

                    $segundoParenteses = stripos($line[2], ")");

                    $inicio = $primeiroParenteses + 1;
                    $fim = $segundoParenteses - $inicio;


                    $email = substr($line[2], $inicio, $fim);
                } else {
                    $consultor = $line[2];
                }

            } else {
                $consultor = null;
            }

            if (strstr($line[3], "@")) {

                $a = stripos($line[3], "(");

                $b = stripos($line[3], ")");

                $inicio = $a + 1;
                $fim = $b - $inicio;

                $email2 = substr($line[3], $inicio, $fim);

            } else {
                $consultor = null;
            }

            if (isset($cliente) && !empty($cliente)) {
                $data[] = [
                    'id_cliente' => $cliente['id'],
                    'consultor' => strtolower($email),
                    'gerente' => strtolower($email2),
                ];
            } else {
                $semCliente[] = ['cnpj' => $line[0]];
            }
        }

        // $this->db->insert_batch('email_notificacao', $data);

        // var_dump("Fim"); exit();

        var_dump($semCliente);
        exit();
    }
}

/* End of file: Configuracao.php */
