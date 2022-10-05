<?php

class Cotacoes_sumarizado extends Admin_controller
{


    private $route, $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('admin/relatorios/cotacoes_sumarizado');
        $this->views = "admin/relatorios/cotacoes_sumarizado";

        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_compradores', 'compradores');
    }

    public function index()
    {
        $this->filtrar();
    }


    public function filtrar()
    {
        $post = $this->input->post();

        $page_title = 'Relatório Sumarizado de cotações';

        $data['form_action'] = "{$this->route}/filtrar/";

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'styles' => [THIRD_PARTY . 'theme/plugins/flatpickr/flatpickr.min.css']
        ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
        ]);
        $data['scripts'] = $this->template->scripts();

        $data['dados'] = $this->get_data($post);

        if (isset($post['dataini']) && $post['datafim']) {

            $data['btnExport'] = "<a class='btn btn-primary' href='{$this->route}/exportar?dataini={$post['dataini']}&datafim={$post['datafim']}'><i class='fa fa-file-excel'></i></a>";

        }

        $data['post'] = $post;

        $this->load->view("{$this->views}/main", $data);
    }

    private function get_data($post = [])
    {

        $where = "YEAR(data_criacao) = YEAR(now())";
        $where2 = "YEAR(Dt_Gravacao) = YEAR(now())";

        if (isset($post['dataini']) && isset($post['datafim'])) {
            $where = "data_criacao between '{$post['dataini']}' and '{$post['datafim']}'";
            $where2 = "Dt_Gravacao between '{$post['dataini']}' and '{$post['datafim']}'";
        }

        $query = "
            select count(distinct cd_cotacao)                            as COTACOES,
                   format(sum(qtd_solicitada * preco_marca), 4, 'de_DE') AS TOTAL_COTADO,
                   DATE_FORMAT(data_criacao, '%m/%Y')                    AS MES_ANO
            from cotacoes_produtos
            where {$where} and cd_cotacao not in ('COT10711-532', 'COT6893-2455', 'COT10012-1673')
            group by DATE_FORMAT(data_criacao, '%m/%Y') order by data_criacao ASC;
        ";

        $data = [];

        $ofertas = $this->db->query($query)->result_array();

        $pedidos = $this->db->query("select FORMAT(sum(ocp.Qt_Produto * ocp.Vl_Preco_Produto), 4, 'de_DE') AS TOTAL_CONVERTIDO, DATE_FORMAT(Dt_Gravacao, '%m/%Y') as MES_ANO
                                        from ocs_sintese_produtos ocp
                                                 join ocs_sintese os on os.id = ocp.id_ordem_compra
                                        where {$where2}
                                        group by DATE_FORMAT(Dt_Gravacao, '%m/%Y')")->result_array();


        $detalhes = $this->db->query("select count(distinct cd_cotacao)                            as COTACOES,
                                           format(sum(qtd_solicitada * preco_marca), 4, 'de_DE') AS TOTAL_COTADO,
                                           DATE_FORMAT(data_criacao, '%m/%Y')                    AS MES_ANO,
                                           integrador
                                    from cotacoes_produtos
                                    where $where
                                      and cd_cotacao not in ('COT10711-532', 'COT6893-2455', 'COT10012-1673')
                                    group by DATE_FORMAT(data_criacao, '%m/%Y'), integrador")->result_array();


        foreach ($ofertas as $oferta) {
            $data[$oferta['MES_ANO']] = $oferta;
        }

        foreach ($pedidos as $pedido) {
            $data[$pedido['MES_ANO']]['TOTAL_CONVERTIDO'] = $pedido['TOTAL_CONVERTIDO'];
        }

        foreach ($detalhes as $detalhes) {
            $data[$detalhes['MES_ANO']]['DETALHADO'][] = $detalhes;
        }

        return $data;

    }

    public function exportar($id_fornecedor = null)
    {
        $post = $this->input->get();


        $data = $this->get_data($post);
        $export = [];

        if (count($data) < 1) {
            $data[] = [
                'mes/ano' => 'mes/ano',
                'cotacoes' => 'cotacoes',
                'total' => 'total',
                'convertido' => 'convertido',
            ];
        } else {
            foreach ($data as $kk => $row) {
                unset($row['DETALHADO']);
                $export[] = [
                    'MES/ANO' => $row['MES_ANO'],
                    'COTAÇÕES RESPONDIDAS' => $row['COTACOES'],
                    'TOTAL RESPONDIDO' => $row['TOTAL_COTADO'],
                    'TOTAL CONVERTIDO' => $row['TOTAL_CONVERTIDO'],
                ];
            }
        }

        $dados_page = ['dados' => $export, 'titulo' => 'Cotacoes'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ($exportar['status'] == false) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

       return redirect($this->route . "/filtrar");
    }

}