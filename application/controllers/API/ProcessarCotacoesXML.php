<?php

use http\Env\Request;

class ProcessarCotacoes extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->urlCliente = 'http://plataformasintese.com/IntegrationService.asmx?WSDL';
        $this->client = new SoapClient($this->urlCliente);
        $this->location = 'http://plataformasintese.com/IntegrationService.asmx';

        $this->load->model('m_cotacoes_produtos', 'cotacao');
        $this->load->model('m_forma_pagamento', 'forma_pagamento');
        $this->load->model('m_forma_pagamento_fornecedor', 'forma_pagamento_fornecedor');
        $this->load->model('m_prazo_entrega', 'prazo_entrega');
        $this->load->model('m_valor_minimo', 'valor_minimo');
        $this->load->model('m_venda_diferenciada', 'venda_dif');
        $this->load->model('m_usuarios', 'usuario');
        $this->load->model('produto_fornecedor_validade', 'pfv');
        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_estados', 'estado');
        $this->load->model('Fornecedor', 'fornecedor');
    }

    public function verificaVendaDiferenciada($produto, $param, $option, $fornecedor) {
                            
        return $this->venda_dif->verificarSeExisteVenda($produto, $param, $option, $fornecedor);
    }

    public function index()
    {

        $fornecedores = $this->fornecedor->find();

        foreach ($fornecedores as $fornecedor) {

            $encontrados = [];

            $function = 'ObterCotacoes';
            $arguments = array('ObterCotacoes' => array(
                'cnpj' => preg_replace("/\D+/", "", $fornecedor['cnpj']),
            ));
            $options = array('location' => $this->location);
            $result = $this->client->__soapCall($function, $arguments, $options);

            $xml = new SimpleXMLElement($result->ObterCotacoesResult);

            // var_dump(count($xml));

            foreach ($xml as $cotacao) {

                $item = (array)$cotacao;

                $cnpj = mask($item['Cd_Comprador'], '##.###.###/####-##');
                $cliente = $this->compradores->get_byCNPJ($cnpj);

                //var_dump($cliente); exit();

                unset($encontrados);
                unset($arrayProdutos);
                unset($produtos_fornecedor);

                if (!empty($cliente)) {

                    // var_dump(count($cotacao->Produtos_Cotacao->Produto_Cotacao));

                    foreach ($cotacao->Produtos_Cotacao->Produto_Cotacao as $produto) {

                        $produto = (array)$produto;

                        //var_dump($produto); exit();

                        $ids_sintese = $this->db->select('id_sintese')
                            ->where('id_produto', $produto['Id_Produto_Sintese'])
                            ->get('produtos_marca_sintese')
                            ->result_array();
                        $ids = [];

                       // var_dump($produto['Id_Produto_Sintese']); exit();

                        foreach ($ids_sintese as $item_ids) {
                            $ids[] = $item_ids['id_sintese'];
                        }
                        $ids = implode(',', $ids);

                        $where = '';
                        if (isset($ids) && !empty($ids)) {
                            $where .= "id_sintese IN ({$ids}) AND ";
                        }

                        if (isset($cliente['estado']) && !empty($cliente['estado'])) {

                            $estado = $this->estado->find("id", "uf = '{$cliente['estado']}'", true);

                            $where .= "id_estado = {$estado['id']} AND ";
                        }

                        //var_dump($estado['id']); exit();

                        if (isset($fornecedor['id']) && !empty($fornecedor['id'])) {
                            $where .= "id_fornecedor = {$fornecedor['id']} AND ";
                        }

                        $where = rtrim($where, 'AND ');

                        unset($encontrados);
                        if (!empty($where)) {
                            $produtos_fornecedor = $this->pfv->get_itens('id, id_produto, codigo, id_sintese, validade, id_marca, marca, preco_unidade, estoque, quantidade_unidade', $where);
                        }

                        //var_dump($this->db->last_query()); exit();
                        if (!empty($produtos_fornecedor)) {
                            $encontrados[$produto['Id_Produto_Sintese']] = $produtos_fornecedor;
                        }

                        $arrayProdutos = [];

                        $campos = [];

                        if (!empty($encontrados)) {
                            foreach ($encontrados as $key => $pp) {
                                foreach ($pp as $ppp){

                                    //somar todos os produtos da cotacao, => total da cotação
                                    // pegar o valor mínimo, dentro do fornecedor, fora de encontrados,, verificr por cnpj ou por
                                    //estado

                                   //var_dump($ppp); exit();

                                    $idVendaDif = $this->verificaVendaDiferenciada($ppp['id_produto'], $cliente['id'], 'CLIENTE', $fornecedor['id']);

                                    if($idVendaDif == NULL) {

                                        $idVendaDif  = $this->verificaVendaDiferenciada($ppp['id_produto'], $estado['id'], 'ESTADOS', $fornecedor['id']);

                                      //  echo 'Entrei no Estado';

                                    }elseif($idVendaDif  == NULL) {

                                        $idVendaDif  = $this->verificaVendaDiferenciada($ppp['id_produto'], $ppp['codigo'], 'CODIGO', $fornecedor['id']);

                                      //  echo 'Entrei no Código';

                                    }

                                  //   var_dump(intval($idVendaDif)); exit();

                                    if(!IS_NULL($idVendaDif)) {

                                        echo 'entrei aki ?';

                                        $valor = $ppp['preco_unidade'];

                                        var_dump($this->venda_dif->getById(intval($idVendaDif))); exit();

                                        $ppp['preco_unidade'] = '';

                                    }
                                    
                    
                                    $campos = [
                                        
                                        "id_produto" => $ppp['id_produto'],
                                        "id_cliente" => $cliente['id'],
                                        "fornecedor_id" => $fornecedor['id'],
                                        "codigo" =>  $ppp['codigo'],
                                        "estado" => $estado['id'],
                                        "desconto" => "",
                                        "valor_normal" => "",
                                        "valor_desconto" => "",

                                    ];

                                   var_dump($campos); exit();

                                
                                    $arrayProdutos[$key][$ppp['codigo']][] = $ppp;

                                }
                            }

                           // var_dump($encontrados); exit();

                            #valor minimo
                            $valor_minimo = $this->valor_minimo->find("*", "id_cliente = {$cliente['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
                            if (empty($valor_minimo)) {
                                $valor_minimo = $this->valor_minimo->find("*", "id_estado = {$estado['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
                            }
                            $valor_minimo = $valor_minimo['valor_minimo'];

                            #prazo entrega
                            $prazo_entrega = $this->prazo_entrega->find("*", "id_cliente = {$cliente['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
                            if (empty($prazo_entrega)) {
                                $prazo_entrega = $this->prazo_entrega->find("*", "id_estado = {$estado['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
                            }
                            $prazo_entrega = $prazo_entrega['prazo'];

                            #condição pagamento
                            $forma_pagamento = $this->forma_pagamento_fornecedor->find("*", "id_cliente = {$cliente['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
                            if (empty($forma_pagamento)) {
                                $forma_pagamento = $this->forma_pagamento_fornecedor->find("*", "id_estado = {$estado['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
                            }
                            $forma_pagamento = $forma_pagamento['id_forma_pagamento'];

                            #validações
                            if (!isset($forma_pagamento) || empty($forma_pagamento)) {
                                $warning = ["type" => "warning", "message" => "É necessário configurar uma forma de pagamento válida, em regras de vendas -> formas de pagamento"];
                            }

                            if (!isset($valor_minimo) || empty($valor_minimo)) {
                                $warning = ["type" => "warning", "message" => "É necessário configurar um valor mínimo, em regras de vendas -> valor minimo"];
                            }

                            if (!isset($prazo_entrega) || empty($prazo_entrega)) {
                                $warning = ["type" => "warning", "message" => "É necessário configurar prazo de entregas, em regras de vendas -> prazo de entregas"];
                            }

                            $dom = new DOMDocument("1.0", "ISO-8859-1");

                            #gerar o codigo
                            $dom->formatOutput = true;

                            #criando o nó principal (root)
                            $root = $dom->createElement("Cotacao");

                            #informações do cabeçalho
                            $root->appendChild($dom->createElement("Tp_Movimento", '1'));
                            $root->appendChild($dom->createElement("Dt_Gravacao", date("d/m/Y H:i:s", time())));
                            $root->appendChild($dom->createElement("Cd_Fornecedor", preg_replace("/\D+/", "", $fornecedor['cnpj'])));
                            $root->appendChild($dom->createElement("Cd_Cotacao", $item['Cd_Cotacao']));
                            // $root->appendChild($dom->createElement("Cd_Cotacao", $cotacao['Cd_Cotacao']));
                            $root->appendChild($dom->createElement("Cd_Condicao_Pagamento", (isset($forma_pagamento) && !empty($forma_pagamento)) ? $forma_pagamento : '1'));
                            $root->appendChild($dom->createElement("Nm_Usuario", "rafael@biohosp"));
                            $root->appendChild($dom->createElement("Ds_Observacao", '-'));
                            $root->appendChild($dom->createElement("Qt_Prz_Minimo_Entrega", (isset($prazo_entrega) && !empty($prazo_entrega)) ? $prazo_entrega : '5'));
                            $root->appendChild($dom->createElement("Vl_Minimo_Pedido", number_format($valor_minimo, 2, ',', '.')));

                            $root->appendChild($dom->createElement("Ds_Observacao_Fornecedor", 'Observacao'));

                            $produtos = $dom->createElement("Produtos_Cotacao");

                            //var_dump($arrayProdutos); exit();


                            foreach($arrayProdutos as $j => $prodsJ) {
                                foreach($prodsJ as $k => $prodsK) {

                                    $produto = $dom->createElement("Produto_Cotacao");

                                    $produto->appendChild($dom->createElement("Id_Produto_Sintese", $j));
                                    $produto->appendChild($dom->createElement("Cd_Produto_Comprador", $k));

                                    $produto->appendChild($dom->createElement("Marcas_Oferta"));

                                    foreach ($prodsK as $pdd){
                                        $marca = $dom->createElement("Marca_Oferta");

                                        $marca->appendChild($dom->createElement("Id_Marca", $pdd['id_marca']));
                                        $marca->appendChild($dom->createElement("Ds_Marca", $pdd['marca']));
                                        $marca->appendChild($dom->createElement("Qt_Embalagem", $pdd['quantidade_unidade']));
                                        $marca->appendChild($dom->createElement("Vl_Preco_Produto", $pdd['preco_unidade']));
                                        $marca->appendChild($dom->createElement("Ds_Obs_Oferta_Fornecedor", ''));

                                        $marca->appendChild($dom->createElement("Cd_produtoERP", $pdd['codigo']));
                                    }

                                    $produto->appendChild($marca);
 
                                }
                            }

                            $root->appendChild($produtos);

                            $root->appendChild($produto);

                            $dom->appendChild($root);
    
                            $dom->preserveWhiteSpace = false;
                        
                            $simpleXML = new SimpleXMLElement($dom->saveXML());
    
                            var_dump($simpleXML->saveXML());
                            exit();
    
                        } 
                    }
                }

            }

            //  var_dump($xml);

        }
    }

}
