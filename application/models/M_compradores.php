<?php
/**
 * Criado por:  Marlon Boecker
 * Criado em: 31/07/2019 07:58
 */
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
class M_compradores extends MY_Model
{

    protected $table = 'compradores';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = '';
    protected $order_direction = 'ASC';
    protected $bio;


    public function __construct()
    {
        parent::__construct();
        $this->load->helper("file");

        $this->bio = $this->load->database('bionexo', true);
    }

    /**
     * Obtem o comprador atras do CNPJ
     *
     * @param String CNPJ
     * @return array
     */
    public function get_byCNPJ($cnpj)
    {
        $this->db->where("cnpj like '%{$cnpj}%'");
        return $this->db->get('compradores')->row_array();
    }

    /**
     * Cria cliente
     *
     * @param campos do form
     * @return id bool
     */
    public function salvar($postData, $pharma = false)
    {
        // inicia uma transação para realizar mais de 1 ação no BD
        $this->db->trans_begin();

        $post = [
            'razao_social' => $postData['razao_social'],
            'cnpj' => $postData['cnpj'],
            'id_tipo_venda' => $postData['id_tipo_venda'],
            'estado' => $postData['estado'],
            'status' => 1,
            'nome_fantasia' => (isset($postData['nome_fantasia']) && !empty($postData['nome_fantasia'])) ? $postData['nome_fantasia'] : null,
            'protocolo_alvara' => (isset($postData['protocolo_alvara']) && !empty($postData['protocolo_alvara'])) ? $postData['protocolo_alvara'] : null,
            'inscricao_estadual' => ( isset($postData['inscricao_estadual']) && !empty($postData['inscricao_estadual']) ) ? $postData['inscricao_estadual'] : null,
            'inscricao_municipal' => ( isset($postData['inscricao_municipal']) && !empty($postData['inscricao_municipal']) ) ? $postData['inscricao_municipal'] : null,
            'validade_alvara' => ( isset($postData['validade_alvara']) && !empty($postData['validade_alvara']) ) ? dbDateFormat($postData['validade_alvara']) : null,
            'motivo_recusa' => ($postData['motivo_recusa']) ? $postData['motivo_recusa'] : null,
            'aprovado' => ( isset($postData['aprovado']) ) ? 1 : 0,
            'numero_afe' => ( isset($postData['numero_afe']) && !empty($postData['numero_afe']) ) ? $postData['numero_afe'] : null,
            'integracao' => ( isset($postData['integracao']) && !empty($postData['integracao']) ) ? $postData['integracao'] : null,
            'responsavel' => ( isset($postData['responsavel']) && !empty($postData['responsavel']) ) ? $postData['responsavel'] : null,
            'endereco' => ( isset($postData['endereco']) && !empty($postData['endereco']) ) ? $postData['endereco'] : null,
            'cidade' => ( isset($postData['cidade']) && !empty($postData['cidade']) ) ? $postData['cidade'] : null,
            'bairro' => ( isset($postData['bairro']) && !empty($postData['bairro']) ) ? $postData['bairro'] : null,
            'cep' => ( isset($postData['cep']) && !empty($postData['cep']) ) ? $postData['cep'] : null,
            'email' => ( isset($postData['email']) && !empty($postData['email']) ) ? $postData['email'] : null,
            'numero' => ( isset($postData['numero']) && !empty($postData['numero']) ) ? $postData['numero'] : null,
            'complemento' => ( isset($postData['complemento']) && !empty($postData['complemento']) ) ? $postData['complemento'] : null,
            'telefone' => ( isset($postData['telefone']) && !empty($postData['telefone']) ) ? $postData['telefone'] : null,
            'celular' => ($postData['celular']) ? $postData['celular'] : null,
            'id_responsavel' => ($_SESSION['id_fornecedor']) ? $_SESSION['id_fornecedor'] : null,
        ];


        if ( isset($postData['senha']) && !empty($postData['senha']) ) {
           $post['senha'] = password_hash($postData['senha'], PASSWORD_DEFAULT);
        }

        // Salva no BD
        $this->db->insert('compradores', $post);

        // Obtem o ID do cliente
        $id = $this->db->insert_id();

        if ($pharma) {

            $dataPharma = ['id_fornecedor' => $this->session->id_fornecedor, 'id_comprador' => $id];

            $this->db->insert('compradores_pharma', $dataPharma);

        }

        // Se o cliente for do tipo marketplace registra um  usuário
        if ( isset($postData['senha']) && ($postData['id_tipo_venda'] == 1 || $postData['id_tipo_venda'] == 3) ) {

            $dataUsuario = [
                'id_comprador' => $id,
                'tipo_usuario' => 2,
                'nivel' =>  0,
                'nome' => $postData['razao_social'],
                'email' => $postData['email'],
                'senha' => $post['senha'],
                'telefone' => isset($postData['telefone']) ? $postData['telefone'] : null,
                'celular' => isset($postData['celular']) ? $postData['celular'] : null,
                'situacao' => 1,
            ];

            // cria usuario
            $this->db->insert('usuarios', $dataUsuario);
        }

        // Armazena os arquivos e atualiza cliente
        $update = [];

        // Armazena o caminho dos arquivos de casa cliente
        $path = PUBLIC_PATH . "clientes/{$id}";

        $arquivos = ['documento_alvara', 'cartao_cnpj', 'logo', 'responsabilidade_tecnica', 'copia_afe'];

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
            $this->cliente->update($update);
        };

