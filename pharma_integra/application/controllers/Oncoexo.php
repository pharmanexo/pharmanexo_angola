<?php

class Oncoexo extends MY_Controller
{
    /**
     * @author : Chule Cabral
     * Data: 24/09/2020
     */

    private $fornecedor = [1 => 25, 2 => 15];
    public $configFTP;

    private $arrayMovEstoque = [];
    private $arrayProdsCatalogo = [];
    private $arrayProdsLote = [];
    private $arrayIdsMarca = [];
    private $preco15 = [];
    private $preco25 = [];
    private $qtd_unidade = [];

    public function __construct()
    {
        parent::__construct();

        /**
         * Dados conexão FTP Oncoexo
         */

        $this->configFTP['hostname'] = 'ftp.oncoexo.com.br';
        $this->configFTP['username'] = 'oncoexo1';
        $this->configFTP['password'] = 'onco@2019@';
        $this->configFTP['debug'] = TRUE;

        $this->load->library('ftp');

        $this->load->model('Engine');
    }

    private function insertPrecoCSV($file, $data_csv)
    {

        /**
         * Varre o arquivo CSV e insere as informações na tabela provisória.
         * tabela: preco_oncoexo (Tabela provisória) Logs
         */

        $csv = fopen($file, 'r');

        $data = date('Y-m-d H:i:s', time());

        while (($line = fgetcsv($csv, NULL, ';')) !== false) {

            $oncoPrecos [] =
                [
                    "produto" => ($line[0]) ? $line[0] : NULL,
                    "codigo" => ($line[1]) ? intval($line[1]) : NULL,
                    "uf" => ($line[2]) ? $line[2] : NULL,
                    "preco" => $line[3],
                    "unidade" => ($line[4]) ? $line[4] : NULL,
                    "qtd_embalagem" => ($line[5]) ? $line[5] : NULL,
                    "data_criacao" => $data,
                    "data_csv" => $data_csv
                ];
        }

        unset($oncoPrecos[0]);

        fclose($csv);

        if (!empty($oncoPrecos))
            $this->db->insert_batch('preco_oncoexo', $oncoPrecos);
    }

    private function mountArraysProds($produtos)
    {

        /**
         * Varre o arquivo JSON e monta os Objetos dos produtos.
         * Movimentação de Estoque;
         * Catalogo;
         * Estoque;
         */

        foreach ($produtos as $produto) {

            $fornecedor = $this->fornecedor[intval($produto['CODFILIAL'])];
            $preco = str_replace('.', '', $produto['PRECO']);
            $preco = str_replace(',', '.', $preco);

            $this->arrayMovEstoque[] = [
                "id_fornecedor" => $fornecedor,
                "produto" => $produto['PRODUTO'],
                "nome_comercial" => $produto['NOME_COMERCIAL'],
                "codigo" => $produto['CODIGO'],
                "apresentacao" => $produto['APRESENTACAO'],
                "quantidade" => $produto['QUANTIDADE'],
                "unidade" => $produto['UNID'],
                "marca" => $produto['MARCA'],
                "rms" => (empty($produto['RMS'])) ? NULL : $produto['RMS'],
                "qtd_unidade" => $produto['QTD_MENOR'],
                "lote" => $produto['LOTE'],
                "validade" => dateFormat($produto["VALIDADE"], "Y-m-d"),
                "preco" => $preco,
                "estado" => $produto['ESTADO']
            ];

            $this->arrayProdsCatalogo[] = [
                "codigo" => intval($produto['CODIGO']),
                "rms" => (empty($produto['RMS'])) ? NULL : $produto['RMS'],
                "apresentacao" => $produto['APRESENTACAO'],
                "marca" => $produto['MARCA'],
                "descricao" => $produto['PRODUTO'],
                "quantidade_unidade" => 1,
                "unidade" => $produto['UNID'],
                "nome_comercial" => $produto['NOME_COMERCIAL'],
                "id_fornecedor" => $fornecedor,
                "ativo" => 1,
                "bloqueado" => 0
            ];

            $id_marca = $this->Engine->checkIdMarca(array("marca" => $produto['MARCA'], "id_fornecedor" => $fornecedor));

            if ($id_marca != 0) {

                $this->arrayIdsMarca[] = [
                    "codigo" => intval($produto['CODIGO']),
                    "id_marca" => $id_marca
                ];
            }

            $this->arrayProdsLote[] = [
                "lote" => $produto['LOTE'],
                "local" => NULL,
                "codigo" => intval($produto['CODIGO']),
                "id_fornecedor" => $fornecedor,
                "estoque" => intval($produto['QUANTIDADE']),
                "validade" => dateFormat($produto["VALIDADE"], "Y-m-d"),
            ];
        }
    }

