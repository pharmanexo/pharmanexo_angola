<?php

class Cotacoes extends CI_Controller
{
    private $DB1, $DB2;

    public function __construct()
    {
        parent::__construct();
        $this->DB1 = $this->load->database('default', true);
        $this->DB2 = $this->load->database('sintese', true);
    }

    /**
     * Exibe todas as cotações
     *
     * @param - get - int - ativa o filtro por cotações em aberto
     * @param - post - int id_fornecedor
     * @param - get - int limit
     * @param - get - int page
     * @return  json
     */
    public function all($emAberto = null)
    {
        $post = $this->input->post();
        $get = $this->input->get();

        if ( isset($post['id_fornecedor']) ) {
            
            $this->DB2->select('*');
            $this->DB2->where('id_fornecedor', $post['id_fornecedor']);

            $page = isset($get['page']) ? isset($get['page']) : 0;

            if ( isset($emAberto) )
                $this->DB2->where("dt_fim_cotacao > now()");

            if ( isset($get['limit']) ) 
                $this->DB2->limit($get['limit'], $page);
       
            $result = $this->DB2->get('cotacoes');

            $this->DB2->order_by('dt_fim_cotacao', 'ASC');
        
            $cotacoes =  $result->result_array();
            
        } else {

            var_dump('Necessário informar id_fornecedor');
            exit();
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($cotacoes));
    }

    /**
     * Exibe todas as cotações por estado
     *
     * @param - get - int - ativa o filtro por cotações em aberto
     * @param - post - int id_fornecedor
     * @param - post - string state
     * @param - get - int limit
     * @param - get - int page
     * @return  json
     */
    public function allStates($emAberto = null)
    {
        $post = $this->input->post();
        $get = $this->input->get();

        if ( isset($post['id_fornecedor']) && isset($post['state']) ) {
            
            $this->DB2->select('*');
            $this->DB2->where('id_fornecedor', $post['id_fornecedor']);
            $this->DB2->where('uf_cotacao', $post['state']);

            $page = isset($get['page']) ? isset($get['page']) : 0;

            if ( isset($emAberto) )
                $this->DB2->where("dt_fim_cotacao > now()");

            if ( isset($get['limit']) ) 
                $this->DB2->limit($get['limit'], $page);

            $this->DB2->order_by('dt_fim_cotacao', 'ASC');
       
            $result = $this->DB2->get('cotacoes');
        
            $cotacoes =  $result->result_array();
            
        } else {

            var_dump('Necessário informar id_fornecedor e state');
            exit();
        }
       
        $this->output->set_content_type('application/json')->set_output(json_encode($cotacoes));
    }

    /**
     * Exibe a cotação especifica
     *
     * @param - post - string cd_Cotacao obrigatório
     * @param - post - int id_fornecedor obrigatório
     * @return  json
     */
    public function find()
    {
        $post = $this->input->post();

        if ( isset($post['cd_cotacao']) && isset($post['id_fornecedor']) ) {
         
            $cotacao = $this->DB2->select('*')
                ->where('cd_cotacao', $post['cd_cotacao'])
                ->where('id_fornecedor', $post['id_fornecedor'])
                ->get('cotacoes')
                ->row_array();

        } else {

            var_dump('Necessário informar cd_cotacao e id_fornecedor');
            exit();
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($cotacao));
    }

    /**
     * busca itens da cotação
     * 
     * @param - post - int id_fornecedor obrigatório
     * @param - post - string cd_cotacao obrigatório
     * @param - get - int limit
     * @param - get - int page
     * @return - json
     */
    public function findProducts()
    {
        $post = $this->input->post();
        $get = $this->input->get();

        if ( isset($post['id_fornecedor']) && isset($post['cd_cotacao']) ) {

            $this->DB2->select('*');
            $this->DB2->where('cd_cotacao', $post['cd_cotacao']);
            $this->DB2->where('id_fornecedor', $post['id_fornecedor']);

            $page = isset($get['page']) ? isset($get['page']) : 0;

            if ( isset($get['limit']) ) 
                $this->DB2->limit($get['limit'], $page);
            
            $result = $this->DB2->get('cotacoes_produtos');
        
            $cotacoes = $result->result_array();
        } else {

            var_dump('Necessário informar id_fornecedor e cd_cotacao'); 
            exit();
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($cotacoes));
    }

    /**
     * Exibe a quantidade de cotações por estado
     * 
     * @param - post - id_fornecedor obrigatorio
     * @param  - get int filtro por cotações em aberto
     * @return  json
     */
    public function states($emAberto = null)
    {
        $post = $this->input->post();

        if (isset($post['id_fornecedor'])) {

            if (isset($emAberto)) {

                $cotacoes = $this->DB2->select('COUNT(0) as total, uf_cotacao as uf')
                    ->where('id_fornecedor', $post['id_fornecedor'])
                    ->where("dt_fim_cotacao > now()")
                    ->group_by('uf_cotacao')
                    ->get('cotacoes')
                    ->result_array();
                
            } else {

                $cotacoes = $this->DB2->select('COUNT(0) as total, uf_cotacao as uf')
                    ->where('id_fornecedor', $post['id_fornecedor'])
                    ->group_by('uf_cotacao')
                    ->get('cotacoes')
                    ->result_array();

            }
        } else {

            var_dump('Necessário informar id_fornecedor');
            exit();
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($cotacoes));
    }
}
