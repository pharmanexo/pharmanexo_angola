<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller
{
    private $route, $views;
    var $google_auth;

    public function __construct()
    {
        parent::__construct();
        $this->load->model("m_login");
        $this->load->model("m_rota", 'rota');
        $this->load->model("M_grupo_usuario_rota", "grupo_usuario_rota");
        $this->load->model("M_representante", 'rep');
        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_estados', 'estado');
        $this->load->model('m_usuarios', 'usuario');

        $this->route = base_url("login/");
        $this->views = "login/";
    }

    public function index()
    {
        $data['frm_action'] = "{$this->route}logar";
        $data['frm_integranexo'] = "{$this->route}logarIntegranexo";
        $data['frm_representante'] = "{$this->route}logarRepresentante";
        $data['frm_distribuidor'] = "{$this->route}logarDistribuidor";
        $data['frm_compracoletiva'] = "{$this->route}logarCompraColetiva";
        $data['frm_novasenha'] = "{$this->route}recuperar_senha";
        $data['frm_novasenharep'] = "{$this->route}recuperar_senhaRepresentante";

        $data['frm_convidado'] = "{$this->route}logarConvidado";

        // TEMPLATE
        $data['header'] = $this->template->header([
            'title' => 'Login'
        ]);
        $data['scripts'] = $this->template->scripts();

        $this->load->view('login', $data);
    }

    /**
     *  Função que verifica email informado para recuperar a senha
     *
     * @return json
     */
    public function verificar_email()
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();
            $usuario = $this->db->where('email', $post['email'])->get('usuarios')->row_array();

            // Verifica se o email existe
            if (!empty($usuario)) {
                // Obtem o caminho do arquivo de email
                $path = "public/html/template_mail/verificar_email.html";

                // Recupera o arquivo
                $template = file_get_contents($path);

                // Obtem o primeiro nome do usuário
                $nome = explode(' ', $usuario['nome'])[0];

                $token = password_hash(time(), PASSWORD_DEFAULT);

                // url para a função nova_senha_email()
                $url = "{$this->route}/validation_email?email={$post['email']}&token={$token}";

                // Altera os coringas do arquivo
                $body = str_replace(['%usuario%', '%url_verificar%'], [$nome, $url], $template);

                $data = [
                    'token' => $token,
                    'validade_token' => date('Y-m-d H:i', strtotime('+1 Hours'))
                ];

                // Atualiza o usuario
                $this->db->where('id', $usuario['id'])->update('usuarios', $data);

                # Envia email para com a nova senha
                $notify = [
                    "to" => $post['email'],
                    "cco" => '',
                    "greeting" => $usuario['nome'],
                    "subject" => "Portal Pharmanexo - Verificação de E-mail",
                    "message" => $body
                ];
                $send = $this->notify->send($notify);

                // Enviar email
                if ($send) {

                    $output = ['type' => 'success', 'message' => 'Por motivos de segurança, enviamos um e-mail para o endereço cadastrado. Clique no link do e-mail e verifique seu endereço.'];
                } else {

                    $output = ['type' => 'error', 'message' => 'Erro ao enviar e-mail!'];
                }
            } else {

                $output = ['type' => 'error', 'message' => 'E-mail não encontrado no sistema.'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     *  Função que verifica email informado para recuperar a senha
     *
     * @return json
     */
    public function renovar_sessao()
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            $this->db->where('id', $post['id'])->update('ci_sessions', ['ip_address' => time(), 'timestamp' => time()]);
            $output = ['type' => 'success', 'message' => 'Sessão atualizada!', 'action' => 'dashboard'];
            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     *  Função que verifica email informado para recuperar a senha
     *
     * @return json
     */
    public function timeout_sessao()
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();
            $this->session->sess_destroy();
            $this->db->query("UPDATE usuarios set logado = 0 WHERE id = {$post['id_usuario']}");
            $this->db->where('id', $post['id'])->update('ci_sessions', ['timestamp' => $post['timestamp']]);
            $output = ['type' => 'error', 'message' => 'Deslogado por inatividade!'];
            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     *  Função que verifica email informado para recuperar a senha
     *
     * @return json
     */
    public function recuperar_senha()
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            $usuario = $this->db->where('email', $post['login'])->get('usuarios')->row_array();

            // Verifica se o email existe
            if (!empty($usuario)) {
                // Obtem o caminho do arquivo de email
                $path = "public/html/template_mail/nova_senha.html";

                // Recupera o arquivo
                $template = file_get_contents($path);

                // Obtem o primeiro nome do usuário
                $nome = explode(' ', $usuario['nome'])[0];

                $token = password_hash(time(), PASSWORD_DEFAULT);

                // url para a função nova_senha_email()
                $url = "{$this->route}/validation_token?email={$post['login']}&token={$token}";

                // Altera os coringas do arquivo
                $body = str_replace(['%usuario%', '%url_verificar%'], [$nome, $url], $template);

                $data = [
                    'token' => $token,
                    'validade_token' => date('Y-m-d H:i', strtotime('+6 Hours'))
                ];

                // Atualiza o usuario
                $this->db->where('id', $usuario['id'])->update('usuarios', $data);

                # Envia email para com a nova senha
                $notify = [
                    "to" => $post['login'],
                    "cco" => '',
                    "greeting" => $usuario['nome'],
                    "subject" => "Portal Pharmanexo - Recuperação de Acesso",
                    "message" => $body
                ];
                $send = $this->notify->send($notify);

                // Enviar email
                if ($send) {

                    $output = ['type' => 'success', 'message' => 'Pronto! Verifique o e-mail informado para concluir a recuperação.'];
                } else {

                    $output = ['type' => 'error', 'message' => 'Erro ao enviar e-mail!'];
                }
            } else {

                $output = ['type' => 'error', 'message' => 'E-mail não encontrado no sistema.'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     *  Função que verifica email informado para recuperar a senha
     *
     * @return json
     */
    public function recuperar_senhaRepresentante()
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            $usuario = $this->db->where('email', $post['login'])->get('representantes')->row_array();

            // Verifica se o email existe
            if (!empty($usuario)) {
                // Obtem o caminho do arquivo de email
                $path = "public/html/template_mail/nova_senha.html";

                // Recupera o arquivo
                $template = file_get_contents($path);

                // Obtem o primeiro nome do usuário
                $nome = explode(' ', $usuario['nome'])[0];

                $token = password_hash(time(), PASSWORD_DEFAULT);

                // url para a função nova_senha_email()
                $url = "{$this->route}/validation_tokenrep?email={$post['login']}&token={$token}";

                // Altera os coringas do arquivo
                $body = str_replace(['%usuario%', '%url_verificar%'], [$nome, $url], $template);

                $data = [
                    'token' => $token,
                    'validade_token' => date('Y-m-d H:i', strtotime('+6 Hours'))
                ];

                // Atualiza o usuario
                $this->db->where('id', $usuario['id'])->update('representantes', $data);

                # Envia email para com a nova senha
                $notify = [
                    "to" => $post['login'],
                    "cco" => '',
                    "greeting" => $usuario['nome'],
                    "subject" => "Portal Pharmanexo - Recuperação de Acesso",
                    "message" => $body
                ];
                $send = $this->notify->send($notify);

                // Enviar email
                if ($send) {

                    $output = ['type' => 'success', 'message' => 'Pronto! Verifique o e-mail informado para concluir a recuperação.'];
                } else {

                    $output = ['type' => 'error', 'message' => 'Erro ao enviar e-mail!'];
                }
            } else {

                $output = ['type' => 'error', 'message' => 'E-mail não encontrado no sistema.'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     *  View para alterar senha
     *
     * @param - int id usuario
     * @return view
     */
    public function alterar_senha()
    {
        $data['frm_action'] = "{$this->route}change_password";
        $data['header'] = $this->template->header(['title' => 'Alterar Senha']);
        $data['scripts'] = $this->template->scripts();


        $this->load->view('change_password', $data);
    }

    /**
     *  View para alterar senha
     *
     * @param - int id usuario
     * @return view
     */
    public function alterar_senharep()
    {
        $data['frm_actionrep'] = "{$this->route}change_passwordrep";
        $data['header'] = $this->template->header(['title' => 'Alterar Senha']);
        $data['scripts'] = $this->template->scripts();


        $this->load->view('change_passwordrep', $data);
    }

    /**
     *  Função que verifica se é o primeiro login para atualização
     *
     * @param - int id usuario
     * @return json
     */
    public function primeiroatt()
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            $post['id'] = $this->session->userdata('id_usuario');

            if ($this->usuario->update($post)) {

                $this->db->where('id', $post['id'])->update('usuarios', ['primeiro_login' => '2', 'avatar' => $post['id_avatar'], 'nickname' => $post['nickname']]);
                $result = ['type' => 'success', 'message' => 'Conta atualizada'];
            } else {

                $result = ['type' => 'warning', 'message' => 'Erro ao atualizar senha!'];
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    /**
     *  Função para validar token liberando acesso
     *
     * @return view
     */
    public function validation_email()
    {
        $get = $this->input->get();

        $usuario = $this->db->where('email', $get['email'])->get('usuarios')->row_array();

        if (strtotime(date('Y-m-d H:i:s')) <= strtotime($usuario['validade_token'])) {
            $this->session->set_userdata(['user_id' => $usuario['id']]);

            $this->db->where('id', $this->session->user_id)->update('usuarios', ['token' => null, 'validade_token' => null, 'verifica_email' => "2"]);
            redirect("{$this->route}validado");
        } else {

            var_dump("Token expirado! Solicite um novo para verificar seu e-mail.");
            exit();
        }
    }

    public function validado()
    {
        $this->auditor->setlog("Logout", 'login', null);
        # $this->db->query("UPDATE usuarios set logado = 0 WHERE id = {$this->session->id_usuario}");
        $this->session->sess_destroy();
        $this->session->set_userdata("logado", "0");
        $this->session->set_userdata("mc", "0"); //menu controle

        $data['header'] = $this->template->header(['title' => 'Alterar Senha']);
        $data['scripts'] = $this->template->scripts();

        $this->load->view('validado', $data);
    }

    /**
     *  Função para validar token liberando acesso
     *
     * @return view
     */
    public function validation_token()
    {
        $get = $this->input->get();

        $usuario = $this->db->where('email', $get['email'])->get('usuarios')->row_array();

        if (strtotime(date('Y-m-d H:i:s')) <= strtotime($usuario['validade_token'])) {

            $this->session->set_userdata(['user_id' => $usuario['id']]);

            redirect("{$this->route}alterar_senha");
        } else {

            var_dump("Token expirado! Solicite uma nova recuperação de senha.");
            exit();
        }
    }

    /**
     *  Função para validar token liberando acesso
     *
     * @return view
     */
    public function validation_tokenrep()
    {
        $get = $this->input->get();

        $usuario = $this->db->where('email', $get['email'])->get('representantes')->row_array();

        if (strtotime(date('Y-m-d H:i:s')) <= strtotime($usuario['validade_token'])) {

            $this->session->set_userdata(['user_id' => $usuario['id']]);

            redirect("{$this->route}alterar_senharep");
        } else {

            var_dump("Token expirado! Solicite uma nova recuperação de senha.");
            exit();
        }
    }

    /**
     *  Função para alterar senha
     *
     * @param - int id usuario
     * @return json
     */
    public function change_password()
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            if ($post['senha'] != $post['c_senha']) {

                $output = ['type' => 'warning', 'message' => 'Erro ao confirmar senha!'];
            } else {

                $post['id'] = $this->session->user_id;

                unset($post['c_senha']);
                unset($post['token']);

                if ($this->usuario->update($post)) {

                    // Atualiza o usuário sem token e validade token
                    $senha = password_hash($post['senha'], PASSWORD_DEFAULT);

                    $this->db->where('id', $this->session->user_id)->update('usuarios', ['token' => null, 'validade_token' => null, 'senha' => $senha]);

                    $output = ['type' => 'success', 'message' => 'Senha alterada com sucesso!', 'route' => $this->route];
                } else {

                    $output = ['type' => 'warning', 'message' => 'Erro ao atualizar senha!'];
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     *  Função para alterar senha
     *
     * @param - int id usuario
     * @return json
     */
    public function change_passwordrep()
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            if ($post['senha'] != $post['c_senha']) {

                $output = ['type' => 'warning', 'message' => 'Erro ao confirmar senha!'];
            } else {

                $post['id'] = $this->session->user_id;

                unset($post['c_senha']);
                unset($post['token']);

                if ($this->rep->update($post)) {

                    // Atualiza o usuário sem token e validade token

                    $senha = password_hash($post['senha'], PASSWORD_DEFAULT);

                    $this->db->where('id', $this->session->user_id)->update('representantes', ['token' => null, 'validade_token' => null, 'senha' => $senha]);

                    $output = ['type' => 'success', 'message' => 'Senha do representante alterada com sucesso!', 'route' => $this->route];
                } else {

                    $output = ['type' => 'warning', 'message' => 'Erro ao atualizar senha!'];
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function esqueci_senha()
    {
        $dados['nome_view'] = 'v_esqueci_senha';
        $this->load->view('v_layout', $dados);
    }

    public function logar()
    {

        $post = $this->input->post();

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array('secret' => '6LcSlLkUAAAAACT-qSeWEd0nrNRzgYJaUqwHuZkR', 'response' => $post['token']);

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

            if ($score > 0) {

                unset($post['token']);

                # Autentica o usuario
                $consulta = $this->m_login->logar($post);

                # Verifica se o retorno da autenticação não deu error
                if (!isset($consulta['error'])) {

                    $this->auditor->setlog("Login", 'login', []);

                    if ($consulta['logado'] == '1') {
                        $this->db->query("UPDATE usuarios set logado = 0 WHERE id = {$consulta['id']}");
                        $warning = ['type' => 'info', 'message' => 'Existia uma sessão ativa para este usuário. Entre novamente!'];
                    } else {

                        /* atualiza usuario logado */
                        $this->db->query("UPDATE usuarios set logado = 1 WHERE id = {$consulta['id']}");
                        $id_sessao = session_id();
                        if (isset($consulta['administrador']) && $consulta['administrador'] == 1) {
                            $userdata = [
                                'logado' => '1',
                                "primeiro" => $consulta['primeiro_login'],
                                'mc' => '0',
                                'id_usuario' => $consulta['id'],
                                "tipo_usuario" => $consulta['tipo_usuario'],
                                "administrador" => $consulta['administrador'],
                                "nome" => $consulta['nome'],
                                "tipo" => 1,
                                "nivel" => $consulta['nivel'],
                                "email" => $consulta['email'],
                                "foto" => $consulta['foto'],
                                "nickname" => $consulta['nickname'],
                                "avatar" => $consulta['avatar'],
                                "verifica" => $consulta['verifica_email'],
                                "routes" => $this->rota->rotasAdmin($consulta['nivel']),
                                "id_sessao" => $id_sessao,
                            ];
                            if ($userdata['primeiro'] == '1') {
                                $warning = ['type' => 'success', 'action' => 'dashboard/primeiro'];
                            }
                            $this->session->set_userdata($userdata);
                            $warning = ['type' => 'success', 'action' => 'dashboard/primeiro'];
                        } else {

                            if (!empty($consulta)) {

                                $this->session->set_userdata('empresas', $consulta['empresas']);

                                $userdata = [
                                    'logado' => '1',
                                    "primeiro" => $consulta['primeiro_login'],
                                    'mc' => '0',
                                    'id_usuario' => $consulta['id'],
                                    "tipo_usuario" => $consulta['tipo_usuario'],
                                    "nome" => $consulta['nome'],
                                    "email" => $consulta['email'],
                                    "foto" => $consulta['foto'],
                                    "nickname" => $consulta['nickname'],
                                    "avatar" => $consulta['avatar'],
                                    "verifica" => (isset($consulta['verifica_email'])) ? $consulta['verifica_email'] : '',
                                    "usuario_sintese" => $consulta['usuario_sintese'],
                                    "id_sessao" => $id_sessao,
                                ];

                                $this->session->set_userdata($userdata);

                                if (count($consulta['empresas']) > 1) {
                                    $warning = ['type' => 'success', 'action' => 'empresas'];
                                } else {
                                    $fornecedor = $this->fornecedor->findById($consulta['empresas'][0]['id']);
                                    // $comissionamento = $this->comissionamento->find("comissao", "id_fornecedor = {$fornecedor['id']}", TRUE);

                                    $usuario_fornecedor = $this->db->select("*")->from('usuarios_fornecedores')->where("id_usuario = {$consulta['id']} and id_fornecedor = {$fornecedor['id']}")->get()->row_array();

                                    $session_data = [
                                        'id_fornecedor' => $fornecedor['id'],
                                        'razao_social' => $fornecedor['razao_social'],
                                        'nome_fantasia' => $fornecedor['nome_fantasia'],
                                        'cnpj' => $fornecedor['cnpj'],
                                        'id_matriz' => $fornecedor['id_matriz'],
                                        "integracao" => $fornecedor['integracao'],
                                        "tipo_empresa" => $fornecedor['tipo'],
                                        "id_tipo_venda" => $fornecedor['id_tipo_venda'],
                                        "id_estado" => $this->estado->find("id", "uf = '{$fornecedor['estado']}'", TRUE)['id'],
                                        'logo' => $fornecedor['logo'],
                                        'comissao' => 3.00,
                                        'estados' => $this->db->query("SELECT id_estado from fornecedores_estados where id_fornecedor = {$fornecedor['id']}")->row_array(),
                                        'routes' => $this->grupo_usuario_rota->get_routes_fornecedor($fornecedor['id'], $usuario_fornecedor['tipo']),
                                        'compra_distribuidor' => $fornecedor['compra_distribuidor'],
                                        'grupo' => $usuario_fornecedor['tipo'],
                                        "nickname" => $consulta['nickname'],
                                        "avatar" => $consulta['avatar'],
                                        "verifica" => $consulta['verifica_email'],
                                        'credencial_bionexo' => $fornecedor['credencial_bionexo']
                                    ];

                                    unset($_SESSION['empresas']);

                                    if (isset($session_data)) {
                                        $this->session->set_userdata($session_data);
                                    }

                                    $warning = ['type' => 'success', 'action' => 'dashboard/primeiro'];
                                }
                            } else {
                                $this->session->set_userdata("logado", "0");
                                $this->session->set_userdata("mc", "0"); //menu controle
                                #$this->session->set_flashdata("mensagem", "Usuário/Senha incorretos.");
                                $warning = ['type' => 'error', 'message' => 'Dados incorretos'];
                            }
                        }
                    }
                } else {

                    $warning = ['type' => 'error', 'message' => $consulta['message']];
                }
            } else {
                $warning = ['type' => 'error', 'message' => 'No Score'];
            }
        } else {
            $warning = ['type' => 'error', 'message' => 'No captch'];
        }


        $this->output->set_content_type('application/json')->set_output(json_encode($warning));
    }

    public function logarRepresentante()
    {
        $post = $this->input->post();

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array('secret' => '6LcSlLkUAAAAACT-qSeWEd0nrNRzgYJaUqwHuZkR', 'response' => $post['token']);

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

                $consulta = $this->rep->login($post);
                if (isset($consulta['id'])) $consulta['id_representante'] = $consulta['id'];

                if (!empty($consulta) && $consulta != false) {

                    $consulta['empresas'] = $this->rep->get_empresas($consulta['id']);

                    $consulta['logado'] = 1;


                    if (!empty($consulta['empresas'])) {
                        if (count($consulta['empresas']) > 1) {
                            $this->session->set_userdata($consulta);
                            $warning = ['type' => 'success', 'action' => 'empresas'];
                        } else {
                            $fornecedor = $consulta['empresas'][0];

                            $session_data = [

                                'id_fornecedor' => $fornecedor['id'],
                                'razao_social' => $fornecedor['razao_social'],
                                'cnpj' => $fornecedor['cnpj'],
                                "integracao" => $fornecedor['integracao'],
                                "id_tipo_venda" => $fornecedor['id_tipo_venda'],
                                "id_estado" => $this->estado->find("id", "uf = '{$fornecedor['estado']}'", TRUE)['id'],
                                'logo' => $fornecedor['logo'],
                                'comissao' => 3.0,
                                'estados' => $this->db->query("SELECT id_estado from fornecedores_estados where id_fornecedor = {$fornecedor['id']}")->row_array(),
                            ];

                            // Session para identificar que é o representante que esta logado
                            $session_data['id_representante'] = 1;

                            $this->session->set_userdata(array_merge($consulta, $session_data));

                            $this->auditor->setlog("Login", 'login', json_encode($consulta));

                            $warning = ['type' => 'success', 'action' => '/representantes/dashboard'];
                        }
                    } else {
                        $warning = ['type' => 'error', 'message' => 'Nenhum distribuidor associado'];
                    }
                } else {
                    $warning = ['type' => 'error', 'message' => 'Dados inválidos, verique e tente novamente.'];
                }
            } else {
                $warning = ['type' => 'error', 'message' => 'No Score'];
            }
        } else {
            $warning = ['type' => 'error', 'message' => 'No captch'];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($warning));
    }

    public function logarDistribuidor()
    {

        $post = $this->input->post();

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array('secret' => '6LcSlLkUAAAAACT-qSeWEd0nrNRzgYJaUqwHuZkR', 'response' => $post['token']);

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

                # Autentica o usuario
                $consulta = $this->m_login->logar($post);

                # Verifica se o retorno da autenticação não deu error
                if (!isset($consulta['error'])) {

                    $this->auditor->setlog("Login", 'login', []);

                    if ($consulta['logado'] == '1') {
                        $this->db->query("UPDATE usuarios set logado = 0 WHERE id = {$consulta['id']}");
                        $warning = ['type' => 'error', 'message' => 'Existia uma sessão ativa para este usuário. Entre novamente!'];
                    } else {

                        /* atualiza usuario logado */
                        $this->db->query("UPDATE usuarios set logado = 1 WHERE id = {$consulta['id']}");
                        $id_sessao = session_id();
                        if (isset($consulta['administrador']) && $consulta['administrador'] == 1) {
                            $userdata = [
                                'logado' => '1',
                                'mc' => '0',
                                'id_usuario' => $consulta['id'],
                                "tipo_usuario" => $consulta['tipo_usuario'],
                                "administrador" => $consulta['administrador'],
                                "nome" => $consulta['nome'],
                                "tipo" => 1,
                                "nivel" => $consulta['nivel'],
                                "email" => $consulta['email'],
                                "foto" => $consulta['foto'],
                                "id_sessao" => $id_sessao,
                                "routes" => $this->rota->rotasAdmin($consulta['nivel']),
                            ];

                            $this->session->set_userdata($userdata);

                            $warning = ['type' => 'success', 'action' => 'dashboard'];
                        } else {

                            if (!empty($consulta)) {

                                $this->session->set_userdata('empresas', $consulta['empresas']);

                                $userdata = [
                                    'logado' => '1',
                                    'mc' => '0',
                                    'id_usuario' => $consulta['id'],
                                    "tipo_usuario" => $consulta['tipo_usuario'],
                                    "nome" => $consulta['nome'],
                                    "email" => $consulta['email'],
                                    "foto" => $consulta['foto'],
                                    "id_sessao" => $id_sessao,
                                    "usuario_sintese" => $consulta['usuario_sintese'],
                                ];

                                $this->session->set_userdata($userdata);

                                if (count($consulta['empresas']) > 1) {
                                    foreach ($consulta['empresas'] as $empresa) {
                                        $fornecedor = $this->fornecedor->findById($empresa['id']);
                                        if ($fornecedor['distribuidor'] == 1) {
                                            $warning = ['type' => 'success', 'action' => 'empresas'];
                                        } else {
                                            $this->session->set_userdata("logado", "0");
                                            $this->session->set_userdata("mc", "0"); //menu controle
                                            #$this->session->set_flashdata("mensagem", "Usuário/Senha incorretos.");
                                            $warning = ['type' => 'error', 'message' => 'Usuário não possui Fornecedor apto'];
                                        }
                                    }
                                } else {

                                    $fornecedor = $this->fornecedor->findById($consulta['empresas'][0]['id']);
                                    // $comissionamento = $this->comissionamento->find("comissao", "id_fornecedor = {$fornecedor['id']}", TRUE);
                                    if ($fornecedor['distribuidor'] == 1) {
                                        $usuario_fornecedor = $this->db->select("*")->from('usuarios_fornecedores')->where("id_usuario = {$consulta['id']} and id_fornecedor = {$fornecedor['id']}")->get()->row_array();

                                        $session_data = [
                                            'id_fornecedor' => $fornecedor['id'],
                                            'razao_social' => $fornecedor['razao_social'],
                                            'nome_fantasia' => $fornecedor['nome_fantasia'],
                                            'cnpj' => $fornecedor['cnpj'],
                                            'id_matriz' => $fornecedor['id_matriz'],
                                            "integracao" => $fornecedor['integracao'],
                                            "tipo_empresa" => $fornecedor['tipo'],
                                            "id_tipo_venda" => $fornecedor['id_tipo_venda'],
                                            "id_estado" => $this->estado->find("id", "uf = '{$fornecedor['estado']}'", TRUE)['id'],
                                            'logo' => $fornecedor['logo'],
                                            'comissao' => 3.00,
                                            'estados' => $this->db->query("SELECT id_estado from fornecedores_estados where id_fornecedor = {$fornecedor['id']}")->row_array(),
                                            'routes' => $this->grupo_usuario_rota->get_routes_fornecedor($fornecedor['id'], $usuario_fornecedor['tipo']),
                                            'compra_distribuidor' => $fornecedor['compra_distribuidor'],
                                            'grupo' => $usuario_fornecedor['tipo'],
                                            'credencial_bionexo' => $fornecedor['credencial_bionexo']
                                        ];

                                        unset($_SESSION['empresas']);

                                        if (isset($session_data)) {
                                            $this->session->set_userdata($session_data);
                                        }

                                        $warning = ['type' => 'success', 'action' => 'dashboard'];
                                    } else {
                                        $this->session->set_userdata("logado", "0");
                                        $this->session->set_userdata("mc", "0"); //menu controle
                                        #$this->session->set_flashdata("mensagem", "Usuário/Senha incorretos.");
                                        $warning = ['type' => 'error', 'message' => 'Usuário não possui Fornecedor apto'];
                                    }
                                }
                            } else {
                                $this->session->set_userdata("logado", "0");
                                $this->session->set_userdata("mc", "0"); //menu controle
                                #$this->session->set_flashdata("mensagem", "Usuário/Senha incorretos.");
                                $warning = ['type' => 'error', 'message' => 'Dados incorretos'];
                            }
                        }
                    }
                } else {

                    $warning = ['type' => 'error', 'message' => $consulta['message']];
                }
            } else {
                $warning = ['type' => 'error', 'message' => 'No Score'];
            }
        } else {
            $warning = ['type' => 'error', 'message' => 'No captch'];
        }


        $this->output->set_content_type('application/json')->set_output(json_encode($warning));
    }

    public function logarCompraColetiva()
    {

        if ($this->input->method() == 'post') {

            $post = $this->input->post();
            $db2 = $this->load->database('adesao', TRUE);
            $comp = $db2->select('*')->where('cnpj', $post['loginCompraColetiva'])->get('compradores')->row_array();

            if (!empty($comp)) {
                if ($comp['situacao'] == '1') {
                    if (password_verify($post['senhaCompraColetiva'], $comp['senha'])) {
                        unset($comp['senhaCompraColetiva']);
                        $_SESSION['validLogin'] = true;
                        $_SESSION['dados'] = $comp;


                        if ($comp['completo'] == 1) {
                            redirect(base_url('compra-coletiva/produtos'));
                        } else {
                            redirect(base_url('compra-coletiva/cadastro/dados'));
                        }
                    } else {
                        $warn = [
                            'type' => 'error',
                            'message' => 'Dados inválidos, tente novamente.'
                        ];
                    }
                } else {
                    $warn = [
                        'type' => 'warning',
                        'message' => 'Seu cadastro está aguardando aprovação do administrador.'
                    ];
                }
                $this->session->set_userdata('warning', $warn);

                redirect($this->route);
            } else {
                $warn = [
                    'type' => 'error',
                    'message' => 'Não encontramos este usuário.'
                ];

                $this->session->set_userdata('warning', $warn);

                redirect($this->route);
            }
        }
    }

    public function logarConvidado()
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            $comp = $this->db->select('*')
                ->where('cnpj', $post['loginconvidado'])
                ->get('compradores')->row_array();

            if (!empty($comp)) {
                if ($comp['situacao_promo'] == 1) {

                    //verifica se é o primeiro login
                    if ($post['senhaconvidado'] == 'Invite@pharma10' && empty($comp['senha_promo'])) {
                        $data = [
                            'id' => $comp['id'],
                            'cnpj' => $comp['cnpj'],
                            'razao_social' => $comp['razao_social'],
                            'troca_senha' => true,
                        ];

                        $_SESSION['validLogin'] = false;
                        $_SESSION['dados'] = $data;

                        redirect(base_url('login/trocarSenhaConvidado'));

                    } else {
                        if (md5($post['senhaconvidado']) == $comp['senha_promo']) {
                            unset($comp['senha_promo']);
                            unset($comp['senha']);
                            $_SESSION['validLogin'] = true;
                            $_SESSION['convidado'] = true;
                            $_SESSION['dados'] = $comp;

                            redirect(base_url('convidados/promocoes'));

                        } else {
                            $warn = [
                                'type' => 'error',
                                'message' => 'Dados inválidos, tente novamente.'
                            ];
                        }
                    }


                } else {
                    $warn = [
                        'type' => 'warning',
                        'message' => 'Seu cadastro está aguardando aprovação do administrador.'
                    ];
                }

                $this->session->set_userdata('warning', $warn);

                redirect($this->route);
            } else {
                $cnpj = soNumero($post['loginconvidado']);

                $content = file_get_contents("https://www.receitaws.com.br/v1/cnpj/{$cnpj}");
                if (!empty($content)) {
                    $result = json_decode($content, true);

                    $data = [
                        'cnpj' => $result['cnpj'],
                        'nome_fantasia' => $result['fantasia'],
                        'razao_social' => $result['nome'],
                        'endereco' => $result['logradouro'],
                        'numero' => $result['numero'],
                        'cidade' => $result['municipio'],
                        'estado' => $result['uf'],
                        'complemento' => $result['complemento'],
                        'cep' => $result['cep'],
                        'email' => $result['email'],
                        'telefone' => $result['telefone'],
                        'situacao_promo' => 0,
                    ];

                    $insert = $this->db->insert('compradores', $data);

                    if ($insert) {

                        $notify = [
                            "to" => "marlon.boecker@pharmanexo.com.br, administracao@pharmanexo.com.br",
                            "cco" => '',
                            "greeting" => "Admninistrador",
                            "subject" => "Portal Pharmanexo - Convidado aguardando aprovação",
                            "message" => "
                            <p>Olá administrador, <br></p>
                            <p>O comprador abaixo está aguardando aprovação, acesso o painel adminitrativo para liberar o acesso.</p>
                            <p>CNPJ: {$data['cnpj']} - {$data['razao_social']}</p>
                          <br>
                          <p>Atenciosamente,</p>
                          <p>Equipe Pharmanexo</p>
                            "
                        ];

                        $send = $this->notify->send($notify);


                        $warn = [
                            'type' => 'warning',
                            'message' => 'Seu cadastro foi enviado para análise, você receberá um e-mail de confirmação.'
                        ];

                        $this->session->set_userdata('warning', $warn);

                        redirect($this->route);

                    }

                }
            }
        }
    }

    public function trocarSenhaConvidado()
    {

        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            if (!isset($post['nome']) || !isset($post['email']) || !isset($post['telefone'])) {
                $warn = [
                    'type' => 'warning',
                    'message' => 'Dados obrigatórios não foram informados'
                ];

                $this->session->set_userdata('warning', $warn);

                redirect("{$this->route}trocarSenhaConvidado");
            } else {

                $comp = $this->db->select('*')->where('id', $post['id_comprador'])->get('compradores');

                if ($comp->num_rows() > 0) {
                    $this->db
                        ->where('id', $post['id_comprador'])
                        ->update('compradores', ['senha_promo' => md5($post['senha'])]);

                    $contato = $this->db
                        ->where('id_comprador', $post['id_comprador'])
                        ->get('compradores_contatos');

                    $dataContato = [
                        'id_comprador' => $post['id_comprador'],
                        'nome' => $post['nome'],
                        'email' => $post['email'],
                        'telefone' => $post['telefone']
                    ];

                    if ($contato->num_rows() > 0) {

                        $this->db
                            ->where('id_comprador', $post['id_comprador'])
                            ->update('compradores_contatos', $dataContato);

                    } else {
                        $this->db->insert('compradores_contatos', $dataContato);
                    }

                    $warn = [
                        'type' => 'success',
                        'message' => 'Dados atualizados com sucesso, faça login novamente'
                    ];

                    $this->session->set_userdata('warning', $warn);

                    redirect("{$this->route}");
                }


            }


        } else {
            $data['frm_action'] = "{$this->route}trocarSenhaConvidado";

            // TEMPLATE
            $data['header'] = $this->template->header([
                'title' => 'Login'
            ]);
            $data['scripts'] = $this->template->scripts();

            $this->load->view('troca_senha_convidado', $data);
        }


    }

    public function logout()
    {


        $this->auditor->setlog("Logout", 'login', null);
        $this->db->query("UPDATE usuarios set logado = 0 WHERE id = {$this->session->id_usuario}");
        $this->session->sess_destroy();
        $this->session->set_userdata("logado", "0");
        $this->session->set_userdata("mc", "0"); //menu controle

        redirect(base_url('/login'));
    }

    public function gravar_senha_alterada()
    {
        $this->load->model("m_login");
        $consulta = $this->login_model->gravar_senha_alterada();
        if (!$consulta) {
            $this->session->set_flashdata("mensagem", "Alteração Não foi realizada.");
            redirect(base_url('Login/alterar_senha'));
        } else {
            redirect(base_url('Login'));
        }
    }

    public function selecionar_empresa($id_fornecedor = null)
    {
        if ($this->input->method() == 'post' || isset($id_fornecedor)) {

            $id = (isset($id_fornecedor)) ? $id_fornecedor : $this->input->post('empresa');


            $verify = $this->db->select("*")
                ->where("id_usuario", $this->session->id_usuario)
                ->where("id_fornecedor", $id)
                ->get("usuarios_fornecedores");


            if ($verify->num_rows() > 0) {

                $fornecedor = $this->fornecedor->findById($id);

                $estados = $this->db->select("id_estado")
                    ->where("id_fornecedor", $fornecedor['id'])
                    ->get("fornecedores_estados")
                    ->row_array();


                $estados_fornecedor = [];

                // foreach ($estados as $estado){
                //     $fornecedor[] = $estado['id_estado'];
                // }

                $usuario_fornecedor = $this->db->select("*")
                    ->from('usuarios_fornecedores')
                    ->where("id_usuario = {$this->session->id_usuario} and id_fornecedor = {$fornecedor['id']}")
                    ->get()->row_array();

                $session_data = [
                    'id_fornecedor' => $fornecedor['id'],
                    'razao_social' => $fornecedor['razao_social'],
                    'nome_fantasia' => $fornecedor['nome_fantasia'],
                    'cnpj' => $fornecedor['cnpj'],
                    'id_matriz' => $fornecedor['id_matriz'],
                    "integracao" => $fornecedor['integracao'],
                    "tipo_empresa" => $fornecedor['tipo'],
                    "id_tipo_venda" => $fornecedor['id_tipo_venda'],
                    "id_estado" => $this->estado->find("id", "uf = '{$fornecedor['estado']}'", TRUE)['id'],
                    'logo' => $fornecedor['logo'],
                    'comissao' => 3.00,
                    'estados' => implode($estados_fornecedor, ','),
                    'routes' => $this->grupo_usuario_rota->get_routes_fornecedor($fornecedor['id'], $usuario_fornecedor['tipo']),
                    'compra_distribuidor' => $fornecedor['compra_distribuidor'],
                    'grupo' => $usuario_fornecedor['tipo'],
                    'credencial_bionexo' => $fornecedor['credencial_bionexo']
                ];

                $this->session->set_userdata($session_data);

                //var_dump();
                redirect(base_url('dashboard'));
                // redirect($this->route . "notificao");
            } else {
                $this->session->set_userdata('warning', ['type' => 'warning', 'message' => 'Empresa não cadastrada para o usuário logado.']);
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            if (!$this->session->has_userdata('empresas')) redirect(base_url('login'));


            $data['frm_action'] = "{$this->route}selecionar_empresa";
            // TEMPLATE
            $data['header'] = $this->template->header([
                'title' => 'Selecionar Empresa'
            ]);
            $data['scripts'] = $this->template->scripts();

            $data['empresas'] = $this->session->empresas;

            $this->load->view('empresas', $data);
        }
    }

    public function notificacao()
    {
        $data['url_dash'] = base_url("dashboard");
        // TEMPLATE
        $data['header'] = $this->template->header([
            'title' => 'Manutenção do Sistema'
        ]);
        $data['scripts'] = $this->template->scripts();

        $data['empresas'] = $this->session->empresas;

        $this->load->view('notificacao_manutencao', $data);
    }
}
