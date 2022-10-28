<?php

class DeParaOncroprod extends MY_Controller
{

    /**
     * @author : Chule Cabral
     * Data: 24/09/2020
     *
     * A rotina tem a função de duplicar o De Para da Empresa Oncoprod
     * para as outras Filiais.
     */

    private $oncroprods = [12, 111, 112, 115, 120, 123, 126];
    private $arrayResult = [];

    public function __construct()
    {
        parent::__construct();
    }

    private function checkDePara($oncoprodTemp1, $oncoprodTemp2)
    {
        /**
         *  Armazena os dados dos produtos tem na Oncoprod Temp1 que não tem na Oncoprod Temp2
         */
        return $this->db->query("select id_sintese, cd_produto
                                    FROM (SELECT DISTINCT CONCAT(id_sintese, ' - ', cd_produto) chave, id_sintese, cd_produto
                                    FROM produtos_fornecedores_sintese
                                    WHERE id_fornecedor = {$oncoprodTemp1}

                                    ORDER BY id_sintese ASC, cd_produto ASC) x

                                    WHERE x.chave NOT IN ((SELECT DISTINCT CONCAT(id_sintese, ' - ', cd_produto)
                                                            FROM produtos_fornecedores_sintese
                                                            WHERE id_fornecedor = {$oncoprodTemp2}

                                                            ORDER BY id_sintese ASC, cd_produto ASC))")->result_array();
    }

    public function index_get()
    {
        /**
         *  Começa o loop confome o objeto $oncoprods
         */
        foreach ($this->oncroprods as $oncoprodTemp1) {

            unset($this->arrayResult);

            /**
             *  Começa o segundo loop confome o objeto $oncoprods
             */
            foreach ($this->oncroprods as $oncoprodTemp2) {

                unset($resultQuery);

                $key = "";

                /**
                 *  Pega o indice do Loop 1 dentro do Objeto
                 */
                $key = array_search($oncoprodTemp1, $this->oncroprods);

                /**
                 *  Só executa a rotina se o valor do Loop 2 for diferente do Loop 1 conforme a chave.
                 */
                if ($oncoprodTemp2 != $this->oncroprods[$key]) {

                    /**
                     *  Chama a função para verificar os produtos de DePara para as empresas.
                     */
                    $resultQuery = $this->checkDePara($oncoprodTemp1, $oncoprodTemp2);

                    if (!empty($resultQuery) && !IS_NULL($resultQuery)) {

                        /**
                         *  Cria a cópia do Depara se existir produtos sem Depara em alguma Filial.
                         */
                        foreach ($resultQuery as $result) {

                            $this->arrayResult[] = [

                                "id_sintese" => intval($result['id_sintese']),
                                "id_pfv" => NULL,
                                "id_usuario" => 187,
                                "cd_produto" => intval($result['cd_produto']),
                                "id_catalogo" => NULL,
                                "id_fornecedor" => intval($oncoprodTemp2)
                            ];
                        }

                    } else {
                        continue;
                    }
                }
            }

            /**
             *  Insere o Depara as Filiais que não tem se o Array não estiver vazio.
             */
            if (!empty($this->arrayResult) && !IS_NULL($this->arrayResult)) {

                $this->db->insert_batch('produtos_fornecedores_sintese', $this->arrayResult);

            } else {
                continue;
            }
        }
    }
}
