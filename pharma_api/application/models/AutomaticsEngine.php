<?php

class AutomaticsEngine extends CI_Model
{
    private $bio;
    private $mix;
    private $oncoprod = [12, 111, 112, 115, 120, 123, 126]; //MELHORAR ISSO
    private $oncoexo = [15, 25];

    public function __construct()
    {
        parent::__construct();

        $this->bio = $this->load->database('bionexo', true);
        $this->mix = $this->load->database('mix', true);
    }

   /* public function getCotsFornecedor($params)
    {
        $where = "id_fornecedor = {$params['id_fornecedor']}";

        $cd_cotacao = '"' . $params['configs']['cotacaoById']['cd_cotacao'] . '"';

        if ($params['configs']['cotacaoById']['status'])
            $where .= " and cd_cotacao = $cd_cotacao";

        if ($params['configs']['checkDataFimCotacao'])
            $where .= " and dt_fim_cotacao > NOW()";


        $fields = "id, cd_cotacao, cd_comprador, id_cliente, dt_fim_cotacao, dt_inicio_cotacao, id_fornecedor, uf_cotacao, revisao";

        $params['db']->select($fields);
        $params['db']->where($where);

        $result = $params['db']->group_by($fields)
            ->get('cotacoes');

        if (empty($result->result_array()))
            return ['status' => FALSE];

        return
            [
                'status' => TRUE,
                'result' => $result->result_array()
            ];

    }*/

    public function getCotsFornecedor($params)
    {
        $where = "id_fornecedor = {$params['id_fornecedor']}";

        $cd_cotacao = '"' . $params['configs']['cotacaoById']['cd_cotacao'] . '"';

        if ($params['configs']['cotacaoById']['status'])
            $where .= " and cd_cotacao = $cd_cotacao";

        if ($params['configs']['checkDataFimCotacao'])
            $where .= " and dt_fim_cotacao > NOW()";

        $fields = "id, cd_cotacao, cd_comprador, id_cliente, dt_fim_cotacao, dt_inicio_cotacao, id_fornecedor, uf_cotacao, revisao";

        $params['db']->select($fields);
        $params['db']->where($where);

        if (!empty($params['estados']) || !empty($params['clientes'])){
            $params['db']->group_start(); //this will start grouping
            if (!empty($params['estados']) && !is_null($params['estados'])) {
                $params['db']->where_in('uf_cotacao', $params['estados']);
            }

            if (!empty($params['clientes']) && !is_null($params['clientes'])) {
                $params['db']->or_group_start()
                    ->where_in('id_cliente', $params['clientes'])
                    ->group_end();
            }
            $params['db']->group_end();
        }

        $result = $params['db']->group_by($fields)
            ->get('cotacoes');


        if (empty($result->result_array()))
            return ['status' => FALSE];

        return
            [
                'status' => TRUE,
                'result' => $result->result_array()
            ];

    }

    public function enabledAutomatic($params, $configs)
    {

        if ($configs['checkEnabledAuto']) {

            /**
             * Verifica se o cliente ou estado estão habilitados para cotar na automática.
             */

            $result = $this->db->where('id_fornecedor', $params['id_fornecedor'])
                ->where_in('regra_venda', [1, 2])
                ->group_start()
                ->where('id_cliente', $params['id_cliente'])
                ->or_where('id_estado', $params['id_estado'])
                ->group_end()
                ->limit(1)
                ->get('controle_cotacoes')
                ->row_array();

            if (IS_NULL($result))
                return FAlSE;

            return TRUE;
        }
        return TRUE;
    }

    public function clientRestriction($params, $configs)
    {

        $bool = FALSE;

        if ($configs['checkClientRestriction']) {

            /**
             * Verifica se o cliente tem alguma restrição para a automática.
             */
            $result = $this->db->where('id_fornecedor', $params['id_fornecedor'])
                ->where('id_cliente', $params['id_cliente'])
                ->where('regra_venda', 0)
                ->get('controle_cotacoes')
                ->row_array();

            if (!IS_NULL($result))
                $bool = TRUE;

        }
        return $bool;
    }

