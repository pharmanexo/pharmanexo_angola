<?php

function loginBionexo(): array
{

    return
        [
            "HOSPIDROGAS" =>
                [
                    'id_fornecedor' => 20,
                    'user' => 'ws_hospidrogas',
                    'password' => 'Bionexo123'
                ],
            "EXOMED" =>
                [
                    'id_fornecedor' => 180,
                    'user' => 'ws_exomed_pe',
                    'password' => 'Bionexo123'
                ],
            "PONTAMED" =>
                [
                    'id_fornecedor' => 5018,
                    'user' => 'ws_pontamed_pr',
                    'password' => '4hyamzzs'
                ]

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
    foreach ($array as $k => $na) {
        $new[$k] = serialize($na);
    }

    if (isset($new)) {
        $uniq = array_unique($new);

        foreach ($uniq as $k => $ser)
            $new1[$k] = unserialize($ser);

        return ($new1);
    } else {
        return [];
    }
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


if (!function_exists('getEstado')) {
    function getEstado($uf = NULL)
    {
        $estados = [
          'AC'   =>  ['id' => 1],
          'AL'   =>  ['id' => 2],
          'AP'   =>  ['id' => 3],
          'AM'   =>  ['id' => 4],
          'BA'   =>  ['id' => 5],
          'CE'   =>  ['id' => 6],
          'DF'   =>  ['id' => 7],
          'ES'   =>  ['id' => 8],
          'GO'   =>  ['id' => 9],
          'MA'   =>  ['id' => 10],
          'MT'   =>  ['id' => 11],
          'MS'   =>  ['id' => 12],
          'MG'   =>  ['id' => 13],
          'PA'   =>  ['id' => 14],
          'PB'   =>  ['id' => 15],
          'PR'   =>  ['id' => 16],
          'PE'   =>  ['id' => 17],
          'PI'   =>  ['id' => 18],
          'RJ'   =>  ['id' => 19],
          'RN'   =>  ['id' => 20],
          'RS'   =>  ['id' => 21],
          'RO'   =>  ['id' => 22],
          'RR'   =>  ['id' => 23],
          'SC'   =>  ['id' => 24],
          'SP'   =>  ['id' => 25],
          'SE'   =>  ['id' => 26],
          'TO'   =>  ['id' => 27],
          'SPI'   =>  ['id' => 28],
        ];

        if (is_null($uf)){
            return $estados;
        }

        return $estados[$uf];
    }
}
