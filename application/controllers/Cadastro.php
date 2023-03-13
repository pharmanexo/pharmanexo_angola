<?php

class Cadastro extends CI_Controller
{
    private $oncoprod = [12, 111, 112, 115, 120, 123];
    private $route;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('cadastro');
    }

    public function index()
    {

        $data['header'] = $this->template->homeheader([]);
        $data['navbar'] = $this->template->homenavbar([]);
        $data['banner'] = $this->template->homebanner([]);
        $data['scripts'] = $this->template->homescripts([]);
        $data['footer'] = $this->template->homefooter([]);

        $data['form_action'] = "{$this->route}/getCnpj";


        $this->load->view('cadastro/init', $data, FALSE);

    }

    public function arquivos()
    {
        if (!isset($_SESSION['novoDist'])) {
            redirect($this->route);
        }

        $data['header'] = $this->template->homeheader([]);
        $data['navbar'] = $this->template->homenavbar([]);
        $data['banner'] = $this->template->homebanner([]);
        $data['scripts'] = $this->template->homescripts([]);
        $data['footer'] = $this->template->homefooter([]);

        $data['form_action'] = "{$this->route}/uploadFiles";
        $data['dados'] = $_SESSION['novoDist'];

        $this->load->view('cadastro/importacao', $data, FALSE);

    }

    public function novoRegistro()
    {

        $data['header'] = $this->template->homeheader([]);
        $data['navbar'] = $this->template->homenavbar([]);
        $data['banner'] = $this->template->homebanner([]);
        $data['scripts'] = $this->template->homescripts([]);
        $data['footer'] = $this->template->homefooter([]);

        $data['form_action'] = "{$this->route}/salvar";
        $data['dados'] = (isset($_SESSION['novoCadastro']['data'])) ? $_SESSION['novoCadastro']['data'] : [];

        $this->load->view('cadastro/step_1', $data, FALSE);
    }

    public function salvar()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('cnpj', 'CNPJ', 'required');
            $this->form_validation->set_rules('nome_fantasia', 'Nome Fantasia', 'required');
            $this->form_validation->set_rules('razao_social', 'Razão Social', 'required');
            $this->form_validation->set_rules('nome', 'Nome de Contato', 'required');
            $this->form_validation->set_rules('celular', 'Celular', 'required');
            $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');


            if ($this->form_validation->run() == FALSE) {
                $erros = $this->form_validation->error_string();

                $output = [
                    'type' => 'warning',
                    'message' => 'Existem campos com erro: ' . $erros
                ];

            } else {

                $this->db->trans_begin();
                //verificar se o distribuidor ja existe
                $dist = $this->db->select('id')->where('cnpj', $post['cnpj'])->get('fornecedores')->row_array();

                if (!empty($dist)) {
                    var_dump($dist);
                    exit();
                } else {
                    $novoDist = [
                        'cnpj' => $post['cnpj'],
                        'nome_fantasia' => $post['nome_fantasia'],
                        'razao_social' => $post['razao_social'],
                    ];


                    $this->db->insert("fornecedores", $novoDist);
                    $idDist = $this->db->insert_id();

                    $novoContatoDist = [
                        [
                            'id_fornecedor' => $idDist,
                            'nome' => $post['nome'],
                            'tipo' => 'email',
                            'valor' => $post['email'],
                            'data_cadastro' => date("Y-m-y H:i:s", time())
                        ],
                        [
                            'id_fornecedor' => $idDist,
                            'nome' => $post['nome'],
                            'tipo' => 'celular',
                            'valor' => $post['celular'],
                            'data_cadastro' => date("Y-m-y H:i:s", time())
                        ]
                    ];

                    $this->db->insert_batch('fornecedores_contatos', $novoContatoDist);

                }

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();

                    $output = [
                        'type' => 'warning',
                        'message' => 'Não foi possível realizar o cadastro, entrem em contato com o suporte'
                    ];
                } else {
                    $this->db->trans_commit();

                    $post['id_dist'] = $idDist;
                    $_SESSION['novoDist'] = $post;

                    $output = [
                        'type' => 'success',
                        'message' => 'Cadastro realizado com sucesso.',
                        'url' => "{$this->route}/arquivos"
                    ];
                }

            }


            if (!empty($output)) {
                $this->output->set_content_type('application/json')->set_output(json_encode($output));
            }
        }
    }

    public function politica_cookies()
    {
        $data['header'] = $this->template->homeheader([]);
        $data['navbar'] = $this->template->homenavbar([]);
        $data['banner'] = $this->template->homebanner([]);
        $data['scripts'] = $this->template->homescripts([]);
        $data['footer'] = $this->template->homefooter([]);

        $this->load->view('politicaCookies', $data, FALSE);
    }

    private function views($qtd)
    {
        if ($qtd < 1000) {
            return (string)$qtd;
        } else if ($qtd < 1000000) {
            return ['valor' => intval($qtd / 1000), 'un' => 'K'];
        } else if ($qtd < 1000000000) {
            return ['valor' => intval($qtd / 1000000), 'un' => 'M'];
        } else {
            return ['valor' => intval($qtd / 1000000000), 'un' => 'B'];
        }
    }


    private function myQuery($fornecedor, $where)
    {

        return $this->db->query("SELECT CODIGO,
       ID_FORNECEDOR,
       ID_ESTADO,
       QUANTIDADE_UNIDADE,
       ESTOQUE,
       ESTOQUE_TOTAL,
       PRECO_UNITARIO,
       PRECO_TOTAL_GERAL,
       PRECO_TOTAL_ONCOPROD,
       LOTE,
       VALIDADE

        FROM (SELECT pl.codigo,
                     pl.id_fornecedor,
                     pp.id_estado,
                     pc.quantidade_unidade,
                     pl.estoque,
                     (pl.estoque * pc.quantidade_unidade) estoque_total,
                     pp.preco_unitario,
                     (CASE
                          WHEN pl.id_fornecedor NOT IN (12, 111, 112, 115, 120, 123) then (pl.estoque * pp.preco_unitario)
                          else NULL END)                  preco_total_geral,
                     (CASE
                          WHEN pl.id_fornecedor IN (12, 111, 112, 115, 120, 123) then (pl.estoque * pp.preco_unitario)
                          else NULL END)                  preco_total_oncoprod,
                     pl.lote,
                     pl.validade
        
              FROM pharmanexo.produtos_lote pl
        
                       JOIN pharmanexo.produtos_catalogo pc
                            on pc.codigo = pl.codigo
                                and pc.id_fornecedor = pl.id_fornecedor
                                and pc.ativo = 1
                                and pc.bloqueado = 0
        
                       JOIN pharmanexo.produtos_preco pp
                            on pp.codigo = pl.codigo
                                and pp.id_fornecedor = pl.id_fornecedor
        
        
              where pl.id_fornecedor = {$fornecedor}
                and pp.id_estado {$where}
        
                AND pp.data_criacao = (CASE
                                           WHEN ISNULL(pp.id_estado) then
                                               (select max(pp2.data_criacao)
                                                from pharmanexo.produtos_preco pp2
                                                where pp2.id_fornecedor = pl.id_fornecedor
                                                  and pp2.codigo = pl.codigo
                                                  and pp2.id_estado is null)
        
                                           ELSE
                                               (select max(pp2.data_criacao)
                                                from pharmanexo.produtos_preco pp2
                                                where pp2.id_fornecedor = pl.id_fornecedor
                                                  and pp2.codigo = pl.codigo
                                                  and pp2.id_estado = pp.id_estado)
                  END)
        
              GROUP BY pl.codigo,
                       pl.id_fornecedor,
                       pp.id_estado,
                       pc.quantidade_unidade,
                       pl.estoque,
                       pp.preco_unitario,
                       pl.lote,
                       pl.validade
                       
                       HAVING estoque > 0) x
                ")->result_array();

    }

    public function getTotal()
    {

        $precoTotal = [];
        $result = [];
        $preco_total = 0;

        $fornecedores = $this->db->select('id')->get('fornecedores')->result_array();

        foreach ($fornecedores as $fornecedor) {

            $preco_geral = 0;
            $preco_oncoprod = 0;

            $where = '';

            $id_fornecedor = intval($fornecedor['id']);

            $uf_fornecedor = $this->db->where('id', $id_fornecedor)->get('fornecedores')->row_array()['estado'];

            $estado = $this->db->where('uf', $uf_fornecedor)->get('estados')->row_array()['id'];

            if (IS_NULL($estado))
                continue;

            if ($id_fornecedor == 20) {

                $where = 'is null';

            }
            if ($id_fornecedor == 104) {

                $where = "= {$estado}";

            } else if ($id_fornecedor == 15 || $id_fornecedor == 25 || $id_fornecedor == 180) {

                $where = "= {$estado}";

            } else if (in_array($id_fornecedor, $this->oncoprod)) {

                if ($id_fornecedor == 112) {
                    $where = '= 9';

                } else {
                    $where = "= {$estado}";
                }

            } else {

                $where = 'is null';
            }

            $arrayPrecos = $this->myQuery($id_fornecedor, $where);

            foreach ($arrayPrecos as $preço) {

                $preco_geral += $preço['PRECO_TOTAL_GERAL'];
                $preco_oncoprod += $preço['PRECO_TOTAL_ONCOPROD'];
            }

            if (in_array($id_fornecedor, $this->oncoprod)) {

                $precoTotal[] = [
                    'fornecedor' => $id_fornecedor,
                    'preco_total' => floatval($preco_oncoprod)
                ];

            } else {

                $precoTotal[] = [
                    'fornecedor' => $id_fornecedor,
                    'preco_total' => floatval($preco_geral)
                ];
            }
        }

        foreach ($precoTotal as $preco) {

            $preco_total += $preco['preco_total'];
        }


        if ($preco_total != 0) {

            $result = [
                'response' => true,
                'total' => $preco_total
            ];
        } else {
            $result = [
                'response' => true,
                'total' => '0,00'
            ];
        }

        return $result;
    }


    public function getCnpj()
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            if (isset($post['cnpj']) && !empty($post['cnpj'])) {

                $consultaFornecedor = $this->db->where('cnpj', $post['cnpj'])->get('fornecedores')->row_array();

                if (!empty($consultaFornecedor) && $consultaFornecedor['finalizado'] == 0) {
                    $step = $consultaFornecedor['step'];

                    switch (intval($step)) {
                        case 1:

                            $output = [
                                'nextStep' => 2,
                                'urlStep' => "{$this->route}/step/2",
                                'data' => $consultaFornecedor
                            ];
                            break;

                        case 2:
                            $output = [
                                'nextStep' => 3,
                                'urlStep' => "{$this->route}/step/3",
                                'data' => $consultaFornecedor
                            ];
                            break;
                    }

                } else {
                    $cnpj = soNumero($post['cnpj']);

                    // Iniciamos a função do CURL:
                    $ch = curl_init("https://receitaws.com.br/v1/cnpj/{$cnpj}");

                    curl_setopt_array($ch, [
                        CURLOPT_CUSTOMREQUEST => 'GET',
                        CURLOPT_HTTPHEADER => [
                            'Auth: 84353f174a4cdd90ce96a58ec0768e8174df95874c6988dc22ec1eb3e6284882'
                        ],
                        CURLOPT_RETURNTRANSFER => 1,
                    ]);

                    $array = json_decode(curl_exec($ch), true);
                    curl_close($ch);



                    if (isset($array['none'])) {
                        $post['razao_social'] = $array['none'];
                    }

                    if (isset($array['fantasia'])) {
                        $post['nome_fantasia'] = $array['fantasia'];
                    }

                    $output = [
                        'nextStep' => 1,
                        'urlStep' => "{$this->route}/novoRegistro",
                        'data' => $post
                    ];


                }

            }

            if (!empty($output)) {

                $_SESSION['novoCadastro'] = $output;
                $this->output->set_content_type('application/json')->set_output(json_encode($output));

            }


        }
    }

}