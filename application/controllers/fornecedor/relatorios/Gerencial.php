<?php

class Gerencial extends MY_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/relatorios/gerencial');
        $this->views = 'fornecedor/relatorios/gerencial';

        $this->load->model('M_relatorios', 'relatorios');
        $this->load->model('m_fornecedor', 'fornecedor');
    }


    public function index()
    {
        $page_title = 'Relatório Gerencial';

        $data['to_datatable'] = "{$this->route}/getData";


        $data['gerentes'] = $this->relatorios->getEquipeAll(3);
        $data['consultores'] = $this->relatorios->getEquipeAll(2);
        $data['assistentes'] = $this->relatorios->getEquipeAll(1);

        $data['header'] = $this->template->header(['title' => $page_title ]);
        $data['navbar']  = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts();

        if ($this->session->has_userdata('id_matriz')) {

            $data['selectMatriz'] = $this->fornecedor->find("id, nome_fantasia", "id_matriz = {$this->session->id_matriz}");
        }

        $this->load->view("{$this->views}/main", $data);
    }


    public function getData($e = null)
    {
        $all = ($e == 1) ? TRUE :  FALSE;
        $export = [];

        $post = $this->input->post();

        $filtros = [
            'id_fornecedor' => $this->session->id_fornecedor,
            'dataini' => date('Y-m-d', strtotime('-5 days')),
            'datafim' => date('Y-m-d', time()),
        ];

        $filtros = array_merge($filtros, $post);

        #busca os produtos das cotações
        $consulta = $this->relatorios->getCotacoes($filtros, $all);
        $produtos = $consulta['data'];

        foreach ($produtos as $j => $produto) {

            #coloca o status inicial como não respondido
            $produto['status'] = 'Não respondido';

            $produto['assistente'] = isset($produto['assistente']) && !empty($produto['assistente']) ? $this->relatorios->getPessoaEquipe($produto['assistente'])['nome'] : '';
            $produto['consultor'] = isset($produto['consultor']) && !empty($produto['consultor']) ? $this->relatorios->getPessoaEquipe($produto['consultor'])['nome'] : '';
            $produto['gerente'] = isset($produto['gerente']) && !empty($produto['gerente']) ? $this->relatorios->getPessoaEquipe($produto['gerente'])['nome'] : '';

            # verifica se teve oferta para o produto

            $ofertas = $this->relatorios->getOfertas($produto);

            if (!empty($ofertas)){
                # em caso de houver oferta, coloca o status como respondido
                $produto['status'] = 'Respondido';
                $i = 1;
                foreach ($ofertas as $k => $oferta){
                    $total = number_format(($oferta['qtd_solicitada'] * $oferta['preco_marca']), 2, ',', '.');
                    $preco = number_format($oferta['preco_marca'], 2, ',', '.');


                    $produto['oferta'] = $i;
                    $produto['total'] = $total;
                    $produto['preco'] = $preco;
                    $produto['dt_inicio_cotacao'] = date("d/m/Y", strtotime($produto['dt_inicio_cotacao']));
                    $export[] = $produto;

                    $i++;

                }

            }else{
                $produto['oferta'] = 0;
                $produto['total'] = '0,00';
                $produto['preco'] = '0,00';
                $produto['dt_inicio_cotacao'] = date("d/m/Y", strtotime($produto['dt_inicio_cotacao']));
                $export[] = $produto;
            }

        }




        if ($e == 1){
            $dados_page = ['dados' => $produtos, 'titulo' => 'Relatório Gerencial'];
            $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        }else{
            $output = [
                "recordsTotal" => $consulta['totalRecords'],
                "recordsFiltered" => $consulta['totalFiltered'],
                "data" => $export
            ];

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }



    }

    public function exportar()
    {
        $post = $this->input->post();

        $filtros = [
            'id_fornecedor' => $this->session->id_fornecedor,
            'dataini' => date('Y-m-d', strtotime('-5 days')),
            'datafim' => date('Y-m-d', time()),
        ];

        $filtros = array_merge($filtros, $post);

        #busca os produtos das cotações
        $consulta = $this->relatorios->getCotacoes($filtros, true);
        $produtos = $consulta['data'];

        $dados_page = ['dados' => $produtos, 'titulo' => 'Relatório Gerencial'];
        $exportar = $this->export->excel("planilha.xlsx", $dados_page);


    }

}
