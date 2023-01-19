<?php

class ImportarCotacoes extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('/fornecedor/ImportarCotacoes/');
        $this->views = 'fornecedor/cotacoes/';

        $this->DB_SINTESE = $this->load->database('sintese', TRUE);
        $this->DB_BIONEXO = $this->load->database('bionexo', TRUE);
        $this->DB_APOIO = $this->load->database('apoio', TRUE);

    }

    public function index()
    {
        $page_title = 'Importar Cotação';
        # Template
        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [

            ]
        ]);

        $data['portais'] = $this->db->get('integradores')->result_array();

        $this->load->view("{$this->views}/main", $data);
    }

}
