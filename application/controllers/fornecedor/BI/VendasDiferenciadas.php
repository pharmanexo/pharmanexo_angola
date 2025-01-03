<?php
defined('BASEPATH') or exit('No direct script access allowed');


class VendasDiferenciadas extends MY_Controller
{

    private $route;
    private $views;
    private $oncoexo;
    private $oncoprod;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('/fornecedor/BI/vendasDiferenciadas');
        $this->views = 'fornecedor/BI/vendasDiferenciadas';

        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_marca', 'marca');
        $this->load->model('m_compradores', 'comprador');
        $this->load->model('m_estados', 'estado');
        $this->load->model('m_bi', 'BI');

        $this->oncoprod = explode(',', ONCOPROD);
        $this->oncoexo = explode(',', ONCOEXO);
    }

    /**
     * Exibe a tela inicial do BI
     *
     * @return view
     */
    public function index()
    {
        $page_title = "Vendas Diferenciadas";

        if ($this->session->has_userdata('id_matriz')) {

            $data['selectMatriz'] = $this->fornecedor->find("id, nome_fantasia", "id_matriz = {$this->session->id_matriz} AND id != {$this->session->id_fornecedor}");
        }

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'styles' => [
                THIRD_PARTY . 'theme/plugins/flatpickr/flatpickr.min.css'
            ]
        ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading(['page_title' => $page_title,
        'buttons' => [

            [
                'type' => 'a',
                'id' => 'btnVoltar',
                'url' => "produtosPreco",
                'class' => 'btn-secondary',
                'icone' => 'fa-arrow-left',
                'label' => 'Retornar'
            ]]]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                'https://cdn.jsdelivr.net/npm/apexcharts',
                THIRD_PARTY . 'theme/plugins/flatpickr/flatpickr.min.js'
            ]
        ]);

        $data['estados'] = $this->estado->find("id, uf, CONCAT(uf, ' - ', descricao) AS estado", null, FALSE, 'estado ASC');
        $data['compradores'] = $this->comprador->find("id, CONCAT(cnpj, ' - ', razao_social) AS comprador", null, FALSE, 'comprador ASC');

        # URLs

        # URLs
        $data['url'] = "{$this->route}/main";

        $data['to_datatable'] = "{$this->route}/to_datatable";

        if ($this->session->has_userdata('id_matriz')) {

            $data['selectMatriz'] = $this->fornecedor->find("id, nome_fantasia", "id_matriz = {$this->session->id_matriz}");
        }

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * obtem os dados para grafico e indicadores da pagina
     *
     * @param - post - String data inicio
     * @param - post - String data fim
     * @param - post - int ID fornecedor (opcional)
     * @param - post - int ID comprador (opcional)
     * @param - post - String UF estado (opcional)
     * @return json
     */
    public function main()
    {

        $post = $this->input->post();

        $dados = $this->filtros($post);

        # Indicadores
        $data['indicadores'] = $this->BI->getValuesRegraVenda($dados);

        $data['series'] = [intval($data['indicadores']['AUTOMATICO']), intval($data['indicadores']['PROMOCAO']), intval($data['indicadores']['DISTRIBUIDOR']), intval($data['indicadores']['DESCONTO'])];
        $data['labels'] = ["AUTOMATICO", "PROMOCAO", "DIST X DIST", "COM DESCONTO"];

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Trata os dados do POST
     *
     * @param - post - String data inicio
     * @param - post - String data fim
     * @param - post - int ID fornecedor (opcional)
     * @param - post - int ID comprador (opcional)
     * @param - post - String UF estado (opcional)
     * @return json
     */
    private function filtros($post)
    {



        $id_fornecedor = (!empty($post['id_fornecedor'])) ? $post['id_fornecedor'] : $this->session->id_fornecedor;

        $post_id = false;

        if ($this->session->has_userdata('id_matriz') && !empty($post['id_fornecedor'])) {

            $post_id = true;
        }

        $filial = $this->BI->matrizFilial(FALSE, $post_id, $id_fornecedor);

        $empresa = "";
        $fornecedor = "";

        foreach ($filial as $key => $item) {

            $empresa = $key;
            $fornecedor = $item;
        }


        $dados = [
            'fornecedor' => $fornecedor,
            'id_fornecedor' => $id_fornecedor,
            'empresa' => $empresa,
            'uf_cotacao' => $post['uf_cotacao'],
            'id_cliente' => $post['id_cliente'],
            'promocao' => $post['promocao'],
            'desconto' => $post['desconto']
        ];

        return $dados;
    }

    public function to_datatable()
    {
        $post = $this->input->post();

        $dados = $this->filtros($post);

        $data = $this->BI->getVendasDiferenciadas($dados, $post);


        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}