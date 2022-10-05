<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_compra extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function CountAll()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $tipo_usuario = $this->session->userdata("tipo_usuario");
        $produto = $this->input->post('product');
        $marca = $this->input->post('marca');
        $this->db->select(" count(DISTINCT pf.id) AS numrows FROM produtos_fornecedores pf INNER JOIN marcas m ON m.id = pf.id_marca INNER JOIN produtos p ON p.id = pf.id_produto ", false);
        if ($tipo_usuario == 1) $this->db->where('pf.id_fornecedor', $id_fornecedor);//somente para fornecedores
        $this->db->where('pf.ativo', 1);
        $this->db->where('pf.estoque >', 0);
        $this->db->where('pf.preco_unidade >', 0);
        $this->db->where('pf.aprovado', 1);
        if ($produto != null) $this->db->like('p.produto_descricao', $produto, 'both');
        if ($marca != null) $this->db->where('pf.id_marca', $marca);
        $consulta = $this->db->get();
        return $consulta;
    }

    public function produtosFornecedor($inicio, $qnt_result_pg)
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $tipo_usuario = $this->session->userdata("tipo_usuario");
        $marca = $this->input->post('marca');
        $produto = $this->input->post('product');

        $this->db->select("*", false);
        if ($tipo_usuario == 1) $this->db->where('id_fornecedor', $id_fornecedor);//somente para fornecedores
        $this->db->where('ativo', 1);
        $this->db->where('aprovado', 1);
        $this->db->where('quantidade >', 0);
        $this->db->where('preco_unidade >', 0);
        if ($marca != null) $this->db->where('pf.id_marca', $marca);
        if (isset($produto) && !empty($produto)) $this->db->having("produto_descricao like '%{$produto}%' " );
        $this->db->limit($qnt_result_pg, $inicio);
        $this->db->order_by("produto_descricao ASC, validade ASC");
        $consulta = $this->db->get("vw_produtos");

        if ($consulta->num_rows() > 0)
            return $consulta;
        else
            return false;
    }

    public function destaques()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $tipo_usuario = $this->session->userdata("tipo_usuario");

        $this->db->select("p.imagem_produto, p.produto_descricao, pf.id, pf.id_marca, m.marca, pf.id_sintese, pf.id_produto, pf.id_estado,  pf.codigo,  pf.porcentagem_campanha, pf.ativo, pf.preco, pf.preco_unidade, pf.estoque as quantidade, pf.quantidade_unidade from produtos_fornecedores pf INNER JOIN marcas m ON m.id = pf.id_marca INNER JOIN produtos p ON p.id = pf.id_produto ", false);
        $this->db->where('pf.ativo', 1);
        $this->db->where('pf.aprovado', 1);
        $this->db->where('pf.destaque', 1);
        $this->db->where('pf.preco_unidade > 0');
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0)
            return $consulta;
        else
            return false;
    }

    public function buscaProduto($id)
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $tipo_usuario = $this->session->userdata("tipo_usuario");

        $this->db->select(" p.produto_descricao, pf.id, pf.id_marca, m.marca, pf.id_sintese, pf.id_produto, pf.id_estado,  pf.codigo,  pf.porcentagem_campanha, pf.ativo, pf.preco, pf.preco_unidade, pf.estoque AS quantidade , pf.quantidade_unidade from produtos_fornecedores pf INNER JOIN marcas m ON m.id = pf.id_marca INNER JOIN produtos p ON p.id = pf.id_produto ", false);
        if ($tipo_usuario == 1) $this->db->where('pf.id_fornecedor', $id_fornecedor);//somente para fornecedores
        $this->db->where('pf.ativo', 1);
        $this->db->where('pf.aprovado', 1);
        $this->db->where('pf.id', $id);
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0)
            return $consulta;
        else
            return false;
    }

    public function marcasFornecedor()
    {
        $tipo_usuario = $this->session->userdata("tipo_usuario");
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $marca = $this->input->post('marca');
        $this->db->select(" DISTINCT pf.id_marca, m.marca from produtos_fornecedores pf INNER JOIN marcas m ON m.id=pf.id_marca ", false);
        if ($tipo_usuario == 1) $this->db->where('pf.id_fornecedor', $id_fornecedor);//somente para fornecedores
        $this->db->order_by('m.marca');
        if ($marca != null) $this->db->where('pf.id_marca', $marca);
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0)
            return $consulta;
        else
            return false;
    }

    public function excluir($id)
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->where('id', $id);
        $this->db->where('id_fornecedor', $id_fornecedor);
        // $this->db->delete('estoque');
        $this->db->set('ativo', 0, FALSE);
        $this->db->update('estoque');
        return;
    }

    //   
    public function colocar_produto_carrinho($id, $qtd_produto_carrinho)
    {
        $id_cliente = $this->session->userdata("id_fornecedor");
        $ch0 = (string)1;
        $ch1 = (string)$id_cliente;
        list($ano, $mes, $dia) = explode('-', date('Y-m-d'));
        $ch2 = (string)$ano . (string)$mes . (string)$dia;
        $chave = $this->session->userdata("chave");
        if (empty($chave)) { // criar o carrinho
            $this->db->select(" count(id_cliente) AS  contador FROM carrinhos ", false);
            $this->db->where('id_cliente', $id_cliente);
            $consulta = $this->db->get();
            if ($consulta->num_rows() == 0) {
                // criar a chave 1+id_cliente+ano+mes+dia
                $chave = $ch0 . $ch1 . $ch2;
                // criar carrinho
                $dados = array(
                    'id_cliente' => $id_cliente,
                    'chave' => $chave,
                    'status' => 'Inserindo',
                    'ativo' => 1
                );
                $this->db->insert("carrinhos", $dados);
                // pegar id carrinho
                $id_carrinho = $this->db->insert_id();
                // montar a session da chave
                $this->session->set_userdata("chave", $chave);
            } else {
                // criar a chave count()+1+id_cliente+ano+mes+dia
                $ch0 = (string)$consulta->row()->contador + 1;
                $chave = $ch0 . $ch1 . $ch2;
                // criar carrinho
                $dados = array(
                    'id_cliente' => $id_cliente,
                    'chave' => $chave,
                    'status' => 'Inserindo',
                    'ativo' => 1
                );
                $this->db->insert("carrinhos", $dados);
                // pegar id carrinho
                $id_carrinho = $this->db->insert_id();
                // montar a session da chave
                $this->session->set_userdata("chave", $chave);
            }

        } else {
            $this->db->where('chave', $chave);
            $consulta = $this->db->get('carrinhos');
            $id_carrinho = $consulta->row()->id;
        }
        //verificar se o produto  existe  no carrinho
        $this->db->where('id', $id);//id do produto
        $this->db->where('id_carrinho', $id_carrinho);
        $consulta = $this->db->get('produtos_carrinho');

        if ($consulta->num_rows() == 0) {
            // se NÃO existir o produto no carrinho  inserir o produto
            $dados = array(
                'id_cliente' => $id_cliente,
                'id_produto_fornecedor' => $id,
                'id_carrinho' => $id_carrinho,
                'quantidade' => $qtd_produto_carrinho
            );
            $this->db->insert("produtos_carrinho", $dados);
        } else {
            // se existir o produto no carrinho  sobrescrever updade 
            $dados = array(
                'id_cliente' => $id_cliente,
                'id_produto_fornecedor' => $id,
                'id_carrinho' => $id_carrinho,
                'quantidade' => $qtd_produto_carrinho
            );
            $this->db->where('id', $id);//id do produto
            $this->db->where('id_carrinho', $id_carrinho);
            $this->db->update("produtos_carrinho", $dados);
        }
        return;
    }

    //
    public function listar_produto_carrinho()
    {
        $id_cliente = $this->session->userdata("id_fornecedor");
        $chave = $this->session->userdata("chave");
        $this->db->select("*");
        $this->db->where('chave', $chave);
        //$this->db->where('c.status', 'Inserindo');
        $this->db->where('id_cliente', $id_cliente);
        $consulta = $this->db->get('vw_carrinhos_produtos');
        if ($consulta->num_rows() > 0)
            return $consulta;
        else
            return false;
    }

    //
    public function retirar_produto_carrinho($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('produtos_carrinho');
        return;
    }

    public function limpar_carrinho()
    {
        $chave = $this->session->userdata("chave");
        $this->db->where('chave', $chave);
        $consulta = $this->db->get('carrinhos');
        $id_carrinho = $consulta->row()->id;
        //
        $this->db->where('id_carrinho', $id_carrinho);
        $this->db->delete('produtos_carrinho');
        //
        $this->db->where('chave', $chave);
        $this->db->delete('carrinhos');
        //
        $this->session->set_userdata("chave", '');
        return;
    }

    //   
    public function gerar_pedido()
    {
        $id_cliente = $this->session->userdata("id_fornecedor");
        $chave = $this->session->userdata("chave");
        $this->db->select(" pc.*, p.produto_descricao,  m.marca, pf.id_sintese, pf.id_produto, pf.id_estado,  pf.codigo,  pf.porcentagem_campanha, pf.ativo, pf.preco, pf.preco_unidade, pf.estoque, pf.quantidade_unidade,pf.id_fornecedor FROM carrinhos c INNER JOIN produtos_carrinho pc ON pc.id_carrinho=c.id INNER JOIN produtos_fornecedores pf ON pf.id=pc.id_produto_fornecedor INNER JOIN marcas m ON m.id = pf.id_marca   INNER JOIN produtos p ON p.id=pf.id_produto", false);
        $this->db->where('c.chave', $chave);
        $this->db->where('c.id_cliente', $id_cliente);
        //$this->db->where('c.status', 'Inserindo');
        $this->db->order_by("pf.id_fornecedor", "asc");
        $consulta = $this->db->get();
        $id_fornecedor = 0;
        $valor_total = 0.00;
        if ($consulta) {
            foreach ($consulta->result() as $ln) {
                if ($ln->id_fornecedor != $id_fornecedor) {

                    //cria o pedido por fornecedor                    
                    $id_fornecedor = $ln->id_fornecedor;
                    $dados = array(
                        'id_carrinho' => $ln->id_carrinho,
                        'id_cliente' => $id_cliente,
                        'id_fornecedor' => $ln->id_fornecedor

                    );
                    /*
                        'id_forma_pagamento_fornecedor' =>$ln->id_forma_pagamento, 
                        'id_prazo_entrega' =>$ln->id_prazo_entrega, 
                        'id_tipo_venda' =>$ln->id_tipo_venda, 
                        'valor_total' =>$valor_total
                     */
                    $this->db->insert("pedidos", $dados);
                    $id_pedido = $this->db->insert_id();
                }
                //insere os produtos no pedido- será usado para Analise por fornecedor e  geração de OC
                $dados = array(
                    'id_pedido' => $id_pedido,
                    'id_cliente' => $id_cliente,
                    'id_produto' => $ln->id_produto_fornecedor,
                    'id_fornecedor' => $ln->id_fornecedor,
                    'id_carrinho' => $ln->id_carrinho,
                    'quantidade' => $ln->quantidade
                );
                $this->db->insert("pedidos_produtos_fornecedores", $dados);
            }
        }
        //coloca o carrinho em analise 
        $dados = array(
            'status' => 'Em Analise',
            'ativo' => 0
        );
        $this->db->where('id', $ln->id_carrinho);
        $this->db->update("carrinhos", $dados);
        return;
    }

    public function listar_oc_fornecedor()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->where('id_fornecedor', $id_fornecedor);
        $consulta = $this->db->get('ordens_compra');
        if ($consulta->num_rows() > 0)
            return $consulta;
        else
            return false;
    }
//
    //
    public function listarProdutoOC($id)
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $id_ordem_compra = $id;
        $this->db->select(" poc.* FROM ordens_compra oc INNER JOIN produtos_ordem_compra poc ON poc.id_ordem_compra=oc.id ", false);
        $this->db->where('poc.id_ordem_compra', $id_ordem_compra);
        $this->db->where('oc.id_fornecedor ', $id_fornecedor);
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0)
            return $consulta;
        else
            return false;
    }

    // busca os dados do carrinho
    public function get_cart($chave)
    {
        if (!isset($chave)) return [];

        $this->db->where('chave', $chave);

        return $this->db->get('vw_carrinhos')->row_array();

    }
}
