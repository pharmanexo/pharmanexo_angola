<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dash extends MY_Controller
{
    private $route;
    private $views;
    private $DB_COTACAO;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('/fornecedor/dash/');
        $this->views = 'fornecedor/dash/';

        $this->DB_COTACAO = $this->load->database('sintese', TRUE);

        $this->load->model('m_compradores', 'cliente');
        $this->load->model('m_cotacoes_produtos', 'cotacoes_produtos');
        $this->load->model('m_fornecedor', 'fornecedor');
    }

    public function index()
    {
        $page_title = "";

        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading(['page_title' => $page_title]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                "https://www.gstatic.com/charts/loader.js",
                "https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.js",
                THIRD_PARTY . '/plugins/jquery.counterup.js'
            ]
        ]);

        # Badges
        $data['url_badge'] = "{$this->route}/badgeUpdate";
        $data['badgeTotalCotacoes'] = $this->cotacoes_produtos->getAmountCot($this->session->id_fornecedor, 'current');
        $data['badgeTotalCotacoesMonetario'] = $this->cotacoes_produtos->getPriceCot($this->session->id_fornecedor, 'current');
        $data['badgeTotalConvertido'] = $this->cotacoes_produtos->getOc($this->session->id_fornecedor, 'current');
        $data['badge'] = $this->cotacoes_produtos->getAcionamentosMix('current');


        # Graficos 
        $data['urlGraficoTotalCotacoes'] = "{$this->route}graficoTotalCotacoes";
        $data['urlGraficoCotOc'] = "{$this->route}graficoCotacaoOc";
        $data['urlGraficoCotacoes'] = "{$this->route}graficoCotacoes";

        # Listas
        $data['urlVolumeCompraCliente'] = "{$this->route}listaVolumeCompraCliente";
        $data['urlTotalCotadoCliente'] = "{$this->route}listaTotalCotadoCliente";
        $data['urlMaisCotado'] = "{$this->route}listaMaisCotado";
        $data['urlPrincipaisClientes'] = "{$this->route}listaPrincipaisClientes";


        $this->load->view("{$this->views}/main", $data);
    }

    public function badgeUpdate($periodo)
    {

        $badgeTotalCotacoesMonetario = $this->cotacoes_produtos->getPriceCot($this->session->id_fornecedor, $periodo);
        $badgeTotalConvertido = $this->cotacoes_produtos->getOc($this->session->id_fornecedor, $periodo);

        $data['badgeTotalCotacoes'] = $this->cotacoes_produtos->getAmountCot($this->session->id_fornecedor, $periodo);
        $data['badgeTotalCotacoesMonetario'] = number_format($badgeTotalCotacoesMonetario, 4, ',', '.');
        $data['badgeTotalConvertido'] = number_format($badgeTotalConvertido, 4, ',', '.');
        $data['badge'] = $this->cotacoes_produtos->getAmountOc($this->session->id_fornecedor, $periodo);

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function graficoTotalCotacoes()
    {

        $this->db->select("YEAR(data_cotacao) as ano");
        $this->db->where("id_fornecedor", $this->session->id_fornecedor);
        $this->db->group_by("YEAR(data_cotacao)");
        $anos = $this->db->get('cotacoes_produtos')->result_array();

        $data = [];

        # Cabeçalho que ficará os anos
        $data[] = ['Year'];
        $data[] = ['Jan'];
        $data[] = ['Fev'];
        $data[] = ['Mar'];
        $data[] = ['Abr'];
        $data[] = ['Mai'];
        $data[] = ['Jun'];
        $data[] = ['Jul'];
        $data[] = ['Ago'];
        $data[] = ['Set'];
        $data[] = ['Out'];
        $data[] = ['Nov'];
        $data[] = ['Dez'];

        foreach (array_column($anos, 'ano') as $ano) {

            array_push($data[0], $ano);

            $v = $this->cotacoes_produtos->totalCotacoesPorMes($this->session->id_fornecedor, $ano);

            array_push($data[1], intval($v['Jan']));
            array_push($data[2], intval($v['Fev']));
            array_push($data[3], intval($v['Mar']));
            array_push($data[4], intval($v['Abr']));
            array_push($data[5], intval($v['Mai']));
            array_push($data[6], intval($v['Jun']));
            array_push($data[7], intval($v['Jul']));
            array_push($data[8], intval($v['Ago']));
            array_push($data[9], intval($v['Set']));
            array_push($data[10], intval($v['Out']));
            array_push($data[11], intval($v['Nov']));
            array_push($data[12], intval($v['Dez']));
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function graficoCotacaoOc($periodo)
    {
        $qntCot = $this->cotacoes_produtos->getAmountCot($this->session->id_fornecedor, $periodo);
        $qntOc = $this->cotacoes_produtos->getAmountOc($this->session->id_fornecedor, $periodo);

        $data = [
            ['cotacao', 'oc'],
            ['Cotações', $qntCot],
            ['Ordens de compra', $qntOc],
        ];

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function graficoCotacoes($periodo)
    {

        $totalManual = $this->cotacoes_produtos->getAmountManual($this->session->id_fornecedor, $periodo);
        $totalAut = $this->cotacoes_produtos->getAmountAutomatica($this->session->id_fornecedor, $periodo);
        $totalMix = $this->cotacoes_produtos->getAmountMix($this->session->id_fornecedor, $periodo);

        $data = [
            ['Element', 'Cotações', ["role" => 'style']],
            ['Manual', $totalManual, '#FF0000'],
            ['Automática', $totalAut, '#FFFF00'],
            ['Mix', $totalMix, '#0000FF'],
        ];

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function listaVolumeCompraCliente($periodo)
    {
        switch ($periodo) {
            case 'current':
                $mes = date('m', time());
                $ano = date('Y', time());

                $where = "MONTH(cots.data_criacao) = '{$mes}' AND YEAR(cots.data_criacao) = '{$ano}'";
                break;
            case '30days':
                $inicio = date('Y-m-d', strtotime('-30days'));
                $fim = date('Y-m-d', time());

                $where = "DATE(cots.data_criacao) BETWEEN '{$inicio}' AND '{$fim}'";

                break;
            case '6months':
                $inicio = date('Y-m-d', strtotime('-6months'));
                $fim = date('Y-m-d', time());

                $where = "DATE(cots.data_criacao) BETWEEN '{$inicio}' AND '{$fim}'";

                break;
        }


        $query = "
                             SELECT
                    MASK(x.cnpj_comprador, '##.###.###/####-##') as cnpj_comprador,
                    SUM(x.qtd_solicitada) qtd_solicitada,
                    SUM(x.preco_total)    preco_total
                FROM (select replace(replace(replace( cots.cnpj_comprador, '/', ''), '-', ''), '.', '') as cnpj_comprador,
                             cots.id_pfv codigo,
                             cots.qtd_solicitada,
                             cots.preco_marca,
                             cots.qtd_solicitada * cots.preco_marca preco_total
                      FROM pharmanexo.cotacoes_produtos cots
                      WHERE $where
                    AND cots.data_criacao is not null
                      GROUP BY cots.cnpj_comprador,
                               cots.qtd_solicitada,
                               cots.preco_marca,
                               cots.id_pfv) x
                GROUP BY x.cnpj_comprador
                order by preco_total DESC";


        $lista = $this->db->query($query)->result_array();

        $v = [];

        foreach ($lista as $kk => $row) {

            $comprador = $this->cliente->find("CONCAT(cnpj, ' - ', nome_fantasia) AS comprador", "cnpj = '{$row['cnpj_comprador']}'", true);

            $v[] = [
                'comprador' => (isset($comprador) && !empty($comprador)) ? "{$comprador['comprador']}" : $row['cnpj_comprador'],
                'preco_total' => $row['preco_total']
            ];
        }

        $output = ['type' => (count($v) > 0) ? 'success' : 'warning', 'data' => $v];

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    public function listaTotalCotadoCliente($periodo)
    {
        $this->DB_COTACAO->select("INSERT(INSERT(INSERT(INSERT(cot.cd_comprador, 13, 0, '-'), 9, 0, '/'), 6, 0, '.'), 3, 0,'.') cnpj_comprador,
            COUNT(DISTINCT cot.cd_cotacao) qtd_cotacao");
        $this->DB_COTACAO->from('cotacoes cot');

        switch ($periodo) {
            case 'current':
                $mes = date('m', time());
                $ano = date('Y', time());

                $this->DB_COTACAO->where("MONTH(cot.dt_inicio_cotacao) = '{$mes}' and YEAR(cot.dt_inicio_cotacao) = '{$ano}'");
                break;
            case '30days':
                $inicio = date('Y-m-d', strtotime('-30days'));
                $fim = date('Y-m-d', time());

                $this->DB_COTACAO->where("DATE(cot.dt_inicio_cotacao) between '{$inicio}' and '{$fim}'");

                break;
            case '6months':
                $inicio = date('Y-m-d', strtotime('-6months'));
                $fim = date('Y-m-d', time());

                $this->DB_COTACAO->where("DATE(cot.dt_inicio_cotacao) between '{$inicio}' and '{$fim}'");

                break;
        }

        $this->DB_COTACAO->where("cot.dt_inicio_cotacao is not null");
        $this->DB_COTACAO->where("cot.id_fornecedor", $this->session->id_fornecedor);
        $this->DB_COTACAO->group_by("cot.cd_comprador");

        $lista = $this->DB_COTACAO->get()->result_array();

        $v = [];

        foreach ($lista as $kk => $row) {

            $comprador = $this->cliente->find("CONCAT(cnpj, ' - ', razao_social) AS comprador", "cnpj = '{$row['cnpj_comprador']}'", true);

            $v[] = [
                'comprador' => (isset($comprador) && !empty($comprador)) ? "{$comprador['comprador']}" : $row['cnpj_comprador'],
                'qtd_cotacao' => $row['qtd_cotacao']
            ];
        }

        $output = ['type' => (count($v) > 0) ? 'success' : 'warning', 'data' => $v];

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    public function listaMaisCotado()
    {

        $post = $this->input->post();

        $limit = (isset($post['length'])) ? $post['length'] : 10;

        $this->db->select("id_pfv, produto, COUNT(id_pfv) AS total");
        $this->db->where("id_fornecedor", $this->session->id_fornecedor);
        $this->db->group_by("id_pfv, produto");
        $this->db->order_by("total DESC");
        $this->db->limit($limit);

        $lista = $this->db->get('cotacoes_produtos')->result_array();
        
        $output = ['type' => (count($lista) > 0) ? 'success' : 'warning', 'data' => $lista];

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    public function listaPrincipaisClientes()
    {

        $post = $this->input->post();

        $limit = (isset($post['length'])) ? $post['length'] : 10;

        $this->db->select("cnpj_comprador, produto, COUNT(cnpj_comprador) AS total");
        $this->db->where("id_fornecedor", $this->session->id_fornecedor);
        $this->db->group_by("cnpj_comprador, produto");
        $this->db->order_by("total DESC");
        $this->db->limit($limit);

        $lista = $this->db->get('cotacoes_produtos')->result_array();

        $v = [];

        foreach ($lista as $kk => $row) {

            $comprador = $this->cliente->find("CONCAT(cnpj, ' - ', razao_social) AS comprador", "cnpj = '{$row['cnpj_comprador']}'", true);

            $v[] = [
                'comprador' => (isset($comprador) && !empty($comprador)) ? "{$comprador['comprador']}" : $row['cnpj_comprador'],
                'total' => $row['total']
            ];
        }

        $output = ['type' => (count($v) > 0) ? 'success' : 'warning', 'data' => $v];

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }
}