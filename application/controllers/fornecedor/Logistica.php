<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Logistica extends MY_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/logistica');
        $this->views = 'fornecedor/logistica';

        $this->load->model('m_logistica', 'logistica');
        $this->load->model('m_historico_ordem_compra', 'historico');
    }

    public function index()
    {
        $page_title = "Logística";

        $data['datatable_src'] = "{$this->route}/to_datatable";
        $data['url_update'] = "{$this->route}/open_modal";

        $data['header'] = $this->template->header([
            'title' => 'Logística'
        ]);

        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();

        $data['heading'] = $this->template->heading([
            'page_title' => $page_title
        ]);

        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/main", $data);
    }

    public function update()
    {
        $response = [];

        $this->form_validation->set_rules('codigo_rastreio', 'Código de Rastreio', 'required|min_length[10]|max_length[15]');
        $this->form_validation->set_rules('transportadora', 'Transportadora', 'required|max_length[50]');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() === FALSE) {
            $errors = [];
            foreach ($this->input->post() as $key => $value) {
                $errors[$key] = form_error($key);
            }

            $response['errors'] = array_filter($errors);
            $response['status'] = false;
        } else {
            $data = [];

            $data['id'] = $this->input->post('id_ordem_compra');
            $data['codigo_rastreio'] = $this->input->post('codigo_rastreio');
            $data['transportadora'] = $this->input->post('transportadora');

            $id = $this->logistica->update($data);
            $response['status'] = true;
            $response['id_ordem_compra'] = $id;
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function to_datatable()
    {
        $r = $this->datatable->exec(
            $this->input->get(),
            'ordens_compra',
            [
                ['db' => 'ordens_compra.id', 'dt' => 'id'],
                ['db' => 'ordens_compra.ordem_compra', 'dt' => 'ordem_compra'],
                ['db' => 'ordens_compra.data_emissao', 'dt' => 'data_emissao', "formatter" => function ($d) {
                    return date('d/m/Y', strtotime($d));
                }],
                ['db' => 'compradores.cnpj', 'dt' => 'cnpj'],
                ['db' => 'compradores.razao_social', 'dt' => 'razao_social'],
                ['db' => 'ordens_compra.valor_total', 'dt' => 'valor_total'],
                ['db' => 'status_ocs.descricao', 'dt' => 'status_ordem_compra']
            ],
            [
                ['status_ocs', 'ordens_compra.id_status_ordem_compra = status_ocs.id'],
                ['compradores', 'ordens_compra.id_cliente = compradores.id'],
            ],
            'ordens_compra.id_fornecedor = ' . $this->session->userdata('id_fornecedor')
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function open_modal($id)
    {
        $data['title'] = "Histórico Logística";
        $data['row'] = $this->logistica->findById($id);
        $data['historicos'] = $this->historico->find('*', ['id_ordem_compra' => $id], FALSE);
        $data['url_update'] = "{$this->route}/update";
        $data['url_salvar_historico'] = "{$this->route}/salvar_historico";

        $this->load->view("{$this->views}/modal", $data);
    }

    public function salvar_historico()
    {
        $response = [];
        $this->form_validation->set_rules('descricao', 'Descrição', 'required|max_length[100]');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() === FALSE) {
            $errors = [];
            foreach ($this->input->post() as $key => $value) {
                $errors[$key] = form_error($key);
            }

            $response['errors'] = array_filter($errors);
            $response['status'] = false;
        } else {
            $data = $this->input->post();
            $id = $this->historico->insert($data);
            $response['row'] = $this->historico->findById($id);
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}

/* End of file: Logistica.php */
