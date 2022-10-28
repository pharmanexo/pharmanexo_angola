
<?php
/**
 * Criado por:  Marlon Boecker
 * Criado em: 31/07/2019 07:58
 */

class M_compradores extends MY_Model
{

    protected $table = 'compradores';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = '';
    protected $order_direction = 'ASC';


    public function __construct()
    {
        parent::__construct();
    }

    public function get_byCNPJ($cnpj)
    {
        $this->db->where("cnpj like '%{$cnpj}%'");
        return $this->db->get('compradores')->row_array();
    }

    public function getByCNPJ($cnpj)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('cnpj', $cnpj);
        $this->db->limit(1);
        return $this->db->get()->row_array();
    }
}
