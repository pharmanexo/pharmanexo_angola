<?php

class M_login extends CI_Model
{
    private $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = "usuarios";
    }

    public function logar($data)
    {
        if (!isset($data['login']) || !isset($data['senha'])) {

            return ['error' => 1, 'message' => "Dados não informados"];
        } 

        # Obtem o registro do usuario com o email informado
        $q = $this->db->select('*')->where("email", $data['login'])->get("usuarios")->row_array();


        if ( !is_null($q)) {

            #verifica se o usuario está dentro do horario permitido
            if (isset($q['login_fe']) && $q['login_fe'] == 0 && $q['administrador'] == 0){

                if (( (intval(date('N')) < 6) && (intval(date('G')) < 7 || intval(date('G')) > 20)) || date('N') > 5){
                    $notify = $this->notify->alertMessage('OUT_HOURS');
                    return ['error' => 1, 'message' => $notify ];
                }
            }

            # Verifica se o usuario não esta bloqueado
            if ( isset($q['situacao']) && $q['situacao'] == 1 ) {

                # Verifica se a senha informada combina com a do registro
                if (password_verify($data['senha'], $q['senha'])) {

                    # Se existir session de tentativas de erro, remove o seu usuario da session
                    if ( $this->session->has_userdata('access_try') && isset($this->session->access_try[$q['id']]) ) {

                        $usersSession = $this->session->access_try;

                        # Remove o indice do login autenticado
                        unset($usersSession[$q['id']]);
                        $this->session->set_userdata(['access_try' => $usersSession ]);

                        # Define tempo de expiração pra session
                        $this->session->mark_as_temp('access_try', 120);

                        $this->db->where('id', $q['id'])->update('usuarios', ['validade_token' => null]);
                    }

                    return $q;
                } else {

                    # Se o usuario solicitar desbloqueio para o admin, a col. validade_token estará com prazo de 2 minutos
                    if ( isset($q['validade_token']) && strtotime(date('Y-m-d H:i:s')) <= strtotime($q['validade_token']) ) {

                        # Reseta a session do usuario caso ele erre denovo a senha depois do desbloqueio
                        $usersSession = $this->session->access_try;
                        unset($usersSession[$q['id']]);
                        $this->session->set_userdata(['access_try' => $usersSession ]);
                    }

                    # Verifica se existe session de erro de acesso com o login informado
                    if ( $this->session->has_userdata('access_try') && isset($this->session->access_try[$q['id']]) && $this->session->access_try[$q['id']]['email'] == $data['login'] ) {

                        # Incrementa a session
                        $count_trys = $this->session->access_try[$q['id']]['try'] + 1;

                        $usersSession = $this->session->access_try;
                        $usersSession[$q['id']] = ['email' => $data['login'], 'try' => $count_trys];

                        if ( $count_trys >= 3 ) {

                            # Bloqueia o usuario
                            $this->db->where('id', $q['id'])->update('usuarios', ['situacao' => 0]);

                            $notify = $this->notify->alertMessage('ACCESS_ATTEMPTS');
                            return ['error' => 1, 'message' => $notify ];
                        } else {

                            $this->session->set_userdata(['access_try' => $usersSession ]);
                        }
                    } else {

                        # Insere na session o ID do usuario, para contabilizar suas tentativas de acesso
                        $usersSession = $this->session->access_try;
                        $usersSession[$q['id']] = ['email' => $data['login'], 'try' => 1];

                        $this->session->set_userdata(['access_try' => $usersSession ]);
                    }
                }
            } else {

                $notify = $this->notify->alertMessage('ACCESS_BLOCKED');
                return ['error' => 1, 'message' => $notify ];
            }
        } else {

            return ['error' => 1, 'message' => "Login não encontrado!"];
        }
    }

    public function recuperar_senha()
    {

        $email = $this->input->post("e_mail");

        $consulta = $this->db->get_where("usuarios", array("e_mail" => $email));

        if ($consulta->num_rows() > 0)
            return true;
        else
            return false;
    }

    public function grava_chave($chave = nulll, $e_mail = null)
    {
        if ($this->db->insert("recuperar_senha", array("chave" => $chave, "e_mail" => $e_mail)))
            return true;
        else {
            return false;
        }
    }

    public function verifica_chave($chave = null)
    {

        $this->db->where("TIMESTAMPDIFF(MINUTE, data_hora, now()) <= 120");
        $consulta = $this->db->get_where("recuperar_senha", array("chave" => $chave));

        if ($consulta->num_rows() > 0) {
            return true;
        } else
            return false;
    }

    public function salvar_senha_recuperada()
    {

        $senha = md5($this->input->post("senha_1"));
        $chave = $this->input->post("chave");

        $email = $this->db->get_where("recuperar_senha", array("chave" => $chave))->row()->email;

        $query = $this->db->where("email", $email)->update("usuarios", array("senha" => $senha));

        /*
         * Após alterar a senha, excluo a chave do banco por segurança.
         */
        $this->db->where("chave", $chave);
        $this->db->delete("recuperar_senha");

        if ($query)
            return true;
        else
            return false;
    }
}
