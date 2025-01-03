<?php

class ImportacaoPromocoes extends MY_Controller
{
    private $views;
    private $id_fornecedor;
    private $route;
    private $estados;

    public function __construct()
    {
        parent::__construct();

        $this->id_fornecedor = $this->session->id_fornecedor;

        $this->route = base_url("/fornecedor/arquivos/importacaopromocoes");
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
        $page_title = "Importação de Promoções";

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

        $data['urlImport'] = "{$this->route}/promocoes";

        $this->load->view("{$this->views}/main_promo", $data);
    }

    public function promocoes()
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

                        if (isset($this->estados[$line[1]])){
                            $estado = $this->estados[$line[1]];
                        }else{
                            var_dump($line);
                            exit();
                        }

                        $insert[] = [
                            'codigo' => $line[0],
                            'id_estado' => $estado['id'],
                            'id_fornecedor' => $this->id_fornecedor,
                            'promocao' => 1,
                            'lote' => $line[2],
                            'desconto_percentual' => str_replace(["-", "%", ","], ["", "", "."], $line[2]),
                            'regra_venda' => 3
                        ];


                    }
                }

                $this->db->trans_start();

                $this->db->insert_batch('vendas_diferenciadas', $insert);

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



}