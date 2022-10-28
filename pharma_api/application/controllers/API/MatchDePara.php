<?php

class MatchDePara extends CI_Controller
{
    private $unidades;

    public function __construct()
    {
        parent::__construct();

        $this->unidades = [
            'comprimido' => 'comp|cmp',
            'comp' => 'comprimido',
            'cpr' => 'comprimido',
            'cap' => 'capsula',
            'cp' => 'capsula',
            'amp' => 'ampola',
            'dragea' => 'comp|comprimido',
            'fr' => 'frasco',
            'inj' => 'injetavel|amp|ampola',
            'iv' => 'injetaval|inj|amp|ampola',
            'ev' => 'injetaval|inj|amp|ampola',
            'cr' => 'creme',
        ];

    }

    private function get_chaves()
    {
        $chaves = [
            'solucao' => 'solução',
            'glicosado' => 'glicose',
            'glicerina' => 'glicerinada',
            'cap' => 'capsula',
            'comp' => 'comprimido',
            'cpr' => 'comp',
            'bolsa' => 'fechado',
            'litro' => '1000',
            '1L' => '1000',
            '1 L' => '1000',
            'tamp.' => 'tamponado',
            'clav.' => 'clavulanato',
            'vag' => 'vaginal',
            'aplic' => 'aplicador'
        ];

        return $chaves;
    }

