<?php
defined('BASEPATH') or exit('No direct script access allowed');
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
class Dashboard extends MY_Controller
{
    private $route;
    private $routelogin;
    private $views;
    protected $oncoexo;
    protected $oncoprod;
    protected $DB_COTACAO;
    protected $MIX;
    protected $bio;

    public function __construct()
    {
        parent::__construct();


        $this->route = base_url('dashboard/');
        $this->routelogin = base_url('login/');

        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_cotacoes_produtos', 'cotacoes_produtos');
        $this->load->model('m_cotacoes', 'COTACOES');
        $this->load->model('m_estados', 'estado');
        $this->load->model('m_compradores', 'comprador');
        $this->load->model('m_grafico', 'grafico');
        $this->load->model('m_fotos', 'fotos');
        $this->load->model('m_bi', 'BI');
        $this->load->model('m_notificacoes', 'notificacao');
        $this->load->model('m_ordemCompra', 'ordem_compra');
        $this->load->model('m_usuarios', 'usuario');

        $this->oncoexo = explode(',', ONCOEXO);
        $this->oncoprod = explode(',', ONCOPROD);

        $this->DB_COTACAO = $this->load->database('sintese', TRUE);
        $this->MIX = $this->load->database('mix', TRUE);
        $this->bio = $this->load->database('bionexo', TRUE);
    }

    /**
     * Função que cria o cliente
     *
     * @return redirect
     */
    public function index()
    {
        $tipo = $this->session->userdata('tipo_usuario');
        $grupo = ($this->session->has_userdata('grupo')) ? $this->session->grupo : '';
        $data['scripts'] = $this->template->scripts();
        $data['frm_actionverifica'] = "{$this->routelogin}verifica_email";



        if ($this->session->userdata('primeiro') == '1') {
            $this->primeiro_login();
        } else {



            if ($this->session->id_usuario == '15') {

                $this->dashboard_vendas();
            } else {

                if (isset($this->session->administrador) && $this->session->administrador == 1) {

                    $this->dashboard_admin();
                } else if ($tipo == 1) {

                    switch ($grupo) {

                        case '1':
                            $this->dashboard_fornecedor();
                            break;
                        case  '2':
                            $this->dashboard_vendas();
                            break;
                        case  '4':
                            redirect(base_url('fornecedor/b2b/ofertas'));
                            break;
                        default:
                            $this->dashboard_vendas();
                            break;
                    }
                } else if ($tipo == 2) {
                    $data = [
                        'header' => $this->tmp->header(),
                        'scripts' => $this->tmp->scripts(),
                        'navbar' => $this->tmp->navbar()
                    ];
                    $view = "marketplace/home";

                    $this->load->view($view, $data);
                }
            }
        }
    }

    /**
     *  Função que verifica se é o primeiro login para atualização
     *
     * @param - int id usuario
     * @return json
     */
    public function primeiro_login()
    {
        $data['frm_actionprimeiro'] = "{$this->routelogin}primeiroatt";
        $data['header'] = $this->template->header(['title' => 'Atualização de cadastro']);
        $data['scripts'] = $this->template->scripts();
        $data['fotos'] = $this->db->get('fotos')->result_array();
        $this->load->view('primeiro', $data);
    }

