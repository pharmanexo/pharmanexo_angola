<?php
date_default_timezone_set('America/Sao_Paulo');

class CotacoesResponsaveis extends MY_Controller
{

    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('/fornecedor/cotacoesResponsaveis');
        $this->views = 'fornecedor/cotacoes/cotacoesResponsaveis';

    }

    /**
     * Exibe a view da lista de cotações
     *
     * @return view
     */
    public function index()
    {
        $page_title = "Responsáveis por Cotação";

        # URLs
        $data['datatable'] = "{$this->route}/datatable";


        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
            ]
        ]);
        $data['scripts'] = $this->template->scripts();


        $data['usuarios'] = $this->db->select('u.id, u.nickname')
            ->from('usuarios u')
            ->join('usuarios_fornecedores uf', 'uf.id_usuario = u.id')
            ->where('uf.id_fornecedor', $this->session->id_fornecedor)
            ->order_by('u.nickname ASC')
            ->get()
            ->result_array();

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Obtem as cotações por produto
     *
     * @return json
     */
    public function datatable()
    {
        if ($this->input->is_ajax_request()) {
            $post = $this->input->post();

            $r = $this->datatable->exec(
                $post,
                'cotacoes_produtos cp',
                [
                    ['db' => 'cp.id_usuario', 'dt' => 'id_usuario'],
                    ['db' => 'cp.cd_cotacao', 'dt' => 'cd_cotacao'],
                    ['db' => 'cp.integrador', 'dt' => 'integrador'],
                    ['db' => 'u.nickname', 'dt' => 'nickname'],
                    ['db' => 'cp.data_criacao', 'dt' => 'data_criacao', 'formatter' => function ($d) {
                        return date('d/m/Y', strtotime($d));
                    }],
                ],
                [
                    ['usuarios u', 'u.id = cp.id_usuario', 'left']
                ],
                "cp.id_fornecedor = {$this->session->id_fornecedor} and cp.id_usuario is not null",
                "cp.id_usuario, cp.cd_cotacao"
            );

            $this->output->set_content_type('application/json')->set_output(json_encode($r));
        }
    }

}
