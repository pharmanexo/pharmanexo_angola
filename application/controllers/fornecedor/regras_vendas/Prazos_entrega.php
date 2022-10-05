<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prazos_entrega extends MY_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/regras_vendas/prazos_entrega');
        $this->views = 'fornecedor/regras_vendas/prazos_entrega';

        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_prazo_entrega');
    }

    /**
     * exibe a view fornecedor/regras_vendas/prazos_entrega/main.php
     *
     * @return view
     */
    public function index()
    {
        $page_title = "Prazos de Entrega";

        $data['to_datatable_estado'] = "{$this->route}/to_datatable_estado";
        $data['to_datatable_cnpj']   = "{$this->route}/to_datatable_cnpj";
        $data['url_update']          = "{$this->route}/openModalUpdate/";
        $data['url_delete_multiple'] = "{$this->route}/delete_multiple/";

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'styles' => [
                THIRD_PARTY . 'plugins/bootstrap-duallistbox/css/bootstrap-duallistbox.css'
            ]
        ]);

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

        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/bootstrap-duallistbox/js/jquery.bootstrap-duallistbox.js'
            ]
        ]);

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * exibe o modal de criar prazo de entrega
     *
     * @return view
     */
    public function openModal()
    {
        $data['form_action'] = "{$this->route}/save";
        $data['getList']     = "{$this->route}/getList";
        $data['isUpdate']    = FALSE;

        $this->load->view("{$this->views}/form", $data);
    }

    /**
     * exibe o modal de atualizar prazo de entrega
     *
     * @param int ID prazo de entrega
     * @return view
     */
    public function openModalUpdate($id)
    {
        $data['form_action'] = "{$this->route}/update";
        $data['dados']       = $this->m_prazo_entrega->getById($id);
        $data['isUpdate']    = TRUE;

        $this->load->view("{$this->views}/form", $data);
    } 

    /**
     * Cria um registro prazo de entrega
     *
     * @return json
     */
    public function save()
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            $this->form_validation->set_rules('prazo', 'Prazo', 'required');
            $this->form_validation->set_rules('opcao', 'Opção', 'required');
            $this->form_validation->set_rules('elementos', 'Elementos', 'required');

            if ($this->form_validation->run() === FALSE) {

                $errors = [];

                foreach ($post as $key => $value) {

                    $errors[$key] = form_error($key);
                }

                $output = ['type' => 'warning', 'message' => array_filter($errors)];
            } else {

                $gravado = $this->m_prazo_entrega->gravar();

                if ($gravado) {
                    
                    $output = ['type' => 'success', 'message' => notify_create];
                } else {

                    $output = ['type' => 'warning', 'message' => notify_failed];
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        } 
    }

    /**
     * Atualiza o registro prazo de entrega
     *
     * @return json
     */
    public function update()
    {
        $post = $this->input->post();

        $this->form_validation->set_rules('prazo', 'Prazo', 'required');

        if ($this->form_validation->run() === FALSE) {

            $errors = [];

            foreach ($post as $key => $value) {

                $errors[$key] = form_error($key);
            }

            $output = ['type' => 'warning', 'message' => array_filter($errors)];
        } else {

            $id = $this->m_prazo_entrega->update($post);

            if ($id) {

                $output = ['type' => 'success', 'message' => notify_update];
            } else {

                $output = ['type' => 'warning', 'message' => notify_failed];
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    /**
     * Deleta os registros selecionados de prazo de entrega
     *
     * @return json
     */
    public function delete_multiple()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            if (!isset($post['el'])) {

                $output = ['type' => 'warning', 'message' => 'Nenhum produto selecionado'];

                $this->output->set_content_type('application/json')->set_output(json_encode($output));
                return;
            }

            $this->db->trans_begin();

            foreach ($post['el'] as $item) {

                $this->m_prazo_entrega->excluir($item);
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
     * obtem os dados para o datatable de prazo por estado
     *
     * @return json
     */
    public function to_datatable_estado()
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'prazos_entrega',
            [
                ['db' => 'prazos_entrega.id', 'dt' => 'id'],
                ['db' => 'prazo',             'dt' => 'prazo'],
                ['db' => 'uf',                'dt' => 'uf'],
                ['db' => 'descricao',         'dt' => 'descricao', 'formatter' => function ($value, $row) {
                    return "{$row['uf']} - {$value}";
                }]
            ],
            [
                ['estados', 'estados.id = prazos_entrega.id_estado']
            ],
            'prazos_entrega.id_fornecedor = ' . $this->session->userdata('id_fornecedor')
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    /**
     * obtem os dados para o datatable de prazo por comprador
     *
     * @return json
     */
    public function to_datatable_cnpj()
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'prazos_entrega',
            [
                ['db' => 'prazos_entrega.id', 'dt' => 'id'],
                ['db' => 'prazos_entrega.prazo', 'dt' => 'prazo'],
                ['db' => 'compradores.cnpj', 'dt' => 'cnpj'],
                ['db' => 'compradores.razao_social', 'dt' => 'razao_social']
            ],
            [
                ['compradores', 'compradores.id = prazos_entrega.id_cliente'],
            ],
            'prazos_entrega.id_fornecedor = ' . $this->session->userdata('id_fornecedor')
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    /**
     * obtem os dados dos estados ou compradores
     *
     * @param string 
     * @return json
     */
    public function getList($option)
    {
        $result = $this->m_prazo_entrega->getList($option);
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    /**
     * Cria um arquivo excel com todos os registros do datatables de prazo
     *
     * @return file
     */
    public function exportar()
    {
        $this->db->select(" 
            CONCAT(e.uf, ' - ', e.descricao) AS estado,
            pe.prazo AS prazo_entrega");
        $this->db->from("prazos_entrega pe");
        $this->db->join('estados e', "e.id = pe.id_estado");
        $this->db->where('pe.id_fornecedor', $this->session->id_fornecedor);
        $this->db->order_by("estado ASC");

        $query_estados = $this->db->get()->result_array();

        $this->db->select(" 
            c.razao_social,
            c.cnpj,
            pe.prazo AS prazo_entrega");
        $this->db->from("prazos_entrega pe");
        $this->db->join('compradores c', "c.id = pe.id_cliente");
        $this->db->where('pe.id_fornecedor', $this->session->id_fornecedor);
        $this->db->order_by("cnpj ASC");

        $query_clientes = $this->db->get()->result_array();

        if (count($query_estados) < 1 ) {
           $query_estados[] = [
                'estado' => '',
                'prazo_entrega' => ''
           ];
        }

        if (count($query_clientes) < 1 ) {
            $query_clientes[] = [
                'razao_social' => '',
                'cnpj' => '',
                'prazo_entrega' => ''
            ];
        }

        $dados_page1 = ['dados' => $query_estados , 'titulo' => 'Estados'];
        $dados_page2 = ['dados' => $query_clientes, 'titulo' => 'Compradores'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page1, $dados_page2);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }
}

/* End of file: Prazos_entrega.php */
