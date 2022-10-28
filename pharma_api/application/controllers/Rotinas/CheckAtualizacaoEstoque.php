<?php

class CheckAtualizacaoEstoque extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('M_notificacao', 'notificacao');
    }


    public function index()
    {
        $fornecedores = $this->db->where('id', 20)->where('sintese', 1)->get('fornecedores')->result_array();

        foreach ($fornecedores as $fornecedor) {
            $config = json_decode($fornecedor['config'], true);


            if (isset($config['alertaEstoque']) && $config['alertaEstoque'] == true) {

                $data = $this->db->select('MAX(data_criacao) as data')->where('id_fornecedor', $fornecedor['id'])->get('produtos_lote')->row_array();


                if (!empty($data)) {
                    $dataAtualizacao = new DateTime($data['data']);
                    $dataAtual = new DateTime("now");
                    $diff = $dataAtual->diff($dataAtualizacao);


                    if ($diff->i > 30) {
                        $q = $this->notificacao->sendEmail([
                            'from' => 'suporte@pharmanexo.com.br',
                            'from-name' => 'Marlon Boecker',
                            'destinatario' => 'marlon.boecker@pharmanexo.com.br, ti@hospidrogas-es.com.br',
                            'assunto' => 'SEM COMUNICAÇÃO DE ESTOQUE - ' . $fornecedor['nome_fantasia'],
                            'msg' => "Não recebemos atualização de estoque do distribuidor {$fornecedor['nome_fantasia']} há {$diff->h} hora(s) e {$diff->i} minuto(s)",
                        ]);
                    }else{
                        echo "Dentro do prazo!";
                    }

                }

            }

        }


    }

}