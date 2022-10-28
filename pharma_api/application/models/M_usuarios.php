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

        return $this->db->get()->$method();
    }

    public function insert($data)
    {
        if (!isset($data)) return [ 'status' => false ];

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

        //Pesquisa se o email já existe no BD
        $id_user = $this->db->where('email', $data['email'])->get($this->table);

        // Busca se o email informado pertence ao proprio usuario que esta logado
        $this->db->where('id_usuario', $id_user->row_array()['id']);
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);
        $msmUsuario = $this->db->get('usuarios_fornecedores');

        if ($msmUsuario->num_rows() > 0) {
            return [ 'status' => false, 'msg' => 'Este email já se encontra cadastrado.' ];
        }

        // Se existir, somente registra em grupo_usuario
        if ($id_user->num_rows() > 0) {

            $user_fornecedor = [
                'id_usuario' => $id_user->row_array()['id'],
                'id_fornecedor' => $this->session->id_fornecedor,
                'tipo' => 1
            ];

            $this->db->insert('usuarios_fornecedores', $user_fornecedor);

            return [ 'status' => true ];

        } else {
            $this->db->trans_begin();

            $this->db->insert('usuarios', $data);

            $ligacao['id_usuario'] = $this->db->insert_id();

            $this->db->insert('usuarios_fornecedores', $ligacao);

            if ($status = $this->db->trans_status() === true) {
                $this->db->trans_commit();
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
        $post['tipo_usuario'] = $tipo;

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
                'tipo' => $post['nivel']
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
}