    private function mountArraysCSV()
    {

        /**
         * Acessa a tabela de preços da Oncoexo, oriunda do arquivo CSV e monta os objetos.
         * Preços empresa 15;
         * Preços empresa 25;
         * Quantidade unidade.
         */

        $preco_oncoexo = $this->db->query("SELECT
       produto,
       codigo,
       uf,
       replace(replace(replace(preco, 'R$', ''), '.', ''), ',', '.') preco,
       unidade,
       qtd_embalagem,
       data_criacao,
       data_csv
FROM pharmanexo.preco_oncoexo p1

where p1.data_criacao = (SELECT max(p2.data_criacao) from pharmanexo.preco_oncoexo p2)")->result_Array();

        foreach ($preco_oncoexo as $x) {

            if ($x['uf'] == 'PE') {

                $id_estado = 17;

            } else if ($x['uf'] == 'PB') {

                $id_estado = 15;

            } else if ($x['uf'] == 'BR') {

                $id_estado = NULL;
            }

            $this->preco15[] = [
                "codigo" => intval($x['codigo']),
                "id_fornecedor" => 15,
                "id_estado" => $id_estado,
                "preco_unitario" => number_format(floatval(trim($x['preco'])), 4, '.', '')
            ];

            $this->preco25[] = [
                "codigo" => intval($x['codigo']),
                "id_fornecedor" => 25,
                "id_estado" => $id_estado,
                "preco_unitario" => number_format(floatval(trim($x['preco'])), 4, '.', '')
            ];

            $this->qtd_unidade[] = [
                "codigo" => intval($x['codigo']),
                "qtd_unidade" => $x['qtd_embalagem']

            ];
        }
    }

    protected function index_post()
    {
        /**
         * Verifica se o fornecedor faz parte da rotina de integração.
         * Inicia a contagem do tempo da Rotina.
         */
        if (!$this->Engine->startIn('BEGIN', $this->fornecedor, time()))
            exit();

        /**
         * Conecta ao FTP.
         */
        $this->ftp->connect($this->configFTP);

        /**
         * Exibe a lista de arquivos na pasta do FTP.
         */
        $list = $this->ftp->list_files('/SINTESE');

        $nameFile = NULL;
        $data_csv = NULL;


        /**
         * Procura nos arquivos o primeiro arquivo com a extensão .CSV.
         */
        foreach ($list as $csv) {

            if (like('%.csv', $csv)) {
                $nameFile = $csv;

            } else {

                continue;
            }

            /**
             * O Arquivo CVS na pasta é salvo com uma data e um nome qualquer.
             * EX: 10082020_nome_arquivo.CSV.
             * Como trabalhamos com Data o tratamento de string a baixo pega essa data e armazena na variável.
             */
            if (!IS_NULL($nameFile))
                $data_csv = substr($nameFile, 0, 8);
        }


        $folder = 'public/FTP/Oncoexo/';

        /**
         * Cria o diretório $folder caso ele não exista.
         */
        if (!is_dir($folder))
            mkdir($folder, 0777, true);

        /**
         * Cria o path file name conforme o arquivo .csv localizado.
         */
        $file = $folder . 'Oncoexo-precos.csv';

        /**
         * Se esse arquivo já existir na servidor local ele é deletado.
         */
        if (file_exists($file))
            unlink($file);

        /**
         * Vericamos no Banco conforme a data do Arquivo, se o mesmo já foi inserido.
         * Se a data ainda não foi inserida, efetuamos o Download do arquivo CSV.
         */

        if (!IS_NULL($nameFile)) {

            $preco_oncoexo = $this->db->where('data_csv', $data_csv)
                ->get('preco_oncoexo')
                ->row_array();

            if (IS_NULL($preco_oncoexo)){
                $this->ftp->download('/SINTESE/' . $nameFile, $file, 'ascii');
            }
        }

        $this->ftp->close();

        /**
         * chama da Função para manipular os dados do CSV.
         */
        if (file_exists($file))
            $this->insertPrecoCSV($file, $data_csv);


        $post = file_get_contents("php://input");

        $produtos = json_decode($post, true);

        /**
         * Chama a Função para criar os arrays com os dados dos produtos.
         */
        $this->mountArraysProds($produtos);
        $this->mountArraysCSV();

        $arrayMovEstoque = $this->arrayMovEstoque;
        $prodsLote = multi_unique($this->arrayProdsLote);
        $prodsCatalogo = multi_unique($this->arrayProdsCatalogo);
        $arrayIdsMarca = multi_unique($this->arrayIdsMarca);

        //  $this->db->trans_start();

        /**
         * Insere todos os dados dos produtos na tabela de log.
         * Movimentação de Estoque
         */
        $this->db->insert_batch('movimentacao_estoque', $arrayMovEstoque);

        /**
         * Limpa toda a tabela de estoque do fornecedor.
         */
        $this->Engine->cleanStockIn($this->fornecedor);

        /**
         * Para cada lote, verifica se é maior que zero.
         * Para cada lote, verifica se já foi inserido.
         * Insere o lote no Banco de Dados.
         */
        foreach ($prodsLote as $prodLot) {

            if ($this->Engine->checkLot($prodLot) && $prodLot['estoque'] > 0)
                $this->db->insert('produtos_lote', $prodLot);
        }

        /**
         * Para cada produto, verifica se já está cadastrado no Catálogo.
         * Insere o produto no Banco de Dados se não estiver no catálogo.
         * Se o produto já for cadastrado, efetua um update no mesmo com a função activeCatalog.
         */
        foreach ($prodsCatalogo as $prodCat) {

            if ($this->Engine->checkCatalog($prodCat)) {

                $this->db->insert('produtos_catalogo', $prodCat);

            } else {
                $this->Engine->activeCatalog($prodCat);
            }
        }


        $preco15 = multi_unique($this->preco15);
        $preco25 = multi_unique($this->preco25);
        $qtd_unidade = multi_unique($this->qtd_unidade);

        /**
         * Para cada preco da empresa 15, verifica se já existe no Banco de dados e se não é zerado.
         * Insere o preço na tabela de produtos preço.
         */
        foreach ($preco15 as $prec15) {

            if ($this->Engine->checkPrice($prec15) && !(floatval($prec15['preco_unitario']) == 0)) {
                $this->db->insert('produtos_preco', $prec15);
            }
        }

        /**
         * Para cada preco da empresa 25, verifica se já existe no Banco de dados e se não é zerado.
         * Insere o preço na tabela de produtos preço.
         */
        foreach ($preco25 as $prec25) {

            if ($this->Engine->checkPrice($prec25) && !(floatval($prec25['preco_unitario']) == 0)) {
                $this->db->insert('produtos_preco', $prec25);
            }
        }

        /**
         * Os informações da Quantidade Unidade da Oncoexo vem no arquivo CSV.
         * Update das informações de quantidade no catalogo.
         */
        foreach ($qtd_unidade as $qtd) {

            $this->db->where_in('id_fornecedor', $this->fornecedor)
                ->where('codigo', $qtd['codigo'])
                ->set('quantidade_unidade', $qtd['qtd_unidade'])
                ->update('produtos_catalogo');
        }

        // $this->db->trans_complete();

        // if ($this->db->trans_status() === FALSE) {

        //ERROR - LOG

        //  } else {

        /**
         * Finaliza a contagem do tempo da Rotina.
         */
        $this->Engine->startIn('END', $this->fornecedor, time());
        // }
    }
}