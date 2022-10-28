<?php

class CheckRotinas extends CI_Controller
{

    private $fornecedores;
    private $configTime;
    private $dateNow;
    private $template;

    private $bio;

    public function __construct()
    {
        parent::__construct();

        $this->bio = $this->load->database('bionexo', true);

        $this->load->library('Notify', 'notify');

        $this->load->model('Engine');
        $this->load->model('Fornecedor');

        $this->dateNow = new DateTime(date('Y-m-d H:i:s'));

        $this->configTime =
            [
                'automatica' => 1440
            ];

        //	$this->fornecedores = $this->Fornecedor->matrizFilial();

        $this->template = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/pharma_api/public/template/body_mail.html');

    }

    private function createMirror($array)
    {

        $folder = $_SERVER['DOCUMENT_ROOT'] . "/pharma_api/public/check_rotinas/exports/";

        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $filename = $folder . $array['rotina'] . "_" . time() . ".pdf";

        $codigo = "Código";

        if ($array['fornecedor'] == 'ONCOPROD')
            $codigo = "Cód. Kraft";

        $table = "";

        $row = "<strong>{$array['title']}</strong>";

        $row .= "
            <table style='width:100%; border: 1px solid #dddddd; border-collapse: collapse; margin-top: 30px'>
           
            <tr>
                <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>{$codigo}</th> 
                <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Nome Comercial</th>
                <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Apresentação</th>
                <th style='border: 1px solid #dddddd; text-align: left; padding-right: 20px'>Marca</th>
                
            </tr>
            ";

        foreach ($array['produtos'] as $produto) {

            $row .= "
                    <tr>
                        <td style='border: 1px solid #dddddd; padding-right: 20px'>{$produto['codigo']}</td>
                        <td style='border: 1px solid #dddddd; padding-right: 20px'>{$produto['nome_comercial']}</td>
                        <td style='border: 1px solid #dddddd; padding-right: 20px'>{$produto['apresentacao']}</td>
                        <td style='border: 1px solid #dddddd; padding-right: 20px'>{$produto['marca']}</td>
                    </tr>
                ";
        }

        $row .= "</table>";
        $table .= $row;

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($table);
        $mpdf->Output($filename, 'F');

        return
            [
                'body' => $table,
                'file' => $filename
            ];

    }

    public function cotAutomatica()
    {

        foreach ($this->fornecedores as $fornecedor) {

            $getEnvCots = $this->Engine->getEnvCots(
                [
                    'nivel' => 2,
                    'id_fornecedor' => $fornecedor['id']
                ]);

            if (empty($getEnvCots)) {

                continue;
            }

            $dateStart = new DateTime($getEnvCots[0]['data_criacao']);

            $dateDiff = $dateStart->diff($this->dateNow);

            $dias = ($dateDiff->days * 24 * 60);

            $horas = ($dateDiff->h * 60);

            ($dias == 0) ? $dias = 0 : $dias = $dias;

            ($horas == 0) ? $horas = 0 : $horas = $horas;

            $tempo_total = ($dias + $horas + $dateDiff->i);

            if ($tempo_total > $this->configTime['automatica']) {

                $msg = "As Cotações Automáticas do fornecedor {$fornecedor['nome_fantasia']} não são executadas a: {$dateDiff->days} dias, {$dateDiff->h} horas e {$dateDiff->i} minutos.";

                echo $msg;

                echo PHP_EOL;

            }

        }
    }

    public function prodsNoPrice()
    {
        $prodNoPrice = $this->Engine->getProdsNoPrice('12,111,112,115,120,123,126');

        $title = "Identificamos produtos sem preço no catálogo, verifique com sua TI o ocorrido, 
				até que isso seja regularizado as ofertas podem ser rejeitadas pelo Portal Sintese.";

        if (!empty($prodNoPrice)) {

            $arr = [
                'rotina' => 'produtos_ativos_sem_preco',
                'title' => $title,
                'fornecedor' => '',
                'filiais' => 'ONCOPROD',
                'produtos' => $prodNoPrice
            ];

            $bodyMirror = $this->createMirror($arr);

            $body = str_replace(["%body%"], [$bodyMirror['body']], $this->template);
            $array = [

                "from" => "no-reply@pharmanexo.com.br",
                "from-name" => "Portal Pharmanexo",
                "assunto" => "PRODUTOS SEM PREÇO CATÁLAGO",
                "destinatario" => "marlon.boecker@pharmanexo.com.br, administracao@pharmanexo.com.br",
                "msg" => $body,
                "anexo" => $bodyMirror['file']
            ];

            $this->Engine->sendEmail($array);
        }
    }

    public function prodsNoMarca()
    {
        foreach ($this->fornecedores as $key => $fornecedor) {

            $prodsNoMarca = $this->Engine->getProdsNoMarca($fornecedor);

            $title = "Identificamos produtos sem marca no catálogo, verifique com sua TI o ocorrido, 
				até que isso seja regularizado as ofertas podem ser rejeitadas pelo Portal Sintese.";

            if (!empty($prodsNoMarca)) {

                $arr = [
                    'rotina' => 'produtos_ativos_sem_marca',
                    'title' => $title,
                    'fornecedor' => $key,
                    'filiais' => $fornecedor,
                    'produtos' => $prodsNoMarca
                ];

                $bodyMirror = $this->createMirror($arr);

                $body = str_replace(["%body%"], [$bodyMirror['body']], $this->template);

                $array = [

                    "from" => "no-reply@pharmanexo.com.br",
                    "from-name" => "Portal Pharmanexo",
                    "assunto" => "PRODUTOS SEM MARCA CATÁLAGO [{$key}]",
                    "destinatario" => "chulesantos@outlook.com",
                    "copia_o" => "chule.cabral@pharmanexo.com.br",
                    "msg" => $body,
                    "anexo" => $bodyMirror['file']
                ];

                $this->notify->sendEmail($array);

            } else {
                continue;
            }
        }
    }

    public function prodsVencimento()
    {
        foreach ($this->fornecedores as $key => $fornecedor) {

            $prodsVencimento = $this->Engine->getProdsVencimento($fornecedor);

            $title = "Identificamos que existem produtos próximos a data de vencimento, verifique o catálogo.";

            if (!empty($prodsVencimento)) {

                $arr = [
                    'rotina' => 'produtos_em_vencimento',
                    'title' => $title,
                    'fornecedor' => $key,
                    'filiais' => $fornecedor,
                    'produtos' => $prodsVencimento
                ];

                $bodyMirror = $this->createMirror($arr);

                $body = str_replace(["%body%"], [$bodyMirror['body']], $this->template);

                $array = [

                    "from" => "no-reply@pharmanexo.com.br",
                    "from-name" => "Portal Pharmanexo",
                    "assunto" => "PRODUTOS EM VENCIMENTO [{$key}]",
                    "destinatario" => "chulesantos@outlook.com",
                    "copia_o" => "chule.cabral@pharmanexo.com.br",
                    "msg" => $body,
                    "anexo" => $bodyMirror['file']
                ];

                $this->notify->sendEmail($array);

            } else {
                continue;
            }
        }
    }

    public function rotinaEric()
    {

        $cotacoes = $this->bio->get('cotacoes')
            ->result_array();

        foreach ($cotacoes as $cotacao) {
            $cnpj_comprador = str_replace('.', '', str_replace('-', '', str_replace('/', '', $cotacao['cd_comprador'])));

            $this->bio->where('id', $cotacao['id'])
                ->set('cd_comprador', $cnpj_comprador)
                ->update('cotacoes');
        }


    }
} // class