        // Verifica se as transações ocorreram e commita
        if ($this->db->trans_status() !== false) {
            $this->db->trans_commit();

            $response['status'] =  true;
        } else {
            // Se der errado desfaz as transações e retorna erro
            var_dump($this->db->error());
            $this->db->trans_rollback();

            $response['status'] =  false;
            $response['error'] = "Erro ao cadastrar Comprador";
        }

        var_dump($response);
        exit();

        return $response;
    }

    /**
     * Atualiza Cliente
     *
     * @param - request do form
     * @param - id do cliente
     * @return bool
     */
    public function atualizar($postData, $id)
    {
        $path = PUBLIC_PATH . "clientes/{$id}";

        $this->db->trans_begin();

        $cliente = $this->cliente->findById($id);

       $post = [
            'razao_social' => $postData['razao_social'],
            'cnpj' => $postData['cnpj'],
            'id_tipo_venda' => $postData['id_tipo_venda'],
            'estado' => $postData['estado'],
            'nome_fantasia' => (isset($postData['nome_fantasia']) && !empty($postData['nome_fantasia'])) ? $postData['nome_fantasia'] : null,
            'protocolo_alvara' => (isset($postData['protocolo_alvara']) && !empty($postData['protocolo_alvara'])) ? $postData['protocolo_alvara'] : null,
            'inscricao_estadual' => ( isset($postData['inscricao_estadual']) && !empty($postData['inscricao_estadual']) ) ? $postData['inscricao_estadual'] : null,
            'inscricao_municipal' => ( isset($postData['inscricao_municipal']) && !empty($postData['inscricao_municipal']) ) ? $postData['inscricao_municipal'] : null,
            'validade_alvara' => ( isset($postData['validade_alvara']) && !empty($postData['validade_alvara']) ) ? dbDateFormat($postData['validade_alvara']) : null,
            'motivo_recusa' => ($postData['motivo_recusa']) ? $postData['motivo_recusa'] : null,
            'aprovado' => ( isset($postData['aprovado']) ) ? 1 : 0,
            'numero_afe' => ( isset($postData['numero_afe']) && !empty($postData['numero_afe']) ) ? $postData['numero_afe'] : null,
            'integracao' => ( isset($postData['integracao']) && !empty($postData['integracao']) ) ? $postData['integracao'] : null,
            'responsavel' => ( isset($postData['responsavel']) && !empty($postData['responsavel']) ) ? $postData['responsavel'] : null,
            'endereco' => ( isset($postData['endereco']) && !empty($postData['endereco']) ) ? $postData['endereco'] : null,
            'cidade' => ( isset($postData['cidade']) && !empty($postData['cidade']) ) ? $postData['cidade'] : null,
            'bairro' => ( isset($postData['bairro']) && !empty($postData['bairro']) ) ? $postData['bairro'] : null,
            'cep' => ( isset($postData['cep']) && !empty($postData['cep']) ) ? $postData['cep'] : null,
            'email' => ( isset($postData['email']) && !empty($postData['email']) ) ? $postData['email'] : null,
            'numero' => ( isset($postData['numero']) && !empty($postData['numero']) ) ? $postData['numero'] : null,
            'complemento' => ( isset($postData['complemento']) && !empty($postData['complemento']) ) ? $postData['complemento'] : null,
            'telefone' => ( isset($postData['telefone']) && !empty($postData['telefone']) ) ? $postData['telefone'] : null,
            'celular' => ($postData['celular']) ? $postData['celular'] : null,
        ];

        if ( isset($postData['senha']) && !empty($postData['senha']) ) {

            $post['senha'] = password_hash($postData['senha'], PASSWORD_DEFAULT);

            // atualiza senha do usuario
            $this->db->where('id_comprador', $id)->update('usuarios', ['senha' => $post['senha']]);
        }


        $arquivos = ['documento_alvara', 'cartao_cnpj', 'logo', 'responsabilidade_tecnica', 'copia_afe'];

        foreach ($arquivos as $arquivo) {
            // Verifica se o arquivo existe
            if ( isset($_FILES[$arquivo]) && !empty($_FILES[$arquivo]['name']) ) {

                // Faz upload do arquivo
                $arq = $this->saveFile($path, $arquivo);
                // Verifica se o upload funcionou
                if ($arq['status']) {

                    if (  !empty($cliente[$arquivo]) ) {

                        // Verifica se existe arquivo salvo
                        if( file_exists($path . '/' . $cliente[$arquivo]) ) {
                            // Verifica se excluiu o arquivo antigo
                            if (unlink($path . '/' . $cliente[$arquivo])) {
                                $post[$arquivo] = $arq['data'];
                            }
                        }
                    } else {
                        $post[$arquivo] = $arq['data'];
                    }
                } else {
                    return ['status' => false, 'error' => $arq['data']];
                }
            }
        }

        $this->db->where('id', $id);
        $this->db->update('compradores', $post);


        if ($this->db->trans_status() !== false) {
            $this->db->trans_commit();

            return ['status' => true ];
        }else {
            $this->db->trans_rollback();
            return ['status' => false, 'error' => 'Erro ao atualizar Comprador!'];
        }
    }

    /**
     * Deleta cliente
     *
     * @param - id cliente
     * @return bool
     */
    public function excluir($id)
    {
        $this->db->where('id', $id);

        $data = ['status' => '3'];

        if ( $this->db->update('compradores', $data) ) {

            return TRUE;
        }else {
            return FALSE;
        }
    }

    /**
     * Atualiza status
     *
     * @param - id cliente
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
     * @param - id cliente
     * @param - email cliente
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
     * Verifica se já existe cnpj no BD
     *
     * @param - id cliente
     * @param - cnpj cliente
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
     * Obtem a lista de compradores da bionexo
     *
     * @return - array
     */
    public function listarCompradoresBionexo()
    {
       $this->db->select("c.id, CONCAT(c.cnpj, ' - ', c.razao_social) AS comprador");
       $this->db->from('compradores c');
       $this->db->join('compradores_integrador ci', 'ci.id_cliente = c.id AND ci.id_integrador = 2');
       $this->db->group_by('ci.id_cliente');
       $this->db->order_by('c.razao_social ASC');

       return $this->db->get()->result_array();
    }

    public function getDadosCatalogo($id_cliente)
    {
        $total = count($this->bio->select('id')->where('id_cliente', $id_cliente)->get('catalogo')->result_array());
        $ocultos = count($this->bio->select('id')->where('id_cliente', $id_cliente)->where('ocultar', 1)->get('catalogo')->result_array());
        $com = count($this->db->select('codigo_hospital')->where('id_cliente', $id_cliente)->group_by('codigo_hospital')->get('vw_produtos_cliente_depara')->result_array());

        $sem = count($this->db->select('*')->where('id_cliente', $id_cliente)->get('vw_produtos_clientes_sem_depara')->result_array());

        $data = [
            'catalogo' => $total,
            'com' => $com,
            'sem' => $sem,
            'ocultos' => $ocultos
        ];

        return $data;

    }

    public function getNovosProdutos()
    {
        $this->db->distinct();
        $this->db->select("id_cliente, max(fim) as data");
        $this->db->where('fim is not null');
        $this->db->where_not_in('id_cliente', '(select distinct id_cliente from responsaveis_depara where fim is null)');
        $this->db->group_by('id_cliente');

        $cmp = $this->db->get('responsaveis_depara')->result_array();


        if (!empty($cmp)){
            $prods = [];
            foreach ($cmp as $c){
                $p = $this->bio->where('id_cliente', $c['id_cliente'])->where("dt_criacao > '{$c['data']}'")->get('catalogo')->result_array();
                $prods[$c['id_cliente']] = count($p);

            }
        }else{
            $prods = 0;
        }



        return $prods;

    }


    //////////////////////////////////////////// LOGIN ///////////////////////////////////////////

    public function login($data){

        if (!isset($data['email']) || !isset($data['senha'])) return false;

        $this->db->where('email', $data['email']);
       // $this->db->where('status', 1);

        $consulta = $this->db->get('compradores')->row_array();

        if (password_verify($data['senha'], $consulta['senha'])){
            return $consulta;
        }else{
            return false;
        }
    }
}
