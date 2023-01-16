<?php

class MatchDePara extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        $produtos = $this->db
            ->select('codigo, nome_comercial, descricao, id_fornecedor')
            ->where('id_fornecedor', 5038)
            //->where('codigo', 8)
            ->where('ativo', 1)
            // ->where('(ocultar_de_para is null or ocultar_de_para = 0)')
            // ->limit(1000)
            ->get('produtos_catalogo')
            ->result_array();
      

        foreach ($produtos as $j => $prod) {

            $produto = (!empty($prod['nome_comercial'])) ? $prod['nome_comercial'] : $prod['descricao'];

            #$produto = "DESVENLAFAXINA G. ALT 100 MG 30 CP (C1)";
            $keys = $this->repairProd(explode(' ', $produto));
            $keysDesc = $this->repairProd(explode(' ', $prod['descricao']));
            $pos = strripos($produto, ' MG', 0);
            $mg = $this->getMg($prod['descricao']);
            $ml = $this->getMl($prod['descricao']);

            if (!isset($keys[0])) {
                continue;
            }


            $search = $keys[0];


            if ($prod['nome_comercial'] == 'GENERICO' || $prod['nome_comercial'] == 'ACTIVE' || $prod['nome_comercial'] == 'NT') {
                /**************************************************** BUSCA NA CMED (ANVISA) ****************************************/

                $encontrados = $this->buscaAnvisa($keysDesc[0]);

                /**************************************************** BUSCA NA SINTESE ****************************************/
                if (empty($encontrados)) {
                    $encontrados = $this->buscaSintese($keysDesc);
                }


            } else {
                /**************************************************** BUSCA NA SINTESE ****************************************/
                $encontrados = $this->buscaAnvisa($search);
            }


            /**************************************************** BUSCA NA SINTESE ****************************************/
            if (empty($encontrados)) {
                $encontrados = $this->buscaSintese($keysDesc);
            }


            if ($mg > 0 || $ml > 0) {
                $encontrados = $this->organize($encontrados, $mg, $ml);
            }


            if (!empty($encontrados)) {
                $produtos[$j]['encontrados'] = $encontrados;
                $this->updateCatalogo($prod['codigo'], $prod['id_fornecedor']);
            }

        };


        $this->saveDePara($produtos);

    }

    private function organize($encontrados, $mg, $ml)
    {
        foreach ($encontrados as $k => $encontrado) {
            $name = $encontrado['descricao'];


            // busca por mg
            if ($mg > 0 && $ml == 0) {

                $p1 = strripos($name, " {$mg} mg", 0);
                $p2 = strripos($name, " {$mg}mg", 0);


                if ($p1 == false && $p2 == false) {
                    unset($encontrados[$k]);
                    continue;
                }

            } else if ($ml > 0 && $mg == 0) {

                if (intval($ml) > 0) {
                    $busca1 = strripos($name, " {$ml} ml", 0);
                    $busca2 = strripos($name, " {$ml}ml", 0);

                    if ($busca1 == false && $busca2 == false) {
                        unset($encontrados[$k]);
                    }
                }

            } else if ($mg > 0 && $ml > 0) {
                $p1 = strripos($name, " {$mg} mg", 0);
                $p2 = strripos($name, " {$mg}mg", 0);


                if ($p1 == false && $p2 == false) {
                    unset($encontrados[$k]);
                    continue;
                }


                if (intval($ml) > 0) {
                    $busca1 = strripos($name, " {$ml} ml", 0);
                    $busca2 = strripos($name, " {$ml}ml", 0);

                    if ($busca1 == false && $busca2 == false) {
                        unset($encontrados[$k]);
                    }
                }
            } else {
                unset($encontrados[$k]);
            }
        }


        return $encontrados;
    }

    private function updateCatalogo($codigo, $idfornecedor)
    {
        $this->db->where('codigo', $codigo);
        $this->db->where('id_fornecedor', $idfornecedor);

        $data = [
            'ocultar_de_para' => 1
        ];

        return $this->db->update('produtos_catalogo', $data);
    }

    private function saveDePara($produtos)
    {
        $insert = [];
        foreach ($produtos as $k => $produto) {

            if (!isset($produto['encontrados'])) {
                continue;
            }

            $codigo = $produto['codigo'];

            foreach ($produto['encontrados'] as $encontrado) {
                $insert[] = [
                    'id_sintese' => $encontrado['id_sintese'],
                    'id_pfv' => $codigo,
                    'id_usuario' => 9999,
                    'cd_produto' => $codigo,
                    'id_fornecedor' => $produto['id_fornecedor']
                ];
            }
        }

        $this->db->insert_batch('produtos_pre_match', $insert);
    }

    private function buscaAnvisa($search)
    {
        $principios = [];
        $encontrados = [];
        $anvisa = $this->db->where('produto', $search)->get('produtos_cmed')->row_array();


        if (isset($anvisa['id'])) {
            $principios = $this->db->where('id_produto', $anvisa['id'])->get('produtos_cmed_principios')->result_array();
        }

        if (!empty($principios)) {
            $this->db->select('id_produto, id_sintese, descricao');
            foreach ($principios as $principio) {
                $this->db->where("descricao like '%{$principio['descricao']}%'");
            }
            $this->db->group_by('id_produto');

            $encontrados = $this->db->get('produtos_marca_sintese')->result_array();

        }

        return $encontrados;
    }

    private function buscaSintese($keys)
    {
        $encontrados = [];

        /*   if (isset($keys[0]) && isset($keys[1])) {
               $where = "descricao like '%{$keys[0]}%' and descricao like '%{$keys[1]}%'";
           } else if (isset($keys[0])) {

           } else {
               return [];
           }*/

        $where = "descricao like '{$keys[0]}%'";

        $encontrados = $this->db
            ->select('id_produto, id_sintese, descricao')
            ->where($where)
            ->group_by('id_produto')
            ->get('produtos_marca_sintese')
            ->result_array();


        foreach ($encontrados as $k => $encontrado) {
            $term = str_replace(" ", "", strtolower($this->tirarAcentos($encontrado['descricao'])));
            $pts = 0;


            foreach ($keys as $key) {


                if (strripos(strtolower($key), "mg/ml", 0) !== false) {
                    $key = intval($key) . "mg";
                }

                if (strripos(strtolower($key), "c/", 0) !== false) {

                    $key = trim(str_replace("c/", " ", strtolower($key)));

                }

                $key = str_replace(" ", "", $key);

                $res = strripos($term, strtolower($this->tirarAcentos($key)), 0);
                if ($res !== false) {
                    $pts = $pts + 10;
                }
            }

            $base = count($keys) * 10;
            $p = ($pts / $base) * 100;

            if ($p < 70) {
                unset($encontrados[$k]);
            }

        }


        return $encontrados;

    }

    private function getMl($prd)
    {
        $res = strripos($prd, ' ML', 0);

        if ($res == false) {
            $res = strripos($prd, 'ML', 0);

        }

        if ($res == false) {
            return 0;
        } else {
            return intval(preg_replace("/[^0-9]/", "", substr($prd, ($res - 4), 5)));
        }

    }

    private function getMg($prd)
    {
        $res = strripos($prd, ' MG', 0);


        if ($res == false) {
            $res = strripos($prd, 'MG', 0);

            if ($res == false) {
                return 0;
            }
        }

        return preg_replace("/[^0-9]/", "", substr($prd, ($res - 4), 5));

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
