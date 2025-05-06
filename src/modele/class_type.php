<?php
class Type
{
    private $db;
    private $select;
    private $deleteById;
    private $insert;
    private $update;

    public function __construct($db)
    {
        $this->db = $db;
        $this->select = $db->prepare("SELECT t.id, t.libelle, t.updateAt, t.deleteAt, 
                                     COUNT(p.id) as nbr_produits 
                                     FROM type t 
                                     LEFT JOIN produit p ON t.id = p.idType AND p.deleteAt IS NULL 
                                     GROUP BY t.id, t.libelle, t.updateAt, t.deleteAt");
        $this->deleteById = $db->prepare("DELETE FROM type WHERE id=:id");
        $this->insert = $db->prepare("INSERT INTO type(libelle) VALUES (:libelle)");
        $this->update = $db->prepare("UPDATE type SET libelle=:libelle, updateAt=now() WHERE id=:id");
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

    public function insert($libelle){
        $r = true;
        $this->insert->execute(array(':libelle' => $libelle));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function update($id, $libelle){
        $r = true;
        $this->update->execute(array(':id' => $id, ':libelle' => $libelle, ));
        if ($this->update->errorCode()!=0){
            print_r($this->update->errorInfo());
            $r=false;
        }
        return $r;
    }
}