<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

class M_fornecedor extends MY_Model
{
    protected $table = 'fornecedores';
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
     * Cria fornecedor
     *
     * @param campos do form
     * @return id fornecedor/false
     */
    public function salvar($postData)
    {
        // inicia uma transação para realizar mais de 1 ação no BD
        $this->db->trans_begin();

//        unset($postData['c_senha']);

        // Trata os campos
        $postData['id_matriz'] = empty($postData['id_matriz']) ? null : $postData['id_matriz'];
        $postData['sintese'] = empty($postData['sintese']) ? null : $postData['sintese'];
//        $postData['senha'] = password_hash($postData['senha'], PASSWORD_DEFAULT);
        $postData['validade_alvara'] = isset($postData['validade_alvara']) ? dbDateFormat($postData['validade_alvara']) : null;
        $postData['aprovado'] = isset($postData['aprovado']) ? $postData['aprovado'] : 0;
        $postData['status'] = 1;

        $this->fornecedor->insert($postData);

        // Obtem o ID do fornecedor
        $id = $this->db->insert_id();

        // Verifica o tipo
        $this->db->where('id_fornecedor', $id);
        if ( $this->db->get('usuarios_fornecedores')->num_rows() < 1 ) {
            $tipo = 0; // Admin
        } else {
            $tipo = 1; // fornecedor
        }

        $dataUsuario = [
            'tipo_usuario' => $tipo,
            'nivel' =>  1,
            'nome' => $postData['razao_social'],
            'email' => $postData['email'],
//            'senha' => $postData['senha'],
            'telefone' => $postData['telefone'],
            'celular' => $postData['celular'],
            'situacao' => 1,
        ];

        // cria usuario
        $this->db->insert('usuarios', $dataUsuario);

        $ligacao = [
            'id_usuario' => $this->db->insert_id(),
            'id_fornecedor' => $id,
            'tipo' => 1
        ];

        // Registra na tabela usuarios_fornecedores
        $this->db->insert('usuarios_fornecedores', $ligacao);

        $rotas = $this->rotas->find('id', 'grupo = 1');

        // Registra as rotas
        foreach ($rotas as $key => $value) {
            $dataRota = [
                'id_rota' => $value['id'],
                'tipo_usuario' => 1,
                'id_fornecedor' => $id
            ];

            $this->gur->insert($dataRota);
        }

        // Armazena o caminho dos arquivos de casa fornecedor
        $path = PUBLIC_PATH . "fornecedores/{$id}";

        $arquivos = ['documento_alvara', 'cartao_cnpj', 'logo', 'responsabilidade_tecnica', 'copia_afe'];

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

        if (!empty($update)) {
            $update['id'] = $id;
            $this->fornecedor->update($update);
        }


        // Verifica se as transações ocorreram e commita
        if ($this->db->trans_status() !== false) {

            $this->db->trans_commit();

            $response['status'] =  true;

        } else {

            // Se der errado desfaz as transações e retorna erro
            $this->db->trans_rollback();

            $response['status'] =  false;
            $response['error'] = "Erro ao cadastrar fornecedor";
        }

        return $response;
    }

