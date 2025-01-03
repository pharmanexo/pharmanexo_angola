<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_preco_mix extends CI_Model
{
    private $DB_MIX;
    private $table;

    public function __construct()
    {
        parent::__construct();
        $this->DB_MIX = $this->load->database('mix', TRUE);

        $this->table = 'produtos_preco_mix';
    }

    public function get_item($data)
    {
        $get = $this->DB_MIX
            ->where('id_cliente', $data['id_cliente'])
            ->where('id_fornecedor', $data['id_fornecedor'])
            ->where('codigo', $data['codigo'])
            ->get($this->table)
            ->row_array();

        return $get;
    }

    public function insert($data)
    {

        if (!empty($data)) {
            return $this->DB_MIX->insert($this->table, $data);
        } else {
            return false;
        }

    }

    public function update($data)
    {

        $update = [
            'preco_base' => $data['preco_base']
        ];

        if (isset($data['preco_mix']))
        {
            $update['preco_mix'] = $data['preco_base'];
        }

        return $this->DB_MIX
            ->where('id_cliente', $data['id_cliente'])
            ->where('id_fornecedor', $data['id_fornecedor'])
            ->where('codigo', $data['codigo'])
            ->update($this->table, $update);


    }


}


