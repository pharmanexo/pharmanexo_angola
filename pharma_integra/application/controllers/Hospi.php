<?php

date_default_timezone_set('America/Fortaleza');

class Hospi extends MY_Controller
{

    /**
     * @author : Chule Cabral
     * Data: 24/09/2020
     */

    private $fornecedor = 20;
    public $configFTP;

    private $xmlId = 0;

    private $arrayMovEstoque = [];
    private $arrayProdsCatalogo = [];
    private $arrayProdsLote = [];
    private $arrayProdsPreco = [];
    private $arrayIdsMarca = [];

    public function __construct()
    {

        exit();
        
        parent::__construct();

        /**
         * Dados conexão FTP Oncoexo
         */

        $this->configFTP['hostname'] = 'ftp.hospidrogas-es.com.br';
        $this->configFTP['username'] = 'pharma@hospidrogas-es.com.br';
        $this->configFTP['password'] = 'hosp@2018';
        $this->configFTP['passive'] = TRUE;
        $this->configFTP['debug'] = TRUE;

        $this->load->library('ftp');
        $this->load->model('Engine');
    }

    private function mountArraysXML($xml): array
    {

        /**
         * Varre o arquivo XML e monta os Objetos dos produtos.
         */

        foreach ($xml->produtos as $objectXML) {

            foreach ($objectXML as $valuesXML) {

                $produtos[] = [

                    "id" => intval($valuesXML->id),
                    "local" => strval($valuesXML->local),
                    "produto" => strval($valuesXML->produto),
                    "nome_comercial" => strval($valuesXML->nome_comercial),
                    "codigo" => intval($valuesXML->codigo),
                    "apresentacao" => strval($valuesXML->apresentacao),
                    "unid" => strval($valuesXML->unid),
                    "qtd_menor" => empty($valuesXML->qtd_menor) ? 1 : intval($valuesXML->qtd_menor),
                    "marca" => strval($valuesXML->marca),
                    "rms" => empty($valuesXML->rms) ? NULL : strval($valuesXML->rms),
                    "quantidade" => intval($valuesXML->quantidade),
                    "lote" => strval($valuesXML->lote),
                    "validade" => strval($valuesXML->validade),
                    "preco" => $valuesXML->preco
                ];
            }
        }
        return $produtos;
    }

    private function mountArraysProds($produtos)
    {

        /**
         * Varre o objeto de produtos e monta os objetos de dados.
         * Movimentação de Estoque;
         * Catalogo;
         * Estoque;
         * Preços.
         */

        foreach ($produtos as $produto) {

            $this->arrayMovEstoque[] = [
                "id_fornecedor" => $this->fornecedor,
                "xml_id" => $produto['id'],
                "local" => $produto['local'],
                "produto" => $produto['produto'],
                "nome_comercial" => $produto['nome_comercial'],
                "codigo" => $produto['codigo'],
                "apresentacao" => $produto['apresentacao'],
                "quantidade" => $produto['quantidade'],
                "unidade" => $produto['unid'],
                "qtd_unidade" => $produto['qtd_menor'],
                "marca" => $produto['marca'],
                "rms" => (empty($produto['rms'])) ? NULL : $produto['rms'],
                "lote" => $produto['lote'],
                "validade" => dateFormat($produto["validade"], "Y-m-d"),
                "preco" => $produto['preco']
            ];

            $this->arrayProdsCatalogo[] = [
                "codigo" => intval($produto['codigo']),
                "rms" => (empty($produto['rms'])) ? NULL : $produto['rms'],
                "apresentacao" => $produto['apresentacao'],
                "marca" => $produto['marca'],
                "unidade" => $produto['unid'],
                "quantidade_unidade" => $produto['qtd_menor'],
                "descricao" => $produto['produto'],
                "nome_comercial" => $produto['nome_comercial'],
                "id_fornecedor" => $this->fornecedor,
                "ativo" => 1,
                "bloqueado" => 0
            ];

            $id_marca = $this->Engine->checkIdMarca(array("marca" => $produto['marca'], "id_fornecedor" => $this->fornecedor));

            if ($id_marca != 0) {

                $this->arrayIdsMarca[] = [
                    "codigo" => intval($produto['codigo']),
                    "id_marca" => $id_marca
                ];
            }

            $this->arrayProdsLote[] = [
                "lote" => $produto['lote'],
                "local" => $produto['local'],
                "codigo" => intval($produto['codigo']),
                "id_fornecedor" => $this->fornecedor,
                "estoque" => intval($produto['quantidade']),
                "validade" => dateFormat($produto["validade"], "Y-m-d"),
            ];

            $this->arrayProdsPreco[] = [
                "codigo" => intval($produto['codigo']),
                "id_fornecedor" => $this->fornecedor,
                "id_estado" => NULL,
                "preco_unitario" => number_format(floatval(strtr($produto['preco'], ',', '.')), 4, '.', '')
            ];
        }
    }

