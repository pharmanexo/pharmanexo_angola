<?php

class Oncoexo extends MY_Model
{
    /**
     * @author : Chule Cabral
     * Data: 24/09/2020
     */

    public $configFTP;

    public function __construct()
    {

        parent::__construct();

        /**
         * Dados Conexão FTP - Oncoexo.
         */

        $this->configFTP['hostname'] = 'ftp.oncoexo.com.br';
        $this->configFTP['username'] = 'oncoexo1';
        $this->configFTP['password'] = 'onco@2019@';
        $this->configFTP['debug'] = TRUE;

        $this->load->library('ftp');

        $this->load->model('Engine');

    }

    public function index_oncoexo($data)
    {

        $oc = $data['cod_oc'];

        /**
         * Verifica se a OC já foi resgatada.
         */
        $verify_oc = $this->Engine->verifyOc($oc);

        if (!IS_NULL($verify_oc)) {

            $this->Engine->outPutOc('error', 'Houve um erro ao resgatar o pedido! Pedido ja resgatado.');

           exit();

        }

        $arrayTemp = $data;

        $dir = $_SERVER['DOCUMENT_ROOT'] . '/pharma_integra/public/oc/oncoexo/';

        /**
         * Cria o diretório $dir se o mesmo não existir.
         */
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        /*
         * 3.1.  Nomenclatura do Arquivo de Pedido

        O arquivo de pedido deverá estar com a nomenclatura: PEDIDO_XXXXXXXXXX_YYYYYYYYYYYYYY_ZZZ.PED,
        onde XXXXXXXXXX é o código do pedido na Indústria,
        YYYYYYYYYYYYYY é o CNPJ do distribuidor e
        ZZZ é o código de identificação da Indústria .
        *
        * OS CAMPOS A SEGUIR FORAM DEFENIDOS PELO ANCHIETA COELHO, COORDENADOR DE TI DA ONCOEXO
        * DATA: 07/07/2020
        * Analista / Programar: Chule Cabral
        */

        $xxxxxx = $arrayTemp['cod_oc'];
        $yyyyyy = $arrayTemp['cnpj_fornecedor'];
        $zzzzzz = 'AZN';

        $file = "PEDIDO_{$xxxxxx}_{$yyyyyy}_{$zzzzzz}.PED";

        $file_name = "{$dir}{$file}";

        $file_pedido = fopen($file_name, "w+");

        /*
         * 4.1.  Arquivo de Pedido (Obrigatório)
         * Registro tipo 1 - Identificação do Cliente = Fixo 1
         */

        $qtd_produtos = count($arrayTemp['products']);

        $fixo1 = "1;";
        $cnpj_cliente = "{$arrayTemp['cnpj']};";
        $email_cliente = ";"; //NULL
        $cnpj_distribuidor = "{$arrayTemp['cnpj_fornecedor']};";

        $prazo_negociado = "";//CONFORME A QUANTIDADE DE PRODUTOS

        for ($i = 0; $i < $qtd_produtos; $i++)
            $prazo_negociado .= ";"; //NULL

        $tipo_venda = "415;";
        $cod_pedido_nf = ";"; //NULL
        $cod_pedido = "{$xxxxxx};";
        $margem = ";"; //NULL
        $flag_tp_pedido = ";"; //NULL
        $versao_layout = "2.1;";
        $sigla_industria = "VAC;";
        $flag_recalculo = ";"; //NULL
        $flag_cnpj = "PJ;";
        $apontador_comercial = ""; //NULL; ULTIMO CAMPO NÃO TEM PONTO E VIRGULA NO FINAL

        $identify_pedido = "";

        $arrayIdentify =
            [
                0 => $fixo1,
                1 => $cnpj_cliente,
                2 => $email_cliente,
                3 => $cnpj_distribuidor,
                4 => $prazo_negociado,
                5 => $tipo_venda,
                6 => $cod_pedido_nf,
                7 => $cod_pedido,
                8 => $margem,
                9 => $flag_tp_pedido,
                10 => $versao_layout,
                11 => $sigla_industria,
                12 => $flag_recalculo,
                13 => $flag_cnpj,
                14 => $apontador_comercial
            ];

        foreach ($arrayIdentify as $item)
            $identify_pedido .= $item;

        fwrite($file_pedido, $identify_pedido . "\r\n");

        /*
        * 4.1.  Arquivo de Pedido (Obrigatório)
        * Registro tipo 2 - Identificação do Produto = Fixo 2
        */

        foreach ($arrayTemp['products'] as $produto) {

            $fixo2 = "2;";
            $ean = "{$produto['ean']};";
            $quantidade_pedida = 0; //ENVIAR COMO CAIXA

            $quantidade_pedida = (intval($produto['quantidade']) / intval($produto['qtd_emb'])) . ";";
            $percentual_unitario = "0.00;";

            $valor_unitario = 0.0; //ENVIAR COMO PRECO CAIXA
            $valor_unitario = (floatval($produto['preco']) * intval($produto['qtd_emb']));
            $valor_unitario_format = number_format($valor_unitario, 2, '.', '.') . ";";

            $prazo_produto = ";"; //NULL
            $flag_produto = "L"; //ULTIMO CAMPO NÃO TEM PONTO E VIRGULA NO FINAL

            $identify_produto = "";

            $arrayIdentifyProduto =
                [
                    0 => $fixo2,
                    1 => $ean,
                    2 => $quantidade_pedida,
                    3 => $percentual_unitario,
                    4 => $valor_unitario_format,
                    5 => $prazo_produto,
                    6 => $flag_produto
                ];

            foreach ($arrayIdentifyProduto as $item)
                $identify_produto .= $item;

            fwrite($file_pedido, $identify_produto . "\r\n");
        }

        /*
        * 4.1.  Arquivo de Pedido (Obrigatório)
        * Registro tipo 9 - Finalizador do Pedido = Fixo 9
        */

        $fixo9 = "9;";
        $total_produtos = $qtd_produtos;

        fwrite($file_pedido, $fixo9 . $total_produtos);

        fclose($file_pedido);

        $array = [];

        if (file_exists($file_name)) {

            $file_in_ftp = "/SINTESE/PHARMANEXO/PEDIDOS/{$file}";

            $this->ftp->connect($this->configFTP);

            $this->ftp->upload($file_name, $file_in_ftp, 'ascii');

            $verify_file = $this->ftp->list_files($file_in_ftp);

            if (empty($verify_file)) {

                $this->Engine->outPutOc('error', 'Houve um erro ao resgatar o pedido!');

            } else {

               /* $this->db->where('Cd_Ordem_Compra', $oc)
                    ->set('pendente', 0)
                    ->update('ocs_sintese');*/

                $this->Engine->outPutOc('success', 'Pedido resgatado com sucesso!');

            }

            $this->ftp->close();

        } else {

            $this->Engine->outPutOc('error', 'Houve um erro ao resgatar o pedido!');

        }

    }
}
