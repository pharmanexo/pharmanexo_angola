<?php

class ImportacaoEstudo extends MY_Controller
{
    private $views;
    private $id_fornecedor;
    private $route;
    private $estados;

    public function __construct()
    {
        parent::__construct();

        $this->id_fornecedor = $this->session->id_fornecedor;

        $this->route = base_url("/fornecedor/arquivos/importacaoestudo");
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
        $page_title = "Importação de Estudo";

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

        $data['urlImport'] = "{$this->route}/import";


        $this->load->view("{$this->views}/main_estudo", $data);
    }

    public function import()
    {

        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $folder = APPPATH . "../public/arquivos/{$this->session->id_fornecedor}/estudos";
            $path = realpath($folder);

            while ($path == false) {
                mkdir($folder);
                $path = realpath($folder);
            }

            $config = array(
                'upload_path' => $path,
                'allowed_types' => 'csv|xls|xlsx|pdf',
                'overwrite' => 1,
                'encrypt_name' => true,
            );


            $this->load->library('upload', $config);

            if ($this->upload->do_upload('file')) {
                $data = $this->upload->data();
                $file = $data['full_path'];
             //   echo base_url(PUBLIC_PATH . "arquivos/{$this->session->id_fornecedor}/estudos/{$data['file_name']}");

                $this->db->trans_start();


                $insert = [
                    'link' => $data['file_name'],
                    'titulo' => $post['titulo'],
                    'id_fornecedor' => $this->session->id_fornecedor
                ];

                $this->db->insert('estudos', $insert);

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