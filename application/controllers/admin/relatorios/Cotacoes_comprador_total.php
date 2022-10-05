<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cotacoes_comprador_total extends Admin_controller
{
    private $route, $views, $DB_COTACAO;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('admin/relatorios/cotacoes_comprador_total');
        $this->views = "admin/relatorios/cotacoes_comprador_total";

        $this->DB_COTACAO = $this->load->database('sintese', TRUE);

        $this->load->model('m_fornecedor', 'fornecedores');
        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_estados', 'estados');
        $this->load->model('m_encontrados_sintese', 'DEPARA');
        $this->load->model('m_marca', 'marcas');
    }

    public function index()
    {
        $page_title = 'Total de cotações por Comprador';

        $data['form_action'] = "{$this->route}/details";

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'styles' => [THIRD_PARTY . 'theme/plugins/flatpickr/flatpickr.min.css']
        ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading(['page_title' => $page_title]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'theme/plugins/flatpickr/flatpickr.min.js',
                'https://npmcdn.com/flatpickr/dist/l10n/pt.js'
            ]
        ]);

        $data['integradores'] = $this->db->get('integradores')->result_array();

        $this->load->view("{$this->views}/main", $data);
    }

    public function details()
    {
        $post = $this->input->post();


        switch ($post['integrador']) {
            case 1:

                $result = $this->db->query("select c.nome_fantasia               AS COMPRADOR,
       c.razao_social               AS RAZAO_SOCIAL,
                                                   ct.uf_cotacao                 AS UF,
                                                   count(distinct ct.cd_cotacao) AS COTACOES,
                                                   count(distinct cp.cd_produto_comprador) as PRODUTOS
                                            from cotacoes_sintese.cotacoes ct
                                                     join pharmanexo.compradores c on c.id = ct.id_cliente
                                                     join cotacoes_sintese.cotacoes_produtos cp on cp.cd_cotacao = ct.cd_cotacao
                                            where ct.dt_inicio_cotacao between '{$post['data_ini']}}' and '{$post['data_fim']}}'
                                            group by ct.id_cliente
                                            order by  count(distinct ct.cd_cotacao) DESC, count(distinct cp.cd_produto_comprador) DESC")->result_array();

                $dados_page = ['dados' => $result, 'titulo' => 'cotacoes'];

                $exportar = $this->export->excel("planilha.xlsx", $dados_page);
                break;
            case 2:

                $result = $this->db->query("select c.nome_fantasia               AS COMPRADOR,
       c.razao_social               AS RAZAO_SOCIAL,
                                                   ct.uf_cotacao                 AS UF,
                                                   count(distinct ct.cd_cotacao) AS COTACOES,
                                                   count(distinct cp.cd_produto_comprador) as PRODUTOS
                                            from cotacoes_bionexo.cotacoes ct
                                                     join pharmanexo.compradores c on c.id = ct.id_cliente
                                                     join cotacoes_bionexo.cotacoes_produtos cp on cp.cd_cotacao = ct.cd_cotacao
                                            where ct.dt_inicio_cotacao between '{$post['data_ini']}}' and '{$post['data_fim']}}'
                                            group by ct.id_cliente
                                            order by  count(distinct ct.cd_cotacao) DESC, count(distinct cp.cd_produto_comprador) DESC")->result_array();

                $dados_page = ['dados' => $result, 'titulo' => 'cotacoes'];

                $exportar = $this->export->excel("planilha.xlsx", $dados_page);
                break;
            case 3:

                $result = $this->db->query("select c.nome_fantasia               AS COMPRADOR,
       c.razao_social               AS RAZAO_SOCIAL,
                                                   ct.uf_cotacao                 AS UF,
                                                   count(distinct ct.cd_cotacao) AS COTACOES,
                                                   count(distinct cp.cd_produto_comprador) as PRODUTOS
                                            from cotacoes_apoio.cotacoes ct
                                                     join pharmanexo.compradores c on c.id = ct.id_cliente
                                                     join cotacoes_apoio.cotacoes_produtos cp on cp.cd_cotacao = ct.cd_cotacao
                                            where ct.dt_inicio_cotacao between '{$post['data_ini']}}' and '{$post['data_fim']}'
                                            group by ct.id_cliente
                                            order by  count(distinct ct.cd_cotacao) DESC, count(distinct cp.cd_produto_comprador) DESC")->result_array();

                $dados_page = ['dados' => $result, 'titulo' => 'cotacoes'];

                $exportar = $this->export->excel("planilha.xlsx", $dados_page);
                break;
            default:
                $sintese = $this->db->query("select c.nome_fantasia               AS COMPRADOR,
       c.razao_social               AS RAZAO_SOCIAL,
                                                   ct.uf_cotacao                 AS UF,
                                                   count(distinct ct.cd_cotacao) AS COTACOES,
                                                   count(distinct cp.cd_produto_comprador) as PRODUTOS,
                                            'SINTESE' AS PORTAL
                                            from cotacoes_sintese.cotacoes ct
                                                     join pharmanexo.compradores c on c.id = ct.id_cliente
                                                     join cotacoes_sintese.cotacoes_produtos cp on cp.cd_cotacao = ct.cd_cotacao
                                            where ct.dt_inicio_cotacao between '{$post['data_ini']}}' and '{$post['data_fim']}'
                                            group by ct.id_cliente
                                            order by  count(distinct ct.cd_cotacao) DESC, count(distinct cp.cd_produto_comprador) DESC")->result_array();

                $bionexo = $this->db->query("select c.nome_fantasia               AS COMPRADOR,
       c.razao_social               AS RAZAO_SOCIAL,
                                                   ct.uf_cotacao                 AS UF,
                                                   count(distinct ct.cd_cotacao) AS COTACOES,
                                                   count(distinct cp.cd_produto_comprador) as PRODUTOS,
                                                     'BIONEXO' AS PORTAL
                                            from cotacoes_bionexo.cotacoes ct
                                                     join pharmanexo.compradores c on c.id = ct.id_cliente
                                                       join cotacoes_bionexo.cotacoes_produtos cp on cp.id_cotacao = ct.id
                                            where ct.dt_inicio_cotacao between '{$post['data_ini']}}' and '{$post['data_fim']}'
                                            group by ct.id_cliente
                                            order by  count(distinct ct.cd_cotacao) DESC, count(distinct cp.cd_produto_comprador) DESC")->result_array();

                $apoio = $this->db->query("select c.nome_fantasia               AS COMPRADOR,
       c.razao_social               AS RAZAO_SOCIAL,
                                                   ct.uf_cotacao                 AS UF,
                                                   count(distinct ct.cd_cotacao) AS COTACOES,
                                                   count(distinct cp.cd_produto_comprador) as PRODUTOS,
                                                    'APOIO' AS PORTAL
                                            from cotacoes_apoio.cotacoes ct
                                                     join pharmanexo.compradores c on c.id = ct.id_cliente
                                                     join cotacoes_apoio.cotacoes_produtos cp on cp.id_cotacao = ct.id
                                            where ct.dt_inicio_cotacao between '{$post['data_ini']}}' and '{$post['data_fim']}}'
                                            group by ct.id_cliente
                                            order by  count(distinct ct.cd_cotacao) DESC, count(distinct cp.cd_produto_comprador) DESC")->result_array();

                $merge = array_merge($sintese, $bionexo, $apoio);
                $data = [];
                foreach ($merge as $item) {
                    $data[] = $item;
                }

                $dados_page = ['dados' => $data, 'titulo' => 'cotacoes'];

                $exportar = $this->export->excel("planilha.xlsx", $dados_page);
                break;

        }


    }

}