    /**
     * Exibe o dashboard de Administração
     *
     * @return view
     */
    private function dashboard_admin()
    {
        $page_title = "Dashboard - Administrador";

        $data['header'] = $this->template->header(['title' => $page_title, 'styles' => []]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading(['page_title' => $page_title]);
        $data['scripts'] = $this->template->scripts(['scripts' => ['https://cdn.jsdelivr.net/npm/apexcharts']]);

        # URLs
        $data['urlCharts'] = "{$this->route}/getChartsAdmin";

        $data['urlChartCotacoes'] = "{$this->route}/createChartCotacoes";
        $data['urlChartTipoCotacao'] = "{$this->route}/createChartTipoCotacao";
        $data['urlChartValorCotado'] = "{$this->route}/createChartValorCotado";
        $novos = $this->comprador->getNovosProdutos();


        if ($this->session->nivel == 3) {
            $mes = $this->usuario->getMetaUser(null, false, true);

            foreach ($mes as $k => $m) {
                $history = [];
                $month = $this->usuario->getMetaUser($m['id_usuario'], false, true, true);
                $hospitais = $this->usuario->getHospitaisAbertos($m['id_usuario'], 2);
                $total = $this->usuario->getTotalHospitais($m['id_usuario'], 2);


                foreach ($month as $m) {
                    $m['mes_nome'] = nameMonth($m['mes']);
                    $history[$m['ano']][$m['mes']] = $m;
                }

                $dia = $this->usuario->getMetaUser($m['id_usuario'], true, false);
                $mes[$k]['dia'] = (empty($dia['total'])) ? 0 : $dia['total'];
                $mes[$k]['historico'] = $history;
                $mes[$k]['hospitais'] = $hospitais;
                $mes[$k]['total_hosp'] = $total;
            }

            $data['meta'] = $mes;
        }


        if ($this->session->nivel != 3) {

            $id_usuario = $this->session->id_usuario;
            $month = $this->usuario->getMetaUser($id_usuario, false, true, true);
            $data['history'] = [];

            foreach ($month as $m) {
                $m['mes_nome'] = nameMonth($m['mes']);
                $data['history'][$m['ano']][$m['mes']] = $m;
            }

            $meta = $this->usuario->getMetaUser($id_usuario, true);
            $data['meta'] = $this->usuario->getMetaUser($id_usuario);

            if (isset($meta['total'])) {
                $meta['total'] = doubleval($meta['total']);
            } else {
                $meta['total'] = 0;
            }

            //   $hospitais_a = $this->usuario->getHospitaisAbertos($id_usuario, 2);
            //  $hospitais_f = $this->usuario->getHospitaisFinalizados($id_usuario, 2);

            $hospitais_a = [];
            $hospitais_f = [];

            if (!empty($hospitais_a)) {
                foreach ($hospitais_a as $l => $hosp) {
                    $dados = $this->comprador->getDadosCatalogo($hosp['id']);
                    $hospitais_a[$l] = array_merge($hosp, $dados);
                }
            }

            if (!empty($hospitais_f)) {
                foreach ($hospitais_f as $h => $hosp) {
                    $dados = $this->comprador->getDadosCatalogo($hosp['id']);
                    $hospitais_f[$h]['novos'] = ($dados['sem'] - $dados['ocultos']);
                }
            }

            $data['percent'] = $this->porcentagem_nx($meta['total'], META_DEPARA);
            $data['n'] = $meta['total'];
            $data['hospitais_a'] = $hospitais_a;
            $data['hospitais_f'] = $hospitais_f;


            $this->load->view("admin/dashboard/main_comercial", $data);
        } else {
            $this->load->view("admin/dashboard/main", $data);
        }
    }

    /**
     * Exibe o dashboard do fornecedor
     *
     * @return view
     */
    private function dashboard_fornecedor()
    {

        $page_title = "Dashboard Fornecedor";

        $data['url_info_mapa'] = "{$this->route}/getInfoMapa/";
        $data['urlCharts'] = "{$this->route}/getChartsFornecedor";
        $data['urlLine'] = "{$this->route}/createChartTotalCotacoes/{$this->session->id_fornecedor}/";

        $data['urlProdutosVencer'] = base_url('fornecedor/estoque/produtos_vencer');

        $data['header'] = $this->template->header(['title' => $page_title, 'styles' => [THIRD_PARTY . 'plugins/jquery.vmap/jqvmap.min.css']]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading(['page_title' => $page_title]);
        $data['scripts'] = $this->template->scripts();

        unset($_SESSION['filtros']);

        $data['dt_cotacaoes'] = $this->COTACOES->ultimoRegistroCotacao($this->session->id_fornecedor);

        $this->DB_COTACAO->select("YEAR(dt_inicio_cotacao) as ano");
        $this->DB_COTACAO->where("id_fornecedor", $this->session->id_fornecedor);
        $this->DB_COTACAO->where("YEAR(dt_inicio_cotacao) IS NOT NULL");
        $this->DB_COTACAO->where("YEAR(dt_inicio_cotacao) > 2000");
        $this->DB_COTACAO->group_by("YEAR(dt_inicio_cotacao)");
        $this->DB_COTACAO->order_by("ano ASC");
        $data['filtroAno'] = $this->DB_COTACAO->get('cotacoes')->result_array();

        $data['indicadores'] = $this->indicadores($this->session->id_fornecedor);

        $data['updateCotacoes'] = $this->db->query("
                                                select (select max(data_criacao) as SINTESE from cotacoes_sintese.cotacoes where id_fornecedor = {$this->session->id_fornecedor}) as SINTESE,
                                                (select max(dt_criacao) as BIONEXO from cotacoes_bionexo.cotacoes where id_fornecedor = {$this->session->id_fornecedor}) AS BIONEXO,
                                                (select max(dt_criacao) as APOIO from cotacoes_apoio.cotacoes where id_fornecedor = {$this->session->id_fornecedor}) AS APOIO
                                                ")->row_array();

        $this->load->view("fornecedor/dashboard/main", $data);
    }

    /**
     * Exibe o dashboard de vendas do fornecedor
     *
     * @return view
     */
    private function dashboard_vendas()
    {

        $page_title = 'Dashboard Vendas';

        $data['to_datatable'] = "{$this->route}/datatables_cotacoes";

        $rede = $this->db
            ->where('id_usuario', $this->session->id_usuario)
            ->where('id_fornecedor', $this->session->id_fornecedor)
            ->get('usuarios_rede_atendimento')
            ->result_array();


        $rede_estados = [];
        $rede_clientes = [];

        foreach ($rede as $item) {
            if ($item['id_estado'] > 0) {
                $rede_estados[] = $item;
            } else {
                $rede_clientes[] = $item;
            }
        }

        # Selects
        $data['estados'] = $this->estado->find("id, uf, CONCAT(uf, ' - ', descricao) AS estado", null, FALSE, 'estado ASC');
        $data['compradores'] = $this->comprador->find("id, CONCAT(cnpj, ' - ', razao_social) AS comprador", null, FALSE, 'comprador ASC');
        foreach ($rede_clientes as $rcli) {
            foreach ($data['compradores'] as $k => $comprador) {
                if ($comprador['id'] == $rcli['id_cliente']) {
                    $data['compradores'][$k]['selected'] = true;
                }
            }
        }


        foreach ($rede_estados as $rest) {
            foreach ($data['estados'] as $k => $estado) {
                if ($estado['id'] == $rest['id_estado']) {
                    $data['estados'][$k]['selected'] = true;
                }
            }
        }


        $cotacoes_sintese = $this->DB_COTACAO->select('cd_cotacao')
            ->where('id_fornecedor', $this->session->id_fornecedor)
            ->where("dt_fim_cotacao > now()")
            ->group_by('cd_cotacao')
            ->order_by('cd_cotacao ASC')
            ->get('cotacoes')
            ->result_array();

        $cotacoes_bionexo = $this->bio->select('cd_cotacao')
            ->where('id_fornecedor', $this->session->id_fornecedor)
            ->where("dt_fim_cotacao > now()")
            ->group_by('cd_cotacao')
            ->order_by('cd_cotacao ASC')
            ->get('cotacoes')
            ->result_array();


        $data['cotacoes'] = array_merge($cotacoes_sintese, $cotacoes_bionexo);

        if (in_array($this->session->id_fornecedor, explode(',', ONCOPROD))) {
            $data['url_cotacao'] = base_url("fornecedor/cotacoes_oncoprod/detalhes");
        } elseif (in_array($this->session->id_fornecedor, explode(',', ONCOEXO))) {
            $data['url_cotacao'] = base_url("fornecedor/cotacoes_oncoexo/detalhes");
        } else {
            $data['url_cotacao'] = base_url("fornecedor/cotacoes/detalhes");
        }

        $data['url_info'] = base_url("fornecedor/cotacoes/info_cotacao/");

        $data['url_ocultar'] = "{$this->route}/ocultarCotacao";
        $data['url_review'] = base_url("fornecedor/cotacoes/review/");

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'styles' => [
                THIRD_PARTY . 'plugins/jquery.vmap/jqvmap.min.css'
            ]
        ]);

        # Session
        $this->session->set_userdata(['perfil_comercial' => 1]);

        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading(['page_title' => $page_title]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/jquery.vmap/jquery.vmap.min.js',
                THIRD_PARTY . 'plugins/jquery.vmap/maps/jquery.vmap.brazil.js',
                THIRD_PARTY . 'theme/plugins/flot/jquery.flot.js',
                THIRD_PARTY . 'theme/plugins/flot/jquery.flot.pie.js',
                THIRD_PARTY . 'theme/plugins/flot/jquery.flot.tooltip.js'
            ]
        ]);


