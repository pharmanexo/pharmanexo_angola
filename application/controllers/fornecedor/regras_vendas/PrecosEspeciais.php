<?php

class PrecosEspeciais extends MY_Controller
{
    private $route;
    private $views;
    private $mix;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/regras_vendas/precosEspeciais');
        $this->views = 'fornecedor/regras_vendas/precosEspeciais';

        $this->load->model('m_compradores', 'comprador');
        $this->load->model('m_fornecedor', 'fornecedor');

        $this->MIX = $this->load->database('mix', TRUE);
    }

    /**
     * Exibe a tela de preçõs especiais
     *
     * @return view
     */
    public function index()
    {
        $page_title = "Preços Especiais";

        $data['header'] = $this->template->header(['title' => $page_title ]);
        $data['scripts'] = $this->template->scripts();
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'button',
                    'id' => 'btnDeleteMultiple',
                    'url' => "",
                    'class' => 'btn-danger',
                    'icone' => 'fa-trash',
                    'label' => 'Excluir Selecionados'
                ],
                [
                    'type' => 'a',
                    'id' => 'btnImport',
                    'class' => 'btn-primary',
                    'url' => "{$this->route}/openModal",
                    'icone' => 'fa-file-import',
                    'label' => 'Importar Excel'
                ]
            ]
        ]);

        # URLs
        $data['urlDatatables'] = "{$this->route}/datatables";
        $data['url_delete_multiple'] = "{$this->route}/delete_multiple";
       
        # Selects
        if ( $this->session->has_userdata("id_matriz") ) {
                
            $data['filiais'] = $this->fornecedor->find("id, nome_fantasia", "id_matriz = {$this->session->id_matriz}", false, "nome_fantasia ASC");
        }

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * exibe ao modal para importar Arquivo
     *
     * @return view
     */
    public function openModal()
    {
        $data['form_action'] = "{$this->route}/importarExcel";

        if ( $this->session->has_userdata("id_matriz") ) {
                
            $data['filiais'] = $this->fornecedor->find("id, nome_fantasia", "id_matriz = {$this->session->id_matriz}", false, "nome_fantasia ASC");
        }
        
        $this->load->view("{$this->views}/modal", $data);
    }

    /**
     * Obtem os registros da tabela produtos_precos do mix
     *
     * @return json
     */
    public function datatables()
    {
        $data = $this->datatable->exec(
            $this->input->post(),
            'produtos_preco_mix pp',
            [
                [ 'db' => 'pp.codigo', 'dt' => 'codigo' ],
                [ 'db' => 'pp.id_fornecedor', 'dt' => 'id_fornecedor' ],
                [ 'db' => 'pp.id_cliente', 'dt' => 'id_cliente' ],
                [ 'db' => 'pp.preco_mix', 'dt' => 'preco_mix', 'formatter' => function ($value, $row) {

                    return number_format($value, 4, ',', '.');
                }],
                [ 'db' => 'pp.preco_base', 'dt' => 'preco_base', 'formatter' => function ($value, $row) {

                    return number_format($value, 4, ',', '.');
                }],
                [ 'db' => 'pc.nome_comercial', 'dt' => 'nome_comercial' ],
                [ 'db' => 'f.nome_fantasia', 'dt' => 'nome_fantasia' ],
                [ 'db' => 'c.cnpj', 'dt' => 'cnpj' ],
                [ 'db' => 'pp.data_criacao', 'dt' => 'data_criacao', 'formatter' => function ($value, $row) {

                    return date("d/m/Y H:i", strtotime($value));
                }],
                [ 'db' => 'c.razao_social', 'dt' => 'razao_social', 'formatter' => function ($value, $row) {

                    return "{$row['cnpj']} - {$value}";
                }],
            ],
            [
                ['pharmanexo.produtos_catalogo pc', 'pc.codigo = pp.codigo AND pc.id_fornecedor = pp.id_fornecedor'],
                ['pharmanexo.compradores c', 'c.id = pp.id_cliente'],
                ['pharmanexo.fornecedores f', 'f.id = pp.id_fornecedor'],
            ],
            "pp.id_estado IS NULL",
            null,
            'mix'
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Deleta os registros selecionados da tela inicial
     *
     * @return json
     */
    public function delete_multiple()
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            $this->db->trans_begin();

            if ( !isset($post['registros']) ) {

                $output = ['type'    => 'warning', 'message' => 'Nenhum registro selecionado'];

                $this->output->set_content_type('application/json')->set_output(json_encode($output));
                return;
            }

            foreach ($post['registros'] as $item) {

                $this->MIX->where('codigo', $item['codigo']);
                $this->MIX->where('id_fornecedor', $item['id_fornecedor']);
                $this->MIX->where('id_cliente', $item['id_cliente']);
                $this->MIX->delete('produtos_preco_mix');
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();

                $output = ['type' => 'warning', 'message' => notify_failed];
            } else {

                $this->db->trans_commit();

                $output = ['type' => 'success', 'message' => notify_delete];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Exporta um arquivo excel para base de dados
     *
     * @param file excel
     * @return json
     */
    public function importarExcel()
    {
        
        $post = $this->input->post();

        # Verifica se o arquivo foi selecionado
        if ( !empty($_FILES['file']['name'])  ) {

            # Verifica se o formato do arquivo é csv
            if ( strpos($_FILES['file']['name'], '.csv') !== false ) {

                # Verifica se o usuario selecionou  fornecedores
                if ( !empty($post['fornecedores']) ) {

                    $file = fopen($_FILES['file']['tmp_name'], 'r');

                    $data = [];

                    while (($line = fgetcsv($file, null, ',')) !== false) {

                        if ( $line[0] != 'cnpj' ) {

                            # Tira o R$
                            $preco_base = str_replace('R$', '', $line[2]);

                            # Remove os espaços vazios
                            $preco_base = trim($preco_base);

                            # Transforma para float
                            $preco_base = dbNumberFormat($preco_base);
                            
                            $data[$line[0]][] = [
                                'cnpj' => $line[0],
                                'codigo' => $line[1],
                                'preco_mix' => $line[3],
                                'preco_base' => $preco_base,
                                'preco_fixo' => 0,
                            ];
                        }
                    }

                    fclose($file);

                    $insert = [];
                    $n_existe = [];

                    foreach ($post['fornecedores'] as $id_fornecedor) {

                        # Remove todos os registros de cada fornecedor selecionado
                        $this->MIX->where("id_fornecedor", $id_fornecedor)->delete('produtos_preco_mix');
                            
                        foreach ($data as $cnpj => $precos) {

                            $cnpj = trim($cnpj);
                            
                            $id_cliente = $this->comprador->find("id", "cnpj = '{$cnpj}' ", true)['id'];

                            if ( isset($id_cliente) && !empty($id_cliente) ) {
                                
                                foreach ($precos as $preco) {
                               
                                    $insert[] = [
                                        'id_fornecedor' => $id_fornecedor,
                                        'id_cliente' => $id_cliente,
                                        'codigo' => $preco['codigo'],
                                        'preco_mix' => $preco['preco_mix'],
                                        'preco_base' => $preco['preco_base'],
                                        'preco_fixo' => $preco['preco_fixo']
                                    ];
                                }
                            } else {

                                $n_existe[] = $cnpj;
                            }
                        }
                    }

                    if ( isset($insert) && !empty($insert) ) {
                        
                        $this->MIX->insert_batch('produtos_preco_mix', $insert);
                    }

                    $output = ['type' => 'success', 'message' => 'Arquivo importado com sucesso']; 
                } else {

                    $output = ['type' => 'warning', 'message' => 'O campo lojas é obrigatório']; 
                }
            } else {

                $output = ['type' => 'warning', 'message' => 'Formato de arquivo invalido! Selecione um arquivo .csv']; 
            }
        } else {

            $output = ['type' => 'warning', 'message' => 'O campo arquivo é obrigatório']; 
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }
}
