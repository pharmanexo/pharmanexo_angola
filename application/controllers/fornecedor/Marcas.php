<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Marcas extends MY_Controller
{

    private $route;
    private $views;
    private $oncoprod;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url("fornecedor/marcas/");
        $this->views = "fornecedor/marcas/";

        $this->load->model('m_marca', 'marca');
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


        $this->load->view("{$this->views}main", $data);
    }

    public function openModal($id_marca = null)
    {
        if ($this->input->post()) {

            $post = $this->input->post();

            if (isset($post['id_marca'])) {

                if (in_array($this->session->id_fornecedor, $this->oncoprod)) {

                    $oncoprod = implode(',', $this->oncoprod);

                    $this->db->where("id_fornecedor in ({$oncoprod})");
                    $this->db->where('marca', $post['marca']);
                    $upd = $this->db->update('produtos_catalogo', ['id_marca' => $post['id_marca']]);
                } else {

                    $this->db->where('id_fornecedor', $this->session->id_fornecedor);
                    $this->db->where('marca', $post['marca']);
                    $upd = $this->db->update('produtos_catalogo', ['id_marca' => $post['id_marca']]);
                }

                if ($upd) {

                    $warning = ['type' => 'success', 'message' => 'Registrado com sucesso'];
                } else {

                    $warning = ['type' => 'warning', 'message' => 'Erro ao registrar'];
                }

            } else {
                $warning = ['type' => 'warning', 'message' => 'Preencha todos os campos!'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        } else {

            $data['form_action'] = "{$this->route}/openModal/";
            $data['title'] = "Alterar Marca";
            $data['select2_marcas'] = "{$this->route}/to_select2_marcas/";


        /*    $marca = $this->db
                ->select('marca')
                ->where('codigo', $id_marca)
                ->where('id_fornecedor', $this->session->userdata('id_fornecedor'))
                ->get('produtos_catalogo')
                ->row_array();

            $data['marca'] = $marca['marca'];*/

            $this->load->view("{$this->views}modal", $data);
        }
    }

    public function to_datatables()
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'produtos_catalogo',
            [
                ['db' => 'produtos_catalogo.codigo', 'dt' => 'codigo'],
                ['db' => 'produtos_catalogo.marca', 'dt' => 'marca'],
                ['db' => 'produtos_catalogo.id_marca', 'dt' => 'id_marca'],
                ['db' => 'marcas.id', 'dt' => 'id'],
                ['db' => 'marcas.cnpj', 'dt' => 'cnpj'],
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
            "id_fornecedor = {$this->session->userdata('id_fornecedor')} and produtos_catalogo.id_marca = 0",
            "produtos_catalogo.marca, produtos_catalogo.id_marca"
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

    public function exportar()
    {
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
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);
        $this->db->group_by('codigo');
        $this->db->order_by('codigo ASC');

        $query = $this->db->get()->result_array();

        if (count($query) < 1) {
            $query[] = [
                'codigo' => '',
                'produto' => '',
                'marca' => '',
                'marca_sintese' => ''
            ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'marcas_produtos'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ($exportar['status'] == false) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }
    }
}

