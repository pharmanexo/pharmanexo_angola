<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_representante extends MY_Model
{
    protected $table = 'representantes';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = 'id';
    protected $order_direction = 'ASC';

    public function __construct()
    {
        parent::__construct();
        $this->load->helper("file");
        $this->load->model('Rota', 'rotas');
        $this->load->model('Grupo_usuario_rota', 'gur');
    }

    /**
     * Cria representante
     *
     * @param campos do form
     * @return id representante/false
     */
    public function salvar($postData)
    {
        $estados = isset($postData['estados']) ? $postData['estados'] : '';
        $postData['status'] = 1;

        unset($postData['estados']);
        unset($postData['fornecedores']);

        // inicia uma transação para realizar mais de 1 ação no BD
        $this->db->trans_begin();

        // Salva no BD
        $this->db->insert('representantes', $postData);

        // Obtem o ID do representante
        $id = $this->db->insert_id();

        if (isset($_SESSION['id_fornecedor'])){

            $data = [
                'id_fornecedor' => $this->session->id_fornecedor,
                'id_representante' => $id
            ];

            $this->db->insert('representantes_fornecedores', $data);

        }

        // estados
        if ( !empty($estados) ) {
           
            $rep_estados = [];
            foreach ($estados as $id_estado) {
             
                $rep_estados[] = [
                    'id_estado' => $id_estado,
                    'id_representante' => $id
                ];
            }

            $this->db->insert_batch('representantes_estados', $rep_estados);
        }

        // Armazena o caminho dos arquivos de cada representante
        $path = PUBLIC_PATH . "representantes/{$id}";

        $arquivos = ['copia_social', 'copia_cnpj', 'copia_id'];

        $update = [];

        foreach ($arquivos as $arquivo) {
            // Verifica se o arquivo existe
            if ( isset($_FILES[$arquivo]) && !empty($_FILES[$arquivo]['name']) ) {
                
                // Faz upload do arquivo
                $arq = $this->saveFile($path, $arquivo);
                // Verifica se o upload funcionou
                if ($arq['status']) {
                    $update[$arquivo] = $arq['data'];
                } else {
                    return ['status' => false, 'error' => $arq['data']];
                }
            }
        }

        $this->db->where('id', $id)->update('representantes', $update);

        // Verifica se as transações ocorreram e commita
        if ($this->db->trans_status() !== false) {

            $this->db->trans_commit();

            $response['status'] =  true;
            $response['url'] =  base_url("admin/representantes/atualizar/{$id}");
        } else {

            // Se der errado desfaz as transações e retorna erro
            $this->db->trans_rollback();

            $response['status'] =  false;
            $response['error'] = "Erro ao cadastrar representante";
        }

        return $response;
    }

    /**
     * Atualiza representante
     *
     * @param - request do form
     * @param - id do representante
     * @return bool
     */
    public function atualizar($postData, $id)
    {
        // Alterar senha
        if ( isset($postData['senha']) && !empty($postData['senha']) ) {
            
            $postData['senha'] = password_hash($postData['senha'], PASSWORD_DEFAULT);
        } else {

            unset($postData['senha']);
        }

        $estados = isset($postData['estados']) ? $postData['estados'] : '';

        unset($postData['c_senha']);
        unset($postData['estados']);
        unset($postData['fornecedores']);

        $this->db->trans_begin();

        // estados
        if ( !empty($estados) ) {

            // Remove todos os existes para atualizar pelos novos
            $this->db->where('id_representante', $id)->delete('representantes_estados');
           
            $rep_estados = [];
            foreach ($estados as $id_estado) {

                $rep_estados[] = ['id_estado' => $id_estado, 'id_representante' => $id ];
            }

            $this->db->insert_batch('representantes_estados', $rep_estados);
        }

        $path = PUBLIC_PATH . "representantes/{$id}";

        $representante = $this->representante->findById($id);

        // Email
        if ($representante['email'] == $postData['email'] ) { unset($postData['email']); }


        $arquivos = ['copia_social', 'copia_cnpj', 'copia_id'];

        foreach ($arquivos as $arquivo) {
            // Verifica se o arquivo existe
            if ( isset($_FILES[$arquivo]) && !empty($_FILES['name']) ) {
                
                // Faz upload do arquivo
                $arq = $this->saveFile($path, $arquivo);
                // Verifica se o upload funcionou
                if ($arq['status']) {

                    // Verifica se existe arquivo salvo
                    if( !empty($representante[$arquivo]) && file_exists($path . '/' . $representante[$arquivo]) ) {
                        // Verifica se excluiu o arquivo antigo
                        if (unlink($path . '/' . $representante[$arquivo])) {
                            $postData[$arquivo] = $arq['data'];
                        }
                    } else {
                        $postData[$arquivo] = $arq['data'];
                    }
                } else {
                    return ['status' => false, 'error' => $arq['data']];
                }
            } else {
                unset($postData[$arquivo]);
            }
        }
        
        $this->db->where('id', $id);
        $this->db->update('representantes', $postData);

        // Verifica se as transações ocorreram e commita
        if ($this->db->trans_status() !== false) {

            $this->db->trans_commit();

            $response = ['status' => true ];

        } else {
            
            // Se der errado desfaz as transações e retorna erro
            $this->db->trans_rollback();

            $response = ['status' => false, 'error' => 'Erro ao atualizar representante!'];
        }

        return $response;
    }

    /**
     * Deleta representantes
     *
     * @param - id representante
     * @return bool
     */
    public function excluir($id)
    {
        $this->db->where('id', $id);
        if ( $this->db->update('representantes', ['status' => 2]) ) {
          
            return TRUE;
        }else {
            return FALSE;
        }
    }

    /**
     * Atualiza status
     *
     * @param - id representante
     * @return bool
     */
    public function updateStatus($id, $opt)
    {
        $this->db->where('id', $id);

        $data = ['status' => $opt];

        if ($this->db->update($this->table, $data)) {
            return TRUE;
        } else {
            return FALSE;
        }
     }

    /**
     * Verifica se já existe email no BD
     *
     * @param - id representante
     * @param - email representante
     * @return bool
     */
    public function check_unique_email($id, $email)
    {
        $this->db->where('email', $email);
        $this->db->where_not_in('id', $id);

        if ($this->db->get($this->table)->num_rows() > 0) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    /**
     * Salva arquivo
     *
     * @param - caminho do arquivo
     * @param - nome do arquivo
     * @return - array
     */
    public function saveFile($path, $file)
    {
        $this->load->library('upload');
        
        $config = [];

        if (!is_dir($path)) mkdir($path, 0777, true);

        $config['encrypt_name'] = true;
        $config['upload_path'] = $path;
        $type_img = ['image/jpeg', 'image/png', 'image/gif'];

        if (in_array($_FILES[$file]['type'], $type_img)  ) {
            $config['allowed_types'] = 'jpg|gif|png|jpeg';
        } else {
            $config['allowed_types'] =  'pdf|doc';
        }   

        $this->upload->initialize($config);

        if (!$this->upload->do_upload($file)) {

            $response = [ 'status' => false, 'data' => $this->upload->display_errors() ];

        }else {
            $response = [ 'status' => true, 'data' => $this->upload->data('file_name') ];
        }

        return $response;
    }

    /** Login do representante */

    public function login($data){

        if (!isset($data['email']) || !isset($data['senha'])) return false;

        $this->db->where('email', $data['email']);
        $this->db->where('status', 1);

        $consulta = $this->db->get('representantes')->row_array();

       if (password_verify($data['senha'], $consulta['senha'])){
          return $consulta;
       }else{
           return false;
       }
    }

    public function get_empresas($id_rep){
        if (!isset($id_rep)) return false;

      return $this->db->select('fornecedores.*, id_representante, id_fornecedor')
            ->where("id_representante = {$id_rep}")
            ->from('representantes_fornecedores')
            ->join('fornecedores', 'fornecedores.id = representantes_fornecedores.id_fornecedor')
            ->get()
            ->result_array();
    }

    public function check_empresa($id_rep, $id_fornecedor){
        if (!isset($id_rep)) return false;

        return $this->db->select('*')
            ->where("id_representante = {$id_rep}")
            ->where("id_fornecedor = {$id_fornecedor}")
            ->from('representantes_fornecedores')
            ->get()
            ->row();
    }
}
