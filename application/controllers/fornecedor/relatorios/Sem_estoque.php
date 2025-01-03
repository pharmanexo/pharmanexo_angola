<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sem_estoque extends MY_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/relatorios/sem_estoque');
        $this->views = 'fornecedor/relatorios/sem_estoque';
    }

    public function index()
    {
        $page_title = 'Relatório de cotações com itens sem estoque';

        $data['cotacoes'] = $this->datatable_cotacoes();
        $data['url_details'] = "{$this->route}/details";

        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar_cotacoes",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel',
                    'label' => 'Exportar Excel'
                ],
            ]
        ]);
        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/main", $data);
    }

    public function details($cd_cotacao)
    {
        $page_title = "Produtos sem estoque da cotação #{$cd_cotacao}";

        $data['to_datatable'] = "{$this->route}/datatables_itens/{$cd_cotacao}";

        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnBack',
                    'url' => $this->route,
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar_itens/{$cd_cotacao}",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel',
                    'label' => 'Exportar Excel'
                ],
            ]
        ]);
        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/details", $data);
    }

    public function datatable_cotacoes()
    {
        $this->db->select("cd_cotacao, DATE_FORMAT(data_criacao, '%d/%m/%Y %H:%i') AS data, DATE_FORMAT(data_criacao, '%Y/%m/%d %H:%i') AS data_criacao, razao_social");
        $this->db->from('vw_rel_sem_estoque_anoAtual');
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);
        $this->db->group_by('cd_cotacao');

        $data = $this->db->get()->result_array();

        return $data;
    }

    public function datatables_itens($cd_cotacao)
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'produtos_sem_estoque',
            [
                ['db' => 'PC.marca', 'dt' => 'marca'],
                ['db' => 'PC.descricao', 'dt' => 'descricao'],
                ['db' => 'PC.apresentacao', 'dt' => 'apresentacao'],
                ['db' => 'PC.nome_comercial', 'dt' => 'produto', 'formatter' => function ($value, $row) {

                    if (!empty($row['descricao'])) {
                        return "{$value} - {$row['descricao']}";
                    }

                    return "{$value} - {$row['apresentacao']}";
                }],
            ],
            [
                ['produtos_catalogo PC', 'PC.codigo = produtos_sem_estoque.codigo AND PC.id_fornecedor = produtos_sem_estoque.id_fornecedor'],
            ],
            "produtos_sem_estoque.id_fornecedor = {$this->session->id_fornecedor} AND produtos_sem_estoque.cd_cotacao = '{$cd_cotacao}'"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function exportar_cotacoes()
    {

        $this->db->select("cd_cotacao, data_criacao");
        $this->db->from('produtos_sem_estoque');
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);
        $this->db->group_by('cd_cotacao');
        $this->db->order_by('data_criacao DESC');

        $query = $this->db->get()->result_array();

        if (count($query) < 1) {
            $query[] = [
                'cd_cotacao' => '',
                'registrado_em' => ''
            ];
        } else {

            foreach ($query as $kk => $row) {


                $query[$kk]['registrado_em'] = date('d/m/Y H:i', strtotime($row['data_criacao']));
                unset($query[$kk]['data_criacao']);
            }
        }


        $dados_page = ['dados' => $query, 'titulo' => 'cotacoes'];
        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ($exportar['status'] == false) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }

    public function exportar_itens($cd_cotacao)
    {

        $this->db->select("
            CASE WHEN PC.descricao is null 
            THEN CONCAT(PC.nome_comercial, ' - ', PC.apresentacao) 
            ELSE CONCAT(PC.nome_comercial, ' - ', PC.descricao) END AS produto, 
            PC.marca");
        $this->db->from('produtos_sem_estoque PSE');
        $this->db->join('produtos_catalogo PC', 'PC.codigo = PSE.codigo AND PC.id_fornecedor = PSE.id_fornecedor');
        $this->db->where('PSE.id_fornecedor', $this->session->id_fornecedor);
        $this->db->where('PSE.cd_cotacao', $cd_cotacao);
        $this->db->order_by('produto ASC');

        $query = $this->db->get()->result_array();

        if (count($query) < 1) {
            $query[] = [
                'produto' => '',
                'marca' => ''
            ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'produtos'];
        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ($exportar['status'] == false) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }
}
