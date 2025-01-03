<?php


class Clientes_spi extends MY_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('/fornecedor/clientes_spi/');
        $this->views = 'fornecedor/clientes_spi/';
        $this->load->model('m_compradores');

    }

    public function index()
    {
        $page_title = "Clientes São Paulo Interior";

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
                ['db' => 'compradores.id', 'dt' => 'id'],
                ['db' => 'compradores.cnpj', 'dt' => 'cnpj'],
                ['db' => 'compradores.razao_social', 'dt' => 'razao_social'],
                ['db' => 'compradores.nome_fantasia', 'dt' => 'nome_fantasia'],
                ['db' => 'compradores.cidade', 'dt' => 'cidade'],
                ['db' => 'compradores.estado', 'dt' => 'estado'],
                ['db' => 'compradores.spi', 'dt' => 'spi', 'formatter' => function ($value, $row) {

                    $checked = false;
                    if ($row['spi']) $checked = true; //verifica se o valor de spi é igual a 1

                    $result = ['value' => $value,
                        'checked' => $checked,
                    ];
                    return $result;
                }],
            ]
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function update()
    {
        if ($this->input->is_ajax_request()) :

            if ($post = $this->input->post()) :

                $data = [
                    'id' => $post['id_cliente'],
                ];

                $cliente = $this->m_compradores->findById($post['id_cliente']);

                if ($cliente['spi'] == 0):
                    $data['spi'] = 1;
                elseif ($cliente['spi'] == 1):
                    $data['spi'] = 0;
                endif;

                $warn = [];

                if ($this->m_compradores->update($data)):
                    $warn = [
                        'type' => 'success',
                    ];

                    if ($cliente['spi'] == 0):
                        $warn['message'] = 'Cliente adicionado com sucesso!';
                    else:
                        $warn['message'] = 'Cliente removido com sucesso!';
                    endif;
                else:
                    $warn = [
                        'type' => 'warning',
                        'message' => 'Ops, Não foi possível atualizar o cliente'
                    ];
                endif;

                return $this->output->set_content_type('application/json')->set_output(json_encode($warn));
            endif;

        endif;


    }
}