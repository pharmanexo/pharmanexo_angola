<?php

class Pedidos extends Admin_controller
{
    private $route, $views;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('admin/pedidos');
        $this->views = "admin/pedidos";
        $this->load->model('pedido_produto_fornecedor', 'ppf');
        $this->load->model('m_compradores', 'cliente');
    }

    public function index()
    {
        $page_title = "Pedidos";
        $data['datatable'] = "{$this->route}/datatables";
        $data['url_update'] = "{$this->route}/atualizar/";
        $data['header'] = $this->template->header([ 'title' => 'Pedidos' ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([ 'page_title' => $page_title ]);
        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/main", $data);
    }

    public function datatables()
    {
        $datatables = $this->datatable->exec(
            $this->input->post(),
            'vw_pedidos',
            [
                ['db' => 'id', 'dt' => 'id'],
                [
                    'db' => 'data_criacao', 
                    'dt' => 'data_criacao', 
                    'formatter' => function($value, $row) {
                        return date('d/m/Y H:i', strtotime($value));
                    }
                ],
                ['db' => 'cnpj', 'dt' => 'cnpj'],
                ['db' => 'razao_social', 'dt' => 'razao_social'],
                ['db' => 'cidade', 'dt' => 'cidade'],
                ['db' => 'uf', 'dt' => 'uf'],
                ['db' => 'total_itens', 'dt' => 'total_itens'],
                ['db' => 'total', 'dt' => 'total'],
                ['db' => 'status', 'dt' => 'status']
            ]
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    public function atualizar($id)
    {
        $page_title = "Avaliar Pedido";
        $data['datasource'] = base_url("/admin/pedidos_produtos/to_datatable/{$id}");
        $data['url_update'] = "{$this->route}/atualizar/";
        $data['url_status'] = "{$this->route}/modal_refuse/";
        $data['header'] = $this->template->header([ 'title' => 'Avaliar Pedido' ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [   
                    'type' => 'a',
                    'id' => 'btn_retomar',
                    'class' => 'btn-primary',
                    'icone' => 'fa-clock',
                    'label' => 'Retomar Produtos'
                ],
                [   
                    'type' => 'a',
                    'id' =>  'btn_recusar',
                    'class' => 'btn-danger',
                    'icone' => 'fa-ban',
                    'label' => 'Recusar Produtos'
                ],
                [   
                    'type' => 'a',
                    'url' => $this->route,
                    'class' => 'btn-primary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts();

        $pedido_produto = $this->ppf->find('*', "id_pedido = {$id}", true);
        $data['entity'] = $this->cliente->find('*', "id = {$pedido_produto['id_cliente']}", true);

        $this->load->view("{$this->views}/form", $data);
    }

    public function modal_refuse()
    {
        if ($this->input->is_ajax_request()) {
          
            if ( $this->input->method() == 'post' ) {

                $post = $this->input->post();
                $output = [];

                if (isset($post['data'])) {

                    foreach ($post['ids'] as $id) {
            
                        if ( isset($post['status']) && $post['status'] == 0 ) {
            
                            $data = [ 'justificativa' => '', 'status' => 0 ];
                        }
            
                        $atualizar = $this->ppf->atualizar($id, $data);
                    
                        if (!$atualizar) {
                            $output = json_encode(['type' => 'danger', 'message' => 'Falha ao retomar']);
                            $this->output->set_content_type('application/json')->set_output($output);    
                        }
                    }
            
                    $output = json_encode(['type' => 'success', 'message' => 'Este produto foi retomado']);
                    $this->output->set_content_type('application/json')->set_output($output);
            
                } else {
                    
                    foreach (explode(',', $post['ids']) as $id) {
            
                        if ( isset($post['status']) && $post['status'] == 2  ) {
            
                            $data = [ 'justificativa' => $post['justificativa'], 'status' => 2 ];
                        } else {
                            if ( isset($post['status']) && $post['status'] == 0 ) {
            
                                $data = [ 'justificativa' => '', 'status' => 0 ];
                            }
                        }
            
                        $atualizar = $this->ppf->atualizar($id, $data);
                    
                        if (!$atualizar) {
                            $output = json_encode(['type' => 'danger', 'message' => 'Falha ao recusar']);
                            $this->output->set_content_type('application/json')->set_output($output);    
                        }
                    }
            
                    $output = json_encode(['type' => 'success', 'message' => 'Este produto foi recusado']);
                    $this->output->set_content_type('application/json')->set_output($output);
                }
            } else {
                
                $request = $this->input->get();

                $data = [ 
                    'title' => 'Justifique a recusa dos Produtos', 
                    'frm_action' => "{$this->route}/modal_refuse",
                    'ids' => $request['ids']
                ];
                    
                $this->load->view("admin/pedidos_produtos/modal_refuse", $data);
            }
        }
    }
}
