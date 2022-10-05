<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Configurar extends MY_Controller
{
    private $route, $views;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('fornecedor/representantes/configurar');
        $this->views = "fornecedor/representantes/cadastros";

        $this->load->model('m_representante', 'rep');
    }

    public function index()
    {
        $page_title = 'Listar Representantes';

        $data['datatables'] = "{$this->route}/datatables";
        $data['url_delete'] = "{$this->route}/delete/";
        $data['url_update'] = "{$this->route}/update/";


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
                [
                    'type' => 'a',
                    'id' => 'btnInsert',
                    'url' => "{$this->route}/configurar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-plus',
                    'label' => 'Novo Registro'
                ]
            ]
        ]);

        $this->load->view("{$this->views}/main", $data);
    }

    public function configurar()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $data_estados = [];
            $data_clientes = [];

            if (isset($post['clientes'])) {
                $clientes = explode(',', $post['clientes']);
            }

            if (isset($post['estados'])) {
                $estados = explode(',', $post['estados']);
            }

            $rep = [
                "id_representante" => $post['id_representante'],
                "id_fornecedor" => $this->session->id_fornecedor,
                "comissao" => dbNumberFormat($post['comissao']),
                "meta" => dbNumberFormat($post['meta']),
                "gerente" => $post['gerente'],
                "email_gerente" => $post['email_gerente'],
                "supervisor" => $post['supervisor'],
                "email_supervisor" => $post['email_supervisor'],
            ];


            if (isset($estados)) {
                foreach ($estados as $estado) {
                    $dt = [
                        "id_estado" => $estado,
                        "id_fornecedor" => $this->session->id_fornecedor,
                        "id_representante" => $post['id_representante']
                    ];

                    array_push($data_estados, $dt);
                }
            }

            if (isset($clientes)) {
                foreach ($clientes as $cliente) {
                    $dt = [
                        "id_cliente" => $cliente,
                        "id_fornecedor" => $this->session->id_fornecedor,
                        "id_representante" => $post['id_representante']
                    ];

                    array_push($data_clientes, $dt);
                }
            }

            $this->db->trans_begin();

            if (!empty($rep)) {

                $this->db->insert('representantes_fornecedores', $rep);

                if (!empty($data_estados)) {
                    $this->db->insert_batch('representantes_estados', $data_estados);
                }

                if (!empty($data_clientes)) {
                    $this->db->insert_batch('representantes_clientes', $data_clientes);
                }
            }


            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();

                $warning = [
                    "type" => "error",
                    "message" => "Erro ao configurar o representante"
                ];
            } else {
                $this->db->trans_commit();

                $warning = [
                    "type" => "success",
                    "message" => "Representante configurado com sucesso"
                ];
            }

            if (isset($warning)) {
                $this->session->set_userdata('warning', $warning);
                redirect($this->route);
            }


        } else {
            $this->insert();
        }
    }

    public function update($id_representante)
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $data_estados = [];
            $data_clientes = [];

            if (isset($post['clientes'])) {
                $clientes = explode(',', $post['clientes']);
            }

            if (isset($post['estados'])) {
                $estados = explode(',', $post['estados']);
            }

            $rep = [
                "id_representante" => $post['id_representante'],
                "id_fornecedor" => $this->session->id_fornecedor,
                "comissao" => dbNumberFormat($post['comissao']),
                "meta" => dbNumberFormat($post['meta']),
                "gerente" => $post['gerente'],
                "email_gerente" => $post['email_gerente'],
                "supervisor" => $post['supervisor'],
                "email_supervisor" => $post['email_supervisor'],
            ];


            if (isset($estados) && !empty($estados)) {
                foreach ($estados as $estado) {
                   if (!empty($estado)){
                       $dt = [
                           "id_estado" => $estado,
                           "id_fornecedor" => $this->session->id_fornecedor,
                           "id_representante" => $post['id_representante']
                       ];

                       array_push($data_estados, $dt);
                   }
                }
            }

            if (isset($clientes) && !empty($clientes)) {
                foreach ($clientes as $cliente) {
                    if (!empty($cliente)){
                        $dt = [
                            "id_cliente" => $cliente,
                            "id_fornecedor" => $this->session->id_fornecedor,
                            "id_representante" => $post['id_representante']
                        ];
                        array_push($data_clientes, $dt);
                    }
                }
            }

            $this->db->trans_begin();

            if (!empty($rep)) {

                $this->db->update('representantes_fornecedores', $rep, "id_representante = {$post['id_representante']}");

                $this->deleteDados($post['id_representante']);

                if (!empty($data_estados)) {

                    $this->db->insert_batch('representantes_estados', $data_estados);
                }

                if (!empty($data_clientes)) {

                    $this->db->insert_batch('representantes_clientes', $data_clientes);
                }
            }


            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();

                $warning = [
                    "type" => "error",
                    "message" => "Erro ao configurar o representante"
                ];
            } else {
                $this->db->trans_commit();

                $warning = [
                    "type" => "success",
                    "message" => "Representante configurado com sucesso"
                ];
            }

            if (isset($warning)) {
                $this->session->set_userdata('warning', $warning);
                redirect($this->route);
            }


        } else {
            $this->form($id_representante);
        }
    }

    public function deleteDados($id)
    {
        $this->db->where('id_representante', $id);
        $this->db->delete('representantes_clientes');

        $this->db->where('id_representante', $id);
        $this->db->delete('representantes_estados');


    }
    public function delete($id_rep)
    {
        if (isset($id_rep)) {

            $this->db->trans_begin();

            #delete vinculo
            $this->db->delete('representantes_fornecedores', ['id_fornecedor' => $this->session->id_fornecedor, 'id_representante' => $id_rep]);

            #deleta estados
            $this->db->delete('representantes_estados', ['id_fornecedor' => $this->session->id_fornecedor, 'id_representante' => $id_rep]);

            #deleta clientes
            $this->db->delete('representantes_clientes', ['id_fornecedor' => $this->session->id_fornecedor, 'id_representante' => $id_rep]);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();

                $warning = [
                    "type" => "error",
                    "message" => "Erro ao excluir o representante"
                ];
            } else {
                $this->db->trans_commit();

                $warning = [
                    "type" => "success",
                    "message" => "Representante excluido com sucesso"
                ];
            }

            if (isset($warning)) {
                $this->output->set_content_type('application/json')->set_output(json_encode($warning));
            }


        }
    }

    private function insert()
    {
        $page_title = 'Configurar Representante';

        $data['action'] = "{$this->route}/configurar";

        $data['representantes'] = $this->db->query("SELECT * FROM
                                                    pharmanexo.representantes r
                                                    where r.id not in (select rp.id_representante 
                                                    from pharmanexo.representantes_fornecedores rp
                                                     where rp.id_fornecedor = {$this->session->id_fornecedor})")
            ->result_array();

        $data['clientes'] = $this->db->get('compradores')->result_array();
        $data['estados'] = $this->db->get('estados')->result_array();

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'styles' => [
                THIRD_PARTY . 'plugins/bootstrap-duallistbox/css/bootstrap-duallistbox.css'
            ]
        ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/bootstrap-duallistbox/js/jquery.bootstrap-duallistbox.js'
            ]
        ]);
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btn_back',
                    'url' => $this->route,
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ],
                [
                    'type' => 'submit',
                    'id' => 'btn_rep',
                    'form' => "frm_rep",
                    'class' => 'btn-primary',
                    'icone' => 'fa-check',
                    'label' => 'Salvar'
                ]
            ]
        ]);

        $this->load->view("{$this->views}/form", $data);
    }

    private function form($id)
    {
        $page_title = 'Configurar Representante';

        if (isset($id)){
            $data['action'] = "{$this->route}/update/{$id}";
        }else{
            $data['action'] = "{$this->route}/configurar";
        }


        $data['representante'] = $this->db
            ->select('r.*, rf.id as id_rep_forn')
            ->from('representantes_fornecedores rf')
            ->join('representantes r', 'r.id = rf.id_representante')
            ->where('r.id', $id)
            ->get()
            ->row_array();


        $data['dados'] = $this->db->where('id', $data['representante']['id_rep_forn'])->get('representantes_fornecedores')->row_array();

        $est = $this->db->where('id_representante', $id)->get('representantes_estados')->result_array();
        $cli = $this->db->where('id_representante', $id)->get('representantes_clientes')->result_array();


        $data['clientes'] = $this->db->get('compradores')->result_array();


        if (!empty($cli)) {
            foreach ($data['clientes'] as $k => $cliente) {
                foreach ($cli as $item) {

                    if ($cliente['id'] == $item['id_cliente']) {
                        $data['clientes'][$k]['checked'] = true;
                    }
                }
            }
        }


        $data['estados'] = $this->db->get('estados')->result_array();

        foreach ($data['estados'] as $k => $estado) {

            foreach ($est as $item) {

                if ($estado['id'] == $item['id_estado']) {
                    $data['estados'][$k]['checked'] = true;
                }
            }
        }


        $data['header'] = $this->template->header([
            'title' => $page_title,
            'styles' => [
                THIRD_PARTY . 'plugins/bootstrap-duallistbox/css/bootstrap-duallistbox.css'
            ]
        ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/bootstrap-duallistbox/js/jquery.bootstrap-duallistbox.js'
            ]
        ]);
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btn_back',
                    'url' => $this->route,
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ],
                [
                    'type' => 'submit',
                    'id' => 'btn_rep',
                    'form' => "frm_rep",
                    'class' => 'btn-primary',
                    'icone' => 'fa-check',
                    'label' => 'Salvar'
                ]
            ]
        ]);

        $this->load->view("{$this->views}/form", $data);
    }

    public function datatables()
    {
        $datatables = $this->datatable->exec(
            $this->input->post(),
            'representantes_fornecedores',
            [
                ['db' => 'representantes_fornecedores.id', 'dt' => 'id'],
                ['db' => 'representantes_fornecedores.id_representante', 'dt' => 'id_representante'],
                ['db' => 'representantes_fornecedores.id_fornecedor', 'dt' => 'id_fornecedor'],
                ['db' => 'representantes.id', 'dt' => 'id_representante'],
                ['db' => 'representantes.nome', 'dt' => 'nome'],
                ['db' => 'representantes.cnpj', 'dt' => 'cnpj'],
                ['db' => 'representantes.email', 'dt' => 'email'],
                ['db' => 'representantes.telefone_comercial', 'dt' => 'telefone_comercial'],
                ['db' => 'representantes.telefone_celular', 'dt' => 'telefone_celular'],
            ],
            [
                ['representantes', 'representantes.id = representantes_fornecedores.id_representante']
            ],
            "representantes_fornecedores.id_fornecedor = {$this->session->id_fornecedor}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    public function exportar()
    {
        $this->db->select("r.nome, r.cnpj, r.email, r.telefone_comercial, r.telefone_celular");
        $this->db->from("representantes_fornecedores rf");
        $this->db->join('representantes r', 'r.id = rf.id_representante');
        $this->db->where('rf.id_fornecedor', $this->session->id_fornecedor);
        $this->db->order_by("nome ASC");

        $query = $this->db->get()->result_array();

        if (count($query) < 1) {
            $query[] = [
                'nome' => '',
                'cnpj' => '',
                'email' => '',
                'telefone_comercial' => '',
                'telefone_celular' => ''
            ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'representantes'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ($exportar['status'] == false) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }
}