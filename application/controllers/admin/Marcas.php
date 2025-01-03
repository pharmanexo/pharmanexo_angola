<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Marcas extends Admin_controller
{

    private $route;
    private $views;
    private $oncoprod;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url("admin/marcas/");
        $this->views = "admin/marcas/";

        $this->load->model('m_marca', 'marca');
        $this->load->model('m_fornecedor', 'fornecedor');
        $this->oncoprod = explode(',', ONCOPROD);
    }

    public function index()
    {
        $this->main();
    }

    private function main()
    {
        $page_title = "Marcas";

        $data['to_datatable'] = "{$this->route}to_datatables";
        $data['url_link'] = "{$this->route}openModal/";  
        $data['url_exportar'] = "{$this->route}exportar/";

        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}exportar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel', 
                    'label' => 'Exportar Excel'
                ],
            ]
        ]);

        $data['fornecedores'] = $this->fornecedor->find('*');
      
        $this->load->view("{$this->views}main", $data);
    }

    public function openModal($id_marca = null)
    {
        if ($this->input->post()) {

            $post = $this->input->post();

            if ( isset($post['id_marca']) && isset($post['id_fornecedor']) ) {

                if ( in_array($post['id_fornecedor'], $this->oncoprod) ) {
          
                    $oncoprod = implode(',', $this->oncoprod);

                    $this->db->where("id_fornecedor in ({$oncoprod})");
                    $this->db->where('codigo', $post['codigo']);
                    $upd = $this->db->update('produtos_catalogo', ['id_marca' => $post['id_marca']]);
               } else {

                    $this->db->where('id_fornecedor', $post['id_fornecedor']);
                    $this->db->where('codigo', $post['codigo']);
                    $upd = $this->db->update('produtos_catalogo', ['id_marca' => $post['id_marca']]);
               }

                if ( $upd ) {

                    $warning = ['type' => 'success', 'message' => 'Registrado com sucesso'];
                } else {

                    $warning = ['type' => 'warning', 'message' => 'Erro ao registrar'];
                }

            } else {
                $warning = ['type' => 'warning', 'message' => 'Preencha todos os campos!'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));  
        } else {

            $data['form_action'] = "{$this->route}openModal/";
            $data['title'] = "Alterar Marca";
            $data['select2_marcas'] = "{$this->route}to_select2_marcas/";

            if ( isset($id_marca) ) {
               
                if ( $id_marca == 0 || $id_marca == null ) {

                    $marca = "";
                } else {

                    $marca = $this->marca->get_row($id_marca)['marca'];
                }
            }

            $data['marca'] = $marca;

            $this->load->view("{$this->views}modal", $data);
        }
    }

    public function to_datatables($id_fornecedor = null)
    {

        $where = ( isset($id_fornecedor) ) ?  "id_fornecedor = {$id_fornecedor}" : "id_fornecedor = 099999999" ;
      
        $r = $this->datatable->exec(
            $this->input->post(),
            'produtos_catalogo',
            [
                ['db' => 'produtos_catalogo.codigo', 'dt' => 'codigo'],
                ['db' => 'produtos_catalogo.nome_comercial', 'dt' => 'nome_comercial'],
                ['db' => 'produtos_catalogo.marca', 'dt' => 'marca'],
                ['db' => 'produtos_catalogo.bloqueado', 'dt' => 'bloqueado'],
                ['db' => 'produtos_catalogo.ativo', 'dt' => 'ativo'],
                ['db' => 'produtos_catalogo.id_marca', 'dt' => 'id_marca'],
                ['db' => 'marcas.id', 'dt' => 'id'],
                ['db' => 'marcas.cnpj', 'dt' => 'cnpj'],
                ['db' => 'produtos_catalogo.apresentacao', 'dt' => 'produto_descricao', "formatter" => function ($value, $row) {
                        return $row['nome_comercial'] . " - " . $value;
                }],
                ['db' => 'marcas.marca', 'dt' => 'marca_sintese', "formatter" => function ($value, $row) {
                      
                    if ($row['id_marca'] == 0 || $row['id_marca'] == null) {
                        return "<small>Sem Marca Sintese</small>";
                    }

                    return $value;
                }]
            ],
            [
                ['marcas', 'produtos_catalogo.id_marca = marcas.id', 'left']
            ],
            "{$where}",
            "codigo"
        );


        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function to_select2_marcas()
    {
        $data = [];
        if (isset($_GET['page'])) {
            $page = $this->input->get('page');
            $length = 50;
            $data = [
                "start" => (($page - 1) * 50),
                "length" => $length
            ];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($this->select2->exec(
            $this->input->get(),
            "marcas",
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'marca', 'dt' => 'marca'],
            ]
        )));
    }

    public function exportar($id_fornecedor = null)
    {
        if ( isset($id_fornecedor) ) {

            $this->db->select("
                pc.codigo,
                CASE WHEN pc.descricao is null 
                    THEN CONCAT(pc.nome_comercial, ' - ', pc.apresentacao) 
                    ELSE CONCAT(pc.nome_comercial, ' - ', pc.descricao) END AS produto,
                pc.marca,
                CASE WHEN pc.id_marca is null OR pc.id_marca = 0 
                    THEN 'Sem Marca Sintese registrado'
                    ELSE m.marca END AS marca_sintese
            ");
            $this->db->from("produtos_catalogo pc");
            $this->db->join('marcas m', 'pc.id_marca = m.id', 'left');
            $this->db->where('id_fornecedor', $id_fornecedor);
            $this->db->group_by('codigo');
            $this->db->order_by('codigo ASC');

            $query = $this->db->get()->result_array();
        } else {

            $query = [];
        }

        if ( count($query) < 1 ) {
            $query[] = [
                'codigo' => '',
                'produto' => '',
                'marca' => '',
                'marca_sintese' => ''
            ];
        }

        $dados_page = ['dados' => $query , 'titulo' => 'marcas_produtos'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }
    }
}
