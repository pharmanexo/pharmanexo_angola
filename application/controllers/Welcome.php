<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{


	    var_dump(password_hash("SINTESE@@2019__APIKEY", 1));
	    exit();

        $xml = simplexml_load_file(base_url("/public/arquivo.xml"));
        //carrega o arquivo XML e retornando um Array

        $xml_novo = new DOMDocument();

        $dom = new DOMDocument("1.0", "ISO-8859-1");

        #gerar o codigo
        $dom->formatOutput = true;

        $registros = [];

        #criando o nó principal (root)
        $root = $dom->createElement("Produto");

        foreach($xml->Produto as $item){
            $atual = time();

            if (strtotime($item->VENCIMENTO) < $atual){
                if (isset($item['RMS'])) unset($item['rms']);
             $registros[] = (array)$item;
            }
        }


        var_dump($this->array_to_xml($registros));exit();

	}

    function array_to_xml(array $data) {
        $document = new DOMDocument();
        $this->array_to_xml_aux($data, $document);
        $document->formatOutput = true;
        return $document;
    }

    function array_to_xml_aux(array $data, DOMNode $parent, $name = null)
    {
        foreach ($data as $key => $value) {
            if ($key[0] == '@') {
                $parent->setAttribute(
                    substr($key, 1),
                    $value
                );
                continue;
            }

            if (is_numeric($key)) {
                $key = $name;
            }

            if (is_array($value)) {
                $areAllInt = true;
                foreach(array_keys($value) as $k) {
                    if (!is_int($k)) {
                        $areAllInt = false;
                        break;
                    }
                }
                if ($areAllInt) {
                    array_to_xml_aux($value, $parent, $key);
                } else {
                    $subnode = new DOMElement($key);
                    $parent->appendChild($subnode);
                    array_to_xml_aux($value, $subnode, $key);
                }

            } else {
                $xml_data->appendChild(new DOMElement($key, $value));
            }
        }
    }

}


