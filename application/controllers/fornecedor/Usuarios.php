<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuarios extends MY_Controller
{
    private $route;
    private $views;
    private $total;
    private $permitidos;
    private $liberacao;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('/fornecedor/usuarios/');
        $this->views = 'fornecedor/usuarios/';

        $this->load->model('m_usuarios', 'usuario');
        $this->load->model('m_fornecedor', 'fornecedor');

        $this->total = $this->db->query("SELECT COUNT(0) as total FROM vw_fornecedores_usuarios WHERE id_fornecedor = {$_SESSION['id_fornecedor']} ")->row_array()['total'];
        $this->permitidos = $this->db->query("SELECT usuarios_permitidos FROM fornecedores WHERE id = {$_SESSION['id_fornecedor']} ")->row_array()['usuarios_permitidos'];

        $this->liberacao = ($this->total < $this->permitidos);
    }

    public function index()
    {
        $page_title = "Usuários";

        $data = [
            'header' => $this->template->header(['title' => $page_title]),
            'navbar' => $this->template->navbar(),
            'sidebar' => $this->template->sidebar(),
            'heading' => $this->template->heading([
                'page_title' => $page_title,
                'buttons' => [
                    [
                        'type' => 'a',
                        'id' => 'btnNovo',
                        'url' => "{$this->route}/insert",
                        'class' => 'btn-primary',
                        'icone' => 'fa-plus',
                        'label' => 'Novo Registro'
                    ],
                    [
                        'type' => 'button',
                        'id' => 'btnExport',
                        'url' => "{$this->route}/exportar",
                        'class' => 'btn-primary',
                        'icone' => 'fa-file-excel',
                        'label' => 'Exportar Excel'
                    ],
                ]
            ]),
            'scripts' => $this->template->scripts([
                    'scripts' => [
                        THIRD_PARTY . 'plugins/jquery.form.min.js',
                        THIRD_PARTY . 'plugins/jquery-validation-1.19.1/dist/jquery.validate.min.js'
                    ]
                ]
            )
        ];

        $data['datatable_src'] = "{$this->route}to_datatable";
        $data['url_update'] = "{$this->route}update";
        $data['url_rede'] = "{$this->route}rede_atendimento/";


        $this->load->view("{$this->views}/main", $data);
    }

    public function rede_atendimento($idUsuario)
    {

        $usuario = $this->usuario->findById($idUsuario);


        $page_title = "Usuário: {$usuario['nome']}";


        $data = [
            'header' => $this->template->header(['title' => $page_title]),
            'navbar' => $this->template->navbar(),
            'sidebar' => $this->template->sidebar(),
            'heading' => $this->template->heading([
                'page_title' => $page_title
            ]),
            'scripts' => $this->template->scripts([

                ]
            )
        ];

        $estados = $this->db
            ->select('ur.*, e.descricao')
            ->from('usuarios_rede_atendimento ur')
            ->join('estados e', 'e.id = ur.id_estado')
            ->where('id_usuario', $idUsuario)
            ->where('id_fornecedor', $this->session->id_fornecedor)
            ->where('id_estado is not null')
            ->get()
            ->result_array();

        $compradores = $this->db
            ->select('ur.*, c.cnpj, c.razao_social')
            ->from('usuarios_rede_atendimento ur')
            ->join('compradores c', 'c.id = ur.id_cliente')
            ->where('id_usuario', $idUsuario)
            ->where('id_fornecedor', $this->session->id_fornecedor)
            ->where('id_cliente is not null')
            ->get()
            ->result_array();

        $data['estados'] = $estados;
        $data['compradores'] = $compradores;

        $data['urlDelete'] = "{$this->route}delete_cliente/{$idUsuario}";
        $data['urlDeleteEstado'] = "{$this->route}delete_estado/{$idUsuario}";
        $data['url_novo_estado'] = "{$this->route}insert_estado/{$idUsuario}";
        $data['url_novo_cliente'] = "{$this->route}insert_cliente/{$idUsuario}";

        $this->load->view("{$this->views}/configuracao", $data);
    }

    public function insert_estado($idUsuario)
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();
            if (isset($post['estados'])) {
                $estados = explode(',', $post['estados']);
                $insert = [];

                foreach ($estados as $estado) {

                    $data = [
                        'id_usuario' => $idUsuario,
                        'id_estado' => $estado,
                        'id_fornecedor' => $this->session->id_fornecedor
                    ];

                    $existe = $this->db->where($data)->get('usuarios_rede_atendimento');

                    if ($existe->num_rows() == 0){
                        $insert[] = $data;
                    }
                }

                if (!empty($insert)) {
                    $i = $this->db->insert_batch('usuarios_rede_atendimento', $insert);

                    $output = ['type' => 'success', 'message' => 'Estados inseridos'];
                } else {
                    $output = ['type' => 'warning', 'message' => 'Houve um erro.'];
                }

                $this->output->set_content_type('application/json')->set_output(json_encode($output));

            }

        } else {
            $data = [];
            $data['form_action'] = "{$this->route}insert_estado/{$idUsuario}";
            $data['estados'] = $this->db->get('estados')->result_array();
            $this->load->view("{$this->views}/modal_estados", $data);
        }
    }

    public function insert_cliente($idUsuario)
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();


            if (isset($post['clientes'])) {
                $clientes = explode(',', $post['clientes']);
                $insert = [];

                foreach ($clientes as $cliente) {

                    $data = [
                        'id_usuario' => $idUsuario,
                        'id_cliente' => $cliente,
                        'id_fornecedor' => $this->session->id_fornecedor
                    ];

                    $existe = $this->db->where($data)->get('usuarios_rede_atendimento');

                    if ($existe->num_rows() == 0){
                        $insert[] = $data;
                    }
                }

                if (!empty($insert)) {
                    $i = $this->db->insert_batch('usuarios_rede_atendimento', $insert);

                    $output = ['type' => 'success', 'message' => 'Estados inseridos'];
                } else {
                    $output = ['type' => 'warning', 'message' => 'Houve um erro.'];
                }

                $this->output->set_content_type('application/json')->set_output(json_encode($output));

            }

        } else {
            $data = [];
            $data['form_action'] = "{$this->route}insert_cliente/{$idUsuario}";
            $data['clientes'] = $this->db->get('compradores')->result_array();
            $this->load->view("{$this->views}/modal_clientes", $data);
        }
    }

    public function delete_estado($idUsuario, $idEstado)
    {

        $delete = $this->db
            ->where('id_fornecedor', $this->session->id_fornecedor)
            ->where('id_estado', $idEstado)
            ->where('id_usuario', $idUsuario)
            ->delete('usuarios_rede_atendimento');

        if ($delete) {
            $output = ['type' => 'success', 'message' => 'Estado removido do usuário'];
        } else {
            $output = ['type' => 'warning', 'message' => 'Houve um erro.'];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    public function delete_cliente($idUsuario, $idCliente)
    {
        $delete = $this->db
            ->where('id_fornecedor', $this->session->id_fornecedor)
            ->where('id_cliente', $idCliente)
            ->where('id_usuario', $idUsuario)
            ->delete('usuarios_rede_atendimento');

        if ($delete) {
            $output = ['type' => 'success', 'message' => 'Cliente removido do usuário'];
        } else {
            $output = ['type' => 'warning', 'message' => 'Houve um erro.'];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    public function insert()
    {
        if ($this->input->method() == 'post') {

            $this->form_validation->set_error_delimiters('<span>', '</span>');
            $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email|is_unique[usuarios.email]');
            $this->form_validation->set_rules('cpf', 'CPF', 'required|is_unique[usuarios.cpf]');


            $password = generatePassword();
            $post = $this->input->post();
            $post['tipo'] = 1;
            $post['situacao'] = 1;


            if ($this->form_validation->run() === false) {

                $errors = [];

                foreach ($post as $key => $value) {

                    $errors[$key] = form_error($key);
                }
//                $verificaCpf = null;
                # Retorna com a lista de erros da validação
                $output = ['type' => 'warning', 'message' => 'Verifique se todos os campos estão preenchidos e se o CPF e/ou E-MAIL já estão cadastrado'];

            } else {

                if ($this->session->has_userdata('id_matriz')) {
                    $id_matriz = $this->session->id_matriz;
                    if ($this->session->has_userdata('id_matriz')) {
                        $filiais = $this->fornecedor->find("id, nome_fantasia", "id_matriz = {$this->session->id_matriz}");
                    }

                    $ids = [];
                    foreach ($filiais as $filial) {
                        $ids[] = $filial['id'];
                    }

                    if (!empty($ids)) {
                        $post['fornecedores'] = $ids;
                    } else {
                        $post['fornecedores'] = [$this->session->id_fornecedor];
                    }


                } else {
                    $post['fornecedores'] = [$this->session->id_fornecedor];
                }
                $post['senha'] = $password;

                $salvar = $this->usuario->salvar($post);

                if ($salvar['status']) {
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


                    $output = ['type' => 'success', 'message' => 'Usuário cadastrado com sucesso'];
                } else {

                    if (!empty($salvar['msg'])) {

                        $output = ['type' => 'warning', 'message' => $salvar['msg']];
                    } else {

                        $output = ['type' => 'warning', 'message' => 'Erro ao cadastrar usuário'];
                    }
                }

            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        } else {

            $this->_form();


            /*if ($this->liberacao) {
                $this->_form();
            } else {
                $this->info();
            }*/
        }
    }

    public function update($id)
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();
            $post['id'] = $id;

            if (isset($post['c_senha'])) unset($post['c_senha']);
            if (isset($post['c_senha']) && empty($post['senha'])) unset($post['senha']);

            $atualizar = $this->usuario->update($post);

            if ($atualizar['status']) {

                $output = ['type' => 'success', 'message' => 'Usuário atualizado com sucesso'];
            } else {

                if (!empty($atualizar['msg'])) {

                    $output = ['type' => 'warning', 'message' => $atualizar['msg']];
                } else {

                    $output = ['type' => 'warning', 'message' => 'Erro ao atualizar usuário'];
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));


        } else {
            $this->_form($id);
        }
    }

    public function delete($id)
    {
        if ($this->usuario->delete($id)) {
            $output = [
                'type' => 'success',
                'message' => 'Usuário cadastrado com sucesso'
            ];
        } else {
            $output = [
                'type' => 'warning',
                'message' => 'Erro ao excluir o usuário'
            ];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    public function reset_password($id = null)
    {
        if (isset($id)) {
            $user = $this->usuario->find("*", "id = {$id}", true);

            if (!empty($user)) {
                $novaSenha = randomPassword();
                $data = [
                    'id' => $id,
                    'senha' => $novaSenha
                ];

                if ($this->usuario->update($data)) {

                    $config = array(
                        'protocol' => 'smtp',
                        'smtp_host' => 'smtp.office365.com',
                        'smtp_port' => '587',
                        'smtp_user' => 'suporte@pharmanexo.com.br',
                        'smtp_pass' => 'Pharma_TI_2019',
                        'mailtype' => 'html',
                        'charset' => 'utf-8',
                        'smtp_crypto' => 'tls',
                        'wordwrap' => true,
                    );

                    $to = $user['email'];

                    $this->load->library('email', $config);
                    $this->email->set_newline("\r\n");
                    $this->email->set_crlf("\r\n");

                    $this->email->initialize($config);
                    $this->email->clear();
                    $this->email->from("suporte@pharmanexo.com.br", 'Pharmanexo');
                    $this->email->to($to, $user['nome']);

                    $template = file_get_contents(base_url('/public/html/template_mail/notifications.html'));
                    $subject = "Redefinicão de Senha";
                    $body = "
                                <p>Olá, {$user['nome']}, </p>
                                <p>O administrador do sistema solicitou a redefinição da sua senha de acesso.</p>
                                <p style='width: 100%; padding: 20px; background-color: #e1e3e5; text-align: center; font-size: 16px'>Nova senha: {$novaSenha}</p>
                                <p>Utilize a nova senha para acessar o sistema Pharmanexo, lembre-se de altera-la no primeiro acesso.</p>
                            ";

                    $body = str_replace(['%to%', '%subject%', '%body_message%'], [$to, $subject, $body], $template);

                    $this->email->subject($subject);
                    $this->email->message($body);

                    $result = $this->email->send();

                    if ($result) {

                    } else {
                        var_dump($result);
                    }

                    var_dump($this->email->print_debugger());
                    exit();

                    $this->output->set_content_type('application/json')->set_output(json_encode(['type' => 'success', 'message' => 'Enviamos um e-mail com a nova senha e as orientações de acesso para o usuário.']));


                }
            }

        }
    }

    public function _form($id = null)
    {
        $data = [];
        $data['form_action'] = "{$this->route}insert";
        if (isset($id)) {
            $data['atualizacao'] = 1;
            $data['form_action'] = "{$this->route}update/{$id}";
            $data['url_delete'] = "{$this->route}delete/{$id}";
            $data['url_resetPass'] = "{$this->route}reset_password/{$id}";
            $data['usuario'] = $this->usuario->findById($id);
        }

        $this->load->view("{$this->views}form", $data);
    }

    public function to_datatable()
    {
        $r = $this->datatable->exec(
            $this->input->get(),
            'usuarios_fornecedores',
            [
                ['db' => 'us.id', 'dt' => 'id'],
                ['db' => 'us.email', 'dt' => 'email'],
                ['db' => 'us.nome', 'dt' => 'nome'],
                ['db' => 'us.nivel', 'dt' => 'nivel', "formatter" => function ($d) {
                    $out = '';
                    switch ($d) {
                        case '1':
                            $out = 'Administrador';
                            break;
                        case '3':
                            $out = 'Financeiro';
                            break;
                        case '2':
                            $out = 'Comercial';
                            break;
                    }

                    return $out;
                }],
            ],
            [['usuarios us', 'id_usuario = us.id']],
            'id_fornecedor = ' . $this->session->userdata('id_fornecedor')
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function info()
    {
        $data = [];

        $this->load->view("{$this->views}info", $data, FALSE);
    }

    /**
     * Perfil do usuario logado
     *
     * @return view
     */
    public function perfil()
    {
        $usuario = $this->usuario->findById($this->session->userdata('id_usuario'));

        $page_title = "Editar Usuário";
        $data['usuario'] = $usuario;
        $data['form_action'] = "{$this->route}atualizar";
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
    public function atualizar()
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

            $this->load->helper('file');
            delete_files($config['upload_path'], true);

            if (!file_exists($config['upload_path'])) mkdir($config['upload_path'], 0777, true);

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('foto')) {
                $error = $this->upload->display_errors('<p>', '</p>');

                $warning = ["type" => 'warning', "message" => $error];

                $this->session->set_userdata('warning', $warning);

                redirect($this->route . 'perfil');
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

            $warning = ['type' => 'success', 'message' => notify_update];
        } else {
            $warning = ['type' => 'warning', 'message' => notify_failed];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route . 'perfil');
    }

    public function generatePass()
    {
        $password = '3Haig4Ty';
        password_hash($password, PASSWORD_DEFAULT);
    }

    public function exportar()
    {
        $this->db->select(" 
            us.nome,
            us.email AS login,
            CASE 
            WHEN uf.tipo = 1 THEN 'Administrador' 
            WHEN uf.tipo = 2 THEN 'Comercial'
            WHEN uf.tipo = 3 THEN 'Financeiro' END AS tipo");
        $this->db->from("usuarios_fornecedores uf");
        $this->db->join("usuarios us", 'uf.id_usuario = us.id');
        $this->db->where("uf.id_fornecedor = {$this->session->userdata('id_fornecedor')}");
        $this->db->order_by('NOME ASC');

        $query = $this->db->get()->result_array();

        if (count($query) < 1) {
            $query[] = [
                'nome' => '',
                'login' => '',
                'tipo' => ''
            ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'Usuarios'];

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

/* End of file: Configuracao.php */