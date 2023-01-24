<?php
defined('BASEPATH') OR exit('No direct script access allowed');

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

class M_forma_pagamento extends MY_Model {

    protected $table = 'formas_pagamento';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = 'id';
    protected $order_direction = 'ASC';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtem a descricao da forma de pagamento
     *
     * @param - String nome do integrador
     * @param - INT ID da forma de pagamento

     * @return  string
     */
    public function getFormaPagamento($integrador, $id)
    {

        # Caso o Id forma de pagamento seja o da bionexo, faz o depara
    	if ( strtoupper($integrador) == 'BIONEXO' ) {

    		$this->db->where('integrador', 2);
    		$this->db->where('cd_forma_pagamento', $id);
    		$depara = $this->db->get('formas_pagamento_depara')->row_array();

    		$forma_pagamento = ( isset($depara) && !empty($depara) ) ? $depara : null;
       	} else {

            $forma_pagamento = $this->db->where("id", $id)->get($this->table)->row_array();
        }



        return ( isset($forma_pagamento) && !empty($forma_pagamento) ) ? $forma_pagamento['descricao'] : '';
    }


    /**
     * Lista as formas de pagamento de acordo com o integrador
     *
     * @param - String nome do integrador

     * @return  array
     */
    public function listar($integrador)
    {

        if ( $integrador == 'SINTESE' ) {

            $this->db->select("id_forma_pagamento AS id, descricao");
            $this->db->where('integrador', 1);
        } else {

            $this->db->select("cd_forma_pagamento AS id, descricao");
            $this->db->where('integrador', 2);
            $this->db->where('id_forma_pagamento != 0');
        }

        return $this->db->get('formas_pagamento_depara')->result_array();
    }
}

/* End of file: M_forma_pagamento.php */
