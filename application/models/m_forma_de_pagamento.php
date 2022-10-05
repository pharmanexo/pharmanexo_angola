<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_forma_de_pagamento extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
 
    public function listar_forma_pagamento_estado()
    {
        $id_fornecedor = $this->session->userdata("id_usuario");
        $this->db->select("fm.id,fp.descricao as forma_pagamento, fm.id_forma_pagamento,e.uf,e.descricao,fm.id_estado FROM formas_pagamento_fornecedores fm INNER JOIN  formas_pagamento fp  ON fp.id=fm.id_forma_pagamento  INNER JOIN  estados e ON e.id=fm.id_estado ", false);
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('id_cliente', NULL);
	$consulta = $this->db->get(); 
        
        if ($consulta->num_rows() > 0) { return $consulta; } else { return false; }
    }

    public function listar_forma_pagamento_cnpj()
    {
        $id_fornecedor = $this->session->userdata("id_usuario");
        $this->db->select(" fm.id_forma_pagamento,fp.descricao as forma_pagamento, fm.id, u.id_dados_usuario,u.id_endereco,u.tipo_usuario, u.cnpj, u.ativo , du.razao_social, du.integracao FROM formas_pagamento_fornecedores fm INNER JOIN  formas_pagamento fp  ON fp.id=fm.id_forma_pagamento INNER JOIN  usuarios u ON u.id=fm.id_cliente INNER JOIN dados_usuarios du ON du.id=u.id_dados_usuario INNER JOIN enderecos e ON e.id=u.id_endereco ", false);
        $this->db->where('fm.id_fornecedor', $id_fornecedor);
        $this->db->where('fm.id_estado', NULL);
	$consulta = $this->db->get(); 
        if ($consulta->num_rows() > 0) { return $consulta; } else { return false; }
    }


    public function gravar()
    {
        $id_fornecedor = $this->session->userdata("id_usuario");
        $id_tipo_venda=$this->session->userdata("id_tipo_venda");//1-markplace 2- integranexo 3- markplace/integranexo
        $forma_pagamento= $this->input->post("forma_pagamento");
        $opc=$this->input->post("opcao");
        if ($opc=='Todos Estados') {
            $this->load->model("m_estados");
            $estados= $this->m_estados->todosEstados();
            foreach ($estados->result() as $estado) { 
                $dados = array(
                    'id_fornecedor' =>$id_fornecedor, 
                    'id_estado'     =>$estado->id,
                    'id_tipo_venda' =>$id_tipo_venda,                     
                    'id_forma_pagamento'  =>$forma_pagamento 
                );
                $this->db->insert("formas_pagamento_fornecedores", $dados);
            }
        }
        if ($opc=='Estados Específicos') {
            $estados = $this->input->post("estado");

            $max = sizeof($estados);						  
            //
            $i = 0;
            while ($max > $i) {		
                $dados =  array( 
                    'id_fornecedor' =>$id_fornecedor, 
                    'id_estado'     =>$estados[$i] ,
                    'id_tipo_venda' =>$id_tipo_venda,                     
                    'id_forma_pagamento'  =>$forma_pagamento 
                );				
                $this->db->insert("formas_pagamento_fornecedores", $dados);
                $i = $i + 1;
            }            
        }
        if ($opc=='Todos CNPJ') {
            $this->load->model("m_usuarios");//clientes
            $clientes= $this->m_usuarios->todosUsuarios(2);	
            foreach ($clientes->result() as $cliente) { 
                $dados = array(
                    'id_fornecedor' =>$id_fornecedor, 
                    'id_cliente'    =>$cliente->id, 
                    'id_tipo_venda' =>$id_tipo_venda,                     
                    'id_forma_pagamento'  =>$forma_pagamento 
                );
                $this->db->insert("formas_pagamento_fornecedores", $dados);                
            }
        }
        if ($opc=='CNPJ Específicos') {
            $cnpjs = $this->input->post("cnpj");
            $max = sizeof($cnpjs);								  
            //
            $x = 0;	
            while ($max > $x) {		
                $dados =  array( 
                    'id_fornecedor' =>$id_fornecedor, 
                    'id_cliente'     =>$cnpjs[$x] ,
                    'id_tipo_venda' =>$id_tipo_venda,                     
                    'id_forma_pagamento'  =>$forma_pagamento 
                );				
                $this->db->insert("formas_pagamento_fornecedores", $dados);
                $x = $x + 1;
            }
        }
       // return;
    }
    public function todasFormasdePagamento()
    {
		$consulta = $this->db->get('formas_pagamento');  
		if ($consulta->num_rows() > 0) { return $consulta; } else { return false; }
    }
    
    public function excluir($id){
       $id_fornecedor = $this->session->userdata("id_usuario");
       $this->db->where('id', $id);
       $this->db->where('id_fornecedor', $id_fornecedor);	
       $this->db->delete('formas_pagamento_fornecedores'); 
       return ;
    }    
    
    
}

