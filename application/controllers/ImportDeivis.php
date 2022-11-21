<?php

class ImportDeivis extends CI_Controller
{

    private $mix;

    public function __construct()
    {
        parent::__construct();
        $this->mix = $this->load->database('mix', true);
    }

    public function updatePrecoMix()
    {
        $taxa = 0.1089;

        $precos = $this->mix
            ->where_in('id_fornecedor', [12, 111, 112, 115, 120, 123, 126])
            ->where("data_atualizacao < '2022-03-30' ")
            ->get('produtos_preco_mix')->result_array();

        foreach ($precos as $preco) {


            $precoBase = doubleval($preco['preco_base']);
            $novoPreco = $precoBase + ($precoBase * $taxa);

            $this->mix
                ->where('codigo', $preco['codigo'])
                ->where('id_fornecedor', $preco['id_fornecedor'])
                ->where('id_cliente', $preco['id_cliente'])
                ->where('data_atualizacao', $preco['data_atualizacao'])
                ->update('produtos_preco_mix', ['preco_base' => $novoPreco]);

        }


    }

    public function gerarChaveSintese()
    {
        $fornecedores = $this->db
            ->where('sintese', '1')
            //  ->where('chave_sintese is null')
            ->get('fornecedores')->result_array();

        foreach ($fornecedores as $fornecedor) {

            $data = [
                'chave_sintese' => md5(soNumero($fornecedor['cnpj']))
            ];

            $this->db->where('id', $fornecedor['id'])->update('fornecedores', $data);

        }

    }

