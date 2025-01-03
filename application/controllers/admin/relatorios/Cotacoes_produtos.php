<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cotacoes_produtos extends Admin_controller
{
    private $route, $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('admin/relatorios/cotacoes_produtos');
        $this->views = "admin/relatorios/cotacoes_produtos";

        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_compradores', 'compradores');
    }

    /**
     * Exibe a view admin/cotacoes_automaticas/main.php
     *
     * @return  view
     */
    public function index()
    {
        $page_title = 'Relatório de Cotações por Produto';

        $data['dataTable'] = "{$this->route}/datatables/";
        $data['url_detalhes'] = "{$this->route}/detalhes/";
        $data['url_exportar'] = "{$this->route}/exportar/";

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'styles' => [THIRD_PARTY . 'theme/plugins/flatpickr/flatpickr.min.css']
        ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
        ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'theme/plugins/flatpickr/flatpickr.min.js',
                'https://npmcdn.com/flatpickr/dist/l10n/pt.js'
            ]
        ]);

        $data['fornecedores'] = $this->fornecedor->find("id, nome_fantasia", null, false, 'nome_fantasia ASC');
        $data['select2'] = "{$this->route}/select2";

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Exibe a view admin/cotacoes_automaticas/detail.php
     *
     * @param string  codigo da cotacao
     * @param int  id do fornecedor
     * @return  view
     */
    public function detalhes($id_fornecedor, $cd_cotacao)
    {
        $data = [];

        //Obtem a cotação
        $this->db->where('cd_cotacao', $cd_cotacao);
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('nivel', 2);
        $data['cotacao'] = $this->db->get('vw_cotacoes')->row_array();

        //Obtem o cliente(comprador)
        $data['comprador'] = $this->compradores->find('*', ['cnpj' => $data['cotacao']['cnpj_comprador']], true);

        // Obtem Valor total de todos os produtos
        $valor_total = $this->db
            ->select("SUM( (preco_marca * qtd_solicitada) ) AS valor_total")
            ->where('id_fornecedor', $id_fornecedor)
            ->where('cd_cotacao', $cd_cotacao)
            ->where('nivel', 2)
            ->get('cotacoes_produtos')
            ->row_array();

        $data['valor_total_produtos'] = $valor_total['valor_total'];

        // Obtem a qnt de produtos de cada cotação
        $this->db->where('cd_cotacao', $cd_cotacao);
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('nivel', 2);
        $data['total_itens'] = $this->db->count_all_results('cotacoes_produtos');

        $page_title = "Produtos da cotação {$cd_cotacao}";

        $data['dataTable'] = "{$this->route}/datatables_detalhes/{$cd_cotacao}/{$id_fornecedor}";

        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnBack',
                    'url' => $this->route,
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar_detalhes/{$cd_cotacao}/{$id_fornecedor}",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel',
                    'label' => 'Exportar Excel'
                ],
            ]
        ]);
        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/detail", $data);
    }

    /**
     * Obtem dados para os datatables de Cotação manuais
     *
     * @param = int ID do fornecedor
     * @return  json
     */
    public function datatables()
    {
        $post = $this->input->post();
        if (isset($post['produtos'])){
            $produtos = implode(",", $post['produtos']);
        }

        if ($post['id_fornecedor'] == 'oncoprod') {

            $where = "id_fornecedor in (" . ONCOPROD . ")";
        } elseif ($post['id_fornecedor'] == 'oncoexo') {

            $where = "id_fornecedor in (" . ONCOEXO . ")";
        } else {

            $where = "id_fornecedor = {$post['id_fornecedor']}";
        }

        $this->db->distinct();
        $this->db->select("cp.cd_cotacao,
                cp.data_cotacao,
                c.cnpj,
                c.nome_fantasia,
                c.razao_social,
                c.estado as uf_comprador,
                c.telefone,
                c.celular,
                c.email,
                (select sum(cpp.qtd_solicitada * cpp.preco_marca) from cotacoes_produtos cpp where cpp.cd_cotacao = cp.cd_cotacao and cpp.id_fornecedor = cp.id_fornecedor) as total
                ");

        $this->db->from('cotacoes_produtos cp');
        $this->db->join("compradores c", "c.id = cp.id_cliente");

        $this->db->where("cp.id_pfv in ({$produtos})");
      #  $this->db->where("cp.id_fornecedor", $post['id_fornecedor']);
        $this->db->where($where);

        if (isset($post['data_ini']) && isset($post['data_fim']))
        {
            $this->db->where("date(cp.data_cotacao) between '{$post['data_ini']}' AND '{$post['data_fim']}'");
        }

        $data = $this->db->get()->result_array();

        foreach ($data as $k => $d)
        {
            $data[$k]['total'] = number_format($d['total'], 4, ',', '.');
            $data[$k]['data_cotacao'] = date("d/m/Y", strtotime($d['data_cotacao']));
        }


        $result = [
            'data' => $data,
            'draw' => 1,
            'recordsFiltered' => count($data),
            'recordsTotal' => count($data)
        ];


        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    /**
     * Obtem dados dos produtos da cotação
     *
     * @param = String codigo da cotação
     * @param = int ID do fornecedor
     * @return  json
     */
    public function datatables_detalhes($cd_cotacao, $id_fornecedor)
    {

        $data = $this->datatable->exec(
            $this->input->post(),
            'cotacoes_produtos cp',
            [
                ['db' => 'cp.id', 'dt' => 'id'],
                ['db' => 'cp.produto', 'dt' => 'produto'],
                ['db' => 'cp.preco_marca', 'dt' => 'preco_marca'],
                ['db' => 'pc.marca', 'dt' => 'marca'],
                ['db' => 'cp.qtd_solicitada', 'dt' => 'qtd_solicitada'],
                ['db' => 'cp.submetido', 'dt' => 'submetido'],
            ],
            [
                ['produtos_catalogo pc', 'pc.codigo = cp.id_pfv AND pc.id_fornecedor = cp.id_fornecedor']
            ],
            "cp.cd_cotacao = '{$cd_cotacao}' AND cp.id_fornecedor = {$id_fornecedor} AND cp.nivel = 2 and cp.submetido = 1"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function select2()
    {
        $post = $this->input->get();
        $where = "";

        if (isset($post['id_fornecedor'])) {
            $where = "id_fornecedor = {$post['id_fornecedor']}";
        }

        $result = $this->select2->exec($post,
            "produtos_catalogo",
            [
                ['db' => 'codigo', 'dt' => 'id'],
                ['db' => 'nome_comercial', 'dt' => 'text'],
            ],
            null,
            $where
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($result));

    }

    /**
     * Gera arquivo excel do datatable
     *
     * @return  downlaod file
     */



}
