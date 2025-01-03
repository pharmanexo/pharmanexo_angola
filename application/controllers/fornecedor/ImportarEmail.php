<?php

ini_set('display_errors', '1');
ini_set('memory_limit', '256M');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

class ImportarEmail extends CI_Controller
{

    private $mix;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('fornecedor/ImportarEmail');
        $this->views = 'fornecedor/importar/';
    }

    public function index()
    {
        $page_title = 'Importar E-mails';
        # Template
        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => []
        ]);
        $data['fornecedores'] = $this->db->get('fornecedores')->result_array();
        $data['url_lista'] = "{$this->route}/lista";
        $data['url_importar'] = "{$this->route}/importar";

        $this->load->view("{$this->views}/importarEmail", $data);
    }

    public function importar()
    {

        if ($this->input->method() == 'post') {
            $post = $this->input->post();

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

                $data = $this->upload->data();
                $file = $data['full_path'];
                $insert = [];
                $csv = fopen($file, 'r');

                while (($line = fgetcsv($csv, null, $post['separador'])) !== false) {

                    if (count($line) < 3 || empty($line[0])) {
                        continue;
                    }

                    //  $exist = $this->db->where('codigo', $line[0])->where('id_fornecedor', $id_fornecedor)->get('produtos_catalogo')->row_array();
                    if (strtoupper($line['0']) != 'CODIGO') {

                        $line[2] = trim(str_replace('R$ ', '', $line[2]));

                        if (strpos($line[2], ',', 0) == false) {
                            $preco = floatval($line[2]);
                        } else {
                            $preco = number_format(dbNumberFormat($line[2]), 4, '.', '');
                        }


                        if (isset($this->estados[$line[1]])) {
                            $estado = $this->estados[$line[1]];
                        } else {
                            $estado['id'] = null;
                        }


                        $insert[] = [
                            'codigo' => intval(preg_replace('/[^0-9]/', '', $line[0])),
                            'id_estado' => $estado['id'],
                            'preco_unitario' => $preco,
                            'id_fornecedor' => $this->id_fornecedor,
                        ];
                    }
                }

                $this->db->trans_start();

                $this->db->insert_batch('produtos_preco', $insert);

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

    public function lista()
    {
        // Configure the uploading settings
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = '5000';
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file')) {
            $error = array('error' => $this->upload->display_errors());
            echo json_encode($error);
        } else {
            $data = array('upload_data' => $this->upload->data());
            echo json_encode($data);
        }

        $file = fopen('./uploads/'.$data['upload_data']['file_name'], 'r');
        var_dump($file);
        exit;
        $column_names = fgetcsv($file, 0, ",");
        fclose($file);
        var_dump($column_names);
        exit;
    }

    public function lista1()
    {
        $file = 'public/ListaEmailHospidrogasGo.csv';
        //hospidrogas GO
        $id_fornecedor = 5046;

        $csv = fopen($file, 'r');

        $lista_cnpj = [];
        $lista_email = [];
        $is_first_row = true;
        $new_rows = 0;
        while (($data = fgetcsv($csv, 1000, ",")) !== FALSE) {
            if ($is_first_row) {
                $is_first_row = false;
                continue;
            }
            $new_rows++;
            $lista_cnpj[] = $data[0];
            $lista_email[] = $data[7] . $data[8] . $data[9] . $data[10] . $data[11];
            if ($new_rows  == 1) {
                //implode the data
                $cnpj = implode("','", $lista_cnpj);
                $email = implode(",", $lista_email);

                $query = $this->db->get_where('compradores', array('cnpj' => $cnpj));
                $result = $query->row();

                if (!empty($result)) {
                    $data = array(
                        'id_fornecedor' => $id_fornecedor,
                        'id_cliente' => intval($result->id),
                        'geral' => $email
                    );

                    $this->db->insert('email_notificacao', $data);
                }
                //reset the counter and array
                $new_rows = 0;
                $lista_cnpj = [];
                $lista_email = [];
            }
        }
        fclose($file);
    }
}
