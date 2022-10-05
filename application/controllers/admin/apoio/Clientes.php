<?php

class Clientes extends MY_Controller
{
    private $views;
    private $route;
    private $bio;

    public function __construct()
    {
        parent::__construct();

        $this->views = "admin/apoio/clientes";
        $this->route = base_url("admin/apoio/clientes");

        $this->apoio = $this->load->database('apoio', true);

        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_usuarios', 'usuarios');
    }

    public function index()
    {
        $page_title = "Compradores";

        $data['datasource'] = "{$this->route}/to_datatables";
        $data['urlUpdate'] = "{$this->route}/atualizar";
        $data['urlDepara'] = "{$this->route}/index_depara";

        # TEMPLATE
        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading(['page_title' => $page_title]);
        $data['scripts'] = $this->template->scripts();

        $id_usuario = $this->session->id_usuario;

        $meta = $this->usuarios->getMetaUser($id_usuario, true);
        $data['meta'] = $this->usuarios->getMetaUser($id_usuario);

        if (isset($meta['total'])) {
            $meta['total'] = doubleval($meta['total']);
        } else {
            $meta['total'] = 0;
        }

        $data['percent'] = $this->porcentagem_nx($meta['total'], META_DEPARA);
        $data['n'] = $meta['total'];

        $this->load->view("{$this->views}/main", $data);
    }

    public function index_depara($id)
    {
        # Cria session
        $this->session->set_userdata(['id_cliente_apoio' => $id]);

        redirect(base_url("admin/apoio/catalogo/consolidacao/"));
    }

    public function atualizar($id)
    {
        $page_title = "Editar Comprador";

        $data['cliente'] = $this->compradores->findById($id);

        $data['formAction'] = "{$this->route}/update/{$id}";

        $src_logo = base_url('/public/clientes/') . $id . '/' . $data['cliente']['logo'];
        $data['src_logo'] = (!is_null($data['cliente']['logo']) && $data['cliente']['logo'] != '') ? $src_logo : base_url('images/avatar-empresa-360sites.png');

        # TEMPLATE
        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts();
        $data['heading'] = $this->template->heading(
            [
                'page_title' => $page_title,
                'buttons' => [
                    [
                        'type' => 'a',
                        'id' => 'btnBack',
                        'url' => "{$this->route}",
                        'class' => 'btn-secondary',
                        'icone' => 'fa-arrow-left',
                        'label' => 'Voltar'
                    ],
                    [
                        'type' => 'submit',
                        'id' => 'btnSave',
                        'form' => 'formComprador',
                        'class' => 'btn-primary',
                        'icone' => 'fa-save',
                        'label' => 'Salvar Alterações'
                    ]
                ]
            ]);


        $this->load->view("{$this->views}/form", $data);
    }

    public function update($id)
    {

        if ($this->input->is_ajax_request()) {

            $post = $this->input->post();

            $this->form_validation->set_error_delimiters('<span>', '</span>');
            $this->form_validation->set_rules('cnpj', 'Cnpj', 'required');
            $this->form_validation->set_rules('razao_social', 'Nome/Razão Social', 'required');
            $this->form_validation->set_rules('nome_fantasia', 'Nome Fantasia', 'required');
            $this->form_validation->set_rules('email', 'E-mail', 'required');
            $this->form_validation->set_rules('estado', 'Estado', 'required');

            if ($this->form_validation->run() === false) {

                $output = ['type' => 'warning', 'message' => $this->form_validation->error_array()];
            } else {

                $post['id'] = $id;

                $update = $this->compradores->update($post);

                if ($update) {

                    $output = ['type' => 'success', 'message' => notify_update, 'route' => $this->route];
                } else {

                    $output = ['type' => 'warning', 'message' => notify_failed];
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function to_datatables()
    {
        $data = $this->datatable->exec(
            $this->input->post(),
            'pharmanexo.compradores c',
            [
                ['db' => 'c.id', 'dt' => 'id'],
                ['db' => 'c.razao_social', 'dt' => 'razao_social'],
                ['db' => 'c.cnpj', 'dt' => 'cnpj'],
                ['db' => 'c.estado', 'dt' => 'estado'],
                ['db' => 'c.id', 'dt' => 'comdepara', 'formatter' => function ($d) {
                    //return $this->getDadosCatalogo($d)['com'];
                }],
                ['db' => 'c.id', 'dt' => 'semdeparageral', 'formatter' => function ($d) {
                  //  return ($this->getDadosCatalogo($d)['sem']);
                }],
                ['db' => 'c.id', 'dt' => 'semdepara', 'formatter' => function ($d) {
                  //  return ($this->getDadosCatalogo($d)['sem'] - $this->getDadosCatalogo($d)['ocultos']);
                }],
                ['db' => 'c.id', 'dt' => 'ocultos', 'formatter' => function ($d) {
                   // return $this->getDadosCatalogo($d)['ocultos'];
                }],
                ['db' => 'c.id', 'dt' => 'total', 'formatter' => function ($d) {
                  //  return $this->getDadosCatalogo($d)['catalogo'];
                }],
            ],
            [
                ['pharmanexo.compradores_integrador ci', 'ci.id_cliente = c.id']
            ],
            'ci.id_integrador = 3',
            "c.id"

        );


        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    private function getDadosCatalogo($id_cliente)
    {
        $total = count($this->apoio->select('id')->where('id_cliente', $id_cliente)->get('catalogo')->result_array());
        $ocultos = count($this->apoio->select('id')->where('id_cliente', $id_cliente)->where('ocultar', 1)->get('catalogo')->result_array());
        $com = count($this->db->select('codigo_hospital')
            ->where('id_cliente', $id_cliente)
            ->where('integrador', 3)
            ->group_by('codigo_hospital')->get('vw_produtos_cliente_depara')->result_array());
        $sem = count($this->db->select('*')->where('id_cliente', $id_cliente)->get('vw_produtos_clientes_sem_depara_apoio')->result_array());

        $data = [
            'catalogo' => $total,
            'com' => $com,
            'sem' => $sem,
            'ocultos' => $ocultos
        ];

        return $data;

    }

    function porcentagem_nx($parcial, $total)
    {
        return ($parcial * 100) / $total;
    }


}

