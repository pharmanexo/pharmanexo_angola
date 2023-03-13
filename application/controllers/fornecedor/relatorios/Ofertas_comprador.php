<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ofertas_comprador extends MY_Controller
{
    private $route, $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/relatorios/ofertas_comprador');
        $this->views = "fornecedor/relatorios/ofertas_comprador";

        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_estados', 'estados');
      
        $this->DB_COTACAO = $this->load->database('sintese', TRUE);
    }

    /**
     * Exibe o relatorio de compradores ofertados
     *
     * @return  view
     */
    public function index()
    {
        $page_title = 'CNPJs Ofertados';

        $data['to_datatable'] = "{$this->route}/datatables";
        $data['url_filtros'] = "{$this->route}/filtros";
        $data['estados'] = $this->estados->find("uf, CONCAT(uf, ' - ', descricao) AS estado", null, FALSE, 'estado ASC');

        $data['header'] = $this->template->header([ 'title' => $page_title ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title, 
            'buttons' => [

                [
                    'type' => 'a',
                    'id' => 'btnVoltar',
                   'url' => "javascript:history.back(1)",
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Retornar'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportarEXCEL",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel', 
                    'label' => 'Exportar Excel'
                ],
                [
                    'type'  => 'a',
                    'id'    => 'btnPdf',
                    'url'   => "{$this->route}/exportarPDF",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-pdf', 
                    'label' => 'Exportar PDF'
                ],
            ]
        ]);



        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Obtem dados para os datatables de compradores ofertados
     *
     * @return  json
     */
    public function datatables()
    {

        $where = "cp.submetido = 1 AND cp.controle = 1";

        if ( $this->session->has_userdata('id_matriz') ) {

            $f = $this->db->select('id')->where('id_matriz', $this->session->id_matriz)->get('fornecedores')->result_array();

            $filiais = implode(', ', array_column($f, 'id'));
           
            $where .= " AND cp.id_fornecedor IN (" . $filiais . ") AND ";
        } else {

            $where .= " AND cp.id_fornecedor = {$this->session->id_fornecedor} AND ";
        }

        $filtros = ($this->session->has_userdata('filtro_relatorio')) ? $this->session->filtro_relatorio : null;


        if (!empty($filtros)) {
            
            if (isset($filtros['periodo']) && !empty($filtros['periodo']) ) {

                switch ($filtros['periodo']) {
                    case 'current':
                        $mes = date('m', time());
                        $ano = date('Y', time());
                        $where .= " MONTH(cp.data_criacao) = '{$mes}' AND YEAR(cp.data_criacao) = '{$ano}'";
                        break;
                    case '30days':
                        $inicio = date('Y-m-d', strtotime('-30days'));
                        $fim = date('Y-m-d', time());
                        $where .= " DATE(cp.data_criacao) BETWEEN '{$inicio}' AND '{$fim}'";
                        break;
                    case '60days':
                        $inicio = date('Y-m-d', strtotime('-60days'));
                        $fim = date('Y-m-d', time());
                        $where .= " DATE(cp.data_criacao) BETWEEN '{$inicio}' AND '{$fim}'";
                        break;
                }
            }
        } else {

            $mes = date('m', time());
            $ano = date('Y', time());
            $where .= " MONTH(cp.data_criacao) = '{$mes}' AND YEAR(cp.data_criacao) = '{$ano}'";
        }

        if (isset($filtros['estado'])){
            $where .= " AND c.estado = '{$filtros['estado']}' AND ";
        }

        $where = rtrim($where, "AND ");

        $data = $this->datatable->exec(
            $this->input->post(),
            'cotacoes_produtos cp',
            [
                ['db' => 'c.cnpj', 'dt' => 'cnpj'],
                ['db' => 'c.razao_social', 'dt' => 'razao_social'],
                ['db' => 'c.estado', 'dt' => 'estado'],
            ],
            [
                ['compradores c', 'c.id = cp.id_cliente']
            ],
            $where,
            'cp.id_cliente'
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Salva os filtros em session da lista de compradores
     *
     * @return void
     */
    public function filtros()
    {
        $post = $this->input->post();

        $_SESSION['filtro_relatorio'] = $post;
    }

    /**
     * obtem os compradores ofertados
     *
     * @return array
     */
    public function getCompradores()
    {
        $where = "cp.submetido = 1 AND cp.controle = 1";

        if ( $this->session->has_userdata('id_matriz') ) {

            $f = $this->db->select('id')->where('id_matriz', $this->session->id_matriz)->get('fornecedores')->result_array();

            $filiais = implode(', ', array_column($f, 'id'));
           
            $where .= " AND cp.id_fornecedor IN (" . $filiais . ") AND ";
        } else {

            $where .= " AND cp.id_fornecedor = {$this->session->id_fornecedor} AND ";
        }

        $filtros = ($this->session->has_userdata('filtro_relatorio')) ? $this->session->filtro_relatorio : null;


        if (!empty($filtros)) {
            
            if (isset($filtros['periodo']) && !empty($filtros['periodo']) ) {

                switch ($filtros['periodo']) {
                    case 'current':
                        $mes = date('m', time());
                        $ano = date('Y', time());
                        $where .= " MONTH(cp.data_criacao) = '{$mes}' AND YEAR(cp.data_criacao) = '{$ano}'";
                        break;
                    case '30days':
                        $inicio = date('Y-m-d', strtotime('-30days'));
                        $fim = date('Y-m-d', time());
                        $where .= " DATE(cp.data_criacao) BETWEEN '{$inicio}' AND '{$fim}'";
                        break;
                    case '60days':
                        $inicio = date('Y-m-d', strtotime('-60days'));
                        $fim = date('Y-m-d', time());
                        $where .= " DATE(cp.data_criacao) BETWEEN '{$inicio}' AND '{$fim}'";
                        break;
                }
            }
        } else {

            $mes = date('m', time());
            $ano = date('Y', time());
            $where .= " MONTH(cp.data_criacao) = '{$mes}' AND YEAR(cp.data_criacao) = '{$ano}'";
        }

        $where = rtrim($where, "AND ");

        $this->db->select("c.cnpj, c.razao_social, c.estado AS UF");
        $this->db->from('cotacoes_produtos cp');
        $this->db->join('compradores c', 'c.id = cp.id_cliente');
        $this->db->where($where);
        $this->db->group_by('cp.id_cliente');

        return $this->db->get()->result_array();
    }

    /**
     * Gera para download arquivo PDF com os dados do relatorio
     *
     * @return file
     */
    public function exportarPDF()
    {
       
        $dados = $this->getCompradores();

        $text = "<h3>CNPJs Ofertados</h3><br><table style='width:100%; border: 1px solid #dddddd; border-collapse: collapse'><thead><tr><th></th><th>CNPJ</th><th>Nome</th><th>UF</th></tr></thead><tbody>";

        foreach ($dados as $n => $comprador) {

            $n = $n + 1;
          
            $text .= "  
                <tr>
                    <td style='border: 1px solid #dddddd'; text-align: 'center'>{$n}.</td>
                    <td style='border: 1px solid #dddddd'>{$comprador['cnpj']}</td>
                    <td style='border: 1px solid #dddddd'>{$comprador['razao_social']}</td>
                    <td style='border: 1px solid #dddddd'>{$comprador['UF']}</td>
                </tr>
            ";
        }

        $text .= "</body></table>";

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($text);
        $data = $mpdf->Output("compradores.pdf", 'D');
    }

    /**
     * Gera para download arquivo EXCEL com os dados do relatorio
     *
     * @return file
     */
    public function exportarEXCEL()
    {
        $query = $this->getCompradores();

        if (count($query) < 1 ) {
           $query[] = [
                'cnpj' => '',
                'nome' => '',
                'UF' => ''
           ];
        } 

        $dados_page = ['dados' => $query, 'titulo' => 'compradores'];

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