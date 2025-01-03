<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Matrizes extends Admin_controller
{
    private $route, $views;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('admin/fornecedores/matrizes');
        $this->views = "admin/fornecedores/matrizes";

        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_tipos_venda', 'tipos_venda');
    }

    /**
     * Exibe a view admin/forncedores/main.php
     *
     * @param   int  $id
     * @return  view
     */
    public function index()
    {
        $page_title = "Matrizes";

        $data['datasource'] = "{$this->route}/datatables";
        $data['url_update'] = "{$this->route}/openModal";
        $data['url_delete_multiple'] = "{$this->route}/delete_multiple";

        $data['header'] = $this->template->header([ 'title' => $page_title ]);
        $data['navbar']  = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons'    => [
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
     * Exibe o datatables de fornecedores
     *
     * @return  json
     */
    public function datatables()
    {
        $datatables = $this->datatable->exec(
            $this->input->post(),
            'fornecedores_matriz',
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'nome', 'dt' => 'nome'],
                ['db' => "(SELECT GROUP_CONCAT(nome_fantasia order by nome_fantasia ASC) AS fornecedores FROM pharmanexo.fornecedores f WHERE f.id_matriz = fornecedores_matriz.id )", 'dt' => 'fornecedores', 'formatter' => function ($value, $row) {
                    
                    $array = array();

                    foreach (explode(',', $value) as $fornecedor) {

                        $array[] = "<span class='badge badge-primary mt-1'>{$fornecedor}</span>";
                    }

                    return implode($array, ' ');
                }],
                ['db' => 'data_criacao', 'dt' => 'data_criacao', 'formatter' => function ($value, $row) {

                     return date("d/m/Y H:i", strtotime($value));
                }],
            ]
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    public function openModal($id = null)
    {

        $data['fornecedores'] = $this->fornecedor->find('*', null, false, "nome_fantasia ASC");

        if ( isset($id) ) {

            $data['title'] = "Atualizar matriz";
            $data['form_action'] = "{$this->route}/update/{$id}";
            $data['matriz'] = $this->db->where('id', $id)->get('fornecedores_matriz')->row_array();

            # adiciona os fornecedores da matriz
            $this->db->select("id");
            $this->db->where('id_matriz', $data['matriz']['id']);
            $fornecedores_matriz = $this->db->get('fornecedores')->result_array();

            $data['matriz']['fornecedores'] = array_column($fornecedores_matriz, 'id');
        } else {

            $data['title'] = "Nova matriz";
            $data['form_action'] = "{$this->route}/save";
        }

        $this->load->view("{$this->views}/modal", $data);
    }

    public function save()
    {
        if ( $this->input->is_ajax_request() ) {
            
            $post = $this->input->post();

            $this->db->trans_begin();

            # Verifica se já existe registro para o nome informado
            if ( $this->db->where('nome', $post['nome'])->count_all_results('fornecedores_matriz') < 1 ) {

                $this->db->insert('fornecedores_matriz', ['nome' => $post['nome']]);

                $id = $this->db->insert_id();

                $this->db->where_in('id',$post['fornecedores'])->update('fornecedores', ['id_matriz' => $id]);

                if ($this->db->trans_status() === FALSE) {

                    $this->db->trans_rollback();

                    $output = $this->notify->errorMessage();
                } else {

                    $this->db->trans_commit();

                    $output = ['type' => 'success', 'message' => notify_create];
                }
            } else {

                $output = ['type' => 'warning', 'message' => 'Esta matriz já possui registro.'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function update($id)
    {
        if ( $this->input->is_ajax_request() ) {
            
            $post = $this->input->post();

            # Atualiza a matriz
            $this->db->where('id', $id)->update('fornecedores_matriz', ['nome' => $post['nome']]);

            # Retira todos os id_matriz dos fornecedores
            $this->db->where('id_matriz', $id)->update('fornecedores', ['id_matriz' => null]);

            # Depois atualiza os novos fornecedores com o ID matriz
            $this->db->where_in('id',$post['fornecedores'])->update('fornecedores', ['id_matriz' => $id]);

            $this->db->trans_begin();

            if ($this->db->trans_status() === FALSE) {

                $this->db->trans_rollback();

                $output = $this->notify->errorMessage();
            } else {

                $this->db->trans_commit();

                $output = ['type' => 'success', 'message' => notify_update];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Função que exclui usuario
     *
     * @return  json
     */
    public function delete_multiple()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $this->db->trans_begin();

            foreach ($post['el'] as $item) {

                # Retira todos os id_matriz dos fornecedores
                $this->db->where('id_matriz', $item)->update('fornecedores', ['id_matriz' => null]);
                
                # Remove a matriz
                $this->db->where('id', $item)->delete('fornecedores_matriz');
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
}

