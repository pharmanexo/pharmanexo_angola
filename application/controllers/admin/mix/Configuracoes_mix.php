<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Configuracoes_mix extends Admin_controller
{

    private $route;
    private $views;
    private $oncoprod;
    private $MIX;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url("admin/mix/configuracoes_mix/");
        $this->views = "admin/mix/configuracoes_mix/";

        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_compradores', 'comprador');
        $this->load->model('m_estados', 'estado');
        $this->oncoprod = explode(',', ONCOPROD);

        $this->MIX = $this->load->database('mix', TRUE);
    }

    public function index()
    {
        $page_title = "Configurações MIX";

        $data['to_datatable_estado'] = "{$this->route}to_datatable_estado";
        $data['to_datatable_cnpj'] = "{$this->route}to_datatable_cnpj";

        $data['url_update_estado'] = "{$this->route}openModal";
        $data['url_update_cnpj'] = "{$this->route}openModal";


        $data['url_delete'] = "{$this->route}delete_multiple";
        $data['estados'] = $this->estado->getList();
        $data['compradores'] = $this->comprador->find("id, CONCAT(cnpj, ' - ', razao_social) as comprador");
        $data['fornecedores'] = $this->fornecedor->find("id, CONCAT(cnpj, ' - ', nome_fantasia) as fornecedor", 'sintese = 1');

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'styles' => [
                THIRD_PARTY . 'plugins/bootstrap-duallistbox/css/bootstrap-duallistbox.css'
            ]
        ]);

        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/bootstrap-duallistbox/js/jquery.bootstrap-duallistbox.js'
            ]
        ]);

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
                    'url' => "{$this->route}export",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel',
                    'label' => 'Exportar Excel'
                ],
                [
                    'type' => 'a',
                    'id' => 'btnAdicionar',
                    'url' => "{$this->route}openModal",
                    'class' => 'btn-primary',
                    'icone' => 'fa-plus',
                    'label' => 'Novo Registro'
                ]
            ]
        ]);

        $this->load->view("{$this->views}main", $data);
    }

    public function save()
    {
        if ($this->input->is_ajax_request()) {

            $post = $this->input->post();

            $tipo = ( $post['type'] == 1 ) ? 'id_estado' : 'id_cliente';
            $label = ( $post['type'] == 1 ) ? 'estado' : 'comprador';

            $this->db->trans_begin();

            foreach (explode(',', $post['elementos']) as $id) {

                if ( $post['type'] == 1 ) {

                    $name = $this->estado->findById($id)['descricao'];
                } else {

                    $c = $this->comprador->findById($id);

                    $name = "{$c['cnpj']} - {$c['razao_social']}";
                }

                # Verifica se o fornecedor informado já possui registro para o estado/comprador
                $this->MIX->select('*');
                $this->MIX->where('id_fornecedor', $post['id_fornecedor']);
                $this->MIX->where($tipo, $id);
                $item = $this->MIX->get('fornecedores_mix_provisorio')->row_array();

                if ( empty($item) ) {

                    # Verifica se já existe a prioridade informada para o estado/comprador
                    $this->MIX->select('*');
                    $this->MIX->where('id_fornecedor !=', $post['id_fornecedor']);
                    $this->MIX->where($tipo, $id);
                    $this->MIX->where('prioridade', $post['prioridade']);
                    $msmPrioridade = $this->MIX->get('fornecedores_mix_provisorio')->row_array();

                    if ( !empty($msmPrioridade) ) {
                       
                        $output = ['type' => 'warning', 'message' => "Falha ao realizar ação! Já existe registro com esta prioridade para o {$label}: {$name}."];
                        $error = true;
                        break;
                    } else {

                        $data = [
                            "id_fornecedor" => $post['id_fornecedor'],
                            "{$tipo}" => $id,
                            "desconto_mix" => floatval($post['desconto_mix']),
                            "prioridade" => floatval($post['prioridade']),
                        ];

                        $novo = $this->MIX->insert('fornecedores_mix_provisorio', $data);

                        if ( $novo ) {
                            
                            $output = ['type' => 'success', 'message' => notify_create];
                        } else {

                            $output = ['type' => 'warning', 'message' => notify_failed];
                        }
                    }
                } else {

                    $output = ['type' => 'warning', 'message' => "Falha ao realizar ação! Já existe registro para o {$label}: {$name}"];
                    $error = true;
                    break;
                }
            }


            if ($this->db->trans_status() === FALSE || isset($error)) {

                $this->db->trans_rollback();
            } else {

                $this->db->trans_commit();
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function update($id)
    {
        $post = $this->input->post();

        $registro = $this->MIX->where('id', $id)->get('fornecedores_mix_provisorio')->row_array();

        $tipo = ( isset($registro['id_estado']) && !empty($registro['id_estado']) ) ? 'id_estado' : 'id_cliente';
        $label = ( isset($registro['id_estado']) && !empty($registro['id_estado']) ) ? 'estado' : 'comprador';

        # Verifica se já existe a prioridade informada para o estado/comprador
        $this->MIX->select('*');
        $this->MIX->where('id_fornecedor !=', $registro['id_fornecedor']);
        $this->MIX->where($tipo, $registro[$tipo]);
        $this->MIX->where('prioridade', $post['prioridade']);
        $msmPrioridade = $this->MIX->get('fornecedores_mix_provisorio')->row_array();

        if (!empty($msmPrioridade) ) {

            $output = ['type' => 'warning', 'message' => "Falha ao realizar ação! Já existe registro com esta prioridade para este {$label}"];
        } else {

            $data = [
                'desconto_mix' => floatval($post['desconto_mix']),
                'prioridade' => $post['prioridade']
            ];

            $this->MIX->where('id', $id);
            $update = $this->MIX->update('fornecedores_mix_provisorio', $data);

            if ( $update ) {

                $output = ['type' => 'success', 'message' => notify_update];
            } else {

                $output = ['type' => 'warning', 'message' => notify_failed];
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    public function delete_multiple()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $this->db->trans_begin();

            foreach ($post['itens'] as $item) {

                $this->MIX->where('id', $item['id']);
                $this->MIX->delete('fornecedores_mix_provisorio');
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();

                $output = ['type'    => 'warning', 'message' => 'Erro ao excluir'];
            }
            else {
                $this->db->trans_commit();

                $output = ['type'    => 'success', 'message' => 'Excluidos com sucesso'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function openModal($id = null)
    {

        if ( isset($id) ) {

            $data['title'] = "Atualizar Desconto MIX";
            $data['form_action'] = "{$this->route}update/{$id}";
            $data['dados'] = $this->MIX->select('*')
                ->from('fornecedores_mix_provisorio')
                ->where('id', $id)
                ->get()->row_array();

            $data['isUpdate'] = true;
        } else {

            $data['title'] = "Novo Desconto MIX";
            $data['form_action'] = "{$this->route}save";
            $data['fornecedores'] = $this->fornecedor->find("id, CONCAT(cnpj, ' - ', nome_fantasia) as fornecedor", 'sintese = 1');
            $data['url_list'] = "{$this->route}getList";
        }

        $this->load->view("{$this->views}modal", $data);
    }

    public function getList()
    {
        if ( $this->input->is_ajax_request() ) {

            $post = $this->input->post();

            if ( $post['type'] == 1 ) {

                $data = $this->estado->find("id, CONCAT(uf, ' - ', descricao) AS value");
            } else {

                $data = $this->comprador->find("id, CONCAT(cnpj, ' - ', razao_social) as value");
            }

            $output = ['type' => (empty($data) ? 'warning' : 'success'), 'data' => $data];

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function list_priority($id_estado)
    {
        $this->MIX->select('prioridade');
        $this->MIX->from('fornecedores_mix_provisorio');
        $this->MIX->where('id_estado', $id_estado);
        $prioridades = $this->MIX->get()->result_array();

        $data = [];


        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function to_datatable_estado()
    {

        $data = $this->datatable->exec(
            $this->input->post(),
            'mix.fornecedores_mix_provisorio mix',
            [
                ['db' => 'mix.id', 'dt' => 'id'],
                ['db' => 'mix.id_estado', 'dt' => 'id_estado'],
                ['db' => 'mix.id_fornecedor', 'dt' => 'id_fornecedor'],
                ['db' => 'mix.prioridade', 'dt' => 'prioridade'],
                ['db' => 'mix.desconto_mix', 'dt' => 'desconto_mix'],
                ['db' => 'e.uf', 'dt' => 'uf'],
                ['db' => 'f.cnpj', 'dt' => 'cnpj'],
                ['db' => 'f.nome_fantasia', 'dt' => 'fornecedor', "formatter" => function ($value, $row) {

                    return $row['cnpj'] . " - " . $value;
                }],
                ['db' => 'e.descricao', 'dt' => 'estado', "formatter" => function ($value, $row) {

                    return $row['uf'] . " - " . $value;
                }]
            ],
            [
                ['estados e', 'e.id = mix.id_estado'],
                ['fornecedores f', 'f.id = mix.id_fornecedor'],
            ],
            "mix.id_cliente is null"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function to_datatable_cnpj()
    {

        $data = $this->datatable->exec(
            $this->input->post(),
            'mix.fornecedores_mix_provisorio mix',
            [
                ['db' => 'mix.id', 'dt' => 'id'],
                ['db' => 'mix.id_cliente', 'dt' => 'id_cliente'],
                ['db' => 'mix.id_fornecedor', 'dt' => 'id_fornecedor'],
                ['db' => 'mix.prioridade', 'dt' => 'prioridade'],
                ['db' => 'mix.desconto_mix', 'dt' => 'desconto_mix'],
                ['db' => 'c.cnpj', 'dt' => 'cnpjComprador'],
                ['db' => 'f.cnpj', 'dt' => 'cnpj'],
                ['db' => 'f.nome_fantasia', 'dt' => 'fornecedor', "formatter" => function ($value, $row) {

                    return $row['cnpj'] . " - " . $value;
                }],
                ['db' => 'c.razao_social', 'dt' => 'comprador', "formatter" => function ($value, $row) {

                    return $row['cnpjComprador'] . " - " . $value;
                }]
            ],
            [
                ['compradores c', 'c.id = mix.id_cliente'],
                ['fornecedores f', 'f.id = mix.id_fornecedor'],
            ],
            "mix.id_estado is null"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function export()
    {
        $this->MIX->select("CONCAT(e.uf, ' - ', e.descricao) AS estado, CONCAT(f.cnpj, ' - ', f.nome_fantasia) AS fornecedor, mix.prioridade, mix.desconto_mix");
        $this->MIX->from("mix.fornecedores_mix_provisorio mix");
        $this->MIX->join('pharmanexo.estados e', 'e.id = mix.id_estado');
        $this->MIX->join('pharmanexo.fornecedores f', 'f.id = mix.id_fornecedor');
        $this->MIX->where('mix.id_cliente is null');
        $this->MIX->order_by('estado ASC');

        $query_estados = $this->MIX->get()->result_array();

        $this->MIX->select("CONCAT(c.cnpj, ' - ', c.razao_social) AS comprador, CONCAT(f.cnpj, ' - ', f.nome_fantasia) AS fornecedor, mix.prioridade, mix.desconto_mix");
        $this->MIX->from("mix.fornecedores_mix_provisorio mix");
        $this->MIX->join('pharmanexo.compradores c', 'c.id = mix.id_cliente');
        $this->MIX->join('pharmanexo.fornecedores f', 'f.id = mix.id_fornecedor');
        $this->MIX->where('mix.id_estado is null');
        $this->MIX->order_by('comprador ASC');

        $query_clientes = $this->MIX->get()->result_array();

        if ( count($query_estados) < 1 ) {
            $query_estados[] = [
                'estado' => '',
                'fornecedor' => '',
                'prioridade' => '',
                'desconto' => ''
            ];
        }

        if ( count($query_clientes) < 1 ) {
            $query_clientes[] = [
                'comprador' => '',
                'fornecedor' => '',
                'prioridade' => '',
                'desconto' => ''
            ];
        }

        $dados_page1 = ['dados' => $query_estados, 'titulo' => 'Estados'];
        $dados_page2 = ['dados' => $query_clientes, 'titulo' => 'Compradores'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page1, $dados_page2);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }
    }
}
