<?php
class M_fotos extends MY_Model{

    public function __construct()
    {
        parent::__construct();

        $this->table = 'fotos';
    }

}