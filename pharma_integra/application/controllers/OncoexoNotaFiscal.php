<?php

class OncoexoNotaFiscal extends API_Controller
{

    /**
     * @author : Chule Cabral
     * Data: 24/09/2020
     */

    private $fornecedor = [1 => 25, 2 => 15];
    public $configFTP;
    private $arrayOcs = [];
    private $arrNotaFiscal = [];

    public function __construct()
    {
        parent::__construct();

        /**
         * Dados conexão FTP - Oncoexo
         */
        $this->configFTP['hostname'] = 'ftp.oncoexo.com.br';
        $this->configFTP['username'] = 'oncoexo1';
        $this->configFTP['password'] = 'onco@2019@';
        $this->configFTP['debug'] = TRUE;

        $this->load->library('ftp');

        $this->load->model('Financeiro');
    }

    private function checkFilesArray($array)
    {
        /**
         * Verifica nas Listas do FTP se o arquivo existe pelo nome.
         */

        $bool = FALSE;

        foreach ($array['list'] as $item) {

            if ($array['nm_file'] == $item) {

                $bool = TRUE;

                $this->arrayOcs[$array['keyOc']][$array['type']] = $bool;

                return $bool;

            } else {

                $this->arrayOcs[$array['keyOc']][$array['type']] = $bool;

                continue;
            }
        }

        return $bool;
    }

