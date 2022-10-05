<?php

class Login extends CI_Controller
{

    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url("pharma/login/");
        $this->views = "pharma/";

        $this->load->model("m_representante", 'rep');
        $this->load->model("m_compradores", 'cmp');
        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_estados', 'estado');
    }

    public function index()
    {
        $data['frm_action'] = "{$this->route}logar";
        $data['frm_novasenha'] = "{$this->route}recuperar_senha";
        // TEMPLATE
        $data['header'] = $this->template->header([
            'title' => 'Portal do Representante'
        ]);
        $data['scripts'] = $this->template->scripts();


        $this->load->view("{$this->views}login2", $data);
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

            if ($score > 0.5) {
                unset($post['token']);

                $consulta = $this->cmp->login($post);

                if (!empty($consulta) && $consulta != false) {

                    $consulta['id_cliente'] = $consulta['id'];

                    $fornecedor = $this->fornecedor->findById(5037);

                    $consulta['logado'] = 1;
                    $consulta['pharma'] = 1;

                    $session_data = [
                        'id_fornecedor' => $fornecedor['id'],
                        'razao_social' => $fornecedor['razao_social'],
                        'cnpj' => $fornecedor['cnpj'],
                        "integracao" => $fornecedor['integracao'],
                        "id_tipo_venda" => $fornecedor['id_tipo_venda'],
                        "id_estado" => $this->estado->find("id", "uf = '{$fornecedor['estado']}'", TRUE)['id'],
                        'logo' => $fornecedor['logo'],
                    ];

                    // Session para identificar que é o representante que esta logado
                    $session_data['id_representante'] = 1;

                    $this->session->set_userdata(array_merge($consulta, $session_data));

                    $this->auditor->setlog("Login", 'login', json_encode($consulta));

                    $warning = ['type' => 'success', 'action' => '/pharma/dashboard'];


                }else{
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

    public function logout()
    {


        $this->auditor->setlog("Logout", 'login', null);


        # $this->db->query("UPDATE usuarios set logado = 0 WHERE id = {$this->session->id_usuario}");
        $this->session->sess_destroy();
        $this->session->set_userdata("logado", "0");
        $this->session->set_userdata("mc", "0");//menu controle

        redirect($this->route);
    }

    public function selecionar_empresa($id_fornecedor = null)
    {
        if ($this->input->method() == 'post' || isset($id_fornecedor)) {
            $post = $this->input->post();
            if (isset($post['empresa']) && !empty($post['empresa'])){
                $id_fornecedor = $post['empresa'];
            }

            $fornecedor = $this->fornecedor->findById($id_fornecedor);

            if (!empty($fornecedor)){
                // verifica se o usuário está permitido para logar neste fornecedor

                $getEmpresaRep =  $this->rep->check_empresa($this->session->id_representante, $fornecedor['id']);

                if (!empty($getEmpresaRep)) {
                    $session_data = [
                        'id_fornecedor' => $fornecedor['id'],
                        'razao_social' => $fornecedor['razao_social'],
                        'cnpj' => $fornecedor['cnpj'],
                        "integracao" => $fornecedor['integracao'],
                        "id_tipo_venda" => $fornecedor['id_tipo_venda'],
                        "id_estado" => $this->estado->find("id", "uf = '{$fornecedor['estado']}'", TRUE)['id'],
                        'logo' => $fornecedor['logo'],
                        'comissao' => 3.00,
                        'logado' => 1
                    ];

                    $this->session->set_userdata($session_data);

                    redirect(base_url('representantes/dashboard'));
                }else{
                    $warning = ['type' => 'error', 'message' => 'Representante não habilitado'];
                }
            }else{
                $warning = ['type' => 'error', 'message' => 'Fornecedor não encontrado'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        } else {
            if (!$this->session->has_userdata('empresas')) redirect(base_url('login'));

            $data['frm_action'] = "{$this->route}selecionar_empresa";
            $data['header'] = $this->template->header(['title' => 'Selecionar Empresa']);
            $data['scripts'] = $this->template->scripts();
            $data['empresas'] = $this->session->empresas;

            $this->load->view("{$this->views}empresas2", $data);
        }
    }
}
