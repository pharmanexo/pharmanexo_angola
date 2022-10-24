<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios extends Admin_controller
{
    private $route, $views;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('admin/usuarios');
        $this->views = "admin/usuarios/";
        $this->load->model('m_usuarios', 'usuario');
        $this->load->model('m_fornecedor', 'fornecedor');
    }

    /**
     * Exibe a view admin/usuarios/main.php
     *
     * @return  view
     */
    public function index()
    {
        $page_title = "Usuarios";
        $data['datasource'] = "{$this->route}/datatables";
        $data['url_update'] = "{$this->route}/atualizar";
        $data['url_new_password'] = "{$this->route}/new_password/";
        $data['url_bloqueio'] = "{$this->route}/blockUser/";
        $data['url_delete_multiple'] = "{$this->route}/delete_multiple/";
        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'button',
                    'id' => 'btnDeleteMultiple',
                    'url' => "",
                    'class' => 'btn-danger',
                    'icone' => 'fa-trash',
                    'label' => 'Excluir Selecionados'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel',
                    'label' => 'Exportar Excel'
                ],
                [
                    'type' => 'a',
                    'id' => 'btnInsert',
                    'url' => "{$this->route}/criar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-plus',
                    'label' => 'Novo Registro'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Exibe o datatables de usuarios
     *
     * @return  json
     */
    public function datatables()
    {
        $datatables = $this->datatable->exec(
            $this->input->post(),
            'usuarios',
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'nickname', 'dt' => 'nome'],
                ['db' => 'email', 'dt' => 'email'],
                ['db' => 'telefone', 'dt' => 'telefone'],
                ['db' => 'situacao', 'dt' => 'situacao'],
                ['db' => 'situacao', 'dt' => 'situacao_lbl', "formatter" => function ($d) {
                    return ($d == 1) ? 'ATIVO' : 'INATIVO';
                }],
            ]
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    public function criar()
    {
        if ($this->input->is_ajax_request()) {

            $post = $this->input->post();
            $password = generatePassword();

            $this->form_validation->set_error_delimiters('<span>', '</span>');
            $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email|is_unique[usuarios.email]');
            //    $this->form_validation->set_rules('cpf', 'CPF', 'required|is_unique[usuarios.cpf]');

            if ($this->form_validation->run() === false) {

                $errors = [];

                foreach ($post as $key => $value) {

                    $errors[$key] = form_error($key);
                }
//                $verificaCpf = null;
                # Retorna com a lista de erros da validação
                $warning = ['type' => 'warning', 'message' => array_filter($errors)];

            } else {

                $keys = explode(' ', $post['nome']);
                $nickname = $keys[0];
                $novoNome = '';

                foreach ($keys as $key) {
                    $key = strtoupper($key);

                    if ($key != 'DE' && $key != 'DOS' && $key != 'DA') {
                        $letra = substr($key, 0, 1);
                        if ($letra != '(') {
                            $novoNome .= $letra . ".";
                        }

                    }
                }
                $post['nickname'] = $nickname;
                $post['nome'] = $novoNome;


                $data = [
                    'nome' => $post['nome'],
                    'email' => $post['email'],
                    'senha' => password_hash($password, PASSWORD_DEFAULT),
                    'telefone' => $post['telefone'],
                    'celular' => $post['celular'],
                    'nivel' => $post['nivel'],
                    'login_fe' => isset($post['login_fe']) ? 1 : 0,
                    'nickname' => $post['nickname']
                ];

                # Determina o perfil do usuario ( futuramente a coluna administrador irá mudar para perfil)
                if ($post['perfil'] == 1) {

                    # Administrador
                    $data['administrador'] = 1;
                    $data['tipo_usuario'] = 0;
                } elseif ($post['perfil'] == 2) {

                    # Fornecedor
                    $data['administrador'] = 0;
                    $data['tipo_usuario'] = 1;
                    # Armazena os fornecedores do usuario
                    $conexao = [];
                    foreach ($post['fornecedores'] as $fornecedor) {
                        $conexao[] = [
                            'id_usuario' => '',
                            'id_fornecedor' => $fornecedor,
                            'tipo' => $post['nivel']
                        ];
                    }
                } else {

                    # Representante...
                    $data['administrador'] = 0;
                    $data['tipo_usuario'] = 1;
                }

                $this->db->trans_begin();

                # Registra o basico do usuário
                $this->db->insert('usuarios', $data);
                $id = $this->db->insert_id();

                # Verifica se existe conexões com fornecedores
                if (isset($conexao) && !empty($conexao)) {

                    # Registra a conexão do usuario com os fornecedores selecionados
                    foreach ($conexao as $kk => $row) {

                        $conexao[$kk]['id_usuario'] = $id;
                    }

                    $this->db->insert_batch('usuarios_fornecedores', $conexao);
                }

                # Armazena a foto do usuario
                $this->insert_photo($id);

                if ($this->db->trans_status() === FALSE) {

                    $this->db->trans_rollback();

                    $warning = $this->notify->errorMessage();
                } else {

                    $this->db->trans_commit();

                    # Envia email para com a nova senha
                    $notify = [
                        "to" => $post['email'],
                        "cco" => 'marlon.boecker@pharmanexo.com.br',
                        "greeting" => $post['nome'],
                        "subject" => "Bem-vindo ao Portal Pharmanexo",
                        "message" => "Seu usuário pharmanexo foi criado utilize os dados abaixo para acessar o portal.
                                        <br><br> 
                                        <p style='text-align: center'>
                                        <strong>Login:</strong> {$post['email']} <br> 
                                        <strong>Senha: </strong> {$password}<br><br>
                                        
                                        <a href='https://pharmanexo.com.br/pharmanexo_v2' style='padding: 15px; border-radius: 5px; text-decoration: none; background-color: #d2d2d2; color: #204a8f'>ACESSE O PORTAL PHARMANEXO</a>
                                        </p> 
                                        <br> 
                                        <br> 
                                        Visando a segurança das informações, aconselhamos a troca de senha após o primeiro login. <br><br> 
                                        Atenciosamente, <br><br> Equipe Pharmanexo"
                    ];

                    $send = $this->notify->send($notify);

                    $warning = ['type' => 'success', 'message' => notify_create];
                }
            }

            if (isset($warning)) {
                $this->output->set_content_type('application/json')->set_output(json_encode($warning));
            }
        } else {

            $this->form();
        }
    }

    /**
     * Função que atualiza o usuario
     *
     * @param int $id
     * @return  json
     */
    public function atualizar($id)
    {
        if ($this->input->method() == 'post') {

            # Obtem o request do form
            $post = $this->input->post();

            # Validação de campos
            $this->form_validation->set_error_delimiters('<span>', '</span>');
            $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email|max_length[255]|callback_check_unique_email');

            # Verifica se a validação deu errado
            if ($this->form_validation->run() === false) {

                $errors = [];

                foreach ($post as $key => $value) {

                    $errors[$key] = form_error($key);
                }

                // Retorna com a lista de erros da validação
                $warning = ['type' => 'warning', 'message' => array_filter($errors)];

                $this->output->set_content_type('application/json')->set_output(json_encode($warning));
            } else {

                $usuario = $this->db->where('id', $id)->get('usuarios')->row_array();

                $data = [
                    'nome' => $post['nome'],
                    'telefone' => $post['telefone'],
                    'celular' => $post['celular'],
                    'cpf' => $post['cpf'],
                    'rg' => $post['rg'],
                    'nivel' => $post['nivel'],
                    'login_fe' => isset($post['login_fe']) ? 1 : 0
                ];

                # Só altera e-mail se for diferente do existente
                if ($post['email'] == $usuario['email']) {

                    $data['email'] = $post['email'];
                }

                # Só altera a senha caso o usuario preencher o campo
                if (isset($post['senha']) && !empty($post['senha'])) {

                    $data['senha'] = password_hash($post['senha'], PASSWORD_DEFAULT);
                }

                # Determina o perfil do usuario ( futuramente a coluna administrador irá mudar para perfil)
                if ($post['perfil'] == 1) {

                    # Administrador
                    $data['administrador'] = 1;
                    $data['tipo_usuario'] = 0;
                } elseif ($post['perfil'] == 2) {

                    # Fornecedor
                    $data['administrador'] = 0;
                    $data['tipo_usuario'] = 1;

                    # Armazena os fornecedores do usuario
                    $conexao = [];
                    foreach ($post['fornecedores'] as $fornecedor) {
                        $conexao[] = [
                            'id_usuario' => $id,
                            'id_fornecedor' => $fornecedor,
                            'tipo' => $post['nivel']
                        ];
                    }
                } else {

                    # Representante...
                    $data['administrador'] = 0;
                    $data['tipo_usuario'] = 1;
                }

                $this->db->trans_begin();

                # Atualiza o basico do usuário
                $this->db->where('id', $id);
                $this->db->update('usuarios', $data);

                # Remove todos as conexoes existes para atualizar pelos novos
                $this->db->where('id_usuario', $id)->delete('usuarios_fornecedores');

                # Verifica se existe conexões com fornecedores
                if (isset($conexao) && !empty($conexao)) {

                    $this->db->insert_batch('usuarios_fornecedores', $conexao);
                }

                # Armazena a foto do usuario
                $this->insert_photo($id);

                if ($this->db->trans_status() === false) {

                    $this->db->trans_rollback();

                    $warning = $this->notify->errorMessage();
                } else {

                    $this->db->trans_commit();

                    $warning = ['type' => 'success', 'message' => notify_update];
                }

                $this->output->set_content_type('application/json')->set_output(json_encode($warning));
            }
        } else {

            $this->form($id);
        }
    }

    public function insert_photo($id)
    {
        if (isset($_FILES['foto']['name']) && !empty($_FILES['foto']['name'])) {

            $this->load->library('upload');

            $config['upload_path'] = PUBLIC_PATH . "usuarios/{$id}";
            $config['allowed_types'] = 'png|gif|jpg|jpeg';
            $config['encrypt_name'] = TRUE;

            if (!file_exists($config['upload_path'])) mkdir($config['upload_path'], 0777, true);

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('foto')) {

                $this->db->trans_rollback();

                $warning = ["status" => false, "message" => $this->upload->display_errors()];

                $this->output->set_content_type('application/json')->set_output(json_encode($warning));
            } else {

                $foto = $this->upload->data()['file_name'];

                # Atualiza o campo foto do usuario
                $this->db->where('id', $id);
                $this->db->update('usuarios', ['foto' => $foto]);

                return true;
            }
        }
    }

    /**
     * Função que envia email para usuario com nova senha
     *
     * @param int ID do usuario
     * @return  json
     */
    public function new_password($id)
    {
        if ($this->input->is_ajax_request()) {

            $usuario = $this->usuario->findById($id);

            if (isset($usuario) && !empty($usuario['email'])) {

                $password = generatePassword();

                # Criptografando a senha
                $passwordCriptografada = password_hash($password, PASSWORD_DEFAULT);

                # Atualiza o usuario com a nova senha
                $this->db->where('id', $id);
                $updt = $this->db->update('usuarios', ['senha' => $passwordCriptografada]);

                if ($updt) {

                    # Envia email para com a nova senha
                    $notify = [
                        "to" => $usuario['email'],
                        "cco" => '',
                        "greeting" => $usuario['nome'],
                        "subject" => "Nova senha Pharmanexo",
                        "message" => "Foi gerado uma nova senha para acesso do sistema: {$password}"
                    ];

                    $send = $this->notify->send($notify);

                    if ($send) {

                        $type = 'success';
                        $message = "Nova senha enviada por e-mail!";
                    } else {

                        $type = 'warning';
                        $message = "Erro ao enviar e-mail com a nova senha!";
                    }
                }
            } else {

                $type = 'warning';

                if (empty($usuario['email'])) {

                    $message = "Usuário sem E-mail cadastrado!";
                } else {

                    $message = "Erro ao identificar registro do usuário informado!";
                }
            }

            $output = ['type' => $type, 'message' => $message];

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Função que exclui usuario
     *
     * @return  json
     */
    public function delete_multiple()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $this->db->trans_begin();

            foreach ($post['el'] as $item) {

                # Remove as conexões de rotas
                $this->db->where('id_usuario', $item)->delete('usuarios_fornecedores');

                # Remove a foto do usuario
                if (file_exists(PUBLIC_PATH . "usuarios/{$item}")) {

                    chmod(PUBLIC_PATH . "usuarios/{$item}", 0777);

                    unlink(PUBLIC_PATH . "usuarios/{$item}");
                }

                # Remove o registro do usuario
                $this->usuario->delete($item);
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();

                $output = $this->notify->errorMessage();
            } else {
                $this->db->trans_commit();

                $output = ['type' => 'success', 'message' => notify_delete];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function blockUser($id, $ativar = null)
    {
        if ($this->input->method() == 'post') {

            $situacao = (isset($ativar)) ? 1 : 0;
            $validade = (isset($ativar)) ? date('Y-m-d H:i:s', strtotime("+3 minutes")) : null;

            $updt = $this->db->where('id', $id)->update('usuarios', ['situacao' => $situacao, 'validade_token' => $validade]);

            if ($updt) {

                $output = ['type' => 'success', 'message' => notify_update];
            } else {

                $output = $this->notify->errorMessage();
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Função customizada do form_validation, verifica se o email do fornecedor já existe na tabela de usuarios no DB
     *
     * @param int $email
     * @return  bool
     */
    function check_unique_email($email)
    {
        $id = $this->input->post('id');

        $result = $this->usuario->check_unique_email($id, $email);

        // Se retornar TRUE o email já existe
        if ($result) {
            $this->form_validation->set_message('check_unique_email', "O campo {field}  já existe, ele deve ser único!");
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function getRoles()
    {
        if ($this->input->is_ajax_request()) {

            $post = $this->input->post();

            if ($post['perfil'] == 1) {

                $data['options'] = $this->db->select('id, titulo AS value')->order_by('id ASC')->get('perfis')->result_array();
            } else {

                $data['options'] = [
                    ['id' => 1, 'value' => 'Administrador'],
                    ['id' => 2, 'value' => 'Comercial'],
                    ['id' => 3, 'value' => 'Financeiro']
                ];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    /**
     * Exibe a view admin/Usuarios/form.php
     *
     * @param int $id
     * @return  view
     */
    private function form($id = null)
    {
        $data = [];
        $data['form_action'] = "{$this->route}/criar";
        $page_title = "Cadastro de usuários";
        $data['foto'] = base_url('images/usuarios/no-user.png');
        $data['fornecedores'] = $this->fornecedor->get();
        $data['url_perfis'] = "{$this->route}/getRoles";
        $data['url_route_success'] = $this->route;

        if (isset($id)) {

            $page_title = "Edição de Usuários";

            $data['form_action'] = "{$this->route}/atualizar/{$id}";
            $data['usuario'] = $this->db->where('id', $id)->get('usuarios')->row_array();

            $user_fornecedores = $this->db->where('id_usuario', $id)->get('usuarios_fornecedores')->result_array();
            $data['user_fornecedores'] = array_column($user_fornecedores, 'id_fornecedor');

            # Caminho da pasta da logo do fornecedor
            $root_path_logo = 'public/usuarios/' . $id . '/' . $data['usuario']['foto'];

            if (isset($data['usuario']['foto']) && !empty($data['usuario']['foto']) && file_exists($root_path_logo)) {

                $data['foto'] = base_url("public/usuarios/{$id}/{$data['usuario']['foto']}");
            } else {

                $data['foto'] = base_url('images/usuarios/no-user.png');
            }
        }

        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnBack',
                    'url' => "{$this->route}",
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ],
                [
                    'type' => 'submit',
                    'id' => 'btnSave',
                    'form' => 'formUsuario',
                    'class' => 'btn-primary',
                    'icone' => 'fa-save',
                    'label' => 'Salvar Alterações'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/jquery.form.min.js',
                THIRD_PARTY . 'plugins/jquery-validation-1.19.1/dist/jquery.validate.min.js'
            ]
        ]);

        $this->load->view("{$this->views}/form", $data);
    }

    /**
     * Perfil do usuario logado
     *
     * @return view
     */
    public function perfil()
    {

        $this->db->where('id', $this->session->userdata('id_usuario'));

        $usuario = $this->db->get('usuarios')->row_array();

        $page_title = "Editar Usuário";
        $data['usuario'] = $usuario;
        $data['form_action'] = "{$this->route}/atualizar_perfil";
        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'submit',
                    'id' => 'btnSave',
                    'form' => 'formUsuario',
                    'class' => 'btn-primary',
                    'icone' => 'fa-save',
                    'label' => 'Salvar Alterações'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/jquery.form.min.js',
                THIRD_PARTY . 'plugins/jquery-validation-1.19.1/dist/jquery.validate.min.js'
            ]
        ]);

        $this->load->view("{$this->views}perfil", $data);
    }

    /**
     * Função que atualiza o perfil
     *
     * @return json - ajax
     */
    public function atualizar_perfil()
    {
        $this->load->library('upload');

        $post = $this->input->post();

        $id = $post['id'];

        // Verifica se o usuario  não informou uma nova senha
        if (!isset($post['senha']) || empty($post['senha'])) {

            unset($post['senha']);
        } else {

            $post['senha'] = password_hash($post['senha'], PASSWORD_DEFAULT);
        }

        // Remove estes campos do array para não atualizar
        unset($post['nome']);
        unset($post['email']);
        unset($post['c_senha']);
        unset($post['id']);

        // Se o usuario alterar a foto
        if (isset($_FILES['foto']['name']) && !empty($_FILES['foto']['name'])) {

            $config['upload_path'] = PUBLIC_PATH . "usuarios/{$id}";
            $config['allowed_types'] = 'png|gif|jpg|jpeg';
            $config['encrypt_name'] = TRUE;

            delete_files($config['upload_path'], true);

            if (!file_exists($config['upload_path'])) mkdir($config['upload_path'], 0777, true);

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('foto')) {
                $error = $this->upload->display_errors('<p>', '</p>');

                $warning = ["type" => 'warning', "message" => $error];

                $this->session->set_userdata('warning', $warning);

                redirect($this->route . '/perfil');
            } else {
                $data = $this->upload->data();

                $post['foto'] = $data['file_name'];
            }
        }

        $this->db->where('id', $id);

        if ($this->db->update('usuarios', $post)) {
            // Se cadastrou logo, atualiza a session para exibir imagem no perfil
            if (isset($post['foto']))
                $this->session->set_userdata('foto', $post['foto']);

            $warning = ['type' => 'success', 'message' => 'Usuário atualizado com sucesso'];
        } else {
            $warning = ['type' => 'warning', 'message' => 'Erro ao atualizar usuário'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route . '/perfil');
    }

    /**
     * Gera arquivo excel do datatable
     *
     * @return  downlaod file
     */
    public function exportar()
    {
        $this->db->select("nome, email, cpf");
        $this->db->from("usuarios");
        $this->db->order_by("nome ASC");

        $query = $this->db->get()->result_array();

        if (count($query) < 1) {
            $query[] = [
                'nome' => '',
                'email' => '',
                'cpf' => ''
            ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'Compradores'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ($exportar['status'] == false) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }
}

/* End of file: Usuarios.php */
