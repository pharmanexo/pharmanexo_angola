<?php

class Pre_match_apoio extends CI_Controller
{
    private $views;
    private $route;
    private $bio;

    public function __construct()
    {
        parent::__construct();

        $this->views = "admin/match";
        $this->route = base_url("admin/match/pre_match_apoio/");

        $this->bio = $this->load->database('bionexo', true);

        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_usuarios', 'usuarios');
        $this->load->model("M_match", "mat");
        $this->load->model('m_estados', 'estado');
        $this->load->model('m_compradores', 'comprador');
    }

    public function index()
    {
        $page_title = "Aprovação de produtos combinados";

        $data['to_datatable'] = "{$this->route}/to_datatables";
        $data['urlUpdate'] = "{$this->route}/atualizar";
        $data['urlDepara'] = "{$this->route}/index_depara";

        # Selects
        $data['estados'] = $this->estado->find("uf, CONCAT(uf, ' - ', descricao) AS estado", null, FALSE, 'estado ASC');
        $data['compradores'] = $this->comprador->find("id, CONCAT(cnpj, ' - ', razao_social) AS comprador", null, FALSE, 'comprador ASC');

        # TEMPLATE
        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading(
            [
                'page_title' => $page_title,
                'buttons' => [
                    [
                        'type' => 'a',
                        'id' => 'btnRejeitar',
                        'url' => "{$this->route}rejeitar",
                        'class' => 'btn-warning',
                        'icone' => 'fa-ban',
                        'label' => 'Rejeitar Selecionados'
                    ],
                    [
                        'type' => 'a',
                        'id' => 'btnAprovar',
                        'url' => "{$this->route}aprovar",
                        'class' => 'btn-primary',
                        'icone' => 'fa-check',
                        'label' => 'Aprovar Selecionados'
                    ]
                ]
            ]
        );
        $data['scripts'] = $this->template->scripts();


        $this->load->view("{$this->views}/main", $data);
    }

    public function aprovar()
    {
        if ($this->input->method() == 'post')
        {
            $id_fornecedor = $this->session->id_fornecedor;
            $post = $this->input->post();


            if (isset($post['el'])){
                if ($this->mat->doMatchClient($post['el'])){
                    $warning = [
                        'type' => 'success',
                        'message' => 'Aprovados com sucesso!'
                    ];
                }else{
                    $warning = [
                        'type' => 'warning',
                        'message' => 'Houve um erro ao aprovar os registros'
                    ];
                }
            }else{
                $warning = [
                    'type' => 'warning',
                    'message' => 'Nenhum produto foi selecionado'
                ];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
    }

    public function rejeitar()
    {
        if ($this->input->method() == 'post')
        {
            $id_fornecedor = $this->session->id_fornecedor;
            $post = $this->input->post();

            if (isset($post['el'])){
                if ($this->mat->undoMatchClient($post['el'])){
                    $warning = [
                        'type' => 'success',
                        'message' => 'Removidos com sucesso'
                    ];
                }else{
                    $warning = [
                        'type' => 'warning',
                        'message' => 'Houve um erro ao gravar os registros'
                    ];
                }
            }else{
                $warning = [
                    'type' => 'warning',
                    'message' => 'Nenhum produto foi selecionado'
                ];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
    }

    public function to_datatables()
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'vw_produtos_pre_depara_apoio',
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'descricao_sintese', 'dt' => 'descricao_sintese'],
                ['db' => 'descricao_catalogo', 'dt' => 'descricao_catalogo'],
                ['db' => 'id_sintese', 'dt' => 'id_sintese'],
                ['db' => 'id_produto', 'dt' => 'id_produto'],
                ['db' => 'cd_produto', 'dt' => 'cd_produto'],
                ['db' => 'codigo_catalogo', 'dt' => 'codigo_catalogo'],
                ['db' => 'id_cliente', 'dt' => 'id_cliente'],
                ['db' => 'id_cliente', 'dt' => 'id_cliente'],
                ['db' => 'nome_fantasia', 'dt' => 'nome_fantasia'],
                ['db' => 'estado', 'dt' => 'estado'],
            ],
            null,
            "integrador = 3"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }


}

