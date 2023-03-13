<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProdutosPreco extends MY_Controller
{

    private $route;
    private $views;
    private $DB_COTACAO;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('/fornecedor/BI/produtosPreco');
        $this->views = 'fornecedor/BI/produtosPreco';

        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_marca', 'marca');
        $this->load->model('m_compradores', 'comprador');
        $this->load->model('m_estados', 'estado');
        $this->load->model('m_catalogo', 'catalogo');
        $this->load->model('m_bi', 'BI');

        $this->DB_COTACAO = $this->load->database('sintese', TRUE);
    }

    /**
     * Exibe a tela inicial do BI
     *
     * @return view
     */
    public function index()
    {
        $page_title = "Relatório de Preços Ofertados";

        $data['header'] = $this->template->header(['title' => $page_title, 'styles' => [THIRD_PARTY . 'theme/plugins/flatpickr/flatpickr.min.css'] ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'theme/plugins/flatpickr/flatpickr.min.js',
                'https://npmcdn.com/flatpickr/dist/l10n/pt.js'
            ]
        ]);
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
                    'type'  => 'submit',
                    'id'    => 'btnExcel',
                    'form'  => 'formExportar',
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel', 
                    'label' => 'Exportar Excel'
                ],
                [
                    'type'  => 'submit',
                    'id'    => 'btnPdf',
                    'form'  => 'formExportar',
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-pdf', 
                    'label' => 'Exportar PDF'
                ]
            ]
        ]);

        # URLs
        $data['urlRelatorioProdutosComprador'] = "{$this->route}/datatableRelatorioProdutosComprador";
        $data['urlExcel'] = "{$this->route}/exportarExcel";
        $data['urlPdf'] = "{$this->route}/exportarPDF";

        # Filtros
        $data['compradores'] = $this->comprador->find("id, CONCAT(cnpj, ' - ', razao_social) AS comprador", null, false, 'comprador ASC');
        $data['estados'] = $this->estado->find("id, uf, CONCAT(uf, ' - ', descricao) AS estado", null, false, 'estado ASC');
        $data['produtos'] = $this->catalogo->find("codigo, CONCAT(codigo, ' - ', nome_comercial, ' - ', apresentacao) as produto", "id_fornecedor = {$this->session->id_fornecedor}");

        if ($this->session->has_userdata('id_matriz')) {

            $data['selectMatriz'] = $this->fornecedor->find("id, nome_fantasia", "id_matriz = {$this->session->id_matriz}");
        }

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Obtem os dados do relatorio de cotações
     *
     * @param - post - String data inicio
     * @param - post - String data fim
     * @param - post - int ID fornecedor (opcional)
     * @return json
     */
    public function datatableRelatorioProdutosComprador()
    {

        $post = $this->input->post();

        $dados = $this->filtros($post);

        $data = $this->BI->produtosPreco($dados);

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Trata os dados do POST
     *
     * @param - post - String data inicio
     * @param - post - String data fim
     * @param - post - int ID fornecedor (opcional)
     * @param - post - int ID comprador (opcional)
     * @param - post - String UF estado (opcional)
     * @return json
     */
    private function filtros($post) 
    {

        $id_fornecedor = (!empty($post['id_fornecedor'])) ? $post['id_fornecedor'] : $this->session->id_fornecedor;

        $post_id = false;

        if ($this->session->has_userdata('id_matriz') && !empty($post['id_fornecedor'])) {

            $post_id = true;
        }

        $filial = $this->BI->matrizFilial(FALSE, $post_id, $id_fornecedor);

        $empresa = "";
        $fornecedor = "";

        foreach ($filial as $key => $item) {

            $empresa = $key;
            $fornecedor = $item;
        }


        $dados = [
            'page' => isset($post['page']) ? intval($post['page']) : 0,
            'notPage' => isset($post['notPage']) ? 1 : null,
            'dt_inicio' => $post['dataini'],
            'dt_fim' => $post['datafim'],
            'fornecedor' => $fornecedor,
            'id_fornecedor' =>  $id_fornecedor,
            'empresa' => $empresa,
            'uf_cotacao' => $post['uf_cotacao'],
            'id_cliente' => $post['id_cliente'],
            'cd_produto' => (isset($post['produto'])) ? $post['produto'] : NULL
        ];

        return $dados;
    }

    /**
     * Cria um arquivo excel dos registros da pagina
     *
     * @return excel file
     */
    public function exportarExcel()
    {
        $post = $this->input->post();

        $post['notPage'] = 1;
        $post['uf_cotacao'] = '';
        $post['dataini'] = date("Y-m-d", strtotime(str_replace('/', '-', $post['dataini'])));
        $post['datafim'] = date("Y-m-d", strtotime(str_replace('/', '-', $post['datafim'])));

        $dados = $this->filtros($post);

        $data = $this->BI->produtosPreco($dados);

        if ( !empty($data) ) {

            $info = [];

            foreach ($data as $comprador) {
                
                foreach ($comprador['produtos'] as $produto) {

                    $arrayCompradorProdutoPreco['comprador'] = $comprador['cnpj'] . ' - ' . $comprador['razao_social'];
                    $arrayCompradorProdutoPreco['produto'] = $produto['codigo'] . ' - ' . $produto['nome_comercial'];

                    foreach ($produto['ultimos_precos'] as $k => $preco) {

                        $k += 1;
                        
                        $arrayCompradorProdutoPreco["preco{$k}"] = $preco['format'];
                        $arrayCompradorProdutoPreco["data{$k}"] = $preco['data'];
                    }

                    $arrayCompradorProdutoPreco['media'] = $produto['mediaFormatada'];

                    $info[] = $arrayCompradorProdutoPreco;
                }
            }
        } else {

            $info[] = [
                'comprador' => '',
                'produto' => '',
                'preço' => '',
            ];
        }

        $dados_page = ['dados' => $info, 'titulo' => 'precos_ofertados'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }

    /**
     * Cria um arquivo PDF dos registros da pagina
     *
     * @return pdf file
     */
    public function exportarPDF()
    {

        $post = $this->input->post();

        $post['notPage'] = 1;
        $post['uf_cotacao'] = '';
        $post['dataini'] = date("Y-m-d", strtotime(str_replace('/', '-', $post['dataini'])));
        $post['datafim'] = date("Y-m-d", strtotime(str_replace('/', '-', $post['datafim'])));

        $filtros = $this->filtros($post);

        $dados = $this->BI->produtosPreco($filtros);

        $info = "";

        if ( !empty($dados) ) {

            foreach ($dados as $comprador) {

                $info .= "<table style='width:100%; border: 1px solid #dddddd; border-collapse: collapse'>";
                $info .= "<tr><td style='border: 1px solid #dddddd' colspan='6'>Comprador: {$comprador['cnpj']} - {$comprador['razao_social']}</td></tr>";
                
                foreach ($comprador['produtos'] as $produto) {

                    $info .= "<tr><td style='border: 1px solid #dddddd' colspan='6'>Produto: {$produto['codigo']} - {$produto['nome_comercial']}</td></tr>";

                    $info .= "
                        <tr>
                            <th style='border: 1px solid #dddddd'>Oferta 1</th>
                            <th style='border: 1px solid #dddddd'>Oferta 2</th>
                            <th style='border: 1px solid #dddddd'>Oferta 3</th>
                            <th style='border: 1px solid #dddddd'>Oferta 4</th>
                            <th style='border: 1px solid #dddddd'>Oferta 5</th>
                            <th style='border: 1px solid #dddddd'>Média</th>
                        </tr>

                    ";

                    $info .= "<tr>";

                    for ($i= 0; $i < 5 ; $i++) { 

                        if ( isset( $produto['ultimos_precos'][$i] ) ) {
                           
                            $info .= "<td style='border: 1px solid #dddddd; text-align: right'>{$produto['ultimos_precos'][$i]['data']}</td>";
                        } else {

                            $info .= "<td style='border: 1px solid #dddddd; text-align: right'></td>";
                        }
                    }

                    $info .= "<td style='border: 1px solid #dddddd; text-align: right'></td></tr>";

                    $info .= "<tr>";

                    for ($i= 0; $i < 5 ; $i++) { 

                        if ( isset( $produto['ultimos_precos'][$i] ) ) {
                           
                            $info .= "<td style='border: 1px solid #dddddd; text-align: right'>{$produto['ultimos_precos'][$i]['format']}</td>";
                        } else {

                            $info .= "<td style='border: 1px solid #dddddd; text-align: right'></td>";
                        }
                    }

                    $info .= "<td style='border: 1px solid #dddddd; text-align: right'>{$produto['mediaFormatada']}</td>";

                    $info .= "</tr>";
                }

                $info .= "</table><br><br>";
            }
        }

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($info);
        $data = $mpdf->Output("precos_ofertados.pdf", 'D');
    }
}