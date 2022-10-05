<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pendentes extends Admin_controller
{
    private $route, $views;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('admin/produtos/pendentes');
        $this->views = 'admin/produtos/pendentes';

        $this->load->model('m_fornecedor', 'fornecedor');
    }

    public function index()
    {
        $page_title = "Catálogo de produtos pendentes";

        $data = [
            'datasource'   => "{$this->route}/datatables/",
            'url_activate' => "{$this->route}/activateProduct",
            'url_exportar' => "{$this->route}/exportar/",
            'fornecedores' => $this->fornecedor->find('id, nome_fantasia'),
            'header'       => $this->template->header(['title' => $page_title]),
            'navbar'       => $this->template->navbar(),
            'sidebar'      => $this->template->sidebar(),
            'heading'      => $this->template->heading([
                'page_title' => $page_title,
                'buttons' => [
                    [
                        'type' => 'button',
                        'id' => 'btnExport',
                        'url' => "{$this->route}/exportar",
                        'class' => 'btn-primary',
                        'icone' => 'fa-file-excel', 
                        'label' => 'Exportar Excel'
                    ]
                ]
            ]),
            'scripts'      => $this->template->scripts()
        ];

        $this->load->view("{$this->views}/main", $data);
    }

    public function datatables($id_fornecedor)
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'produtos_catalogo pc',
            [
                ['db' => 'pc.codigo', 'dt' => 'codigo'],
                ['db' => 'pc.nome_comercial', 'dt' => 'nome_comercial'],
                ['db' => 'pc.apresentacao', 'dt' => 'apresentacao'],
                [
                    'db' => 'pc.nome_comercial',
                    'dt' => 'produto_descricao', "formatter" => function ($d, $r) {
                        return $d . " - " . $r['apresentacao'];
                }],
                ['db' => 'pc.marca', 'dt' => 'marca'],
                ['db' => 'pc.bloqueado', 'dt' => 'bloqueado'],
                ['db' => 'pc.id_fornecedor', 'dt' => 'id_fornecedor'],
                ['db' => 'f.razao_social', 'dt' => 'razao_social'],
                ['db' => 'f.id_estado', 'dt' => 'id_estado'],
                ['db' => 'pc.ativo', 'dt' => 'ativo'],
            ],
            [
                ['fornecedores f', 'f.id = pc.id_fornecedor', 'LEFT']
            ],
            "pc.id_fornecedor = {$id_fornecedor} AND pc.ativo = 0 AND pc.aprovado = 0 AND pc.bloqueado = 0"
        );


        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function activateProduct()
    {
        if ( $this->input->is_ajax_request() ) {

            $post = $this->input->post();

            $this->db->where('codigo', $post['codigo']);
            $this->db->where('id_fornecedor', $post['id_fornecedor']);
            $updt = $this->db->update('produtos_catalogo', ['ativo' => 1, 'aprovado' => 1 ]);

            if ( $updt ) {

                $output = ["type" => "success", "message" => "Produto aprovado com sucesso"];
            } else {
                $output = ["type" => "warning", "message" => "Erro ao aprovar o produto, tente novamente!"];
            }
            
            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function exportar($id_fornecedor = null)
    {
        if ( isset($id_fornecedor) ) {
                
            $this->db->select("
                pc.codigo,
                CASE WHEN pc.descricao is null THEN CONCAT(pc.nome_comercial, ' - ', pc.apresentacao) ELSE CONCAT(pc.nome_comercial, ' - ', pc.descricao) END AS descricao,
                pc.marca");
            $this->db->from("produtos_catalogo pc");
            $this->db->join('fornecedores f', "f.id = pc.id_fornecedor", "left");
            $this->db->where("pc.id_fornecedor", $id_fornecedor);
            $this->db->where("pc.ativo", 0);
            $this->db->where("pc.aprovado", 0);
            $this->db->where("pc.bloqueado", 0);
            $this->db->order_by("pc.codigo ASC");

            $query = $this->db->get()->result_array();
        } else {
            $query = [];
        }

        if ( count($query) < 1 ) {
            $query[] = [
                'codigo' => '',
                'descricao' => '',
                'marca' => '',
            ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'Produtos'];

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

/* End of file: Catalogo.php */
