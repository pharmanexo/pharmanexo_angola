<?php

class Promocoes extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('fornecedor/b2b/promocoes');
        $this->views = 'fornecedor/b2b/promocoes/';

        $this->load->model('m_estoque', 'estoque');
        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_promocoes', 'promocoes');
    }

    public function index()
    {
        $page_title = "Promoções Distribuidor x Distribuidor";
        $data['datatables'] = "{$this->route}/datatables";
        $data['tenho_interesse'] = "{$this->route}/tenho_interesse";
        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type'  => 'a',
                    'id'    => 'btnBack',
                    'url'   => base_url('fornecedor/b2b/ofertas'),
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
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
                    'id' => 'btnAdicionar',
                    'url' => "{$this->route}/save",
                    'class' => 'btn-success',
                    'icone' => 'fa-thumbs-up',
                    'label' => 'Tenho interesse'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/main", $data);
    }

    public function tenho_interesse()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $this->db->trans_begin();
            $itens = [];

            $id = time();
            $date = date('d/m/Y', time());

            $destinatarios = "marlon.boecker@pharmanexo.com.br, ericlempe1994@gmail.com";

            foreach ($post['dados'] as $item) {
                $vd = $this->promocoes->findById($item['id']);
                $dadosFornecedor = $this->fornecedor->findById($vd['id_fornecedor']);

                # Concatena o email de cada promoção
                if ( !empty($dadosFornecedor['emails_config']) ) {

                    $emails = json_decode($dadosFornecedor['emails_config']);

                    if ( !empty($emails->distribuidor_distribuidor) ) {
                        $destinatarios .=  ", {$emails->distribuidor_distribuidor}";
                    }
                }

                $forma_pag = $this->get_forma_pagamento($this->session->id_estado, $vd['id_fornecedor']);
                $prazo = $this->get_prazo_entrega($this->session->id_estado, $vd['id_fornecedor']);

                $itens[] = [
                    "id_fornecedor_oferta" => $vd['id_fornecedor'],
                    "id_fornecedor_interessado" => $this->session->id_fornecedor,
                    "id_forma_pagamento" => $forma_pag['id'],
                    "id_prazo_entrega" => $prazo['id'],
                    "codigo" => $vd['codigo'],
                    'preco_unitario' => dbNumberFormat($item['preco_unitario']),
                    "quantidade" => $item['qtd'],
                    "valor_maximo" => dbNumberFormat($item['vlmax']),
                    "id_solicitacao" => $id,
                    "id_usuario" => $this->session->id_usuario,
                    "id_venda_diferenciada" => $vd['id']
                ];

                #noticar por notifações pharmanexo
                $alert = [
                    "id_usuario" => NULL,
                    "id_fornecedor" => $dadosFornecedor['id'],
                    "message" => "A proposta #{$id} recebida em {$date} está disponível no portal Pharmanexo. Clique para ver mais.",
                    "url" => base_url("fornecedor/b2b/ofertas_recebidas/detalhes/{$id}")
                ];

                $this->notify->alert($alert);
            }

            $this->db->insert_batch('ofertas_b2b_itens', $itens);


            #noticar por e-mail
            $noticar = [
                "to" => $destinatarios,
                "greeting" => "Distribuidor x Distribuidor",
                "subject" => "Nova proposta recebida - #{$id}",
                "message" => "A proposta #{$id} recebida em {$date} está disponível no portal Pharmanexo."
            ];

            $this->notify->send($noticar);


            if ($this->db->trans_status() === false) {
                var_dump($this->db->error());exit();
                $this->db->trans_rollback();


                $output = ['type' => 'warning', 'message' => 'Erro ao registrar interesse!'];
            } else {
                $this->db->trans_commit();

                $output = ['type' => 'success', 'message' => 'Interesse registrado com sucesso!'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function datatables()
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'vendas_diferenciadas promocoes',
            [
                ['db' => 'promocoes.id', 'dt' => 'id'],
                ['db' => 'promocoes.id_fornecedor', 'dt' => 'id_fornecedor'],
                ['db' => 'promocoes.codigo', 'dt' => 'codigo'],
                ['db' => 'promocoes.regra_venda', 'dt' => 'regra_venda'],
                ['db' => 'promocoes.lote', 'dt' => 'lote'],
                ['db' => 'promocoes.desconto_percentual', 'dt' => 'desconto_percentual'],
                ['db' => 'promocoes.quantidade', 'dt' => 'quantidade', 'formatter' => function ($d, $row) {
                    return $this->estoque->allStock($row['codigo'], $row['id_fornecedor']);
                }],
                ['db' => 'produtos_catalogo.nome_comercial', 'dt' => 'nome_comercial'],
                ['db' => 'produtos_catalogo.marca', 'dt' => 'marca'],
                ['db' => 'promocoes.id_cliente', 'dt' => 'id_cliente'],
                ['db' => 'promocoes.id_estado', 'dt' => 'prazo_entrega', "formatter" => function ($d, $row) {

                    if (!empty($row['id_cliente'])) {
                        $prazo = $this->db->select("*")->where("id_cliente = {$row['id_cliente']} and id_fornecedor = {$row['id_fornecedor']}")->get('prazos_entrega')->row_array();
                    } else {
                        $prazo = [];
                    }

                    if (empty($prazo)) {
                        $prazo = $this->db->select("*")->where("id_estado = {$this->session->id_estado} and id_fornecedor = {$row['id_fornecedor']}")->get('prazos_entrega')->row_array();

                    }

                    return $prazo['prazo'];
                }],
                ['db' => 'promocoes.id_estado', 'dt' => 'forma_pagamento', "formatter" => function ($d, $row) {

                    if (!empty($row['id_cliente'])) {

                        $prazo = $this->db->select("*")->where("id_cliente = {$row['id_cliente']} and id_fornecedor = {$row['id_fornecedor']}")->get('vw_formas_pagamento_fornecedores')->row_array();

                    } else {
                        $prazo = [];
                    }


                    if (empty($prazo)) {
                        $prazo = $this->db->select("*")->where("id_estado = {$this->session->id_estado} and id_fornecedor = {$row['id_fornecedor']}")->get('vw_formas_pagamento_fornecedores')->row_array();

                    }

                    return $prazo['descricao'];
                }],
                ['db' => 'produtos_catalogo.apresentacao', 'dt' => 'apresentacao'],
                ['db' => 'f.nome_fantasia', 'dt' => 'fornecedor'],
                [
                    'db' => 'produtos_catalogo.descricao',
                    'dt' => 'descricao',
                    'formatter' => function ($value, $row) {

                        $this->db->where('codigo', $row['codigo']);
                        $this->db->where('lote', $row['lote']);
                        $this->db->where('id_fornecedor', $row['id_fornecedor']);
                        $lote = $this->db->get('produtos_lote')->row_array();

                        if (isset($lote) && !empty($lote)) {
                            
                            $data = date("d/m/Y", strtotime($lote['validade']));

                            $validade = "<br> <small>Validade: {$data}</small>";
                        } else {
                            $validade = "";
                        }

                        if (!empty($row['descricao'])) {
                            return "{$row['nome_comercial']} - {$row['descricao']} <br> <small>Marca: {$row['marca']}</small> {$validade} <br> {$row['codigo']} / {$row['fornecedor']}";
                        }
                        return "{$row['nome_comercial']} - {$row['apresentacao']} <br> <small>Marca: {$row['marca']}</small> {$validade} <br> {$row['codigo']} / {$row['fornecedor']}";
                    }
                ],
                [
                    'db' => 'prod.preco_unitario',
                    'dt' => 'preco',
                    'formatter' => function ($value, $row) {

                        $preco = $value - ($value * ($row['desconto_percentual'] / 100));
                        return number_format($preco, 4, ',', '.');
                    }
                ],
                // ['db' => 'promocoes.id_estado', 'dt' => 'preco', "formatter" => function ($d, $row) {

                //     $preco = $this->db->select("*")->where("codigo = {$row['codigo']} and id_fornecedor = {$row['id_fornecedor']} and id_estado is not null")->get('produtos_preco')->row_array();
                //     if (!isset($preco) || empty($preco) ) {
                //         $preco = $this->db->select("*")->where("codigo = {$row['codigo']} and id_fornecedor = {$row['id_fornecedor']} and id_estado is null")->get('produtos_preco')->row_array();
                //     }

                //     if (isset($preco['preco_unitario']) && !empty($preco['preco_unitario'])) {

                //         $preco = $preco['preco_unitario'];

                //         $preco = $preco - ($preco * ($row['desconto_percentual'] / 100) );
                //         $preco = number_format($preco, 4, ",", ".");
                //     } else {
                //         $preco = '0,0000';
                //     }

                //     return $preco;
                // }],
            ],
            [
                ['produtos_catalogo', 'produtos_catalogo.codigo = promocoes.codigo AND produtos_catalogo.id_fornecedor = promocoes.id_fornecedor'],
                ['produtos_preco prod', 'produtos_catalogo.codigo = prod.codigo AND produtos_catalogo.id_fornecedor = prod.id_fornecedor', 'LEFT'],
                ['fornecedores f', 'f.id = promocoes.id_fornecedor', 'LEFT']

            ],
            "promocoes.promocao = 1 AND 
            produtos_catalogo.id_fornecedor != {$this->session->userdata('id_fornecedor')} AND
            promocoes.id_fornecedor != {$this->session->userdata('id_fornecedor')} AND 
            promocoes.regra_venda in (0, 4, 5, 6) AND
            ((prod.id_estado is not null 
            AND prod.data_criacao = (SELECT max(pd.data_criacao)
                                    FROM produtos_preco pd
                                    WHERE pd.id_estado is not null AND
                                        pd.codigo = prod.codigo AND
                                        pd.id_estado = prod.id_estado AND
                                        pd.id_fornecedor = prod.id_fornecedor))
            OR
            (isnull(prod.id_estado) AND 
                prod.data_criacao = (SELECT max(pd.data_criacao)
                                    FROM produtos_preco pd
                                    WHERE isnull(pd.id_estado) AND
                                        pd.codigo = prod.codigo AND
                                        pd.id_fornecedor = prod.id_fornecedor)))",
            "codigo, id_fornecedor"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    private function get_prazo_entrega($id_estado, $id_fornecedor)
    {
      
        return $this->db->select("*")->where("id_estado = {$id_estado} and id_fornecedor = {$id_fornecedor}")->get('prazos_entrega')->row_array();
    }

    private function get_forma_pagamento($id_estado, $id_fornecedor)
    {
        return $this->db->select("*")->where("id_estado = {$id_estado} and id_fornecedor = {$id_fornecedor}")->get('vw_formas_pagamento_fornecedores')->row_array();
    }

    public function exportar()
    {
        $this->db->select(" 
            CASE WHEN pc.descricao is null THEN CONCAT(pc.nome_comercial, ' - ', pc.apresentacao) ELSE CONCAT(pc.nome_comercial, ' - ', pc.descricao) END  AS descricao, 
            pc.marca AS marca,
            prod.preco_unitario AS preco_unitario,
            vd.*
            ");
        $this->db->from("vendas_diferenciadas vd");
        $this->db->join("produtos_catalogo pc", 'pc.codigo = vd.codigo AND pc.id_fornecedor = vd.id_fornecedor');
        $this->db->join("produtos_preco prod", 'pc.codigo = prod.codigo AND pc.id_fornecedor = prod.id_fornecedor', 'LEFT');
        $this->db->where("
            vd.promocao = 1 AND 
            pc.id_fornecedor != {$this->session->userdata('id_fornecedor')} AND
            vd.id_fornecedor != {$this->session->userdata('id_fornecedor')} AND 
            vd.regra_venda in (0, 4, 5, 6) AND 
            vd.id_fornecedor != {$this->session->userdata('id_fornecedor')} AND 
            ((prod.id_estado is not null 
            AND prod.data_criacao = (SELECT max(pd.data_criacao)
                                    FROM produtos_preco pd
                                    WHERE pd.id_estado is not null AND
                                        pd.codigo = prod.codigo AND
                                        pd.id_estado = prod.id_estado AND
                                        pd.id_fornecedor = prod.id_fornecedor))
            OR
            (isnull(prod.id_estado) AND 
                prod.data_criacao = (SELECT max(pd.data_criacao)
                                    FROM produtos_preco pd
                                    WHERE isnull(pd.id_estado) AND
                                        pd.codigo = prod.codigo AND
                                        pd.id_fornecedor = prod.id_fornecedor)))");
        $this->db->group_by('codigo, id_fornecedor');
        $this->db->order_by('descricao ASC');

        $query = $this->db->get()->result_array();

        if ( count($query) < 1 ) {
            $query[] = [
                'descricao' => '',
                'marca' => '',
                'validade' => '',
                'preco_unitario' => '',
                'estoque' => '',
                'qtde' => '',
                'valor_maximo' => '',
                'prazo_entrega' => '',
                'forma_pagamento' => ''
            ];
        } else {
            $data = [];
            foreach ($query as $k => $row) {

                # Forma de pagamento
                if (!empty($row['id_cliente'])) {

                    $forma_pgto = $this->db->select("*")->where("id_cliente = {$row['id_cliente']} and id_fornecedor = {$row['id_fornecedor']}")->get('vw_formas_pagamento_fornecedores')->row_array();
                } else {
                    $forma_pgto = [];
                }

                if (empty($forma_pgto)) {
                    $forma_pgto = $this->db->select("*")->where("id_estado = {$this->session->id_estado} and id_fornecedor = {$row['id_fornecedor']}")->get('vw_formas_pagamento_fornecedores')->row_array();
                }
  
                # Prazo entrega
                if (!empty($row['id_cliente'])) {
                    $prazo = $this->db->select("*")->where("id_cliente = {$row['id_cliente']} and id_fornecedor = {$row['id_fornecedor']}")->get('prazos_entrega')->row_array();
                } else {
                    $prazo = [];
                }

                if (empty($prazo)) {
                    $prazo = $this->db->select("*")->where("id_estado = {$this->session->id_estado} and id_fornecedor = {$row['id_fornecedor']}")->get('prazos_entrega')->row_array();
                }

                # Preço
                $preco = $row['preco_unitario'];
                $preco = $preco - ($preco * ($row['desconto_percentual'] / 100) );
                $preco = number_format($preco, 4, ",", ".");

                # Estoque 
                $estoque = $this->estoque->allStock($row['codigo'], $row['id_fornecedor']);

                # Validade
                $this->db->where('codigo', $row['codigo']);
                $this->db->where('lote', $row['lote']);
                $this->db->where('id_fornecedor', $row['id_fornecedor']);
                $lote = $this->db->get('produtos_lote')->row_array();

                if (isset($lote) && !empty($lote)) {
                    
                    $validade = date("d/m/Y", strtotime($lote['validade']));
                } else {
                    $validade = "";
                }

                $data[] = [
                    'descricao' => $row['descricao'],
                    'marca' => $row['marca'],
                    'validade' => $validade,
                    'preco_unitario' => $preco,
                    'estoque' => $estoque,
                    'qtde' => '',
                    'valor_maximo' => '',
                    'prazo_entrega' => $prazo['prazo'],
                    'forma_pagamento' => $forma_pgto['descricao']
                ];
            }

            $query = $data;
        }

        $dados_page = ['dados' => $query, 'titulo' => 'Promocoes'];

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