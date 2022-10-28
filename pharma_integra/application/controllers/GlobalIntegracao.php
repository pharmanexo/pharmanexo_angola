<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

class GlobalIntegracao extends CI_Controller
{
    private $id_fornecedor;

    public function __construct()
    {
        parent::__construct();

        // $this->db = $this->load->database('teste_pharmanexo', true);

        $this->configFTP['hostname'] = 'srv42444.oracledba.com.br';
        $this->configFTP['username'] = 'alldita';
        $this->configFTP['password'] = 'aLt#503!';
        $this->configFTP['port'] = 15521;
        $this->configFTP['passive'] = TRUE;
        $this->configFTP['debug'] = TRUE;

        $this->load->library('ftp');

        $this->id_fornecedor = 5038;
    }

    public function importClientes()
    {
        $strArquivoRemoto = "ftp://srv42444.oracledba.com.br:15521/winthor-alldita/CLIENTES.TXT";
        $strArquivoLocal = "Global/CLIENTES.txt";

        $download = $this->conectFtp($strArquivoLocal, $strArquivoRemoto);
        if (!$download) {
            exit();
        }

        $files = utf8_encode(file_get_contents($strArquivoLocal));
        $lines = explode("\n", $files);
        $insert = [];

        foreach ($lines as $line) {

            $op = substr($line, 0, 3);

            if ($op == '001') {
                $arr = [
                    'codfilial' => $this->removeZeroEsquerda(trim(substr($line, 3, 4))),
                    'numregiao' => $this->removeZeroEsquerda(trim(substr($line, 7, 4))),
                    'codcliente' => $this->removeZeroEsquerda(trim(substr($line, 11, 9))),
                    'cnpj' => mask(trim(substr($line, 20, 14)), '##.###.###/####-##'),
                    'col' => 1 //usar sempre 1 (Mateus que falou),
                ];

                $cliente = $this->db->where('cnpj', $arr['cnpj'])->get('compradores')->row_array();

                if (!empty($cliente)) {
                    $arr['id_cliente'] = $cliente['id'];
                    $arr['id_fornecedor'] = $this->id_fornecedor;

                    $existe = $this->db
                        ->where('codfilial', $arr['codfilial'])
                        ->where('codcliente', $arr['codcliente'])
                        ->where('numregiao', $arr['numregiao'])
                        ->where('id_fornecedor', $arr['id_fornecedor'])
                        ->get('compradores_regiao')
                        ->row_array();

                    if (!empty($existe)) {

                        if ($arr['col'] != $existe['col']) {

                            $this->db
                                ->where('codfilial', $arr['codfilial'])
                                ->where('codcliente', $arr['codcliente'])
                                ->where('numregiao', $arr['numregiao'])
                                ->where('id_fornecedor', $arr['id_fornecedor'])
                                ->update('compradores_regiao', $arr);

                        }

                    } else {
                        $insert[] = $arr;
                    }

                }

            }
        }

        if (!empty($insert)) {
            $this->db->insert_batch('compradores_regiao', $insert);
        }

    }

    public function importPrecos()
    {

        $strArquivoRemoto = "ftp://srv42444.oracledba.com.br:15521/winthor-alldita/EDIPRECOS.TXT";
        $strArquivoLocal = "Global/EDIPRECOS.txt";

        $download = $this->conectFtp($strArquivoLocal, $strArquivoRemoto);

        if (!$download) {
            exit();
        }

        /*  $files = utf8_encode(file_get_contents($strArquivoLocal));
          $lines = explode("\n", $files);
          unset($files);*/

        $handle = fopen($strArquivoLocal, "r");
        if ($handle) {

            $this->db->where('cod_produto > 0')->delete('produtos_global');

            $produtos = [];
            $cabecalho = [];

            while (($line = fgets($handle)) !== false) {
                $op = substr($line, 0, 3);

                if ($op == '002') {
                    $produto = [
                        'ean' => substr($line, 3, 13),
                        'codforn' => intval(substr($line, 16, 10)),
                        'estoque' => intval(substr($line, 26, 10)),
                        'unidade_venda' => substr($line, 36, 2),
                        'cod_produto' => intval(substr($line, 38, 6)),
                        'desc_produto' => substr($line, 44, 40),
                        'qtd_embalagem' => intval(substr($line, 84, 10)),
                        'embalagem' => trim(substr($line, 94, 12)),
                        'multiplo' => substr($line, 106, 10),
                        'validade' => substr($line, 116, 8),
                        'preco_1' => $this->parseToMoney(substr($line, 139, 15)),
                        'preco_2' => $this->parseToMoney(substr($line, 154, 15)),
                        'preco_3' => $this->parseToMoney(substr($line, 169, 15)),
                        /*  'preco_1' => (substr($line, 139, 15)),
                          'preco_2' => (substr($line, 154, 15)),
                          'preco_3' => (substr($line, 169, 15)),*/
                        'regiao' => $cabecalho['num_regiao'],
                        'uf' => $cabecalho['uf_regiao']
                    ];

                    $this->db->insert('produtos_global', $produto);

                } else {
                    $cabecalho = [
                        'data' => substr($line, 3, 8),
                        'hora' => substr($line, 11, 6),
                        'num_regiao' => $this->removeZeroEsquerda(substr($line, 17, 4)),
                        'uf_regiao' => $this->removeZeroEsquerda(substr($line, 21, 2))
                    ];

                }

            }

            fclose($handle);
        } else {
            echo 'erro file';
        }

        $this->processa();

    }

