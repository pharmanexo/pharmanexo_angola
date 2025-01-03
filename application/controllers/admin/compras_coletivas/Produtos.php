<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Produtos extends Admin_controller
{
    private $route, $views, $db_ades;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('admin/compras_coletivas/produtos');
        $this->views = "admin/compras_coletivas/produtos/";

        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_tipos_venda', 'tipos_venda');
        $this->load->model('CC_Produto', 'produto');

        $this->db_ades = $this->load->database('adesao', true);
    }

    public function insert()
    {
        if ($this->input->method() =='post' ){
            $post = $this->input->post();
            $files = $_FILES;
            if (isset($post['valor'])) $post['valor'] =  dbNumberFormat($post['valor']);

            $post['preco_500'] = $post['valor'];
            $post['preco_1000'] = $post['valor'];
            $post['preco_2000'] = $post['valor'];
            $post['preco_5000'] = $post['valor'];
            $post['preco_10000'] = $post['valor'];

            if ($id = $this->produto->insert($post)){

                #upload foto
                $foto = $this->doUpload('foto',  "contratos/Contrato{$id}");

                $ficha = $this->doUpload('ficha', "contratos/Contrato{$id}",'anexo_1.pdf','pdf');

                $update = [
                    'id' => $id,
                    'imagem' => $foto['data']['file_name'],
                    'contrato' => $id,
                    'url_ficha' => $ficha['data']['file_name']
                ];

                $this->produto->update($update);

            }else{
                var_dump($this->db->error());
            }



            #cadastra o produto

            #cria a pasta e faz upload dos arquivos

            #atualiza o produtos


        }else{
            $this->form();
        }
    }

    public function update($id)
    {
        if ($this->input->method() =='post' ){
            $post = $this->input->post();
            $files = $_FILES;
            if (isset($post['valor'])) $post['valor'] =  dbNumberFormat($post['valor']);

            $post['preco_500'] = $post['valor'];
            $post['preco_1000'] = $post['valor'];
            $post['preco_2000'] = $post['valor'];
            $post['preco_5000'] = $post['valor'];
            $post['preco_10000'] = $post['valor'];

            if ($this->produto->insert($post)){
                $id = $this->db_ades->insert_id();

                #upload foto
                $foto = $this->doUpload('foto',  "contratos/Contrato{$id}");

                $ficha = $this->doUpload('ficha', "contratos/Contrato{$id}",'anexo_1.pdf','pdf');

                $update = [
                    'id' => $id,
                    'imagem' => $foto['data']['file_name'],
                    'contrato' => $id
                ];

                var_dump($update);
                exit();

                $this->produto->update($update);

            }else{

            }



            #cadastra o produto

            #cria a pasta e faz upload dos arquivos

            #atualiza o produtos


        }else{
            $this->form($id);
        }
    }

    /**
     * Exibe a view admin/forncedores/main.php
     *
     * @param int $id
     * @return  view
     */
    public function index()
    {
        $page_title = "Fornecedores";
        $data['datasource'] = "{$this->route}/datatables";
        $data['url_update'] = "{$this->route}/update";
        $data['url_status'] = "{$this->route}/updateStatus/";
        $data['url_delete_multiple'] = "{$this->route}/delete_multiple/";
        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnInsert',
                    'url' => "{$this->route}/insert",
                    'class' => 'btn-primary',
                    'icone' => 'fa-plus',
                    'label' => 'Novo Registro'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/main", $data);
    }

    public function form($id = null)
    {
        $page_title = "Compras Coletivas - Produtos";
        $data['datasource'] = "{$this->route}/datatables";
        $data['url_update'] = "{$this->route}/atualizar";
        $data['url_status'] = "{$this->route}/updateStatus/";
        $data['url_delete_multiple'] = "{$this->route}/delete_multiple/";
        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type'  => 'a',
                    'id'    => 'btnBack',
                    'url'   => "{$this->route}",
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ],
                [
                    'type' => 'submit',
                    'id' => 'btnInsert',
                    'form' => "frmProduto",
                    'class' => 'btn-primary',
                    'icone' => 'fa-save',
                    'label' => ' Salvar'
                ],
            ]
        ]);
        $data['scripts'] = $this->template->scripts();
        $data['src_logo'] = base_url('images/image.png');

        $data['vendedores'] = $this->db_ades->select('id, nome_fantasia')->get('distribuidores')->result_array();

        if (isset($id)){
           $data['produto'] = $this->db_ades->where('id', $id)->get('produtos')->row_array();

        }


        $this->load->view("{$this->views}/form", $data);
    }


    public function datatables()
    {
        $datatables = $this->datatable->exec(
            $this->input->post(),
            'compras_coletivas.produtos prod',
            [
                ['db' => 'prod.id', 'dt' => 'id'],
                ['db' => 'prod.descricao', 'dt' => 'descricao'],
                ['db' => 'prod.id_vendedor', 'dt' => 'id_vendedor'],
                ['db' => 'd.nome_fantasia', 'dt' => 'nome_fantasia'],
                ['db' => 'prod.inicio_adesao', 'dt' => 'inicio_adesao', 'formatter' => function ($d) {

                    if (!empty($d)) {
                        return date('d/m/Y', strtotime($d));
                    } else {
                        return "-";
                    }
                }],
                ['db' => 'prod.fim_adesao', 'dt' => 'fim_adesao', 'formatter' => function ($d) {
                    if (!empty($d)) {
                        return date('d/m/Y', strtotime($d));
                    } else {
                        return "-";
                    }
                }],
            ],
            [
                ['compras_coletivas.distribuidores d', 'd.id = prod.id_vendedor']
            ]
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    private function doUpload($filename, $path, $name = null, $type = null)
    {
        unset($config);

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $config['upload_path'] = $path;
        $config['allowed_types'] = (isset($type)) ? $type : 'pdf|doc|jpeg|jpg|png|gif|doc|docx';
      //  $config['max_width'] = 2048;
       // $config['max_height'] = 768;

        if (isset($name)){
            $config['file_name'] = 'anexo_1.pdf';
        }else{
            $config['encrypt_name'] = true;
        }

        $this->load->library('upload');

        $this->upload->initialize($config);

        $r = $this->upload->do_upload($filename);

        if (!$r){
            var_dump($this->upload->display_errors());
        }

        return ['result' => $r , 'data' => $this->upload->data()];

    }
}

