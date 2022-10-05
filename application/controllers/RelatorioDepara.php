<?php

class RelatorioDepara extends CI_Controller
{
    private $views;
    private $route;
    private $bio;

    public function __construct()
    {
        parent::__construct();

        $this->bio = $this->load->database('bionexo', true);

        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_usuarios', 'usuarios');
    }

    public function bionexo()
    {
        // seleciona os compradores

        $data = $this->db->distinct()->select('c.id, c.cnpj, estado')->from('cotacoes_bionexo.catalogo ct')->join('pharmanexo.compradores c', 'c.id = ct.id_cliente')->get()->result_array();


        
        
        // pega qtd de de/para se for 0 sem depara coloca como finalizado

        // separa por estado


    }
}