    public function valorMinimo($params, $configs)
    {

        $bool = TRUE;

        $vl_minimo = "1000.0000";
        $desconto = 0.0;

        if ($configs['checkValorMinimo']) {

            $result = $this->db->where('id_fornecedor', $params['id_fornecedor'])
                ->group_start()
                ->where('id_cliente', $params['id_cliente'])
                ->or_where('id_estado', $params['id_estado'])
                ->group_end()
                ->limit(1)
                ->get('valor_minimo_cliente')
                ->row_array();

            if (IS_NULL($result)) {

                $bool = FALSE;

            } else {

                $vl_minimo = $result['valor_minimo'];
                $desconto = $result['desconto_padrao'];
            }
        }

        $arrResult =
            [
                'valor_minimo' => $vl_minimo,
                'desconto_padrao' => $desconto
            ];

        return
            [
                'status' => $bool,
                'result' => $arrResult
            ];

    }

    public function formaPagamentoBionexo($params)
    {

        $bool = TRUE;

        $cd_forma_pagamento = 5;

        if ($this->sinteseConfigs['checkFormaPagamento']) {

            $checkFormaPagamento = $this->db->where('id_fornecedor', $params['id_fornecedor'])
                ->group_start()
                ->where('id_cliente', $params['id_cliente'])
                ->or_where('id_estado', $params['id_estado'])
                ->group_end()
                ->limit(1)
                ->get('formas_pagamento_fornecedores')
                ->row_array()['id_forma_pagamento'];

            if (IS_NULL($checkFormaPagamento))
                return ['status' => FALSE];

            $result = $this->db->where('integrador', 2)
                ->where('ativo', 1)
                ->where('id_forma_pagamento', $checkFormaPagamento)
                ->limit(1)
                ->get('formas_pagamento_depara')
                ->row_array()['cd_forma_pagamento'];

            if (IS_NULL($result)) {

                $bool = FALSE;

            } else {

                $cd_forma_pagamento = $result;
            }
        }

        $arrResult =
            ['cd_forma_pagamento' => $cd_forma_pagamento];

        return
            [
                'status' => $bool,
                'result' => $arrResult
            ];
    }

    public function prazoEntrega($params, $configs)
    {

        $bool = TRUE;

        $prazo_entrega = 15;

        if ($configs['checkPrazoEntrega']) {

            $result = $this->db->where('id_fornecedor', $params['id_fornecedor'])
                ->group_start()
                ->where('id_cliente', $params['id_cliente'])
                ->or_where('id_estado', $params['id_estado'])
                ->group_end()
                ->limit(1)
                ->get('prazos_entrega')
                ->row_array()['prazo'];

            if (IS_NULL($result)) {

                $bool = FALSE;

            } else {

                $prazo_entrega = $result;
            }
        }

        $arrResult =
            ['prazo_entrega' => $prazo_entrega];

        return
            [
                'status' => $bool,
                'result' => $arrResult
            ];
    }

    public function productCotRestriction($params, $configs)
    {
        if ($configs['checkPrdCotRestriction']) {

            $result = $this->db->where('cd_cotacao', $params['cd_cotacao'])
                ->where('id_fornecedor', $params['id_fornecedor'])
                ->group_start()
                ->group_start()
                ->where('id_produto_sintese', $params['id_produto_sintese'])
                ->where('cd_produto_comprador', $params['cd_produto_comprador'])
                ->group_end()
                ->or_group_start()
                ->where('id_produto_sintese is NULL')
                ->where('cd_produto_comprador', $params['cd_produto_comprador'])
                ->group_end()
                ->group_end()
                ->limit(1)
                ->get('restricoes_produtos_cotacoes')
                ->row_array();

            if (IS_NULL($result))
                return FALSE;

            return TRUE;
        }
        return FALSE;
    }

