<?php

function loginBionexo(): array
{

    return
        [
            "HOSPIDROGAS" =>
                [
                    'id_fornecedor' => 20,
                    'user' => 'ws_hospidrogas_pharm',
                    'password' => '3fjwk3dm'
                ],
            "EXOMED" =>
                [
                    'id_fornecedor' => 180,
                    'user' => 'ws_exomed_pharm',
                    'password' => 'ExO11mD*'
                ],
            "PONTAMED" =>
                [
                    'id_fornecedor' => 5018,
                    'user' => 'ws_pontamed_pr',
                    'password' => '4hyamzzs'
                ],
           /* "LONDRICIR" =>
                [
                    'id_fornecedor' => 5039,
                    'user' => 'ws_londricir_pr',
                    'password' => '3g8tvvcs'
                ],*/

        ];

}

function loginApoio(): array
{

    return
        [
            "HOSPIDROGAS" =>
                [
                    'id_fornecedor' => 20,
                    'user' => 'HOSPIDROGAS',
                    'password' => 'hsp@2019'
                ],
            /*"LONDRICIR" =>
                [
                    'id_fornecedor' => 5039,
                    'user' => 'londricir.ws',
                    'password' => 'lo3dr1cir.w5'
                ],*/
            "PONTAMED" =>
                [
                    'id_fornecedor' => 5018,
                    'user' => 'anderson.yudi',
                    'password' => 'Ponta.2023'
                ],
        ];

}

function dateFormat($date, $format)
{
    return date($format, strtotime(str_replace('/', '-', $date)));
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

function arrayFormat($array): array
{
    if (!isset($array[0])) {

        $arrayCopy = $array;

        unset($array);

        $array[0] = $arrayCopy;
    }
    return $array;
}

function createFile($file, $filePath)
{
    $dir = $_SERVER['DOCUMENT_ROOT'] . '/public';

    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $newFile = fopen("{$dir}/{$filePath['name']}.{$filePath['ext']}", "w+");

    fwrite($newFile, $file);

    fclose($newFile);

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

function createObject($type, $array, $rootParam, $xml = NULL)
{
    $object = json_encode($array);

    // $array = arrayFormat($array);

    if ($type == 'xml') {

        $_xml = $xml;

        // Se não houver elemento raiz, cria-se ...
        if ($_xml === NULL) {
            $_xml = new SimpleXMLElement("<{$rootParam}/>");
        }

        $newXml = $_xml;

        // Visite todos os pares de valores-chave
        foreach ($array as $key => $subArray) {

            // Se houver uma matriz aninhada
            if (is_array($subArray)) {

                foreach ($subArray as $keyII => $newArray) {

                    if (!is_numeric($key)) {

                        $newXml = $_xml->AddChild($key);
                    }

                    $newArray = arrayFormat($newArray);

                    foreach ($newArray as $subNewArray) {

                        createObject('xml', $subNewArray, NULL, $newXml->addChild($keyII));
                    }
                }

            } else {
                // Add elemento ao pai
                $_xml->addChild($key, $subArray);
            }
        }

        return $_xml->asXML();

    } else if ($type == 'json') {

        return $object;
    }
}

function like($needle, $haystack)
{
    $regex = '/' . str_replace('%', '.*?', $needle) . '/';

    return preg_match($regex, $haystack) > 0;
}

function multi_unique($array)
{
    foreach ($array as $k => $na)
        $new[$k] = serialize($na);

    $uniq = array_unique($new);

    foreach ($uniq as $k => $ser)
        $new1[$k] = unserialize($ser);

    return ($new1);
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

function decimalRand($iMin, $iMax, $fSteps = 0.5)
{
    $a = range($iMin, $iMax, $fSteps);

    return $a[mt_rand(0, count($a) - 1)];
}
