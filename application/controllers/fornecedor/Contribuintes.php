<?php


class Contribuintes extends MY_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('/fornecedor/contribuintes/');
        $this->views = 'fornecedor/contribuintes/';
        $this->load->model('m_clientes_contribuintes');

    }

    public function index()
    {
        $page_title = "Contribuintes";

        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnVoltar',
                   'url' => "javascript:history.back(1)",
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Retornar'
                ]]
        ]);

        $data['urlDatatables'] = "{$this->route}to_datatable";


        $this->load->view($this->views . "list", $data);

    }

    public function to_datatable()
    {
        $r = $this->datatable->exec(
            $this->input->get(),
            'compradores',
            [
                ['db' => 'compradores.id', 'dt' => 'id', 'formatter' => function ($value, $row) {

                    $contribuinte = $this->m_clientes_contribuintes->find('id', "id_cliente = {$value} AND id_fornecedor = {$this->session->id_fornecedor} ");

                    $checked = false;
                    if (!empty($contribuinte)):
                        $checked = true;
                    endif;

                    $result = ['value' => $value,
                        'checked' => $checked,
                    ];
                    return $result;
                }],
                ['db' => 'compradores.cnpj', 'dt' => 'cnpj'],
                ['db' => 'compradores.razao_social', 'dt' => 'razao_social'],
                ['db' => 'compradores.nome_fantasia', 'dt' => 'nome_fantasia'],
                ['db' => 'compradores.cidade', 'dt' => 'cidade'],
                ['db' => 'compradores.estado', 'dt' => 'estado'],
            ]
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function save()
    {
        if ($this->input->is_ajax_request()) {

            if ($post = $this->input->post()) {

                $data = [
                    'id_cliente' => $post['id_cliente'],
                ];

                ///
                /// Grupo de fornecedores
                ///
                $grupo_fornecedores = [
                    12,
                    111,
                    112,
                    115,
                    120,
                    123,
                    126
                ];

                //Se o fornecedor da sessão estiver no grupo de fornecedores, também salva cada um do grupo no banco
                if (in_array($this->session->id_fornecedor, $grupo_fornecedores)):
                    foreach ($grupo_fornecedores as $id):
                        $data['id_fornecedor'] = $id;

                        $contribuinte = $this->m_clientes_contribuintes->find('id', "id_cliente = {$post['id_cliente']} AND id_fornecedor = {$id} ");

                        if (empty($contribuinte)):
                            $this->m_clientes_contribuintes->insert($data);
                        else:
                            $this->m_clientes_contribuintes->delete($contribuinte[0]['id']);
                        endif;
                    endforeach;
                else:
                    $data['id_fornecedor'] = $this->session->id_fornecedor;

                    $contribuinte = $this->m_clientes_contribuintes->find('id', "id_cliente = {$post['id_cliente']} AND id_fornecedor = {$this->session->id_fornecedor} ");

                    if (empty($contribuinte)):
                        $this->m_clientes_contribuintes->insert($data);
                    else:
                        $this->m_clientes_contribuintes->delete($contribuinte[0]['id']);
                    endif;
                endif;


            }

        }


    }
}