    public function index_get()
    {
        $fornecedor = $this->fornecedor;

        /**
         * Verifica se o fornecedor faz parte da rotina de integração.
         * Inicia a contagem do tempo da Rotina.
         */
        if (!$this->Engine->start('BEGIN', $fornecedor, time()))
            exit();

        $folder = 'public/Files/Hospidrogas/';

        /**
         * Cria o diretório $folder caso ele não exista.
         */
        if (!is_dir($folder))
            mkdir($folder, 0777, true);

        $file = $folder . 'estoqueHospidrogas.xml';

        /**
         * Conecta ao FTP.
         */
        $this->ftp->connect($this->configFTP);

        /**
         * Exibe a lista de arquivos na pasta do FTP.
         */
        $list = $this->ftp->list_files('/PHARMANEXO/');

        /**
         * Faz Download do arquivo XML.
         */
        $this->ftp->download('/PHARMANEXO/ARQ_PHARMANEXO_ESTOQUE.XML', $file, 'ascii');

        $this->ftp->close();

        /**
         * Se o mesmo arquivo já existir localmente, integração é interrompida.
         * Ou seja, o arquivo já foi processado.
         */
        if (!file_exists($file))
            exit();

        /**
         * Carregao os dados do Arquivo XML.
         */
        $xml = simplexml_load_file($file);

        $produtos = $this->mountArraysXML($xml);

        /**
         * A Integração da Hospidrogas trabalha com o XML id.
         * Cada XML tem uma identificação única, caso o XML venha mais
         * de uma vez na integração, ele não é processado.
         * Um e-mail é enviado para o responsável da Integração da Hospidrogas.
         */
        $this->xmlId = $produtos[0]['id'];

        if (!$this->Engine->checkXmlId($this->xmlId, $fornecedor)) {

            $date = date('d/m/y H:i:s', time());

            $text = "Integração não realizada. O último XML_ID processado: {$this->xmlId} <br><small>Data do Processamento: {$date}</small>";

            $template = file_get_contents('https://pharmanexo.com.br/public/html/template_mail/mail_simple.html');

            $body = str_replace(["%user%", "%body%"], ["Elismar Lobão", $text], $template);

            $mail = [

                "from" => "suporte@pharmanexo.com.br",
                "from-name" => "Pharmanexo",
                "assunto" => "Integração de Estoque",
                "destinatario" => 'elismar.lobao@hospidrogas-es.com.br',
                // "copia_o" => "marlon.boecker@pharmanexo.com.br",
                'msg' => $body
            ];
            $this->sendEmail($mail);
            exit();
        }

        /**
         * Chama a Função para criar os arrays com os dados dos produtos.
         */
        $this->mountArraysProds($produtos);

        $arrayMovEstoque = $this->arrayMovEstoque;
        $arrayProdsLote = multi_unique($this->arrayProdsLote);
        $arrayProdsPreco = multi_unique($this->arrayProdsPreco);
        $arrayProdsCatalogo = multi_unique($this->arrayProdsCatalogo);
        $arrayIdsMarca = multi_unique($this->arrayIdsMarca);

        // $this->db->trans_start();

        /**
         * Insere todos os dados dos produtos na tabela de log.
         * Movimentação de Estoque
         */
        $this->db->insert_batch('movimentacao_estoque', $arrayMovEstoque);

        /**
         * Limpa toda a tabela de estoque do fornecedor.
         */
        $this->Engine->cleanStock($fornecedor);

        /**
         * Para cada lote, verifica se é maior que zero.
         * Para cada lote, verifica se já foi inserido.
         * Insere o lote no Banco de Dados.
         */
        foreach ($arrayProdsLote as $prodLot) {

            if ($this->Engine->checkLot($prodLot) && $prodLot['estoque'] > 0)
                $this->db->insert('produtos_lote', $prodLot);
        }

        /**
         * Para cada preco, verifica se já existe no Banco de dados e se não é zerado.
         * Insere o preço na tabela de produtos preço.
         */
        foreach ($arrayProdsPreco as $prodPrec) {

            if ($this->Engine->checkPrice($prodPrec) && !(floatval($prodPrec['preco_unitario']) == 0)) {
                $this->db->insert('produtos_preco', $prodPrec);
            }
        }

        /**
         * Para cada produto, verifica se já está cadastrado no Catálogo.
         * Insere o produto no Banco de Dados se não estiver no catálogo.
         * Se o produto já for cadastrado, efetua um update no mesmo com a função activeCatalog.
         */
        foreach ($arrayProdsCatalogo as $prodCat) {

            if ($this->Engine->checkCatalog($prodCat)) {

                $this->db->insert('produtos_catalogo', $prodCat);

            } else {

                $this->Engine->activeCatalog($prodCat);
            }
        }

        /**
         * Finaliza a contagem do tempo da Rotina.
         */
        $this->Engine->start('END', $fornecedor, time(), $this->xmlId);

    }
}