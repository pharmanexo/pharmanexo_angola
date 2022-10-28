<?php

class EngineOncoprod extends CI_Model
{
    private $mix;

    public function __construct()
    {
        parent::__construct();

        $this->mix = $this->load->database('mix', true);
    }

    public function getUFsWithPrices($fornecedor)
    {
        return $this->db->select('IF(id_estado IS NULL, 0, id_estado) id_estado')
            ->where('id_fornecedor', $fornecedor)
            ->group_by('id_estado')
            ->get('produtos_preco')
            ->result_array();
    }

    public function getPriceProd($array)
    {
        $this->db->select("pp.preco_unitario");
        $this->db->from('produtos_preco pp');
        $this->db->where('pp.codigo', $array['codigo']);
        $this->db->where('pp.id_fornecedor', $array['id_fornecedor']);
        $this->db->where("pp.id_estado {$array['estado']}");
        $this->db->where("
                pp.data_criacao = (CASE
                    WHEN ISNULL(pp.id_estado) then
                        (select max(pp2.data_criacao)
                            from pharmanexo.produtos_preco pp2
                            where pp2.id_fornecedor = pp.id_fornecedor
                                and pp2.codigo = pp.codigo
                                and pp2.id_estado is null)
                    ELSE
                        (select max(pp2.data_criacao)
                            from pharmanexo.produtos_preco pp2
                              where pp2.id_fornecedor = pp.id_fornecedor
                                and pp2.codigo = pp.codigo
                                and pp2.id_estado = pp.id_estado) END) LIMIT 1
            ");

        return $this->db->get()->row_array();
    }

    public function getPriceProdMix($array)
    {

        $price = $this->mix->where('preco_fixo', 0)
            ->where('id_fornecedor', $array['id_fornecedor'])
            ->where('codigo', $array['codigo'])
            ->group_start()
            ->where('id_cliente', $array['id_cliente'])
            ->or_where('id_estado', $array['id_estado'])
            ->group_end()
            ->limit(1)
            ->get('produtos_preco_mix')
            ->row_array()['preco_base'];

        return $price;

    }

    public function getEnvCots($array)
    {

        $this->db->select("prods.id_fornecedor, f.nome_fantasia, MAX(prods.data_criacao) data_criacao");
        $this->db->from('cotacoes_produtos prods');
        $this->db->join("fornecedores f", "f.id = prods.id_fornecedor");
        $this->db->where("prods.nivel", $array['nivel']);
        $this->db->where("prods.id_fornecedor", $array['id_fornecedor']);
        $this->db->group_by("prods.id_fornecedor, f.nome_fantasia");
        $this->db->order_by("data_criacao DESC");

        return $this->db->get()->result_array();
    }

    public function getProdsNoPrice($fornecedor)
    {

        /*		$query = "SELECT cat.codigo,
                                    cat.nome_comercial,
                                    cat.apresentacao,
                                    cat.marca,

                                (SELECT COUNT(*)
                                   FROM pharmanexo.produtos_preco pp
                                  WHERE pp.codigo = cat.codigo
                                    AND pp.id_fornecedor IN ({$fornecedor})) preco_unitario,

                                (SELECT SUM(pl.estoque)
                                   FROM pharmanexo.produtos_lote pl
                                   WHERE pl.codigo = cat.codigo
                                     AND pl.id_fornecedor IN (12, 111, 112, 115, 120, 123, 126)) estoque

                         FROM pharmanexo.produtos_catalogo cat

                          WHERE cat.id_fornecedor IN ({$fornecedor})
                              AND cat.ativo = 1
                               AND cat.bloqueado = 0

                         GROUP BY cat.codigo,
                                   cat.marca,
                                  cat.nome_comercial,
                                   cat.apresentacao

                         HAVING preco_unitario = 0
                               AND estoque > 0

                         ORDER BY cat.codigo ASC";

                $noPrice = $this->db->query($query)->result_array();

                return $noPrice;*/

        $this->db->select("cat.codigo");
        $this->db->select("cat.nome_comercial");
        $this->db->select("cat.apresentacao");
        $this->db->select("cat.marca");

        $this->db->select("(
            SELECT COUNT(*)
            FROM produtos_preco_oncoprod pp
            WHERE pp.codigo = cat.codigo
                AND pp.id_fornecedor IN ({$fornecedor}) )  preco_unitario
        ");

        $this->db->select("(
            SELECT SUM(pl.estoque)
            FROM pharmanexo.produtos_lote pl
            WHERE pl.codigo = cat.codigo
                AND pl.id_fornecedor IN ({$fornecedor}) ) estoque
        ");

        $this->db->from('produtos_catalogo cat');
        $this->db->where("cat.id_fornecedor IN ({$fornecedor})");
        $this->db->where("cat.ativo", 1);
        $this->db->where("cat.bloqueado", 0);
        $this->db->group_by("cat.codigo, cat.marca, cat.nome_comercial, cat.apresentacao");
        $this->db->having("preco_unitario = 0 AND estoque > 0");
        $this->db->order_by("cat.codigo ASC");

        $q = $this->db->get();

        return $q->result_array();

    }

    public function getProdsNoMarca($fornecedor)
    {

//	    $query = $this->db->query("
//            SELECT
//                cat.codigo,
//                cat.nome_comercial,
//                cat.apresentacao,
//                '' AS marca
//            FROM pharmanexo.produtos_catalogo cat
//            WHERE cat.id_fornecedor IN ({$fornecedor})
//                AND cat.marca IS NULL
//                AND cat.ativo = 1
//                AND cat.bloqueado = 0
//            GROUP BY
//                cat.codigo,
//                cat.nome_comercial,
//                cat.apresentacao
//            ORDER BY cat.codigo ASC
//        ")->result_array();

        $this->db->select("cat.codigo");
        $this->db->select("cat.nome_comercial");
        $this->db->select("cat.apresentacao");
        $this->db->select("'' AS marca");
        $this->db->from("produtos_catalogo cat");
        $this->db->where("cat.id_fornecedor IN ({$fornecedor})");
        $this->db->where("cat.marca IS NULL");
        $this->db->where("cat.ativo", 1);
        $this->db->where("cat.bloqueado", 0);
        $this->db->group_by("cat.codigo, cat.nome_comercial, cat.apresentacao");
        $this->db->order_by("cat.codigo ASC");
        return $this->db->get()->result_array();

    }

    public function getProdsVencimento($fornecedor)
    {

//		$query = "
//            SELECT
//                pl.codigo,
//                pc.nome_comercial,
//               pc.apresentacao,
//               pc.marca
//            FROM pharmanexo.produtos_lote pl
//            JOIN pharmanexo.produtos_catalogo pc
//                ON pc.codigo = pl.codigo
//                    AND pc.id_fornecedor = pl.id_fornecedor
//            WHERE pl.id_fornecedor IN ({$fornecedor})
//                AND pl.validade BETWEEN DATE(NOW())
//                AND (DATE(NOW() + INTERVAL 1 MONTH))
//                GROUP BY pl.codigo,
//                     pc.nome_comercial,
//                     pc.apresentacao,
//                     pc.marca
//            ORDER BY pl.codigo ASC
//        ";
//		$prodsVencendo = $this->db->query($query)->result_array();

        $this->db->select("pl.codigo");
        $this->db->select("pc.nome_comercial");
        $this->db->select("pc.apresentacao");
        $this->db->select("pc.marca");
        $this->db->from("produtos_lote pl");
        $this->db->join("produtos_catalogo pc", "pc.codigo = pl.codigo AND pc.id_fornecedor = pl.id_fornecedor");
        $this->db->where("pl.id_fornecedor IN ({$fornecedor})");
        $this->db->where("pl.validade BETWEEN DATE(NOW()) AND (DATE(NOW() + INTERVAL 1 MONTH))");
        $this->db->group_by("pc.nome_comercial, pc.apresentacao, pc.marca");
        $this->db->order_by("pl.codigo ASC");

        return $this->db->get()->result_array();
    }

    public function sendEmail($params)
    {
        /**
         * Envia o e-mail.
         */

        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://mail.pharmanexo.com.br',
            'smtp_port' => 465,
            'smtp_user' => 'no-reply@pharmanexo.com.br',
            'smtp_pass' => 'Pharma@2020',
            'validate' => true,
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => '\r\n',
            'wordwrap' => true,
        );

        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->set_crlf("\r\n");

        $this->email->initialize($config);

        $this->email->clear(true);

        $this->email->from($params['from'], $params['from-name']);
        $this->email->subject($params['assunto']);
        $this->email->reply_to("no-reply@pharmanexo.com.br");
        $this->email->to($params['destinatario']);

        isset($params['c_copia']) ? $this->email->cc($params['c_copia']) : FALSE;
        isset($params['copia_o']) ? $this->email->bcc($params['copia_o']) : FALSE;
        isset($params['anexo']) ? $this->email->attach($params['anexo']) : FALSE;

        $this->email->message($params['msg']);

        $return = $this->email->send();

        if (isset($params['anexo'])) {

            file_exists($params['anexo']) ? unlink($params['anexo']) : FALSE;
        }

        if ($return) {

            return $return;

        } else {

            return $this->email->print_debugger();
        }

    }

}


