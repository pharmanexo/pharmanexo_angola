<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * classe responsável por configurar os dados de acesso ao integrador SINTESE
 */
class Configuracoes extends Admin_controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('/admin/configuracoes/configuracoes');
        $this->views = 'admin/configuracoes';
    }

    /**
     * View para selecionar fornecedor
     *
     * @return view
     */
    public function index()
    {
        $page_title = "Configurações";

        $data['datasource'] = "{$this->route}/datatables";
        $data['url_update'] = "{$this->route}/openModal";
        $data['url_delete_multiple'] = "{$this->route}/delete_multiple";

        $data['header'] = $this->template->header([ 'title' => $page_title ]);
        $data['navbar']  = $this->template->navbar();
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
                    'id' => 'btnAdicionar',
                    'url' => "{$this->route}/openModal",
                    'class' => 'btn-primary',
                    'icone' => 'fa-plus',
                    'label' => 'Novo Registro'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Exibe o modal
     * 
     * @param null $id
     * @return view
     */
    public function openModal($id = null)
    {

        if ( isset($id) ) {

            $data['title'] = "Atualizar configuração";
            $data['form_action'] = "{$this->route}/update/{$id}";
            $data['dados'] = $this->db->where('id', $id)->get('configs')->row_array();
        } else {

            $data['title'] = "Nova configuração";
            $data['form_action'] = "{$this->route}/save";
        }

        $this->load->view("{$this->views}/modal", $data);
    }

    /**
     * Salva as configurações sintese
     * @param POST form
     * @return json
     */
    public function save()
    {
        if ($this->input->is_ajax_request()) {

            $post = $this->input->post();

            $data['chave'] = $post['chave'];
            $data['json'] = ( isset($post['json']) ) ? 1 : 0;

            if ( $data['json'] == 1 ) {

                $json = [];

                for ($i = 0; $i < count($post['nome']); $i++) { 

                    if ( $post['nome'][$i] != "" && $post['valor'][$i] != "" ) {
                        
                        $json[$post['nome'][$i]] = $post['valor'][$i];
                    }
                }

                $data['valor'] = json_encode($json);
            } else {

                $data['valor'] = $post['valorSemJson'];
            }

            if (  $this->db->where("chave", $data['chave'])->count_all_results('configs') < 1 ) {
             
                if ( $this->db->insert("configs", $data) ) {
                    
                    $output = ['type' => 'success', 'message' => notify_create];
                } else {

                    $output = $this->notify->errorMessage();
                }
            } else {

                $output = ['type' => 'warning', 'message' => "Já existe uma configuração com esta chave no banco de dados!"];
            }
            
            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Atualiza as configurações sintese
     * 
     * @param GET ID registro config sintese
     * @param POST form
     * @return json
     */
    public function update($id)
    {
        if ($this->input->is_ajax_request()) {

            $post = $this->input->post();

            $config = $this->db->where('id', $id)->get('configs')->row_array();

            $data['json'] = ( isset($post['json']) ) ? 1 : 0;

            if ( $data['json'] == 1 ) {

                $json = [];

                for ($i = 0; $i < count($post['nome']); $i++) { 

                    if ( $post['nome'][$i] != "" && $post['valor'][$i] != "" ) {
                        
                        $json[$post['nome'][$i]] = $post['valor'][$i];
                    }
                }

                $data['valor'] = json_encode($json);
            } else {

                $data['valor'] = $post['valorSemJson'];
            }

            if ( $this->db->where("id", $config['id'])->update('configs', $data) ) {
             
                $output = ['type' => 'success', 'message' => notify_update];
            } else {

                $output = $this->notify->errorMessage();
            } 

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Remove os registros marcados do datatable
     * 
     * @param POST array id registros
     * @return json
     */
    public function delete_multiple()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $this->db->trans_begin();

            foreach ($post['el'] as $id) {
                $this->db->where('id', $id)->delete('configs');
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();

                $output = $this->notify->errorMessage();
            }
            else {
                $this->db->trans_commit();

                $output = ['type' => 'success', 'message' => notify_delete];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Exibe a lista de fornecedores
     *
     * @return  json
     */
    public function datatables()
    {
        $datatables = $this->datatable->exec(
            $this->input->post(),
            'configs',
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'chave', 'dt' => 'chave'],
                ['db' => 'valor', 'dt' => 'valor'],
                ['db' => 'data_criacao', 'dt' => 'data_criacao', 'formatter' => function ($value, $row) {

                    return date("d/m/Y H:i", strtotime($value));
                }],
            ],
            null
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }
}