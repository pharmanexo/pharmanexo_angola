<?php
class M_catalogo extends MY_Model{

    public function __construct()
    {
        parent::__construct();

        $this->table = 'produtos_catalogo';
    }

}