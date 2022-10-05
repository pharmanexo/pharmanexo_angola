<?php

class M_helpdesk extends CI_Model
{
    protected $DB_COTACAO;
    private $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = 'ca_articles';
        $this->dbHelp = $this->load->database('helpdesk', TRUE);
    }

    public function insert($data)
    {
        $q = $this->dbHelp->insert($this->table, $data);

        if (!$q){
            var_dump($this->dbHelp->error());
            exit();
        }

        return $q;
    }

    public function insertId(){
        return $this->dbHelp->insert_id();
    }

    public function update($id, $data)
    {
        $this->dbHelp->where('id', $id);
        return $this->dbHelp->update($this->table, $data);
    }


    public function getById($id){

        $this->dbHelp->where('id', $id);
        return $this->dbHelp->get($this->table)->row_array();
    }

    public function getCategories()
    {
        return $this->dbHelp->query('SELECT * FROM ca_categorias')->result_array();
    }

}
