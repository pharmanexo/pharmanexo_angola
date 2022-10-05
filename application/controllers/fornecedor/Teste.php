<?php
defined('BASEPATH') or exit('No direct script access allowed');

class teste extends CI_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('/fornecedor/teste');
        $this->views = 'fornecedor/teste';

        error_reporting(E_ALL);
        ini_set("display_errors", 1);
    }

    public function index()
    {

        // $nome[0] = "Marlon";
        // $nome[1] = "Chule";
        // $nome[2] = "Eric";
        // $nome[3] = "karina";

        // foreach ($nome as $key => $item) {

        //     $result = $this->db->where('nome', $item)->limit(1)->get("teste")->row_array();

        //     if ( is_null($result) ) {
                
        //         $this->db->insert("teste", ['nome' => $item]);
        //     } else {

        //         echo $item . "<br>";
        //     }
        // }

        
        $url = __DIR__ . "\\Teste.php";

        $fp = fopen($url, "r");


        flock($fp, LOCK_SH);

        $result = $this->db->where('nome', 'Chule')->get("teste");

        if ( $result->num_rows() == 0 ) {

            foreach (range(1, 20) as $item) {

                sleep(5);

                $this->db->insert("teste", ['nome' => 'Chule']);
            }
        } else {

            $this->db->insert("teste", ['nome' => 'Marlon']);
        }

        flock($fp, LOCK_UN); // libera o lock

        fclose($fp);
    }
}

/* End of file: Configuracao.php */
