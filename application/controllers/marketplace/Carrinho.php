<?php

class Carrinho extends MY_Controller{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

    }

    public function add_item($id){
        $this->load->model("m_estoque");
        if ($this->input->method() == 'post'){
        }else{

            $data = [
                "produto" => $this->m_estoque->buscaProduto($id)->row_array()
            ];

            $this->load->view("v_modal_carrinho", $data);
        }
    }

}