<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ConfiguracoesEnvio extends MY_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/regras_vendas/configuracoesEnvio');
        $this->views = 'fornecedor/regras_vendas/configuracoes_envio';

        $this->load->model('m_estados', 'estado');
        $this->load->model('m_configuracoes_envio', 'config_envio');
    }

    /**
     * exibe a view fornecedor/regras_vendas/configuracoesEnvio/main.php
     *
     * @return view
     */
    public function index()
    {
        $page_title = "Configurações de Envio";

        $data['datatable'] = "{$this->route}/datatable";
        $data['url_delete_multiple'] = "{$this->route}/delete_multiple/";
        $data['url_update'] = "{$this->route}/openModalUpdate";

        $data['header'] = $this->template->header(['title' => $page_title ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnVoltar',
                   'url' => "javascript:history.back(1)",
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Retornar'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnDeleteMultiple',
                    'url' => "",
                    'class' => 'btn-danger',
                    'icone' => 'fa-trash',
                    'label' => 'Excluir Selecionados'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel', 
                    'label' => 'Exportar Excel'
                ],
                [
                    'type' => 'a',
                    'id' => 'btnAdicionar',
                    'url' => "{$this->route}/openModal",
                    'class' => 'btn-primary',
                    'icone' => 'fa-plus',
                    'label' => 'Novo Registro'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts(['scripts' => [] ]);

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Exibe o modal de cadastrar controle de cotação
     *
     * @return view
     */
    public function openModal()
    {
        $data['form_action'] = "{$this->route}/save";
        $data['estados'] = $this->estado->getList();
        $data['integradores'] = $this->db->get('integradores')->result_array();

        $this->load->view("{$this->views}/modal", $data);
    }

    public function openModalUpdate($id)
    {
        $data['form_action'] = "{$this->route}/update";
        $data['dados'] = $this->config_envio->findById($id);
        $data['estados'] = $this->estado->getList();
        $data['integradores'] = $this->db->get('integradores')->result_array();

        $this->load->view("{$this->views}/modal", $data);
    }

    /**
     * Registra um controle de cotação
     *
     * @return json
     */
    public function save()
    {

        $post = $this->input->post();

        $this->form_validation->set_rules('observacao', 'Observação', 'required');
        $this->form_validation->set_rules('tipo', 'Tipo', 'required');
        $this->form_validation->set_rules('integradores[]', 'Integradores', 'required');
        $this->form_validation->set_rules('estados[]', 'Estados', 'required');

        if ($this->form_validation->run() === FALSE) {

            $errors = [];

            foreach ($post as $key => $value) {

                $errors[$key] = form_error($key);
            }

            $output = ['type' => 'warning', 'message' => array_filter($errors)];
        } else {
            
            $gravado = $this->config_envio->gravar($post);

            if ($gravado) {

                $output = ['type' => 'success', 'message' => notify_create];
            } else {

                $output = ['type' => 'warning', 'message' => notify_failed];
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    /**
     * Atualiza um controle de cotação
     *
     * @return json
     */
    public function update()
    {

        $post = $this->input->post();

        $this->form_validation->set_rules('observacao', 'Observação', 'required');

        if ($this->form_validation->run() === false) {

            $errors = [];

            foreach ($post as $key => $value) {

                $errors[$key] = form_error($key);
            }
           
            $output = ['type' => 'warning', 'message' => array_filter($errors)];
        } else {

            $update = $this->config_envio->atualizar($post);

            if ($update) {

                $output = ['type' => 'success', 'message' => notify_update];
            } else {

                $output = ['type' => 'warning', 'message' => notify_failed];
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    /**
     * Deleta os registros de configuracoes de envio
     *
     * @return json
     */
    public function delete_multiple()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $this->db->trans_begin();

            if (!isset($post['el'])) {

                $output = ['type'    => 'warning', 'message' => 'Nenhum produto selecionado'];

                $this->output->set_content_type('application/json')->set_output(json_encode($output));
                return;
            }

            foreach ($post['el'] as $id) {

                $this->config_envio->delete($id);
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();

                $output = ['type'    => 'warning', 'message' => notify_failed];
            } else {
                
                $this->db->trans_commit();

                $output = ['type'    => 'success', 'message' => notify_delete];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Obtem os dados para o datatable  de controle de cotações por estado
     *
     * @return json
     */
    public function datatable()
    {
        $r = $this->datatable->exec(
            $this->input->get(),
            'configuracoes_envio config',
            [
                ['db' => 'config.id', 'dt' => 'id'],
                ['db' => 'config.id_estado', 'dt' => 'id_estado'],
                ['db' => 'config.tipo', 'dt' => 'tipo', "formatter" => function ($value, $row) {
                    switch ($value) {
                        case '1':
                            return 'Automática';
                            break;
                        case '2':
                            return 'Manual';
                            break;
                        case '3':
                            return 'Manual e Automática';
                            break;
                    }
                }],
                ['db' => 'config.integrador', 'dt' => 'integrador', "formatter" => function ($value, $row) {
                    switch ($value) {
                        case '1':
                            return 'Síntese';
                            break;
                        case '2':
                            return 'Bionexo';
                            break;
                        case '3':
                            return 'Apoio';
                            break;
                    }
                }],
                ['db' => 'e.uf', 'dt' => 'uf'],
                ['db' => 'e.descricao', 'dt' => 'estado', 'formatter' => function ($value, $row) {

                    if ( $row['id_estado'] == 0 ) {
                       
                       return 'Todos';
                    }

                    return "{$row['uf']} - {$value}";
                }]
            ],
            [
                ['estados e', 'e.id = config.id_estado', 'left']
            ],
            "config.id_fornecedor = {$this->session->id_fornecedor}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    /**
     * Cria um arquivo excel com todos os registros de configuracoes de envio do fornecedor logado
     *
     * @return file
     */
    public function exportar()
    {
        
        $this->db->select("(CASE 
            WHEN config.integrador = '1' THEN 'Síntese'
            WHEN config.integrador = '2' THEN 'Bionexo'
            WHEN config.integrador = '3' THEN 'Apoio'
            WHEN config.tipo = '1' THEN 'Automático'
            WHEN config.tipo = '2' THEN 'Manual'
            WHEN config.tipo = '3' THEN 'Manual e Automático' END) tipo,
            (CASE WHEN config.id_estado = '0' THEN 'Todos' ELSE CONCAT(e.uf, ' - ', e.descricao) END) estado,
            config.observacao
        ");
        $this->db->from("configuracoes_envio config");
        $this->db->join('estados e', "e.id = config.id_estado", 'left');
        $this->db->where('config.id_fornecedor', $this->session->id_fornecedor);
        $this->db->order_by("estado ASC");

        $query = $this->db->get()->result_array();

        if (count($query) < 1 ) {
           $query[] = [
                'tipo' => '',
                'integrador' => '',
                'estado' => '',
                'observacao' => ''
           ];
        }

        $dados_page = ['dados' => $query , 'titulo' => 'Estados'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }
}

/* End of file: Controle_cotacoes.php */