        if (in_array($this->session->id_fornecedor, $this->oncoprod)) {

            $this->DB_COTACAO->select('*');
            $this->DB_COTACAO->where("id_fornecedor in (" . ONCOPROD . ")");
            $this->DB_COTACAO->order_by('data_criacao DESC');
            $this->DB_COTACAO->limit(1);
            $data['dt_cotacaoes'] = $this->DB_COTACAO->get('cotacoes')->row_array()['data_criacao'];
        } else {

            $this->DB_COTACAO->select('*');
            $this->DB_COTACAO->where('id_fornecedor', $this->session->id_fornecedor);
            $this->DB_COTACAO->order_by('data_criacao DESC');
            $this->DB_COTACAO->limit(1);
            $data['dt_cotacaoes'] = $this->DB_COTACAO->get('cotacoes')->row_array()['data_criacao'];
        }


        $data['updateCotacoes'] = $this->db->query("
                                                select (select max(data_criacao) as SINTESE from cotacoes_sintese.cotacoes where id_fornecedor = {$this->session->id_fornecedor}) as SINTESE,
                                                (select max(dt_criacao) as BIONEXO from cotacoes_bionexo.cotacoes where id_fornecedor = {$this->session->id_fornecedor}) AS BIONEXO,
                                                (select max(dt_criacao) as APOIO from cotacoes_apoio.cotacoes where id_fornecedor = {$this->session->id_fornecedor}) AS APOIO
                                                ")->row_array();



        $this->load->view("fornecedor/dashboard/vendas", $data);
    }

