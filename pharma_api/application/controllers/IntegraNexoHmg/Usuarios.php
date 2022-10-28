<?php

class Usuarios extends MY_Controller_hmg
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_usuarios', 'usuario');
    }

    public function listar()
    {
        $tipos = [
            0 => 'ADMINISTRADOR',
            1 => 'FINANCEIRO',
            2 => 'COMERCIAL'
        ];



        $usuarios = $this->db->select('u.id, u.nome, u.email, uf.tipo, u.situacao')
            ->from('usuarios_fornecedores uf')
            ->join('usuarios u', 'u.id = uf.id_usuario')
            ->where('uf.id_fornecedor', $this->session->id_fornecedor)
            ->get()
            ->result_array();

        foreach ($usuarios as $k => $usuario){

            $usuarios[$k]['nivel'] = $tipos[$usuario['tipo']];
            $usuarios[$k]['situacao'] = ($usuario['situacao'] == 1) ? 'ATIVO' : 'INATIVO';
            unset($usuarios[$k]['tipo']);

        }

        $this->output->set_content_type('application/json')
            ->set_status_header(200)->set_output(json_encode($usuarios));
    }

    public function cadastrar()
    {
        $post = json_decode(file_get_contents("php://input"), true);

        $output = [];

        foreach ($post as $getDados) {

            $getUser = $this->db
                ->where('cpf', $getDados['cpf'])
                ->get('usuarios')
                ->row_array();


            if (!empty($getUser)) {
                if (($getUser['usuario_externo'] != $getDados['cod_usuario']) || ($getUser['situacao'] != $getDados['situacao'])) {

                    $dataUpdate = [
                        'usuario_externo' => $getDados['cod_usuario'],
                        'situacao' => $getDados['situacao']
                    ];

                    $this->db
                        ->where('id', $getUser['id'])
                        ->update('usuarios', $dataUpdate);

                    $output[] = ['type' => 'success', 'message' => "Usuário {$getDados['nome']} atualizado com sucesso."];
                }else{
                    $output[] = ['type' => 'warning', 'message' => "CPF {$getDados['cpf']} já cadastrado!"];
                }
            } else {

                $result = $this->novoUsuario($getDados);
                $output[] = $result;

            }

        }

        if (empty($output)) {
            $output = ['type' => 'sucess', 'message' => 'Usuário(s) inserido(s) com sucesso.'];
        }


        $this->output->set_content_type('application/json')
            ->set_status_header(200)->set_output(json_encode($output));

    }

    private function novoUsuario($getDados)
    {
        $password = generatePassword();

        //verificar se o email não existe
        $getUserMail = $this->db
            ->where('email', $getDados['email'])
            ->get('usuarios')
            ->row_array();

        if (empty($getUserMail)) {
            $data = [
                'nome' => $getDados['nome'],
                'email' => $getDados['email'],
                'senha' => $password,
                'cpf' => $getDados['cpf'],
                'nivel' => 2,
                'tipo' => 1,
                'situacao' => 1,
                'fornecedores' => [$this->session->id_fornecedor]
            ];

            $status = $this->usuario->salvar($data);

            if ($status){

                $notify = [
                    "to" => $getDados['email'],
                    "cco" => 'marlon.boecker@pharmanexo.com.br',
                    "greeting" => $getDados['nome'],
                    "subject" => "Bem-vindo ao Portal Pharmanexo",
                    "message" => "Seu usuário pharmanexo foi criado utilize os dados abaixo para acessar o portal.
                                        <br><br> 
                                        <p style='text-align: center'>
                                        <strong>Login:</strong> {$getDados['email']} <br> 
                                        <strong>Senha: </strong> {$password}<br><br>
                                        
                                        <a href='https://pharmanexo.com.br/pharmanexo_v2' style='padding: 15px; border-radius: 5px; text-decoration: none; background-color: #d2d2d2; color: #204a8f'>ACESSE O PORTAL PHARMANEXO</a>
                                        </p> 
                                        <br> 
                                        <br> 
                                        Visando a segurança das informações, aconselhamos a troca de senha após o primeiro login. <br><br> 
                                        Atenciosamente, <br><br> Equipe Pharmanexo"
                ];

                $send = $this->notify->send($notify);

                return ['type' => 'success', 'message' => "Usuário {$getDados['nome']} cadastrado com sucesso, senha enviada no e-mail."];

            }else{
                return ['type' => 'error', 'message' => "Erro ao cadastrar o usuário {$getDados['nome']}"];
            }


        } else {
            return ['type' => 'error', 'message' => "E-mail {$getDados['email']} já cadastrado"];
        }
    }

}