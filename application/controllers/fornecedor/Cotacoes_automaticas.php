<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cotacoes_automaticas extends MY_Controller
{
    private $route, $views;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('fornecedor/cotacoes_automaticas');
        $this->views = "fornecedor/cotacoes/cotacoes_automaticas";
        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_marca', 'marca');
        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_cotacoes', 'cotacoes');
        $this->load->model('m_forma_pagamento', 'forma_pagamento');
        $this->oncoprod = explode(',', ONCOPROD);
        $this->DB_COTACAO = $this->load->database('sintese', TRUE);
    }

    /**
     * Exibe a view admin/cotacoes_manuais/main.php
     *
     * @return  view
     */
    public function index()
    {
        $page_title = 'Lista de Cotações Automáticas';

        $data['dataTable'] = "{$this->route}/getDatasource";
        $data['url_detalhes'] = "{$this->route}/detalhes/";
        $data['compradores'] = $this->compradores->find("*", null, false, "razao_social desc");
        $data['cotacoes'] = $this->cotacoes->find("*", "id_fornecedor = {$this->session->id_fornecedor}", FALSE, null, "cd_cotacao");

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'styles' => [
                THIRD_PARTY . 'theme/plugins/flatpickr/flatpickr.min.css'
            ]
        ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title, 
            'buttons' => [
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel', 
                    'label' => 'Exportar Excel'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                'https://cdn.jsdelivr.net/npm/apexcharts',
                THIRD_PARTY . 'theme/plugins/flatpickr/flatpickr.min.js',
                'https://npmcdn.com/flatpickr/dist/l10n/pt.js'
            ]
        ]);

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Exibe a view admin/cotacoes_manuais/detail.php
     *
     * @param   int  $id_cotacao
     * @return  view
     */
    public function detalhes($cd_cotacao)
    {
        $data = [];

        //Obtem a cotação
        $this->db->where('cd_cotacao', $cd_cotacao);
        $data['cotacao'] = $this->db->get('vw_cotacoes', '1')->row_array();

        //Obtem o cliente(comprador)
        $data['comprador'] = $this->compradores->find('*', ['cnpj' => $data['cotacao']['cnpj_comprador']], true);

        // Obtem Valor total de todos os produtos
         $valor_total = $this->db
            ->select("SUM( (preco_marca * qtd_solicitada) ) AS valor_total")
            ->where('id_fornecedor', $this->session->id_fornecedor)
            ->where('cd_cotacao', $cd_cotacao)
            ->where('nivel', 2)
            ->get('cotacoes_produtos')
            ->row_array();
                                                    
        $data['valor_total_produtos'] = $valor_total['valor_total'];
        
        // Obtem a qnt de produtos de cada cotação
        $this->db->where('cd_cotacao', $data['cotacao']['cd_cotacao']);
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);
        $this->db->where('nivel', 2);
        $data['total_itens'] = $this->db->count_all_results('cotacoes_produtos');

        $page_title = "Produtos da cotação {$data['cotacao']['cd_cotacao']} <br> <small>{$data['cotacao']['id_cotacao']}</small>";

        $data['url_cotacao'] = "{$this->route}/exportar_cotacao/{$cd_cotacao}/1";
        $data['dataTable'] = "{$this->route}/getDatasource/{$cd_cotacao}";
        $data['header'] = $this->template->header([ 'title' => $page_title ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnBack',
                    'url' => $this->route,
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar_detalhes/{$cd_cotacao}",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel', 
                    'label' => 'Exportar Excel'
                ],
                [
                    'type'  => 'a',
                    'id'    => 'btnPdf',
                    'url'   => "{$this->route}/exportar_cotacao/{$cd_cotacao}",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-pdf', 
                    'label' => 'Exportar PDF'
                ],
                [
                    'type'  => 'a',
                    'id'    => 'btnEspelho',
                    'url'   => "",
                    'class' => 'btn-primary',
                    'icone' => 'fa-print', 
                    'label' => 'Espelho Cotação' 
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
            ]
        ]);

        $this->load->view("{$this->views}/detail", $data);
    }

    /**
     * Obtem dados para os datatables de Cotação automaticas
     *
     * @param = string cd_cotacao
     * @return  json
     */
    public function getDataSource($cd_cotacao = null)
    {
        if (isset($cd_cotacao)) {
            $data = $this->datatable->exec(
                $this->input->get(),
                'cotacoes_produtos',
                [
                    ['db' => 'cotacoes_produtos.id', 'dt' => 'id'],
                    ['db' => 'cotacoes_produtos.cd_cotacao', 'dt' => 'cd_cotacao'],
                    ['db' => 'cotacoes_produtos.id_produto', 'dt' => 'id_produto'],
                    ['db' => 'cotacoes_produtos.id_pfv', 'dt' => 'id_pfv'],
                    ['db' => 'produtos_catalogo.nome_comercial', 'dt' => 'nome_comercial'],
                    ['db' => 'produtos_catalogo.apresentacao', 'dt' => 'apresentacao'],
                    ['db' => 'produtos_catalogo.marca', 'dt' => 'marca'],
                    ['db' => 'cotacoes_produtos.produto', 'dt' => 'produto', 'formatter' => function($value, $row) {

                        if (!empty($row['descricao'])) {
                            return "{$row['nome_comercial']} - {$row['descricao']}";
                        }

                        return "{$row['nome_comercial']} - {$row['apresentacao']}";
                    }],
                    ['db' => 'cotacoes_produtos.preco_marca', 'dt' => 'preco_marca', 'formatter' => function ($preco_marca) {
                        return number_format($preco_marca, 4, ',', '.');
                    }],
                    ['db' => 'cotacoes_produtos.qtd_solicitada', 'dt' => 'qtd_solicitada'],
                ],
                [
                    ['produtos_catalogo', 'produtos_catalogo.codigo = cotacoes_produtos.id_pfv and produtos_catalogo.id_fornecedor = cotacoes_produtos.id_fornecedor', 'left'],
                ],
                "nivel = 2 AND cotacoes_produtos.id_fornecedor = {$this->session->id_fornecedor} AND cotacoes_produtos.submetido = 1 AND cotacoes_produtos.cd_cotacao = '{$cd_cotacao}'"
            );
        } else {

            $data = $this->datatable->exec(
                $this->input->post(),
                'cotacoes_produtos cp',
                [
                    ['db' => 'cp.id_cotacao', 'dt' => 'id_cotacao'],
                    ['db' => 'cp.cd_cotacao', 'dt' => 'cd_cotacao'],
                    ['db' => 'cp.data_cotacao', 'dt' => 'data_cotacao', 'formatter' => function ($value, $row) {
                        
                        return date('d/m/Y H:i:s', strtotime($value));
                    }],
                    ['db' => 'cp.id_cliente', 'dt' => 'id_cliente'],
                    ['db' => 'c.cnpj', 'dt' => 'cnpj'],
                    ['db' => 'c.razao_social', 'dt' => 'razao_social', "formatter" => function ($value, $row) {

                        return "{$value} - {$row['cnpj']}";
                    }],
                    ['db' => 'cp.uf_comprador', 'dt' => 'uf_comprador'],
                    ['db' => 'cp.qtd_solicitada', 'dt' => 'qtd_solicitada'],
                    ['db' => "(
                        SELECT SUM(cp2.preco_marca * cp2.qtd_solicitada)
                        FROM pharmanexo.cotacoes_produtos cp2
                        WHERE cp2.id_fornecedor = cp.id_fornecedor
                            AND cp2.cd_cotacao = cp.cd_cotacao )"
                    , 'dt' => 'valor_total', 'formatter' => function ($value, $row) {

                        return number_format($value, 4, ',', '.');
                    }],
                ],
                [
                    ['compradores c', 'c.id = cp.id_cliente']
                ],
                "cp.nivel = 2 and cp.submetido = 1 and cp.id_fornecedor = {$this->session->id_fornecedor}",
                'cd_cotacao, id_fornecedor'
            );
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function exportar_cotacao($cd_cotacao, $getMirror = null)
    {
        if ( in_array($this->session->id_fornecedor, $this->oncoprod) ) {

            $this->db->select("*");
            $this->db->where('id_fornecedor', $this->session->id_fornecedor);
            $this->db->where('cd_cotacao', $cd_cotacao);
            $this->db->where('nivel', 2);
            $itens_cotacao = $this->db->get('cotacoes_produtos')->result_array();

            $this->DB_COTACAO->where('cd_cotacao', $cd_cotacao);
            $this->DB_COTACAO->where('id_fornecedor', $this->session->id_fornecedor);
            $this->DB_COTACAO->group_by('id_produto_sintese, cd_produto_comprador');
            $cotacoes_produtos = $this->DB_COTACAO->get('cotacoes_produtos')->result_array();

            $itens = [];
            foreach ($cotacoes_produtos as $jj => $produto) {

                foreach ($itens_cotacao as $item) {
                    
                    if ( $produto['id_produto_sintese'] == $item['id_produto'] ) {
                       
                        $this->db->select("*");
                        $this->db->where("codigo = {$item['id_pfv']} and id_fornecedor = {$item['id_fornecedor']}");
                        $produto_catalogo = $this->db->get('produtos_catalogo')->row_array();

                        $produto_catalogo['preco_unidade'] = $item['preco_marca'];

                        $itens[$item['id_produto']]['ds_produto_comprador'] = $produto['ds_produto_comprador'];
                        $itens[$item['id_produto']]['qt_produto_total'] = $produto['qt_produto_total'];
                        $itens[$item['id_produto']]['ds_unidade_compra'] = $produto['ds_unidade_compra'];
                        $itens[$item['id_produto']]['itens'][] = $produto_catalogo;
                    }
                }
            }

            $cliente = $this->compradores->get_byCNPJ($itens_cotacao[0]['cnpj_comprador']);

            $dados = [
                'cliente' => $cliente,
                'observacao' => '',
                'condicao_pagamento' => $itens_cotacao[0]['id_forma_pagamento'],             
                'valor_minimo' =>  $itens_cotacao[0]['valor_minimo'],
                'prazo_entrega' => $itens_cotacao[0]['prazo_entrega'],
                'data_envio' => $itens_cotacao[0]['data_cotacao'],
                'rows' => $itens
            ];

            $this->DB_COTACAO->where('cd_cotacao', $cd_cotacao);
            $this->DB_COTACAO->where('id_fornecedor', $this->session->id_fornecedor);
            $cot = $this->DB_COTACAO->get('cotacoes')->row_array();

            $msg = $this->createBodyMessage($cot, $dados);
        } else {

            $this->db->select("*");
            $this->db->where('id_fornecedor', $this->session->id_fornecedor);
            $this->db->where('cd_cotacao', $cd_cotacao);
            $this->db->where('nivel', 2);
            $itens_cotacao = $this->db->get('cotacoes_produtos')->result_array();

            $this->DB_COTACAO->where('cd_cotacao', $cd_cotacao);
            $this->DB_COTACAO->where('id_fornecedor', $this->session->id_fornecedor);
            $this->DB_COTACAO->group_by('id_produto_sintese, cd_produto_comprador');
            $cotacoes_produtos = $this->DB_COTACAO->get('cotacoes_produtos')->result_array();

            $itens = [];
            foreach ($cotacoes_produtos as $jj => $produto) {

                foreach ($itens_cotacao as $item) {
                    
                    if ( $produto['id_produto_sintese'] == $item['id_produto'] ) {
                       
                        $this->db->select("*");
                        $this->db->where("codigo = {$item['id_pfv']} and id_fornecedor = {$item['id_fornecedor']}");
                        $produto_catalogo = $this->db->get('produtos_catalogo')->row_array();

                        $produto_catalogo['preco_unidade'] = $item['preco_marca'];

                        $itens[$item['id_pfv']]['ds_produto_comprador'] = $produto['ds_produto_comprador'];
                        $itens[$item['id_pfv']]['qt_produto_total'] = $produto['qt_produto_total'];
                        $itens[$item['id_pfv']]['ds_unidade_compra'] = $produto['ds_unidade_compra'];
                        $itens[$item['id_pfv']]['codigo'] = $produto_catalogo['codigo'];
                        $itens[$item['id_pfv']]['marca'] = $produto_catalogo['marca'];
                        $itens[$item['id_pfv']]['preco_unidade'] = $produto_catalogo['preco_unidade'];
                        $itens[$item['id_pfv']]['quantidade_unidade'] = $produto_catalogo['quantidade_unidade'];
                        $itens[$item['id_pfv']]['apresentacao'] = $produto_catalogo['nome_comercial'];
                    }
                }
            }

            $cliente = $this->compradores->get_byCNPJ($itens_cotacao[0]['cnpj_comprador']);

            $dados = [
                'cliente' => $cliente,
                'observacao' => '',
                'condicao_pagamento' => $itens_cotacao[0]['id_forma_pagamento'],             
                'valor_minimo' =>  $itens_cotacao[0]['valor_minimo'],
                'prazo_entrega' => $itens_cotacao[0]['prazo_entrega'],
                'data_envio' => $itens_cotacao[0]['data_cotacao'],
                'rows' => $itens
            ];

            $this->DB_COTACAO->where('cd_cotacao', $cd_cotacao);
            $this->DB_COTACAO->where('id_fornecedor', $this->session->id_fornecedor);
            $cot = $this->DB_COTACAO->get('cotacoes')->row_array();

            $msg = $this->createBodyMessage($cot, $dados);
        }

        if (isset($getMirror)) {

            $this->output->set_content_type('text/html')->set_output($msg); 
        } else {
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->WriteHTML($msg);
            $data = $mpdf->Output("cotacao_enviada.pdf", 'D');
        }
    }

    /**
     * Cria o layout dos itens enviados da cotação ONCOPROD para mandar por e-mail
     *
     * @return  string
     */
    public function createBodyMessage($cotacao, $dados)
    {

        $data_inicio = date('d/m/Y H:i:s', strtotime($cotacao['dt_inicio_cotacao']));
        $data_fim = date('d/m/Y H:i:s', strtotime($cotacao['dt_fim_cotacao']));
        $data_validade = date('d/m/Y', strtotime($cotacao['dt_validade_preco']));
        $data_envio = date('d/m/Y', strtotime($dados['data_envio']));
        $valor_minimo = number_format($dados['valor_minimo'], 2, ",", ".");
        $condicao_pagamento = $this->forma_pagamento->findById($dados['condicao_pagamento'])['descricao'];

        if ( in_array($this->session->id_fornecedor, $this->oncoprod) ) {

            $i = 1;
            $rows = "";
            foreach ($dados['rows'] as $produto) {

                $row = "
                <table style='width:100%; border: 1px solid #dddddd; border-collapse: collapse'>
                <tr>
                    <td style='border: 1px solid #dddddd' colspan='2'>{$i}. {$produto['ds_produto_comprador']}</td>
                    <td style='border: 1px solid #dddddd' colspan='2'><strong>Qtde Solicitada:</strong> {$produto['qt_produto_total']}</td>
                    <td style='border: 1px solid #dddddd' colspan='1'><strong>Und. Compra:</strong> {$produto['ds_unidade_compra']}</td>
                </tr>
                <tr>
                    <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Cód. Kraft</th>
                    <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Marca</th>
                    <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Embalagem</th>
                    <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Preço</th>
                    <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Descrição</th>
                </tr>
                ";

                foreach ($produto['itens'] as $item) {

                    $fornecedor = $this->fornecedor->findById($item['id_fornecedor'])['nome_fantasia'];
                    $marca = $this->marca->get_row($item['id_marca'])['marca'];
                    $preco = number_format($item['preco_unidade'], 4, ",", ".");

                    $row .=  "
                        <tr>
                            <td style='border: 1px solid #dddddd; padding-right: 20px'>{$item['codigo']}</td>
                            <td style='border: 1px solid #dddddd; padding-right: 20px'>{$marca}</td>
                            <td style='border: 1px solid #dddddd; padding-right: 20px'>{$item['quantidade_unidade']}</td>
                            <td style='border: 1px solid #dddddd; padding-right: 20px'>{$preco}</td>
                            <td style='border: 1px solid #dddddd; padding-right: 20px'>{$item['descricao']}</td>
                        </tr>
                        <tr>
                            <td colspan='6' style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Observações: </td>
                        </tr>
                    ";
                }

                $row .= "</table>";
                $rows .= $row;
                $i++;
            }

            $data = "
                <small>
                    <p>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Numero da Cotação:</strong> {$cotacao['cd_cotacao']}</label><br>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Empresa:</strong> {$dados['cliente']['razao_social']}</label><br>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Situação:</strong> Em Andamento </label>
                    </p>
                    <p>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Data e Hora de Início:</strong> {$data_inicio} </label>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Data e Hora de Término:</strong> {$data_fim} </label>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Data de Validade:</strong> {$data_validade} </label>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Data de Envio:</strong> {$data_envio} </label>
                    </p>
                    <hr>
                    <strong>Condições de Pagamento: </strong> {$condicao_pagamento} <br>
                    <strong>Valor mínimo do pedido por entrega (R$):</strong> {$valor_minimo} <br>
                    <strong>Prazo de entrega (dias):</strong> {$dados['prazo_entrega']} <br>
                    <strong>Observações:</strong> {$dados['observacao']} <br>
                    <hr>
                    
                    {$rows}
                   
                </small>
            ";  
        } else {

            $i = 1;
            $rows = "";

            foreach ($dados['rows'] as $produto) {

                $preco = number_format($produto['preco_unidade'], 4, ",", ".");

                $row = "
                <table style='width:100%; border: 1px solid #dddddd; border-collapse: collapse'>
                <tr>
                    <td style='border: 1px solid #dddddd' colspan='2'>{$i}. {$produto['ds_produto_comprador']}</td>
                    <td style='border: 1px solid #dddddd' colspan='2'><strong>Qtde Solicitada:</strong> {$produto['qt_produto_total']}</td>
                    <td style='border: 1px solid #dddddd' colspan='1'><strong>Und. Compra:</strong> {$produto['ds_unidade_compra']}</td>
                </tr>
                <tr>
                    <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Código</th>
                    <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Marca</th>
                    <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Embalagem</th>
                    <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Preço</th>
                    <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Descrição</th>
                </tr>
                <tr>
                    <td style='border: 1px solid #dddddd; padding-right: 20px'>{$produto['codigo']}</td>
                    <td style='border: 1px solid #dddddd; padding-right: 20px'>{$produto['marca']}</td>
                    <td style='border: 1px solid #dddddd; padding-right: 20px'>{$produto['quantidade_unidade']}</td>
                    <td style='border: 1px solid #dddddd; padding-right: 20px'>{$preco}</td>
                    <td style='border: 1px solid #dddddd; padding-right: 20px'>{$produto['apresentacao']}</td>
                </tr>
                <tr>
                    <td colspan='6' style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Observações: </td>
                </tr>
                ";


                $row .= "</table>";
                $rows .= $row;
                $i++;
            }

            $data = "
                <small>
                    <p>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Numero da Cotação:</strong> {$cotacao['cd_cotacao']}</label><br>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Empresa:</strong> {$dados['cliente']['razao_social']}</label><br>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Situação:</strong> Em Andamento </label>
                    </p>
                    <p>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Data e Hora de Início:</strong> {$data_inicio} </label>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Data e Hora de Término:</strong> {$data_fim} </label>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Data de Validade:</strong> {$data_validade} </label>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Data de Envio:</strong> {$data_envio} </label>
                    </p>
                    <hr>
                    <strong>Condições de Pagamento: </strong> {$condicao_pagamento} <br>
                    <strong>Valor mínimo do pedido por entrega (R$):</strong> {$valor_minimo} <br>
                    <strong>Prazo de entrega (dias):</strong> {$dados['prazo_entrega']} <br>
                    <strong>Observações:</strong> {$dados['observacao']} <br>
                    <hr>
                    
                    {$rows}
                   
                </small>
            ";
        }

        return $data;
    }

    public function exportar()
    {
        $this->db->select("
            cd_cotacao, 
            cnpj_comprador AS comprador,
            data_cotacao,
            FORMAT(valor_total, 4 , 'de_DE') AS valor_total");
        $this->db->from("vw_cotacoes");
        $this->db->where('nivel', 2);
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);
        $this->db->order_by("data_cotacao DESC");

        $query = $this->db->get()->result_array();

         if (count($query) < 1 ) {
           $query[] = [
                'cd_cotacao' => '',
                'comprador' => '',
                'data_cotacao' => '',
                'valor_total' => ''
           ];
        } else {
            foreach ($query as $k => $row) {
                
                $comprador = $this->db->select("razao_social, cnpj")->where("cnpj", $row['comprador'])->get('compradores')->row_array();

                $data = date('d/m/Y H:i:s', strtotime($row['data_cotacao']));

                $query[$k]['comprador'] = "{$comprador['razao_social']} - {$comprador['cnpj']}";
                $query[$k]['data_cotacao'] = $data;
            }
        }

        $dados_page = ['dados' => $query, 'titulo' => 'Cotacoes'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }

    public function exportar_detalhes($cd_cotacao)
    {
        $this->db->select("
            CASE WHEN pc.descricao is null THEN CONCAT(pc.nome_comercial, ' - ', pc.apresentacao) ELSE CONCAT(pc.nome_comercial, ' - ', pc.descricao) END AS produto,
            pc.marca AS marca_oferta,
            FORMAT(cp.preco_marca, 4 , 'de_DE') AS preco_oferta,
            cp.qtd_solicitada AS qtde_solicitada");
        $this->db->from("cotacoes_produtos cp");
        $this->db->join("produtos_catalogo pc", 'pc.codigo = cp.id_pfv and pc.id_fornecedor = cp.id_fornecedor', 'LEFT');
        $this->db->where('cp.cd_cotacao', $cd_cotacao);
        $this->db->where('cp.nivel', 2);
        $this->db->where('cp.submetido', 1);
        $this->db->where('cp.id_fornecedor', $this->session->id_fornecedor);
        $this->db->order_by("produto ASC");

        $query = $this->db->get()->result_array();

         if (count($query) < 1 ) {
           $query[] = [
                'produto' => '',
                'marca_oferta' => '',
                'preco_oferta' => '',
                'qtde_solicitada' => ''
           ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'Cotacoes'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }
}