    ######################  FUNÇÕES FORNECEDOR

    public function getChartsFornecedor()
    {
        $json = [];
        $file = "public/charts_{$this->session->id_fornecedor}.json";


        if (file_exists($file)) {
            $json = file_get_contents($file);

            // exclui arquivos com 1 dia de diferença
            $fileDate = date_create(date("Y-m-d H:i:s", filectime($file)));
            $date1 = date_create(date("Y-m-d H:i:s", time()));
            $interval = date_diff($fileDate, $date1);

            if ($interval->days > 0) {
                unlink($file);
            }
        } else {
            $post = $this->input->post();

            $integrador = (isset($post['integrador'])) ? $post['integrador'] : 'SINTESE';

            // $this->graficoCotacoes($this->session->id_fornecedor, $post['ano']);

            $data['chartLine'] = $this->createChartTotalCotacoes($this->session->id_fornecedor, $post['ano']);

            $data['chartColumn'] = $this->createChartProdutosVencer($this->session->id_fornecedor);

            $data['chartMap'] = $this->createMap($this->session->id_fornecedor);

            $json = json_encode($data);

            $f = fopen($file, 'w+');
            fwrite($f, $json);
            fclose($f);
        }

        $this->output->set_content_type('application/json')->set_output($json);
    }

    /**
     * Obtem os dados para os widgets do dashbaord de fornecedor
     *
     * @param int id_do fornecedor
     * @return array
     */
    public function indicadores($id_fornecedor)
    {

        $totalOfertado = $this->cotacoes_produtos->getAmountCot($id_fornecedor, 'current');
        $valorTotalOfertado = $this->cotacoes_produtos->getPriceCot($id_fornecedor, 'current');
        $totalOc = $this->ordem_compra->getTotalPriceOc($id_fornecedor, 'current');
        $totalCotacoesAberto = $this->COTACOES->getTotalOpenQuotes($id_fornecedor);

        $data = [
            'totalOfertado' => $totalOfertado,
            'totalCotacoesAberto' => $totalCotacoesAberto,
            'valorTotalOfertado' => number_format($valorTotalOfertado, 4, ',', '.'),
            'totalOc' => number_format($totalOc, 4, ',', '.'),
        ];

        return $data;
    }