    public function vendaDif($params, $configs)
    {

        if ($configs['checkVendaDif']) {


            $result = $this->db->where('id_fornecedor', $params['id_fornecedor'])
                ->where('codigo', $params['codigo'])
                ->where('regra_venda IN  (0, 2, 3, 6)')
                ->group_start()
                ->where('id_cliente', $params['id_cliente'])
                ->or_where('promocao', 1)
                ->or_where('id_estado', $params['id_estado'])
                ->group_end()
                ->limit(1)
                ->order_by('desconto_percentual DESC')
                ->get('vendas_diferenciadas')
                ->row_array();

            if (IS_NULL($result))
                return ['status' => FALSE];

            return
                [
                    'status' => TRUE,
                    'result' => $result
                ];
        }

        return
            [
                'status' => TRUE,
                'result' =>
                    [
                        'desconto_percentual' => 0
                    ]
            ];

    }

    public function productRestriction($params, $configs)
    {

        if ($configs['checkPrdRestriction']) {

            /**
             * Verifica se existem retrições para o cliente ou para o estado para determinado produto.
             */

            $result = $this->db->where('id_fornecedor', $params['id_fornecedor'])
                ->where('id_produto', $params['codigo'])
                ->group_start()
                ->where('id_cliente', $params['id_cliente'])
                ->or_where('id_estado', $params['id_estado'])
                ->group_end()
                ->limit(1)
                ->get('restricoes_produtos_clientes')
                ->row_array();

            if (IS_NULL($result))
                return FALSE;

            return TRUE;
        }
        return FALSE;
    }

    public function getEstoque($params, $configs)
    {

        if ($configs['checkPrdStock']) {

            $vd = $this->db->select('min(validade) AS validade')
                ->where('codigo', $params['codigo'])
                ->where('id_fornecedor', $params['id_fornecedor'])
                ->order_by('validade')
                ->get('produtos_lote')
                ->row_array();

            $result = $this->db->select('SUM(estoque) AS total, validade')
                ->where('codigo', $params['codigo'])
                ->where('id_fornecedor', $params['id_fornecedor'])
                ->order_by('validade')
                ->get('produtos_lote')
                ->row_array();

            if (IS_NULL($result))
                return ['status' => FALSE];

            if (intval($result['total']) === 0)
                return ['status' => FALSE];

            return
                [
                    'status' => TRUE,
                    'result' =>
                        [
                            'total' => intval($result['total']),
                            'validade' => date('d/m/Y', strtotime(str_replace('-', '/', $vd['validade'])))
                        ]
                ];

        }

        return
            [
                'status' => TRUE,
                'result' =>
                    [
                        'total' => 0,
                        'validade' => date('d/m/Y', strtotime('+2 years', strtotime(date('Y-m-d'))))
                    ]
            ];
    }

