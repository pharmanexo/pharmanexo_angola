<?php

class OncoprodPrecosNewHmg extends MY_Controller
{
    /**
     * @author : Chule Cabral
     * Data: 27/02/2021
     */

    public function __construct()
    {

        parent::__construct();

        $this->load->model('Engine');

        /**
         * Dados conexão FTP OncoProd
         */
        $this->configFTP['hostname'] = 'ftpedi.oncoprod.com.br';
        $this->configFTP['username'] = 'ftp.Phamanexo';
        $this->configFTP['password'] = 'Onco@ph4rm4';
        $this->configFTP['passive'] = FALSE;
        $this->configFTP['debug'] = TRUE;

        $this->load->library('ftp');
        $this->db = $this->load->database('teste_pharmanexo', true);
    }

    private function mountArrayPrice($data, $column, $variante, $id_fornecedor)
    {
        $id_estado = NULL;
        $icms = NULL;

        $formatVariante = explode('ICMS', $variante);

        /**
         * Verifica se a variante é ICMS ou ESTADO.
         *
         * Se não existir variante nenhuma, próxima column.
         */
        if (!isset($formatVariante[1])) {

            $formatVariante = explode('CLIENTES', $variante);

            if (!isset($formatVariante[1]))
                return ['status' => FALSE];
        }

        /**
         * Formata a variante, tirando os espaçamos e os caracteres especiais.
         */
        $formatVariante = trim(str_replace(",", ".", str_replace("%", "", $formatVariante[1])));

        /**
         * Se a Variante for númerica é ICMS, se não é UF.
         */
        if (is_numeric($formatVariante)) {

            $icms = $formatVariante;

        } else {

            $id_estado = $this->db->where('uf', $formatVariante)
                ->get('estados')
                ->row_array()['id'];
        }

        /**
         * Se icms e id_estado for NULL, próxima column.
         */
        if (IS_NULL($icms) && IS_NULL($id_estado))
            return ['status' => FALSE];

        $prices = [];

        foreach ($data as $item) {


            $price = trim(str_replace('R$', '', str_replace(',', '.', str_replace('.', '', $item[$column]))));

            $prices[] =
                [
                    'codigo' => intval($item[0]),
                    'id_fornecedor' => $id_fornecedor,
                    'id_estado' => $id_estado,
                    'icms' => $icms,
                    'preco_unitario' => number_format(floatval($price), 4, '.', '')
                ];

            if ($item[0] == '874') {
                $c = [
                    'codigo' => intval($item[0]),
                    'id_fornecedor' => $id_fornecedor,
                    'id_estado' => $id_estado,
                    'icms' => $icms,
                    'preco_unitario' => number_format(floatval($price), 4, '.', '')
                ];

            }
        }

        return
            [
                'status' => TRUE,
                'result' => $prices
            ];
    }

