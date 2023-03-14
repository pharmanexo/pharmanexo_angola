<?php

class Ofertas extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('fornecedor/b2b/ofertas');
        $this->views = 'fornecedor/b2b/ofertas/';

        $this->load->model('m_estoque', 'estoque');
        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_venda_diferenciada', 'venda_dif');

    }

    public function index()
    {
        $page_title = "Ofertas Distribuidor x Distribuidor";
        $data['datatables'] = "{$this->route}/datatables";
        $data['tenho_interesse'] = "{$this->route}/tenho_interesse";
        $data['url_export'] = "{$this->route}/exportar";
        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
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
                    'type' => 'a',
                    'id' => 'btnPromo',
                    'url' => base_url('fornecedor/b2b/promocoes'),
                    'class' => 'btn-primary',
                    'icone' => 'fa-exclamation-circle',
                    'label' => 'Ver as Promoções'
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

            $destinatarios = "marlon.boecker@pharmanexo.com.br";

            foreach ($post['dados'] as $item) {
                $vd = $this->venda_dif->findById($item['id']);
                $dadosFornecedor = $this->fornecedor->findById($vd['id_fornecedor']);

                # Concatena o email de cada promoção
                if ( !empty($dadosFornecedor['emails_config']) ) {

                    $emails = json_decode($dadosFornecedor['emails_config']);

                    if ( !empty($emails->distribuidor_distribuidor) ) {
                        $destinatarios .=  ", {$emails->distribuidor_distribuidor}";
                    }
                }

                $forma_pag = $this->get_forma_pagamento($vd['id_cliente'], $vd['id_estado'], $vd['id_fornecedor']);
                $prazo = $this->get_prazo_entrega($vd['id_cliente'], $vd['id_estado'], $vd['id_fornecedor']);

                $itens[] = [
                    "id_fornecedor_oferta" => $vd['id_fornecedor'],
                    "id_fornecedor_interessado" => $this->session->id_fornecedor,
                    "id_forma_pagamento" => $forma_pag['id'],
                    "id_prazo_entrega" => $prazo['id'],
                    "codigo" => $vd['codigo'],
                    "preco_unitario" => dbNumberFormat($item['preco_unitario']),
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
                $this->db->trans_rollback();
                $output = ['type' => 'warning', 'message' => 'Erro ao registrar interesse!'];
            } else {
                $this->db->trans_commit();
                $output = ['type' => 'success', 'message' => 'Interesse registrado com sucesso!'];

                $date = date('d/m/Y', time());

                #noticar por e-mail
                $noticar = [
                    "to" => "marlon.boecker@pharmanexo.com.br. {$dadosFornecedor['email']}",
                    "greeting" => "Distribuidor x Distribuidor",
                    "subject" => "Nova proposta recebida - #{$id}",
                    "message" => "A proposta #{$id} recebida em {$date} está disponível no portal Pharmanexo."
                ];

                $this->notify->send($noticar);

                #noticar por notifações pharmanexo
                $alert = [
                    "id_usuario" => NULL,
                    "id_fornecedor" => $dadosFornecedor['id'],
                    "message" => "A proposta #{$id} recebida em {$date} está disponível no portal Pharmanexo. Clique para ver mais.",
                    "url" => base_url("fornecedor/b2b/ofertas_recebidas/detalhes/{$id}")
                ];

                $this->notify->alert($alert);

            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function datatables()
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'vendas_diferenciadas',
            [
                ['db' => 'vendas_diferenciadas.id', 'dt' => 'id'],
                ['db' => 'vendas_diferenciadas.id_fornecedor', 'dt' => 'id_fornecedor'],
                ['db' => 'vendas_diferenciadas.codigo', 'dt' => 'codigo'],
                ['db' => 'vendas_diferenciadas.regra_venda', 'dt' => 'regra_venda'],
                ['db' => 'vendas_diferenciadas.quantidade', 'dt' => 'quantidade', 'formatter' => function ($d, $row) {
                    return $this->estoque->allStock($row['codigo'], $row['id_fornecedor']);
                }],
                ['db' => 'produtos_catalogo.nome_comercial', 'dt' => 'nome_comercial'],
                ['db' => 'produtos_catalogo.marca', 'dt' => 'marca'],
                ['db' => 'vendas_diferenciadas.id_cliente', 'dt' => 'id_cliente'],
                ['db' => 'vendas_diferenciadas.desconto_percentual', 'dt' => 'desconto_percentual'],
                ['db' => 'vendas_diferenciadas.id_estado', 'dt' => 'prazo_entrega', "formatter" => function ($d, $row) {

                    if (!empty($row['id_cliente'])) {
                        $prazo = $this->db->select("*")->where("id_cliente = {$row['id_cliente']} and id_fornecedor = {$row['id_fornecedor']}")->get('prazos_entrega')->row_array();
                    } else {
                        $prazo = [];
                    }

                    if (empty($prazo)) {
                        $prazo = $this->db->select("*")
                            ->where("id_estado", $d)
                            ->where("id_fornecedor", $row['id_fornecedor'])
                            ->get('prazos_entrega')->row_array();
                    }

                    return (isset($prazo)) ? $prazo['prazo'] : '';
                }],
                ['db' => 'vendas_diferenciadas.id_estado', 'dt' => 'forma_pagamento', "formatter" => function ($d, $row) {

                    if (!empty($row['id_cliente'])) {

                        $prazo = $this->db->select("*")->where("id_cliente = {$row['id_cliente']} and id_fornecedor = {$row['id_fornecedor']}")->get('vw_formas_pagamento_fornecedores')->row_array();

                    } else {
                        $prazo = [];
                    }


                    if (empty($prazo)) {
                        $prazo = $this->db->select("*")->where("id_estado = {$d} and id_fornecedor = {$row['id_fornecedor']}")->get('vw_formas_pagamento_fornecedores')->row_array();

                    }

                    return $prazo['descricao'];
                }],
                [
                    'db' => 'prod.preco_unitario',
                    'dt' => 'preco',
                    'formatter' => function ($value, $row) {

                        $preco = $value - ($value * ($row['desconto_percentual'] / 100));
                        return number_format($preco, 4, ',', '.');
                    }
                ],
                ['db' => 'produtos_catalogo.apresentacao', 'dt' => 'apresentacao'],
                [
                    'db' => 'produtos_catalogo.descricao',
                    'dt' => 'descricao',
                    'formatter' => function ($value, $row) {
                        if (!empty($row['descricao'])) {
                            return "{$row['nome_comercial']} - {$row['descricao']} <br> <small>Marca: {$row['marca']}</small>";
                        }
                        return "{$row['nome_comercial']} - {$row['apresentacao']} <br> <small>Marca: {$row['marca']}</small>";
                    }
                ],
            ],
            [
                ['produtos_catalogo', 'produtos_catalogo.codigo = vendas_diferenciadas.codigo AND produtos_catalogo.id_fornecedor = vendas_diferenciadas.id_fornecedor'],
                ['produtos_preco prod', 'produtos_catalogo.codigo = prod.codigo AND produtos_catalogo.id_fornecedor = prod.id_fornecedor', 'LEFT']

            ],
            "produtos_catalogo.id_fornecedor != {$this->session->userdata('id_fornecedor')} 
            AND vendas_diferenciadas.id_fornecedor != {$this->session->userdata('id_fornecedor')}
            AND (vendas_diferenciadas.id_estado  = {$this->session->id_estado} OR vendas_diferenciadas.id_cliente = {$this->session->id_fornecedor} ) 
            AND
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
            'codigo, id_fornecedor'
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    private function get_prazo_entrega($id_cliente, $id_estado, $id_fornecedor)
    {
        if (!empty($id_cliente)) {
            $prazo = $this->db->select("*")->where("id_cliente = {$id_cliente} and id_fornecedor = {$id_fornecedor}")->get('prazos_entrega')->row_array();
        } else {
            $prazo = [];
        }


        if (empty($prazo)) {
            $prazo = $this->db->select("*")->where("id_estado = {$id_estado} and id_fornecedor = {$id_fornecedor}")->get('prazos_entrega')->row_array();

        }

        return $prazo;
    }

    private function get_forma_pagamento($id_cliente, $id_estado, $id_fornecedor)
    {
        if (!empty($id_cliente)) {
            $forma = $this->db->select("*")->where("id_cliente = {$id_cliente} and id_fornecedor = {$id_fornecedor}")->get('vw_formas_pagamento_fornecedores')->row_array();
        } else {
            $forma = [];
        }


        if (empty($forma)) {
            $forma = $this->db->select("*")->where("id_estado = {$id_estado} and id_fornecedor = {$id_fornecedor}")->get('vw_formas_pagamento_fornecedores')->row_array();

        }

        return $forma;
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
        $this->db->where("pc.id_fornecedor != {$this->session->userdata('id_fornecedor')} 
            AND vd.id_fornecedor != {$this->session->userdata('id_fornecedor')}
            AND (vd.id_estado  = {$this->session->id_estado} or vd.id_cliente = {$this->session->id_fornecedor} ) 
            AND
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
                    $forma_pgto = $this->db->select("*")->where("id_estado = {$row['id_estado']} and id_fornecedor = {$row['id_fornecedor']}")->get('vw_formas_pagamento_fornecedores')->row_array();
                }
  
                # Prazo entrega
                if (!empty($row['id_cliente'])) {
                    $prazo = $this->db->select("*")->where("id_cliente = {$row['id_cliente']} and id_fornecedor = {$row['id_fornecedor']}")->get('prazos_entrega')->row_array();
                } else {
                    $prazo = [];
                }

                if (empty($prazo)) {
                    $prazo = $this->db->select("*")->where("id_estado = {$row['id_estado']} and id_fornecedor = {$row['id_fornecedor']}")->get('prazos_entrega')->row_array();
                }

                # Preço

                $preco = $row['preco_unitario'];
                $preco = $preco - ($preco * ($row['desconto_percentual'] / 100) );
                $preco = number_format($preco, 4, ",", ".");

                # Estoque 
                $estoque = $this->estoque->allStock($row['codigo'], $row['id_fornecedor']);

        
                $data[] = [
                    'descricao' => $row['descricao'],
                    'marca' => $row['marca'],
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

        $dados_page = ['dados' => $query, 'titulo' => 'Ofertas'];

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