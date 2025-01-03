<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cotacoes extends MY_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('admin/relatorios/cotacoes');
        $this->views = 'admin/relatorios/cotacoes';

        $this->load->model('m_cotacoes', 'cotacoes');
        $this->load->model('cotacao', 'cotacao');
        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_integracao', 'integracao');
    }

    public function index()
    {
        $page_title = "Relatório de Cotações";
        $data['datatables']   = "{$this->route}/datatables";
        $data['url_detail']   = "{$this->route}/detalhes";
        $data['header'] = $this->template->header([ 'title' => $page_title ]);
        $data['navbar']  = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([ 'page_title' => $page_title ]);
        $data['scripts'] = $this->template->scripts();

        $data['fornecedores'] = $this->cotacoes->getFornecedores();

        $this->load->view("{$this->views}/main", $data);
    }

    public function detalhes($id)
    {

        $data['row'] = $this->cotacoes->getById($id);

        $cliente = $this->compradores->getByCNPJ($data['row']['cnpj_comprador']);
        if (!empty($cliente)) {
            $data['row'] = array_merge($data['row'], $cliente);
        }

        $data['produtos'] = $this->cotacao->find("*", "id_cotacao = {$id} AND rejeitado = 'N' and submetido = {$data['row']['submetido']}");
        
        $data['produtos_rejeitados'] = $this->cotacao->find("*", "id_cotacao = {$id} AND rejeitado = 'S' and submetido = {$data['row']['submetido']}");

        $totais_cotacao         = $this->integracao->totaisCotacao($id, $data['row']['submetido'])->row_array();
        $totais_cotacao_validos = $this->integracao->totaisCotacao_validos($id, $data['row']['submetido'])->row_array();
        $total_ganho            = floatval($totais_cotacao_validos['total_marca_marca']) + floatval($totais_cotacao_validos['total_outra_marca']);

        $nao_coatado      = $this->integracao->totaisCotacaoRejeitados($id, $data['row']['submetido'])->row_array();
        $total_nao_cotado = floatval($nao_coatado['total_marca_marca']) + floatval($nao_coatado['total_outra_marca']);

        $data['totais'] = [
            'valor_total_cotacao' => $totais_cotacao['total_cotacao'],
            'participacao'        => $totais_cotacao_validos['total_cotacao_oferta'],
            'total_ganho'         => $total_ganho,
            'total_rejeitado'     => $total_nao_cotado,
            'participacoes'       => [
                'marca'       => [
                    'total'             => $totais_cotacao_validos['total_marca_marca'],
                    'percentual_total'  => ($totais_cotacao_validos['total_marca_marca'] * 100) / $totais_cotacao['total_cotacao'],
                    'percentual_cotado' => ($totais_cotacao_validos['total_cotacao_oferta'] > 0) ? ($totais_cotacao_validos['total_marca_marca'] * 100) / $totais_cotacao_validos['total_cotacao_oferta'] : 0,
                ],
                'outra_marca' => [
                    'total'             => $totais_cotacao_validos['total_outra_marca'],
                    'percentual_total'  => ($totais_cotacao_validos['total_outra_marca'] * 100) / $totais_cotacao['total_cotacao'],
                    'percentual_cotado' => ($totais_cotacao_validos['total_cotacao_oferta'] > 0) ? ($totais_cotacao_validos['total_outra_marca'] * 100) / $totais_cotacao_validos['total_cotacao_oferta'] : 0,
                ]
            ],

        ];

        $page_title = "Pedido: #{$data['row']['cd_cotacao']}";
        $data['header'] = $this->template->header([ 'title' => $page_title ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type'  => 'a',
                    'id'    => 'btnBack',
                    'url'   => $this->route,
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ],
            ]
        ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/Chart.js/Chart.min.js',
                THIRD_PARTY . 'plugins/Chart.js/Chart.bundle.min.js',
                THIRD_PARTY . 'plugins/Chart.js/plugins/chartjs-plugin-labels.min.js'
            ]
        ]);

        $this->load->view("{$this->views}/detail", $data);
    }

    /**
     * Exibe o datatables de cotações
     *
     * @return  json
     */
    public function datatables()
    {
        $datatables = $this->datatable->exec(
            $this->input->post(),
            'vw_cotacoes',
            [
                [ 'db' => 'vw_cotacoes.id', 'dt' => 'id' ],
                [ 'db' => 'vw_cotacoes.id_cotacao', 'dt' => 'id_cotacao' ],
                [ 'db'        => 'vw_cotacoes.data_cotacao', 'dt' => 'data_cotacao', 
                    'formatter' => function ($d) { 
                        return date('d/m/Y', strtotime($d)); 
                    } 
                ],
                [ 'db' => 'vw_cotacoes.cnpj_comprador', 'dt' => 'cnpj_comprador' ],
                [ 'db' => 'vw_cotacoes.uf_comprador', 'dt' => 'uf_comprador' ],
                [ 'db' => 'vw_cotacoes.id_fornecedor', 'dt' => 'id_fornecedor' ],
                [ 'db' => 'fornecedores.razao_social', 'dt' => 'razao_social' ],
                ['db' => 'vw_cotacoes.id_cotacao', 'dt' => 'total_itens', 'formatter' => function ($d) {

                    $this->db->where('id_cotacao', $d);
                    return $this->db->count_all_results('cotacoes_produtos');
                }],
            ],
            [
                ['fornecedores', 'fornecedores.id = vw_cotacoes.id_fornecedor', 'INNER']
            ]
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }
}

/* End of file: Cotacoes.php */
