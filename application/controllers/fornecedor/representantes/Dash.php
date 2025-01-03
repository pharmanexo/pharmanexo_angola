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

        $this->route = base_url('/fornecedor/representantes/dash/');
        $this->views = 'fornecedor/representantes/dash/';

        $this->DB_COTACAO = $this->load->database('sintese', TRUE);

        $this->load->model('m_compradores', 'cliente');
        $this->load->model('m_cotacoes_produtos', 'cotacoes_produtos');
        $this->load->model('m_fornecedor', 'fornecedor');

        $this->load->model('m_pedido_rep_dash', 'pedido');
        $this->load->model('m_produto', 'produto');
        $this->load->model('m_bi', 'BI');
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
                "'https://cdn.jsdelivr.net/npm/apexcharts'",

            ]
        ]);


        # Badges
        $data['url_badge'] = "{$this->route}/badgeUpdate";
        $data['badgeTotalCotacoes'] = $this->pedido->totalPedidosAbertos();
        $data['badgeTotalCotacoesMonetario'] = $this->pedido->totalPedidosEnviados();
        $data['badgeTotalConvertido'] = $this->pedido->totalPedidosFaturados();
        $data['badgeTotalParcial'] = $this->pedido->totalPedidosParciais();
        $data['badge'] = $this->pedido->totalPedidosCancelados();


        $data['url_grafico'] = "{$this->route}/charts";

        $this->load->view("{$this->views}/main", $data);
    }

    public function charts()
    {

        $data['chartMeta'] = $this->createChartMeta();
        $data['chartMetaRep'] = $this->createChartRep();

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function createChartMeta()
    {
        $this->db->select('sum(meta) as meta');
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);
        $rep = $this->db->get('representantes_fornecedores')->row_array();

        $meta = floatval($rep['meta']);

        $total_pedidos_faturados = $this->pedido->totalPedidosFaturados();


        $valor = (isset($total_pedidos_faturados)) ? $total_pedidos_faturados : 0;

        $data['valor'] = ($valor == 0) ? 0 : ($valor * 100) / $meta;
        $data['meta'] = number_format($meta, 2, ',', '.');

        return $data;

    }

    public function createChartRep()
    {
        $this->db->select('id_representante, meta, r.nome');
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);
        $this->db->join('representantes r', 'r.id = representantes_fornecedores.id_representante');
        $rep = $this->db->get('representantes_fornecedores')->result_array();

        $valores = [];
        $labels = [];
        $total = 0;

        foreach ($rep as $k => $item) {

            $meta = floatval($item['meta']);

            $total_pedidos_faturados = $this->pedido->totalPedidosFaturados($item['id_representante']);

            $valor = (isset($total_pedidos_faturados)) ? $total_pedidos_faturados : 0;

            $total = $total + $total_pedidos_faturados;

            $valores[] = ($valor == 0) ? 0 : ($valor * 100) / $meta;
            $labels[] = $item['nome'];
        }

        $data = [
            'valor' => (!empty($valores)) ? $valores : [],
            'labels' => (!empty($labels)) ? $labels : [],
            'total' => number_format($total, 2, ',', '.')
        ];


        return $data;

    }
}