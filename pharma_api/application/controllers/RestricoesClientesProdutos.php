<?php

class RestricoesClientesProdutos extends CI_Controller
{
    private $homolog;

    public function __construct()
    {
        parent::__construct();
        $this->homolog = $this->load->database('teste_pharmanexo', true);
    }

    public function index()
    {
       $valorCotacoes = $this->db->query("
            SELECT date_format(cp.data_criacao, '%m/%y') as data,
                count(distinct cp.cd_cotacao) as quantidadeCotacao,
                SUM(cp.qtd_solicitada * cp.preco_marca) as valorTotal,
                fn.nome_fantasia as fornecedor
            FROM cotacoes_produtos cp
            JOIN fornecedores fn on cp.id_fornecedor = fn.id
            WHERE date(cp.data_criacao) BETWEEN '2022-05-01' AND '2022-05-10'
            GROUP BY cp.id_fornecedor")->result_array();

        $data = [];
        $data['teste'] = $valorCotacoes;
        $this->load->view('primeiraView', $data);
    }

    public function mudarPraIndexDps()
    {
        $arrayCodigos = [
            348,
            4313,
            111931,
            3804,
            900,
            4549,
            4550,
            111842,
            4468,
            110238,
            110251,
            110723,
            110722,
            4517,
            4240,
            3083,
            3084,
            4513,
            4406,
            4405,
            1572,
            110135,
            110122,
            110854,
            109839,
            110372,
            110381,
            110380,
            110359,
        ];
        $dados = [];
        $arrayIdsOncoProd = [12, 111, 112, 115, 120, 123];
        $estados = $this->db->where_in('uf', ['SP', 'SC', 'PR', 'RS'])->get('estados')->result_array();
        foreach ($arrayIdsOncoProd as $idOncoProd) {
            foreach ($arrayCodigos as $row) {
                foreach ($estados as $estado) {
                    $dados[] = [
                        'id_estado' => $estado['id'],
                        'id_fornecedor' => $idOncoProd,
                        'id_produto' => $row,
                        'id_tipo_venda' => 3,
                        'integrador' => 1
                    ];
                }
            }
        }
//        $this->db->insert_batch('restricoes_produtos_clientes', $dados);
    }
}

