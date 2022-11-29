<?php

class UpdateCotacoesEncerrada extends CI_Controller
{

    private $sint;
    private $bio;
    private $apoio;

    public function __construct()
    {
        parent::__construct();

        $this->sint = $this->load->database('sintese', true);
        $this->bio = $this->load->database('bionexo', true);
        $this->apoio = $this->load->database('apoio', true);

    }

    public function index()
    {

        // reabre
        $this->sint
            ->where('encerrada', '1')
            ->where('dt_fim_cotacao > now()')
            ->update('cotacoes', ['encerrada' => 0]);

        $this->bio
            ->where('encerrada', '1')
            ->where('dt_fim_cotacao > now()')
            ->update('cotacoes', ['encerrada' => 0]);

        $this->apoio
            ->where('encerrada', '1')
            ->where('dt_fim_cotacao > now()')
            ->update('cotacoes', ['encerrada' => 0]);


        // encerra
        $this->sint
            ->where('encerrada', '0')
            ->where('dt_fim_cotacao < now()')
            ->update('cotacoes', ['encerrada' => 1]);

        $this->bio
            ->where('encerrada', '0')
            ->where('dt_fim_cotacao < now()')
            ->update('cotacoes', ['encerrada' => 1]);

        $this->apoio
            ->where('encerrada', '0')
            ->where('dt_fim_cotacao < now()')
            ->update('cotacoes', ['encerrada' => 1]);
    }


}
