<?php
class M_catalogo extends MY_Model{

    public function __construct()
    {
        parent::__construct();

        $this->table = 'produtos_catalogo';
    }

    public function catalogo_distribuidor($where, $single = false){
       $result = $this->db
            ->select('c.*')
            ->from('catalogo_distribuidor cd')
            ->join('catalogo c', 'c.codprod = cd.codprod')
            ->where($where)
            ->get();

       if ($single){
           return $result->row_array();
       }else{
           return $result->result_array();
       }
    }
}