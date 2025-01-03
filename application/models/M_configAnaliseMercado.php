
<?php
/**
 * Criado por:  Marlon Boecker
 * Criado em: 31/07/2019 07:58
 */

class M_configAnaliseMercado extends MY_Model
{

    protected $table = 'config_analise_mercado';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = '';
    protected $order_direction = 'ASC';


    public function __construct()
    {

        parent::__construct(); 

        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_estados', 'estado');
    }

    public function gravar($post)
    {

        if ( $post['id_fornecedor'] == 'oncoprod' ) {

            $where = "id_fornecedor in (" . ONCOPROD . ")";

            # Remove todos os registros do produto da filial
            $this->db->where('codigo', $post['codigo']);
            $this->db->where($where);
            $this->db->delete($this->table);

            foreach (explode(',', ONCOPROD) as $id_fornecedor) {

                $data = [];
               
                $data['id_fornecedor'] = $id_fornecedor;
                $data['codigo'] = $post['codigo'];

                $precos = [];

                for ($i = 0; $i < count($post['estado']); $i++) {

                    $precos['precos'][] = [
                        'estado' => $post['estado'][$i],
                        'preco_minimo' => dbNumberFormat($post['preco_minimo'][$i]),
                        'preco_medio' => dbNumberFormat($post['preco_medio'][$i]),
                    ];
                }

                $data['data'] = json_encode($precos);

                $this->db->insert($this->table, $data);
            }
        
            if ($this->db->trans_status() === FALSE) {

                $this->db->trans_rollback();

                return false;
            } else {

                $this->db->trans_commit();

                return true;
            }
        } else {

            $data['id_fornecedor'] = $post['id_fornecedor'];
            $data['codigo'] = $post['codigo'];

            $precos = [];

            for ($i = 0; $i < count($post['estado']); $i++) {

                $precos['precos'][] = [
                    'estado' => $post['estado'][$i],
                    'preco_minimo' => dbNumberFormat($post['preco_minimo'][$i]),
                    'preco_medio' => dbNumberFormat($post['preco_medio'][$i]),
                ];
            }

            $data['data'] = json_encode($precos);

            $this->db->where('codigo', $post['codigo']);
            $this->db->where('id_fornecedor', $post['id_fornecedor']);
            $config = $this->db->get($this->table)->row_array();

            if ( isset($config) && !empty($config) ) {

                $this->db->where('id', $config['id']);
                if ( $this->db->update($this->table, $data) ) {

                    return true;
                } else {

                    return false;
                }
            } else {

                if ( $this->db->insert($this->table, $data) ) {

                    return true;
                } else {

                    return false;
                }
            }
        }
    }

    public function getProducts($id_fornecedor)
    {
        
        $this->db->select("pc.*, config.data");
        $this->db->from("config_analise_mercado config");
        $this->db->join("produtos_catalogo pc", 'pc.codigo = config.codigo AND pc.id_fornecedor = config.id_fornecedor');
        $this->db->where("pc.id_fornecedor", $id_fornecedor);
        $produtos = $this->db->get()->result_array();

        foreach ($produtos as $kk => $produto) {
            
            $data = json_decode($produto['data'], true)['precos'];
            $produtos[$kk]['data'] = $data;

            $produtos[$kk]['estados'] = array_column($data, 'estado');
        }

        return $produtos;
    }

    public function getProduct($id)
    {
        
        $this->db->select("pc.*, config.data");
        $this->db->from("config_analise_mercado config");
        $this->db->join("produtos_catalogo pc", 'pc.codigo = config.codigo AND pc.id_fornecedor = config.id_fornecedor');
        $this->db->where("config.id", $id);
        $produto = $this->db->get()->row_array();

        $data = json_decode($produto['data'], true)['precos'];

        foreach ($data as $kk => $row) {
            
            $estado = $this->estado->find("*", "uf = '{$row['estado']}' ", true);
            $data[$kk]['estado'] = $estado;

            # Preço catalogo
            $data[$kk]['preco_catalogo'] = $this->price->getPrice(['id_fornecedor' => $produto['id_fornecedor'], 'codigo' => $produto['codigo'], 'id_estado' => $estado['id'] ]);

            # Ofertas
            $ofertas = $this->getOfertas($produto['id_fornecedor'], $produto['codigo'], $estado['uf']);

            if ( empty($ofertas) ) {
               
                $data[$kk]['media_oferta'] = 0;
                $data[$kk]['valor_diferenca'] = 0; 
            } else {

                $data[$kk]['media_oferta'] = array_sum(array_column($ofertas, 'preco_marca')) / count($ofertas);
                $data[$kk]['valor_diferenca'] = $data[$kk]['media_oferta'] / $row['preco_medio'];
            }

            
        }

        $produto['data'] = $data;

        return $produto;
    }

    public function getOfertas($id_fornecedor, $codigo, $uf)
    {
        
        $queryPreco = "
                SELECT cp.preco_marca
                FROM pharmanexo.cotacoes_produtos cp
                WHERE cp.id_fornecedor = {$id_fornecedor}
                    AND cp.id_pfv = {$codigo}
                    AND cp.uf_comprador = '{$uf}'
                    AND cp.submetido = 1
                    AND cp.controle = 1
                    AND cp.ocultar = 0
                    AND cp.preco_marca > 0
                ORDER BY cp.data_cotacao DESC
                LIMIT 3
            ";

        return $this->db->query($queryPreco)->result_array(); 
    }
}
