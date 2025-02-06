<?php
class Type
{
    private $db;
    private $select;

    public function __construct($db)
    {
        $this->db = $db;
        $this->select = $db->prepare("select id, libelle from type order by id");
    }

    public function select(){
        $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }
}