    private function process()
    {
        //$file = 'public/FTP/Oncoprod/precos_novos_oncoprod.csv';
        $file = 'precos_oncoprod.csv';

        $csv = fopen($file, 'r');


        $data = [];

        #ler o csv e coloca num array
        while (($line = fgetcsv($csv, NULL, ';')) !== FALSE) {


            $data[] = [
                0 => $line[0],
                1 => $line[1],
                2 => $line[2],
                3 => $line[3],
                4 => $line[4],
                5 => $line[5],
                6 => $line[6],
                7 => $line[7],
                8 => $line[8],
                9 => $line[9],
                10 => $line[10],
                11 => $line[11],
                12 => $line[12],
                13 => $line[13],
                14 => $line[14],
                15 => $line[15],
                16 => $line[16],
                17 => $line[17],
                18 => $line[18],
                19 => $line[19],
                20 => $line[20],
                21 => $line[21],
                22 => $line[22],
                23 => $line[23],
                24 => $line[24],


            ];

        }

        unset($data[0]); // Cabeçalho

        // $data => Dados Gerais, Variantes de Estados e ICMS

        // $data[1] => Apenas Variantes, Estados e ICMS

        $variantes = $data[1];


        unset($data[1]);

        /**
         *
         * REGRAS COLUMNS ONCOPROD (ATENÇÃO: A ORDEM NÃO PODE SER ALTERADA)
         *
         * 0 => Código do Produto.
         * 1, 2 => Descartadas.
         * 3 a 4 => ONCOPROD RS (12).
         * 5, 6 => ONCOPROD PE (123).
         * 7 a 14 => ONCOPROD SP (115).
         * 15 a 21 => ONCOPROD DF (126).
         * 22 a 24 => ONCOPROD ES (112).
         *
         */

        $prices = [];


        foreach ($variantes as $column => $variante) {

            // $column[0] => Código do Produto


            if ($column >= 3 && $column <= 4) {

                //ONCOPROD RS (12)

                $getPrice = $this->mountArrayPrice($data, $column, $variante, 12);

                if (!$getPrice['status'])
                    continue;

                $prices = array_merge($prices, $getPrice['result']);

            } else if ($column >= 5 && $column <= 6) {

                //ONCOPROD PE (123)

                $getPrice = $this->mountArrayPrice($data, $column, $variante, 123);

                if (!$getPrice['status'])
                    continue;

                $prices = array_merge($prices, $getPrice['result']);

            } else if ($column >= 7 && $column <= 14) {

                //ONCOPROD SP (115)

                $getPrice = $this->mountArrayPrice($data, $column, $variante, 115);

                if (!$getPrice['status'])
                    continue;

                $prices = array_merge($prices, $getPrice['result']);

            } else if ($column >= 15 && $column <= 21) {

                //ONCOPROD DF (126)

                $getPrice = $this->mountArrayPrice($data, $column, $variante, 126);

                if (!$getPrice['status'])
                    continue;

                $prices = array_merge($prices, $getPrice['result']);

            } else if ($column >= 22 && $column <= 24) {

                //ONCOPROD ES (112)

                $getPrice = $this->mountArrayPrice($data, $column, $variante, 112);

                if (!$getPrice['status'])
                    continue;

                $prices = array_merge($prices, $getPrice['result']);
            }

        }

        if (empty($prices))
            exit();

        // insere os preços
        foreach ($prices as $price) {

          /*  if ($this->Engine->checkPriceOncoprod($price) && !(floatval($price['preco_unitario']) == 0)) {
                $this->db->insert('produtos_preco_oncoprod', $price);
            }*/

            if (!(floatval($price['preco_unitario']) == 0.00)) {
                $this->db->insert('produtos_preco_oncoprod', $price);
            }
        }

        // chama a rotina que atualiza os produtos
        $this->updateProducts($data);

    }

    private function updateProducts($data)
    {
        $updateCatalogo = [];

        foreach ($data as $item) {
            $onco = [12, 111, 112, 115, 120, 123, 126];


            foreach ($onco as $id) {
                $d = [
                    'codigo' => $item[0],
                    'classe' => $item[1],
                    'origem' => $item[2]
                ];


                if (strpos($item[1], 'EXCETO EM SP') > 0) {
                    if ($id == 115) {
                        $d['classe'] = 'TRIBUTADO';
                    } else {
                        $d['classe'] = 'ISENTO';
                    }
                } else if (strpos($item[1], 'EXCETO EM DF E SP') > 0) {
                    if ($id == 115 || $id == 126) {
                        $d['classe'] = 'TRIBUTADO';
                    } else {
                        $d['classe'] = 'ISENTO';
                    }
                }

                $updateCatalogo[$id][] = $d;

            }

        }

        foreach ($updateCatalogo as $k => $produtos) {

            foreach ($produtos as $produto) {
                $id_fornecedor = $k;
                $codigo = $produto['codigo'];

                unset($produto['codigo']);

                $this->db->where('id_fornecedor', $id_fornecedor);
                $this->db->where('codigo', $codigo);
                $this->db->update('produtos_catalogo', $produto);
            }
        }
    }

    protected function index_get()
    {

        $this->process();
        exit();

        $folder = 'public/FTP/Oncoprod/';

        /**
         * Cria o diretório $folder caso ele não exista.
         */
        if (!is_dir($folder))
            mkdir($folder, 0777, true);

        $file = $folder . 'precos_novos_oncoprod.csv';

        /**
         * Conecta ao FTP.
         */
        $this->ftp->connect($this->configFTP);

        /**
         * Exibe a lista de arquivos na pasta do FTP.
         */
        $list = $this->ftp->list_files('/Phamanexo');

        /**
         * Faz Download do arquivo CSV.
         */
        $this->ftp->download($list[0], $file, 'ascii');

        $this->ftp->close();

        if (file_exists($file)) {
            $this->process();
        }
    }
}
