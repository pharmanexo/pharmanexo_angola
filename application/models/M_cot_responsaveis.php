<?php


class M_cot_responsaveis extends MY_Model
{
    protected $table = 'cot_responsaveis';

    public function __construct()
    {
        parent::__construct();
    }

    public function gravar()
    {
        $this->db->trans_begin();

        $id_fornecedor = $this->session->userdata("id_fornecedor");

        $id_consultor = $this->input->post('consultor');
        $id_assitente = $this->input->post('assistente');
        $id_gerente = $this->input->post('gerente');
        $elementos = explode(',', $this->input->post('elementos'));


        $dataNovo = [];
        $dataAtualizacao = [];

        foreach ($elementos as $key => $value) {

            $id = $this->verifyIfExists($value);

            if ($id) {

                $dataAtualizacao[] = [
                    'id' => $id,
                    'id_fornecedor' => $id_fornecedor,
                    'id_gerente' => $id_gerente,
                    'id_assistente' => $id_assitente,
                    'id_consultor' => $id_consultor,
                    'id_comprador' => $value
                ];

            } else {

                $dataNovo[] = [
                    'id_fornecedor' => $id_fornecedor,
                    'id_gerente' => $id_gerente,
                    'id_assistente' => $id_assitente,
                    'id_consultor' => $id_consultor,
                    'id_comprador' => $value
                ];
            }

        }

        if (!empty($dataNovo))
            $this->db->insert_batch($this->table, $dataNovo);

        if (!empty($dataAtualizacao))
            $this->db->update_batch($this->table, $dataAtualizacao, 'id');

        if ($this->db->trans_status() === false) {

            $this->db->trans_rollback();
            return false;
        } else {

            $this->db->trans_commit();
            return true;
        }
    }



    private function verifyIfExists($param)
    {
        $this->db->select('id');
        $this->db->from($this->table);


        $this->db->where('id_comprador', $param);
        $this->db->where("id_fornecedor", $this->session->id_fornecedor);

        $this->db->limit(1);
        $query = $this->db->get();

        return $query->row_array()['id'];
    }
}