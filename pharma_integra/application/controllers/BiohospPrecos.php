<?php

class BiohospPrecos extends REST_Controller
{

    /**
     * @author : Chule Cabral
     * Data: 24/09/2020
     */

    private $fornecedor = 104;

    private $arrayProdsPreco = [];

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Engine');
    }

    private function mountArraysProds($produtos)
    {

        /**
         * Varre o arquivo JSON e monta os Objetos de preços por UF.
         * Verifica se o arquivo existe e é válido.
         */

        if(!isset($produtos["produtos"])) {

            throw new Exception ('Estrutura do JSON invalida!');
        }

        foreach ($produtos as $produto) {

            $produto = arrayFormat($produto);

            foreach ($produto as $prod) {

                $precos = $prod['precos_uf'];

                if (isset($precos) && !empty($precos)) {

                    foreach ($precos as $uf => $preco) {

                        $id_estado = $this->db->where('uf', $uf)->get('estados')->row_array()['id'];

                        $this->arrayProdsPreco[] = [
                            "codigo" => intval($prod['codigo']),
                            "id_fornecedor" => $this->fornecedor,
                            "id_estado" => intval($id_estado),
                            "preco_unitario" => number_format(floatval(strtr($preco, ',', '.')), 4, '.', '')
                        ];
                    }

                } else {

                    throw new Exception ('Codigo '. $prod['codigo'] . ' com precos invalidos!');

                }
            }
        }
    }

    protected function index_post()
    {
        try {

            $post = file_get_contents("php://input");

            /**
             * A TI da Biohosp, não tem poder 100% em cima do ERP da empresa,
             * por esse motivo o arquivo JSON vem todos desconfigurado, pela má
             * configuração do sistema.
             * O tratamento de Strings é necessário para a extruturação
             * do arquivo JSON.
             */
            $re = '/(\w+)(:)/m';

            $subst = '\\"$1\\"$2';

            $result = preg_replace($re, $subst, $post);

            $json = str_replace("\\", "", $result);

            $produtos = json_decode($json, true);

            if (IS_NULL($produtos) || empty($produtos)) {

                throw new Exception ('Nao ha itens na lista!');
            }

            /**
             * Chama a Função para criar o array com os preços dos produtos.
             */
            $this->mountArraysProds($produtos);

            $precosProdutos = $this->arrayProdsPreco;

            if (IS_NULL($precosProdutos)) {

                throw new Exception ('Arquivo de Precos Invalido!');
            }

            /**
             * Para cada preco, verifica se já existe no Banco de dados e se não é zerado.
             * Insere o preço na tabela de produtos preço.
             */
            foreach ($precosProdutos as $prodPrec) {

                if ($this->Engine->checkPrice($prodPrec) && !(floatval($prodPrec['preco_unitario']) == 0)) {
                    $this->db->insert('produtos_preco', $prodPrec);
                }
            }

            $return = [
                "response" => TRUE,
                "message" => "Precos Atualizados!"
            ];

        } catch (Exception $e) {

            $return = [
                "response" => FALSE,
                "message" => $e->getMessage()
            ];
        }


        $this->output->set_content_type('application/json')->set_output(json_encode($return));
    }
}
