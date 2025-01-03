<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cotacoes_mix extends Admin_controller
{
    private $route;
    private $views;
    private $oncoprod;
    private $DB_COTACAO;
    private $MIX;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('admin/mix/cotacoes_mix');
        $this->views = "admin/mix/cotacoes_mix";

        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_estados', 'estado');
        $this->load->model('m_marca', 'marca');
        $this->load->model('m_compradores', 'comprador');
        $this->load->model('m_cotacoes', 'cotacoes');
        $this->load->model('m_forma_pagamento', 'forma_pagamento');
        $this->load->model('m_encontrados_sintese', 'DEPARA');

        $this->oncoprod = explode(',', ONCOPROD);

        $this->DB_COTACAO = $this->load->database('sintese', TRUE);
        $this->MIX = $this->load->database('mix', TRUE);
    }

    /**
     * Exibe a view admin/cotacoes_manuais/main.php
     *
     * @return  view
     */
    public function index()
    {
        $page_title = 'Lista de Cotações MIX';

        # select
        $data['compradores'] = $this->comprador->find("id, CONCAT(cnpj, ' - ', razao_social) as comprador", NULL, FALSE, "razao_social ASC");

        # URLs
        $data['cotacoes'] = $this->getCotacoes();
        $data['url_detalhes'] = "{$this->route}/detalhes";

        $data['header'] = $this->template->header(['title' => $page_title ]);
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
     * Exibe a view admin/mix/cotacoes_mix/detail.php
     *
     * @param   int  ID fornecedor
     * @param   int  ID da cotação
     * @return  view
     */
    public function detalhes($id_cotacao)
    {
        # Obtem a cotação MIX
        $data['cotacao'] = $this->MIX->where('id_cotacao', $id_cotacao)->get('cotacoes')->row_array();

        # ID do comprador
        $id_cliente = $data['cotacao']['id_cliente'];

        # Codigo da cotação
        $cd_cotacao = $data['cotacao']['cd_cotacao'];

        # Obtem o objeto do comprador
        $data['comprador'] = $this->comprador->findById($id_cliente);

        # estado do comprador
        $id_estado = $this->estado->find('*', "uf = '{$data['comprador']['estado']}'", true)['id'];

        # Lista dos fornecedores prioridade do comprador
        $fornecedores = array_column($this->getFornecedores($id_cliente, $id_estado), 'id_fornecedor');

        # Ordem de compra
        $data['ordens_compra'] = $this->getOc($fornecedores, $cd_cotacao);

        # Obtem os produtos MIX combinados com seus envios 
        $data['produtos_cotacao'] = $this->getProdutos($id_cotacao);

        # Obtem os dados da cotação sintese
        $data['cotacao_sintese'] = $this->DB_COTACAO->select('*')->where_in("id_fornecedor in (" . implode(', ', $fornecedores) .")")->where('cd_cotacao', $cd_cotacao)->get('cotacoes', 1)->row_array();

        # Total de itens da oferta MIX
        $data['total_mix'] = $this->getAmountMix($cd_cotacao);

        # Total de produtos da cotação x quantidade com estoque para cada fornecedor
        $data['total_produto_estoque'] = $this->getAmountProductStock($fornecedores, $cd_cotacao);

        # Obtem a ultima data dos envios da cotação
        $data['envios_cot'] = $this->getLastCot($fornecedores, $cd_cotacao);

        # Obtem a qtd total de produtos da cotação e o nº de itens respondidos pelo aut e mix.
        $data['relatorioQtdItensCotacao'] = $this->getCountCot($fornecedores, $cd_cotacao, 1);

        # Obtem a qtd total de produtos da cotação e o nº de itens respondidos pelo aut e mix.
        $data['relatorioValorRespondido'] = $this->getTotalCotado($fornecedores, $cd_cotacao, 1);


        $data['relatorioMixRejeitados'] = $this->relatorioMixRejeitados($cd_cotacao);

        $data['relatorioMixAprovados'] = $this->relatorioMixAprovados($cd_cotacao);

        
        $data['relatorioProdutosRevertidos'] = $this->getProdutosRevertidos($fornecedores, $cd_cotacao);


        # URLs
        // $data['url_grafico_aprovado1'] = "{$this->route}/getCountCot/{$id_fornecedor}/{$cd_cotacao}";
        // $data['url_grafico_aprovado2'] = "{$this->route}/getTotalCotado/{$id_fornecedor}/{$cd_cotacao}";

        // $data['url_grafico_rejeitado1'] = "{$this->route}/getCountCot/{$id_fornecedor}/{$cd_cotacao}";
        // $data['url_grafico_rejeitado2'] = "{$this->route}/getCountCot/{$id_fornecedor}/{$cd_cotacao}";


        $log = $this->MIX->where('cd_cotacao', $cd_cotacao)->get('log_espelho')->row_array();

        if ( isset($log) && !empty($log) ) {
            
            $buttom_log =  [
                'type' => 'a',
                'id' => 'btnEspelho',
                'url' => "{$this->route}/mirror/{$cd_cotacao}/{$id_cliente}",
                'class' => 'btn-primary',
                'icone' => 'fa-eye',
                'label' => 'Log'
            ];
        } else {

            $buttom_log = null;
        }


        $page_title = "Produtos recebidos da cotação #{$cd_cotacao}";
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
                $buttom_log
            ]
        ]);
        $data['scripts'] = $this->template->scripts([

            'scripts' => ["https://www.gstatic.com/charts/loader.js"]
        ]);

        $this->load->view("{$this->views}/detail", $data);
    }   

    /**
     * Obtem as marcas dos produtos com restricoes, sem preço, sem estoque, sem de para ou rejeitado por preço do MIX
     *
     * @param - String codigo da cotação
     * @return array
     */
    public function relatorioMixRejeitados($cd_cotacao)
    {

        $data = [];

        $log = $this->MIX->where('cd_cotacao', $cd_cotacao)->get('log_espelho')->row_array(); 

        if ( isset($log) && !empty($log) ) {

            foreach (json_decode($log['json'], true) as $kk => $row) {

                $produto = $this->MIX->select('cd_cotacao, cd_produto_sintese, cd_produto_marca, ds_produto_marca, ds_marca, SUM(qt_atribuida_comprador) as qtd_solicitada, vl_preco_produto')
                    ->where('cd_cotacao', $row['cd_cotacao'])
                    ->where('cd_produto_sintese', $row['id_produto_sintese'])
                    ->group_by('cd_cotacao, cd_produto_sintese, cd_produto_marca, ds_produto_marca, ds_marca, vl_preco_produto')
                    ->get('cotacoes_produtos')
                    ->row_array();


                # Busca se o produto foi enviado
                $this->db->select('produto, id_marca, id_fornecedor, preco_marca, qtd_solicitada, data_criacao');
                $this->db->from('cotacoes_produtos');
                $this->db->where('cd_cotacao', $produto['cd_cotacao']);
                $this->db->where('id_produto', $produto['cd_produto_sintese']);
                $this->db->where('id_sintese', $produto['cd_produto_marca']);
                $this->db->where('nivel', 3);
                $produto_enviado = $this->db->get()->row_array();

                if ( !isset($produto_enviado) || empty($produto_enviado) ) {

                    # Produtos que perderam no preço
                    if ( isset($row['perdeu_mix']) && !isset($row['ganhou_usando_gordura']) ) {

                        $tipo_produto = ( isset( $row['perdeu_mix']['ofertaMixOutMarca'] ) ) ? 
                            $row['perdeu_mix']['ofertaMixOutMarca'] : 
                            $row['perdeu_mix']['ofertaMixMarca'];

                        $tipo = ( isset($row['perdeu_mix']['ofertaMixMarca']) ) ? "MARCA MARCA" : "OUTRA MARCA";

                        $produto['rejeitadosPorPreço'] = [
                            'vl_preco_produto' => $row['vl_preco_produto'],
                            'preco_final' => $tipo_produto['preco_final'],
                            'tipo' => $tipo
                        ];
                    }

                    # Produtos sem de para
                    if ( isset($row['DEPARA']) ) {

                        if ( !isset($row['DEPARA']['MARCA_MARCA']) && !isset($row['DEPARA']['OUTRA_MARCA']) ) {
                            
                            foreach ($row['DEPARA'] as $k => $fornecedores_sem_depara) {

                                $fornecedores_sem_depara['fornecedor'] = $this->fornecedor->findById($fornecedores_sem_depara['id_fornecedor'])['nome_fantasia'];

                                $produto['sem_depara'][] = $fornecedores_sem_depara;
                            }

                        } 
                    }

                    if ( isset($row['warnings']['MARCA_MARCA']) || isset($row['warnings']['OUTRA_MARCA']) ) {
                      
                        $tipo_produto = ( isset($row['warnings']['MARCA_MARCA']) ) ? $row['warnings']['MARCA_MARCA'] : $row['warnings']['OUTRA_MARCA'];

                        $tipo = ( isset($row['warnings']['MARCA_MARCA']) ) ? "MARCA MARCA" : "OUTRA MARCA";

                        foreach ($tipo_produto as $k => $pro) {

                            $prod = $this->db->select('pc.codigo, pc.nome_comercial, f.nome_fantasia')
                                ->from('produtos_catalogo pc')
                                ->join('fornecedores f', 'pc.id_fornecedor = f.id')
                                ->where('pc.codigo', $pro['codigo'])
                                ->where('pc.id_fornecedor', $pro['id_fornecedor'])
                                ->get()
                                ->row_array();
                                
                            # Sem preço
                            if ( isset($pro['preco']) ) {

                                $produto['sem_preco'][] = [
                                    'codigo' => $prod['codigo'],
                                    'produto_pharmanexo' => $prod['nome_comercial'],
                                    'fornecedor' => $prod['nome_fantasia'],
                                    'tipo' => $tipo
                                ]; 
                            }

                            if ( isset($pro['estoque']) ) {

                                $produto['sem_estoque'][] = [
                                    'codigo' => $prod['codigo'],
                                    'produto_pharmanexo' => $prod['nome_comercial'],
                                    'fornecedor' => $prod['nome_fantasia'],
                                    'tipo' => $tipo
                                ];
                            }

                            if ( isset($pro['restricoes']) ) {

                                $produto['restricoes'][] = [
                                    'codigo' => $prod['codigo'],
                                    'produto_pharmanexo' => $prod['nome_comercial'],
                                    'fornecedor' => $prod['nome_fantasia'],
                                    'tipo' => $tipo
                                ];
                            }
                        }
                    }

                    $data['produtos'][] = $produto;
                }
            }
        }

        return $data;
    }


    public function relatorioMixAprovados($cd_cotacao)
    {
        $data = [];

        $log = $this->MIX->where('cd_cotacao', $cd_cotacao)->get('log_espelho')->row_array(); 

        if ( isset($log) && !empty($log) ) {

            foreach (json_decode($log['json'], true) as $kk => $row) {

                $produto = $this->MIX->select('cd_cotacao, cd_produto_sintese, cd_produto_marca, ds_produto_marca, ds_marca, SUM(qt_atribuida_comprador) as qtd_solicitada, vl_preco_produto')
                    ->where('cd_cotacao', $row['cd_cotacao'])
                    ->where('cd_produto_sintese', $row['id_produto_sintese'])
                    ->group_by('cd_cotacao, cd_produto_sintese, cd_produto_marca, ds_produto_marca, ds_marca, vl_preco_produto')
                    ->get('cotacoes_produtos')
                    ->row_array();


                # Busca se o produto foi enviado
                $this->db->select('produto, id_marca, id_fornecedor, preco_marca, qtd_solicitada, data_criacao');
                $this->db->from('cotacoes_produtos');
                $this->db->where('cd_cotacao', $produto['cd_cotacao']);
                $this->db->where('id_produto', $produto['cd_produto_sintese']);
                $this->db->where('id_sintese', $produto['cd_produto_marca']);
                $this->db->where('nivel', 3);
                $produto_enviado = $this->db->get()->row_array();

                if ( isset($produto_enviado) && !empty($produto_enviado) ) {

                    # Produtos que ganharam com a gordura
                    if ( isset($row['perdeu_mix']) && isset($row['ganhou_usando_gordura']) ) {

                        $tipo_produto = ( isset( $row['perdeu_mix']['ofertaMixOutMarca'] ) ) ? 
                            $row['perdeu_mix']['ofertaMixOutMarca'] : 
                            $row['perdeu_mix']['ofertaMixMarca'];

                        $tipo = ( isset($row['perdeu_mix']['ofertaMixMarca']) ) ? "MARCA MARCA" : "OUTRA MARCA";

                        $produto['rejeitadosPorPreço'] = [
                            'vl_preco_produto' => $row['vl_preco_produto'],
                            'preco_final' => $tipo_produto['preco_final'],
                            'tipo' => $tipo
                        ];
                    }   

                    if ( isset($row['warnings']['MARCA_MARCA']) || isset($row['warnings']['OUTRA_MARCA']) ) {
                      
                        $tipo_produto = ( isset($row['warnings']['MARCA_MARCA']) ) ? $row['warnings']['MARCA_MARCA'] : $row['warnings']['OUTRA_MARCA'];

                        $tipo = ( isset($row['warnings']['MARCA_MARCA']) ) ? "MARCA MARCA" : "OUTRA MARCA";

                        foreach ($tipo_produto as $k => $pro) {

                            $prod = $this->db->select('pc.codigo, pc.nome_comercial, f.nome_fantasia')
                                ->from('produtos_catalogo pc')
                                ->join('fornecedores f', 'pc.id_fornecedor = f.id')
                                ->where('pc.codigo', $pro['codigo'])
                                ->where('pc.id_fornecedor', $pro['id_fornecedor'])
                                ->get()
                                ->row_array();
                                
                            # Sem preço
                            if ( isset($pro['preco']) ) {

                                $produto['sem_preco'][] = [
                                    'codigo' => $prod['codigo'],
                                    'produto_pharmanexo' => $prod['nome_comercial'],
                                    'fornecedor' => $prod['nome_fantasia'],
                                    'tipo' => $tipo
                                ]; 
                            }

                            if ( isset($pro['estoque']) ) {

                                $produto['sem_estoque'][] = [
                                    'codigo' => $prod['codigo'],
                                    'produto_pharmanexo' => $prod['nome_comercial'],
                                    'fornecedor' => $prod['nome_fantasia'],
                                    'tipo' => $tipo
                                ];
                            }

                            if ( isset($pro['restricoes']) ) {

                                $produto['restricoes'][] = [
                                    'codigo' => $prod['codigo'],
                                    'produto_pharmanexo' => $prod['nome_comercial'],
                                    'fornecedor' => $prod['nome_fantasia'],
                                    'tipo' => $tipo
                                ];
                            }
                        }
                    }

                    $data['produtos'][] = $produto;
                }
            }

        }

        return $data;
    }

    /**
     * Lista dos fornecedores de prioridade do comprador, somente eles vão participar do processo do MIX
     *
     * @return array
     */
    public function getFornecedores($id_cliente, $id_estado)
    {
        return $this->MIX
            ->group_start()
            ->where('id_cliente', $id_cliente)
            ->or_where('id_estado', $id_estado)
            ->group_end()
            ->order_by('prioridade ASC')
            ->get('fornecedores_mix_provisorio')
            ->result_array();
    }

    /**
     * Obtem os cotações MIX que possui envio para sintese
     *
     * @return array
     */
    public function getCotacoes()
    {

        # Combina as cotações do MIX com cotações que existem envios da pharmanexo
        $this->MIX->select("cot.id_cotacao, CONCAT(c.cnpj, ' - ', c.razao_social) AS comprador, cot.cd_cotacao, cp.id_cliente, cot.data_criacao");
        $this->MIX->from("cotacoes cot");
        $this->MIX->join("pharmanexo.compradores c", "c.id = cot.id_cliente");
        $this->MIX->join("pharmanexo.cotacoes_produtos cp", "cot.cd_cotacao = cp.cd_cotacao AND (cp.nivel = 1 OR cp.nivel = 2)");
        $this->MIX->group_by("cp.cd_cotacao");
        $this->MIX->order_by('cp.data_criacao DESC');
        $cotacoes = $this->MIX->get()->result_array();

        # Obtem o total
        foreach ($cotacoes as $kk => $cotacao) {
           
            # Obtem a quantidade de produtos na cotação
            $this->MIX->where('id_cotacao', $cotacao['id_cotacao']);
            $this->MIX->from('cotacoes_produtos');
            $cotacoes[$kk]['total'] = $this->MIX->count_all_results();

            $cotacoes[$kk]['data'] = date("d/m/Y H:i", strtotime($cotacao['data_criacao']));


            # Obtem a quantidade de registros enviados
            $this->db->where('cd_cotacao', $cotacao['cd_cotacao']);
            $this->db->where('nivel', 3);
            $this->db->from('cotacoes_produtos');
            $cotacoes[$kk]['total_enviado'] = $this->db->count_all_results();
        }

        return $cotacoes;
    }


    /**
     * Obtem a ordem de compra da cotação
     *
     * @param = Array de IDs dos fornecedores
     * @param = String codigo da cotação
     * @return  array/json
     */
    public function getOc($fornecedores, $cd_cotacao)
    {
            
        $data = [];

        foreach ($fornecedores as $id_fornecedor) {
            
            $fornecedor = $this->fornecedor->findById($id_fornecedor);

            $this->db->where('Cd_Cotacao', $cd_cotacao);
            $this->db->where('id_fornecedor', $id_fornecedor);
            $oc = $this->db->get('ocs_sintese')->row_array();

            if ( isset($oc) && !empty($oc) ) {

                $oc['status'] = $this->db->where('codigo', $oc['Status_OrdemCompra'])->get('ocs_sintese_status')->row_array()['descricao'];
            }
            

            $data[] = [
                'fornecedor' => $fornecedor['nome_fantasia'],
                'oc' => ( isset($oc) && !empty($oc) ) ? $oc : null
            ];
        }

        return $data;
    }


    /**
     * Obtem aq quantidade de produtos sintese e do mix
     *
     * @param = Array de IDs dos fornecedores
     * @param = String codigo da cotação
     * @return  array/json
     */
    public function getAmountMix($cd_cotacao)
    {

        return $this->MIX->select('*')
            ->from('cotacoes_produtos')
            ->where('cd_cotacao', $cd_cotacao)
            ->count_all_results();
    }

    /**
     * Obtem os produtos da cotação da oferta MIX
     *
     * @param = int ID do fornecedor
     * @param = int id_cotacao
     * @return  array
     */
    public function getProdutos($id_cotacao)
    {

        $this->MIX->select('cd_cotacao, cd_produto_sintese, cd_produto_marca, ds_produto_marca, ds_marca, complemento_produto_marca, SUM(qt_atribuida_comprador) as qtd_solicitada, vl_preco_produto');
        $this->MIX->from("cotacoes_produtos");
        $this->MIX->where("id_cotacao", $id_cotacao);
        $this->MIX->group_by('cd_cotacao, cd_produto_sintese, cd_produto_marca, ds_produto_marca, ds_marca, complemento_produto_marca, vl_preco_produto');
        $this->MIX->order_by('ds_produto_marca ASC');
        $produtos = $this->MIX->get()->result_array();

        $enviados = [];
        $rejeitados = [];

        foreach ($produtos as $kk => $produto) {
            
            # Busca se o produto foi enviado
            $this->db->select('produto, id_marca, id_fornecedor, preco_marca, qtd_solicitada, data_criacao');
            $this->db->from('cotacoes_produtos');
            $this->db->where('cd_cotacao', $produto['cd_cotacao']);
            $this->db->where('id_produto', $produto['cd_produto_sintese']);
            $this->db->where('id_sintese', $produto['cd_produto_marca']);
            $this->db->where('nivel', 3);
            $produto_enviado = $this->db->get()->row_array();

            if ( isset($produto_enviado) && !empty($produto_enviado) ) {

                # Obtem a marca do produto enviado
                $marca = $this->marca->get_row($produto_enviado['id_marca']);
                $produto_enviado['marca'] = (isset($marca)) ? $marca['marca'] : '';

                # Obtem o nome do fornecedor da oferta
                $produtos[$kk]['enviado'][] = $produto_enviado;
                $enviados[] = $produtos[$kk];
            } else {

                $produtos[$kk]['enviado'] = null;
                $rejeitados[] = $produtos[$kk];
            }
        }

        $data['enviados'] = $enviados;
        $data['rejeitados'] = $rejeitados;
        
        return $data;
    }

    public function getAmountProductStock($fornecedores, $cd_cotacao)
    {
        $data = [];

        foreach ($fornecedores as $id_fornecedor) {

             $fornecedor = $this->fornecedor->findById($id_fornecedor);
                
            $total_sintese = $this->DB_COTACAO->select('*')
                ->from('cotacoes_produtos')
                ->where('id_fornecedor', $id_fornecedor)
                ->where('cd_cotacao', $cd_cotacao)
                ->count_all_results();


            $total_com_estoque = $this->MatchProducts($cd_cotacao, $id_fornecedor);

            $data[] = [
                'fornecedor' => $fornecedor['nome_fantasia'],
                'total_sintese' => ( isset($total_sintese) && !empty($total_sintese) ) ? $total_sintese : 0,
                'total_com_estoque' => ( isset($total_com_estoque) && !empty($total_com_estoque) ) ? $total_com_estoque : 0,
            ];
        }

        return $data;
    }

    /**
     * Combina os produtos pharmanexo encontrado no depara com os produtos da SINTESE
     *
     * @param - String codigo da cotação
     * @param - Int ID do fornecedor
     * @return  function getDetailsProducts
     */
    public function MatchProducts($cd_cotacao, $id_fornecedor)
    {
        # Obtem os produtos da pharmanexo dos produtos da sintese
        $depara = $this->DEPARA->getProdutos_depara($cd_cotacao, "= {$id_fornecedor}");

        # Lista dos produtos da cotação na SINTESE
        $this->DB_COTACAO->select("id_produto_sintese");
        $this->DB_COTACAO->select("id_fornecedor");
        $this->DB_COTACAO->select("cd_produto_comprador");
        $this->DB_COTACAO->select("ds_produto_comprador");
        $this->DB_COTACAO->select("ds_unidade_compra");
        $this->DB_COTACAO->select("ds_complementar");
        $this->DB_COTACAO->select("SUM(qt_produto_total) AS qt_produto_total");
        $this->DB_COTACAO->select("cd_cotacao");
        $this->DB_COTACAO->where('cd_cotacao', $cd_cotacao);
        $this->DB_COTACAO->where('id_fornecedor', $id_fornecedor);
        $this->DB_COTACAO->group_by('id_produto_sintese, cd_produto_comprador');
        $produtos_cotacao = $this->DB_COTACAO->get('cotacoes_produtos')->result_array();

        $produtos = [];

        # Faz a combinação dos produtos Pharmanexo x Sintese
        foreach ($produtos_cotacao as $produto) {

            $encontrados = [];

            if ( isset($depara) && !empty($depara) ) {
                
                foreach ($depara as $prod) {

                    if ( $prod['id_produto'] == $produto['id_produto_sintese'] ) {

                        $encontrados[] = $prod;
                    }
                }
            }

            $produtos[] = [
                'cotado' => $produto,
                'encontrados' => $encontrados,
            ];
        }
       
        return $this->getCountProductsStock($produtos);
    }

    /**
     * Obtem a quantidade de itens da cotalão com algum estoque
     *
     * @param - Array de produtos
     * @return  int 
     */
    public function getCountProductsStock($produtos)
    {
        $total = 0;

        foreach ($produtos as $kk => $produto) {
                
            if ( isset($produto['encontrados']) && !empty($produto['encontrados']) ) {
                
               foreach ($produto['encontrados'] as $k => $p) {
                    
                    # Obtem o estoque
                    $estoque = $this->getStock($p['codigo'], $p['id_fornecedor']);

                    if ($estoque > 0) {

                        $total++;
                        break;
                    }
               }
            }
        }

        return $total;
    }

    public function getStock($codigo, $id_fornecedor)
    {

        $this->db->select("quantidade_unidade");
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('codigo', $codigo);
        
        $qtd_unidade = $this->db->get('produtos_catalogo')->row_array()['quantidade_unidade'];

        if ( isset($qtd_unidade) &&  $qtd_unidade > 0 ) {

            $this->db->select("( SUM(estoque) * {$qtd_unidade} )  AS estoque");
        } else {

            $this->db->select(" (SUM(estoque)) AS estoque");
        }

        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('codigo', $codigo);
        $estoque = $this->db->get('produtos_lote')->row_array()['estoque'];

        return $estoque;
    }

    /**
     * Obtem o valor total cotado via aut e mix
     *
     * @param = Array de IDs dos fornecedores
     * @param = String codigo da cotação
     * @param = Bool - modifica o retorno da função
     * @return  array/json
     */
    public function getTotalCotado($fornecedores, $cd_cotacao, $return = null)
    {

        $data = [];

        foreach ($fornecedores as $id_fornecedor) {

            $fornecedor = $this->fornecedor->findById($id_fornecedor);

            # Valor Total Manual
            $valor_total_manual = $this->db
                ->select("SUM( (preco_marca * qtd_solicitada) ) AS valor_total")
                ->where('cd_cotacao', $cd_cotacao)
                ->where('nivel', 1)
                ->where('submetido', 1)
                ->where('id_fornecedor', $id_fornecedor)
                ->get('cotacoes_produtos')
                ->row_array()['valor_total'];

            # Obtem Valor total da automatica
            $valor_total_automatica = $this->db
                ->select("SUM( (preco_marca * qtd_solicitada) ) AS valor_total")
                ->where('cd_cotacao', $cd_cotacao)
                ->where('nivel', 2)
                ->where('submetido', 1)
                ->where('id_fornecedor', $id_fornecedor)
                ->get('cotacoes_produtos')
                ->row_array()['valor_total'];

            # Valor Total mix
            $valor_total_mix = $this->db
                ->select("SUM( (preco_marca * qtd_solicitada) ) AS valor_total")
                ->where('cd_cotacao', $cd_cotacao)
                ->where('nivel', 3)
                ->where('submetido', 1)
                ->where('id_fornecedor', $id_fornecedor)
                ->get('cotacoes_produtos')
                ->row_array()['valor_total'];

            $data[] = [
                'fornecedor' => $fornecedor['nome_fantasia'],
                'total_manual' => $valor_total_manual,
                'total_automatica' => $valor_total_automatica,
                'total_mix' => $valor_total_mix
            ];
        }


        if (isset($return)) {

            return $data;
        } else {

            $data = [
                ['Cotações', 'Total'],
                ['Manual', floatval($valor_total_manual)],
                ['Automática', floatval($valor_total_automatica)],
                ['Mix', floatval($valor_total_mix)]
            ];

            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    /**
     * Obtem a qtd de registros da cotação repondidos via aut e mix
     *
     * @param = Array de IDs dos fornecedores
     * @param = String codigo da cotação
     * @param = Bool - modifica o retorno da função
     * @return  array/json
     */
    public function getCountCot($fornecedores, $cd_cotacao, $return = null)
    {

        $data = [];

        foreach ($fornecedores as $id_fornecedor) {

            $fornecedor = $this->fornecedor->findById($id_fornecedor);
            
            # Numero de itens respondidos pela Manual
            $this->db->where('cd_cotacao', $cd_cotacao);
            $this->db->where('id_fornecedor', $id_fornecedor);
            $this->db->where('nivel', 1);
            $this->db->where('submetido', 1);
            $this->db->from('cotacoes_produtos');
            $total_manual = $this->db->count_all_results();

            # Numero de itens respondidos pela aut
            $this->db->where('cd_cotacao', $cd_cotacao);
            $this->db->where('id_fornecedor', $id_fornecedor);
            $this->db->where('nivel', 2);
            $this->db->where('submetido', 1);
            $this->db->from('cotacoes_produtos');
            $total_aut = $this->db->count_all_results();


            # Numero de itens respondidos pelo mix
            $this->db->where('cd_cotacao', $cd_cotacao);
            $this->db->where('id_fornecedor', $id_fornecedor);
            $this->db->where('nivel', 3);
            $this->db->from('cotacoes_produtos');
            $total_mix = $this->db->count_all_results();

            $data[] = [
                'fornecedor' => $fornecedor['nome_fantasia'],
                'total_manual' => $total_manual,
                'total_automatica' => $total_aut,
                'total_mix' => $total_mix,
            ];
        }

        if (isset($return)) {

            return $data;
        } else {

            $data = [
                ['Cotações', 'Numero de registros'],
                ['Manual', $total_manual],
                ['Automática', $total_aut],
                ['Mix', $total_mix]
            ];

            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function getProdutosRevertidos($fornecedores, $cd_cotacao)
    {
        $data = [];
       
        foreach ($fornecedores as $id_fornecedor) {

            $fornecedor = $this->fornecedor->findById($id_fornecedor);
            
            $query = "
                SELECT 
                    cot.id_pfv,
                    cot.preco_marca preco_mix,
                    cot2.preco_marca preco_pharmanexo
                FROM pharmanexo.cotacoes_produtos cot
                JOIN pharmanexo.cotacoes_produtos cot2
                    ON cot2.cd_cotacao = cot.cd_cotacao
                        AND cot2.id_pfv = cot.id_pfv
                        AND cot2.id_fornecedor = cot.id_fornecedor
                        AND cot2.nivel IN (1, 2)
                WHERE cot.cd_cotacao = '{$cd_cotacao}'
                    AND cot.id_fornecedor = {$id_fornecedor}
                    AND cot.nivel = 3
                    AND cot.submetido = 1
                GROUP BY cot.id_pfv
            ";

            $itens = $this->db->query($query)->result_array();

            $data[] = [
                'fornecedor' => $fornecedor['nome_fantasia'],
                'total' =>  (isset($itens) && !empty($itens)) ? count($itens) : 0
            ];
        }

        return $data;
    }

    /**
     * Obtem qual cotação foi enviada pela pharmanexo
     *
     * @param = String codigo da cotação
     * @return  array
     */
    public function getLastCot($fornecedores, $cd_cotacao)
    {
        $data = [];

        foreach ($fornecedores as $id_fornecedor) {
            
            $fornecedor = $this->fornecedor->findById($id_fornecedor);

            # manual
            $this->db->select("MAX(data_criacao) as data_criacao");
            $this->db->where("id_fornecedor", $id_fornecedor);
            $this->db->where('cd_cotacao', $cd_cotacao);
            $this->db->where("submetido", 1);
            $this->db->where_in("nivel", 1);
            $data_manual = $this->db->get('cotacoes_produtos')->row_array();

            # Automatica
            $this->db->select("MAX(data_criacao) as data_criacao");
            $this->db->where("id_fornecedor", $id_fornecedor);
            $this->db->where('cd_cotacao', $cd_cotacao);
            $this->db->where("submetido", 1);
            $this->db->where_in("nivel", 2);
            $data_aut = $this->db->get('cotacoes_produtos')->row_array();

             # Automatica
            $this->db->select("MAX(data_criacao) as data_criacao");
            $this->db->where("id_fornecedor", $id_fornecedor);
            $this->db->where('cd_cotacao', $cd_cotacao);
            $this->db->where("submetido", 1);
            $this->db->where_in("nivel", 3);
            $data_mix = $this->db->get('cotacoes_produtos')->row_array();


            $data[] = [
                'fornecedor' => $fornecedor['nome_fantasia'],
                'data_manual' => ( isset($data_manual) && !empty($data_manual) ) ? $data_manual['data_criacao'] : '',
                'data_aut' => ( isset($data_aut) && !empty($data_aut) ) ? $data_aut['data_criacao'] : '',
                'data_mix' => ( isset($data_mix) && !empty($data_mix) ) ? $data_mix['data_criacao'] : ''
            ];
        }
        
        return $data;
    }

    public function mirror($cd_cotacao, $id_cliente)
    {

        # Comprador 
        $cliente = $this->comprador->findById($id_cliente);

        $estado = $this->estado->find('*', "uf = '{$cliente['estado']}'", true);

        # Log da cotação
        $log = $this->MIX->where('cd_cotacao', $cd_cotacao)->get('log_espelho')->row_array();

        $data = [];


        foreach (json_decode($log['json'], true) as $kk => $row) {

            # Nome do produto sintese
            $produto = $this->MIX->select('cd_produto_sintese, cd_produto_marca, ds_produto_marca, ds_marca, SUM(qt_atribuida_comprador) as qtd_solicitada, vl_preco_produto')
                ->where('cd_cotacao', $row['cd_cotacao'])
                ->where('cd_produto_sintese', $row['id_produto_sintese'])
                ->group_by('cd_produto_sintese, cd_produto_marca, ds_produto_marca, ds_marca, vl_preco_produto')
                ->get('cotacoes_produtos')
                ->row_array();

            $row['produto'] = $produto;
            $data[] = $row;
        }

        $body = $this->createBody($data, $id_cliente, $estado['id']);

        // var_dump($body); exit();

        ob_end_clean();

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($body);
        $mpdf->Output("Espelho_mix.pdf", 'D');
    }

    public function createBody($data, $id_cliente, $id_estado)
    {
       # Cabeçalho

        $comprador = $this->comprador->findById($id_cliente);
        $estado = $this->estado->findById($id_estado);

        $row = "
            <table style='width:100%; border: 1px solid #dddddd; border-collapse: collapse;'>
                <tr style='font-size: 14px;'>
                    <th style='text-align: left'>Comprador</th>
                    <th style='text-align: center'>Estado</th>
                    <th colspan='2'></th>
                </tr>
                <tr style='font-size: 14px;'>
                    <td style='text-align: left'>{$comprador['cnpj']} - {$comprador['razao_social']}</td>
                    <td style='text-align: center'>{$estado['uf']}</td>
                    <td colspan='2'></td>
                </tr>
            </table>
        ";

        $row .= "<br><br>";

        $i = 1;

        foreach ($data as $kk => $produto) {


            $row .= "<table style='width:100%; border: 1px solid #dddddd; border-collapse: collapse;'>";

            $preco = number_format($produto['produto']['vl_preco_produto'], 4, ',', '.');
            $nome_produto = strtoupper($produto['produto']['ds_produto_marca']);
          
            $row .= "
                <tr>
                    <td colspan='2' style='border: 1px solid #dddddd'><strong>{$i}.</strong> {$nome_produto}</td>
                    <td colspan='2' style='border: 1px solid #dddddd'><strong>Marca:.</strong> {$produto['produto']['ds_marca']}</td>
                </tr>
                <tr>
                    <th style='text-align: left'><small>ID Produto</small></th>
                    <th style='text-align: left'><small>ID Sintese</small></th>
                    <th style='text-align: left'><small>Qtde Solicitada</small></th>
                    <th style='text-align: left'><small>Preço</small></th>
                </tr>
                <tr>
                    <td style='text-align: left'><small>{$produto['produto']['cd_produto_sintese']}</small></td>
                    <td style='text-align: left'><small>{$produto['produto']['cd_produto_marca']}</small></td>
                    <td style='text-align: left'><small>{$produto['produto']['qtd_solicitada']}</small></td>
                    <td style='text-align: left'><small>{$preco}</small></td>
                </tr>
                <br>
            ";

            
            if ( isset($produto['warnings']['fornecedor_pharmanexo']) ) {

                $fornecedor = $this->fornecedor->findById($produto['id_fornecedor_oferta'])['nome_fantasia'];

                $row .= "
                    <tr>
                        <td colspan='4'>Produto não processado pelo MIX! Fornecedor {$fornecedor} ganhador.</td>
                    </tr>
                ";
            } else {

                # Passo 1 - Verificar marca do produto e outras marcas

                $row .= "
                    <tr>
                        <td colspan='4' style='border: 1px solid #dddddd; background-color: #C0C0C0'><small># REALIZANDO DE -> PARA DO PRODUTO...</small></td>
                    </tr>
                ";

                if ( !isset($produto['DEPARA']['MARCA_MARCA']) && !isset($produto['DEPARA']['OUTRA_MARCA']) ) {

                    foreach ($produto['DEPARA'] as $kk => $warning) {

                        $fornecedor = $this->fornecedor->findById($warning['id_fornecedor'])['nome_fantasia'];
                       
                        $row .= "
                            <tr>
                                <td colspan='4'>O fornecedor: {$fornecedor} não possui De -> Para.</td>
                            </tr>
                        ";
                    }
                } else {

                    $row .= "
                        <tr>
                            <td colspan='4' style='border: 1px solid #dddddd; background-color: #C0C0C0'># BUSCANDO MARCA SOLICITADA...</td>
                        </tr>
                    ";

                    if ( isset($produto['DEPARA']['MARCA_MARCA']) ) {
                       
                        $row .= "
                            <tr>
                                <td colspan='4'>Marca solicitada encontrada</td>
                            </tr>
                        ";

                        // $marca = $produto['DEPARA']['MARCA_MARCA'][0];

                        // $prod = $this->db->select("pc.codigo, pc.nome_comercial, pc.marca, f.nome_fantasia")
                        //     ->from('produtos_catalogo pc')
                        //     ->join('fornecedores f', 'f.id = pc.id_fornecedor')
                        //     ->where('pc.codigo', $marca['codigo'])
                        //     ->where('pc.id_fornecedor', $marca['id_fornecedor'])  
                        //     ->get()
                        //     ->row_array();  

                        // $row .= "
                        //     <tr>
                        //         <th colspan='2'>Código</th>
                        //         <th colspan='2'>Produto</th>
                        //         <th colspan='2'>Marca</th>
                        //         <th colspan='2'>Fornecedor</th>
                        //     </tr>
                        //     <tr>
                        //         <td colspan='2'>{$prod['codigo']}</td>
                        //         <td colspan='2'>{$prod['nome_comercial']}</td>
                        //         <td colspan='2'>{$prod['marca']}</td>
                        //         <td colspan='2'>{$prod['nome_fantasia']}</td>
                        //     </tr>
                        // ";
                    } else {

                        $row .= "
                            <tr>
                                <td colspan='4'>Marca solicitada não encontrada</td>
                            </tr>
                        ";
                    }

                    $row .= "
                        <tr>
                            <td colspan='4' style='border: 1px solid #dddddd; background-color: #C0C0C0'># BUSCANDO OUTRAS MARCAS...</td>
                        </tr>
                    ";

                    if ( isset($produto['DEPARA']['OUTRA_MARCA']) ) {

                        $qtd_marcas = count($produto['DEPARA']['OUTRA_MARCA']);
                        
                        $row .= "
                            <tr>
                                <td colspan='4'>Foram encontrado {$qtd_marcas} marcas.</td>
                            </tr>
                        ";

                        // $outras_marcas = $produto['DEPARA']['OUTRA_MARCA'];

                        // foreach ($outras_marcas as $marca) {
                           
                        //     $prod = $this->db->select("pc.codigo, pc.nome_comercial, pc.marca, f.nome_fantasia")
                        //         ->from('produtos_catalogo pc')
                        //         ->join('fornecedores f', 'f.id = pc.id_fornecedor')
                        //         ->where('pc.codigo', $marca['codigo'])
                        //         ->where('pc.id_fornecedor', $marca['id_fornecedor'])  
                        //         ->get()
                        //         ->row_array();  

                        //     $row .= "
                        //         <tr>
                        //             <td colspan='2'>{$prod['codigo']}</td>
                        //             <td colspan='2'>{$prod['nome_comercial']}</td>
                        //             <td colspan='2'>{$prod['marca']}</td>
                        //             <td colspan='2'>{$prod['nome_fantasia']}</td>
                        //         </tr>
                        //     ";
                        // }
                    } else {

                        $row .= "
                            <tr>
                                <td colspan='4'>Não foram encontrada outras marcas.</td>
                            </tr>
                        ";
                    }
                }

                # Passo 2 - Analise das marcas encontradas (restrições, preço, estoque)

                if ( isset($produto['warnings']['MARCA_MARCA']) ) {

                    $row .= "
                        <tr>
                            <td colspan='4' style='border: 1px solid #dddddd; background-color: #C0C0C0'># ANÁLISE DA MARCA SOLICITADA ENCONTRADA...</td>
                        </tr>
                        <tr style='font-size: 14px;'>
                            <th style='text-align: left'>Código</th>
                            <th style='text-align: left'>Produto</th>
                            <th style='text-align: left'>Marca</th>
                            <th style='text-align: left'>Fornecedor</th>
                            <th colspan='4' ></th>
                        </tr>
                    ";

                    $warning_marca_marca = $produto['warnings']['MARCA_MARCA'][0];

                    $prod = $this->db->select("pc.codigo, pc.nome_comercial, pc.marca, f.nome_fantasia")
                    ->from('produtos_catalogo pc')
                    ->join('fornecedores f', 'f.id = pc.id_fornecedor')
                    ->where('pc.codigo', $warning_marca_marca['codigo'])
                    ->where('pc.id_fornecedor', $warning_marca_marca['id_fornecedor'])  
                    ->get()
                    ->row_array();  

                    if ( isset($warning_marca_marca['restricoes']) ) {

                        $row .= "
                            <tr style='font-size: 14px;'>
                                <td style='text-align: left'><small>{$prod['codigo']}</small></td>
                                <td style='text-align: left'><small>{$prod['nome_comercial']}</small></td>
                                <td style='text-align: left'><small>{$prod['marca']}</small></td>
                                <td style='text-align: left'><small>{$prod['nome_fantasia']}</small></td>
                                <td colspan='4' ></td>
                            </tr>
                            <tr>
                                <td colspan='4' style='background-color: #FF6347'>Marca excluída por conter restrição para comprador ou por estado.</td>
                            </tr>
                        ";
                    } elseif( isset($warning_marca_marca['preco']) ) {

                        $row .= "
                            <tr style='font-size: 14px;'>
                                <td style='text-align: left'><small>{$prod['codigo']}</small></td>
                                <td style='text-align: left'><small>{$prod['nome_comercial']}</small></td>
                                <td style='text-align: left'><small>{$prod['marca']}</small></td>
                                <td style='text-align: left'><small>{$prod['nome_fantasia']}</small></td>
                                <td colspan='4' ></td>
                            </tr>
                            <tr>
                                <td colspan='4' style='background-color: #FF6347'>Marca excluída por não existir preço.</td>
                            </tr>
                        ";
                    } elseif( isset($warning_marca_marca['estoque']) ) {

                        $row .= "
                            <tr style='font-size: 14px;'>
                                <td style='text-align: left'><small>{$prod['codigo']}</small></td>
                                <td style='text-align: left'><small>{$prod['nome_comercial']}</small></td>
                                <td style='text-align: left'><small>{$prod['marca']}</small></td>
                                <td style='text-align: left'><small>{$prod['nome_fantasia']}</small></td>
                                <td colspan='4' ></td>
                            </tr>
                            <tr>
                                <td colspan='4' style='background-color: #FF6347'>Marca excluída por não existir estoque.</td>
                            </tr>
                        ";
                    } 
                }   
            
                if ( isset($produto['warnings']['OUTRA_MARCA']) ) {

                    $row .= "
                        <tr>
                            <td colspan='4' style='border: 1px solid #dddddd; background-color: #C0C0C0'># ANÁLISE DAS OUTRAS MARCAS ENCONTRADA...</td>
                        </tr>
                        <tr style='font-size: 14px;'>
                            <th style='text-align: left'>Código</th>
                            <th style='text-align: left'>Produto</th>
                            <th style='text-align: left'>Marca</th>
                            <th style='text-align: left'>Fornecedor</th>
                            <th colspan='4'></th>
                        </tr>
                    ";

                    $warning_outras_marcas = $produto['warnings']['OUTRA_MARCA'];

                    foreach ($warning_outras_marcas as $k => $warning_outra_marca) {

                        $prod = $this->db->select("pc.codigo, pc.nome_comercial, pc.marca, f.nome_fantasia")
                            ->from('produtos_catalogo pc')
                            ->join('fornecedores f', 'f.id = pc.id_fornecedor')
                            ->where('pc.codigo', $warning_outra_marca['codigo'])
                            ->where('pc.id_fornecedor', $warning_outra_marca['id_fornecedor'])  
                            ->get()
                            ->row_array();  

                        if ( isset($warning_outra_marca['restricoes']) ) {

                            $row .= "
                                <tr style='font-size: 14px;'>
                                    <td style='text-align: left'><small>{$prod['codigo']}</small></td>
                                    <td style='text-align: left'><small>{$prod['nome_comercial']}</small></td>
                                    <td style='text-align: left'><small>{$prod['marca']}</small></td>
                                    <td style='text-align: left'><small>{$prod['nome_fantasia']}</small></td>
                                    <td colspan='4'></td>
                                </tr>
                                <tr>
                                    <td colspan='4' style='background-color: #FF6347'>Marca excluída por conter restrição para comprador ou por estado.</td>
                                </tr>
                            ";
                        } elseif( isset($warning_outra_marca['preco']) ) {

                            $row .= "
                                <tr style='font-size: 14px;'>
                                    <td style='text-align: left'><small>{$prod['codigo']}</small></td>
                                    <td style='text-align: left'><small>{$prod['nome_comercial']}</small></td>
                                    <td style='text-align: left'><small>{$prod['marca']}</small></td>
                                    <td style='text-align: left'><small>{$prod['nome_fantasia']}</small></td>
                                    <td colspan='4'></td>
                                </tr>
                                <tr>
                                    <td colspan='4' style='background-color: #FF6347'>Marca excluída por não existir preço.</td>
                                </tr>
                            ";
                        } elseif( isset($warning_outra_marca['estoque']) ) {

                            $row .= "
                                <tr style='font-size: 14px;'>
                                    <td style='text-align: left'><small>{$prod['codigo']}</small></td>
                                    <td style='text-align: left'><small>{$prod['nome_comercial']}</small></td>
                                    <td style='text-align: left'><small>{$prod['marca']}</small></td>
                                    <td style='text-align: left'><small>{$prod['nome_fantasia']}</small></td>
                                    <td colspan='4'></td>
                                </tr>
                                <tr>
                                    <td colspan='4' style='background-color: #FF6347'>Marca excluída por não existir estoque.</td>
                                </tr>
                            ";
                        } 
                    }
                }


                # Passo 3 - marcas que passaram na analise

                if ( isset($produto['success']) ) {

                    $row .= "
                        <tr>
                            <td colspan='4' style='border: 1px solid #dddddd; background-color: #C0C0C0'># PRODUTO ESCOLHIDO</td>
                        </tr>
                        <tr style='font-size: 14px;'>
                            <th style='text-align: left'>Código</th>
                            <th style='text-align: left'>Produto</th>
                            <th style='text-align: left'>Marca</th>
                            <th style='text-align: left'>Fornecedor</th>
                            <td colspan='4'></td>
                        </tr>
                    ";

                    if ( isset($produto['success']['MARCA_MARCA']) ) {
                        
                        $warning = $produto['success']['MARCA_MARCA'];

                        $prod = $this->db->select("pc.codigo, pc.nome_comercial, pc.marca, f.nome_fantasia")
                            ->from('produtos_catalogo pc')
                            ->join('fornecedores f', 'f.id = pc.id_fornecedor')
                            ->where('pc.codigo', $warning['codigo'])
                            ->where('pc.id_fornecedor', $warning['id_fornecedor'])  
                            ->get()
                            ->row_array();  

                        $row .= "
                            <tr style='font-size: 14px;'>
                                <td style='text-align: left'><small>{$prod['codigo']}</small></td>
                                <td style='text-align: left'><small>{$prod['nome_comercial']}</small></td>
                                <td style='text-align: left'><small>{$prod['marca']}</small></td>
                                <td style='text-align: left'><small>{$prod['nome_fantasia']}</small></td>
                                <td colspan='4'></td>
                            </tr>
                        ";
                    } else {

                        $warning = $produto['success']['OUTRA_MARCA'];

                        $prod = $this->db->select("pc.codigo, pc.nome_comercial, pc.marca, f.nome_fantasia")
                            ->from('produtos_catalogo pc')
                            ->join('fornecedores f', 'f.id = pc.id_fornecedor')
                            ->where('pc.codigo', $warning['codigo'])
                            ->where('pc.id_fornecedor', $warning['id_fornecedor'])  
                            ->get()
                            ->row_array();  

                        $row .= "
                            <tr style='font-size: 14px;'>
                                <td style='text-align: left'><small>{$prod['codigo']}</small></td>
                                <td style='text-align: left'><small>{$prod['nome_comercial']}</small></td>
                                <td style='text-align: left'><small>{$prod['marca']}</small></td>
                                <td style='text-align: left'><small>{$prod['nome_fantasia']}</small></td>
                                <td colspan='4'></td>
                            </tr>
                        ";
                    }
                } 

                # Passo 4 - Analisar os produtos aprovados na analise 

                if ( isset($produto['ganhou_mix']) ) {

                    if ( isset($produto['ganhou_mix']['ofertaMixMarca']) ) {
                        
                        $prod = $produto['success']['MARCA_MARCA'];
                        $preco_final = $produto['ganhou_mix']['ofertaMixMarca']['preco_final'];
                        $tipo = 'MARCA MARCA';
                    } else {

                        $prod = $produto['success']['OUTRA_MARCA'];
                        $preco_final = $produto['ganhou_mix']['ofertaMixoOutMarca']['preco_final'];
                        $tipo = 'OUTRA MARCA';
                    }

                    $fornecedor = $this->fornecedor->findById($prod['id_fornecedor'])['nome_fantasia'];


                    $p = $this->db->select('*')
                        ->where('codigo', $prod['codigo'])
                        ->where('id_fornecedor', $prod['id_fornecedor'])
                        ->get('produtos_catalogo')
                        ->row_array();

                    $preco_enviado = number_format($preco_final, 4, ',', '.');
                    $marca = $this->marca->get_row($p['id_marca'])['marca'];
                     
                    $row .= "
                        <tr>
                            <td style='border: 1px solid #dddddd; background-color: #C0C0C0' colspan='4'>MARCA GANHHADORA MIX</td>
                        </tr>
                        <tr style='font-size: 14px;'>
                            <th style='text-align: left'>Código - Produto</th>
                            <th style='text-align: left'>Marca</th>
                            <th style='text-align: left'>Fornecedor</th>
                            <th style='text-align: left'>Preço enviado (R$)</th>
                        </tr>
                        <tr style='font-size: 14px;'>
                            <td style='text-align: left'><small>{$prod['codigo']} - {$p['nome_comercial']}</small></td>
                            <td style='text-align: left'><small>{$marca}</small></td>
                            <td style='text-align: left'><small>{$fornecedor}</small></td>
                            <td style='text-align: left'><small>{$preco_enviado}</small></td>
                        </tr>
                    ";
                }  elseif( isset($produto['perdeu_mix']) ) {

                    $row .= "
                        <tr>
                            <td colspan='4' style='border: 1px solid #dddddd; background-color: #C0C0C0'># MARCA REJEITADA NO PROCESSAMENTO MIX </td>
                        </tr>
                        <tr style='font-size: 14px;'>
                            <th style='text-align: left'>Código - Produto</th>
                            <th style='text-align: left'>Marca</th>
                            <th style='text-align: left'>Fornecedor</th>
                            <th style='text-align: left'>Preço Enviado (R$)</th>
                        </tr>
                    ";

                    if ( isset($produto['perdeu_mix']['ofertaMixMarca']) ) {
                        
                        $prod = $produto['success']['MARCA_MARCA'];
                        $preco_final = $produto['perdeu_mix']['ofertaMixMarca']['preco_final'];
                        $tipo = 'MARCA MARCA';
                    } else {

                        $prod = $produto['success']['OUTRA_MARCA'];
                        $preco_final = $produto['perdeu_mix']['ofertaMixOutMarca']['preco_final'];
                        $tipo = 'OUTRA MARCA';
                    }


                    $p = $this->db->select('*')
                        ->where('codigo', $prod['codigo'])
                        ->where('id_fornecedor', $prod['id_fornecedor'])
                        ->get('produtos_catalogo')
                        ->row_array();

                    $fornecedor = $this->fornecedor->findById($prod['id_fornecedor'])['nome_fantasia'];

                    $preco_enviado = number_format($preco_final, 4, ',', '.');
                    $marca = $this->marca->get_row($p['id_marca'])['marca'];

                    $row .= "
                        <tr style='font-size: 14px;'>
                            <td style='text-align: left'><small>{$prod['codigo']} - {$p['nome_comercial']}</small></td>
                            <td style='text-align: left'><small>{$marca}</small></td>
                            <td style='text-align: left'><small>{$fornecedor}</small></td>
                            <td style='text-align: left'><small>{$preco_enviado}</small></td>
                        </tr>
                    ";


                    # gordura
                    if ( isset($produto['ganhou_usando_gordura']) ) {

                        $row .= "
                            <tr>
                                <td colspan='4' style='border: 1px solid #dddddd; background-color: #C0C0C0'># REPROCESSAMENTO DA MARCA</td>
                            </tr>
                        ";

                        if ( isset($produto['ganhou_usando_gordura']['ofertaMixMarca']) ) {
                        
                            $prod = $produto['ganhou_usando_gordura']['ofertaMixMarca'];
                            $tipo = 'MARCA MARCA';
                        } else {

                            $prod = $produto['ganhou_usando_gordura']['ofertaMixOutMarca'];
                            $tipo = 'OUTRA MARCA';
                        }

                        $desconto = $prod['gordura_utilizada'];
                        $novo_preco = $prod['preco_final'] - $desconto;
                        $preco = number_format($novo_preco, 4, ',', '.');

                        $row .= "
                            <tr>
                                <td style='border: 1px solid #dddddd; background-color: #C0C0C0' colspan='4'># PRODUTO APROVADO USANDO GORDURA</td>
                            </tr>
                            <tr style='font-size: 14px;'>
                                <th style='text-align: left'>Código - Produto</th>
                                <th style='text-align: left'>Marca</th>
                                <th style='text-align: left'>Fornecedor</th>
                                <th style='text-align: left'>Desconto Utilizado (R$)</th>
                                <th style='text-align: left'>Preço Enviado (R$)</th>
                            </tr>
                            <tr style='font-size: 14px;'>
                                <td style='text-align: left'><small>{$prod['codigo']} - {$prod['nome_comercial']}</small></td>
                                <td style='text-align: left'><small>{$marca}</small></td>
                                <td style='text-align: left'><small>{$fornecedor}</small></td>
                                <td style='text-align: left'><small>{$desconto}</small></td>
                                <td style='text-align: left'><small>{$preco}</small></td>
                            </tr>
                        ";
                    }
                } else {

                    $row .= "
                        <tr>
                            <td colspan='4' style='border: 1px solid #dddddd; background-color: #C0C0C0'>NENHUMA MARCA PARA PROCESSAMENTO MIX</td>
                        </tr>
                    ";
                }


                $row .= "</table><br><br><br>";
                $i++; 
            } // Fim foreach       
        }

        return $row;
    }
}