<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Logs extends Admin_controller
{
    private $route, $views;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('admin/logs');
        $this->views = "admin/logs/";
    }

    /**
     * Exibe a view admin/logs/main.php
     *
     * @return  view
     */
    public function index()
    {
        $page_title = "Logs de Usuário";

        $data['datasource'] = "{$this->route}/datatables";
        $data['url_delete_multiple'] = "{$this->route}/delete_multiple/";
        $data['header'] = $this->template->header([ 'title' => $page_title ]);
        $data['navbar']  = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons'    => [
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
            ]
        ]);
        $data['scripts'] = $this->template->scripts();

        $data['usuarios'] = $this->db->get('usuarios')->result_array();

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Exibe o datatables de logs
     *
     * @return  json
     */
    public function datatables()
    {
        // $datatables = $this->datatable->exec(
        //     $this->input->post(),
        //     'ci_logs',
        //     [
        //         ['db' => 'id', 'dt' => 'id'],
        //         ['db' => 'action', 'dt' => 'action', 'formatter' => function($value, $row) {
        //             if ($value == "Login" || $value == "Logout") {
        //                return "{$value} - ID usuário: {$row['id_usuario']}";
        //             }
        //             return $value;
        //         }],
        //         ['db' => 'module', 'dt' => 'module'],
        //         ['db' => 'url', 'dt' => 'url', 'formatter' => function($value, $row) {
        //             if ($value == 0) {
        //                 return '';
        //             }

        //             return $value;
        //         }],
        //         ['db' => 'message', 'dt' => 'message'],
        //         ['db' => 'data', 'dt' => 'data',  'formatter' => function($data) {
        //             return date('d/m/Y H:i:s', strtotime($data));
        //         }],
        //         ['db' => 'id_usuario', 'dt' => 'id_usuario']
        //     ]
        // );

        $datatables = $this->datatable->exec(
            $this->input->post(),
            'user_audit_trails log',
            [
                ['db' => 'log.id', 'dt' => 'id'],
                ['db' => 'log.user_id', 'dt' => 'user_id'],
                ['db' => 'log.table_name', 'dt' => 'table_name'],
                ['db' => 'u.nome', 'dt' => 'nome_usuario'],
                ['db' => 'log.url', 'dt' => 'url'],
                ['db' => 'log.event', 'dt' => 'event', 'formatter' => function ($value, $row) {

                    switch ($value) {
                        case 'insert':
                            return 'Cadastro';
                            break;
                        case 'update':
                            return 'Atualização';
                            break;
                        case 'delete':
                            return 'Exclusão';
                        default:
                            return $value;
                            break;
                    }

                }],
                ['db' => 'log.created_at', 'dt' => 'created_at',  'formatter' => function($value, $row) {
                    return date('d/m/Y H:i:s', strtotime($value));
                }],
            ],
            [
                ['usuarios u', "u.id = log.user_id"]
            ]
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    /**
     * Função que exclui log
     *
     * @return  json
     */
    public function delete_multiple()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();


            if ($this->db->where_in('id', $post['el'])->delete('ci_logs')) {
                
                $output = ['type'    => 'success', 'message' => 'Excluidos com sucesso'];
            }
            else {

                $output = ['type'    => 'warning', 'message' => 'Erro ao excluir'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Gera arquivo excel do datatable
     *
     * @return  downlaod file
     */
    public function exportar()
    {

        $this->db->select("
            CASE 
                WHEN action = 'Login' OR action = 'Logout' THEN CONCAT(action, ' - ID usuário: ', id_usuario)
                ELSE action END AS acao,
                module AS modulo,
                CASE WHEN URL = 0 THEN '' ELSE url END AS url,
                DATE_FORMAT(data, '%d/%m/%Y %H:%i:%s') AS data
        ");
        $this->db->from("ci_logs");
        $this->db->order_by("data DESC");

        $query = $this->db->get()->result_array();

        if ( count($query) < 1 ) {
            $query[] = [
                'acao' => '',
                'modulo' => '',
                'url' => '',
                'data' => ''
            ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'logs'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }
}

/* End of file: logs.php */
