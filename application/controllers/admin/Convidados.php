<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Convidados extends CI_Controller
{
    private $route, $views;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('admin/convidados');
        $this->views = "admin/convidados";


        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_compradores', 'compradores');
    }

    public function index()
    {
        $page_title = "Compradores";

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
                    'type' => 'button',
                    'id' => 'btnDeleteMultiple',
                    'url' => "",
                    'class' => 'btn-danger',
                    'icone' => 'fa-trash',
                    'label' => 'Excluir Selecionados'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel',
                    'label' => 'Exportar Excel'
                ],
                [
                    'type' => 'a',
                    'id' => 'btnInsert',
                    'url' => "{$this->route}/criar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-plus',
                    'label' => 'Novo Registro'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/main", $data);
    }

    public function importarPromocoes()
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

                $id_fornecedor = $post['id_fornecedor'];
                $data = $this->upload->data();
                $file = $data['full_path'];

                $csv = fopen($file, 'r');
                $lines = [];
                $insert = [];

                while (($line = fgetcsv($csv, null, $post['separador'])) !== false) {

                    $lines[] = $line;
                }

                unset($lines[0]);

                foreach ($lines as $line) {

                    $insert[] = [
                        'codigo' => $line[0],
                        'descricao' => $line[1],
                        'marca' => $line[2],
                        'qtd_embalagem' => (intval($line[3]) > 0) ? intval($line[3]) : 1,
                        'unidade' => 'UND',
                        'quantidade' => $line[4],
                        'lote' => $line[5],
                        'validade' => dbDateFormat(trim($line[6])),
                        'preco' => dbNumberFormat(trim(str_replace('R$', '', $line[7]))),
                        'data_cadastro' => date('Y-m-d h:i:s', time()),
                        'situacao' => 1,
                        'id_fornecedor' => $id_fornecedor,
                    ];

                }

                $this->db->trans_start();

                //delete todos registros anteriores
                $this->db->where('id_fornecedor', $id_fornecedor)->delete('conv_promocoes');

                $this->db->insert_batch('conv_promocoes', $insert);

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
            }

            $_SESSION['warning'] = $warning;
            redirect("{$this->route}/importarPromocoes");


        } else {
            $page_title = "Importar Promoções";

            $data['fornecedores'] = $this->db->order_by('nome_fantasia ASC')->get('fornecedores')->result_array();

            $data['header'] = $this->template->header(['title' => $page_title]);
            $data['navbar'] = $this->template->navbar();
            $data['sidebar'] = $this->template->sidebar();
            $data['heading'] = $this->template->heading([
                'page_title' => $page_title,
                'buttons' => []
            ]);
            $data['scripts'] = $this->template->scripts();
            $data['form_action'] = "{$this->route}/importarPromocoes";

            $this->load->view("{$this->views}/importarPromocoes", $data);
        }
    }
}
