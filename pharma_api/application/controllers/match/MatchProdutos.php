<?php

class MatchProdutos extends CI_Controller
{
    private $id_fornecedor;

    public function __construct()
    {
        parent::__construct();

        $this->id_fornecedor = 5018;
    }

    public function index()
    {
        $produtos = $this->db->query("select codigo from produtos_catalogo where id_fornecedor = {$this->id_fornecedor} and ocultar_de_para = 0" )->result_array();

        foreach ($produtos as $produto){

            $this->db->where('cd_produto', $produto['codigo']);
            $this->db->where('id_fornecedor', $this->id_fornecedor);
            $exist = $this->db->get('produtos_fornecedores_sintese')->result_array();

            if (!empty($exist)){

                $this->db->where('codigo', $produto['codigo']);
                $this->db->where('id_fornecedor', $this->id_fornecedor);
                $this->db->update('produtos_catalogo', ['ocultar_de_para' => 1]);

            }



        }
    }


    private function repairProd($keys)
    {
        $data = [];
        foreach ($keys as $p => $key) {
            $valid = true;
            switch (strtoupper($key)) {
                case '-':
                case 'DE':
                case '|':
                case '':
                case 'REF.':
                case 'REF':
                case '+':
                case '(G)':
                case '(C1)':
                case '(C)':
                    $valid = false;
                    break;
                default:
                    break;
            }

            if ($valid) {
                $data[] = $key;
            }
        }

        return $data;
    }

    function tirarAcentos($string)
    {
        return preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), $string);
    }
}
