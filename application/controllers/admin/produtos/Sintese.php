<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sintese extends Admin_controller
{
    private $route, $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('admin/produtos/sintese');
        $this->views = 'admin/produtos/sintese';
        $this->load->model('Produto_marca_sintese', 'pms');
    }

    public function index()
    {
        $page_title = "Banco de Dados Central";

        $data['datasource'] = "{$this->route}/datatables";
        $data['slct_marcas'] = "{$this->route}/to_select2_marcas";
        $data['grupos'] = $this->db->select('id_grupo, grupo')->group_by('id_grupo')->order_by('grupo ASC')->get('produtos_marca_sintese')->result_array();
        $data['url_delete_multiple'] = "{$this->route}/delete_multiple/";

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
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel', 
                    'label' => 'Exportar Excel'
                ],
            ]
             ]);
        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Exibe o datatables da tabela produtos_marca_sintese
     *
     * @return  json
     */
    public function datatables()
    {
        $datatables = $this->datatable->exec(
            $this->input->post(),
            'produtos_marca_sintese',
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'descricao', 'dt' => 'descricao', 'formatter' => function($value, $row) {

                    return "{$value} <br> <small><b>Complemento: </b> {$row['complemento']}</small>";
                }],
                ['db' => 'complemento', 'dt' => 'complemento'],
                ['db' => 'id_grupo', 'dt' => 'id_grupo'],
                ['db' => 'id_produto', 'dt' => 'id_produto'],
                ['db' => 'id_sintese', 'dt' => 'id_sintese'],
                ['db' => 'id_marca', 'dt' => 'id_marca'],
                ['db' => 'marca', 'dt' => 'marca'],
                ['db' => 'grupo', 'dt' => 'grupo']
            ],
            NULL,
            "ativo = 1",
            "id_produto"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    public function delete_multiple()
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            if ( isset($post['itens']) ) {

                $data = [
                    'ativo' => 0,
                    'id_usuario_exclusao' => $this->session->id_usuario,
                    'motivo_exclusao' => $post['motivo']
                ];

                $updt = $this->db->where("id in (" . implode(', ', $post['itens']) . ") ")->update('produtos_marca_sintese', $data);

                if ( $updt ) {

                    $output = ['type' => 'success', 'message' => 'Excluidos com sucesso'];
                } else {
                    
                    $output = ['type' => 'warning', 'message' => 'Erro ao excluir'];
                }

                $this->output->set_content_type('application/json')->set_output(json_encode($output));
            } else {

                $output = ['type'    => 'warning', 'message' => 'Nenhum produto selecionado'];

                $this->output->set_content_type('application/json')->set_output(json_encode($output));
            }
        }
    }

    public function to_select2_marcas()
    {
        $this->output->set_content_type('application/json')->set_output(json_encode($this->select2->exec(
            $this->input->get(),
            "marcas",
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'marca', 'dt' => 'marca'],
            ]
        )));
    }

    public function exportar()
    {
        $this->db->select("id_produto, CONCAT(descricao, '.  Complemento: ', complemento) AS descricao, marca, grupo");
        $this->db->from("produtos_marca_sintese");
        $this->db->where("ativo", 1);
        $this->db->group_by("id_produto");
        $this->db->order_by("id_produto ASC");

        $query = $this->db->get()->result_array();

        var_dump($query); exit();

        if ( count($query) < 1 ) {
            $query[] = [
                'id_produto' => '',
                'descricao' => '',
                'marca' => '',
                'grupo' => ''
            ];
        }

        $dados_page = ['dados' => $query , 'titulo' => 'Produtos'];

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

/* End of file: Sintese.php */