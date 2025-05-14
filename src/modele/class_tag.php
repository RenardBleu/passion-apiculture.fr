<?php
class Tag
{
    private $db;
    private $select;

    public function __construct($db)
    {
        $this->db = $db;
        $this->select = $db->prepare("SELECT id, libelle FROM tag");
    }

    public function select(){
        $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }
}