    public function index()
    {

        /**
         * Conecta ao FTP.
         */
        $connect = $this->ftp->connect($this->configFTP);

        $dir_ftp = '/SINTESE/PHARMANEXO/';

        if (!$connect) {

            // var_dump('Deu erro na conexão, verifica ai fiote!');
            //ALERTA, erro ao conectar no FTP
            exit();
        }

        /**
         * Lista todos os Arquivos da Pasta de Nota Fiscal.
         */
        $list_nf = $this->ftp->list_files($dir_ftp . 'NOTA*FISCAL/');

        /**
         * Lista todos os Arquivos da Pasta de Erro.
         */
        $list_err = $this->ftp->list_files($dir_ftp . 'ERROS/');

        if (!isset($list_nf) && !isset($list_err)) {

            //   var_dump('Erro nas pastas do FTP');

            //ALERTA, mandar e-mail, pasta de Nota Fiscal não existe no FTP

            exit();

        } else {

            unset($list_nf[0], $list_nf[1]);
            unset($list_err[0], $list_err[1]);

            // $list_err = array_values($list_err);

            /**
             * Armazena em $ocs todas as Ordem de Compras que Foram Resgatadas.
             */
            $ocs = $this->Financeiro->getOcResgatada($this->fornecedor);

            if (empty($ocs)) {

                //    var_dump('Não tem oc para resgatada');

                exit();

            } else {

                if (empty($list_nf) && empty($list_err)) {

                    //NAO TEM NADA DE ARQUIVO NO FTP

                    exit();

                }

                /**
                 * Para cada Ordem de Compra Resgatada é verifica se existem Arquivos de Nota Fiscal ou de Erros.
                 */
                foreach ($ocs as $keyOc => $oc) {

                    $nm_file_nota = "NOTA_{$oc['Cd_Ordem_Compra']}_{$oc['Cd_Fornecedor']}_AZN.PED";

                    $nm_file_erro = "PEDIDO_{$oc['Cd_Ordem_Compra']}_{$oc['Cd_Fornecedor']}_AZN.PED.TXT";

                    $this->arrayOcs[$keyOc] =
                        [
                            'id_fornecedor' => $oc['id_fornecedor'],
                            'Cd_Fornecedor' => $oc['Cd_Fornecedor'],
                            'Cd_Ordem_Compra' => $oc['Cd_Ordem_Compra'],
                            'file_nf' => $nm_file_nota,
                            'file_error' => $nm_file_erro
                        ];

                    /**
                     * Se Não existir nenhuma Nota Fiscal na Pasta, Verifica se existe arquivos de erro.
                     */
                    if (empty($list_nf)) {

                        $list_err = array_values($list_err);

                        /**
                         * Função que verifica se Tem Algum Arquivo de Erro para a OC da posição do Loop.
                         */
                        $this->checkFilesArray(
                            [
                                'type' => 'Error',
                                'list' => $list_err,
                                'nm_file' => $nm_file_erro,
                                'keyOc' => $keyOc
                            ]
                        );

                        $this->arrayOcs[$keyOc]['NotaFiscal'] = FALSE;

                        continue;

                        /**
                         * Se a Lista de Nota Fiscal
                         */
                    } else {

                        /**
                         * Se a pasta de Notas Fiscal não for Vazia, chama a função para verificar se Existe NF
                         * para a OC da posição do Loop atual.
                         */

                        $list_nf = array_values($list_nf);

                        $checkNf = $this->checkFilesArray(
                            [
                                'type' => 'NotaFiscal',
                                'list' => $list_nf,
                                'nm_file' => $nm_file_nota,
                                'keyOc' => $keyOc
                            ]
                        );

                        /**
                         * Se Tem Nota Fiscal, não tem erro.
                         */

                        if ($checkNf) {

                            $this->arrayOcs[$keyOc]['Error'] = FALSE;

                            continue;

                        } else {

                            /**
                             * Existem Notas na pasta, mas, nenhuma nota é da OC na posição do Loop Atual.
                             * Verifica então se existe Erro para a OC.
                             */

                            if (empty($list_err)) {

                                $this->arrayOcs[$keyOc]['Error'] = FALSE;

                                continue;

                            } else {

                                $list_err = array_values($list_err);

                                $this->checkFilesArray(
                                    [
                                        'type' => 'Error',
                                        'list' => $list_err,
                                        'nm_file' => $nm_file_erro,
                                        'keyOc' => $keyOc
                                    ]
                                );
                            }
                        }
                    }

                    if (!$this->arrayOcs[$keyOc]['NotaFiscal'] && (!$this->arrayOcs[$keyOc]['Error']))
                        unset($this->arrayOcs[$keyOc]);

                }

                /**
                 * A primeira parte verifica apenas se existe Notas e Arquivos de Erros.
                 * Um Objeto é montado com o dados corretos.
                 * Agora será feito o Download dos Arquivos no FTP.
                 */

                if (!empty($this->arrayOcs)) {

                    $folder = 'public/nf/oncoexo/';

                    /**
                     * Cria o diretório Folder se ele não existir.
                     */
                    if (!is_dir($folder))
                        mkdir($folder, 0777, true);

                    /**
                     * Para cada OC que existe arquivo.
                     */
                    foreach ($this->arrayOcs as $keyOc => $oc) {

                        /**
                         * A OC Atual tem Nota Fiscal.
                         */
                        if ($oc['NotaFiscal']) {

                            $file = $folder . $oc['file_nf'];

                            $strlen = strlen($file);

                            /**
                             * Mudar a extensão do arquivo .PED para .txt
                             */
                            $file_local = substr($file, -$strlen, ($strlen - 3)) . 'txt';

                            /**
                             * Deleta o arquivo da pasta se já existir.
                             */
                            if (file_exists($file_local))
                                unlink($file_local);

                            /**
                             * Download do arquivo na pasta do FTP.
                             */
                            $ftp_download = $this->ftp->download($dir_ftp . 'NOTA FISCAL/' . $oc['file_nf'], $file_local, 'ascii');

                            /**
                             * Se o Download for concluído com Sucesso.
                             * Monta um Objeto com os Dados Fixos do Arquivo.
                             *
                             * Começa a Ler os dados do Arquivo.
                             */
                            if ($ftp_download) {

                                $myFile = file($file_local);

                                /**
                                 * Extrutura do Arquivo, existe um Manual para entender Melhor como que a Nota Fiscal Funciona.
                                 * Procurar Anchieta da empresa Oncoexo, para disponibilizar o Manual de Nota Fiscal.

                                1;13000019;2020-08-14;10:53:43;2020-08-14;08958628000297;
                                2;2436;0.00;3691.50;;1;25200808958628000297550010000024361113398399;
                                3;3691.50;0.00;0.00;3691.50;0.00;0.00;0.00;
                                4;4036124019518;30;0.00;0.00;123.05;3691.50;0.00;12.00;0.00;442.98;0.00;0.00;6108;I;;0;0;;0.00;3691.50;123.05;00;0.00;0.00;M;
                                4.1;4036124019518;B235419P01;30;2022-08-31;
                                5;12.00;3691.50;442.98;
                                6;442.98;0.00;30;
                                8;2436-1;2020-08-14;3691.50;2020-08-14;3691.50;0.00;0.00;0.00;0.00;;;;0.00;
                                9;9;
                                 */

                                $arrayFixo =
                                    [
                                        '1' =>
                                            [
                                                'tipo' => 'Fixo1',
                                                'tam' => 8
                                            ],
                                        '2' =>
                                            [
                                                'tipo' => 'Fixo2',
                                                'tam' => 7
                                            ],
                                        '3' =>
                                            [
                                                'tipo' => 'Fixo3',
                                                'tam' => 8
                                            ],
                                        '4' =>
                                            [
                                                'tipo' => 'Fixo4',
                                                'tam' => 26
                                            ],
                                        '4.1' =>
                                            [
                                                'tipo' => 'Fixo4.1',
                                                'tam' => 5
                                            ],
                                        '5' =>
                                            [
                                                'tipo' => 'Fixo5',
                                                'tam' => 4
                                            ],
                                        '6' =>
                                            [
                                                'tipo' => 'Fixo6',
                                                'tam' => 4
                                            ],
                                        '8' =>
                                            [
                                                'tipo' => 'Fixo8',
                                                'tam' => 14
                                            ],
                                        '9' =>
                                            [
                                                'tipo' => 'Fixo9',
                                                'tam' => 2
                                            ]
                                    ];

                                /**
                                 * Faz a Leitura da linha do arquivo, utiliza o explode para colocar cada linha em um array.
                                 * Pesquise mais sobre a função Explode do PHP.
                                 */
                                foreach ($myFile as $key => $line) {

                                    $arr = explode(';', $line);

                                    $fixo = $arrayFixo[$arr[0]]['tipo'];
                                    $tam_fixo = $arrayFixo[$arr[0]]['tam'];

                                    $newArr = array_slice($arr, 0, count($arr) - 1);

                                    $tam_newArr = count($newArr);

                                    if ($tam_fixo == $tam_newArr) {

                                        $this->arrNotaFiscal[$fixo][] = $newArr;

                                    } else {

                                        $x = ($tam_fixo - $tam_newArr);

                                        $array = [];

                                        for ($i = 0; $i < $x; $i++) {

                                            $array[$i] = "";

                                        }

                                        $this->arrNotaFiscal[$fixo][] = array_merge($newArr, $array);

                                    }
                                }

                                //  var_dump($this->arrNotaFiscal);

                                $arrDadosNota =
                                    [
                                        'id_fornecedor' => $oc['id_fornecedor'],
                                        'cd_ordem_compra' => $oc['Cd_Ordem_Compra'],
                                        'cd_pedido_fornecedor' => $this->arrNotaFiscal['Fixo1'][0][1],
                                        'data_emissao' => $this->arrNotaFiscal['Fixo1'][0][4],
                                        'numero' => $this->arrNotaFiscal['Fixo2'][0][1],
                                        'modelo' => $this->arrNotaFiscal['Fixo2'][0][4],
                                        'serie' => $this->arrNotaFiscal['Fixo2'][0][5],
                                        'chave' => $this->arrNotaFiscal['Fixo2'][0][6],
                                        'valor' => $this->arrNotaFiscal['Fixo3'][0][1],
                                        'valor_total_produto' => $this->arrNotaFiscal['Fixo3'][0][4],
                                        'valor_frete' => $this->arrNotaFiscal['Fixo3'][0][6]
                                    ];

                                $this->db->trans_start();

                                $insertNota = $this->Financeiro->insertNotaFiscal($arrDadosNota);

                                $id_nota = $this->db->insert_id();

                                if ($insertNota) {

                                    $arrDadosNotaProdutos = [];

                                    foreach ($this->arrNotaFiscal['Fixo4'] as $produto) {

                                        $codigo = $this->Financeiro->checkEan(
                                            [
                                                'fornecedor' => $this->fornecedor,
                                                'ean' => $produto[1]
                                            ]);

                                        $arrDadosNotaProdutos[] =
                                            [
                                                'id_nota_fiscal' => $id_nota,
                                                'id_fornecedor' => $oc['id_fornecedor'],
                                                'cd_ordem_compra' => $oc['Cd_Ordem_Compra'],
                                                'ean' => $produto[1],
                                                'codigo' => $codigo,
                                                'qtd_atendida' => $produto[2],
                                                'valor_unitario' => $produto[5],
                                                'valor_total_produto' => $produto[20]
                                            ];
                                    }

                                    $insertProdutos = $this->Financeiro->insertNfProdutos($arrDadosNotaProdutos);

                                    if ($insertProdutos === FALSE)
                                        $this->db->trans_rollback();

                                    //MUDAR O STATUS DA ORDEM DE COMPRA E NOTA FISCAL SE DER TUDO CERTO

                                    $this->db->trans_complete();

                                }
                            }
                        } else if ($oc['Error']) {

                            //LOGGGGG

                            $this->Financeiro->RestartOc(
                                [
                                    'id_fornecedor' => intval($oc['id_fornecedor']),
                                    'oc' => $oc['Cd_Ordem_Compra']
                                ]
                            );

                        } else {

                            //LOGGG

                            unset($this->arrayOcs[$keyOc]);
                        }
                    }
                }
            }
        }

        $this->ftp->close();
    }
}