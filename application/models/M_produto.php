<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_produto extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function CountAll()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $produto = $this->input->post('product');
        $marca = $this->input->post('marca');
        $this->db->select(" count(DISTINCT pf.id) AS numrows FROM produtos_fornecedores pf INNER JOIN marcas m ON m.id = pf.id_marca INNER JOIN produtos p ON p.id = pf.id_produto ", false);
        $this->db->where('pf.id_fornecedor', $id_fornecedor);
        $this->db->where('pf.ativo', 1);
        $this->db->where('pf.aprovado', 0);
        if ($produto != null) $this->db->like('p.produto_descricao', $produto, 'both');
        if ($marca != null) $this->db->where('pf.id_marca', $marca);
        $consulta = $this->db->get();
        return $consulta;
    }

    public function produtosFornecedorEmAprovacao($inicio, $qnt_result_pg)
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $marca = $this->input->post('marca');
        $produto = $this->input->post('product');
        $this->db->select(" pf.produto_descricao, pf.id, pf.id_marca, pf.marca, pf.id_sintese, pf.id_produto, pf.id_estado, pf.codigo,  pf.porcentagem_campanha, pf.ativo, pf.preco, pf.preco_unidade, pf.estoque AS quantidade, pf.quantidade_unidade from produtos_fornecedores pf  ", false);
        $this->db->where('pf.id_fornecedor', $id_fornecedor);
        $this->db->where('pf.ativo', 1);
        $this->db->where('pf.aprovado', 0);
        if ($marca != null) $this->db->where('pf.id_marca', $marca);
        if ($produto != null) $this->db->like('p.produto_descricao', $produto, 'both');
        $this->db->limit($qnt_result_pg, $inicio);
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0)
            return $consulta;
        else
            return false;
    }

    public function marcasFornecedor()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $marca = $this->input->post('marca');
        $this->db->select(" DISTINCT pf.id_marca, m.marca from produtos_fornecedores pf INNER JOIN marcas m ON m.id=pf.id_marca ", false);
        $this->db->where('pf.id_fornecedor', $id_fornecedor);
        $this->db->order_by('m.marca');
        if ($marca != null) $this->db->where('pf.id_marca', $marca);
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0)
            return $consulta;
        else
            return false;
    }

    function CountAllRecusado()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $produto = $this->input->post('product');
        $marca = $this->input->post('marca');
        $this->db->select(" count(DISTINCT pf.id) AS numrows FROM produtos_fornecedores pf INNER JOIN marcas m ON m.id = pf.id_marca INNER JOIN produtos p ON p.id = pf.id_produto ", false);
        $this->db->where('pf.id_fornecedor', $id_fornecedor);
        $this->db->where('pf.ativo', 1);
        $this->db->where('pf.aprovado', 3);
        if ($produto != null) $this->db->like('p.produto_descricao', $produto, 'both');
        if ($marca != null) $this->db->where('pf.id_marca', $marca);
        $consulta = $this->db->get();
        return $consulta;
    }

    public function produtosFornecedorRecusado($inicio, $qnt_result_pg)
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $marca = $this->input->post('marca');
        $produto = $this->input->post('product');
        $this->db->select(" p.produto_descricao,pf.motivo_recusa, pf.id, pf.id_marca, m.marca, pf.id_sintese, pf.id_produto, pf.id_estado,  pf.codigo, pf.porcentagem_campanha, pf.ativo, pf.preco, pf.preco_unidade, pf.estoque AS quantidade, pf.quantidade_unidade from produtos_fornecedores pf INNER JOIN marcas m ON m.id = pf.id_marca INNER JOIN produtos p ON p.id = pf.id_produto ", false);
        $this->db->where('pf.id_fornecedor', $id_fornecedor);
        $this->db->where('pf.ativo', 1);
        $this->db->where('pf.aprovado', 3);
        if ($marca != null) $this->db->where('pf.id_marca', $marca);
        if ($produto != null) $this->db->like('p.produto_descricao', $produto, 'both');
        $this->db->limit($qnt_result_pg, $inicio);
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0)
            return $consulta;
        else
            return false;
    }
    //
    public function gravar_venda_diferenciada()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $id_tipo_venda = $this->session->userdata("id_tipo_venda"); //1-markplace 2- integranexo 3- markplace/integranexo
        $desconto = floatval($this->input->post("desconto_percentual"));

        $opc = $this->input->post("opcao");
        if ($opc == 'Todos Estados') {
            $this->load->model("m_estados");
            $estados = $this->m_estados->todosEstados();

            $produtos = $this->input->post("prod");

            $max_p = sizeof($produtos);
            $p = 0;
            while ($max_p > $p) {
                foreach ($estados->result() as $estado) {
                    $dados = array(
                        'id_fornecedor' => $id_fornecedor,
                        'id_estado'     => $estado->id,
                        'id_produto'    => $produtos[$p],
                        'id_tipo_venda' => $id_tipo_venda,
                        'desconto_percentual'  => $desconto
                    );
                    $this->db->insert("vendas_diferenciadas", $dados);
                }
                $p = $p + 1;
            }
        }
        if ($opc == 'Estados Específicos') {
            $estados = $this->input->post("estado");
            $produtos = $this->input->post("prod");
            $max = sizeof($estados);
            $max_p = sizeof($produtos);
            $p = 0;
            while ($max_p > $p) {
                $i = 0;
                while ($max > $i) {
                    $dados =  array(
                        'id_fornecedor' => $id_fornecedor,
                        'id_estado'     => $estados[$i],
                        'id_produto'     => $produtos[$p],
                        'id_tipo_venda' => $id_tipo_venda,
                        'desconto_percentual'  => $desconto
                    );
                    $this->db->insert("vendas_diferenciadas", $dados);
                    $i = $i + 1;
                }
                $p = $p + 1;
            }
        }
        if ($opc == 'Todos CNPJ') {
            $this->load->model("m_usuarios"); //clientes
            $clientes = $this->m_usuarios->todosUsuarios();
            $produtos = $this->input->post("prod");
            $max_p = sizeof($produtos);
            $p = 0;
            while ($max_p > $p) {
                foreach ($clientes->result() as $cliente) {
                    $dados = array(
                        'id_fornecedor' => $id_fornecedor,
                        'id_cliente'    => $cliente->id,
                        'id_produto'     => $produtos[$p],
                        'id_tipo_venda' => $id_tipo_venda,
                        'desconto_percentual'  => $desconto
                    );
                    $this->db->insert("vendas_diferenciadas", $dados);
                }
                $p = $p + 1;
            }
        }
        if ($opc == 'CNPJ Específicos') {
            $cnpjs = $this->input->post("cnpj");
            $produtos = $this->input->post("prod");
            $max = sizeof($cnpjs);
            $max_p = sizeof($produtos);
            $p = 0;
            while ($max_p > $p) {
                $x = 0;
                while ($max > $x) {
                    $dados =  array(
                        'id_fornecedor' => $id_fornecedor,
                        'id_cliente'     => $cnpjs[$x],
                        'id_produto'     => $produtos[$p],
                        'id_tipo_venda' => $id_tipo_venda,
                        'desconto_percentual'  => $desconto
                    );
                    $this->db->insert("vendas_diferenciadas", $dados);
                    $x = $x + 1;
                }
                $p = $p + 1;
            }
        }
        // return;
    }
    //
    public function listar_vendas_diferenciadas_estado()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->select("p.produto_descricao, vd.id, vd.desconto_percentual,e.uf,pf.descricao,vd.id_estado FROM vendas_diferenciadas vd INNER JOIN produtos_fornecedores pf  ON pf.id = vd.id_produto INNER JOIN produtos p ON p.id = pf.id_produto INNER JOIN  estados e ON pf.id=vd.id_estado ", false);
        $this->db->where('vd.id_fornecedor', $id_fornecedor);
        $this->db->where('vd.id_cliente', NULL);
        $consulta = $this->db->get();

        if ($consulta->num_rows() > 0) {
            return $consulta;
        } else {
            return false;
        }
    }

    public function listar_vendas_diferenciadas_cnpj()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->select("p.produto_descricao, vd.desconto_percentual, vd.id, u.id_dados_usuario,u.id_endereco,u.tipo_usuario, u.cnpj, u.ativo , du.razao_social, du.integracao FROM vendas_diferenciadas vd INNER JOIN produtos_fornecedores pf  ON pf.id = vd.id_produto INNER JOIN produtos p ON p.id = pf.id_produto INNER JOIN  usuarios u ON u.id=vd.id_cliente INNER JOIN dados_usuarios du ON du.id=u.id_dados_usuario INNER JOIN enderecos e ON pf.id=u.id_endereco ", false);
        $this->db->where('vd.id_fornecedor', $id_fornecedor);
        $this->db->where('vd.id_estado', NULL);
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0) {
            return $consulta;
        } else {
            return false;
        }
    }
    //
    public function excluir_vendas_diferenciadas($id)
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->where('id', $id);
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->delete('vendas_diferenciadas');
        return;
    }
    //
    public function gravar_restricao()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $id_tipo_venda = $this->session->userdata("id_tipo_venda"); //1-markplace 2- integranexo 3- markplace/integranexo

        $opc = $this->input->post("opcao");
        if ($opc == 'Todos Estados') {
            $this->load->model("m_estados");
            $estados = $this->m_estados->todosEstados();

            $produtos = $this->input->post("prod");

            $max_p = sizeof($produtos);
            $p = 0;
            while ($max_p > $p) {
                foreach ($estados->result() as $estado) {
                    $dados = array(
                        'id_fornecedor' => $id_fornecedor,
                        'id_estado'     => $estado->id,
                        'id_produto'    => $produtos[$p],
                        'id_tipo_venda' => $id_tipo_venda
                    );
                    $this->db->insert("restricoes_produtos_clientes", $dados);
                }
                $p = $p + 1;
            }
        }
        if ($opc == 'Estados Específicos') {
            $estados = $this->input->post("estado");
            $produtos = $this->input->post("prod");
            $max = sizeof($estados);
            $max_p = sizeof($produtos);
            $p = 0;
            while ($max_p > $p) {
                $i = 0;
                while ($max > $i) {
                    $dados =  array(
                        'id_fornecedor' => $id_fornecedor,
                        'id_estado'     => $estados[$i],
                        'id_produto'     => $produtos[$p],
                        'id_tipo_venda' => $id_tipo_venda
                    );
                    $this->db->insert("restricoes_produtos_clientes", $dados);
                    $i = $i + 1;
                }
                $p = $p + 1;
            }
        }
        if ($opc == 'Todos CNPJ') {
            $this->load->model("m_usuarios"); //clientes
            $clientes = $this->m_usuarios->todosUsuarios();
            $produtos = $this->input->post("prod");
            $max_p = sizeof($produtos);
            $p = 0;
            while ($max_p > $p) {
                foreach ($clientes->result() as $cliente) {
                    $dados = array(
                        'id_fornecedor' => $id_fornecedor,
                        'id_cliente'    => $cliente->id,
                        'id_produto'     => $produtos[$p],
                        'id_tipo_venda' => $id_tipo_venda
                    );
                    $this->db->insert("restricoes_produtos_clientes", $dados);
                }
                $p = $p + 1;
            }
        }
        if ($opc == 'CNPJ Específicos') {
            $cnpjs = $this->input->post("cnpj");
            $produtos = $this->input->post("prod");
            $max = sizeof($cnpjs);
            $max_p = sizeof($produtos);
            $p = 0;
            while ($max_p > $p) {
                $x = 0;
                while ($max > $x) {
                    $dados =  array(
                        'id_fornecedor' => $id_fornecedor,
                        'id_cliente'     => $cnpjs[$x],
                        'id_produto'     => $produtos[$p],
                        'id_tipo_venda' => $id_tipo_venda
                    );
                    $this->db->insert("restricoes_produtos_clientes", $dados);
                    $x = $x + 1;
                }
                $p = $p + 1;
            }
        }
        // return;
    }
    //
    public function listar_restricoes_estado()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->select("p.produto_descricao, r.id,e.uf,pf.descricao,r.id_estado FROM restricoes_produtos_clientes r INNER JOIN produtos_fornecedores pf  ON pf.id = r.id_produto INNER JOIN produtos p ON p.id = pf.id_produto INNER JOIN  estados e ON pf.id=r.id_estado ", false);
        $this->db->where('r.id_fornecedor', $id_fornecedor);
        $this->db->where('r.id_cliente', NULL);
        $consulta = $this->db->get();

        if ($consulta->num_rows() > 0) {
            return $consulta;
        } else {
            return false;
        }
    }

    public function listar_restricoes_cnpj()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->select("p.produto_descricao, r.id, u.id_dados_usuario,u.id_endereco,u.tipo_usuario, u.cnpj, u.ativo , du.razao_social, du.integracao FROM restricoes_produtos_clientes r INNER JOIN produtos_fornecedores pf  ON pf.id = r.id_produto INNER JOIN produtos p ON p.id = pf.id_produto INNER JOIN  usuarios u ON u.id=r.id_cliente INNER JOIN dados_usuarios du ON du.id=u.id_dados_usuario INNER JOIN enderecos e ON pf.id=u.id_endereco ", false);
        $this->db->where('r.id_fornecedor', $id_fornecedor);
        $this->db->where('r.id_estado', NULL);
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0) {
            return $consulta;
        } else {
            return false;
        }
    }
    //
    public function excluir_restricoes($id)
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->where('id', $id);
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->delete('restricoes_produtos_clientes');
        return;
    }

    public function gravarNovoproduto()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $id_tipo_venda = $this->session->userdata("id_tipo_venda"); //1-markplace 2- integranexo 3- markplace/integranexo
        //'linha_hospitalar'=> 
        //'linha_farma'=> 
        //'linha_odonto'=>
        //'id_sintese'=> 
        //'id_produto'=> 
        //'id_marca'=>
        //'id_estado'=>
        //'preco_unidade'=> 
        //'id_unidade'=>
        $dados =  array(
            'id_fornecedor' => $id_fornecedor,
            'marca' => $this->input->post("marca"),
            'id_tipo_venda' => $this->input->post("tipo_venda"),
            'unidade' => $this->input->post("unidade"),
            'produto_descricao' => $this->input->post("produto"),
            'apresentacao' => $this->input->post("apresentacao"),
            'rms' => $this->input->post("rms"),
            'codigo' => $this->input->post("codigo"),
            'contra_proposta' => $this->input->post("contra_proposta"),
            'porcentagem_campanha' => $this->input->post("campanha_promocional"),
            'venda_parcelada' => $this->input->post("venda_parcelada"),
            'venda_parcelada' => $this->input->post("rms"),
            'quantidade' => $this->input->post("quantidade"),
            'quantidade_unidade' => $this->input->post("qtd_unidade"),
            'qtde_min_pedido' => $this->input->post("qtd_minima_pedido"),
            'qtde_total_venda' => $this->input->post("qtd_total_venda"),
            'valor_final_revenda' => $this->input->post("v_final_revenda")
        );

        //$this->db->insert("produtos_fornecedores", $dados);
        $this->db->insert("estoque", $dados);
        return;
    }

    public function alterarProduto($id)
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $id_tipo_venda = $this->session->userdata("id_tipo_venda"); //1-markplace 2- integranexo 3- markplace/integranexo
        //'linha_hospitalar'=>
        //'linha_farma'=>
        //'linha_odonto'=>
        //'id_sintese'=>
        //'id_produto'=>
        //'id_marca'=>
        //'id_estado'=>
        //'preco_unidade'=>
        //'id_unidade'=>

        $dados =  array(
            'id_fornecedor' => $id_fornecedor,
            'marca' => $this->input->post("marca"),
            'id_tipo_venda' => $this->input->post("tipo_venda"),
            'unidade' => $this->input->post("unidade"),
            'produto_descricao' => $this->input->post("produto"),
            'apresentacao' => $this->input->post("apresentacao"),
            'rms' => $this->input->post("rms"),
            'codigo' => $this->input->post("codigo"),
            'contra_proposta' => $this->input->post("contra_proposta"),
            'porcentagem_campanha' => $this->input->post("campanha_promocional"),
            'venda_parcelada' => $this->input->post("venda_parcelada"),
            'quantidade' => $this->input->post("quantidade"),
            'quantidade_unidade' => $this->input->post("qtd_unidade"),
            'qtde_min_pedido' => $this->input->post("qtd_minima_pedido"),
            'qtde_total_venda' => $this->input->post("qtd_total_venda"),
            'valor_final_revenda' => $this->input->post("v_final_revenda")
        );

        //$this->db->insert("produtos_fornecedores", $dados);

        $this->db->where("id", $id);
        $this->db->update("estoque", $dados);

        // var_dump($this->db->last_query());exit();


        return;
    }

    public function getProdutos()
    {
        $this->db->select('id, codigo, produto_descricao, marca, preco, validade, lote, estoque');
        $this->db->from('vw_produtos_fornecedores_validades');
        $this->db->where('id_fornecedor', $this->session->userdata('id_fornecedor'));
        if (!empty($this->session->estados)) $this->db->where('id_estado in ', "({$this->session->estados})");

        return $this->db->get()->result_array();
    }

    /**
     * Obtem preço de um produto
     *
     * @param   int  codigo do produto
     * @param   int  id do fornecedor
     * @param   flag para ativar registro por estado. default é preço brasil
     * @return  objeto
     */
    public function getPrice($codigo, $id_fornecedor, $porEstado = null)
    {
        $this->db->select('*');
        $this->db->where('codigo', $codigo);
        $this->db->where('id_fornecedor', $id_fornecedor);

        if ($porEstado == null) {
            $this->db->where('id_estado is null');
        } else {
            $this->db->where('id_estado is not null');
        }

        return $this->db->get('vw_produtos_precos')->row_array();
    }

    ////// Produtos Vencer

    public function totalItensPorPeriodo($id_fornecedor, $id_estado, $periodoInicial, $periodoFinal = null)
    {   

        # Gambiarra do estado
        if (in_array($id_fornecedor, [12, 104, 111, 115, 123, 120, 15, 180, 25])) {
            $estado = " = {$id_estado}";
        } elseif ($id_fornecedor == 112) {
            $estado = " = 9";
        } else {
            $estado = " is null";
        }

        # Gambiarra do preço
        if ( in_array($id_fornecedor, [12, 104, 111, 112, 115, 120, 123]) ) {
            $preco_total = "(pl.estoque * pp.preco_unitario) preco_total,";
        } else {
            $preco_total = "((pl.estoque * pc.quantidade_unidade) * pp.preco_unitario) preco_total,";
        }

        if (!isset($periodoFinal)) {
            $where = " WHERE x.validade > '{$periodoFinal}' ";
        } else {
            $where = " WHERE x.validade between '{$periodoInicial}' and '{$periodoFinal}' ";
        }

        if ( $this->session->id_fornecedor == 104 ) {
           
           $condicao_nestle_biohosp = "  AND ( pc.id_marca != 201 AND pc.marca not like '%nestle%' ) ";
        } else {

            $condicao_nestle_biohosp = "";
        }

        $query = "
            SELECT sum(x.preco_total) preco_total
            FROM (
                SELECT 
                    pl.codigo,
                    pl.id_fornecedor,
                    pp.id_estado,
                    IF(pc.quantidade_unidade is null, 1, pc.quantidade_unidade) quantidade_unidade,
                    pl.estoque,
                    (pl.estoque * IF(pc.quantidade_unidade is null, 1, pc.quantidade_unidade) ) estoque_total, 
                    pp.preco_unitario,
                    {$preco_total}
                    pl.lote,
                    pl.validade
                FROM pharmanexo.produtos_lote pl
                JOIN pharmanexo.produtos_catalogo pc 
                    on pc.codigo = pl.codigo
                    AND pc.id_fornecedor = pl.id_fornecedor
                    AND pc.ativo = 1
                    AND pc.bloqueado = 0
                JOIN pharmanexo.produtos_preco pp
                    ON pp.codigo = pl.codigo
                    AND pp.id_fornecedor = pl.id_fornecedor          
                WHERE pl.id_fornecedor = {$id_fornecedor}
                    AND pp.id_estado {$estado}
                    {$condicao_nestle_biohosp}
                    AND pp.data_criacao = (
                        CASE WHEN ISNULL(pp.id_estado) THEN (
                            SELECT MAX(pp2.data_criacao) 
                            FROM pharmanexo.produtos_preco pp2
                            WHERE pp2.id_fornecedor = pl.id_fornecedor
                                AND pp2.codigo = pl.codigo
                                AND pp2.id_estado is null)
                        ELSE (
                            SELECT MAX(pp2.data_criacao)
                            FROM pharmanexo.produtos_preco pp2
                            WHERE pp2.id_fornecedor = pl.id_fornecedor
                                AND pp2.codigo = pl.codigo
                                AND pp2.id_estado = pp.id_estado)
                    END)
                GROUP BY pl.codigo,
                    pl.id_fornecedor,
                    pp.id_estado,
                    pc.quantidade_unidade,
                    pl.estoque,
                    pp.preco_unitario,
                    pl.lote,
                    pl.validade
                HAVING estoque_total > 0
            ) x
            {$where}";

        $total = $this->db->query($query)->row_array();

        return ['total' => $total['preco_total'] ];
    }
}
