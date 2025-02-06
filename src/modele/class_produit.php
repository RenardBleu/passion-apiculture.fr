<?php
class Produit
{
    private $db;
    private $insert;
    private $produit;
    private $select;

    public function __construct($db)
    {
        $this->db = $db;
        $this->insert = $this->db->prepare("insert into produit(title, description, prix, idType, miniature) values (:title, :description, :prix, :type, :miniature)"); // Étape 2
        $this->produit = $this->db->prepare("select title, desciption, prix, idType, miniature, updateAt, deleteAt from produit where id=:id");
        $this->select = $db->prepare("select p.id, title, description, prix, miniature, updateAt, deleteAt, t.libelle as libelletype from produit p, type t where p.idType = t.id order by title");
    }

    public function insert($title, $description, $prix, $type, $miniature)
    { // Étape 3
        $r = true;
        $this->insert->execute(array(':title' => $title, ':description' => $description, ':prix' => $prix, ':type' => $type, ':miniature' => $miniature));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function produit($id)
    {
        $this->produit->execute(array(':id' => $id));
        if ($this->produit->errorCode() != 0) {
            print_r($this->produit->errorInfo());
        }
        return $this->produit->fetch();
    }

    public function select(){
        $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }
}