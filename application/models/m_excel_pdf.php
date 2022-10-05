<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_excel_pdf extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    public function listar_valor_minimo_estado()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->select("vm.id, vm.valor_minimo,e.uf,e.descricao,vm.id_estado FROM valor_minimo_cliente vm INNER JOIN  estados e ON e.id=vm.id_estado ", false);
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('id_cliente', NULL);
	$consulta = $this->db->get(); 
        
        if ($consulta->num_rows() > 0) { return $consulta; } else { return false; }
    }

    public function listar_valor_minimo_cnpj()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->select(" vm.valor_minimo, vm.id, u.id_dados_usuario,u.id_endereco,u.tipo_usuario, u.cnpj, u.ativo , du.razao_social, du.integracao FROM valor_minimo_cliente vm INNER JOIN  usuarios u ON u.id=vm.id_cliente INNER JOIN dados_usuarios du ON du.id=u.id_dados_usuario INNER JOIN enderecos e ON e.id=u.id_endereco ", false);
        $this->db->where('vm.id_fornecedor', $id_fornecedor);
        $this->db->where('vm.id_estado', NULL);
	$consulta = $this->db->get(); 
        if ($consulta->num_rows() > 0) { return $consulta; } else { return false; }
    }
    //
    public function listar_prazo_entrega_estado()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->select("pe.id, pe.prazo,e.uf,e.descricao,pe.id_estado FROM prazos_entrega pe INNER JOIN  estados e ON e.id=pe.id_estado ", false);
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('id_cliente', NULL);
	$consulta = $this->db->get(); 
        
        if ($consulta->num_rows() > 0) { return $consulta; } else { return false; }
    }

    public function listar_prazo_entrega_cnpj()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->select(" pe.prazo, pe.id, u.id_dados_usuario,u.id_endereco,u.tipo_usuario, u.cnpj, u.ativo , du.razao_social, du.integracao FROM prazos_entrega pe INNER JOIN  usuarios u ON u.id=pe.id_cliente INNER JOIN dados_usuarios du ON du.id=u.id_dados_usuario INNER JOIN enderecos e ON e.id=u.id_endereco ", false);
        $this->db->where('pe.id_fornecedor', $id_fornecedor);
        $this->db->where('pe.id_estado', NULL);
	$consulta = $this->db->get(); 
        if ($consulta->num_rows() > 0) { return $consulta; } else { return false; }
    }
    //
    public function listar_forma_pagamento_estado()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->select("fm.id,fp.descricao as forma_pagamento, fm.id_forma_pagamento,e.uf,e.descricao,fm.id_estado FROM formas_pagamento_fornecedores fm INNER JOIN  formas_pagamento fp  ON fp.id=fm.id_forma_pagamento  INNER JOIN  estados e ON e.id=fm.id_estado ", false);
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('id_cliente', NULL);
	$consulta = $this->db->get(); 
        
        if ($consulta->num_rows() > 0) { return $consulta; } else { return false; }
    }

    public function listar_forma_pagamento_cnpj()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->select(" fm.id_forma_pagamento,fp.descricao as forma_pagamento, fm.id, u.id_dados_usuario,u.id_endereco,u.tipo_usuario, u.cnpj, u.ativo , du.razao_social, du.integracao FROM formas_pagamento_fornecedores fm INNER JOIN  formas_pagamento fp  ON fp.id=fm.id_forma_pagamento INNER JOIN  usuarios u ON u.id=fm.id_cliente INNER JOIN dados_usuarios du ON du.id=u.id_dados_usuario INNER JOIN enderecos e ON e.id=u.id_endereco ", false);
        $this->db->where('fm.id_fornecedor', $id_fornecedor);
        $this->db->where('fm.id_estado', NULL);
	$consulta = $this->db->get(); 
        if ($consulta->num_rows() > 0) { return $consulta; } else { return false; }
    }
    //
    public function listar_vendas_diferenciadas_estado()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->select("p.produto_descricao, vd.id, vd.desconto_percentual,e.uf,e.descricao,vd.id_estado FROM vendas_diferenciadas vd INNER JOIN produtos_fornecedores pf ON pf.id = vd.id_produto INNER JOIN produtos p ON p.id = pf.id_produto INNER JOIN  estados e ON e.id=vd.id_estado ", false);
        $this->db->where('vd.id_fornecedor', $id_fornecedor);
        $this->db->where('vd.id_cliente', NULL);
	$consulta = $this->db->get(); 
        
        if ($consulta->num_rows() > 0) { return $consulta; } else { return false; }
    }

    public function listar_vendas_diferenciadas_cnpj()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->select("p.produto_descricao, vd.desconto_percentual, vd.id, u.id_dados_usuario,u.id_endereco,u.tipo_usuario, u.cnpj, u.ativo , du.razao_social, du.integracao FROM vendas_diferenciadas vd INNER JOIN produtos_fornecedores pf ON pf.id = vd.id_produto INNER JOIN produtos p ON p.id = pf.id_produto INNER JOIN  usuarios u ON u.id=vd.id_cliente INNER JOIN dados_usuarios du ON du.id=u.id_dados_usuario INNER JOIN enderecos e ON e.id=u.id_endereco ", false);
        $this->db->where('vd.id_fornecedor', $id_fornecedor);
        $this->db->where('vd.id_estado', NULL);
	$consulta = $this->db->get(); 
        if ($consulta->num_rows() > 0) { return $consulta; } else { return false; }
    }    
    //
    public function listarProdutoOC($id){
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $id_ordem_compra= $id;
        $this->db->select(" poc.* FROM ordens_compra oc INNER JOIN produtos_ordem_compra poc ON poc.id_ordem_compra=oc.id ", false);
        $this->db->where('poc.id_ordem_compra',  $id_ordem_compra);
        $this->db->where('oc.id_fornecedor ', $id_fornecedor );	
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0)
            return $consulta;
        else
            return false;
    }
    
    public function listar_restricoes_estado()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->select("p.produto_descricao, r.id,e.uf,e.descricao,r.id_estado FROM restricoes_produtos_clientes r INNER JOIN produtos_fornecedores pf ON pf.id = r.id_produto INNER JOIN produtos p ON p.id = pf.id_produto INNER JOIN  estados e ON e.id=r.id_estado ", false);
        $this->db->where('r.id_fornecedor', $id_fornecedor);
        $this->db->where('r.id_cliente', NULL);
	$consulta = $this->db->get(); 
        
        if ($consulta->num_rows() > 0) { return $consulta; } else { return false; }
    }

    public function listar_restricoes_cnpj()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->select("p.produto_descricao, r.id, u.id_dados_usuario,u.id_endereco,u.tipo_usuario, u.cnpj, u.ativo , du.razao_social, du.integracao FROM restricoes_produtos_clientes r INNER JOIN produtos_fornecedores pf ON pf.id = r.id_produto INNER JOIN produtos p ON p.id = pf.id_produto INNER JOIN  usuarios u ON u.id=r.id_cliente INNER JOIN dados_usuarios du ON du.id=u.id_dados_usuario INNER JOIN enderecos e ON e.id=u.id_endereco ", false);
        $this->db->where('r.id_fornecedor', $id_fornecedor);
        $this->db->where('r.id_estado', NULL);
	$consulta = $this->db->get(); 
        if ($consulta->num_rows() > 0) { return $consulta; } else { return false; }
    }    
    
    
    
    
    
}
