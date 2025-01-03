<?php

class ProcessarCotacoes extends CI_Controller
{
 private $db1;
 private $db2;

 public function __construct()
 {
  parent::__construct();

  $this->db1 = $this->load->database('default', true);
  $this->db2 = $this->load->database('sintese', true);

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

 public function verificaCotacaoAutomatica($id_fornecedor, $param, $option)
 {
  $this->db1->select('*');
  $this->db1->from('controle_cotacoes');

  if ($option === 'CNPJ') {

   $this->db1->where('id_cliente', $param);

  } elseif ($option === 'ESTADO') {

   $this->db1->where('id_estado', $param);
  }

  $this->db1->where('id_fornecedor', $id_fornecedor);
  $this->db1->where('automatico', 1);

  $x = $this->db1->get();

  $return = $x->row_array();

  return ((isset($return) && !empty($return)) || !is_null($return)) ? true : false;
 }

 public function verificaVendaDiferenciada($produto, $param, $option, $fornecedor)
 {
  return $this->venda_dif->verificarSeExisteVenda($produto, $param, $option, $fornecedor);
 }

 public function verificaValorMinimo($key1, $key2, $option)
 {
  $var = '';

  if ($option == 1) {

   $var = 'id_cliente';

  } elseif ($option == 2) {

   $var = 'id_estado';
  }

  return $this->valor_minimo->find("*", "{$var} = {$key1} and id_fornecedor = {$key2}", true);
 }

 public function verificaRetricoes($id_produto, $id_fornecedor, $param, $option)
 {
  $this->db1->select('*');
  $this->db1->from('restricoes_produtos_clientes');

  if ($option === 'CNPJ') {

   $this->db1->where('id_cliente', $param);

  } elseif ($option === 'ESTADO') {

   $this->db1->where('id_estado', $param);
  }

  $this->db1->where('id_produto', $id_produto);
  $this->db1->where('id_fornecedor', $id_fornecedor);

  $x = $this->db1->get();

  return $x->result_array();
 }

 public function verificaPrecoEspecial($codigo_produto, $id_estado)
 {
  return $this->db1->select('valor')
   ->where('codigo', $codigo_produto)
   ->where('id_estado', $id_estado)
   ->where('tipo', 0)
   ->or_where('tipo', 2)
   ->get('precos_especiais')
   ->row_array()['valor'];
 }

 public function verificaCotacaoEnviada($cd_cotacao)
 {

  return $this->db1->select('cd_cotacao')
   ->where('cd_cotacao', $cd_cotacao)
   ->get('cotacoes_produtos')
   ->result_array();

  return ((isset($return) && !empty($return)) || !is_null($return)) ? true : false;

 }

 public function index()
 {
  
  $warning = [];

  $log = [];

  $valorTotalCotacao = 0;

  $fornecedores = $this->fornecedor->find();

  foreach ($fornecedores as $fornecedor) {

   $log["id_fornecedor"] = $fornecedor['id'];

   $encontrados = [];

   $function = 'ObterCotacoes';
   $arguments = array('ObterCotacoes' => array(
    'cnpj' => preg_replace("/\D+/", "", $fornecedor['cnpj']),
   ));

   $cotacoes = $this->db2->select('*')
    ->where('id_fornecedor', $fornecedor['id'])
    ->get('cotacoes')
    ->result_array();

   //   var_dump($cotacoes); exit();

   foreach ($cotacoes as $cotacao) {

    $item = $cotacao;

    ($this->verificaCotacaoEnviada($item['cd_cotacao'])) ? $warning["cotacao_atendida"] = "A cotacão {$item['cd_cotacao']} já foi atendida" : '';

    $log["cd_cotacao"] = $item['cd_cotacao'];

    $produtos = $this->db2->select('*')
     ->where('cd_cotacao', $item['cd_cotacao'])
     ->where('id_fornecedor', $fornecedor['id'])
     ->get('cotacoes_produtos')
     ->result_array();

    $cnpj = mask($item['cd_comprador'], '##.###.###/####-##');
    $cliente = $this->compradores->get_byCNPJ($cnpj);

    $log["id_cliente"] = $cliente['id'];

    unset($encontrados);
    unset($arrayProdutos);
    unset($produtos_fornecedor);

    $valores_minimos = [];

    if (!empty($cliente)) {

     #valor minimo
     $vl_min_cnpj = $this->verificaValorMinimo($cliente['id'], $fornecedor['id'], 1);

     (isset($cliente['estado']) && !empty($cliente['estado'])) ? $estado = $this->estado->find("id", "uf = '{$cliente['estado']}'", true) : '';

     $log["id_estado"] = $estado['id'];

     $vl_min_uf = $this->verificaValorMinimo($estado['id'], $fornecedor['id'], 2);

     $valores_minimos = [
      "vl_min_cnpj" => floatval($vl_min_cnpj['valor_minimo']),
      "desconto_padrao_cnpj" => floatval($vl_min_cnpj['desconto_padrao']),
      "vl_min_uf" => floatval($vl_min_uf['valor_minimo']),
      "desconto_padrao_uf" => floatval($vl_min_uf['desconto_padrao']),
     ];

    } else {
     $warning = [
      "cliente" => "Cliente não localizado na Base de Dados !",
     ];
    }

    $cotacaoAutomatica = $this->verificaCotacaoAutomatica($fornecedor['id'], $cliente['id'], 'CNPJ');

    (!$cotacaoAutomatica) ? $this->verificaCotacaoAutomatica($fornecedor['id'], $estado['id'], 'ESTADO') : '';

    if (!$cotacaoAutomatica) {
     $warning["cotacao_automatica"] =
      "Cotação automática não habilitada: Fornecedor: {$fornecedor['razao_social']}, Cliente: {$cliente['razao_social']}, Estado: {$cliente['estado']}";
    }

    $log["cotacao_automatica"] = $cotacaoAutomatica;

    $valor_minimo = '';
    $desconto_padrao = '';

    if (isset($valores_minimos['vl_min_cnpj'])
     && !empty($valores_minimos['vl_min_cnpj'])
     && ($valores_minimos['vl_min_cnpj'] != 0)) {

     $valor_minimo = $valores_minimos['vl_min_cnpj'];

    } else {

     $valor_minimo = $valores_minimos['vl_min_uf'];
    }

    if (isset($valores_minimos['desconto_padrao_cnpj'])
     && !empty($valores_minimos['desconto_padrao_cnpj'])
     && ($valores_minimos['desconto_padrao_cnpj'] != 0)) {

     $desconto_padrao = $valores_minimos['desconto_padrao_cnpj'];

    } else {

     $desconto_padrao = $valores_minimos['desconto_padrao_uf'];
    }

    $log["valor_minimo"] = $valor_minimo;
    $log["desconto_padrao"] = $desconto_padrao;

    foreach ($produtos as $produto) {

     $ids_sintese = $ids_sintese = $this->db1->select('id_sintese')
      ->where('id_produto', $produto['id_produto_sintese'])
      ->get('produtos_marca_sintese')
      ->result_array();
     $ids = [];

     foreach ($ids_sintese as $item_ids) {
      $ids[] = $item_ids['id_sintese'];
     }
     $ids = implode(',', $ids);

     $log["ids_sintese"] = $ids;

     //  var_dump($ids);

     $where = '';
     if (isset($ids) && !empty($ids)) {
      $where .= "id_sintese IN ({$ids}) AND ";
     }

     if (isset($cliente['estado']) && !empty($cliente['estado'])) {

      $where .= "id_estado = {$estado['id']} AND ";
     }

     //var_dump($estado['id']); exit();

     if (isset($fornecedor['id']) && !empty($fornecedor['id'])) {
      $where .= "id_fornecedor = {$fornecedor['id']} AND ";
     }

     $where .= "validade > NOW() AND ";

     $where = rtrim($where, 'AND ');

     unset($encontrados);
     if (!empty($where)) {
      $produtos_fornecedor =
      $this->pfv->get_itens('id, id_produto, codigo, id_sintese, id_marca, marca, validade, preco_unidade, estoque, quantidade_unidade', $where, 'validade');
     }

     // var_dump($produtos_fornecedor); exit();

     if (!empty($produtos_fornecedor)) {
      $encontrados[$produto['id_produto_sintese']] = $produtos_fornecedor;
     }

     // var_dump($encontrados); exit();

     $arrayProdutos = [];

     $campos = [];

     if (!empty($encontrados)) {

      // var_dump($encontrados); exit();

      foreach ($encontrados as $key => $pp) {
       foreach ($pp as $k => $ppp) {

        $idVendaDif = $this->verificaVendaDiferenciada($ppp['id_produto'], $cliente['id'], 'CLIENTE', $fornecedor['id']);

        if (IS_NULL($idVendaDif)) {

         $idVendaDif = $this->verificaVendaDiferenciada($ppp['id_produto'], $estado['id'], 'ESTADOS', $fornecedor['id']);

         //  echo 'Entrei no Estado';

        } elseif (IS_NULL($idVendaDif)) {

         $idVendaDif = $this->verificaVendaDiferenciada($ppp['id_produto'], $ppp['codigo'], 'CODIGO', $fornecedor['id']);

         //  echo 'Entrei no Código';
        }

        $restricoes = $this->verificaRetricoes($ppp['id_produto'], $fornecedor['id'], $cliente['id'], 'CNPJ');

        if (IS_NULL($restricoes)) {

         $this->verificaRetricoes($ppp['id_produto'], $fornecedor['id'], $estado['id'], 'ESTADO');
        }

        // var_dump($encontrados); exit();

        $log["restricoes"] = false;

        if (isset($restrições) && !empty($restrições)) {
         $warning["restricoes"] = "Existem restrições para o produto {$ppp['id_produto']} no fornecedor {$fornecedor['id']} no estado {$estado['id']} !";

         $log["restricoes"] = true;

         unset($pp[$key]);
        }

        //var_dump($restricoes); exit();

        //var_dump($idVendaDif); exit();

        $valor = floatval($ppp['preco_unidade']);
        $newValor = 0;
        $desconto = 0;

        if (!IS_NULL($idVendaDif)) {

         $x = $this->db1->select('desconto_percentual')
          ->where('id', intval($idVendaDif))
          ->get('vendas_diferenciadas')
          ->row_array();

         $desconto = floatval($x['desconto_percentual']);

         $newValor = $valor - ($valor * ($desconto / 100));

         $ppp['preco_unidade'] = strval($newValor);

         // var_dump($ppp); exit();

         $log["id_venda_diferenciada"] = $idVendaDif;

        } elseif (!IS_NULL($desconto_padrao) && $desconto_padrao != 0) {

         $newValor = $valor - ($valor * ($desconto_padrao / 100));

         $ppp['preco_unidade'] = strval($newValor);
        }

        $preco_especial = $this->verificaPrecoEspecial($ppp['codigo'], $estado['id']);

        $log["preco_especial"] = false;

        if (isset($preco_especial) && !empty($preco_especial) && !IS_NULL($preco_especial)) {

         $ppp['preco_unidade'] = $preco_especial;

         $log["preco_especial"] = true;
        }

        $arrayProdutos[$key][$ppp['codigo']][] = $ppp;
       }
      }

      // validaçoes para o pagamento ...

      #prazo entrega
      $prazo_entrega = $this->prazo_entrega->find("*", "id_cliente = {$cliente['id']} and id_fornecedor = {$fornecedor['id']}", true);

      if (empty($prazo_entrega)) {
       $prazo_entrega = $this->prazo_entrega->find("*", "id_estado = {$estado['id']} and id_fornecedor = {$fornecedor['id']}", true);
      }
      $prazo_entrega = $prazo_entrega['prazo'];

      #condição pagamento
      $forma_pagamento = $this->forma_pagamento_fornecedor->find("*", "id_cliente = {$cliente['id']} and id_fornecedor = {$fornecedor['id']}", true);
      if (empty($forma_pagamento)) {
       $forma_pagamento = $this->forma_pagamento_fornecedor->find("*", "id_estado = {$estado['id']} and id_fornecedor = {$fornecedor['id']}", true);
      }
      $forma_pagamento = $forma_pagamento['id_forma_pagamento'];

      #validações para o LOG

      $log["forma_pagamento"] = true;
      $log["prazo_entrega"] = true;

      if (!isset($forma_pagamento) || empty($forma_pagamento)) {
       $warning["forma_pagamento"] = "É necessário configurar uma forma de pagamento válida, em regras de vendas -> formas de pagamento";
       $log["forma_pagamento"] = false;
      }

      if (!isset($valor_minimo) || empty($valor_minimo)) {
       $warning["valor_minimo"] = "É necessário configurar um valor mínimo, em regras de vendas -> valor minimo";
      }

      if (!isset($prazo_entrega) || empty($prazo_entrega)) {
       $warning["prazo_entrega"] = "É necessário configurar prazo de entregas, em regras de vendas -> prazo de entregas";
       $log["prazo_entrega"] = false;
      }

      //   var_dump($warning); exit();

      $dom = new DOMDocument("1.0", "ISO-8859-1");

      #gerar o codigo
      $dom->formatOutput = true;

      #criando o nó principal (root)
      $root = $dom->createElement("Cotacao");

      #informações do cabeçalho
      $root->appendChild($dom->createElement("Tp_Movimento", '1'));
      $root->appendChild($dom->createElement("Dt_Gravacao", date("d/m/Y H:i:s", time())));
      $root->appendChild($dom->createElement("Cd_Fornecedor", preg_replace("/\D+/", "", $fornecedor['cnpj'])));
      $root->appendChild($dom->createElement("Cd_Cotacao", $item['cd_cotacao']));
      // $root->appendChild($dom->createElement("Cd_Cotacao", $cotacao['Cd_Cotacao']));
      $root->appendChild($dom->createElement("Cd_Condicao_Pagamento", (isset($forma_pagamento) && !empty($forma_pagamento)) ? $forma_pagamento : '1'));
      $root->appendChild($dom->createElement("Nm_Usuario", "rafael@biohosp"));
      $root->appendChild($dom->createElement("Ds_Observacao", '-'));
      $root->appendChild($dom->createElement("Qt_Prz_Minimo_Entrega", (isset($prazo_entrega) && !empty($prazo_entrega)) ? $prazo_entrega : '5'));
      $root->appendChild($dom->createElement("Vl_Minimo_Pedido", number_format($valor_minimo, 2, ',', '.')));

      $root->appendChild($dom->createElement("Ds_Observacao_Fornecedor", 'Observacao'));

      $produtosXML = $dom->createElement("Produtos_Cotacao");

      //    var_dump($arrayProdutos); exit();

      foreach ($arrayProdutos as $j => $prodsJ) {
       foreach ($prodsJ as $k => $prodsK) {

        $produtoXML = $dom->createElement("Produto_Cotacao");

        $produtoXML->appendChild($dom->createElement("Id_Produto_Sintese", $j));
        $produtoXML->appendChild($dom->createElement("Cd_Produto_Comprador", $k));

        $marcas_ofertas = $dom->createElement("Marcas_Oferta");

        foreach ($prodsK as $pdd) {

         if (intval($pdd['quantidade_unidade']) >= intval($produto['qt_produto_total'])) {

          $marca = $dom->createElement("Marca_Oferta");

          $marca->appendChild($dom->createElement("Id_Marca", $pdd['id_marca']));
          $marca->appendChild($dom->createElement("Ds_Marca", $pdd['marca']));
          $marca->appendChild($dom->createElement("Qt_Embalagem", $pdd['quantidade_unidade']));
          $marca->appendChild($dom->createElement("Vl_Preco_Produto", $pdd['preco_unidade']));
          $marca->appendChild($dom->createElement("Ds_Obs_Oferta_Fornecedor", "Validade: {$pdd['validade']}"));

          $marca->appendChild($dom->createElement("Cd_produtoERP", $pdd['codigo']));

          $valorTotalCotacao += floatval($ppp['preco_unidade']);
          $marcas_ofertas->appendChild($marca);

          break;

         }
        }

        $produtoXML->appendChild($marcas_ofertas);
        $produtosXML->appendChild($produtoXML);
       }
      }

      if ($valorTotalCotacao < $valor_minimo) {
       $warning["regra_vl_minimo"] = "O total da cotação não está atentendo o valor mínimo necessário";
      }

      /* ENVIAR COTACAO AUTOMATICA */

      $dir = "public/cot_automaticas/";

      if (isset($warning) && !empty($warning)) {

   //    throw new Exception("Cotação: {$item['cd_cotacao']} não atendida pois existem algumas pendências ...");

       //print_r("Cotação: {$item['cd_cotacao']} não atendida pois existem algumas pendências ...");

       $logWarning = fopen("{$dir}warning/{$item['cd_cotacao']}.json", "w+");

       $logs = fopen("{$dir}logs/NOT-ENV_{$item['cd_cotacao']}.json", "w+");

       fwrite($logWarning, json_encode($warning));
       
       fwrite($logs, json_encode($log));

       fclose($logWarning);

       fclose($logs);

       continue;

      } else {

       $root->appendChild($produtosXML);

       $root->appendChild($produtoXML);

       $dom->appendChild($root);

       $dom->preserveWhiteSpace = false;

       $simpleXML = new SimpleXMLElement($dom->saveXML());

       $xml = fopen("public/catacao.xml", "w+");

       fwrite($xml, $dom->saveXML());

       fclose($xml);

       $logs = fopen("{$dir}logs/ENV_{$item['cd_cotacao']}.json", "w+");

       fwrite($logs, json_encode($log));

       fclose($logs);

      }
     }
    }
   } //cotacao
   exit();
  }
 }
}
