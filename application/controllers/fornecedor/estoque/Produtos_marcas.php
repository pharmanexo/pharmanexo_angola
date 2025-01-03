<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Produtos_marcas extends MY_Controller
{

    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url("/fornecedor/estoque/Produtos_marcas/");
        $this->views = "fornecedor/produtos/produtos_marcas";
    }

    public function index()
    {
        $this->main();
    }

    private function main()
    {
        $page_title = "Marcas";

        $data['to_datatable'] = "{$this->route}to_datatables/";
        $data['url_link'] = "{$this->route}openModal/";  
      
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
                    'url' => "{$this->route}/exportar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel', 
                    'label' => 'Exportar Excel'
                ],
            ]
        ]);

      
        $this->load->view("{$this->views}/main", $data);
    }

    public function openModal()
    {
        if ($this->input->post()) {

            $post = $this->input->post();

            if ((isset($post['marca']) && !empty($post['marca'])) && isset($post['id_marca']) && !empty($post['id_marca']) ) {
                
                $this->db->where('id_fornecedor', $this->session->id_fornecedor);
                $this->db->where('marca', $post['marca']);
                $this->db->update('produtos_catalogo', ['id_marca' => $post['id_marca']]);

                if (true) {

                    $warning = ['type' => 'success', 'message' => 'Registrado com sucesso'];
                } else {

                    $warning = ['type' => 'warning', 'message' => 'Erro ao registrar'];
                }

            } else {
                $warning = ['type' => 'warning', 'message' => 'Preencha todos os campos!'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));  
        } else {

            $data['form_action'] = "{$this->route}openModal";
            $data['title'] = "Combinar Marca";
            $data['select2_marcas'] = "{$this->route}to_select2_marcas/";

            $this->load->view("{$this->views}/modal", $data);
        }
    }

    public function to_datatables()
    {
        $r = $this->datatable->exec(
            $this->input->get(),
            'produtos_catalogo',
            [
                ['db' => 'codigo', 'dt' => 'codigo'],
                ['db' => 'nome_comercial', 'dt' => 'nome_comercial'],
                ['db' => 'marca', 'dt' => 'marca'],
                ['db' => 'bloqueado', 'dt' => 'bloqueado'],
                ['db' => 'ativo', 'dt' => 'ativo'],
                [
                    'db' => 'apresentacao',
                    'dt' => 'produto_descricao', "formatter" => function ($d, $r) {
                        return $r['nome_comercial'] . " - " . $d;
                }],
            ],
            null,
            "id_fornecedor = {$this->session->userdata('id_fornecedor')} AND id_marca = 0",
            "marca"
        );


        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function to_select2_marcas()
    {
        $data = [];
        

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
        $this->db->select("marca");
        $this->db->from("produtos_catalogo");
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);
        $this->db->where('id_marca', 0);
        $this->db->group_by('marca');
        $this->db->order_by('marca ASC');

        $query = $this->db->get()->result_array();

        if ( count($query) < 1 ) {
            $query[] = [
                'marca' => '',
            ];
        }

        $dados_page = ['dados' => $query , 'titulo' => 'lotes'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }
    }
}