    public function getPriceProd($params, $configs)
    {

        /**
         * Função responsável por pegar o preço exato do produto.
         */

        $tablePrice = "produtos_preco_mix";

        $valuePrice = $this->mix->select('preco_mix, preco_base')
            ->where('preco_fixo', 0)
            ->where('id_fornecedor', $params['id_fornecedor'])
            ->where('codigo', $params['codigo'])
            ->group_start()
            ->where('id_cliente', $params['id_cliente'])
            ->or_where('id_estado', $params['id_estado'])
            ->group_end()
            ->limit(1)
            ->get($tablePrice)
            ->row_array();

        $precoMix = 0.0;
        $checkPriceMix = 0.0;
        $precoBase = 0.0;
        $acrescimoTabMix = 0.0;

        if (!IS_NULL($valuePrice)) {

            $precoMix = floatval($valuePrice['preco_mix']);
            $precoBase = floatval($valuePrice['preco_base']);

            if ($precoMix != $precoBase) {

                if ($precoMix > 0) {

                    $checkPriceMix = $precoMix;

                    $rand = decimalRand(0.01, 0.50, 0.01);

                    $acrescimoTabMix = $rand;

                    $valuePrice = $precoMix + ($precoMix * ($rand / 100));

                } else {

                    $valuePrice = $precoBase;
                }
            } else {
                $valuePrice = $precoBase;
            }
        }

        $valuePrice = floatval($valuePrice);

        if ($valuePrice == 0) {

            $tablePrice = "produtos_preco_max";

            /**
             * Pega todos os estados que o fornecedor tem preços de produtos.
             */
            $estadosPrecoFornecedor = $this->db->select('IF(id_estado IS NULL, 0, id_estado) id_estado')
                ->where('id_fornecedor', $params['id_fornecedor'])
                ->group_by('id_estado')
                ->get($tablePrice)
                ->result_array();

            $estadosFornecedor = [];

            /**
             * Coloca todos os estados em um array.
             */
            foreach ($estadosPrecoFornecedor as $item)
                array_push($estadosFornecedor, intval($item['id_estado']));

            /**
             * Verifica se o id estado passado por parametro está dentro do array.
             * Ou seja, o estado que será verificado o fornecedor tem produto com preço ?
             */
            if (in_array($params['id_estado'], $estadosFornecedor)) {

                /**
                 * Se existe preço para o estado ... $estado recebe o id_estado de parametro.
                 */

                $estado = "= {$params['id_estado']}";

            } elseif (in_array(0, $estadosFornecedor)) {

                /**
                 * Se não existe o id_estado, mais o fornecedor tem preço para o Brasil.
                 * $estado recebe is null.
                 * Na tabela do Banco produtos_precos, quando o preço é para o Brasil, o id_estado é NULL.
                 */

                $estado = "is null";

            } else {

                /**
                 * Se não encontrou o id_estado de parametro e não atende o Brasil, $estado recebe NULL (VAZIO)
                 * Não é o is null igual o caso anterior. Esse NULL significa que não tem preço nenhum.
                 */
                $estado = NULL;
            }

            if (IS_NULL($estado))
                return ['status' => FALSE];

            /**
             * Se o $estado não for NULL. A rotina irá buscar o preço exato daquele id_estado.
             */

            $valuePrice = $this->db->where('codigo', $params['codigo'])
                ->where('id_fornecedor', $params['id_fornecedor'])
                ->where("id_estado {$estado}")
                ->limit(1)
                ->get($tablePrice)
                ->row_array()['preco_unitario'];

            if (IS_NULL($valuePrice))
                return ['status' => FALSE];

        }

        $price = floatval($valuePrice);

        $priceAtt = 0;

        $desconto_padrao = $params['desconto_padrao'];
        $desconto_percentual = $params['desconto_percentual'];

        # TODO => Preço Caixa OncoProd, Hospidrogas e BioHosp

        /**
         * As empresas:
         * ONCOPROD, HOSPIDROGAS e BIOHOSP enviam na integração o preço de CAIXA.
         * A sintese trabalha com preço unitário, ou seja, os preços caixas precisam ser
         * divididos pela quantidade de itens que vem na caixa, para o preço ficar conforme
         * atendimento da Sintese.
         */

        $priceAtt = $price;

        $typePrice = "UNITARIO";

        $priceTab = $price;

        if ($checkPriceMix > 0)
            $priceTab = $checkPriceMix;

        if (in_array($params['id_fornecedor'], $this->oncoprod)) {
            $typePrice = "CAIXA";
            $priceAtt = ($price / $params['qtd_unidade']);
        }

        if (($params['id_fornecedor'] == 20) || ($params['id_fornecedor'] == 104)) {
            $typePrice = "CAIXA";
            $priceAtt = ($price / $params['qtd_unidade']);
        }

        /**
         * Se houver algum desconto é aplicado em cima do preço unitário.
         */

        $desconto_aplicado = 0;
        $tipoDesconto = "NENHUM";

        if ($desconto_padrao > 0) {
            $desconto_aplicado = $desconto_padrao;
            $tipoDesconto = "PADRAO";
        }

        if ($desconto_percentual > 0) {
            $desconto_aplicado = $desconto_percentual;
            $tipoDesconto = "PRODUTO";
        }

        $priceDesconto = $priceAtt;

        if ($desconto_aplicado > 0)
            $priceDesconto = $priceAtt - ($priceAtt * ($desconto_aplicado / 100));

        return
            [
                "status" => TRUE,
                "acrescimoTabMix" => $acrescimoTabMix,
                "tabelaPrecos" => $tablePrice,
                "tipoDesconto" => $tipoDesconto,
                "priceTabela" => $priceTab,
                "typePrice" => $typePrice,
                "descontoAplicado" => $desconto_aplicado,
                "priceOferta" => $priceDesconto
            ];
    }