    public function arvore_sistema()
    {
        $dir_iterator = new RecursiveDirectoryIterator("./");
        $iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);
        $Regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);


        foreach ($Regex as $file) {
            foreach ($file as $final) {
                echo $final, "<br/>";
            }
        }

    }

    private function recursivo($caminho, $dirName)
    {

        global $dirName;

        $DI = new DirectoryIterator($caminho);

        foreach ($DI as $file) {
            if (!$file->isDot()) {
                if ($file->isFile()) {
                    //
                    $fileName = $file->getFilename();
                    //
                    echo $dirName . ": " . $fileName . "<br>";
                }
            }

        }
    }

    public function acertoVD()
    {

        $fornecedores = [111, 126];
        $compradores = [452];

        $db = $this->load->database('mix', true);

        $produtos = $db->query('select distinct codigo from mix.produtos_preco_mix')->result_array();

        $insert = [];

        foreach ($fornecedores as $fornecedor) {
            foreach ($compradores as $comprador) {
                foreach ($produtos as $produto) {

                    $ver = $this->db
                        ->where('id_cliente', $comprador)
                        ->where('id_fornecedor', $fornecedor)
                        ->where('codigo', $produto['codigo'])
                        ->where('regra_venda in (2,3)')
                        ->get('vendas_diferenciadas')
                        ->result_array();

                    if (empty($ver)) {
                        $insert[] = [
                            'id_cliente' => $comprador,
                            'id_fornecedor' => $fornecedor,
                            'codigo' => $produto['codigo'],
                            'regra_venda' => 3
                        ];
                    }

                }
            }
        }

        $this->db->insert_batch('vendas_diferenciadas', $insert);


    }

    public function sendMail()
    {
        $sendError = $this->notify->send([
            "to" => 'marlon.boecker@pharmanexo.com.br',
            "greeting" => "",
            "subject" => "Erro ao enviar espelho da cotação {#cd_cotacao}",
            "message" => "teste",
            "oncoprod" => 1,
        ]);

    }

    public function index()
    {
        $file = fopen('catalogo_oncoprod.csv', 'r');
        $produtos = [];
        $insert = [];

        $oncoprod = [12, 111, 112, 115, 120, 123, 126];
        while (($line = fgetcsv($file, null, ',')) !== false) {

            $produtos[] = $line;

        }


        foreach ($oncoprod as $k => $id) {

            foreach ($produtos as $line) {

                if (strtoupper($line[1]) !== 'TRIBUTADO' && strtoupper($line[1]) !== 'ISENTO' && strtoupper($line[1]) !== 'CONVENIO 118') {

                    if (strtoupper($line[1]) == 'SP') {

                        if ($id == '115') {
                            $line[1] = 'TRIBUTADO';
                        } else {
                            $line[1] = 'CONVENIO 118';
                        }
                    }

                    if (strtoupper($line[1]) == 'DF/SP') {
                        if ($id == '115' || $id == '126' || $id == '120') {
                            $line[1] = 'TRIBUTADO';
                        } else {
                            $line[1] = 'CONVENIO 118';
                        }
                    }

                }

                $insert[$id][] = [
                    'codigo' => $line[0],
                    'id_fornecedor' => $id,
                    'classe' => $line['1'],
                    'origem' => (isset($line[2])) ? $line[2] : ''
                ];
            }


        }

        foreach ($insert as $k => $codigos) {

            foreach ($codigos as $prod) {
                $this->db->where('id_fornecedor', $k);
                $this->db->where('codigo', $prod['codigo']);
                $this->db->set('classe', $prod['classe']);
                $this->db->set('origem', $prod['origem']);
                $this->db->update('produtos_catalogo');
            }


        }

        fclose($file);


    }


    public function fourbioo()
    {
        $file = fopen('depara_4bio.csv', 'r');
        $usuarios = [];
        $insert = [];

        $forns = [5042, 5043, 5044];
        while (($line = fgetcsv($file, null, ';')) !== false) {
            $codigo = $line[0];
            $id_produto = $line[2];

            //get id_sintese
            $prodSint = $this->db
                ->where('id_produto', $id_produto)->limit(1)
                ->get('produtos_marca_sintese')
                ->row_array();

           if (!empty($prodSint['id_sintese'])){

               $id_sintese = $prodSint['id_sintese'];

               foreach ($forns as $forn){
                   $insert[] = [
                       'id_sintese' => $id_sintese,
                       'id_usuario' => 999,
                       'cd_produto' => $codigo,
                       'id_pfv' => $codigo,
                       'id_fornecedor' => $forn
                   ];
               }
           }
        }

        $this->db->insert_batch('produtos_fornecedores_sintese', $insert);



    }

    public function usuario_oncoprod()
    {
        $file = fopen('usuarios.csv', 'r');
        $usuarios = [];
        $insert = [];

        $oncoprod = [12, 111, 112, 115, 120, 123, 126];
        while (($line = fgetcsv($file, null, ',')) !== false) {

            $usuarios[] = $line;

        }

        foreach ($oncoprod as $id) {
            foreach ($usuarios as $usuario) {
                $data = [
                    'id_fornecedor' => $id,
                    'nome' => $usuario[1],
                    'usuario' => $usuario[0]
                ];

                $this->db->insert('usuarios_resgate', $data);
            }
        }

    }

    public function ofertas_sintese()
    {
        $file = fopen('produtos_ofertados_sintese.csv', 'r');
        $insert = [];

        while (($line = fgetcsv($file, null, ',')) !== false) {

            if ($line['0'] != 'CODIGO') {

            } else {
                continue;
            }

            $insert = [
                'codigo' => $line[0],
                'produtos' => substr($line[1], 2),
                'unidade' => $line[2],
                'marca' => $line[3],
                'valor' => dbNumberFormat(str_replace("R$ ", "", $line[4])),
                'qtd_embalagem' => $line[5],
                'qtd_solicitada' => $line[6],
                'cotacao' => $line[7],
                'ordem_compra' => $line[8],
                'fornecedor' => substr($line[9], 2),
                'data_cotacao' => date('Y-m-d', strtotime($line[10])),
                'hospital' => substr($line[11], 2),
            ];

            $this->db->insert('produtos_ofertados_sintese', $insert);

        }


    }

    public function clientesLlondricir()
    {
        $file = fopen('clientes_londricir.csv', 'r');
        $insert = [];

        while (($line = fgetcsv($file, null, ',')) !== false) {

            $cnpj = mask($line[0], '##.###.###/####-##');
            $cliente = $this->db->where('cnpj', $cnpj)->get('compradores')->row_array();
            $user = $this->db->where('usuario_externo', $line[1])->get('usuarios')->row_array();


            if (!empty($cliente) && !empty($user)) {
                $insert = [
                    "id_usuario" => $user['id'],
                    "id_cliente" => $cliente['id'],
                    "id_fornecedor" => 5039
                ];
            }


            $this->db->insert('usuarios_rede_atendimento', $insert);

        }


    }

    public function preco_medio()
    {
        $file = fopen('preco_medio.csv', 'r');
        $insert = [];

        while (($line = fgetcsv($file, null, ',')) !== false) {

            $insert[] = [
                'produto' => $line[0],
                'marca' => $line[2],
                'quantidade_embalagem' => $line[3],
                'preco_medio' => dbNumberFormat(str_replace("R$ ", "", $line[4]))
            ];

        }

        $this->db->insert_batch('preco_medio', $insert);

    }

    public function panfarma()
    {
        $file = fopen('produtos_panpharma.csv', 'r');
        $insert = [];

        while (($line = fgetcsv($file, null, ',')) !== false) {

            $this->db->where('id_fornecedor', 5037);
            $this->db->where('codigo', $line[0]);
            $existe = $this->db->get('produtos_catalogo')->row_array();

            if (!empty($existe)) {
                $this->db->where('id_fornecedor', 5037);
                $this->db->where('codigo', $line[0]);
                $this->db->update('produtos_catalogo', ['quantidade_unidade' => $line['6']]);
            } else {

                $insert[] = [
                    'id_fornecedor' => 5037,
                    'codigo' => $line[0],
                    'nome_comercial' => $line[1],
                    'ean' => $line[2],
                    'marca' => $line[3],
                    'quantidade_unidade' => $line[6],
                    'ativo' => 1
                ];

            }

        }

        $this->db->insert_batch('produtos_catalogo', $insert);

    }

    public function mapa()
    {
        $file = fopen('mapa.csv', 'r');
        $itens = [];
        $insert = [];

        $oncoprod = [12, 111, 112, 115, 120, 123, 126];
        while (($line = fgetcsv($file, null, ',')) !== false) {

            $itens[] = [
                'icms' => $line[0],
                'classe' => $line[1],
                'origem' => $line[2],
                'id_estado' => $this->db->where('uf', $line[3])->get('estados')->row_array()['id'],
                'id_fornecedor' => $line[4]
            ];


        }

        unset($itens[0]);

        $this->db->insert_batch('mapa_logistico', $itens);

    }

    public function precos_oncoprod()
    {
        $file = fopen('preco_fixo_oncoprod.csv', 'r');
        $usuarios = [];
        $insert = [];

        while (($line = fgetcsv($file, null, ',')) !== false) {

            $cliente = $this->db->select('id')->where('cnpj', $line['0'])->get('compradores')->row_array();

            $insert[] = [
                'codigo' => $line[2],
                'id_fornecedor' => 115,
                'id_cliente' => $cliente['id'],
                'preco_mix' => '0.0000',
                'preco_base' => '',
                'preco_fixo' => 0,
                'margem_minima' => '0.00',
                'margem_maxima' => '0.00',
            ];
        }
    }

    public function oncoprod()
    {

        $file = fopen('compradores_oncoprod_n.csv', 'r');
        $forn = [12, 111, 112, 115, 120, 123, 126];
        $insert = [];

        while (($line = fgetcsv($file, null, ',')) !== false) {
            $cmp = $this->db->where('cnpj', $line[0])->get('compradores')->row_array();

            foreach ($forn as $f) {
                $insert[] = [
                    'id_fornecedor' => $f,
                    'id_cliente' => $cmp['id'],
                    'id_tipo_venda' => 3,
                    'regra_venda' => 3
                ];

            }

        }

        $this->db->insert_batch('controle_cotacoes', $insert);

    }

    public function mill()
    {
        $file = fopen('nao_medicamentos.csv', 'r');
        $insert = [];
        $id_fornecedor = 5034;
        while (($line = fgetcsv($file, null, ',')) !== false) {

            $exist = $this->db->where('codigo', $line[0])->where('id_fornecedor', $id_fornecedor)->get('produtos_catalogo')->row_array();

            if (empty($exist)) {
                $insert[] = [
                    'codigo' => $line[0],
                    'nome_comercial' => $line[1],
                    'descricao' => $line[2],
                    'marca' => $line[3],
                    'rms' => $line[4],
                    'ean' => $line[5],
                    'unidade' => 'UN',
                    'quantidade_unidade' => $line[7],
                    'id_fornecedor' => $id_fornecedor,
                    'ativo' => 1
                ];
            }
        }

        $this->db->insert_batch('produtos_catalogo', $insert);

    }

    public function precosMill()
    {
        $file = fopen('precos_mill_rj.csv', 'r');
        $precos = [];
        $existe = [];
        $id_fornecedor = 5033;
        while (($line = fgetcsv($file, null, ',')) !== false) {

            $precos[] = [
                'codigo' => $line[0],
                'id_fornecedor' => $id_fornecedor,
                'id_estado' => 19,
                'preco_unitario' => dbNumberFormat(trim(str_replace('R$', '', $line[2])))
            ];

        }

        $this->db->insert_batch('produtos_preco', $precos);

    }

    public function controleCotacoes()
    {

        $file = fopen('Compradores.csv', 'r');
        $insert = [];
        $id_fornecedor = 20;
        while (($line = fgetcsv($file, null, ',')) !== false) {

            $comprador = $this->db->where('cnpj', $line[2])->get('compradores')->row_array();

            $insert[] = [
                'id_fornecedor' => $id_fornecedor,
                'id_cliente' => $comprador['id'],
                'id_tipo_venda' => 3,
                'regra_venda' => 0,
                'integrador' => 1
            ];

        }

        $this->db->insert_batch('controle_cotacoes', $insert);
    }

    public function pontaMed()
    {
        $file = fopen('nao_medicamentos.csv', 'r');
        $insert = [];
        $id_fornecedor = 5018;
        while (($line = fgetcsv($file, null, ',')) !== false) {

            $exist = $this->db->where('codigo', $line[0])->where('id_fornecedor', $id_fornecedor)->get('produtos_catalogo')->row_array();

            if (empty($exist)) {
                $insert[] = [
                    'codigo' => $line[0],
                    'nome_comercial' => $line[1],
                    'descricao' => $line[2],
                    'marca' => $line[3],
                    'rms' => $line[4],
                    'ean' => $line[5],
                    'unidade' => 'UN',
                    'quantidade_unidade' => $line[7],
                    'id_fornecedor' => $id_fornecedor,
                    'ativo' => 1
                ];
            }
        }

        $this->db->insert_batch('produtos_catalogo', $insert);

    }

    public function panpharma()
    {
        $file = fopen('panpharma.csv', 'r');
        $insert = [];
        $id_fornecedor = 5037;
        while (($line = fgetcsv($file, null, ',')) !== false) {

            $exist = $this->db->where('codigo', $line[0])->where('id_fornecedor', $id_fornecedor)->get('produtos_catalogo')->row_array();

            if (empty($exist)) {
                $insert[] = [
                    'codigo' => $line[0],
                    'nome_comercial' => $line[2],
                    'descricao' => '',
                    'marca' => $line[3],
                    'rms' => '',
                    'ean' => $line[1],
                    'unidade' => 'UN',
                    'quantidade_unidade' => 1,
                    'id_fornecedor' => $id_fornecedor,
                    'ativo' => 1
                ];
            }
        }

        $this->db->insert_batch('produtos_catalogo', $insert);

    }

    public function pontaMedUpdate()
    {
        $file = fopen('pontamed.csv', 'r');
        $insert = [];
        $id_fornecedor = 5018;
        while (($line = fgetcsv($file, null, ',')) !== false) {

            $update = [
                'descricao' => $line[1] . " " . $line[5],
            ];

            $this->db->where("id_fornecedor", $id_fornecedor);
            $this->db->where("codigo", intval(preg_replace('/[^0-9]/', '', $line[0])));
            $this->db->update('produtos_catalogo', $update);

        }


    }


    public function pontaMedEstoque()
    {
        $file = fopen('pontamed_estoque.csv', 'r');
        $insert = [];
        $id_fornecedor = 5018;
        while (($line = fgetcsv($file, null, ',')) !== false) {
            if ($line[0] != 'codigo') {

                $codigo = intval(preg_replace('/[^0-9]/', '', $line[0]));

                $insert[] = [
                    'codigo' => $codigo,
                    'lote' => $line[1],
                    'validade' => date("Y-m-d", strtotime($line[2])),
                    'estoque' => $line[3],
                    'id_fornecedor' => $id_fornecedor
                ];

            }

        }

        $this->db->insert_batch('produtos_lote', $insert);


    }

    public function geraSenha()
    {
        echo password_hash('Init123@2022', PASSWORD_DEFAULT);
    }

    public function pontaMedPrecos()
    {
        $file = fopen('pontamed_precos.csv', 'r');
        $insert = [];
        $id_fornecedor = 5018;


        while (($line = fgetcsv($file, null, ',')) !== false) {
            if ($line[0] != 'codigo') {

                $codigo = intval(preg_replace('/[^0-9]/', '', $line[0]));
                $estado = getEstado($line[2]);


                $insert[] = [
                    'codigo' => $codigo,
                    'id_fornecedor' => $id_fornecedor,
                    'id_estado' => $estado,
                    'preco_unitario' => dbNumberFormat(trim(str_replace('R$', '', $line[1])))
                ];

            }

        }

        /*   foreach ($insert as $item){
               var_dump($item);
               exit();
           }
           var_dump($insert);
           exit();*/

        $this->db->insert_batch('produtos_preco', $insert);


    }


    public function promoHospi()
    {
        $file = fopen('promo_hosp.csv', 'r');
        $insert = [];
        $id_fornecedor = 20;
        while (($line = fgetcsv($file, null, ',')) !== false) {

            $estados = $this->db->select('id_estado')->where('id_estado > 0')->where('id_fornecedor', 20)->group_by('id_estado')->get('vendas_diferenciadas')->result_array();
            $this->db->where('codigo', $line[0])->where('id_fornecedor', 20)->delete('vendas_diferenciadas');

            foreach ($estados as $estado) {

                $insert[] = [
                    'id_estado' => $estado['id_estado'],
                    'id_fornecedor' => 20,
                    'id_produto' => 0,
                    'id_tipo_venda' => 0,
                    'desconto_percentual' => 35,
                    'comissao' => 0,
                    'codigo' => $line[0],
                    'regra_venda' => 3
                ];

            }

        }

        $this->db->insert_batch('vendas_diferenciadas', $insert);
    }

    public function importSintese()
    {
        $file = fopen('produto_sintese.csv', 'r');
        $insert = [];
        $id_fornecedor = 5018;

        while (($line = fgetcsv($file, null, ',')) !== false) {

            $data['id_sintese'] = $line[0];
            $data['id_produto'] = $line[1];
            $data['descricao'] = $line[2];
            $data['id_marca'] = $line[3];
            $data['ativo'] = 1;

            $this->db->where('id_sintese', $data['id_sintese']);
            $this->db->where('id_produto', $data['id_produto']);
            $exist = $this->db->get('produtos_marca_sintese')->row_array();

            if (empty($exist)) {
                $insert[] = $data;
            }

        }

        $this->db->insert_batch('produtos_marca_sintese', $insert);


    }

    public function preco_minimo()
    {
        $file = fopen('preco_minimo_sintese_dist.csv', 'r');
        $insert = [];
        while (($line = fgetcsv($file, null, ',')) !== false) {
            if ($line['0'] == 'CODIGO') {
                continue;
            }
            $preco = dbNumberFormat(trim(str_replace('R$', '', $line[4])));
            $insert[] = [
                'codigo' => $line[0],
                'produto' => trim(html_entity_decode($line[1]), " \t\n\r\0\x0B\xC2\xA0"),
                'unidade' => $line[2],
                'marca' => $line[3],
                'preco' => $preco,
                'qtd' => $line[5],
                'cotacao' => $line[6],
                'data_cot' => date('Y-m-d', strtotime($line[7])),
                'hospital' => $line[8],
                'fornecedor' => $line[9],
                'total' => $preco * intval($line[5])
            ];

        }

        $this->db->insert_batch('preco_minimo_distribuidores', $insert);
    }

    public function importMarcas()
    {
        $file = fopen('marcas_sintese.csv', 'r');
        $insert = [];
        while (($line = fgetcsv($file, null, ',')) !== false) {
            $m = $this->db->where('id', $line[1])->get('marcas')->row_array();

            if (empty($m)) {
                $insert[] = [
                    'id' => $line[1],
                    'marca' => $line[0]
                ];
            }

        }

        $this->db->insert_batch('marcas', $insert);
    }

    public function importMateriais()
    {
        $file = fopen('materiais_sintese.csv', 'r');
        $insert = [];
        while (($line = fgetcsv($file, null, ',')) !== false) {
            $m = $this->db->where('cnpj', $line[0])->get('compradores')->row_array();

            if (!empty($m)) {
                $insert[] = [
                    'id_cliente' => $m['id'],
                    'cd_produto_comprador' => $line[1],
                    'ds_produto_comprador' => $line[2],
                    'id_produto' => $line[3]
                ];
            }

            /*  $this->db->insert_batch('produtos_materiais_sintese', $insert);
              exit();*/
        }

        $this->db->insert_batch('produtos_materiais_sintese', $insert);
    }


    public function hospidrogasPromos()
    {
        $file = fopen('promocoes_hospidrogas.csv', 'r');
        $insert = [];
        $id_fornecedor = 20;


        while (($line = fgetcsv($file, null, ',')) !== false) {
            if ($line[0] != 'codigo') {


                $estados = [5, 8, 9, 13, 19, 25];

                foreach ($estados as $estado) {
                    $insert[] = [
                        'id_estado' => $estado,
                        'id_fornecedor' => $id_fornecedor,
                        'codigo' => $line[0],
                        'desconto_percentual' => str_replace(["-", "%", ","], ["", "", "."], $line[2]),
                        'lote' => $line[1],
                        'promocao' => 1,
                        'regra_venda' => 3
                    ];
                }
            }

        }


        $this->db->insert_batch('vendas_diferenciadas', $insert);


    }


    public function catalogoGlobal()
    {
        $file = fopen('catalogo_global.csv', 'r');
        $insert = [];
        $id_fornecedor = 5038;
        $log = [];


        while (($line = fgetcsv($file, null, ',')) !== false) {
            if ($line[0] != 'Código') {


                $upd = [
                    'cd_produto' => $line[0],
                    'validado' => 1
                ];


                $this->db
                    ->where('cd_produto', $line[1])
                    ->where('validado is null')
                    ->where('id_fornecedor', $id_fornecedor)
                    ->update('produtos_fornecedores_sintese', $upd);

            }


        }


        //$this->db->insert_batch('vendas_diferenciadas', $insert);


    }


    public function fourbio()
    {
        $file = fopen('catalogo_4bio.csv', 'r');
        $insert = [];

        while (($line = fgetcsv($file, null, ',')) !== false) {

            if ($line['0'] == 'código') continue;

            $this->db->where('id_fornecedor', 5044);
            $this->db->where('codigo', $line[0]);
            $existe = $this->db->get('produtos_catalogo')->row_array();

            if (!empty($existe)) {
                $updade = [
                    'nome_comercial' => $line[7],
                    'apresentacao' => $line[1],
                    'descricao' => $line[3],
                    'ean' => $line[8],
                    'rms' => $line[4],
                    'marca' => $line[2],
                    'quantidade_unidade' => $line[6],
                    'unidade' => $line[5],
                    'ativo' => $line[9]
                ];

                $this->db
                    ->where_in('id_fornecedor', [5042, 5043, 5044])
                    ->where('codigo', $line[0])
                    ->update('produtos_catalogo', $updade);


            } else {

                foreach ([5042, 5043, 5044] as $id_forn) {
                    $insert[] = [
                        'id_fornecedor' => $id_forn,
                        'codigo' => $line[0],
                        'nome_comercial' => $line[7],
                        'apresentacao' => $line[1],
                        'descricao' => $line[3],
                        'ean' => $line[8],
                        'rms' => $line[4],
                        'marca' => $line[2],
                        'quantidade_unidade' => $line[6],
                        'unidade' => $line[5],
                        'ativo' => $line[9]
                    ];
                }

            }

        }

        $this->db->insert_batch('produtos_catalogo', $insert);

    }

    public function geraPrevisao()
    {
        $id_forn = 5010;
        $folder = APPPATH . "../uploads/arquivos/previsoes/{$id_forn}";

        $path = realpath($folder);

        while ($path == false) {
            mkdir($folder);
            $path = realpath($folder);
        }

        $estados = $this->db->get('estados')->result_array();

        foreach ($estados as $estado) {

            $query = "select pc.codigo,
                   pc.nome_comercial,
                   pc.quantidade_unidade,
                   sum(cp.qt_produto_total) as total,
                   (select preco_unitario
                    from pharmanexo.produtos_preco_max ppm
                    where ppm.codigo = pc.codigo
                      and ppm.id_fornecedor = {$id_forn}
                    limit 1) as preco,
                   c.estado
            from cotacoes_sintese.cotacoes ct
                     join cotacoes_sintese.cotacoes_produtos cp
                          on cp.cd_cotacao = ct.cd_cotacao and cp.id_fornecedor = ct.id_fornecedor
                     join pharmanexo.produtos_marca_sintese pms on pms.id_produto = cp.id_produto_sintese
                     join pharmanexo.produtos_fornecedores_sintese pfs
                          on pfs.id_sintese = pms.id_sintese and pfs.id_fornecedor = {$id_forn}
                     join produtos_catalogo pc on pc.id_fornecedor = {$id_forn} and pc.codigo = pfs.cd_produto
                     join compradores c on c.id = ct.id_cliente
            where ct.id_fornecedor = 20
              and ct.data_criacao between '2022-01-01' and '2022-05-01' and c.estado = '{$estado['uf']}'
            group by cp.id_produto_sintese, c.estado
            order by sum(cp.qt_produto_total) DESC";


            $data = $this->db->query($query)->result_array();


            if (!empty($data)) {


                $csv = fopen("uploads/arquivos/previsoes/{$id_forn}/Previsao_{$estado['uf']}.csv", 'w');
                foreach ($data as $item) {
                    $item['total'] = number_format($item['preco'] * $item['total'], 4, ',', '.');
                    $item['preco'] = number_format($item['preco'], 4, ',', '.');

                    $dt = [];
                    foreach ($item as $value) {
                        $dt[] = $value;
                    }

                    fputcsv($csv, $dt);

                }

                fclose($csv);

                /*$file = "previsao_{$estado['uf']}.csv";
                header( 'Content-type: application/csv' );
                header( 'Content-Disposition: attachment; filename='.$file );
                header( 'Content-Transfer-Encoding: binary' );
                header( 'Pragma: no-cache');

                $csv = fopen( 'php://output', 'w' );

                foreach ($data as $item){
                    $dt = [];

                    foreach ($item as $value){
                        $dt[] = $value;
                    }

                    fputcsv($csv, $dt);

                }
                fclose( $csv );*/
            }


        }


    }

    private function mask($val, $mask)
    {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k])) $maskared .= $val[$k++];
            } else {
                if (isset($mask[$i])) $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }


    public function importMedicamentos()
    {
        $file = fopen('csv/medicamentos_julho.csv', 'r');
        $insert = [];

        while (($line = fgetcsv($file, null, ',')) !== false) {
            if ($line[0] == 'CODIGO' || $line[0] == 'ID_ProdutoSintese') {
                continue;
            }

            $line[4] = str_replace("R$ ", "", $line[4]);

            $insert[] = [
                'codigo' => $line[0],
                'produto' => substr($line[1], '2'),
                'unidade' => trim($line[2]),
                'marca' => trim($line[3]),
                'preco' => dbNumberFormat(trim($line[4])),
                'data' => date("Y-m-d", strtotime($line['10'])),
                'qtd' => intval($line[6]),
                'cotacao' => trim($line[7]),
                'hospital' => trim($line[11]),
                'fornecedor' => trim($line[9]),
            ];
        }


        $this->db->insert_batch('precos_medicamentos', $insert);


    }

    public function importPrecoMedio()
    {
        $file = fopen('csv/medicamentos_setembro.csv', 'r');
        $insert = [];

        while (($line = fgetcsv($file, null, ',')) !== false) {
            if ($line[0] == 'CODIGO' || $line[0] == 'ID_ProdutoSintese') {
                continue;
            }

            $day = rand(5, 15);
            $line[5] = str_replace("R$ ", "", $line[5]);

            $insert[] = [
                'id_sintese' => $line[0],
                'produto' => substr($line[1], '0'),
                'unidade' => trim($line[2]),
                'marca' => trim($line[3]),
                'qtd' => intval($line[4]),
                'preco' => dbNumberFormat(trim($line[5])),
                'data' => date("Y-m-d", strtotime("-{$day} day")),
            ];
        }


        $this->db->insert_batch('precos_medicamentos', $insert);


    }

    public function depara4bio()
    {
        $depara = $this->db
            ->where('id_fornecedor', 5042)
            ->get('produtos_fornecedores_sintese')
            ->result_array();

        foreach ($depara as $item) {
            var_dump($item);
            exit();
        }

    }


    public function catalagoGlobal()
    {
        $file = fopen('Catalogo_Global_Novo.csv', 'r');
        $insert = [];

        while (($line = fgetcsv($file, null, ',')) !== false) {

            $existe = $this->db
                ->where('id_fornecedor', 5038)
                ->where('codigo', $line[0])
                ->get('produtos_catalogo')
                ->row_array();

            if (!empty($existe)) {

               if (strtolower($existe['marca']) == strtolower($line[2])){
                   var_dump($existe);
                   exit();
               }else{
                   echo "{$existe['marca']} | {$line[2]}";
                   exit();
               }

               exit();

                $updade = [
                    'nome_comercial' => $line[7],
                    'apresentacao' => $line[1],
                    'descricao' => $line[3],
                    'ean' => $line[8],
                    'rms' => $line[4],
                    'marca' => $line[2],
                    'quantidade_unidade' => $line[6],
                    'unidade' => $line[5],
                    'ativo' => $line[9]
                ];

                $this->db
                    ->where_in('id_fornecedor', [5042, 5043, 5044])
                    ->where('codigo', $line[0])
                    ->update('produtos_catalogo', $updade);


            } else {

                foreach ([5042, 5043, 5044] as $id_forn) {
                    $insert[] = [
                        'id_fornecedor' => $id_forn,
                        'codigo' => $line[0],
                        'nome_comercial' => $line[7],
                        'apresentacao' => $line[1],
                        'descricao' => $line[3],
                        'ean' => $line[8],
                        'rms' => $line[4],
                        'marca' => $line[2],
                        'quantidade_unidade' => $line[6],
                        'unidade' => $line[5],
                        'ativo' => $line[9]
                    ];
                }

            }

        }

    }

}
