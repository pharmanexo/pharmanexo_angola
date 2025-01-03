<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cotacoes extends MY_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('cotacoes');
        $this->views = 'fornecedor/cotacoes';
    }

    public function index()
    {
        $page_title = "Cotações Abertas";

        $data['to_datatable'] = "{$this->route}/to_datatable";
        $data['urlUpdate'] = "{$this->route}/detalhes/";

        $data['header'] = $this->template->header([
            'title' => $page_title
        ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading(['page_title' => $page_title,

        ]);
        $data['scripts'] = $this->template->scripts([
        ]);


        $this->load->view("{$this->views}/main", $data);
    }

    public function encerradas()
    {
        $page_title = "Cotações Encerradas";

        $data['to_datatable'] = "{$this->route}/to_datatable/1";
        $data['urlUpdate'] = "{$this->route}/detalhes/";

        $data['header'] = $this->template->header(['title' => 'Cotações Encerradas',]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,

        ]);
        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/main_encerradas", $data);
    }

    public function abertura($id = null)
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $post['situacao'] = 1;

            $cotExist = $this->db->where('id', $post['id'])->get('cotacoes');

            if ($cotExist->num_rows() > 0) {
                $idCot = $post['id'];
                unset($post['id']);

                $result = $this->db->where('id', $idCot)->update('cotacoes', $post);
                $insertId = $idCot;

            } else {
                $post['id_comprador'] = $this->session->id_empresa;
                $post['dt_abertura'] = date('Y-m-d H:i:s', time());

                $result = $this->db->insert('cotacoes', $post);
                $insertId = $this->db->insert_id();
            }


            if ($result) {
                redirect("{$this->route}/detalhes/{$insertId}");
            }

        } else {

            $this->_form($id);

        }
    }

    public function publicar($idCot)
    {
        if (isset($idCot)) {

            $this->db->where('id', $idCot)->update('cotacoes', ['situacao' => 2]);

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

            $prodExist = $this->db
                ->where('id_produto_catalogo', $post['id_produto_catalogo'])
                ->get('cotacoes_produtos')->row_array();

            if (!empty($prodExist)) {
                $valor = ($post['quantidade'] + $prodExist['quantidade']);

                $warning = ['type' => 'warning', 'message' => "Deseja atualizar quantidade do produto para {$valor} ?", 'data' => ['id' => $prodExist['id'], 'qtd' => $valor]];

            } else {
                $this->db->insert('cotacoes_produtos', $post);

                $warning = ['type' => 'success', 'message' => "Produto inserido com sucesso."];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
    }

    public function deleteItem($idProd)
    {

        $delete = $this->db->where('id', $idProd)->delete('cotacoes_produtos');

        if ($delete) {
            $warning = ['type' => 'success', 'message' => "Produto excluído com sucesso."];
        } else {
            $warning = ['type' => 'warning', 'message' => "Erro ao excluir o produto"];

        }

        $this->output->set_content_type('application/json')->set_output(json_encode($warning));


    }

    public function atualizarItem()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            if (isset($post['id']) && isset($post['qtd'])) {
                $upd = $this->db->set('quantidade', $post['qtd'])->where('id', $post['id'])->update('cotacoes_produtos');

                if ($upd) {
                    $warning = ['type' => 'success', 'message' => "Produto atualizado com sucesso."];
                } else {
                    $warning = ['type' => 'warning', 'message' => "Erro ao atualizar o produto"];

                }

                $this->output->set_content_type('application/json')->set_output(json_encode($warning));

            }

        }
    }

    public function encerrar($id)
    {

        $encerra = $this->db
            ->set('situacao', 9)
            ->set('data_encerramento', date('Y-m-d H:i:s', time()))
            ->where('id', $id)
            ->where('situacao <> 9')
            ->update('cotacoes');

        if ($encerra) {
            $warning = ['type' => 'success', 'message' => "Cotação encerrada com sucesso."];
        } else {
            $warning = ['type' => 'warning', 'message' => "Erro ao encerrar a cotação"];

        }

        $this->output->set_content_type('application/json')->set_output(json_encode($warning));
    }

    public function reabrir($id)
    {

        $encerra = $this->db
            ->set('situacao', 0)
            ->set('data_encerramento', NULL)
            ->where('id', $id)
            ->update('cotacoes');

        if ($encerra) {
            $warning = ['type' => 'success', 'message' => "Cotação reaberta com sucesso."];
        } else {
            $warning = ['type' => 'warning', 'message' => "Erro ao reabrir a cotação"];

        }

        $this->output->set_content_type('application/json')->set_output(json_encode($warning));
    }

    public function detalhes($id)
    {
        $page_title = "Detalhes da Cotação";
        $data = $this->getCotacao($id);

        if ($data['cotacao']['situacao'] == 0) {
            redirect("{$this->route}/abertura/{$id}");
        }

        if (isset($data['cotacao']['id_condicao_pagamento'])) {
            $idfp = $data['cotacao']['id_condicao_pagamento'];

            $data['cotacao']['condicao'] = $this->db
                ->where('id', $idfp)
                ->get('formas_pagamento')
                ->row_array();
        }

        if (isset($_SESSION['id_empresa']) && $data['cotacao']['id_comprador'] == $_SESSION['id_empresa']) {
            if ($data['cotacao']['situacao'] > 1) {
                $buttons = [
                    [
                        'type' => 'button',
                        'id' => 'btnReabrirCot',
                        'url' => "{$this->route}/reabrir/$id",
                        'class' => 'btn-info',
                        'icone' => 'fa-pen',
                        'label' => 'Reabrir Cotação'
                    ],
                    [
                        'type' => 'button',
                        'id' => 'btnEncerraCot',
                        'url' => "{$this->route}/encerrar/$id",
                        'class' => 'btn-primary',
                        'icone' => 'fa-ban',
                        'label' => 'Encerrar Cotação'
                    ]
                ];
            }
        }else{
            $buttons = [];
        }

        $data['produtos'] = $this->db->get('catalogo')->result_array();


        /*  $data['ofertas'] = $this->db
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
          }*/

        $data['to_datatable'] = "{$this->route}/to_datatable/";
        $data['formAction'] = "{$this->route}/adicionarItem/";

        $data['header'] = $this->template->header(['title' => $page_title,]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => $buttons
        ]);
        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/produtos", $data);
    }

    public function mapa_ofertas($id)
    {

        $page_title = "Detalhes da Cotação";
        $data = $this->getCotacao($id);

        if ($data['cotacao']['situacao'] == 0) {
            redirect("{$this->route}/abertura/{$id}");
        }

        if (isset($data['cotacao']['id_condicao_pagamento'])) {
            $idfp = $data['cotacao']['id_condicao_pagamento'];

            $data['cotacao']['condicao'] = $this->db
                ->where('id', $idfp)
                ->get('formas_pagamento')
                ->row_array();
        }

        if (isset($_SESSION['id_empresa']) && $data['cotacao']['id_comprador'] == $_SESSION['id_empresa']) {
            if ($data['cotacao']['situacao'] > 1) {
                $buttons = [
                    [
                        'type' => 'button',
                        'id' => 'btnReabrirCot',
                        'url' => "{$this->route}/reabrir/$id",
                        'class' => 'btn-info',
                        'icone' => 'fa-pen',
                        'label' => 'Reabrir Cotação'
                    ],
                    [
                        'type' => 'button',
                        'id' => 'btnEncerraCot',
                        'url' => "{$this->route}/encerrar/$id",
                        'class' => 'btn-primary',
                        'icone' => 'fa-ban',
                        'label' => 'Encerrar Cotação'
                    ]
                ];
            }
        }else{
            $buttons = [];
        }

        $data['produtos'] = $this->db
            ->select('c.*')
            ->from('catalogo c')
            ->join('estoque e', 'e.codprod = c.codprod')
            ->where('id_fornecedor', 4)
            ->get()
            ->result_array();

        $data['formasPgto'] = $this->db->get('formas_pagamento')->result_array();

        /*  $data['ofertas'] = $this->db
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
          }*/

        $data['to_datatable'] = "{$this->route}/to_datatable/";
        $data['formAction'] = "{$this->route}/adicionarItem/";

        $data['header'] = $this->template->header(['title' => $page_title,]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => $buttons
        ]);
        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/form_ofertas", $data);
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
            $data = $this->getCotacao($id);
        }

        $data['to_datatable'] = "{$this->route}/to_datatable/";
        $data['url_update'] = "{$this->route}/update";
        $data['header'] = $this->template->header(['title' => $page_title,]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
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
        $data['scripts'] = $this->template->scripts([]);

        $data['formasPgto'] = $this->db->get('formas_pagamento')->result_array();

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
            'cotacoes cot',
            [
                ['db' => 'cot.id', 'dt' => 'id'],
                ['db' => 'cot.dt_abertura', 'dt' => 'dt_abertura', 'formatter' => function ($d) {
                    return date('d/m/Y H:i', strtotime($d));
                }],
                ['db' => 'cot.dt_vencimento', 'dt' => 'dt_vencimento', 'formatter' => function ($d) {
                    return date('d/m/Y H:i', strtotime($d));
                }],
                ['db' => 'cot.id_comprador', 'dt' => 'id_comprador'],
                ['db' => 'cot.situacao', 'dt' => 'situacao'],
                ['db' => 'cot.ds_cotacao', 'dt' => 'ds_cotacao'],
                ['db' => 'c.nif', 'dt' => 'nif'],
                ['db' => 'c.nome', 'dt' => 'nome_fantasia'],
                ['db' => 'c.estado', 'dt' => 'estado'],
            ],
            [
                ["empresas c", "c.id = cot.id_comprador"],
            ],
            $where
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    private function getLastId()
    {
        $lastId = $this->db->select('max(id) as lastid')->get('cotacoes')->row_array();
        if (isset($lastId['lastid']) && !is_null($lastId['lastid'])) {
            $lastId = $lastId['lastid'] + 1;
        } else {
            $lastId = 10100;
        }

        return $lastId;
    }

    private function getCotacao($id)
    {

        $data['cotacao'] = $this->db
            ->select('cot.*, c.nif, c.nome, c.estado, c.cidade, st.descricao as status')
            ->from('cotacoes cot')
            ->join('empresas c', 'c.id = cot.id_comprador')
            ->join('aux_cotacoes_status st', 'st.id = cot.situacao', 'LEFT')
            ->where('cot.id', $id)
            ->get()->row_array();


        $data['produtosCotacao'] = $this->db
            ->select('p.*, concat(pms.nome," - ", pms.substancia, " - ", pms.dosagem, " - ", pms.embalagem, " - ", pms.forma_farmaceutica) as nome')
            ->from('cotacoes_produtos p')
            ->join('catalogo pms', 'pms.id = p.id_produto_catalogo')
            ->where('p.id_cotacao', $id)
            ->order_by('p.id DESC')
            ->get()->result_array();

        return $data;


    }

}

/* End of file: Promocoes.php */
