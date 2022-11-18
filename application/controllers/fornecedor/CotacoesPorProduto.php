<?php
date_default_timezone_set('America/Sao_Paulo');

class CotacoesPorProduto extends MY_Controller
{

    private $route;
    private $views;
    protected $oncoexo;
    protected $oncoprod;
    private $DB_COTACAO;
    private $DB_BIO;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('/fornecedor/cotacoesPorProduto');
        $this->views = 'fornecedor/cotacoes/cotacoesPorProduto';

        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_compradores', 'comprador');
        $this->load->model('m_estados', 'estado');

        $this->oncoexo = explode(',', ONCOEXO);
        $this->oncoprod = explode(',', ONCOPROD);

        $this->DB_COTACAO = $this->load->database('sintese', TRUE);
        $this->DB_BIO = $this->load->database('bionexo', TRUE);
    }

    /**
     * Exibe a view da lista de cotações
     *
     * @return view
     */
    public function index()
    {
        $page_title = "Cotações por produto <br><small><b>Preencha algum dos filtros para exibir as cotações</b></small>";

        # URLs
        $data['urlDatatable'] = "{$this->route}/datatable";
        $data['urlFiltro'] = "{$this->route}/getCotacoes";

        if (in_array($this->session->id_fornecedor, $this->oncoprod)) {

            $data['urlCotacao'] = base_url("fornecedor/cotacoes_oncoprod/detalhes");
        } elseif (in_array($this->session->id_fornecedor, $this->oncoexo)) {

            $data['urlCotacao'] = base_url("fornecedor/cotacoes_oncoexo/detalhes");
        } else {

            $data['urlCotacao'] = base_url("fornecedor/cotacoes/detalhes");
        }

        $data['url_ocultar'] = "{$this->route}/ocultarCotacao";

        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
            ]
        ]);
        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Obtem as cotações por produto
     *
     * @return json
     */
    public function getCotacoes()
    {
        if ($this->input->is_ajax_request()) {
            $post = $this->input->post();

            switch ($post['s_integrador']) {
                case 'SINTESE':
                    $cotacoes = $this->busca_sintese($post);
                    break;
                case 'BIONEXO':
                    $cotacoes = $this->busca_bionexo($post);
                    break;
                case 'APOIO':
                    $cotacoes = $this->busca_apoio($post);
                    break;
                default:
                    $sintese = $this->busca_sintese($post);
                    $bionexo = $this->busca_bionexo($post);
                    $apoio = $this->busca_apoio($post);
                    $cotacoes = array_merge($sintese, $bionexo, $apoio);
                    break;
            }

            $data = array(
                "draw" => 0,
                "recordsTotal" => count($cotacoes),
                "recordsFiltered" => count($cotacoes),
                "data" => $cotacoes
            );

            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    private function busca_sintese($post)
    {

        $codigo = $post['codigo'];
        $nome = trim($post['nome']);

        $this->db->select('codigo');
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);

        if (!empty($codigo) && !empty($nome)) {

            $this->db->where('codigo', $codigo);
            $this->db->or_where("nome_comercial LIKE '%{$nome}%'");
        } elseif (!empty($codigo) && empty($nome)) {

            $this->db->where('codigo', $codigo);
        } elseif (empty($codigo) && !empty($nome)) {

            $this->db->where("nome_comercial LIKE '%{$nome}%'");
        }

        $produtos = $this->db->get('produtos_catalogo')->result_array();

        $codigosProduto = array_column($produtos, 'codigo');

        if (!empty($codigosProduto)) {

            # Obtem os ID sintese
            $this->db->select("id_sintese");
            $this->db->where_in("cd_produto", $codigosProduto);
            $this->db->where("id_fornecedor", $this->session->id_fornecedor);

            $produtos_sintese = $this->db->get('produtos_fornecedores_sintese')->result_array();

            $idsSintese = array_column($produtos_sintese, 'id_sintese');

            if (!empty($idsSintese)) {

                # Obtem os ID produto
                $this->db->select("id_produto");
                $this->db->where_in("id_sintese", $idsSintese);
                $pms = $this->db->get('produtos_marca_sintese')->result_array();

                $idsProduto = array_column($pms, 'id_produto');

                if (!empty($idsProduto)) {

                    # Procura os produtos nas cotações
                    $this->DB_COTACAO->select("cot.cd_cotacao");
                    $this->DB_COTACAO->select("cot.ds_cotacao");
                    $this->DB_COTACAO->select("cot.dt_inicio_cotacao");
                    $this->DB_COTACAO->select("cot.dt_fim_cotacao");
                    $this->DB_COTACAO->select("c.cnpj");
                    $this->DB_COTACAO->select("c.razao_social");
                    $this->DB_COTACAO->select("c.estado");
                    $this->DB_COTACAO->select("cp.qt_produto_total");
                    $this->DB_COTACAO->from("cotacoes_produtos cp");
                    $this->DB_COTACAO->join("cotacoes cot", "cot.cd_cotacao = cp.cd_cotacao AND cot.id_fornecedor = cp.id_fornecedor");
                    $this->DB_COTACAO->join("pharmanexo.compradores c", "cot.id_cliente = c.id", 'LEFT');
                    $this->DB_COTACAO->where_in('cp.id_produto_sintese', $idsProduto);
                    $this->DB_COTACAO->where('cp.id_fornecedor', $this->session->id_fornecedor);
                    $this->DB_COTACAO->where('cot.id_fornecedor', $this->session->id_fornecedor);
                    $this->DB_COTACAO->where("cot.dt_fim_cotacao > now()");
                    $this->DB_COTACAO->where("cot.oculto != 1");
                    $this->DB_COTACAO->order_by("cot.dt_fim_cotacao ASC");
                    $cotacoes = $this->DB_COTACAO->get()->result_array();

                    foreach ($cotacoes as $kk => $row) {

                        $periodo = date('d/m/Y H:i', strtotime($row['dt_inicio_cotacao'])) . ' - ' . date('d/m/Y H:i', strtotime($row['dt_fim_cotacao']));

                        $cotacoes[$kk]['ds_cotacao'] = "<small>{$row['ds_cotacao']}</small>";
                        $cotacoes[$kk]['comprador'] = "<small>{$row['razao_social']}<br>CNPJ: {$row['cnpj']}</small>";
                        $cotacoes[$kk]['data'] = $periodo;
                        $cotacoes[$kk]['qtd_solicitada'] = $row['qt_produto_total'];
                        $cotacoes[$kk]['estado'] = $row['estado'];
                    }
                } else {

                    $cotacoes = [];
                }
            } else {

                $cotacoes = [];
            }
        } else {

            $cotacoes = [];
        }

        return $cotacoes;
    }

    public function busca_bionexo($post)
    {
        $codigo = $post['codigo'];
        $nome = trim($post['nome']);

        $where = '';

        if (!empty($codigo) && !empty($nome)) {

            $where = "(pc.codigo = {$codigo} or pc.nome_comercial LIKE '%$nome%')";

        } elseif (!empty($codigo) && empty($nome)) {

            $where = "pc.codigo = {$codigo}";
        } elseif (empty($codigo) && !empty($nome)) {

            $where = "pc.nome_comercial LIKE '%$nome%'";
        }


        $cotacoes = $this->db
            ->select('ct.cd_cotacao, ct.ds_cotacao, ct.dt_inicio_cotacao, ct.dt_fim_cotacao, cp.qt_produto_total, c.cnpj, c.razao_social, c.estado')
            ->from('produtos_catalogo pc')
            ->join('produtos_fornecedores_sintese pfs', 'pfs.cd_produto = pc.codigo and pc.id_fornecedor = pfs.id_fornecedor')
            ->join('produtos_marca_sintese pms', 'pms.id_sintese = pfs.id_sintese')
            ->join('produtos_clientes_depara pcd', 'pcd.id_produto_sintese = pms.id_produto and pcd.id_integrador = 2')
            ->join('cotacoes_bionexo.cotacoes_produtos cp', 'cp.cd_produto_comprador = pcd.cd_produto')
            ->join('cotacoes_bionexo.cotacoes ct', 'ct.id = cp.id_cotacao and ct.id_cliente = pcd.id_cliente and ct.id_fornecedor = pc.id_fornecedor')
            ->join('compradores c', 'c.id = pcd.id_cliente')
            ->where('pc.id_fornecedor', $this->session->id_fornecedor)
            ->where($where)
            ->where('ct.dt_fim_cotacao > now()')
            ->get()
            ->result_array();

        if (!empty($cotacoes)) {
            foreach ($cotacoes as $kk => $row) {

                $periodo = date('d/m/Y H:i', strtotime($row['dt_inicio_cotacao'])) . ' - ' . date('d/m/Y H:i', strtotime($row['dt_fim_cotacao']));

                $cotacoes[$kk]['ds_cotacao'] = "<small>{$row['ds_cotacao']}</small>";
                $cotacoes[$kk]['comprador'] = "<small>{$row['razao_social']}<br>CNPJ: {$row['cnpj']}</small>";
                $cotacoes[$kk]['data'] = $periodo;
                $cotacoes[$kk]['qtd_solicitada'] = $row['qt_produto_total'];
                $cotacoes[$kk]['estado'] = $row['estado'];
            }
        }

        return $cotacoes;

    }

    public function busca_apoio($post)
    {
        $codigo = $post['codigo'];
        $nome = trim($post['nome']);

        $where = '';

        if (!empty($codigo) && !empty($nome)) {

            $where = "(pc.codigo = {$codigo} or pc.nome_comercial LIKE '%$nome%')";

        } elseif (!empty($codigo) && empty($nome)) {

            $where = "pc.codigo = {$codigo}";
        } elseif (empty($codigo) && !empty($nome)) {

            $where = "pc.nome_comercial LIKE '%$nome%'";
        }


        $cotacoes = $this->db
            ->select('ct.cd_cotacao, ct.ds_cotacao, ct.dt_inicio_cotacao, ct.dt_fim_cotacao, cp.qt_produto_total, c.cnpj, c.razao_social, c.estado')
            ->from('produtos_catalogo pc')
            ->join('produtos_fornecedores_sintese pfs', 'pfs.cd_produto = pc.codigo and pc.id_fornecedor = pfs.id_fornecedor')
            ->join('produtos_marca_sintese pms', 'pms.id_sintese = pfs.id_sintese')
            ->join('produtos_clientes_depara pcd', 'pcd.id_produto_sintese = pms.id_produto and pcd.id_integrador = 3')
            ->join('cotacoes_apoio.cotacoes_produtos cp', 'cp.cd_produto_comprador = pcd.cd_produto')
            ->join('cotacoes_apoio.cotacoes ct', 'ct.id = cp.id_cotacao and ct.id_cliente = pcd.id_cliente and ct.id_fornecedor = pc.id_fornecedor')
            ->join('compradores c', 'c.id = pcd.id_cliente')
            ->where('pc.id_fornecedor', $this->session->id_fornecedor)
            ->where($where)
            ->where('ct.dt_fim_cotacao > now()')
            ->get()
            ->result_array();

        if (!empty($cotacoes)) {
            foreach ($cotacoes as $kk => $row) {

                $periodo = date('d/m/Y H:i', strtotime($row['dt_inicio_cotacao'])) . ' - ' . date('d/m/Y H:i', strtotime($row['dt_fim_cotacao']));

                $cotacoes[$kk]['ds_cotacao'] = "<small>{$row['ds_cotacao']}</small>";
                $cotacoes[$kk]['comprador'] = "<small>{$row['razao_social']}<br>CNPJ: {$row['cnpj']}</small>";
                $cotacoes[$kk]['data'] = $periodo;
                $cotacoes[$kk]['qtd_solicitada'] = $row['qt_produto_total'];
                $cotacoes[$kk]['estado'] = $row['estado'];
            }
        }

        return $cotacoes;

    }
}
