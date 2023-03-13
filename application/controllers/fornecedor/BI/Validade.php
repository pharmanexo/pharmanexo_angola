<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Validade extends MY_Controller
{

    private $route;
    private $views;
    private $DB_COTACAO;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('/fornecedor/BI/validade');
        $this->views = 'fornecedor/BI/validade';

        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_marca', 'marca');
        $this->load->model('m_compradores', 'comprador');
        $this->load->model('m_encontrados_sintese', 'DEPARA');
        $this->load->model('m_estados', 'estado');
        $this->load->model('m_bi', 'BI');
        $this->load->model('m_produto', 'produto');

        $this->DB_COTACAO = $this->load->database('sintese', TRUE);
    }

    /**
     * Exibe a tela inicial do BI
     *
     * @return view
     */
    public function index()
    {
        $page_title = "Relatório de Produtos por validade";

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
        $data['scripts'] = $this->template->scripts(['scripts' => ['https://cdn.jsdelivr.net/npm/apexcharts'] ]);

        # URLs
        $data['url'] = "{$this->route}/main";

        $data['urlRelatorioProdutosValidade'] = "{$this->route}/datatableRelatorioProdutosValidade";

        # Filtros

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

        $id_fornecedor = ( isset($post['id_fornecedor']) && !empty($post['id_fornecedor']) ) ? $post['id_fornecedor'] : $this->session->id_fornecedor;

        if ( $id_fornecedor == $this->session->id_fornecedor ) {

            $id_estado = $this->session->id_estado;
        } else {

            $f = $this->fornecedor->findById($id_fornecedor);
            $id_estado = $this->estado->find("id", "uf = '{$f['estado']}' ", true)['id'];
        }
    
        # Grafico
        $data['chart'] = $this->createChartProdutosVencer($id_fornecedor, $id_estado);

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function createChartProdutosVencer($id_fornecedor, $id_estado)
    {

        $intervalo1 = $this->BI->valorTotalProdutosPorValidade($id_fornecedor, $id_estado, date('Y-m-d'), date('Y-m-d', strtotime('+3months')));
        $intervalo2 = $this->BI->valorTotalProdutosPorValidade($id_fornecedor, $id_estado, date('Y-m-d', strtotime('+3months')), date('Y-m-d', strtotime('+6months')));
        $intervalo3 = $this->BI->valorTotalProdutosPorValidade($id_fornecedor, $id_estado, date('Y-m-d', strtotime('+6months')), date('Y-m-d', strtotime('+9months')));
        $intervalo4 = $this->BI->valorTotalProdutosPorValidade($id_fornecedor, $id_estado, date('Y-m-d', strtotime('+9months')), date('Y-m-d', strtotime('+12months')));
        $intervalo5 = $this->BI->valorTotalProdutosPorValidade($id_fornecedor, $id_estado, date('Y-m-d', strtotime('+12months')), date('Y-m-d', strtotime('+18months')));
        $intervalo6 = $this->BI->valorTotalProdutosPorValidade($id_fornecedor, $id_estado, date('Y-m-d', strtotime('+18months')));

        
        $data['format'] = [ 
            number_format($intervalo1, 4, ',', '.'), 
            number_format($intervalo2, 4, ',', '.'), 
            number_format($intervalo3, 4, ',', '.'), 
            number_format($intervalo4, 4, ',', '.'), 
            number_format($intervalo5, 4, ',', '.'), 
            number_format($intervalo6, 4, ',', '.'), 
        ];

        $data['value'] = [ [ 'name' => 'Total', 'data' => [
            $intervalo1, 
            $intervalo2, 
            $intervalo3, 
            $intervalo4, 
            $intervalo5, 
            $intervalo6, 
        ] ] ];

        return $data;
    }

    /**
     * Obtem os dados do relatorio de cotações
     *
     * @param - post - String data inicio
     * @param - post - String data fim
     * @param - post - int ID fornecedor (opcional)
     * @return json
     */
    public function datatableRelatorioProdutosValidade()
    {

        $post = $this->input->post();
            
        switch( intval($post['filtro']) ) {
            case 0: 

                $dataInicio =  date('Y-m-d');
                $dataFim = date('Y-m-d', strtotime('+3months'));
                break;
            case 1:  

                $dataInicio =  date('Y-m-d', strtotime('+3months'));
                $dataFim = date('Y-m-d', strtotime('+6months'));
                break;
            case 2:

                $dataInicio =  date('Y-m-d', strtotime('+6months'));
                $dataFim = date('Y-m-d', strtotime('+9months'));
                break;
            case 3:

                $dataInicio =  date('Y-m-d', strtotime('+9months'));
                $dataFim = date('Y-m-d', strtotime('+12months'));
                break;
            case 4:

                $dataInicio =  date('Y-m-d', strtotime('+12months'));
                $dataFim = date('Y-m-d', strtotime('+18months'));
                break;
            case 5:

                $dataInicio =  date('Y-m-d', strtotime('+18months'));
                $dataFim = null;
                break;
            default:
                $dataInicio =  date('Y-m-d');
                $dataFim = date('Y-m-d', strtotime('+3months'));
                break;
        }  

        $id_fornecedor = ( isset($post['id_fornecedor']) && !empty($post['id_fornecedor']) ) ? $post['id_fornecedor'] : $this->session->id_fornecedor;

        if ( $id_fornecedor == $this->session->id_fornecedor ) {

            $id_estado = $this->session->id_estado;
        } else {

            $f = $this->fornecedor->findById($id_fornecedor);
            $id_estado = $this->estado->find("id", "uf = '{$f['estado']}' ", true)['id'];
        }

        $data = $this->BI->produtosPorValidade($post, $id_fornecedor, $id_estado, $dataInicio, $dataFim);

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}