    public function createMap($id_fornecedor)
    {

        if (!isset($id_fornecedor)) {

            $id_fornecedor = $this->session->id_fornecedor;
        }

        $query = "
            SELECT count(x.cd_cotacao) total, x.uf_cotacao uf FROM ( SELECT uf_cotacao, cd_cotacao
            FROM cotacoes_sintese.cotacoes a
            WHERE id_fornecedor  = {$id_fornecedor}
                AND dt_fim_cotacao > now()
                
            GROUP BY cd_cotacao, uf_cotacao) x
            GROUP BY x.uf_cotacao
            ORDER BY x.uf_cotacao ASC
            ";

        $response = $this->db->query($query)->result_array();

        $queryBionexo = "
            SELECT count(x.cd_cotacao) total, x.uf_cotacao uf FROM ( SELECT uf_cotacao, cd_cotacao
            FROM cotacoes_bionexo.cotacoes a
            WHERE id_fornecedor  = {$id_fornecedor}
                AND dt_fim_cotacao > now()
               
            GROUP BY cd_cotacao, uf_cotacao) x
            GROUP BY x.uf_cotacao
            ORDER BY x.uf_cotacao ASC
            ";

        $bionexo = $this->db->query($queryBionexo)->result_array();

        $queryApoio = "
            SELECT count(x.cd_cotacao) total, x.uf_cotacao uf FROM ( SELECT uf_cotacao, cd_cotacao
            FROM cotacoes_apoio.cotacoes a
            WHERE id_fornecedor  = {$id_fornecedor}
                AND dt_fim_cotacao > now()
               
            GROUP BY cd_cotacao, uf_cotacao) x
            GROUP BY x.uf_cotacao
            ORDER BY x.uf_cotacao ASC
            ";

        $apoio = $this->db->query($queryApoio)->result_array();

        $dataCot = [];
        $estados = $this->db->get('estados')->result_array();
        foreach ($estados as $estado) {
            $sigla = $estado['uf'];
            foreach ($response as $k => $item) {

                if ($item['uf'] == $sigla) {

                    $dataCot[$sigla]['sintese'] = $item['total'];
                }
            }

            foreach ($bionexo as $k => $item) {

                if ($item['uf'] == $sigla) {

                    $dataCot[$sigla]['bionexo'] = $item['total'];
                }
            }

            foreach ($apoio as $k => $item) {

                if ($item['uf'] == $sigla) {

                    $dataCot[$sigla]['apoio'] = $item['total'];
                }
            }
        }

        $data = [];
        foreach ($dataCot as $sigla => $row) {

            $data[] = [
                'code' => $sigla,
                'sintese' => isset($row['sintese']) ? $row['sintese'] : '',
                'bionexo' => isset($row['bionexo']) ? $row['bionexo'] : '',
                'apoio' => isset($row['apoio']) ? $row['apoio'] : ''
            ];
        }

        return $data;
    }

    public function createChartProdutosVencer($id_fornecedor)
    {

        $intervalo1 = $this->BI->valorTotalProdutosPorValidade($id_fornecedor, $this->session->id_estado, date('Y-m-d'), date('Y-m-d', strtotime('+3months')));
        $intervalo2 = $this->BI->valorTotalProdutosPorValidade($id_fornecedor, $this->session->id_estado, date('Y-m-d', strtotime('+3months')), date('Y-m-d', strtotime('+6months')));
        $intervalo3 = $this->BI->valorTotalProdutosPorValidade($id_fornecedor, $this->session->id_estado, date('Y-m-d', strtotime('+6months')), date('Y-m-d', strtotime('+9months')));
        $intervalo4 = $this->BI->valorTotalProdutosPorValidade($id_fornecedor, $this->session->id_estado, date('Y-m-d', strtotime('+9months')), date('Y-m-d', strtotime('+12months')));
        $intervalo5 = $this->BI->valorTotalProdutosPorValidade($id_fornecedor, $this->session->id_estado, date('Y-m-d', strtotime('+12months')), date('Y-m-d', strtotime('+18months')));


        $data['format'] = [
            number_format($intervalo1, 4, ',', '.'),
            number_format($intervalo2, 4, ',', '.'),
            number_format($intervalo3, 4, ',', '.'),
            number_format($intervalo4, 4, ',', '.'),
            number_format($intervalo5, 4, ',', '.'),
        ];

        $data['value'] = [['name' => 'Total', 'data' => [
            $intervalo1,
            $intervalo2,
            $intervalo3,
            $intervalo4,
            $intervalo5,
        ]]];

        return $data;
    }

