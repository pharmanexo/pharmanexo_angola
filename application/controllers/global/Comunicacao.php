<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

class Comunicacao extends CI_Controller
{

    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = "global/comunicacao/";
        $this->views = "global/";

        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('mensagem');
        $this->load->model('m_usuarios', 'usuario');
    }

    public function index()
    {

        $contatos = $this->getContacts($this->session->id_usuario, $this->session->id_fornecedor);

        $page_title = "";

        $data['header'] = $this->template->header(['title' => 'Correio']);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
        ]);
        $data['scripts'] = $this->template->scripts();
        $data['contatos'] = $this->template->fragment("{$this->views}contatos", [
            'contatos' => $contatos
        ]);


        $this->load->view("{$this->views}home", $data);
    }

    public function messages($idContato = null)
    {

        if (!isset($idContato)) {
            redirect(base_url("{$this->route}"));
        }

        $contatos = $this->getContacts($this->session->id_usuario, $this->session->id_fornecedor);

        foreach ($contatos as $k => $contato){
            $idDecode = base64url_decode($idContato);
            if ($contato['id'] == $idDecode){
                $contatos[$k]['active'] = true;
            }else{
                $contatos[$k]['active'] = false;
            }
        }

        $page_title = "Nova Mensagem";

        $data['idContato'] = $idContato;
        $data['idUsuario'] = base64url_encode($this->session->id_usuario);

        $data['urlLoadMessages'] = base_url("{$this->route}loadMessages/{$data['idUsuario']}/{$data['idContato']}");
        $data['urlReadMessages'] = base_url("{$this->route}readAllMessages/{$data['idUsuario']}/{$data['idContato']}");
        $data['urlDeleteAll'] = base_url("{$this->route}deleteAll/{$data['idUsuario']}/{$data['idContato']}");
        $data['urlDeleteMessages'] = base_url("{$this->route}delete/");

        $data['form_action'] = base_url("{$this->route}insert");
        $data['header'] = $this->template->header(['title' => 'Correio']);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
        ]);
        $data['scripts'] = $this->template->scripts();
        $data['contatos'] = $this->template->fragment("{$this->views}contatos", [
            'contatos' => $contatos
        ]);

        $data['dados'] = $this->usuario->getById(base64url_decode($idContato));

        $this->load->view("{$this->views}messages", $data);
    }

    public function insert()
    {

        if ($this->input->method() == 'post' /*&& $this->input->is_ajax_request()*/) {

            $post = $this->input->post();

            $id_remetente = intval(base64url_decode($post['idUsuario']));
            $id_destinatario = intval(base64url_decode($post['idContato']));
            $mensagem = base64_encode($post['mensagem']);

            if (($id_remetente == 0) || ($id_destinatario == 0) || empty($mensagem) || IS_NULL($mensagem)) {

                $war = [
                    'type' => 'error',
                    'mensage' => 'Parametros invalidos!'
                ];

            } else {

                $arrayMessage =
                    [
                        'remetente' => $id_remetente,
                        'destinatario' => $id_destinatario,
                        'mensagem' => $mensagem,
                        'status' => 0
                    ];

                $insert = $this->mensagem->insertMensage($arrayMessage);

                if ($insert) {

                    $war = [
                        'type' => 'success',
                        'mensage' => 'Mensagem enviada com sucesso!'
                    ];

                } else {

                    $war = [
                        'type' => 'error',
                        'mensage' => 'Mensagem nao enviada!'
                    ];
                }


                $this->output->set_content_type('application/json')->set_output(json_encode($war));

            }

        } else {

            redirect(base_url("{$this->route}"));
        }
    }

    public function delete($id_message)
    {
        $delete = $this->mensagem->deleteMessage($id_message);

        if ($delete) {

            $warn = [
                'type' => 'success',
                'mensage' => 'Mensagem deletada com sucesso!'
            ];

        } else {

            $warn = [
                'type' => 'error',
                'mensage' => 'Houve um erro ao excluir a mensagem'
            ];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($warn));
    }

    public function deleteAll($id_remetente, $id_destinatario)
    {
        $id_remetente = base64url_decode($id_remetente);
        $id_destinatario = base64url_decode($id_destinatario);

        $deleteAll = $this->mensagem->deleteAllMessage(
            [
                'remetente' => $id_remetente,
                'destinatario' => $id_destinatario
            ]
        );

        if ($deleteAll) {

            $warn = [
                'type' => 'success',
                'mensage' => 'Mensagens deletadas com sucesso!'
            ];

        } else {

            $warn = [
                'type' => 'error',
                'mensage' => 'Mensagens nao deletadas!'
            ];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($warn));
    }

    public function loadMessages($idUsuario, $idContato)
    {

        if (isset($idUsuario) && isset($idContato)) {
            $idLogado = $this->session->id_usuario;
            $idContato = base64url_decode($idContato);
            $idUsuario = base64url_decode($idUsuario);


            $messages = $this->getMessages($idUsuario, $idContato);


            foreach ($messages as $k => $message) {

                if ($message['remetente'] == $idLogado) {
                    $messages[$k]['send'] = true;
                } else {
                    $messages[$k]['send'] = false;
                }
                $messages[$k]['mensagem'] = base64_decode($message['mensagem']);
                $messages[$k]['data_enviado'] = date("d/m/Y H:i:s", strtotime($message['data_enviado']));

            }

            $this->output->set_content_type('application/json')->set_output(json_encode($messages));
        }
    }

    public function readAllMessages($idUsuario, $idContato)
    {
        $idContato = base64url_decode($idContato);
        $idUsuario = base64url_decode($idUsuario);

        if (isset($idUsuario) && isset($idContato)) {

            $data = [
                'remetente' => $idContato,
                'destinatario' => $idUsuario
            ];

            if ($this->mensagem->readAllMessages($data)) {

            }

        }
    }

    public function checkAllMessages()
    {
        $contatos = $this->getContacts($this->session->id_usuario, $this->session->id_fornecedor);
        foreach ($contatos as $k => $contato){
            $arr = [
                'remetente' => $contato['id'],
                'destinatario' => $this->session->id_usuario
            ];

            $contatos[$k]['qtd_msg'] = intval($this->mensagem->getMsgUnRead($arr)['qtd_msg']);

            if ($contatos[$k]['qtd_msg'] < 1){
               unset($contatos[$k]);
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($contatos));
    }

    private function getContacts($id_usuario, $id_fornecedor)
    {

        $dataUsuarios = [];

        $queryCheckMatriz = [
            'select' => 'id_matriz, nome_fantasia',
            'where' => "id = {$id_fornecedor}",
            'row_array' => TRUE,
            'order' => NULL,
            'group' => NULL,
        ];

        $arrFornecedor = [];

        $checkMatriz = $this->fornecedor->find(
            $queryCheckMatriz['select'],
            $queryCheckMatriz['where'],
            $queryCheckMatriz['row_array']
        );

        $groupName = "";

        $id_matriz = intval($checkMatriz['id_matriz']);

        $arrFornecedor[] = $id_fornecedor;

        if ($id_matriz == 0) {

            $groupName = $checkMatriz['nome_fantasia'];

        } else {

            $nome_matriz = $this->fornecedor->getMatriz($id_matriz)['nome'];

            if (IS_NULL($nome_matriz) || empty($nome_matriz)) {

                $groupName = $checkMatriz['nome_fantasia'];

            } else {

                $groupName = $nome_matriz;
            }

            $queryGetFiliais =
                [
                    'select' => 'id',
                    'where' => "id_matriz = {$id_matriz} AND id <> {$id_fornecedor}",
                    'row_array' => FALSE,
                    'order' => NULL,
                    'group' => NULL,
                ];

            $getFiliais = $this->fornecedor->find(
                $queryGetFiliais['select'],
                $queryGetFiliais['where'],
                $queryGetFiliais['row_array']
            );

            foreach ($getFiliais as $filial)
                $arrFornecedor[] = intval($filial['id']);

        }

        $filiais = implode(",", $arrFornecedor);

        $queryGetUsuarios =
            [
                'select' => "nome, id, email, foto, '{$groupName}' AS NIVEL",
                'where' => "id_fornecedor IN ({$filiais}) AND id <> {$id_usuario}",
                'row_array' => FALSE,
                'order' => 'nome',
                'group' => 'nome, id, email, foto'
            ];

        $getUsuarios = $this->usuario->find(
            $queryGetUsuarios['select'],
            $queryGetUsuarios['where'],
            $queryGetUsuarios['row_array'],
            $queryGetUsuarios['order'],
            $queryGetUsuarios['group']
        );

        $fields = "usuarios.nome, usuarios.id, usuarios.email, usuarios.foto, 'PHARMANEXO' AS NIVEL";

        $getAdmUsers = $this->usuario->listAdmUsers($fields);

        if (!empty($getUsuarios)) {

            $dataUsuarios = array_merge($getUsuarios, $getAdmUsers);

        } else {

            $dataUsuarios = $getAdmUsers;
        }

        foreach ($dataUsuarios as $k => $data) {

            $dataUsuarios[$k]['url'] = base_url("{$this->route}messages/") . base64url_encode($data['id']);

            # Caminho da pasta da logo do fornecedor

            $root_path_logo = 'public/usuarios/' . $data['id'] . '/' . $data['foto'];

            if (isset($data['foto']) && !empty($data['foto']) && file_exists($root_path_logo)) {

                $dataUsuarios[$k]['src_logo'] = base_url($root_path_logo);

            } else {

                $dataUsuarios[$k]['src_logo'] = base_url('images/usuarios/no-user.png');
            }

            $arr = [
                'remetente' => $data['id'],
                'destinatario' => $this->session->id_usuario
            ];

            $dataUsuarios[$k]['qtd_msg'] = intval($this->mensagem->getMsgUnRead($arr)['qtd_msg']);

        }

        sort($dataUsuarios);

        return $dataUsuarios;
    }

    private function getMessages($id_remetente, $id_destinatario)
    {

        $getMesanges = $this->mensagem->getMessages(
            [
                'remetente' => $id_remetente,
                'destinatario' => $id_destinatario

            ]);

        return $getMesanges;
    }
}