<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_match extends MY_Model
{

    protected $table = 'produtos_pre_depara';

    public function doMatch($prods)
    {
        if (!is_array($prods)) return false;
        $insert = [];

        $this->db->trans_begin();

        foreach ($prods as $prod) {
            $this->db->where('cd_produto', $prod['codigo']);
            $this->db->where('id_sintese', $prod['id_sintese']);
            $this->db->where('id_fornecedor', $this->session->id_fornecedor);

            $data = $this->db->get('produtos_pre_depara')->row_array();

            if (!empty($data)) {
                $insert[] = $data;
            }
        }

        $this->db->insert_batch('produtos_fornecedores_sintese', $insert);


        // exclui os produtos que foram inseridos na tabela definitiva
        foreach ($prods as $prod) {
            $this->db->where('cd_produto', $prod['codigo']);
            $this->db->where('id_sintese', $prod['id_sintese']);
            $this->db->where('id_fornecedor', $this->session->id_fornecedor);

            $this->db->delete('produtos_pre_depara');
        }

        $status = $this->db->trans_status();
        if ($status === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        return $status;

    }

    public function undoMatch($prods)
    {
        if (!is_array($prods)) return false;
        $insert = [];
        $codigos = [];

        $this->db->trans_begin();


        $this->db->insert_batch('produtos_fornecedores_sintese', $insert);


        // exclui os produtos que foram inseridos na tabela definitiva
        foreach ($prods as $prod) {
            $this->db->where('cd_produto', $prod['codigo']);
            $this->db->where('id_sintese', $prod['id_sintese']);
            $this->db->where('id_fornecedor', $this->session->id_fornecedor);

            $this->db->delete('produtos_pre_depara');

            $codigos[] = $prod['codigo'];
        }

        $this->updateCatalogo($codigos);

        $status = $this->db->trans_status();
        if ($status === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        return $status;

    }

    private function updateCatalogo($produtos)
    {
        $data = [
            'ocultar_de_para' => 0,
            'ativo' => 1,
            'bloqueado' => 0
        ];

        $this->db->trans_begin();

        foreach ($produtos as $produto) {

            $this->db->where('cd_produto', $produto);
            $this->db->where('id_fornecedor', $this->session->id_fornecedor);

            $count = $this->db->get('produtos_pre_depara')->result_array();

            if (empty($count)) {
                $this->db->where('codigo', $produto);
                $this->db->where('id_fornecedor', $this->session->id_fornecedor);

                $this->db->update('produtos_catalogo', $data);
            }

        }


        $status = $this->db->trans_status();
        if ($status === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        return $status;
    }

}

/* End of file: M_correio.php */
