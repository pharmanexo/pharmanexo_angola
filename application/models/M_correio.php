<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_correio extends MY_Model
{

    protected $table = 'correio';
    
    public function getMensagens()
    {
        $this->db->select('*');
        $this->db->from('correio');

        return $this->db->get()->result_array();
    }

    public function getMessageId($id)
    {
        $this->db->select('cor.assunto, cor.mensagem, cor.dt_registro, rem.nome as nm_remetente, rem.email as em_remetente, des.email as em_destinatario');
        $this->db->from('correio cor');
        $this->db->join('usuarios rem', 'rem.id = cor.id_user_remetente', 'INNER');
        $this->db->join('usuarios des', 'des.id = cor.id_user_destinatario', 'INNER');
        $this->db->where('cor.id', $id);
        
        return$this->db->get()->row_array();
    }

}

/* End of file: M_correio.php */
