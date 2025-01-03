<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cotacoes extends Conv_controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('convidados/cotacoes');
        $this->views = 'convidados/cotacoes';
    }

    public function index()
    {
        $page_title = "Cotações Abertas";

        $data['to_datatable'] = "{$this->route}/to_datatable/";
        $data['urlUpdate'] = "{$this->route}/detalhes/";
        $data['header'] = $this->tmp_conv->header(['title' => 'Cotações',]);
        $data['navbar'] = $this->tmp_conv->navbar();
        $data['sidebar'] = $this->tmp_conv->sidebar();
        $data['heading'] = $this->tmp_conv->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'button',
                    'id' => 'btnPedido',
                    'url' => "{$this->route}/abertura",
                    'class' => 'btn-primary',
                    'icone' => 'fa-cart',
                    'label' => '<i class="fa fa-plus"></i> Nova Cotação'
                ],
            ]
        ]);
        $data['scripts'] = $this->tmp_conv->scripts();

        $this->load->view("{$this->views}/main", $data);
    }

    public function encerradas()
    {
        $page_title = "Cotações Encerradas";

        $data['to_datatable'] = "{$this->route}/to_datatable/1";
        $data['url_update'] = "{$this->route}/update";
        $data['header'] = $this->tmp_conv->header(['title' => 'Cotações Encerradas',]);
        $data['navbar'] = $this->tmp_conv->navbar();
        $data['sidebar'] = $this->tmp_conv->sidebar();
        $data['heading'] = $this->tmp_conv->heading([
            'page_title' => $page_title,

        ]);
        $data['scripts'] = $this->tmp_conv->scripts();

        $this->load->view("{$this->views}/main_encerradas", $data);
    }

    public function abertura()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();
            $post['id_comprador'] = $this->session->dados['id'];
            $post['situacao'] = 1;

            $post['dt_abertura'] = date('Y-m-d H:i:s', time());

            $ins = $this->db->insert('conv_cotacoes', $post);

            if ($ins) {
                $insertId = $this->db->insert_id();

                redirect("{$this->route}/detalhes/{$insertId}");
            }

        } else {

            $this->_form();

        }
    }

    public function publicar($idCot)
    {
        if (isset($idCot)) {

            $this->db->where('id', $idCot)->update('conv_cotacoes', ['situacao' => 2]);

            $this->session->set_userdata('warning', [
                'type' => 'success',
                'message' => 'Cotação publicada e enviada ao mercado'
            ]);


            redirect("{$this->route}/detalhes/{$idCot}");

        }
    }

    public function adicionarItem()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();
            $idCot = $post['id_cotacao'];

            $post['descricao_produto'] = '';
            $post['marcas_favoritas'] = isset($post['marcas']) ? $post['marcas'] : null;
            unset($post['marcas']);

            $this->db->insert('conv_cotacoes_produtos', $post);

            redirect("{$this->route}/detalhes/{$idCot}");

        }
    }

    public function detalhes($id)
    {
        $page_title = "Detalhes da Cotação";

        $data['cotacao'] = $this->db
            ->select('cot.*, c.cnpj, c.razao_social, c.estado')
            ->from('conv_cotacoes cot')
            ->join('compradores c', 'c.id = cot.id_comprador')
            ->where('cot.id', $id)
            ->get()->row_array();

        $buttons = [
            [
                'type' => 'button',
                'id' => 'btnPedido',
                'url' => "{$this->route}/publicar/$id",
                'class' => 'btn-primary',
                'icone' => 'fa-check',
                'label' => 'Publicar Cotação'
            ],
        ];

        if ($data['cotacao']['situacao'] > 1) {
            $buttons = [
                [
                    'type' => 'button',
                    'id' => 'btnPedido',
                    'url' => "{$this->route}/encerrar/$id",
                    'class' => 'btn-primary',
                    'icone' => 'fa-close',
                    'label' => 'Encerrar Cotação'
                ]
            ];
        }

        $data['produtos'] = $this->db->get('produtos_marca_sintese')->result_array();

        $data['produtosCotacao'] = $this->db
            ->select('p.*, pms.descricao')
            ->from('conv_cotacoes_produtos p')
            ->join('produtos_marca_sintese pms', 'pms.id_produto = p.id_produto_catalogo')
            ->where('p.id_cotacao', $id)
            ->get()->result_array();

        $data['ofertas'] = $this->db
            ->select('p.*, m.marca, f.nome_fantasia, f.estado')
            ->from('conv_cotacoes_ofertas p')
            ->join('fornecedores f', 'f.id = p.id_fornecedor')
            ->join('marcas m', 'm.id = p.id_marca')
            ->where('p.id_cotacao', $id)
            ->get()->result_array();

        foreach ($data['produtosCotacao'] as $k => $prodCot) {
            foreach ($data['ofertas'] as $prodOfer) {
                if ($prodCot['id'] == $prodOfer['id_produto_cotacao']) {
                    $data['produtosCotacao'][$k]['ofertas'][] = $prodOfer;
                }
            }
        }

        $data['to_datatable'] = "{$this->route}/to_datatable/";
        $data['formAction'] = "{$this->route}/adicionarItem/";

        $data['header'] = $this->tmp_conv->header(['title' => $page_title,]);
        $data['navbar'] = $this->tmp_conv->navbar();
        $data['sidebar'] = $this->tmp_conv->sidebar();
        $data['heading'] = $this->tmp_conv->heading([
            'page_title' => $page_title,
            'buttons' => $buttons
        ]);
        $data['scripts'] = $this->tmp_conv->scripts();

        $this->load->view("{$this->views}/produtos", $data);
    }


    private function _form($id = null)
    {

        if (is_null($id)) {
            $page_title = "Nova Cotação";
            // get last insert id
            $lastId = $this->getLastId();

            $data['lastId'] = $lastId;

        } else {
            $page_title = "Edição de Cotação";

        }

        $data['to_datatable'] = "{$this->route}/to_datatable/";
        $data['url_update'] = "{$this->route}/update";
        $data['header'] = $this->tmp_conv->header(['title' => $page_title,]);
        $data['navbar'] = $this->tmp_conv->navbar();
        $data['sidebar'] = $this->tmp_conv->sidebar();
        $data['heading'] = $this->tmp_conv->heading([
            'page_title' => $page_title,
            'buttons' => [
                ['type' => 'submit',
                    'id' => 'btnCotacao',
                    'form' => 'frmCotacao',
                    'class' => 'btn-primary',
                    'icone' => 'fa-check',
                    'label' => 'Salvar Cotação'
                ],
            ]
        ]);
        $data['scripts'] = $this->tmp_conv->scripts();

        $this->load->view("{$this->views}/form", $data);
    }

    public function to_datatable($encerradas = false)
    {
        $where = null;

        if (!$encerradas) {
            $where = "cot.dt_vencimento > now()";
        }


        $r = $this->datatable->exec(
            $this->input->post(),
            'conv_cotacoes cot',
            [
                ['db' => 'cot.id', 'dt' => 'id'],
                ['db' => 'cot.dt_abertura', 'dt' => 'dt_abertura'],
                ['db' => 'cot.dt_vencimento', 'dt' => 'dt_vencimento'],
                ['db' => 'cot.id_comprador', 'dt' => 'id_comprador'],
                ['db' => 'cot.situacao', 'dt' => 'situacao'],
                ['db' => 'cot.ds_cotacao', 'dt' => 'ds_cotacao'],
                ['db' => 'c.cnpj', 'dt' => 'cnpj'],
                ['db' => 'c.nome_fantasia', 'dt' => 'nome_fantasia'],
                ['db' => 'c.razao_social', 'dt' => 'razao_social'],
                ['db' => 'c.estado', 'dt' => 'estado'],
            ],
            [
                ["compradores c", "c.id = cot.id_comprador"],
            ],
            $where
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    private function getLastId()
    {
        $lastId = $this->db->select('max(id) as lastid')->get('conv_cotacoes')->row_array();
        if (isset($lastId['lastid']) && !is_null($lastId['lastid'])) {
            $lastId = $lastId['lastid'] + 1;
        } else {
            $lastId = 10100;
        }

        return $lastId;
    }

}

/* End of file: Promocoes.php */
