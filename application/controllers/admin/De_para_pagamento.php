<?php

class De_para_pagamento extends CI_Controller
{
    private $views;
    private $route;
    private $bio;

    public function __construct()
    {
        parent::__construct();

        $this->views = "admin/de_para_pgto";
        $this->route = base_url("admin/de_para_pagamento");

        $this->apoio = $this->load->database('apoio', true);

        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_usuarios', 'usuarios');
    }

    public function index()
    {
        $page_title = "De/Para Formas Pagamento";

        # TEMPLATE
        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'submit',
                    'id' => 'btnSave',
                    'form' => 'frm',
                    'class' => 'btn-primary',
                    'icone' => 'fa-save',
                    'label' => 'Salvar Alterações'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts();

        $data['form_action'] = "{$this->route}/insert";

        $data['fpPharmanexo'] = $this->db->get('formas_pagamento')->result_array();

        $data['formasPgto'] = $this->db
            ->select('fp.id, fp.id_integrador, fp.descricao, i.desc')
            ->from('formas_pagamento_integradores fp')
            ->join('integradores i', 'i.id = fp.id_integrador')
            ->where('fp.id_integrador', 4)
            ->get()
            ->result_array();


        $this->load->view("{$this->views}/main", $data);
    }

    public function insert()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();
            $insert = [];

            foreach ($post['fp'] as $k => $item) {

                switch ($k) {
                    case 'Huma':
                        foreach ($post['fp']['Huma'] as $item) {

                            $fpExist = $this->db
                                ->where('cd_forma_pagamento', $item['cd_forma_pagamento'])
                                ->where('id_forma_pagamento', $item['id_forma_pagamento'])
                                ->where('integrador', 4)
                                ->get('formas_pagamento_depara');

                            if ($fpExist->num_rows() == 0){
                                $insert[] = [
                                    'cd_forma_pagamento' => $item['cd_forma_pagamento'],
                                    'id_forma_pagamento' => $item['id_forma_pagamento'],
                                    'descricao' => $item['descricao'],
                                    'integrador' => 4,
                                    'ativo' => 1,
                                    'qtd_dias' => 0,
                                ];
                            }
                        }

                }

                if (empty($item['cd_forma_pagamento'])) {
                    unset($post['fp'][$k]);
                }
            }

            if ($this->db->insert_batch('formas_pagamento_depara', $insert) == 0) {
                $array = array(
                    'type' => 'success',
                    'message' => 'Realizado com sucesso',
                );
            } else {
                $array = array(
                    'type' => 'warning',
                    'message' => 'Falha ao realizar de/para',
                );
            }

            $this->session->set_userdata('warning', $array);
            redirect($this->route, 'refresh');

        }
    }
}