    public function index()
    {
        $id_fornecedor = 5039;

        $produtos = $this->db->query("select codigo, nome_comercial, descricao, id_fornecedor
                            from produtos_catalogo pc
                            where pc.id_fornecedor = {$id_fornecedor}
                              and pc.codigo not in (select pfs.cd_produto from produtos_fornecedores_sintese pfs 
                              where pfs.id_fornecedor = {$id_fornecedor}) and ( pc.ocultar_de_para = 0 or pc.ocultar_de_para is null)")->result_array();


        foreach ($produtos as $j => $prod) {

            $produto = (!empty($prod['nome_comercial'])) ? $prod['nome_comercial'] : $prod['descricao'];
            $produto = str_replace("-", " ", $produto);
            $produto = str_replace("/", " ", $produto);


            #$produto = "DESVENLAFAXINA G. ALT 100 MG 30 CP (C1)";
            $keys = $this->repairProd(explode(' ', $produto));
            $keysDesc = $this->repairProd(explode(' ', $produto));
            $pos = strripos($produto, ' MG', 0);


            $mg = $this->getMg($prod['nome_comercial']);
            $g = $this->getG($prod['nome_comercial']);
            $gr = $this->getGr($prod['nome_comercial']);
            $ml = $this->getMl($prod['nome_comercial']);
            $ui = $this->getUI($prod['nome_comercial']);
            $mcg = $this->getMCG($prod['nome_comercial']);
            $perc = $this->getPerc($prod['nome_comercial']);


            if (!isset($keys[0])) {
                continue;
            }

            $search = $keys[0];


            if ($prod['nome_comercial'] == 'GENERICO' || $prod['nome_comercial'] == 'ACTIVE' || $prod['nome_comercial'] == 'NT') {
                /**************************************************** BUSCA NA SINTESE ****************************************/
                $encontrados = $this->buscaSintese($keysDesc);

                /**************************************************** BUSCA NA CMED (ANVISA) ****************************************/
                if (empty($encontrados)) {

                    $encontrados = $this->buscaAnvisa($keysDesc[0]);
                }

            } else {
                /**************************************************** BUSCA NA SINTESE ****************************************/
                $encontrados = $this->buscaSintese($keysDesc);


            }



            /**************************************************** BUSCA NA ANVISA ****************************************/
            if (empty($encontrados)) {
                $encontrados = $this->buscaAnvisa($search);
            }




            if (!empty($encontrados)) {
                foreach ($encontrados as $jj => $encontrado) {


                    $term = str_replace(" ", "", strtolower($this->tirarAcentos($encontrado['descricao'])));


                    foreach ($keys as $key) {
                        $key = strtolower($key);
                        if (isset($this->unidades[$key])) {


                            $und = explode("|", strtolower($this->unidades[$key]));
                            $v = false;

                            foreach ($und as $un) {
                                $t1 = strripos(strtolower($term), $key, 0);
                                $t2 = strripos(strtolower($term), $un, 0);


                                if ($t1 != false || $t2 != false) {
                                    $v = true;
                                }
                            }

                            if ($v == false) {
                                unset($encontrados[$jj]);
                                continue 2;
                            }
                        }

                    }
                }
            }


            $filter = [
                'mg' => str_replace(",", '.', $mg),
                'g' => str_replace(",", '.', $g),
                'gr' => str_replace(",", '.', $gr),
                'ml' => str_replace(",", '.', $ml),
                'ui' => str_replace(",", '.', $ui),
                'mcg' => str_replace(",", '.', $mcg),
                'perc' => str_replace(",", '.', $perc)
            ];


            $temFiltro = false;
            foreach ($filter as $fil) {
                if (!empty($fil)) {
                    $temFiltro = true;
                }
            }

            if ($temFiltro == false) {
                continue;
            }

            $filter['encontrados'] = $encontrados;

            $encontrados = $this->organize($filter);

            if (!empty($encontrados)) {

                $produtos[$j]['encontrados'] = $encontrados;
                $this->updateCatalogo($prod['codigo'], $prod['id_fornecedor']);
            }

        };


        $this->saveDePara($produtos);

    }

    private function organize($data)
    {
        if (empty($data['encontrados'])) {
            return [];
        }

        $encontrados = $data['encontrados'];

        foreach ($encontrados as $k => $encontrado) {


            if ($data['mg'] == 0 and $data['ml'] == 0 and $data['mcg'] == 0 and $data['ui'] == 0 && $data['perc']) {
                return false;
            }

            $name = $encontrado['descricao'];
            $unid = "";
            $n = 0;

            if ($data['mg'] > 0) {
                $unid = "mg";
                $n = $data['mg'];
            } else if ($data['ml'] > 0) {
                $unid = "ml";
                $n = $data['ml'];
            } else if ($data['g'] > 0) {
                $unid = "g";
                $n = $data['g'];
            } else if ($data['mcg'] > 0) {
                $unid = "mcg";
                $n = $data['mcg'];
            } else if ($data['perc'] > 0) {
                $unid = "%";
                $n = $data['perc'];
            } else if ($data['perc'] > 0) {
                $unid = "gr";
                $n = $data['gr'];
            } else if ($data['ui'] > 0) {
                $unid = "ui";
                $n = $data['ui'];
            } else {
                $unid = "";
            }

            if (!empty($unid)) {

                $p1 = strripos($name, " {$n} {$unid}", 0);
                $p2 = strripos($name, " {$n}{$unid}", 0);


                if ($p1 == false && $p2 == false) {
                    unset($encontrados[$k]);
                    continue;
                }


                if ($data['mg'] > 0 && $data['ml'] > 0) {
                    $l1 = strripos($name, " {$data['ml']} ml", 0);
                    $l2 = strripos($name, " {$data['ml']}ml", 0);

                    if ($l1 == false && $l2 == false) {
                        unset($encontrados[$k]);
                        continue;
                    }

                } elseif ($data['ml'] > 0 && $data['perc'] > 0) {

                    $l1 = strripos($name, " {$data['ml']} ml", 0);
                    $l2 = strripos($name, " {$data['ml']}ml", 0);

                    $p1 = strripos($name, " {$data['perc']} %", 0);
                    $p2 = strripos($name, " {$data['perc']}%", 0);

                    if ($p1 == false && $p2 == false) {
                        unset($encontrados[$k]);
                        continue;
                    } else {
                        if ($l1 == false && $l2 == false) {
                            unset($encontrados[$k]);
                            continue;
                        }
                    }

                } else {
                    if ($p1 == false && $p2 == false) {
                        unset($encontrados[$k]);
                        continue;
                    }
                }

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
                $keys = $this->repairProd(explode(' ', $principio['descricao']));

                foreach ($keys as $key) {
                    if (strtoupper($key) == 'CLORIDRATO') {
                        continue;
                    } else {
                        $this->db->where("descricao like '%{$key}%'");
                    }
                }


            }
            $this->db->group_by('id_produto');

            $encontrados = $this->db->get('produtos_marca_sintese')->result_array();

        }

        return $encontrados;
    }

    private function buscaSintese($keys)
    {
        $encontrados = [];

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

                $key = strtolower($key);

                $chaves = $this->get_chaves();

                if (isset($chaves[$key])) {
                    $key2 = $chaves[$key];
                }

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
                } else {
                    if (isset($key2)) {
                        $res = strripos($term, strtolower($this->tirarAcentos($key2)), 0);
                        if ($res != false) {
                            $pts = $pts + 10;
                        }
                    }
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

    private function getG($prd)
    {
        $res = strripos(strtoupper($prd), ' G', 0);


        if ($res == false) {
            $res = strripos(strtoupper($prd), 'G', 0);

            if ($res == false) {
                return 0;
            }
        }

        return preg_replace("/[^0-9]/", "", substr($prd, ($res - 4), 5));

    }

    private function getGr($prd)
    {
        $res = strripos(strtoupper($prd), ' GR', 0);


        if ($res == false) {
            $res = strripos(strtoupper($prd), 'GR', 0);

            if ($res == false) {
                return 0;
            }
        }

        return preg_replace("/[^0-9]/", "", substr($prd, ($res - 4), 5));

    }

    private function getUI($prd)
    {
        $prd = str_replace(".", "", $prd);

        $res = strripos($prd, ' UI', 0);

        if ($res == false) {
            $res = strripos($prd, 'UI', 0);

        }

        $r = 0;

        if ($res == false) {
            $r = 0;
        } else {
            $r = preg_replace("/[^\d\.]/", "", substr($prd, ($res - 4), 5));
        }


        return $r;

    }

    private function getMCG($prd)
    {
        $res = strripos($prd, ' MCG', 0);

        if ($res == false) {
            $res = strripos($prd, 'MCG', 0);

        }

        $r = 0;

        if ($res == false) {
            $r = 0;
        } else {
            $r = preg_replace("/[^\d\.\,]/", "", substr($prd, ($res - 4), 5));
        }

        return $r;

    }

    private function getPerc($prd)
    {
        $res = strripos($prd, ' %', 0);

        if ($res == false) {
            $res = strripos($prd, '%', 0);

        }

        $r = 0;

        if ($res == false) {
            $r = 0;
        } else {
            $r = preg_replace("/[^\d\.\,]/", "", substr($prd, ($res - 4), 5));
        }

        return $r;

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
                case 'UN':
                case '(':
                case ')':
                case 'NAO':
                case 'NÃO':
                case 'INPM':
                case 'C/':
                case 'CX':
                case 'IV':
                case '(GEN)':
                case '1FA':
                case 'ACEITA':
                case 'EQUIVALENTE':
                case 'ALTERNATIVA':
                case 'ETILICO':
                case 'HIDRATADO':
                case 'GEN':
                    $valid = false;
                    break;
                default:
                    break;
            }

            $kk = count(str_split($key));


            if ($kk > 1) {
                if ($key[0] == '(' && $key[$kk - 1] == ")") {
                    $valid = false;
                }

            } else {
                $valid = false;
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
