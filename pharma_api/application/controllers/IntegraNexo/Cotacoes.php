<?php

class Cotacoes extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $getDados = $this->input->post();
        $arrayWhere = [];

        if (isset($getDados['data_inicial']) && isset($getDados['data_final']) && isset($_SESSION['id_fornecedor'])) {

            $arrayWhere['id_fornecedor'] = $_SESSION['id_fornecedor'];

            $dataAtualCru = date_create($getDados['data_inicial']);
            $dataLimiteCru = date_create($getDados['data_final']);

            $dataini = $dataAtualCru->format('Y-m-d');
            $datafim = $dataLimiteCru->format('Y-m-d');

            $diferenca = $dataAtualCru->diff($dataLimiteCru);

            if ($diferenca->days <= 2) {

                if (isset($getDados['cotacao'])) {
                    $arrayWhere['cd_cotacao'] = $getDados['cotacao'];
                }
                $this->db->select('cd_cotacao, id_cliente, integrador, data_cotacao');
                $this->db->where($arrayWhere);
                $this->db->where("date(data_criacao) BETWEEN '{$dataini}' AND '{$datafim}'");
                $this->db->where("submetido = 1");
                $this->db->where("data_download is null");
                $this->db->group_by('cd_cotacao');
                $cotacoes = $this->db
                    ->get('cotacoes_produtos')->result_array();

                $cotacoesResult = [];

                foreach ($cotacoes as $l => $cotacao) {

                    switch (strtoupper($cotacao['integrador'])) {
                        case 'SINTESE':
                            $banco = $this->load->database('sintese', true);
                            $cot = $banco
                                ->where('cd_cotacao', $cotacao['cd_cotacao'])
                                ->where('id_fornecedor', $_SESSION['id_fornecedor'])
                                ->get('cotacoes')
                                ->row_array();

                            $dataAtual = time();
                            $dataFimCot = strtotime($cot['dt_fim_cotacao']);

                            if ($dataFimCot > $dataAtual) {
                                continue;
                            }


                            break;
                        case 'BIONEXO':
                            $banco = $this->load->database('bionexo', true);
                            $cot = $banco
                                ->where('cd_cotacao', $cotacao['cd_cotacao'])
                                ->where('id_fornecedor', $_SESSION['id_fornecedor'])
                                ->get('cotacoes')
                                ->row_array();

                            $dataAtual = time();
                            $dataFimCot = strtotime($cot['dt_fim_cotacao']);

                            if ($dataFimCot > $dataAtual) {
                                continue;
                            }


                            break;
                        case 'APOIO':
                            $banco = $this->load->database('apoio', true);
                            $cot = $banco
                                ->where('cd_cotacao', $cotacao['cd_cotacao'])
                                ->where('id_fornecedor', $_SESSION['id_fornecedor'])
                                ->get('cotacoes')
                                ->row_array();

                            $dataAtual = time();
                            $dataFimCot = strtotime($cot['dt_fim_cotacao']);

                            if ($dataFimCot > $dataAtual) {
                                continue;
                            }

                            break;
                    }

                    //get dados comprador
                    $comprador = $this->db->select('cnpj, razao_social, nome_fantasia, endereco, cidade, estado, cep, telefone, celular, email')
                        ->where('id', $cotacao['id_cliente'])
                        ->get('compradores')
                        ->row_array();

                    $cotacao = array_merge($cotacao, $comprador);


                    //get produtos
                    $this->db->where("submetido = 1");
                    $this->db->where("cd_cotacao", $cotacao['cd_cotacao']);
                    $this->db->where("id_fornecedor", $_SESSION['id_fornecedor']);
                    $produtos = $this->db
                        ->get('cotacoes_produtos')->result_array();




                    $cotacao['produtos'] = $produtos;

                  

                    $cotacoesResult[] = $cotacao;


                    //update cotação
                    $this->db
                        ->where('cd_cotacao', $cotacao['cd_cotacao'])
                        ->where('id_fornecedor', $_SESSION['id_fornecedor'])
                        ->update('cotacoes_produtos', ['data_download' => date('Y-m-d H:i:s')]);

                    //cria log
                    $log = [
                        'cotacao' => $cotacao['cd_cotacao'],
                        'id_fornecedor' => $_SESSION['id_fornecedor'],
                        'tipo' => 4,
                        'mensagem' => "Download da cotação realizada pelo distribuidor"
                    ];

                    $this->db->insert('log_cotacoes_api', $log);

                }


                $this->output->set_content_type('application/json')->set_status_header(200)->set_output(json_encode(
                    [
                        'status' => 'success',
                        'data' => $cotacoesResult
                    ]
                ));

            } else {
                $this->output->set_content_type('application/json')->set_status_header(400)->set_output(json_encode(
                    [
                        'status' => 'error',
                        'message' => "O limite de busca é de 2 dias"
                    ]
                ));
            }
        } else {
            $this->output->set_content_type('application/json')->set_status_header(400)->set_output(json_encode(
                [
                    'status' => 'error',
                    'message' => "Data inicial, data final e id do fornecedor são obrigatórios"
                ]
            ));
        }

    }

    /** Atualiza o status da cotação gerando um log
     * necessário codigo_cotacao, mensagem, e tipo
     * TIPOS: 0 ERRO, 1 SUCESSO, 2 WARNING
     */
    public function atualizarStatus()
    {
        $post = $this->input->post();
        $warning = [];

        if (!isset($post['cotacao']) || empty($post['cotacao'])) {
            $warning[] = ['type' => 'error', 'message' => 'Campo cotação obrigatório'];
        }

        if (!isset($post['mensagem']) || empty($post['mensagem'])) {
            $warning[] = ['type' => 'error', 'message' => 'Campo mensagem obrigatório'];
        }

        if (!isset($post['tipo']) || empty($post['tipo'])) {
            $post['tipo'] = 4;
        }


        if (empty($warning)) {

            $log = [
                'cotacao' => $post['cotacao'],
                'id_fornecedor' => $_SESSION['id_fornecedor'],
                'tipo' => $post['tipo'],
                'mensagem' => $post['mensagem']
            ];

            $insert = $this->db->insert('log_cotacoes_api', $log);


            if ($insert){
                $warning[] = ['type' => 'success', 'message' => 'Registramos a atualização'];
            }

        }


        $this->output->set_content_type('application/json')->set_status_header(400)->set_output(json_encode($warning));

    }
}
