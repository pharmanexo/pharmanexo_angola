<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_match extends MY_Model
{

    protected $table = 'produtos_pre_match';

    public function __construct()
    {
        parent::__construct();
        $this->bio = $this->load->database('bionexo', true);

    }


    public function doMatch($prods, $user = null)
    {
        if (!is_array($prods)) return false;
        $insert = [];
        $log = [];
        $id_usuario = $this->session->id_usuario;
        $usuariosMatch = [421, 387, 15];
        $this->db->trans_begin();

        foreach ($prods as $prod) {
            $this->db->where('cd_produto', $prod['codigo']);
            $this->db->where('id_sintese', $prod['id_sintese']);
            $this->db->where('id_fornecedor', $this->session->id_fornecedor);

            $data = $this->db->get('produtos_pre_match')->row_array();



            if (!empty($data)) {



                if (array_search($id_usuario, $usuariosMatch) ) {

                    $data['id_usuario'] = $id_usuario;

                    $dt['id_usuario'] = $this->session->id_usuario;
                    $dt['id_produto'] = $data['cd_produto'];
                    $dt['id_cliente'] = $this->session->id_fornecedor;
                    $dt['integrador'] = 1;
                    $dt['distribuidor'] = $this->session->id_fornecedor;

                    $log[] = $dt;
                }

                $insert[] = $data;

            }
        }


        $this->db->insert_batch('log_de_para', $log);
        $this->db->insert_batch('produtos_fornecedores_sintese', $insert);


        // exclui os produtos que foram inseridos na tabela definitiva
        foreach ($prods as $prod) {
            $this->db->where('cd_produto', $prod['codigo']);
            $this->db->where('id_sintese', $prod['id_sintese']);
            $this->db->where('id_fornecedor', $this->session->id_fornecedor);

            $this->db->delete('produtos_pre_match');
        }

        $status = $this->db->trans_status();
        if ($status === FALSE) {
            $error = $this->db->error();
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

            $this->db->delete('produtos_pre_match');

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

            $count = $this->db->get('produtos_pre_match')->result_array();

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

    public function doMatchClient($prods)
    {
        if (!is_array($prods)) return false;
        $insert = [];
        $log = [];

        $this->db->trans_begin();

        foreach ($prods as $prod) {
            $insert[] = [
                'id_produto_sintese' => $prod['id_produto'],
                'cd_produto' => $prod['codigo'],
                'id_integrador' => 2,
                'id_usuario' => 999,
                'id_cliente' => $prod['id_cliente'],
            ];

            $log[] = [
                'id_usuario' => $this->session->id_usuario,
                'id_produto' => $prod['id_produto'],
                'id_cliente' => $prod['id_cliente'],
                'integrador' => 2
            ];

            $this->bio->where('id_cliente', $prod['id_cliente']);
            $this->bio->where('codigo', $prod['codigo']);
            $this->bio->update('catalogo', ['ocultar' => 1]);

        }


        //insert depara
        $this->db->insert_batch('produtos_clientes_depara', $insert);


        // registra no log
        $this->db->insert_batch('log_de_para', $log);


        // exclui os produtos que foram inseridos na tabela definitiva
        foreach ($prods as $prod) {

            $this->db->where('cd_produto', $prod['codigo']);
            $this->db->where('id_produto', $prod['id_produto']);
            $this->db->where('id_cliente', $prod['id_cliente']);

            $this->db->delete('produtos_pre_depara');

        }

        $status = $this->db->trans_status();
        if ($status === FALSE) {
            $this->db->trans_rollback();
            var_dump($this->db->error());
        } else {
            $this->db->trans_commit();
        }

        return $status;

    }

    public function undoMatchClient($prods)
    {
        if (!is_array($prods)) return false;

        $this->db->trans_begin();

        // exclui os produtos que foram inseridos na tabela definitiva
        foreach ($prods as $prod) {

            $this->db->where('cd_produto', $prod['codigo']);
            $this->db->where('id_produto', $prod['id_produto']);
            $this->db->where('id_cliente', $prod['id_cliente']);

            $this->db->delete('produtos_pre_depara');

        }

        $status = $this->db->trans_status();
        if ($status === FALSE) {
            $this->db->trans_rollback();
            var_dump($this->db->error());
        } else {
            $this->db->trans_commit();
        }

        return $status;

    }


}

/* End of file: M_correio.php */
