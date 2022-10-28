<?php

set_time_limit(0);
ini_set("memory_limit", -1);
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);

class MatchDeParaClientesHMG extends CI_Controller
{
    private $bio;
    private $hmg;
    private $unidades;

    public function __construct()
    {
        parent::__construct();

        $this->bio = $this->load->database('bionexo', true);
        $this->hmg = $this->load->database('teste_pharmanexo', true);

        $this->unidades = [
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
        ];

    }

    private function get_chaves()
    {
        $chaves = [
            'solucao' => 'solução',
            'glicosado' => 'glicose',
            'cap' => 'capsula',
            'comp' => 'comprimido',
            'bolsa' => 'fechado'
        ];

        return $chaves;
    }


    public function index()
    {

        $encontrados = $this->db
            ->select('*')
            ->from('teste')
            ->where('visto', 0)
            // ->where('codigo_catalogo', '03192')
            //->where('id', 201172)
            ->limit(100000)
            ->get()
            ->result_array();

        $result = [];


        if (!empty($encontrados)) {

            foreach ($encontrados as $jj => $encontrado) {

                $this->db->where('id', $encontrado['id'])->update('teste', ['visto' => 1]);


                $checkProd = $this->bio->where('codigo', $encontrado['codigo_catalogo'])->where('id_cliente', $encontrado['id_cliente'])->get('catalogo')->row_array();
                if (!empty($checkProd) && $checkProd['id_categoria'] != 100) {
                    continue;
                }

                $keys = array_unique(explode(' ', $encontrado['descricao_catalogo']));


                $principios = (!empty($encontrado['principios']) ? explode('|', $encontrado['principios']) : []);


                $mg = $this->getMg($encontrado['descricao_catalogo']);
                $ml = $this->getMl($encontrado['descricao_catalogo']);
                $ui = $this->getUI($encontrado['descricao_catalogo']);
                $mcg = $this->getMCG($encontrado['descricao_catalogo']);
                $perc = $this->getPerc($encontrado['descricao_catalogo']);


                $keys = $this->repairProd($keys);

                if (empty($principios)) {

                    $term = str_replace(" ", "", strtolower($this->tirarAcentos($encontrado['descricao_sintese'])));
                    $pts = 0;

                    foreach ($keys as $key) {

                        if (strripos(strtolower($key), "c/", 0) !== false) {
                            $key = trim(str_replace("c/", " ", strtolower($key)));
                        }

                        $key = strtolower(str_replace(" ", "", $key));

                        $chaves = $this->get_chaves();

                        if (isset($chaves[$key])) {
                            $key2 = $chaves[$key];
                        }

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


                    if ($p < 80) {

                        unset($encontrados[$jj]);
                        continue;
                    }

                    foreach ($keys as $key) {
                        $key = strtolower($key);
                        if (isset($this->unidades[$key])) {
                            $und = strtolower($this->unidades[$key]);
                            $t1 = strripos(strtolower($term), $key, 0);
                            $t2 = strripos(strtolower($term), $und, 0);

                            if ($t1 == false && $t2 == false) {

                                unset($encontrados[$jj]);

                                continue 2;
                            }

                        }

                    }


                } else {
                    foreach ($principios as $principio) {
                        $pts = 0;
                        $kkk = explode(' ', $principio);
                        $desc = $this->tirarAcentos(strtolower(str_replace(" ", "", $encontrado['descricao_sintese'])));
                        $kk = $this->repairProd($kkk);

                        foreach ($kk as $item) {

                            if (strripos(strtolower($item), "mg/ml", 0) !== false) {
                                $item = intval($item) . "mg";
                            }

                            if (strripos(strtolower($item), "c/", 0) !== false) {

                                $item = trim(str_replace("c/", " ", strtolower($item)));

                            }

                            if (strripos($desc, strtolower($item), 0) == FALSE) {
                                $pts = $pts + 10;
                            }

                        }

                        $base = count($kkk) * 10;
                        $p = ($pts / $base) * 100;

                        if ($p < 70) {

                            unset($encontrados[$jj]);
                            continue 2;
                        }

                        $term = str_replace(" ", "", strtolower($this->tirarAcentos($encontrado['descricao_sintese'])));

                        foreach ($keys as $key) {
                            $key = strtolower($key);
                            if (isset($this->unidades[$key])) {
                                $und = strtolower($this->unidades[$key]);
                                $t1 = strripos(strtolower($term), $key, 0);
                                $t2 = strripos(strtolower($term), $und, 0);

                                if ($t1 == false && $t2 == false) {

                                    unset($encontrados[$jj]);
                                    continue 3;
                                }

                            }

                        }

                    }
                }


                $filter = [
                    'mg' => str_replace(",", '.', $mg),
                    'ml' => str_replace(",", '.', $ml),
                    'ui' => str_replace(",", '.', $ui),
                    'mcg' => str_replace(",", '.', $mcg),
                    'perc' => str_replace(",", '.', $perc),
                    'encontrado' => $encontrado
                ];

                $test = $this->process($filter);


                if (!$test) {
                    $result['false'][] = $encontrado;

                    // atualiza o produto como visitado
                    $this->db->where('id', $encontrado['id']);
                    $this->db->update('teste', ['associado' => 0]);
                } else {
                    $insert = [
                        'id_sintese' => $encontrado['id_sintese'],
                        'id_produto' => $encontrado['id_produto'],
                        'id_pfv' => $encontrado['codigo_catalogo'],
                        'id_usuario' => 9999,
                        'cd_produto' => $encontrado['codigo_catalogo'],
                        'id_catalogo' => $encontrado['id'],
                        'id_cliente' => $encontrado['id_cliente'],
                        'integrador' => 2,
                    ];

                    $this->db->insert('produtos_pre_depara', $insert);

                    $this->db->where('id', $encontrado['id']);
                    $this->db->update('teste', ['associado' => 1]);

                }


            }
        }


    }

    public function getProdutosClientes()
    {
        //$id_cliente = 14861;
        $produtos = $this->bio
            ->select('ct.codigo, ct.descricao, ct.id_cliente')
            ->from('pharmanexo.vw_produtos_clientes_sem_depara ct')
            ->join('pharmanexo.compradores c', ' c.id = ct.id_cliente')
            ->where('c.estado', 'ES')
            // ->where('ct.id_categoria', 100)
            // ->where('ct.codigo', 259399)
            // ->where('c.id', $id_cliente)
            ->where('ct.process', 0)
            ->where('ct.ocultar', 0)
            ->limit(2000)
            ->get()->result_array();


        $insert = [];
        $naoAchou = [];
        $cods = [];
        foreach ($produtos as $k => $produto) {


            unset($t);
            $principios = [];
            $id_cliente = $produto['id_cliente'];
            $pImplode = [];

            $keys = $this->repairProd(explode(' ', $produto['descricao']));

            if (isset($keys[0])) {
                // CONSULTA ANVISA
                $principios = $this->db
                    ->distinct()
                    ->select('pc.produto, pcp.descricao, nome')
                    ->from('produtos_cmed pc')
                    ->where('nome', str_replace("-", " ", $keys[0]))
                    ->or_where('produto', str_replace("-", " ", $keys[0]))
                    ->join('produtos_cmed_principios pcp', 'pc.id = pcp.id_produto')
                    ->get()
                    ->result_array();
            }


            $pts = 0;

            if (!empty($principios)) {
                foreach ($principios as $k => $principio) {

                    $pts = 0;
                    $kkk = explode(' ', $principio['produto']);

                    $kk = $this->repairProd($kkk);

                    foreach ($kk as $item) {
                        $pos = strripos($produto['descricao'], $item, 0);

                        if ($pos !== FALSE) {
                            $pts = $pts + 10;
                        }
                    }

                    if ($pts < (count($kk) * 10)) {
                        unset($principios[$k]);
                    }

                }
            }


            if (isset($principios) && !empty($principios)) {

                $p = array_values($this->repairProd(explode(' ', $principios[0]['descricao'])));

                $s = $p[0] . '%';

                $s2 = isset($p[1]) ? $p[1] : '';


                foreach ($principios as $principio) {
                    $pImplode[] = $principio['descricao'];
                }

            } else {
                if (isset($keys) && !empty($keys)) {
                    $s = (isset($keys[0])) ? $keys[0] : '';

                    if (isset($keys[1])) {
                        $s2 = $keys[1];
                    } else if (!isset($keys[1]) and isset($keys[3])) {
                        $s2 = $keys[3];
                    } else {
                        $s2 = '';
                    }

                } else {
                    $s = '';
                    $s2 = '';
                }
            }


            $encontrados = $this->db
                ->where("descricao like '{$keys[0]}%'")
                ->get('produtos_marca_sintese')
                ->result_array();


            if (empty($encontrados)) {

                $this->db->where("descricao like '{$s}%'");
                $this->db->where("descricao like '%{$s}%'");
                $encontrados = $this->db->get('produtos_marca_sintese')->result_array();
            }


            /*$query = "WITH cte1 AS (SELECT DISTINCT ms.id_produto       as ID_PRODUTO,
                                                     ms.id_sintese       as ID_SINTESE,
                                                      UPPER(ms.descricao) as DESCRICAO_SINTESE,
                                                      c2.PRIMEIRO_NOME    as PALAVRA_CHAVE,
                                                      c.codigo            as CODIGO_CATALOGO,
                                                      c.descricao         as DESCRICAO_CATALOGO
                                      from pharmanexo.produtos_marca_sintese ms,
                                           (SELECT c.codigo, descricao
                                            FROM cotacoes_bionexo.catalogo c
                                            WHERE id_cliente = {$id_cliente} and c.codigo = '{$produto['codigo']}') c,
                                           (SELECT UPPER(SUBSTRING_INDEX(descricao, ' ', 1)) as PRIMEIRO_NOME
                                            FROM cotacoes_bionexo.catalogo c
                                            WHERE id_cliente = {$id_cliente}
                                              and c.codigo = '{$produto['codigo']}') c2
                                      order by id_produto)
                        SELECT *
                        FROM cte1
                        WHERE cte1.DESCRICAO_SINTESE like CONCAT((SELECT UPPER(SUBSTRING_INDEX(descricao, ' ', 1)) as PRIMEIRO_NOME
                                                                  FROM cotacoes_bionexo.catalogo c
                                                                  WHERE id_cliente = {$id_cliente}
                                                                    and c.codigo = '{$produto['codigo']}'), '%') OR (cte1.DESCRICAO_SINTESE like '{$s}' and cte1.DESCRICAO_SINTESE like '{$s2}')
                                                                    group by cte1.id_produto
                    ";*/


            /*try {
                $t = $this->bio->query($query)->result_array();

            } catch (Exception $e) {
                echo $query;
            }*/

            $this->bio->reset_query();
            $this->bio->flush_cache();


            if (empty($encontrados)) {
                $naoAchou[] = $produto['codigo'];
            } else {


                foreach ($encontrados as $item) {

                    $tituloKeys = $this->repairProd(explode(" ", $produto['descricao']));
                    foreach ($tituloKeys as $x => $tituloKey) {
                        $tituloKeys[$x] = strtoupper($tituloKey);
                    }
                    $tituloKeys = array_unique($tituloKeys);
                    $descricao = implode(" ", $tituloKeys);

                    $insert = [
                        'id_produto' => $item['id_produto'],
                        'id_sintese' => $item['id_sintese'],
                        'descricao_sintese' => strtoupper($this->tirarAcentos($item['descricao'])),
                        'palavra_chave' => strtoupper($keys[0]),
                        'codigo_catalogo' => $produto['codigo'],
                        'descricao_catalogo' => strtoupper($this->tirarAcentos($descricao)),
                        'id_cliente' => $id_cliente,
                        'principios' => implode('|', $principios)
                    ];

                    $in = $this->db->insert('teste', $insert);
                    if ($in) {
                        // seta como ocultar caso não encontre nenhum depara
                        $this->bio->where('codigo', $item['CODIGO_CATALOGO']);
                        $this->bio->where('id_cliente', $id_cliente);
                        $this->bio->update('catalogo', ['process' => 1]);
                    }
                }
            }


        }


        foreach ($naoAchou as $value) {

            // seta como ocultar caso não encontre nenhum depara
            $this->bio->where('codigo', $value);
            $this->bio->where('id_cliente', $id_cliente);
            $this->bio->update('catalogo', ['ocultar' => 1]);

        }


    }

    private function process($data)
    {

        $encontrado = $data['encontrado'];


        if ($data['mg'] == 0 and $data['ml'] == 0 and $data['mcg'] == 0 and $data['ui'] == 0 && $data['perc']) {
            return false;
        }


        $name = $encontrado['descricao_sintese'];
        $unid = "";
        $n = 0;

        if ($data['mg'] > 0) {
            $unid = "mg";
            $n = $data['mg'];
        } else if ($data['ml'] > 0) {
            $unid = "ml";
            $n = $data['ml'];
        } else if ($data['mcg'] > 0) {
            $unid = "mcg";
            $n = $data['mcg'];
        } else if ($data['perc'] > 0) {
            $unid = "%";
            $n = $data['perc'];
        } else {
            $unid = "";
        }

        if (!empty($unid)) {

            $p1 = strripos($name, " {$n} {$unid}", 0);
            $p2 = strripos($name, " {$n}{$unid}", 0);


            if ($p1 == false && $p2 == false) {
                return false;
            }


            if ($data['mg'] > 0 && $data['ml'] > 0) {
                $l1 = strripos($name, " {$data['ml']} ml", 0);
                $l2 = strripos($name, " {$data['ml']}ml", 0);

                if ($l1 == false && $l2 == false) {
                    return false;
                }

            } elseif ($data['ml'] > 0 && $data['perc'] > 0) {

                $l1 = strripos($name, " {$data['ml']} ml", 0);
                $l2 = strripos($name, " {$data['ml']}ml", 0);

                $p1 = strripos($name, " {$data['perc']} %", 0);
                $p2 = strripos($name, " {$data['perc']}%", 0);

                if ($p1 == false && $p2 == false) {
                    return false;
                } else {
                    if ($l1 == false && $l2 == false) {
                        return false;
                    }
                }

            } else {
                if ($p1 == false && $p2 == false) {
                    return false;
                }
            }

        }

        return true;
    }

    public function deleteVistos()
    {
        $vistos = $this->db->select('id')->where('visto', 1)->limit(500000)->get('teste')->result_array();


        $this->db->trans_start();

        foreach ($vistos as $visto) {

            $this->db->where('id', $visto['id']);
            $this->db->delete('teste');

        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
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

    private function saveDePara($result)
    {
        $produtos = (!empty($result) ? $result : []);


        foreach ($produtos as $k => $produto) {

            if (empty($produtos)) {
                continue;
            }

            $insert = [
                'id_sintese' => $produto['id_sintese'],
                'id_pfv' => $produto['codigo_catalogo'],
                'id_usuario' => 9999,
                'cd_produto' => $produto['codigo_catalogo'],
            ];

            $this->db->insert('produtos_pre_depara', $insert);

        }


    }

    private function getMl($prd)
    {
        $res = strripos($prd, ' ML', 0);

        if ($res == false) {
            $res = strripos($prd, 'ML', 0);

        }

        $r = 0;

        if ($res == false) {
            $r = 0;
        } else {
            $r = preg_replace("/[^\d\.]/", "", substr($prd, ($res - 4), 5));
        }

        return $r;

    }

    private function getUI($prd)
    {
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

    private function getMg($prd)
    {
        $res = strripos($prd, ' MG', 0);

        if ($res == false) {
            $res = strripos($prd, 'MG', 0);
        }

        if ($res) {
            $r = preg_replace("/[^\d\.]/", "", substr($prd, ($res - 4), 5));
        } else {
            return 0;
        }


        return $r;

    }

    private function repairProd($keys)
    {
        foreach ($keys as $p => $key) {
            switch (strtoupper($key)) {

                case '-':
                case 'DE':
                case '|':
                case '':
                case 'REF.':
                case 'REF':
                case 'NAO':
                case 'NÃO':
                case 'ACEITA':
                case 'ALTERNATIVA':
                    unset($keys[$p]);
                    break;
                default:
                    break;
            }
        }

        return $keys;
    }

    function tirarAcentos($string)
    {
        return preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), $string);
    }
}
