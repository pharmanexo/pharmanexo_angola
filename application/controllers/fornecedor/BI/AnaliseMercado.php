<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AnaliseMercado extends MY_Controller
{

    private $route;
    private $views;
    private $DB_COTACAO;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('/fornecedor/BI/analiseMercado');
        $this->views = 'fornecedor/BI/analiseMercado';

        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_marca', 'marca');
        $this->load->model('m_compradores', 'comprador');
        $this->load->model('m_encontrados_sintese', 'DEPARA');
        $this->load->model('m_estados', 'estado');
        $this->load->model('m_bi', 'BI');
        $this->load->model('m_configAnaliseMercado', 'analiseMercado');

        $this->DB_COTACAO = $this->load->database('sintese', TRUE);
    }

    /**
     * Exibe a tela inicial do BI
     *
     * @return view
     */
    public function index()
    {
        $page_title = "Lista de produtos para análise de mercado";

        $data['header'] = $this->template->header(['title' => $page_title ]);
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
        $data['scripts'] = $this->template->scripts();

        # URLs
        $data['datasource'] = "{$this->route}/datatable";
        $data['urlDetalhes'] = "{$this->route}/detalhes";

        # Filtros
        $data['estados'] = $this->estado->find("id, uf, CONCAT(uf, ' - ', descricao) AS estado", null, false, 'estado ASC');

        $this->load->view("{$this->views}/main", $data);
    }

    public function detalhes($id)
    {

        $page_title = "Relatório de Análise de mercado";

        $data['produto'] = $this->analiseMercado->getProduct($id);
        $data['fornecedor'] = $this->fornecedor->findById($data['produto']['id_fornecedor']);

        $data['urlAnaliseMercado'] = "{$this->route}/consulta";

        $data['header'] = $this->template->header(['title' => $page_title ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                    [
                        'type'  => 'a',
                        'id'    => 'btnBack',
                        'url'   => "{$this->route}",
                        'class' => 'btn-secondary',
                        'icone' => 'fa-arrow-left',
                        'label' => 'Voltar'
                    ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts();


        $this->load->view("{$this->views}/detail", $data);
    }

    /**
     * Exibe a lista de configurações
     *
     * @return  json
     */
    public function datatable()
    {

        $datatables = $this->datatable->exec(
            $this->input->post(),
            'config_analise_mercado config',
            [
                ['db' => 'config.id', 'dt' => 'id'],
                ['db' => 'config.codigo', 'dt' => 'codigo'],
                ['db' => 'config.id_fornecedor', 'dt' => 'id_fornecedor'],
                ['db' => 'pc.nome_comercial', 'dt' => 'nome_comercial'],
                ['db' => 'f.nome_fantasia', 'dt' => 'nome_fantasia'],
                ['db' => 'config.data_criacao', 'dt' => 'data_criacao', 'formatter' => function ($value, $row) {

                    return date('d/m/Y H:i', strtotime($value));
                }],
                ['db' => "JSON_EXTRACT(config.data, '$.precos')", 'dt' => 'data', 'formatter' => function ($value, $row) {

                    $data = json_decode($value);

                    $array = array();

                    return implode(', ', array_column($data, 'estado'));

                    foreach (array_column($data, 'estado') as $uf) {

                        $array[] = "<span class='badge badge-primary mt-1'>{$uf}</span>";
                    }

                    return implode(' ', $array);
                }],
            ],
            [
                ['fornecedores f', 'f.id = config.id_fornecedor'],
                ['produtos_catalogo pc', 'pc.codigo = config.codigo AND pc.id_fornecedor = config.id_fornecedor'],
            ],
            null
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    public function consulta()
    {
        
        if ( $this->input->is_ajax_request() ) {

            $post = $this->input->post();


            $precoConcorrencia = floatval($post['preco']);

            $ofertas = $this->analiseMercado->getOfertas($post['id_fornecedor'], $post['codigo'], $post['uf']);

            if ( !empty($ofertas) ) {

                $media = array_sum(array_column($ofertas, 'preco_marca')) / count($ofertas);
                $min = min(array_column($ofertas, 'preco_marca'));
                $max = max(array_column($ofertas, 'preco_marca'));

                $variacao = $precoConcorrencia * ( $media - $precoConcorrencia ) / ( $precoConcorrencia * 100 );

                $variacao = ($variacao > 0 && $variacao < 1) ? number_format( $variacao, 4, ',', '.') : number_format( $variacao, 2, ',', '.');
                $valorMinimo = number_format( $min, 2, ',', '.' );
                $valorMaximo = number_format( $max, 2, ',', '.' );
                $media = number_format( $media, 2, ',', '.' );

                if ( $variacao[0] == '-' ) {

                    $variacao = str_replace('-', '', $variacao);
                    $variacao = "desconto de {$variacao}";
                } 

                $data = [
                    "O valor minimo ofertado é de R$ {$valorMinimo}",
                    "o valor máximo ofertado é de R$ {$valorMaximo}",
                    "a média é de R$ {$media}",
                    "e a variação é de {$variacao} porcento"
                ];

                $output = ['type' => 'success', 'message' => $data];
            } else {

                $output = ['type' => 'warning', 'message' => 'A análise de mercado não pode ser feita pois não existem ofertas do produto para este estado'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }
}