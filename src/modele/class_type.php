<?php
class Type
{
    private $db;
    private $select;
    private $deleteById;
    private $insert;

    public function __construct($db)
    {
        $this->db = $db;
        $this->select = $db->prepare("select id, libelle, updateAt, deleteAt from type order by id");
        $this->deleteById = $db->prepare("delete from type where id=:id");
        $this->insert = $db->prepare("insert into type(libelle) values (libelle=:libelle)");
    }

    public function select(){
        $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }

    public function deleteById($id){
        $this->deleteById->execute(array(':id' => $id));
        if ($this->deleteById->errorCode()!=0){
            print_r($this->deleteById->errorInfo());
        }
        return $this->deleteById->fetch();
    }

    public function insert(){
        $this->insert->execute(array(':libelle' => $libelle));
        if ($this->insert->errorCode()!=0){
            print_r($this->insert->errorInfo());
        }
        return $this->insert->fetch();
    }
}