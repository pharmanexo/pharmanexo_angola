<?php

if (!function_exists('getMotivosRecusa')) {
    function getMotivosRecusa($id = NULL)
    {
        $data = [
            1 => 'CLIENTE INADIMPLENTE',
            2 => 'CLIENTE INATIVO',
            3 => 'ITEM REGIONALIZADO',
            4 => 'NÃO TRABALHAMOS COM O ITEM',
            5 => 'SEM ESTOQUE',
            6 => 'OUTROS',
        ];


        if (isset($id)){
            return $data[$id];
        }else{
            return $data;
        }

    }
}


function getStatusPedidos($stts = null)
{

    $rows = [
        0 => "Cotações abertas",
        1 => "Cotações em análise do comprador",
        2 => "Cotações concluídas"
    ];

    return (isset($stts)) ? $rows[$stts] : $rows;
}

if (!function_exists('dbDateFormat')) {
    function dbDateFormat($date = NULL)
    {
        $d = explode('/', $date);
        if (is_array($d) && count($d) === 3) {
            return implode('-', array_reverse($d));
        } else {
            return false;
        }
    }
}


if (!function_exists('getEstado')) {
    function getEstado($uf)
    {

        $estados = [1 => 'AC',
            2 => 'AL',
            3 => 'AP',
            4 => 'AM',
            5 => 'BA',
            6 => 'CE',
            7 => 'DF',
            8 => 'ES',
            9 => 'GO',
            10 => 'MA',
            11 => 'MT',
            12 => 'MS',
            13 => 'MG',
            14 => 'PA',
            15 => 'PB',
            16 => 'PR',
            17 => 'PE',
            18 => 'PI',
            19 => 'RJ',
            20 => 'RN',
            21 => 'RS',
            22 => 'RO',
            23 => 'RR',
            24 => 'SC',
            25 => 'SP',
            26 => 'SE',
            27 => 'TO',
            28 => 'SPI'];

        return array_search($uf, $estados);

    }
}




if (!function_exists('soNumero')) {
    function soNumero($data)
    {
        return preg_replace('/[^0-9]/', '', $data);
    }
}

if (!function_exists('multi_unique')) {
    function multi_unique($array)
    {
        foreach ($array as $k => $na)
            $new[$k] = serialize($na);

        $uniq = array_unique($new);

        foreach ($uniq as $k => $ser)
            $new1[$k] = unserialize($ser);

        return ($new1);
    }
}

if (!function_exists('vai_dump')) {
    function vai_dump($param, $opt = TRUE)
    {

        if (is_array($param)) {

            $param = json_encode($param);
            $ext = 'json';
        } else {

            $ext = 'txt';
        }

        if ($opt) {

            $dir = $_SERVER['DOCUMENT_ROOT'] . '/pharmanexo';

            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            $newFile = fopen("{$dir}/vaiDump.{$ext}", "w+");

            fwrite($newFile, $param);

            fclose($newFile);
        } else {

            var_dump($param);
        }

        exit();
    }
}


if (!function_exists('statusPedidoRepresentante')) {
    function statusPedidoRepresentante($status = null)
    {
        $data = [1 => "Em aberto", "Enviado para análise", "Aguardando faturamento", "Faturado", "Cancelado", "Aprovado parcialmente", "Aguardando aprovação do comprador"];

        return (isset($status)) ? $data[$status] : $data;
    }
}

if (!function_exists('dateFormat')) {
    function dateFormat($date, $format)
    {
        return date($format, strtotime(str_replace('/', '-', $date)));
    }
}

