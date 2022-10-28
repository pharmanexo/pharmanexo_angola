<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_usuarios extends MY_Model
{
    protected $table = 'usuarios';
    protected $vw = 'vw_fornecedores_usuarios';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = 'id';
    protected $order_direction = 'ASC';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * obtem a lista de usuarios do admin Master
     *
     * @return  array
     */
    public function listAdmMaster()
    {
        return $this->db->select('usuarios.*')
            ->from('usuarios')
            ->join('perfis', "perfis.id = usuarios.nivel")
            ->where('usuarios.tipo_usuario', 0)
            ->where('perfis.titulo', "Master")
            ->get()
            ->result_array();
    }

    public function listAdmUsers($fields = '*')
    {
        return $this->db->select($fields)
            ->from('usuarios')
            ->join('perfis', "perfis.id = usuarios.nivel")
            ->where('usuarios.tipo_usuario', 0)
            ->get()
            ->result_array();
    }

    public function listarFornecedorUsers($id_fornecedor)
    {
        return $this->db->select("u.*")
            ->from('usuarios u')
            ->join('usuarios_fornecedores', "usuarios_fornecedores.id_usuario = u.id")
            ->where('usuarios_fornecedores.id_fornecedor', $id_fornecedor)
            ->get()
            ->result_array();
    }

    public function update_password($id, $new_password)
    {
        if (!isset($id) && !isset($new_password)) return FALSE;

        $this->db->where('id', $id);

        return $this->db->update('usuarios', ['password' => password_hash($new_password, PASSWORD_DEFAULT)]);
    }

    public function find($fields = '*', $where = NULL, $single = FALSE, $order = NULL, $group = null)
    {
        $method = ($single == TRUE) ? 'row_array' : 'result_array';

        $this->db->select($fields);
        $this->db->from($this->vw);
        if (isset($where)) $this->db->where($where);
        if (isset($order)) $this->db->order_by($order);
        if (isset($group)) $this->db->group_by($group);

        return $this->db->get()->$method();
    }

    public function insert($data)
    {
        if (!isset($data)) return ['status' => false];

        $ligacao['id_fornecedor'] = $this->session->id_fornecedor;

        if (isset($data['senha']) && !empty($data['senha'])) {
            $data['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
        } else {
            unset($data['senha']);
        }

        if (isset($data['tipo'])) {
            $ligacao['tipo'] = $data['tipo'];
            unset($data['tipo']);
        }

        //Pesquisa se o cpf já existe no BD
        $cpf_user = $this->db->where('cpf', $data['cpf'])->get($this->table);
        var_dump($cpf_user->num_rows());
        exit();
        if($cpf_user->num_rows() > 0){
            return ['status' => false, 'msg' => 'Este cpf já se encontra cadastrado.'];
        }

        //Pesquisa se o email já existe no BD
        $id_user = $this->db->where('email', $data['email'])->get($this->table);

        // Busca se o email informado pertence ao proprio usuario que esta logado
        $this->db->where('id_usuario', $id_user->row_array()['id']);
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);
        $msmUsuario = $this->db->get('usuarios_fornecedores');

        if ($msmUsuario->num_rows() > 0) {
            return ['status' => false, 'msg' => 'Este email já se encontra cadastrado.'];
        }

        // Se existir, somente registra em grupo_usuario
        if ($id_user->num_rows() > 0) {

            $user_fornecedor = [
                'id_usuario' => $id_user->row_array()['id'],
                'id_fornecedor' => $this->session->id_fornecedor,
                'tipo' => 1
            ];

            $this->db->insert('usuarios_fornecedores', $user_fornecedor);

            return ['status' => true];

        } else {
            $this->db->trans_begin();

            $this->db->insert('usuarios', $data);

            $ligacao['id_usuario'] = $this->db->insert_id();

            $this->db->insert('usuarios_fornecedores', $ligacao);

            if ($status = $this->db->trans_status() === true) {
                // $this->db->trans_commit();
            } else {
                $this->db->trans_rollback();
            }

            return ['status' => $status];
        }
    }

    public function update($data)
    {
        $id = $data['id'];

        unset($data['id']);
        unset($data['email']);

        if (isset($data['tipo'])) {
            $ligacao['tipo'] = $data['tipo'];
            unset($data['tipo']);
        }

        if (isset($data['senha']) && !empty($data['senha'])) {

            $data['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
        } else {

            unset($data['senha']);
        }

        $this->db->trans_begin();

        $this->db->update($this->table, $data, "id = {$id}");

        if (isset($ligacao)) {
            $this->db->update('usuarios_fornecedores', $ligacao, "id_usuario = {$id}");
        }

        if ($status = $this->db->trans_status() === true) {

            $this->db->trans_commit();
        } else {

            $this->db->trans_status();
        }

        return ['status' => $status];
    }

    public function findById($id)
    {
        if (!isset($id)) return false;

        $filter = $this->primary_filter;
        $id = $filter($id);

        $this->db->where($this->primary_key, $id);
        $this->db->limit(1);

        return $this->db->get($this->vw)->row_array();
    }

    public function getById($id)
    {
        if (!isset($id)) return false;

        $filter = $this->primary_filter;
        $id = $filter($id);

        $this->db->where($this->primary_key, $id);
        $this->db->limit(1);

        return $this->db->get($this->table)->row_array();
    }

    /**
     * Verifica se já existe email no BD
     *
     * @param - id usuario
     * @param - email usuario
     * @return bool
     */
    public function check_unique_email($id, $email)
    {
        $this->db->where('email', $email);
        $this->db->where_not_in('id', $id);

        if ($this->db->get($this->table)->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    #ADMIN

    /**
     * Cadastro de usuario na area de admin
     *
     * @param - array $post
     * @return  json
     */
    public function salvar($post)
    {
        $this->load->library('upload');

        $fornecedores = $post['fornecedores'];
        $tipo = $post['tipo'];
        $post['senha'] = password_hash($post['senha'], PASSWORD_DEFAULT);

        unset($post['c_senha']);
        unset($post['fornecedores']);
        unset($post['tipo']);

        $this->db->trans_begin();


        if (isset($post['administrador'])) $post['administrador'] = 1;
        $post['tipo_usuario'] = 1;

        $this->db->insert($this->table, $post);

        $id = $this->db->insert_id();

        $rotasAdmin = [];

        if (isset($post['administrador'])) {

            $this->db->where('grupo', 0);
            $rotas = $this->db->get('rotas')->result_array();

            foreach ($rotas as $rota) {
                $rotasAdmin[] = [
                    'id_usuario' => $id,
                    'id_rota' => $rota['id']
                ];
            }

            $this->db->insert_batch('admin_rotas', $rotasAdmin);
        }


        $data = [];

        // Armazena o usuario com cada fornecedor
        foreach ($fornecedores as $fornecedor) {
            $data[] = [
                'id_usuario' => $id,
                'id_fornecedor' => $fornecedor,
                'tipo' => $tipo
            ];

            if (isset($post['administrador'])) {

            }

        }

        $this->db->insert_batch('usuarios_fornecedores', $data);

        // salvar foto
        if (isset($_FILES['foto']['name']) && !empty($_FILES['foto']['name'])) {

            $config['upload_path'] = PUBLIC_PATH . "usuarios/{$id}";
            $config['allowed_types'] = 'png|gif|jpg|jpeg';
            // $config['max_size'] = 1000;
            // $config['max_width'] = 1024;
            // $config['max_height'] = 768;
            $config['encrypt_name'] = TRUE;

            if (!file_exists($config['upload_path'])) mkdir($config['upload_path'], 0777, true);

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('foto')) {

                $this->db->trans_rollback();
                return ["status" => false, "message" => $this->upload->display_errors()];
            } else {

                $foto = $this->upload->data()['file_name'];

                // Atualiza o campo foto de usuario
                $this->db->where('id', $id);
                $this->db->update('usuarios', ['foto' => $foto]);
            }
        }


        if ($this->db->trans_status() === FALSE) {

            $warning = ['status' => false, 'message' => 'Erro ao cadastrar usuário'];
            $this->db->trans_rollback();
        } else {

            $this->db->trans_commit();

            $warning = ["status" => true];
        }

        return $warning;
    }

    /**
     * Atualização de usuario na area de admin
     *
     * @param - array $post
     * @return  json
     */
    public function atualizar($post)
    {
        $this->load->library('upload');

        $fornecedores = $post['fornecedores'];
        $tipo = $post['tipo'];
        $id = $post['id'];

        unset($post['id']);
        unset($post['c_senha']);
        unset($post['fornecedores']);
        unset($post['tipo']);

        $this->db->trans_begin();

        $data = [];

        $usuario = $this->usuario->findById($id);

        // Atualiza email
        if ($post['email'] == $usuario['email']) {

            unset($post['email']);
        }

        // Atualiza senha
        if (isset($post['senha']) && !empty($post['senha'])) {

            $post['senha'] = password_hash($post['senha'], PASSWORD_DEFAULT);
        } else {

            unset($post['senha']);
        }

        // Altera os registros de fornecedores
        if (!empty($fornecedores)) {

            // Remove todos os existes para atualizar pelos novos
            $this->db->where('id_usuario', $id)->delete('usuarios_fornecedores');

            foreach ($fornecedores as $fornecedor) {
                $data[] = [
                    'id_usuario' => $id,
                    'id_fornecedor' => $fornecedor,
                    'tipo' => $tipo
                ];
            }

            $this->db->insert_batch('usuarios_fornecedores', $data);
        }

        // salvar foto
        if (isset($_FILES['foto']['name']) && !empty($_FILES['foto']['name'])) {

            $config['upload_path'] = PUBLIC_PATH . "usuarios/{$id}";
            $config['allowed_types'] = 'png|gif|jpg|jpeg';
            $config['encrypt_name'] = TRUE;

            if (!file_exists($config['upload_path'])) mkdir($config['upload_path'], 0777, true);

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('foto')) {

                $this->db->trans_rollback();
                return ["status" => false, "message" => $this->upload->display_errors()];
            } else {

                $post['foto'] = $this->upload->data()['file_name'];
            }
        } else {
            unset($post['foto']);
        }

        // Administrador
        if (isset($post['administrador'])) {
            $post['administrador'] = 1;
        } else {
            $post['administrador'] = 0;
        }

        // Atualiza usuario
        $this->db->where('id', $id);
        $this->db->update('usuarios', $post);

        if ($this->db->trans_status() === FALSE) {

            $warning = ['status' => false, 'message' => 'Erro ao atualizar usuário'];
            $this->db->trans_rollback();
        } else {

            $this->db->trans_commit();

            $warning = ["status" => true];
        }

        return $warning;
    }

    public function getMetaUser($id_usuario = null, $day = false, $all = false, $month = false, $integrador = 2)
    {


        $this->db->select("lp.id_usuario, u.nome, count(0) as total");
        $this->db->from('log_de_para lp');
        $this->db->join('usuarios u', "u.id = lp.id_usuario");

        if (!empty($id_usuario)) {
            $this->db->where('lp.id_usuario', $id_usuario);
        }

        if (!$month) {
            if ($day) {
                $this->db->where("date(lp.data_criacao) = date(now())");
            } else {
                $this->db->where("month(lp.data_criacao) = month(now()) and year(lp.data_criacao) = year(now())");
            }

        }

        if ($month) {
            $this->db->select("lp.id_usuario, u.nome, count(0) as total, month(lp.data_criacao) as mes, year(lp.data_criacao) as ano");
            $this->db->group_by('lp.id_usuario, month(lp.data_criacao), year(lp.data_criacao)');
        } else {
            $this->db->group_by('lp.id_usuario');
        }

       // $this->db->where('integrador', $integrador);

        $q = $this->db->get();

        if ($all) {
            return $q->result_array();
        } else {
            return $q->row_array();
        }


    }

    public function getHospitaisAbertos($id_usuario, $integrador)
    {
        $this->db->select('rd.*, c.id, c.nome_fantasia, c.estado');
        $this->db->from('responsaveis_depara rd');
        $this->db->join('compradores c', 'c.id = rd.id_cliente');
        $this->db->where('rd.id_usuario', $id_usuario);
        $this->db->where('rd.integrador', $integrador);
        $this->db->where('rd.fim is null');
        return $this->db->get()->result_array();
    }

    public function getHospitaisFinalizados($id_usuario, $integrador)
    {
        $this->db->select('rd.*, c.id, c.nome_fantasia, c.estado');
        $this->db->from('responsaveis_depara rd');
        $this->db->join('compradores c', 'c.id = rd.id_cliente');
        $this->db->where('rd.id_usuario', $id_usuario);
        $this->db->where('rd.integrador', $integrador);
        $this->db->where('rd.fim is not null');
        return $this->db->get()->result_array();
    }

    public function getTotalHospitais($id_usuario, $integrador)
    {
        return $this->db
            ->query("select count(distinct id_cliente) as total from responsaveis_depara 
                            where id_usuario = {$id_usuario} and integrador = {$integrador} and fim is not null")->row_array()['total'];
    }


    public function getUserDepara()
    {
        return $this->db->distinct()->select('lp.id_usuario, u.nome')->from('log_de_para lp')->join('usuarios u', "u.id = lp.id_usuario")->get()->result_array();
    }



    public function countHours($id_usuario, $integrador){
        $this->db->where('id_usuario', $id_usuario);
        $this->db->where('integrador', $integrador);
        $this->db->where('fim is not null');
        $data = $this->db->get('responsaveis_depara')->result_array();

        if (!empty($data)){
            foreach ($data as $item){
                var_dump($item);
                $start_date = new DateTime($item['inicio']);
                $end_date = new DateTime($item['fim']);

                $diff = $end_date->diff($start_date);

                echo $diff->h .':';
                echo $diff->i;
                exit();
            }
        }



    }

}
