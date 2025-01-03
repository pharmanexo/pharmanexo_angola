<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produtos_vencer extends MY_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('m_catalogo', 'catalogo');
        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_estados', 'estados');
        $this->load->model('m_estoque', 'estoque');
        $this->load->model("m_venda_diferenciada", "venda_diferenciada");

        $this->route = base_url('fornecedor/estoque/produtos_vencer');
        $this->views = "fornecedor/produtos_vencer";
    }

    /**
     * Exibe a tela de produtos em vencimento por periodo
     *
     * @param  int quantidade de meses inicial
     * @param  int quantidade de meses final
     * @return  view
     */
    public function index($inicio = NULL, $fim = NULL)
    {
        $page_title = "Produto a Vencer";

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'styles' => [
                THIRD_PARTY . 'plugins/bootstrap-duallistbox/css/bootstrap-duallistbox.css'
            ]
        ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnVoltar',
                    'url' => "/fornecedor/estoque/produtos",
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Retornar'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar/{$inicio}/{$fim}",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel', 
                    'label' => 'Exportar Excel'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/jquery.form.min.js',
                THIRD_PARTY . 'plugins/jquery-validation-1.19.1/dist/jquery.validate.min.js',
                THIRD_PARTY . 'plugins/bootstrap-duallistbox/js/jquery.bootstrap-duallistbox.js'
            ]
        ]);
        $data['dataSource'] = "{$this->route}/to_datatable/{$inicio}/{$fim}";

        $data['url_regra_venda'] = "{$this->route}/modal/";

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Abre modal para cadastrar promoções para o produto (venda diferenciada)
     *
     * @param  int codigo do produto
     * @param  GET - String lote do produto
     * @return  /view
     */
    public function modal($codigo)
    {
        $lote = $this->input->get('lote');

        $prod = $this->catalogo->find("*", "codigo = {$codigo} AND id_fornecedor = {$this->session->id_fornecedor}", true);
       
        # Obtem  o lote e validade do produto
        $produto_lote = $this->estoque->getLote($codigo, $lote, $this->session->id_fornecedor, 1);

        $prod['estoque'] =  ( isset($produto_lote) ) ? $produto_lote['estoque'] : 0;
        $prod['validade'] = $produto_lote['validade'];
        $prod['lote'] = $lote;

        # Obtem o preço
        $prod['preco_unitario'] = $this->price->getPrice(['id_fornecedor' => $this->session->id_fornecedor, 'codigo' => $codigo, 'id_estado' => $this->session->id_estado ]);

        $data['title'] = "Configurar Promoção";
        $data['produto'] = $prod;
        $data['form_action'] = "{$this->route}/save/{$codigo}";
        $data['url_select'] = "{$this->route}/list";

        $this->load->view("{$this->views}/modal_venda_diferenciada", $data);
    }

    /**
     * Salva ou atualiza as promoções para o produto (venda diferenciada)
     *
     * @param  int codigo do produto
     * @return  json
     */
    public function save($codigo)
    {
        $post = $this->input->post();   

        # Define qual foi selecionado
        $type = ($post['selectType'] == '1' ) ? 'id_estado' : 'id_cliente';

        $this->db->trans_begin();

        $promocoesInsert = [];

        # Percorre os ID dos estados ou cnpjs selecionados, inserindo
        foreach (explode(',', $post['elementos']) as $kk => $id) {

            $where = "{$type} = {$id}";

            $id_promocao = $this->venda_diferenciada->verificarExistente($codigo, $this->session->id_fornecedor, $post['lote'], $where, 1);
            
            # Se existir registro atualiza
            if ( $id_promocao != false ) {

                $this->db->where("id", $id_promocao)->update("vendas_diferenciadas", [
                    'desconto_percentual' => dbNumberFormat($post['desconto_percentual']),
                    'regra_venda' => $post['regra_venda'],
                ]);
            } else {

                # Se não existir registro, insere
                $promocoesInsert[] = [
                    'id_fornecedor' => $this->session->id_fornecedor,
                    'codigo' => $codigo,
                    'lote' => $post['lote'],
                    'promocao' => 1,
                    "{$type}" => $id,
                    'desconto_percentual' => dbNumberFormat($post['desconto_percentual']),
                    'regra_venda' => $post['regra_venda']
                ];
            }
        }

        if ( !empty($promocoesInsert) ) {
            
            $this->db->insert_batch('vendas_diferenciadas', $promocoesInsert);
        }

        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();

            $output = $this->notify->errorMessage();
        } else {

            $this->db->trans_commit();

            $output = ['type' => 'success', 'message' => notify_create];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    /**
     * obtem os registros dos produtos em vencimento
     *
     * @param  int quantidade de meses inicial
     * @param  int quantidade de meses final
     * @return  json
     */
    public function to_datatable($inicio = NULL, $fim = NULL)
    {
        $where = "";

        if (isset($inicio) && isset($fim)) {
            $inicio = ($inicio == '1') ? date('Y-m-d', time()) : "+{$inicio}months";
            $fim = "+{$fim}months";

            $where .= " AND pl.validade BETWEEN '" . date('Y-m-d', strtotime($inicio)) . "' AND '" . date('Y-m-d', strtotime($fim)) . "'";
        }

        if ( $this->session->id_fornecedor == 104 ) {
           
           $condicao_nestle_biohosp = "  AND ( pc.id_marca != 201 AND pc.marca not like '%nestle%' ) ";
        } else {

            $condicao_nestle_biohosp = "";
        }

        $r = $this->datatable->exec(
            $this->input->post(),
            'produtos_catalogo pc',
            [
                ['db' => 'pc.codigo', 'dt' => 'codigo'],
                ['db' => 'pc.nome_comercial', 'dt' => 'nome_comercial'],
                ['db' => 'pc.id_fornecedor', 'dt' => 'id_fornecedor'],
                ['db' => 'pc.marca', 'dt' => 'marca'],
                ['db' => 'pl.lote', 'dt' => 'lote'],
                ['db' => 'pl.validade', 'dt' => 'validade_padrao'],
                ['db' => 'pc.quantidade_unidade', 'dt' => 'quantidade_unidade'],
                ['db' => 'pl.estoque', 'dt' => 'estoque'],
                [
                    'db' => 'pc.id',
                    'dt' => 'preco',
                    'formatter' => function ($value, $row) {

                        $preco_unit = $this->price->getPrice([
                            'id_fornecedor' => $this->session->id_fornecedor,
                            'codigo' => $row['codigo'],
                            'id_estado' => $this->session->id_estado
                        ]);

                        return number_format($preco_unit, 4, ',', '.');
                    }
                ],
                [
                    'db' => 'pl.validade',
                    'dt' => 'validade',
                    'formatter' => function ($d) {
                        return date('d/m/Y', strtotime($d));
                    }
                ]
            ],
            [
               [ 'produtos_lote pl', 'pl.codigo = pc.codigo AND pl.id_fornecedor = pc.id_fornecedor', 'LEFT']
            ],
            "pc.id_fornecedor = {$this->session->id_fornecedor} 
            AND pl.id_fornecedor = {$this->session->id_fornecedor}
            AND pl.estoque > 0
            {$condicao_nestle_biohosp}
            AND pc.ativo = 1
            AND pc.bloqueado = 0
            AND pl.validade > NOW()
            {$where}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    /**
     * Obtem os registros de estados ou compradores
     *
     * @param  int type registro
     * @return  json
     */
    public function list($type)
    {

        if ( $type == 1 ) {

            $result = $this->estados->find("id, CONCAT(uf, ' - ', descricao) as descricao", null, false, 'descricao ASC');
        } else {

            $result = $this->compradores->find("id, CONCAT(cnpj, ' - ', razao_social) as descricao", null, false, 'razao_social ASC');
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function exportar($inicio = NULL, $fim = NULL)
    {
        $this->db->select("
            pc.codigo,
            pc.nome_comercial as produto,
            pc.marca, 
            pl.estoque as quantidade,
            pc.quantidade_unidade as qtd_embalagem,
            pl.validade,
            pl.lote
        ");
        $this->db->from("produtos_catalogo pc");
        $this->db->join("produtos_lote pl", "pl.codigo = pc.codigo AND pl.codigo = pc.codigo", 'LEFT');
        $this->db->where("pc.id_fornecedor", $this->session->id_fornecedor);
        $this->db->where("pl.id_fornecedor", $this->session->id_fornecedor);
        $this->db->where("pl.estoque > 0");
        $this->db->where("pl.validade > NOW()");

        if (isset($inicio) && isset($fim)) {
            $inicio = ($inicio == '1') ? date('Y-m-d', time()) : "+{$inicio}months";
            $fim = "+{$fim}months";

            $where = "pl.validade BETWEEN '" . date('Y-m-d', strtotime($inicio)) . "' AND '" . date('Y-m-d', strtotime($fim)) . "'";
           $this->db->where("{$where}");
        }

        $this->db->order_by("pl.validade ASC");

        $query = $this->db->get()->result_array();

        if ( count($query) < 1 ) {
            $query[] = [
                'codigo' => '',
                'produto' => '',
                'marca' => '',
                'preco' => '',
                'qtd_embalagem' => '',
                'validade' => '',
                'lote' => '',
            ];
        } else {

            foreach ($query as $kk => $row) {
                
                $preco_unit = $this->price->getPrice([
                    'id_fornecedor' => $this->session->id_fornecedor,
                    'codigo' => $row['codigo'],
                    'id_estado' => $this->session->id_estado
                ]);

                $query[$kk]['validade'] = date("d/m/Y", strtotime($row['validade']));

                $query[$kk]['preco'] = number_format($preco_unit, 4, ",", ".");
            }
        }

        $dados_page = ['dados' => $query, 'titulo' => 'Produtos'];

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
