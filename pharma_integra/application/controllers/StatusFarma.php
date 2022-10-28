<?php


class StatusFarma extends CI_Controller
{

    private $token;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('m_StatusFarma', 'st');

        /**
         * Dados conexão FTP OncoProd
         */
        $this->configFTP['hostname'] = '208.115.238.110';
        $this->configFTP['username'] = 'stafarma';
        $this->configFTP['password'] = 'sf010203';
        $this->configFTP['passive'] = FALSE;
        $this->configFTP['debug'] = TRUE;

        $this->load->library('ftp');

    }

    private function prepare($file)
    {
        $lotes = [];
        $precos = [];
        $produtos = [];

        // prepara os arrays de dados
        foreach ($file['item'] as $item) {
            $produtos[$item['Codigo']] = [
                'nome_comercial' => empty($item['Apresentacao']) ? '' : $item['Apresentacao'],
                'apresentacao' => empty($item['Apresentacao']) ? '' : $item['Apresentacao'],
                'codigo' => empty($item['Codigo']) ? '' : $item['Codigo'],
                'unidade' => empty($item['Unidade']) ? '' : $item['Unidade'],
                'marca' => empty($item['Marca']) ? '' : $item['Marca'],
                'rms' => empty($item['Rms']) ? '' : $item['Rms'],
                'quantidade_unidade' => 1,
                'id_fornecedor' => 5031,
                'ativo' => 1
            ];

            $lotes[] = [
                'codigo' => $item['Codigo'],
                'lote' => $item['Lote'],
                'estoque' => $item['Quantidade'],
                'validade' => $item['Validade'],
                'id_fornecedor' => 5031,
            ];

            $precos[$item['Codigo']] = [
                'codigo' => $item['Codigo'],
                'preco_unitario' => $item['Preco'],
                'id_fornecedor' => 5031,
            ];
        }

        //insere produtos no catalogo
       foreach ($produtos as $produto) {
           //verifica se o produto ja existe
            $prod = $this->st->getProduto($produto);

            //insere se nao existir
            if (empty($prod)) {
                $this->st->insertProduto($produto);
            }
        }


        if (!empty($lotes)) {
            // limpa todos os lotes
            if ($this->st->resetLotes()) {
                //insere os lotes
                $this->st->insertLotes($lotes);
            }

        }

        //prepare preços
        $precoNovo = [];
        foreach ($precos as $preco)
        {
            // verifica se o preço ja existe e se é igual ao ultimo recebido
            $price = $this->st->checkPrice($preco);

            if ($price){
                $precoNovo[] = $preco;
            }

        }

        if (!empty($precoNovo)) {
            $this->st->insertPrecos($precoNovo);
        }

    }

    public function import()
    {

        $folder = 'public/FTP/StatusFarma/';

        /**
         * Cria o diretório $folder caso ele não exista.
         */
        if (!is_dir($folder))
            mkdir($folder, 0777, true);

        $file = $folder . 'estoque.xml';

        /**
         * Conecta ao FTP.
         */
        $this->ftp->connect($this->configFTP);

        /**
         * Exibe a lista de arquivos na pasta do FTP.
         */
        $list = $this->ftp->list_files('/');


        /**
         * Faz Download do arquivo CSV.
         */
        $this->ftp->download('Pharmanexo Preço.XML', $file, 'ascii');

        $this->ftp->close();


        $xml = simplexml_load_file($file);

        $file = json_decode(json_encode($xml), true);;

        $this->prepare($file);
    }

    public function importCSV()
    {
        $csv = fopen('estoque_st.csv', 'r');


        while (($line = fgetcsv($csv, NULL, ',')) !== false) {

            $produto = [
                'nome_comercial' => empty($line[2]) ? '' : $line[2],
                'apresentacao' => empty($line[2]) ? '' : $line[2],
                'codigo' => empty($line[1]) ? '' : $line[1],
                'unidade' => empty($line[6]) ? '' : $line[6],
                'marca' => empty($line[3]) ? '' : $line[3],
                'rms' => empty($line[5]) ? '' : $line[5],
                'quantidade_unidade' => 1,
                'id_fornecedor' => 5031,
                'ativo' => 1
            ];

            $prod = $this->st->getProduto($produto);

            if (empty($prod)) {
                $this->st->insertProduto($produto);
            }

        }
    }
}