    public function prodsOferta($arrParams)
    {

        $header = $arrParams['dadosCotacao'];
        $produto = $arrParams['produtoOferta'];

        return
            [
                "integrador" => $arrParams['type'],
                "produto" => $produto['nome_comercial'] . " - " . $produto['apresentacao'],
                "qtd_solicitada" => $produto['qtd_solicitada'],
                "qtd_embalagem" => $produto['qtd_unidade'],
                "cd_produto_comprador" => $arrParams['cd_produto_comprador'],
                "id_produto" => $produto['id_produto'],
                "preco_marca" => $produto['preco_oferta'],
                "data_cotacao" => $header['dt_inicio_cotacao'],
                "cd_cotacao" => $header['cd_cotacao'],
                "id_fornecedor" => $header['id_fornecedor'],
                "id_fornecedor_logado" => $header['id_fornecedor'],
                "id_forma_pagamento" => $header['forma_pagamento'],
                "prazo_entrega" => $header['prazo_entrega'],
                "valor_minimo" => $header['valor_minimo'],
                "nivel" => 2,
                "cnpj_comprador" => $header['cnpj_cliente'],
                'uf_comprador' => $header['uf_cotacao'],
                "id_cliente" => $header['id_cliente'],
                "controle" => "1",
                "submetido" => "1",
                "id_cotacao" => $header['id_cotacao'],
                "id_pfv" => $produto['codigo'],
                "obs" => (isset($produto['obs'])) ? $produto['obs'] : '',
                'obs_produto' => (isset($produto['obs_produto'])) ? $produto['obs_produto'] : '-'
            ];
    }

    public function saveProdsOferta($array, $cofigs)
    {

        if ($cofigs['saveProdsOferta']) {

            foreach ($array as $item) {

                $result = $this->db->where('id_fornecedor', $item['id_fornecedor'])
                    ->where('id_pfv', $item['id_pfv'])
                    ->where('cd_produto_comprador', $item['cd_produto_comprador'])
                    ->where('qtd_solicitada', $item['qtd_solicitada'])
                    ->where('id_produto', $item['id_produto'])
                    ->where('cd_cotacao', $item['cd_cotacao'])
                    ->where('integrador', $item['integrador'])
                    ->limit(1)
                    ->get('cotacoes_produtos')
                    ->row_array();

                if (IS_NULL($result))
                    $this->db->insert('cotacoes_produtos', $item);

            }
        }
    }

    public function getConfigsEnvio($params)
    {

        $result = $this->db
            ->where('id_fornecedor', $params['id_fornecedor'])
            ->where('integrador', (isset($params['integrador']) ? $params['integrador'] : 1))
            ->group_start()
            ->where('id_estado', $params['id_estado'])
            ->or_where('id_estado', 0)
            ->group_end()
            ->limit(1)
            ->get('configuracoes_envio')
            ->row_array();

        if (IS_NULL($result))
            return ['status' => FALSE];

        return
            [
                'status' => TRUE,
                'result' => $result
            ];

    }

