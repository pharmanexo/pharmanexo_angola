<?php

class Pedidos extends MY_Controller_hmg
{
    public function __construct()
    {
        parent:: __construct();
    }

    public function index()
    {
        /**
         * CAMPOS QUE PERMITEM BUSCA
         * data_atual (referente a data inicio)
         * data_limite
         * cd_ordem_compra
         * status_ordem_compra
         **/

        if ($this->input->method() == 'post') {

            $getDados = $this->input->post();
            $arrayWhere = [];

            $this->db->trans_begin();/** Valida erros das consultas no BD. Caso de erro faz um rollback automatico */

            if (isset($getDados['data_atual']) && isset($getDados['data_limite']) && isset($_SESSION['id_fornecedor'])) {

                if (isset($getDados['cd_ordem_compra'])) {
                    $arrayWhere['Cd_Ordem_Compra'] = $getDados['cd_ordem_compra'];
                }

                if (isset($getDados['status_ordemCompra'])) {
                    if ($getDados['status_ordemCompra'] == 1) {
                        $arrayWhere['pendente'] = 1;
                    }
                    $arrayWhere['Status_OrdemCompra'] = $getDados['status_ordemCompra'];
                }

                $arrayWhere['id_fornecedor'] = $_SESSION['id_fornecedor'];

                $dataAtualCru = date_create($getDados['data_atual']);
                $dataLimiteCru = date_create($getDados['data_limite']);

                $dataAtual = $dataAtualCru->format('Y-m-d');
                $dataLimite = $dataLimiteCru->format('Y-m-d');

                $diferenca = $dataAtualCru->diff($dataLimiteCru);


                if (intval($diferenca->days) <= 2) {

                    $this->db->select('oc.*, c.nome_fantasia, c.razao_social');
                    $this->db->from('ocs_sintese oc');
                    $this->db->join('compradores c', 'c.id = oc.id_comprador');


                    $this->db->where($arrayWhere);
                    $this->db->where("date(oc.Dt_Ordem_Compra) BETWEEN '{$dataLimite}' AND '{$dataAtual}'");

                    $ocsSinteseResult = $this->db->get()->result_array();
//                    var_dump($this->db->last_query());
//                    exit();
                    $headerArray = [];

                    foreach ($ocsSinteseResult as $header) {
                        $ocsSinteseProdutoResult = $this->db->where('id_ordem_compra', $header['id'])->get('ocs_sintese_produtos')->result_array();

                        $ocsStatusOrdemCompra = $this->db->where('codigo', $header['Status_OrdemCompra'])->get('ocs_sintese_status')->row_array();

                        $header['status_ordemCompra_descricao'] = $ocsStatusOrdemCompra['descricao'];

                        $header['produtos'] = $ocsSinteseProdutoResult;
                        //REMOVE ESSES CAMPOS DA LISTAGEM
                        unset($header['Cd_Fornecedor'], $header['id_fornecedor'], $header['Tp_Situacao'], $header['Tp_Movimento'],
                            $header['nota'], $header['chave_nf'], $header['sequencia'], $header['prioridade']);

                        $headerArray[] = $header;
                    }

                    if ($this->db->trans_status() === FALSE) {
                        $getError = $this->db->error();

                        $this->db->trans_rollback();

                        $this->output->set_content_type('application/json')->set_status_header(200)->set_output(json_encode(
                            [
                                'status' => 'error',
                                'message' => $getError['message']
                            ]
                        ));
                    } else {
                        $this->db->trans_commit();
                        $this->output->set_content_type('application/json')->set_status_header(200)->set_output(json_encode(
                            [
                                'status' => 'success',
                                'data' => $headerArray
                            ]
                        ));
                    }

                } else {
                    $this->output->set_content_type('application/json')->set_status_header(400)->set_output(json_encode(
                        [
                            'status' => 'error',
                            'message' => 'O limite de busca é de 2 dias'
                        ]
                    ));
                }
            } else {
                $this->output->set_content_type('application/json')->set_status_header(400)->set_output(json_encode(
                    [
                        'status' => 'error',
                        'message' => 'Informe a data inicial, data final e id do fornecedor'
                    ]
                ));
            }
        }
    }

    public function pedidoDetalhes()
    {
        if ($this->input->method() == 'post') {
            $getDados = $this->input->post();
            $arrayWhere = [];

            if (isset($getDados['cd_ordem_compra']) && isset($_SESSION['id_fornecedor'])) {
                $arrayWhere =
                    [
                        'id_fornecedor' => $_SESSION['id_fornecedor'],
                        'Cd_Ordem_Compra' => $getDados['cd_ordem_compra']
                    ];

                $this->db->select('oc.*, c.nome_fantasia, c.razao_social');
                $this->db->from('ocs_sintese oc');
                $this->db->join('compradores c', 'c.id = oc.id_comprador');

                $this->db->where($arrayWhere);

                $ocsSinteseResult = $this->db->get()->result_array();

                $headerArray = [];

                foreach ($ocsSinteseResult as $header) {
                    $ocsSinteseProdutoResult = $this->db->where('id_ordem_compra', $header['id'])->get('ocs_sintese_produtos')->result_array();

                    $ocsStatusOrdemCompra = $this->db->where('codigo', $header['Status_OrdemCompra'])->get('ocs_sintese_status')->row_array();

                    $header['status_ordemCompra_descricao'] = $ocsStatusOrdemCompra['descricao'];

                    $header['produto'] = $ocsSinteseProdutoResult;
                    //REMOVE ESSES CAMPOS DA LISTAGEM
                    unset($header['Cd_Fornecedor'], $header['id_fornecedor'], $header['Tp_Situacao'], $header['Tp_Movimento'],
                        $header['nota'], $header['chave_nf'], $header['sequencia'], $header['prioridade']);

                    $headerArray[] = $header;
                }

                $this->output->set_content_type('application/json')->set_status_header(200)->set_output(json_encode(
                    [
                        'status' => 'success',
                        'data' => $headerArray
                    ]
                ));


            } else {
                $this->output->set_content_type('application/json')->set_status_header(400)->set_output(json_encode(
                    [
                        'status' => 'error',
                        'data' => 'Informe o cd_ordem_compra'
                    ]
                ));
            }
        }
    }

    public function atualizarPedido()
    {
        if ($this->input->method() == 'post') {
            $getDados = $this->input->post();

            if (isset($getDados['status']) && isset($getDados['idPedidoPharmanexo']) && isset($_SESSION['id_fornecedor'])) {

                $mudaStatus = ['Status_OrdemCompra' => $getDados['status']];

                if (isset($getDados['nota'])) {
                    $mudaStatus['nota'] = $getDados['nota'];
                }

                $this->db->where('id_fornecedor', $_SESSION['id_fornecedor']);
                $this->db->where('id', $getDados['idPedidoPharmanexo']);
                $result = $this->db->update('ocs_sintese', $mudaStatus);

                if ($result) {
                    $this->output->set_content_type('application/json')->set_status_header(200)->set_output(json_encode(
                        [
                            'status' => 'success',
                            'message' => 'Status alterado com sucesso'
                        ]
                    ));
                } else {
                    $this->output->set_content_type('application/json')->set_status_header(200)->set_output(json_encode(
                        [
                            'status' => 'error',
                            'message' => 'Erro ao alterar status'
                        ]
                    ));
                }
            }
        }
    }
}


