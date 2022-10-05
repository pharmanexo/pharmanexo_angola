<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Preco_especial extends MY_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();
        $this->views = "fornecedor/produtos/preco_especial/";
        $this->route = base_url("fornecedor/estoque/preco_especial/");

        $this->load->model('m_estados', 'estados');
    }

    public function index()
    {
        $page_title = "Preços Especiais";

        $data['datatables'] = "{$this->route}datatables/";
        $data['url_update'] = "{$this->route}estados/";
        $data['header'] = $this->template->header([ 'title' => $page_title, ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([ 'page_title' => $page_title ]);
        $data['scripts'] = $this->template->scripts([ 'scripts' => [] ]);

        $this->load->view("{$this->views}main", $data);
    }

    public function estados($codigo)
    {
        $page_title = "Preço por Estado";
        $data['datatables'] = "{$this->route}datatables_estados/{$codigo}";
        $data['form_action'] = "{$this->route}salvar/{$codigo}";
        $data['header'] = $this->template->header([ 'title' => $page_title ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([ 
            'page_title' => $page_title,
            'buttons'    => [
                [
                    'type'  => 'a',
                    'id'    => 'btnBack',
                    'url'   => "{$this->route}",
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ],
                [
                    'type'  => 'submit',
                    'id'    => 'btnSave',
                    'form'  => 'formPreco',
                    'class' => 'btn-primary',
                    'icone' => 'fa-save',
                    'label' => 'Salvar Alterações'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts([ 'scripts' => [] ]);

        $data['estado'] = $this->estados->todosEstados()->result_array();

        $data['preco'] = $this->db->select('*')->where('codigo', $codigo)->get('precos_especiais')->result_array();

        // var_dump($data['preco']); exit();

        $this->load->view("{$this->views}detail", $data);
    }

    public function salvar($codigo)
    {
        $post = $this->input->post();

        $data = [];

        for ($i=0; $i < 27; $i++) { 
            $data[] = [
                'id_estado' => $post['id_estado'][$i],
                'codigo' => $codigo,
                'valor' => ($post['preco'][$i] == 0 ) ? null : $post['preco'][$i],
                'tipo' => ($post['tipo'][$i] == "") ? null : $post['tipo'][$i]
            ];
        }

        // Remove todos os registros para inserir novos
        $this->db->where('codigo', $codigo)->delete('precos_especiais');

        $salvar = $this->db->insert_batch('precos_especiais', $data);

        if ($salvar != false) {
            $output = [ 'type' => 'success', 'message' => 'Preços Atualizados' ];

        } else {
            $output = [ 'type' => 'warning', 'message' => 'Erro ao atualizar preço' ];
        }

        $this->session->set_userdata('warning', $output);

        redirect($this->route);
    }

    public function datatables()
    {
        $datatables = $this->datatable->exec(
            $this->input->get(),
            'vw_produtos_fornecedores',
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'codigo', 'dt' => 'codigo'],
                ['db' => 'produto_descricao', 'dt' => 'produto_descricao'],
                ['db' => 'marca', 'dt' => 'marca'],
                ['db' => 'preco', 'dt' => 'preco', "formatter" => function ($d) {
                    return number_format($d, 4, ',', '.');
                }],
            ],
            null,
            'ativo = 1 AND id_fornecedor = ' . $this->session->userdata('id_fornecedor')
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    public function datatables_estados()
    {
        $datatables = $this->datatable->exec(
            $this->input->get(),
            'estados',
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'uf', 'dt' => 'uf'],
                ['db' => 'descricao', 'dt' => 'descricao']
            ]
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }
}

/* End of file: Controle_cotacoes.php */