    public function createChartTotalCotacoes($id_fornecedor, $ano, $return = null)
    {

        $resp = $this->grafico->getDadosCotacaoMensal($this->session->id_fornecedor, $ano, 'SINTESE');


        $totalCot = [];
        $cotEnv = [];
        $cotProd = [];

        foreach ($resp as $row) {

            $indice = intval($row['mes']);


            (isset($totalCot[$indice])) ? $totalCot[$indice] += 1 : $totalCot[$indice] = 1;


            if ($row['depara'] == "S") {

                (isset($cotProd[$indice])) ? $cotProd[$indice] += 1 : $cotProd[$indice] = 1;
            }

            if ($row['depara'] == "S" && $row['oferta'] == "S") {

                if ($row['nivel'] == 'S') {

                    (isset($cotEnv[$indice])) ? $cotEnv[$indice] += 2 : $cotEnv[$indice] = 2;
                } else {

                    (isset($cotEnv[$indice])) ? $cotEnv[$indice] += 1 : $cotEnv[$indice] = 1;
                }
            }
        }

        for ($i = 1; $i <= 12; $i++) {
            if (!isset($totalCot[$i])) $totalCot[$i] = 0;
        }
        for ($i = 1; $i <= 12; $i++) {
            if (!isset($cotEnv[$i])) $cotEnv[$i] = 0;
        }
        for ($i = 1; $i <= 12; $i++) {
            if (!isset($cotProd[$i])) $cotProd[$i] = 0;
        }

        $dataCurrente = date('m', time());
        $anoCurrente = date('Y', time());
        $key = intval($dataCurrente);

        if ($ano == $anoCurrente) {
            $cotEnv[$key] = $this->cotacoes_produtos->getAmountCot($id_fornecedor, 'current');
        }


        $data = [
            [
                'name' => 'TOTAL COT',
                'type' => 'column',
                'data' => [
                    $totalCot[1], $totalCot[2], $totalCot[3], $totalCot[4], $totalCot[5], $totalCot[6],
                    $totalCot[7], $totalCot[8], $totalCot[9], $totalCot[10], $totalCot[11], $totalCot[12]
                ]
            ],
            [
                'name' => 'COT COM PROD',
                'type' => 'line',
                'data' => [
                    $cotProd[1], $cotProd[2], $cotProd[3], $cotProd[4], $cotProd[5], $cotProd[6],
                    $cotProd[7], $cotProd[8], $cotProd[9], $cotProd[10], $cotProd[11], $cotProd[12]
                ]

            ],
            [
                'name' => 'COT ENVIADA',
                'type' => 'line',
                'data' => [
                    $cotEnv[1], $cotEnv[2], $cotEnv[3], $cotEnv[4], $cotEnv[5], $cotEnv[6],
                    $cotEnv[7], $cotEnv[8], $cotEnv[9], $cotEnv[10], $cotEnv[11], $cotEnv[12]
                ]
            ],
            [
                'name' => '%',
                'type' => 'column',
                'data' => [
                    ($totalCot[1] / $totalCot[2]) , $totalCot[2], $totalCot[3], $totalCot[4], $totalCot[5], $totalCot[6],
                    $totalCot[7], $totalCot[8], $totalCot[9], $totalCot[10], $totalCot[11], $totalCot[12]
                ]
            ]
        ];


        if (isset($return)) {

            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {

            return $data;
        }
    }

    public function graficoCotacoes($id_fornecedor, $ano, $return = null)
    {
        $resp = $this->grafico->getDadosCotacaoMensal($this->session->id_fornecedor, $ano, 'SINTESE');
        $totalCot = [];
        $cotEnv = [];
        $cotProd = [];

        foreach ($resp as $row) {

            $indice = intval($row['mes']);


            (isset($totalCot[$indice])) ? $totalCot[$indice] += 1 : $totalCot[$indice] = 1;


            if ($row['depara'] == "S") {

                (isset($cotProd[$indice])) ? $cotProd[$indice] += 1 : $cotProd[$indice] = 1;
            }

            if ($row['depara'] == "S" && $row['oferta'] == "S") {

                if ($row['nivel'] == 'S') {

                    (isset($cotEnv[$indice])) ? $cotEnv[$indice] += 2 : $cotEnv[$indice] = 2;
                } else {

                    (isset($cotEnv[$indice])) ? $cotEnv[$indice] += 1 : $cotEnv[$indice] = 1;
                }
            }
        }

        for ($i = 1; $i <= 12; $i++) {
            if (!isset($totalCot[$i])) $totalCot[$i] = 0;
        }
        for ($i = 1; $i <= 12; $i++) {
            if (!isset($cotEnv[$i])) $cotEnv[$i] = 0;
        }
        for ($i = 1; $i <= 12; $i++) {
            if (!isset($cotProd[$i])) $cotProd[$i] = 0;
        }

        $dataCurrente = date('m', time());
        $anoCurrente = date('Y', time());
        $key = intval($dataCurrente);

        if ($ano == $anoCurrente) {
            $cotEnv[$key] = $this->cotacoes_produtos->getAmountCot($id_fornecedor, 'current');
        }

        for ($i = 1; $i <= 12; $i++) {
            $grafico = $this->db->get_where('graficos', array(
                'ano' => $ano,
                'mes' => $i,
                'id_fornecedor' => $id_fornecedor
            ));
            if ($grafico) {
                $dataGrafico = array(
                    'total_cotacao' => $totalCot[$i],
                    'cotacao_produto' => $cotProd[$i],
                    'cotacao_enviada' => $cotEnv[$i],
                );
                $this->db->where(array(
                    'ano' => $ano,
                    'mes' => $i,
                    'id_fornecedor' => $id_fornecedor
                ));
                $velhoGrafico = $this->db->update('graficos', $dataGrafico);
            } else {
                $dataGrafico = array(
                    'ano' => $ano,
                    'mes' => $i,
                    'id_fornecedor' =>   $id_fornecedor,
                    'total_cotacao' =>   $totalCot[$i],
                    'cotacao_produto' => $cotProd[$i],
                    'cotacao_enviada' => $cotEnv[$i],
                );
                $novoGrafico = $this->db->insert('graficos', $dataGrafico);
            }
        }

        if (isset($return)) {

            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {

            return var_dump($grafico, $novoGrafico, $velhoGrafico);
            exit;
        }
    }

    public function getInfoMapa($id_fornecedor = null)
    {
        $data = [];

        if (!isset($id_fornecedor)) {

            $id_fornecedor = $this->session->id_fornecedor;
        }

        $query = "
            SELECT count(x.cd_cotacao) total, x.uf_cotacao uf FROM ( SELECT uf_cotacao, cd_cotacao
            FROM cotacoes_sintese.cotacoes a
            WHERE id_fornecedor  = {$id_fornecedor}
                AND dt_fim_cotacao > now()
                AND uf_cotacao in ('AL', 'BA', 'CE', 'ES', 'MG', 'MT', 'PA', 'PB', 'PE', 'PR', 'RJ', 'RN', 'RS', 'SE', 'SP')
            GROUP BY cd_cotacao, uf_cotacao) x
            GROUP BY x.uf_cotacao
            ";

        $response = $this->db->query($query)->result_array();

        if (!empty($response)) {

            foreach ($response as $k => $item) {

                $aux = [];
                $aux['code'] = $item['uf'];
                $aux['values']['item'] = $item['total'];

                array_push($data, $aux);
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    ######################  FUNÇÕES ADMIN

    public function getChartsAdmin()
    {

        $data['chartCotacoes'] = $this->createChartCotacoes('SINTESE', 'current');
        $data['chartTipoCotacao'] = $this->createChartTipoCotacao('SINTESE', 'current');
        $data['chartValorCotado'] = $this->createChartValorCotado('SINTESE', 'current');

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function createChartCotacoes($integrador, $periodo, $return = null)
    {

        $resultado = $this->grafico->getDadosCotacao($integrador, $periodo);

        $dados = [
            ['name' => 'TOTAL COT', 'data' => $resultado['totalCot']],
            ['name' => 'COT COM PROD', 'data' => $resultado['cotProd']],
            ['name' => 'COT ENVIADA', 'data' => $resultado['cotEnviada']]
        ];

        $data['data'] = $dados;
        $data['labels'] = $resultado['labels'];

        if (isset($return)) {

            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {

            return $data;
        }
    }

    public function createChartTipoCotacao($integrador, $periodo, $return = null)
    {

        $fornecedores = $this->grafico->matrizFilial(true);

        $totalManual = [];
        $totalAutomatica = [];
        $totalMix = [];

        foreach ($fornecedores as $ids) {

            array_push($totalManual, $this->grafico->cotacoesPorFornecedor($integrador, $periodo, $ids, 1));
            array_push($totalAutomatica, $this->grafico->cotacoesPorFornecedor($integrador, $periodo, $ids, 2));
            array_push($totalMix, $this->grafico->cotacoesPorFornecedor($integrador, $periodo, $ids, 3));
        }

        $dados = [
            ['name' => 'COT MANUAL', 'type' => 'column', 'data' => $totalManual],
            ['name' => 'COT AUTOMÀTICA', 'type' => 'column', 'data' => $totalAutomatica],
            ['name' => 'MIX', 'type' => 'line', 'data' => $totalMix],
        ];

        $data['data'] = $dados;
        $data['labels'] = array_keys($fornecedores);

        if (isset($return)) {

            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {

            return $data;
        }
    }

    public function createChartValorCotado($integrador, $periodo, $return = null)
    {

        $dados = $this->grafico->getQuotePriceSent($integrador, $periodo);

        # Ordena os dados
        array_multisort(array_column($dados, 'total'), SORT_DESC, $dados);

        $valores = [];
        $valoresFormatados = [];

        foreach ($dados as $value) {

            array_push($valores, floatval($value['total']));
            array_push($valoresFormatados, number_format($value['total'], 4, ',', '.'));
        }

        $data['data'] = [['name' => 'Total', 'data' => $valores]];
        $data['labels'] = array_column($dados, 'matriz');
        $data['formatado'] = $valoresFormatados;

        if (isset($return)) {

            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {

            return $data;
        }
    }

    ######################

    public function ocultarCotacao($id_cotacao, $integrador = 'SINTESE')
    {
        if ($integrador == "SINTESE") {

            $updt = $this->DB_COTACAO->where('id', $id_cotacao)->update('cotacoes', ['oculto' => 1, 'data_atualizacao' => date("Y-m-d H:i:s")]);
        } else {

            $updt = $this->bio->where('id', $id_cotacao)->update('cotacoes', ['oculto' => 1]);
        }

        if ($updt) {

            $output = ['type' => 'success', 'message' => notify_update];
        } else {

            $output = $this->notify->errorMessage();
        }

        $this->session->set_userdata('warning', $output);
        return redirect($this->route);
    }

    /**
     * Exibe as cotações sintese/bionexo em aberto
     *
     * @return  json
     */
    public function datatables_cotacoes()
    {

        $datatables = $this->COTACOES->cotacoesEmAberto();

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    public function readAll()
    {

        if ($this->session->has_userdata('id_fornecedor')) {

            $id_fornecedor = $this->session->id_fornecedor;
        } else {

            $id_fornecedor = null;
        }

        $readAll = $this->notificacao->readAll($this->session->id_usuario, $id_fornecedor);

        if ($readAll) {

            $warning = ['type' => 'success', 'message' => 'Notificações lidas.'];
        } else {

            $warning = ['type' => 'error', 'message' => 'Não foi possível realizar a ação, comunique o suporte.'];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($warning));
    }

    public function grafico()
    {
        $this->db->select('id');
        $query = $this->db->get('fornecedores');
        $result = $query->result();
        var_dump($result);
    }

    function porcentagem_nx($parcial, $total)
    {
        return ($parcial * 100) / $total;
    }
}

/* End of file: Dashboard.php */