if (!function_exists('status_regra_venda')) {
    function status_regra_venda($regra_venda = null)
    {
        $data = [
            0 => '<a data-toggle="tooltip" title="Todos os tipos"><i class="fas fa-keyboard">&nbsp;&nbsp;<i class="fas fa-robot">&nbsp;&nbsp;<i class="fas fa-network-wired"></i></a>',
            1 => '<a data-toggle="tooltip" title="Manual" ><i class="fas fa-keyboard"></i></a>',
            2 => '<a data-toggle="tooltip" title="Automático" ><i class="fas fa-robot"></i></a>',
            3 => '<a data-toggle="tooltip" title="Manual e Automático" ><i class="fas fa-keyboard"></i>&nbsp;&nbsp;<i class="fas fa-robot"></i></a>',
            4 => '<a data-toggle="tooltip" title="Distribuidor x Distribuidor"><i class="fas fa-network-wired"></i></a>',
            5 => '<a data-toggle="tooltip" title="Distribuidor x Manual" ><i class="fas fa-network-wired"></i>&nbsp;&nbsp;<i class="fas fa-keyboard"></i></a>',
            6 => '<a data-toggle="tooltip" title="Distribuidor x Automático" ><i class="fas fa-network-wired"></i>&nbsp;&nbsp;<i class="fas fa-robot"></i></a>',
            7 => '<a data-toggle="tooltip" title="Farma" ><i class="fas fa-keyboard"></i></a>',
        ];

        return (isset($regra_venda)) ? $data[$regra_venda] : '';
    }
}

if (!function_exists('array_format')) {
    function array_format($array)
    {
        if (!isset($array[0])) {

            $arrayCopy = $array;

            unset($array);

            $array[0] = $arrayCopy;
        }

        return $array;
    }
}

function mask($val, $mask)
{
    $maskared = '';
    $k = 0;
    for ($i = 0; $i <= strlen($mask) - 1; $i++) {
        if ($mask[$i] == '#') {
            if (isset($val[$k]))
                $maskared .= $val[$k++];
        } else {
            if (isset($mask[$i]))
                $maskared .= $mask[$i];
        }
    }
    return $maskared;
}

if (!function_exists('dbNumberFormat')) {
    function dbNumberFormat($value = NULL)
    {
        $v = str_replace(',', '.', str_replace('.', '', $value));

        if (is_numeric($v)) {
            return floatval($v);
        } else {
            return false;
        }
    }
}


function randomPassword()
{
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();
    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    // curl_setopt($curl, CURLOPT_USERPWD, "username:password");
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);
    return json_decode($result, true);
}

if (!function_exists('base64url_encode')) {
    function base64url_encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}

if (!function_exists('base64url_decode')) {
    function base64url_decode($data)
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
}

if (!function_exists('generatePassword')) {
    function generatePassword()
    {

        # gera uma nova senha

        # Letras minúsculas embaralhadas
        $smallLetters = str_shuffle('abcdefghijklmnopqrstuvwxyz');
        # Letras maiúsculas embaralhadas
        $capitalLetters = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        # Números aleatórios
        $numbers = (((date('Ymd') / 12) * 24) + mt_rand(800, 9999));
        $numbers .= 1234567890;
        # Caracteres Especiais
        $specialCharacters = str_shuffle('@#$');
        # Junta tudo
        $characters = $capitalLetters . $smallLetters . $numbers . $specialCharacters;
        # Embaralha e pega apenas a quantidade de caracteres informada no parâmetro
        $password = substr(str_shuffle($characters), 0, 8);

        return $password;

    }
}

if (!function_exists('nameMonth')) {
    function nameMonth($i)
    {

        $data = [
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro',
        ];

        return $data[$i];

    }
}

if (!function_exists('prioridadeTicket')) {
    function prioridadeTicket($i)
    {

        $data = [
            1 => 'Baixo',
            2 => 'Normal',
            3 => 'Alto',
            4 => 'Urgente'
        ];

        return $data[$i];

    }


}
if (!function_exists('ticketstatus')) {
    function ticketstatus()
    {

        $data = [
            0 => ' Aguardando interação do atendente',
            1 => ' Não iniciada',
            2 => ' Respondido, aguardando resposta do cliente',
            3 => ' Respondido pelo cliente, aguardando resposta',
            4 => ' Cancelada',
            5 => ' Finalizada',
            6 => ' Atendente modificado',
            7 => ' Enviada para equipe de apoio',
            8 => ' Aguardando avaliação do gerente',
            9 => ' Aguardando avaliação do gerente',
        ];

        return $data;

    }
}