    private function processa()
    {
        /*$pages = $this->db->get('produtos_global')->num_rows();
        var_dump($pages);
        exit();*/


        $produtos = $this->db->get('produtos_global')->result_array();

        $arrayCatalogo = [];
        $arrayLotes = [];
        $arrayPrecos = [];


        foreach ($produtos as $produto) {
            $arrayCatalogo[] = [
                'codigo' => $produto['cod_produto'],
                'ean' => $produto['ean'],
                'nome_comercial' => trim($produto['desc_produto']),
                'apresentacao' => trim($produto['embalagem']),
                'quantidade_embalagem' => $produto['qtd_embalagem'],
                'unidade' => $produto['unidade_venda'],
                'estoque' => $produto['estoque'],
                'validade' =>  $this->mask($produto['validade'], '##/##/####'),
                'preco_1' => $produto['preco_1'],
                'preco_2' => $produto['preco_2'],
                'preco_3' => $produto['preco_3'],
                'uf' => $produto['uf'],
                'regiao' => $produto['regiao']
            ];
        }


        $arrayCatalogoAgrupado = [];
        foreach ($arrayCatalogo as $k => $catalogo) {
            $arrayCatalogoAgrupado[$catalogo['codigo']][] = $catalogo;
        }

        unset($arrayCatalogo);

        $arrayCatalogoAux = [];

        foreach ($arrayCatalogoAgrupado as $items) {
            if (count($items) > 1) {
                foreach ($items as $item) {
                    if ($item['unidade'] == 'CX') {
                        $arrayCatalogoAux[] = $item;
                    }
                }
            } else {
                $arrayCatalogoAux[] = $items[0];
            }
        }

        unset($arrayCatalogoAgrupado);

        foreach ($arrayCatalogoAux as $item) {

            $qtd = (intval($item['quantidade_embalagem']) > 0) ? intval($item['quantidade_embalagem']) : 1;

            $arrayPrecos[] = [
                'codigo' => $item['codigo'],
                'preco_1' => ($item['preco_1'] / $qtd),
                'preco_2' => ($item['preco_2'] / $qtd),
                'preco_3' => ($item['preco_3'] / $qtd),
                'uf' => $item['uf'],
                'regiao' => $item['regiao'],
                'id_fornecedor' => 5038
            ];
        }

        foreach ($arrayCatalogoAux as $item) {


            if ($item['estoque'] > 0) {
                $arrayLotes[] = [
                    'codigo' => $item['codigo'],
                    'lote' => 'XPXZ',
                    'estoque' => $item['estoque'],
                    'validade' => dbDateFormat($item['validade']),
                    'id_fornecedor' => 5038
                ];

            }
        }

        $arrayCatalogo = [];

        foreach ($arrayCatalogoAux as $k => $prod) {
            $produtosCatalogo = $this->db
                ->where('id_fornecedor', $this->id_fornecedor)
                ->where('codigo', $prod['codigo'])
                ->get('produtos_catalogo');

            if ($produtosCatalogo->num_rows() == 0) {
                $prod['id_fornecedor'] = $this->id_fornecedor;
                $prod['ativo'] = 1;

                $arrayCatalogo[] = [
                    'codigo' => $prod['codigo'],
                    'ean' => $prod['ean'],
                    'quantidade_unidade' => $prod['quantidade_embalagem'],
                    'ativo' => $prod['ativo'],
                    'id_fornecedor' => $prod['id_fornecedor'],
                    'nome_comercial' => $prod['nome_comercial'],
                    'apresentacao' => $prod['apresentacao'],
                    'bloqueado' => 0
                ];

            }

        }


        if (!empty($arrayCatalogo)) {
            $this->db->insert_batch('produtos_catalogo', $arrayCatalogo);
        }

        if (!empty($arrayPrecos)) {
            $this->db
                ->where('id_fornecedor', $this->id_fornecedor)
                ->delete('produtos_precos_regiao');

            $this->db->insert_batch('produtos_precos_regiao', $arrayPrecos);
        }

        if (!empty($arrayLotes)) {
            $arrayLotes = multi_unique($arrayLotes);

            $this->db
                ->where('id_fornecedor', $this->id_fornecedor)
                ->delete('produtos_lote');

            $this->db->insert_batch('produtos_lote', $arrayLotes);
        }

    }

    function array_unique_multidimensional($input)
    {
        $serialized = array_map('serialize', $input);
        $unique = array_unique($serialized);
        return array_intersect_key($input, $unique);
    }

    // funções auxiliares

    private function conectFtp($strArquivoLocal, $strArquivoRemoto)
    {
        $fo = fopen($strArquivoLocal, 'w+');
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $strArquivoRemoto); #input
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FILE, $fo); #output
        curl_setopt($curl, CURLOPT_USERPWD, "alldita:aLt#503!");
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
        if (file_exists($strArquivoLocal)) {
            curl_setopt($curl, CURLOPT_RESUME_FROM, filesize($strArquivoLocal));
        }
        curl_exec($curl);
        fclose($fo);
        $err = curl_errno($curl);



        curl_close($curl);


        if ($err) {
            return false;
        } else {
            return true;
        }

    }

    private function parseToMoney($num)
    {
        $value = ltrim($num, "0");
        $init = (strlen($value) - 6);
        $fracao = substr($value, $init, 6);
        $fracao = (empty($fracao)) ? '0000' : $fracao;

        $num = substr($value, 0, $init);
        $num = (empty($num)) ? '0' : $num;
        $money = "{$num}.{$fracao}";

        return $money;

    }

    private function removeZeroEsquerda($num)
    {
        return ltrim($num, "0");
    }

    private function mask($val, $mask)
    {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k])) $maskared .= $val[$k++];
            } else {
                if (isset($mask[$i])) $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }


}