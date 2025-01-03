<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MargemEstoque extends CI_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/regras_vendas/margemEstoque');
        $this->views = 'fornecedor/regras_vendas/margemEstoque';

        $this->load->model('m_estados', 'estado');
        $this->load->model('m_configuracoes_envio', 'config_envio');
    }

    /**
     * exibe a view fornecedor/regras_vendas/configuracoesEnvio/main.php
     *
     * @return view
     */
    public function index()
    {
        $page_title = "Configurações de Estoque";

        $data['formAction'] = "{$this->route}/update";

        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title
        ]);
        $data['scripts'] = $this->template->scripts(['scripts' => []]);

        $data['dados'] = $this->db->where('id_fornecedor', $this->session->id_fornecedor)->get('margem_estoque')->row_array();

        $data['dados']['disp'] = (100 - intval($data['dados']['margem']));

        $this->load->view("{$this->views}/main", $data);
    }

    public function update()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();
            if ( $post['margem'] > 100){
                $warning = [
                    'type' => 'warning',
                    'message' => 'Margem maior que 100% não permitido'
                ];
            }else {

                $data = [
                    'id_fornecedor' => $this->session->id_fornecedor,
                    'margem' => $post['margem'],
                    'responder_zerados' => (isset($post['sem_estoque'])) ? 1 : 0,
                    'oferta_parcial' => (isset($post['oferta_parcial'])) ? 1 : 0,
                    'notificar_zerado' => (isset($post['alerta_sem_estoque'])) ? 1 : 0,
                    'envia_obs_parcial' => (isset($post['envia_obs'])) ? 1 : 0,
                    'destinatarios' => $post['emails']
                ];

                $this->db->where('id_fornecedor', $this->session->id_fornecedor)->delete('margem_estoque');

                $i = $this->db->insert('margem_estoque', $data);

                if ($i) {
                    $warning = [
                        'type' => 'success',
                        'message' => 'Atualizado com sucesso!'
                    ];
                } else {
                    $warning = [
                        'type' => 'warning',
                        'message' => 'Houve um erro ao atualizar'
                    ];
                }
            }

            $this->session->set_flashdata('warning', $warning);
            redirect($this->route);

        }
    }
}

/* End of file: Controle_cotacoes.php */