    public function sendEmail($params)
    {
        /**
         * Envia o e-mail.
         */

        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtplw.com.br',
            'smtp_port' => 587,
            'smtp_user' => 'pharmanexo',
            'smtp_pass' => 'AzqvIbuZ5038',
            'smtp_timeout' => 20,
            'validate' => true,
            'smtp_crypto' => false,
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => '\r\n',
            'wordwrap' => true,
            'bcc_batch_mode' => FALSE,
            'bcc_batch_size' => 200
        );


        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->set_crlf("\r\n");

        $this->email->initialize($config);

        $this->email->clear(true);

        $this->email->from($params['from'], $params['from-name']);
        $this->email->subject($params['assunto']);
        $this->email->reply_to("suporte@pharmanexo.com.br");
        $this->email->to($params['destinatario']);

        isset($params['c_copia']) ? $this->email->cc($params['c_copia']) : FALSE;
        isset($params['copia_o']) ? $this->email->bcc($params['copia_o']) : FALSE;
        isset($params['anexo']) ? $this->email->attach($params['anexo']) : FALSE;

        $this->email->message($params['msg']);

        $return = $this->email->send();

        if (isset($params['anexo'])) {

            file_exists($params['anexo']) ? unlink($params['anexo']) : FALSE;
        }

        if ($return) {

            return $return;

        } else {

            return $this->email->print_debugger();
        }

    }

    private function createMirror($params, $configs)
    {

        $data_inicio = date('d/m/Y H:i:s', strtotime($params['dadosCotacao']['dt_inicio_cotacao']));
        $data_fim = date('d/m/Y H:i:s', strtotime($params['dadosCotacao']['dt_fim_cotacao']));
        $data_envio = date('d/m/Y H:i');

        $condicao_pagamento = $this->db->where('id', $params['dadosCotacao']['forma_pagamento'])
            ->get('formas_pagamento')
            ->row_array()['descricao'];

        $i = 1;

        $rows = "";

        $id_fornecedor = $params['dadosCotacao']['id_fornecedor'];

        $fornecedor = $this->db->where('id', $id_fornecedor)
            ->get('fornecedores')
            ->row_array()['nome_fantasia'];

        $cliente = $this->db->where('id', $params['dadosCotacao']['id_cliente'])
            ->get('compradores')
            ->row_array()['razao_social'];

        $filial = "";
        $ds_filial = "";

        if (in_array($id_fornecedor, $this->oncoprod) || in_array($id_fornecedor, $this->oncoexo)) {

            $filial = "<th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Filial</th>";
            $ds_filial = "<th style='border: 1px solid #dddddd; padding-right: 20px'>{$fornecedor}</th>";
        }

        $label_codigo = in_array($id_fornecedor, $this->oncoprod) ? 'Código Kraft' : 'Código';

        $valor_minimo = number_format($params['dadosCotacao']['valor_minimo'], 2, ",", ".");

        foreach ($params['prodsEspelho'] as $produto) {

            $row = "
                        <table style='width:100%; border: 1px solid #dddddd; border-collapse: collapse'>
                        <tr>
                            <td style='border: 1px solid #dddddd; background-color: #D3D3D3' colspan='2'>{$i}. {$produto['ds_produto_comprador']}</td>
                            <td style='border: 1px solid #dddddd; background-color: #D3D3D3' colspan='2'><strong>Qtde Solicitada:</strong> {$produto['qt_produto_total']}</td>
                            <td style='border: 1px solid #dddddd; background-color: #D3D3D3' colspan='2'><strong>Und. Compra:</strong> {$produto['ds_unidade_compra']}</td>
                        </tr>
                        <tr>
                            <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>{$label_codigo}</th>
                            {$filial}
                            <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Marca</th>
                            <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Embalagem</th>
                            <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Preço</th>
                            <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Descrição</th>
                        </tr>
                    ";

            foreach ($produto['marcas_encontradas'] as $item) {

                $preco = number_format($item['preco_oferta'], 4, ",", ".");

                $row .= "
                                <tr>
                                    <td style='border: 1px solid #dddddd; padding-right: 20px'>{$item['codigo']}</td>
                                    {$ds_filial}
                                    <td style='border: 1px solid #dddddd; padding-right: 20px'>{$item['marca']}</td>
                                    <td style='border: 1px solid #dddddd; padding-right: 20px'>{$item['qtd_unidade']}</td>
                                    <td style='border: 1px solid #dddddd; padding-right: 20px'>{$preco}</td>
                                    <td style='border: 1px solid #dddddd; padding-right: 20px'>{$item['descricao']}</td>
                                </tr>
                                <tr>
                                    <td colspan='6' style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Observações: {$item['nome_comercial']}  - {$item['obs_produto']}</td>
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
                        <label style='margin-right: 20px; font-size: 12px'><strong>Numero da Cotação:</strong> {$params['dadosCotacao']['cd_cotacao']}</label><br>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Empresa:</strong> {$cliente} </label><br>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Situação:</strong> Em Andamento </label><br>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Data e Hora de Início:</strong> {$data_inicio} </label><br>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Data e Hora de Término:</strong> {$data_fim} </label><br>
                        <label style='margin-right: 20px; font-size: 12px'><strong>Data de Envio:</strong> {$data_envio} </label>
                    </p>
                    <hr>
                    <strong>Condições de Pagamento: </strong> {$condicao_pagamento} <br>
                    <strong>Valor mínimo do pedido por entrega (R$):</strong> {$valor_minimo} <br>
                    <strong>Prazo de entrega (dias):</strong> {$params['dadosCotacao']['prazo_entrega']} <br>
                    <strong>Observações:</strong> - <br>
                    <hr>

                    {$rows}

                </small>
            ";

        # Armazena o arquivo
        $filename = $_SERVER['DOCUMENT_ROOT'] . "/pharma_api/public/cotacoes/exports/cotacao_" . time() . ".pdf";

        if (file_exists($filename)) {
            unlink($filename);
        }

        if ($configs['sendEmailAnexo']) {

            $mpdf = new \Mpdf\Mpdf();
            $mpdf->WriteHTML($data);
            $mpdf->Output($filename, 'F');

        }

        return
            [
                'body' => $data,
                'anexo' => $filename
            ];
    }

    public function submitEmail($params, $configs)
    {

        /**
         * Responspavel por pegar todos os e-mails responspaveis de cada em    presa.
         */

        if ($configs['sendEmail']) {
            $oncoprd = [12, 111, 112, 115, 120, 123, 125];
            $this->db->where("modulo", 'MIRROR_AUTOMATIC');
            $this->db->where("ativo", 1);
            $this->db->where("tipo", 1);

            $notify = $this->db->get('modulo_notificacoes')->row_array();

            if (!IS_NULL($notify) && !empty($notify)) {

                $email_notificacao = $this->db->where('id_fornecedor', $params['dadosCotacao']['id_fornecedor'])
                    ->where('id_cliente', $params['dadosCotacao']['id_cliente'])
                    ->get('email_notificacao')
                    ->row_array();

                if (IS_NULL($email_notificacao)) {

                    $email_notificacao = $this->db->where('id_fornecedor', $params['dadosCotacao']['id_fornecedor'])
                        ->where('id_cliente', NULL)
                        ->get('email_notificacao')
                        ->row_array();
                }

                $emails = [];

                if (!IS_NULL($email_notificacao)) {

                    if (!empty($email_notificacao['gerente']))
                        array_push($emails, strtolower($email_notificacao['gerente']));

                    if (!empty($email_notificacao['consultor']))
                        array_push($emails, strtolower($email_notificacao['consultor']));

                    if (!empty($email_notificacao['geral']))
                        array_push($emails, strtolower($email_notificacao['geral']));

                    if (!empty($email_notificacao['grupo']))
                        array_push($emails, strtolower($email_notificacao['grupo']));
                    if (in_array($params['dadosCotacao']['id_fornecedor'], $oncoprd)) {
                        array_push($emails, 'karina.souza@oncoprod.com.br');
                    }

                }

                if (!$configs['sendEmailDestiny'])
                    $emails = NULL;

                $copia_o = NULL;

                #$copia_o = "marlon.boecker@pharmanexo.com.br";

                if ((IS_NULL($emails) || empty($emails)) && IS_NULL($copia_o))
                    return;

                $dt_cotacao = date('d/m/Y H:i:s', strtotime(str_replace('/', '-', $params['dadosCotacao']['dt_fim_cotacao'])));

                $ds_cliente = "";

                $cliente = $this->db->where('id', $params['dadosCotacao']['id_cliente'])
                    ->get('compradores')
                    ->row_array();

                if (empty($cliente['nome_fantasia']) || is_null($cliente['nome_fantasia'])) {

                    $ds_cliente = $cliente['razao_social'];

                } else {
                    $ds_cliente = $cliente['nome_fantasia'];
                }

                $myMirror = $this->createMirror($params, $configs);

                $template = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/pharma_api/public/template/body_mail.html');

                $body = str_replace(["%body%"], [$myMirror['body']], $template);

                $mail =
                    [
                        "from" => "suporte@pharmanexo.com.br",
                        "from-name" => "Portal Pharmanexo",
                        "assunto" => "COTACAO {$configs['integrador']} #{$params['dadosCotacao']['cd_cotacao']} {$ds_cliente} - {$params['dadosCotacao']['uf_cotacao']} {$dt_cotacao}",
                        "destinatario" => IS_NULL($emails) ? $copia_o : $emails,
                        "copia_o" => $copia_o,
                        "c_copia" => $copia_o,
                        "msg" => $body,
                        "anexo" => $myMirror['anexo']
                    ];

                $this->sendEmail($mail);
            }
        }
    }

    public function saveLogs($logs, $params, $configs)
    {
        if ($configs['saveLogs']) {

            $xml = NULL;
            $result = NULL;
            $status = 0;
            $logs_json = json_encode($logs, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_FORCE_OBJECT | JSON_PRESERVE_ZERO_FRACTION | JSON_NUMERIC_CHECK);
            $log_config = json_encode($configs, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_FORCE_OBJECT | JSON_PRESERVE_ZERO_FRACTION | JSON_NUMERIC_CHECK);

            if (isset($params['xml'])) {
                if (!IS_NULL($params['xml'])) {
                    $xml = $params['xml'];
                    $status = 1;
                }
            }

            if (!boolval($status)) {

                $result = $this->db->where('cd_cotacao', $params['cd_cotacao'])
                    ->where('id_fornecedor', $params['id_fornecedor'])
                    ->where('id_cliente', $params['id_cliente'])
                    ->where('id_estado', $params['id_estado'])
                    ->where('integrador', $params['type'])
                    ->where('status', 0)
                    ->limit(1)
                    ->get('log_envio_automatico')
                    ->row_array();
            }

            if (!IS_NULL($result)) {

                $this->db->where('cd_cotacao', $params['cd_cotacao'])
                    ->where('id_fornecedor', $params['id_fornecedor'])
                    ->where('id_cliente', $params['id_cliente'])
                    ->where('id_estado', $params['id_estado'])
                    ->where('integrador', $params['type'])
                    ->where('status', 0)
                    ->set('logs', $logs_json)
                    ->set('configs', $log_config)
                    ->set('data_atualizacao', date('Y-m-d H:i:s'))
                    ->update('log_envio_automatico');

                return;
            }

            $this->db->insert('log_envio_automatico', [
                'cd_cotacao' => $params['cd_cotacao'],
                'id_fornecedor' => $params['id_fornecedor'],
                'integrador' => $params['type'],
                'id_cliente' => $params['id_cliente'],
                'id_estado' => $params['id_estado'],
                'status' => $status,
                'logs' => $logs_json,
                'xml' => $xml,
                'configs' => $log_config,
                'data_atualizacao' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
