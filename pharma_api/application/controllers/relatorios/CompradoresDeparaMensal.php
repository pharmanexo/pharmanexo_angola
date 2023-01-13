<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CompradoresDeparaMensal extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $anexos = [];
        $mes_atual = date("m");
        $ano_atual = date("Y");
        $filtro_ano = $ano_atual;
        $filtro_mes = ($mes_atual - 1);


        if ($mes_atual == '01') {
            $filtro_ano = ($ano_atual - 1);
            $filtro_mes = 12;
        }

        $fornecedoresAgrupados = [];
        $fornecedores = $this->db->query("
                        select f.id, f.nome_fantasia, f.id_matriz, pm.nome
                            from fornecedores f
                            left join fornecedores_matriz pm on pm.id = f.id_matriz
                            where f.id in (select id_fornecedor
                                         from cotacoes_produtos
                                         where month(data_criacao) = '{$filtro_mes}'
                                           and year(data_criacao) = '{$filtro_ano}'
                                         group by id_fornecedor
                            )
        ")->result_array();


        foreach ($fornecedores as $fornecedor) {
            if (!empty($fornecedor['nome'])) {
                $fornecedoresAgrupados[$fornecedor['nome']][] = $fornecedor;
            } else {
                $fornecedoresAgrupados['outros'][] = $fornecedor;
            }

        }

        foreach ($fornecedoresAgrupados as $k => $forn) {

            if ($k != 'outros') {
                $ids = [];
                foreach ($forn as $id) {
                    $ids[] = $id['id'];
                }

                $filter = [
                    'ids' => implode(",", $ids),
                    'fornecedor' => $k,
                    'mes' => $filtro_mes,
                    'ano' => $filtro_ano
                ];

                $anexos[] = $this->output_csv($filter);

            } else {
                foreach ($forn as $id) {

                    $filter = [
                        'ids' => "{$id['id']}",
                        'fornecedor' => $id['nome_fantasia'],
                        'mes' => $filtro_mes,
                        'ano' => $filtro_ano
                    ];

                    $anexos[] = $this->output_csv($filter);
                }
            }

        }

        $errorMsg = [
            "to" => "marlon.boecker@pharmanexo.com.br, administracao@pharmanexo.com.br",
            "greeting" => "",
            "anexos" => $anexos,
            "subject" => "Relatório Mensal - Cotações geral por distribuidor",
            "message" => "Segue anexos planilhas do periodo: {$filtro_mes}/{$filtro_ano}"
        ];

        $send = $this->notify->sendRel($errorMsg);
        if ($send){
            foreach ($anexos as $anexo){
                unlink($anexo);
            }
        }

    }


    public function output_csv($filter)
    {


        $filename = str_replace(" ", '_', $filter['fornecedor']);
        $time = "{$filter['mes']}_{$filter['ano']}";
        $name = "public/relatorios/Rel_{$filename}_{$time}";
        $fullname = "{$name}.csv";
        $file = fopen($fullname, 'w+');


        $data = [];
        $sint = $this->load->database('sintese', true);

        $cotacoesProdutos = $sint
            ->select('ct.cd_cotacao, ct.id_cliente, id_produto_sintese, cd_produto_comprador')
            ->from('cotacoes ct')
            ->join('cotacoes_produtos cp', 'ct.id_fornecedor = cp.id_fornecedor and ct.cd_cotacao = cp.cd_cotacao')
            ->where("cp.id_fornecedor in ({$filter['ids']})
                                and month(ct.data_criacao) = '{$filter['mes']}'
                                and year(ct.data_criacao) = '{$filter['ano']}'
                                "
            )
            ->group_by('ct.cd_cotacao, id_produto_sintese, cd_produto_comprador')
            ->get()
            ->result_array();

        $clientes = [];

        foreach ($cotacoesProdutos as $produto) {
            $clientes[$produto['id_cliente']][$produto['cd_cotacao']][] = $produto;
        }


        foreach ($clientes as $k => $cotacoes) {

            $cliente = $this->db->where('id', $k)->get('compradores')->row_array();
            $data[$k]['cnpj_comprador'] = $cliente['cnpj'];
            $data[$k]['comprador'] = (!empty($cliente['none_fantasia'])) ? $cliente['none_fantasia'] : $cliente['razao_social'];
            $data[$k]['uf_comprador'] = $cliente['estado'];


            $data[$k]['cotacoes'] = count($cotacoes);
            $cotsDepara = 0;
            $cotsOfertas = 0;
            $itensOfertados = 0;
            $totalProds = 0;
            foreach ($cotacoes as $j => $produtos) {

                $totalProds = ($totalProds + count($produtos));

                foreach ($produtos as $produto) {
                    $depara = $this->db
                        ->select('pms.id_produto')
                        ->from('produtos_fornecedores_sintese pfs')
                        ->join('produtos_marca_sintese pms', 'pfs.id_sintese = pms.id_sintese')
                        ->where('pms.id_produto', $produto['id_produto_sintese'])
                        ->where("pfs.id_fornecedor in ({$filter['ids']})")
                        ->group_by('pfs.cd_produto')
                        ->get()
                        ->result_array();

                    if (!empty($depara)) {
                        $cotsDepara++;
                        break;
                    }
                }

                $ofertas = $this->db
                    ->where('cd_cotacao', $j)
                    ->where("id_fornecedor in ({$filter['ids']})")
                    ->where('submetido', 1)
                    ->get('cotacoes_produtos');

                $count = $ofertas->num_rows();
                $itensOfertados = ($itensOfertados + $count);

                if ($count > 0) {
                    $cotsOfertas++;
                }


            }
            $data[$k]['depara'] = $cotsDepara;
            $data[$k]['itens'] = $totalProds;
            $data[$k]['ofertadas'] = $cotsOfertas;
            $data[$k]['itens_ofertados'] = $itensOfertados;

        }

        fputcsv($file, ['CNPJ', 'COMPRADOR', 'UF', 'ABERTAS', 'C/DEPARA', 'ITENS', 'OFERTADAS', 'ITENS OFERTADOS']);


        foreach ($data as $k => $line) {
            fputcsv($file, $line);
        }

        fclose($file);

        return $this->convertToXls($fullname, $name);

    }


    private function convertToXls($file, $name)
    {

        $fullname = $name . '.xlsx';
        $spreadsheet = new Spreadsheet();
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();

        /* Set CSV parsing options */

        $reader->setDelimiter(',');
        $reader->setEnclosure('"');
        $reader->setSheetIndex(0);

        /* Load a CSV file and save as a XLS */

        $spreadsheet = $reader->load($file);
        $writer = new Xlsx($spreadsheet);
        $writer->save($fullname);

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
        unlink($file);
        return $fullname;
    }
}