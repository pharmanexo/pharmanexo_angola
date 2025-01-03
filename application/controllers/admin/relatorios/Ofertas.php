<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ofertas extends Admin_controller
{
    private $route, $views, $DB_SINTESE, $DB_BIONEXO;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('admin/relatorios/ofertas');
        $this->views = "admin/relatorios/ofertas";

        $this->DB_SINTESE = $this->load->database('sintese', TRUE);
        $this->DB_BIONEXO = $this->load->database('bionexo', TRUE);

        $this->load->model('m_fornecedor', 'fornecedores');
        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_cotacaoManual', 'COT');
        $this->load->model('m_cotacoes_produtos', 'cotacoes');
    }

    /**
     * Exibe a tela de cotações por periodo
     *
     * @return  view
     */
    public function index()
    {
        $page_title = 'Relatório de ofertas';

        $data['datatables'] = "{$this->route}/datatables";
        $data['url_exportar'] = "{$this->route}/exportar";
        $data['url'] = "{$this->route}/getOfertas";


        $data['header'] = $this->template->header([
            'title' => $page_title,
            'styles' => [THIRD_PARTY . 'theme/plugins/flatpickr/flatpickr.min.css']
        ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [

            ]
        ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'theme/plugins/flatpickr/flatpickr.min.js',
                'https://npmcdn.com/flatpickr/dist/l10n/pt.js'
            ]
        ]);

        $data['fornecedores'] = $this->fornecedores->find("*", "sintese = 1", false, 'nome_fantasia ASC');

        $this->load->view("{$this->views}/main", $data);
    }

    public function getOfertas()
    {
        if ($this->input->is_ajax_request()) {

            $post = $this->input->post();

            # Total cotações recebidas
            $db = ($post['integrador'] == 'SINTESE') ? $this->DB_SINTESE : $this->DB_BIONEXO;
            $db->select("cd_cotacao");
            $db->where("DATE(dt_inicio_cotacao) BETWEEN '{$post['dataini']}' AND '{$post['datafim']}' ");
            if (isset($post['id_fornecedor']) && !empty($post['id_fornecedor'])) {
                $db->where_in("id_fornecedor", $post['id_fornecedor']);
            }
            $db->group_by('cd_cotacao');
            $total_recebidas = $db->count_all_results('cotacoes');


            # Total cotações respondidas
            $this->db->select("cd_cotacao");
            if (isset($post['id_fornecedor']) && !empty($post['id_fornecedor'])) {
                $this->db->where_in("id_fornecedor", $post['id_fornecedor']);
            }
            $this->db->where("DATE(data_cotacao) BETWEEN '{$post['dataini']}' AND '{$post['datafim']}' ");
            $this->db->where("submetido", 1);
            $this->db->where("controle", 1);
            $this->db->where("ocultar", 0);
            $this->db->where("integrador", $post['integrador']);
            $this->db->group_by('cd_cotacao');
            $total_resp = $this->db->count_all_results("cotacoes_produtos");


            # Valor total cotações respondidas
            $this->db->select("SUM(qtd_solicitada * preco_marca) preco_total");
            if (isset($post['id_fornecedor']) && !empty($post['id_fornecedor'])) {
                $this->db->where_in("id_fornecedor", $post['id_fornecedor']);
            }
            $this->db->where("DATE(data_cotacao) BETWEEN '{$post['dataini']}' AND '{$post['datafim']}' ");
            $this->db->where("submetido", 1);
            $this->db->where("controle", 1);
            $this->db->where("ocultar", 0);
            $this->db->where("integrador", $post['integrador']);
            $valor_total = $this->db->get("cotacoes_produtos")->row_array();

            $data = [
                'total_recebido' => $total_recebidas,
                'total_respondido' => $total_resp,
                'valor_total_respondido' => number_format($valor_total['preco_total'], 4, ',', '.')
            ];

            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }
}