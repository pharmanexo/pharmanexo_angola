<?php

class OncoprodPrecos extends MY_Controller
{
    /**
     * @author : Chule Cabral
     * Data: 24/09/2020
     */

    private $arrayProdsPreco = [];

    private $fornecedor = [12, 111, 112, 115, 120, 126, 123];

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
    }

    private function mountArrayPreco($file)
    {

        /**
         * Varre o arquivo CSV e monta os Objetos de preços por UF.
         * Verifica se o arquivo existe e é válido.
         */

        $insert = [];

        #ler o csv e coloca num array
        while (($line = fgetcsv($file, null, ';')) !== false) {
            $insert[] = $line;
        }

        #separa a primeira linha do array como empresas
        $empresas = $insert[0];
        #separa a segunda linha do array como estados
        $estados = $insert[1];

        #remove a primeira e segunda linha do vetor insert
        unset($insert[0], $insert[1]);

        #inicia o processo de verificação e inserção de preçø
        foreach ($insert as $key => $value) {

            $dt = [];
            #verifica qual o id_fornecedor baseado no nome das empresas enviados no arquivo
            foreach ($value as $indice => $valor) {

                $emp = $empresas[$indice];

                switch (trim($emp)) {
                    case "ONCOPROD RS":
                        $emp = 12;
                        break;
                    case "ONCOPROD SP":
                        $emp = 115;
                        break;
                    case "ONCOPROD PE":
                        $emp = 123;
                        break;
                    case "NORPROD CE":
                        $emp = 111;
                        break;
                    case "HOSPLOG DF":
                        $emp = 120;
                        break;
                    case "ONCOPROD ES":
                        $emp = 112;
                        break;
                    case "ONCOPROD DF":
                        $emp = 126;
                        break;
                }

                #monta um array com o nome do campo, codigo do produto, preco e estados (array);
                $data = [
                    'campo' => $emp,
                    'codigo' => (isset($dt[0])) ? $dt[0]['preco'] : 0,
                    'preco' => $valor,
                    'estados' => explode('/', trim($estados[$indice]))
                ];

                #faz foreach nos estados para pegar o id_estado
                foreach ($data['estados'] as $k => $v) {

                    $id_estado = $this->db->where('uf', $v)->get('estados')->row_array();

                    $data['estados'][$k] = $id_estado['id'];

                }

                $dt[] = $data;
            }

            #percorrer o vetor montando o insert de preço basedo em $dt
            for ($i = 1; $i < count($dt); $i++) {

                $aux = $dt[$i];

                foreach ($aux['estados'] as $j => $vv) {

                    $preco = str_replace(',', '.', str_replace('.', '', $aux['preco']));

                    $this->arrayProdsPreco[] = [
                        'codigo' => intval($aux['codigo']),
                        'id_estado' => intval($vv),
                        'preco_unitario' => number_format(floatval($preco), 4, '.', ''),
                        'id_fornecedor' => intval($aux['campo']),
                    ];
                }

                $data = [];
            }
        }
    }

    protected function index_get()
    {

        //    $this->db->trans_start();

        $folder = 'public/FTP/Oncoprod/';

        /**
         * Cria o diretório $folder caso ele não exista.
         */
        if (!is_dir($folder))
            mkdir($folder, 0777, true);

        $file = $folder . 'precos_oncoprod.csv';

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

        /**
         * Se Download foi concluído com Sucesso, e o arquivo existe, abra o arquivo e faça a Leitura.
         */
        if (file_exists($file)) {

            $file = fopen($file, 'r');

            /**
             * Chama a Função para criar o array de preço dos produtos.
             */
            $this->mountArrayPreco($file);

            $arrayProdsPreco = multi_unique($this->arrayProdsPreco);

            /**
             * Para cada preco, verifica se já existe no Banco de dados e se não é zerado.
             * Insere o preço na tabela de produtos preço.
             */
            foreach ($arrayProdsPreco as $prodPrec) {

                if ($this->Engine->checkPrice($prodPrec) && !(floatval($prodPrec['preco_unitario']) == 0)) {
                    if (in_array($prodPrec['id_fornecedor'], $this->fornecedor)) {
                        $this->db->insert('produtos_preco', $prodPrec);
                    }
                }
            }
        }
    }
}