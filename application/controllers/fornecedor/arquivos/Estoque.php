<?php

class Estoque extends CI_Controller
{

    public function __contruct()
    {
        parent::__construct();
    }


    public function index()
    {

        if ($this->input->method() == 'post') {

            $folder = APPPATH . "../uploads/arquivos/{$this->session->id_fornecedor}";
            $path = realpath($folder);

            while ($path == false) {
                mkdir($folder);
                $path = realpath($folder);
            }

            $config = array(
                'upload_path' => $path,
                'allowed_types' => 'csv',
                'overwrite' => 1,
            );


            $this->load->library('upload', $config);

            if ($this->upload->do_upload('file')) {

                $id_fornecedor = 5018;
                $data = $this->upload->data();
                $file = $data['full_path'];

                $csv = fopen($file, 'r');

                while (($line = fgetcsv($csv, null, ',')) !== false) {

                    //  $exist = $this->db->where('codigo', $line[0])->where('id_fornecedor', $id_fornecedor)->get('produtos_catalogo')->row_array();
                    if (strtoupper($line['0']) != 'CODIGO') {
                        if (empty($exist)) {
                            $insert[] = [
                                'codigo' => $line[0],
                                'lote' => $line[1],
                                'validade' => dbDateFormat($line[2]),
                                'estoque' => $line[3],
                                'id_fornecedor' => $id_fornecedor,
                            ];
                        }
                    }
                }

                $this->db->trans_start();

                $this->db->insert_batch('produtos_lote', $insert);

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();

                    $warning = [
                        'type' => 'warning',
                        'message' => 'Erro ao importar o arquivo, fale com o suporte'
                    ];

                } else {
                    $this->db->trans_commit();

                    $warning = [
                        'type' => 'success',
                        'message' => 'Importação realizada com sucesso!'
                    ];
                }




            } else {
                $warning = [
                    'type' => 'warning',
                    'message' => $this->upload->display_errors()
                ];
            }


            $this->output->set_content_type('application/json')->set_output(json_encode($warning));


        }


    }

    public function Catalogo()
    {

        if ($this->input->method() == 'post') {

            $folder = APPPATH . "../uploads/arquivos/{$this->session->id_fornecedor}";
            $path = realpath($folder);

            while ($path == false) {
                mkdir($folder);
                $path = realpath($folder);
            }

            $config = array(
                'upload_path' => $path,
                'allowed_types' => 'csv',
                'overwrite' => true,
            );


            $this->load->library('upload', $config);

            if ($this->upload->do_upload('file')) {

                $id_fornecedor = 5018;
                $data = $this->upload->data();
                $file = $data['full_path'];

                $csv = fopen($file, 'r');

                while (($line = fgetcsv($csv, null, ',')) !== false) {

                    $exist = $this->db->where('codigo', $line[0])->where('id_fornecedor', $id_fornecedor)->get('produtos_catalogo')->row_array();

                    if ($line['0'] != 'CODIGO') {
                        $insert[] = [
                            'codigo' => preg_replace('/[^0-9]/', '', $line[0]),
                            'nome_comercial' => $line[2],
                            'descricao' => $line[1],
                            'marca' => $line[3],
                            'rms' => $line[6],
                            'ean' => $line[7],
                            'unidade' => $line['5'],
                            'quantidade_unidade' => $line[4],
                            'id_fornecedor' => $id_fornecedor,
                            'ativo' => 1,
                            'codigo_externo' => $line[0]
                        ];
                    }

                }

                // unset($insert[0]);


                $this->db->insert_batch('produtos_catalogo', $insert);


                $warning = [
                    'type' => 'success',
                    'message' => 'Importação realizada com sucesso!'
                ];
            } else {
                $warning = [
                    'type' => 'warning',
                    'message' => $this->upload->display_errors()
                ];
            }


            $this->output->set_content_type('application/json')->set_output(json_encode($warning));


        }


    }


}