    /**
     * Atualiza fornecedor
     *
     * @param - request do form
     * @param - id do fornecedor
     * @return bool
     */
    public function atualizar($postData, $id)
    {
        $path = PUBLIC_PATH . "fornecedores/{$id}";

        $fornecedor = $this->fornecedor->findById($id);
        
        $data = [
            'cnpj' => $postData['cnpj'],
            'nome_fantasia' =>  $postData['nome_fantasia'],
            'razao_social' =>  $postData['razao_social'],
            'protocolo_alvara' => $postData['protocolo_alvara'],
            'inscricao_estadual' =>  $postData['inscricao_estadual'],
            'inscricao_municipal' => $postData['inscricao_municipal'],
            'validade_alvara' =>  isset($postData['validade_alvara']) ? dbDateFormat($postData['validade_alvara']) : null,
            'motivo_recusa' =>  $postData['motivo_recusa'] ,
            'aprovado' =>  isset($postData['aprovado']) ? $postData['aprovado'] : 0,
            'numero_afe' =>  $postData['numero_afe'],
            'integracao' =>  $postData['integracao'],
            'id_tipo_venda' =>  $postData['id_tipo_venda'],
            'permitir_cadastro_prod' =>  $postData['permitir_cadastro_prod'],
            'estado' =>  $postData['estado'],
            'endereco' =>  $postData['endereco'],
            'cidade' =>  $postData['cidade'],
            'bairro' =>  $postData['bairro'],
            'numero' =>  $postData['numero'],
            'cep' =>  $postData['cep'],
            'id_matriz' => empty($postData['id_matriz']) ? null : $postData['id_matriz'],
            'sintese' => empty($postData['sintese']) ? null : $postData['sintese'],
            'complemento' => $postData['complemento'] ,
            'email' =>  $postData['email'],
            'telefone' =>  $postData['telefone'],
            'celular' =>  $postData['celular'],
            'usuarios_permitidos' =>  $postData['usuarios_permitidos'],
        ];

        if ( isset($postData['senha']) && !empty($postData['senha']) ) {
            $data['senha'] = password_hash($postData['senha'], PASSWORD_DEFAULT);

            //Localiza o id do usuario
            $id_usuario = $this->db->where('id_fornecedor', $id)->get('usuarios_fornecedores')->row_array()['id_usuario'];

            //Atualiza senha do usuario
            $this->db->where('id', $id_usuario)->update('usuarios', ['senha' => $data['senha']]);
        }

        $arquivos = ['documento_alvara', 'cartao_cnpj', 'logo', 'responsabilidade_tecnica', 'copia_afe'];

        foreach ($arquivos as $arquivo) {
            // Verifica se o arquivo existe
            if ( isset($_FILES[$arquivo]) && !empty($_FILES[$arquivo]['name']) ) {
                
                // Faz upload do arquivo
                $arq = $this->saveFile($path, $arquivo);
                // Verifica se o upload funcionou
                if ($arq['status']) {

                    if (  !empty($fornecedor[$arquivo]) ) {

                        // Verifica se existe arquivo salvo
                        if( file_exists($path . '/' . $fornecedor[$arquivo]) ) {
                            // Verifica se excluiu o arquivo antigo
                            if (unlink($path . '/' . $fornecedor[$arquivo])) {
                                $data[$arquivo] = $arq['data'];
                            }
                        }
                    } else {
                        $data[$arquivo] = $arq['data'];
                    }
                } else {
                    return ['status' => false, 'error' => $arq['data']];
                }
            }
        }
        
        $this->db->where('id', $id);

        if ($this->db->update('fornecedores', $data)) {

            // Se cadastrou logo, atualiza a session para exibir imagem no perfil
            if (isset($data['logo'])) 
               $this->session->set_userdata('logo', $data['logo']);

            return ['status' => true ];
        }else {
            return ['status' => false, 'error' => notify_update];
        }
    }

    /**
     * Deleta fornecedores
     *
     * @param - id fornecedor
     * @return bool
     */
    public function excluir($id)
    {
        $this->db->where('id', $id);

        $data = ['status' => '3'];

        if ( $this->db->update('fornecedores', $data) ) {
            return TRUE;
        }else {
            return FALSE;
        }
    }

    /**
     * Atualiza status
     *
     * @param - id fornecedor
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
     * Verifica se já existe cnpj no BD
     *
     * @param - id fornecedor
     * @param - cnpj fornecedor
     * @return  - filename/false
     */
    public function check_unique_cnpj($id, $cnpj)
    {
        $this->db->where('cnpj', $cnpj);
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
     * @param - objeto arquivo
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

    /**
     * Obtem o registro da matriz
     *
     * @param - INT id da matriz
     * @return - array
     */
    public function getMatriz($id_matriz)
    {

        $getMatriz = $this->db->where('id', $id_matriz)
            ->get('fornecedores_matriz')
            ->row_array();

        return $getMatriz;

    }
}
