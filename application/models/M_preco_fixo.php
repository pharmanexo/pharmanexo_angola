<?php


class M_preco_fixo extends MY_Model
{
    protected $table = 'mix.produtos_preco_mix';

    public function __construct()
    {
        parent::__construct();
    }


    public function atualizar($data)
    {
        if (isset($data['id_cliente'])){
            $this->db->where('id_cliente', $data['id_cliente']);
        }

        if (isset($data['id_estado'])){
            $this->db->where('id_estado', $data['id_estado']);
        }

        if (isset($data['codigo'])){
            $this->db->where('codigo', $data['codigo']);
        }

        if (isset($data['id_fornecedor'])){
            $this->db->where('id_fornecedor', $data['id_fornecedor']);
        }

        return $this->db->update($this->table, ['preco_base' => $data['preco_base']]);


    }

    public function deletar($data)
    {
        if (isset($data['id_cliente'])){
            $this->db->where('id_cliente', $data['id_cliente']);
        }

        if (isset($data['id_estado'])){
            $this->db->where('id_estado', $data['id_estado']);
        }

        if (isset($data['codigo'])){
            $this->db->where('codigo', $data['codigo']);
        }

        if (isset($data['id_fornecedor'])){
            $this->db->where('id_fornecedor', $data['id_fornecedor']);
        }

        return $this->db->delete($this->table);


    }


}
