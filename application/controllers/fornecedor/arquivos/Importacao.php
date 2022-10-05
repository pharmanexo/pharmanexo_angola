<?php

class Importacao extends MY_Controller
{
    private $views;
    private $id_fornecedor;
    private $route;
    private $estados;

    public function __construct()
    {
        parent::__construct();

        $this->id_fornecedor = $this->session->id_fornecedor;

        $this->route = base_url("/fornecedor/arquivos/importacao");
        $this->views = "fornecedor/arquivos/";

        $estadosArray = $this->db->get('estados')->result_array();

        foreach ($estadosArray as $estado){
            $this->estados[$estado['uf']] = [
                'id' => $estado['id'],
                'descricao' => $estado['descricao']
            ];
        }


    }

    public function index()
    {
        $page_title = "Importação de Arquivos";

        // TEMPLATE
        $data['header'] = $this->template->header(['title' => $page_title,]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
        ]);

        $data['scripts'] = $this->template->scripts([
            'scripts' => []
        ]);

        $data['urlEstoque'] = "{$this->route}/estoque";
        $data['urlPreco'] = "{$this->route}/precos";
        $data['urlCatalogo'] = "{$this->route}/catalogo";

        $this->load->view("{$this->views}/main", $data);
    }

    public function Precos()
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

                    if (count($line) < 3 || empty($line[0])){
                        continue;
                    }

                    //  $exist = $this->db->where('codigo', $line[0])->where('id_fornecedor', $id_fornecedor)->get('produtos_catalogo')->row_array();
                    if (strtoupper($line['0']) != 'CODIGO') {

                        $line[2] = trim(str_replace('R$ ', '', $line[2]));

                        if (strpos($line[2], ',', 0) == false){
                            $preco = floatval($line[2]);
                        }else{
                            $preco = number_format(dbNumberFormat($line[2]), 4, '.', '');

                        }


                        if (isset($this->estados[$line[1]])){
                            $estado = $this->estados[$line[1]];
                        }else{
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

    public function Estoque()
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

                $id_fornecedor = $this->id_fornecedor;
                $data = $this->upload->data();
                $file = $data['full_path'];
                $insert = [];
                $csv = fopen($file, 'r');

                while (($line = fgetcsv($csv, null, $post['separador'])) !== false) {

                    if (count($line) == 1) {
                        continue;
                    }
                    $codigo = intval(preg_replace('/[^0-9]/', '', $line[0]));

                    // $exist = $this->db->where('codigo', $codigo)->where('id_fornecedor', $id_fornecedor)->get('produtos_catalogo')->row_array();
                    if (strtoupper($line['0']) != 'CODIGO') {
                        if (empty($exist)) {
                            $insert[] = [
                                'codigo' => $codigo,
                                'lote' => $line[1],
                                'validade' => dbDateFormat($line[2]),
                                'estoque' => $line[3],
                                'id_fornecedor' => $id_fornecedor,
                            ];
                        }
                    }
                }


                $this->db->trans_start();

                /*         DELETA O ESTOQUE TODO              */
                $this->db->where('id_fornecedor', $this->id_fornecedor);
                $this->db->delete('produtos_lote');
                /*         DELETA O ESTOQUE TODO              */

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
                'overwrite' => true,
            );


            $this->load->library('upload', $config);

            if ($this->upload->do_upload('file')) {

                $id_fornecedor = $this->id_fornecedor;
                $data = $this->upload->data();
                $file = $data['full_path'];
                $insert = [];
                $csv = fopen($file, 'r');

                while (($line = fgetcsv($csv, null, $post['separador'])) !== false) {

                    if (count($line) == 1 || $line['0'] == 'CODIGO') {
                        continue;
                    }

                    $codigo = intval(preg_replace('/[^0-9]/', '', $line[0]));
                    $exist = $this->db->where('codigo', $codigo)->where('id_fornecedor', $id_fornecedor)->get('produtos_catalogo')->row_array();

                    if ($line['0'] != 'CODIGO' && empty($exist)) {

                        $qtd = soNumero($line[4]);
                        $insert[] = [
                            'codigo' => $codigo,
                            'nome_comercial' => $line[2],
                            'descricao' => $line[1] . " " . $line['5'],
                            'marca' => $line[3],
                            'rms' => soNumero($line[6]),
                            'ean' => soNumero($line[7]),
                            'unidade' => $line['5'],
                            'quantidade_unidade' => (empty($qtd) || $qtd == 0 ) ? 1 : $qtd,
                            'id_fornecedor' => $this->id_fornecedor,
                            'ativo' => 1,
                            'codigo_externo' => $line[0]
                        ];
                    }

                }

                if (!empty($insert)) {
                    $this->db->insert_batch('produtos_catalogo', $insert);

                    $warning = [
                        'type' => 'success',
                        'message' => 'Importação realizada com sucesso!'
                    ];
                }else{
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


}