<?php
class Produit
{
    private $db;
    private $insert;
    private $produit;
    private $select;
    private $deleteById;
    private $update;
    private $deleteImage;

    public function __construct($db)
    {
        $this->db = $db;
        $this->insert = $this->db->prepare("insert into produit(title, description, prix, idType, miniature, caracteristiques, stock) values (:title, :description, :prix, :type, :miniature, :caracteristiques, :stock)"); // Étape 2
        $this->produit = $this->db->prepare("select id,title, description, prix, idType, miniature, updateAt, deleteAt, caracteristiques, stock from produit where id=:id");
        $this->select = $db->prepare("select p.id, title, description, prix, miniature, caracteristiques, stock, p.updateAt, p.deleteAt, t.libelle as libelletype from produit p, type t where p.idType = t.id order by title");
        $this->deleteById = $db->prepare("update produit set deleteAt=now() where id=:id");
        $this->update = $db->prepare("update produit set title=:title, description=:description, prix=:prix, idType=:type, miniature=:miniature, caracteristiques=:caracteristiques, stock=:stock, updateAt=now() where id=:id");
        $this->deleteImage = $db->prepare("update produit set miniature=null, updateAt=now() where miniature=:miniature");
    }

    public function insert($title, $description, $prix, $type, $miniature, $caracteristiques, $stock)
    { // Étape 3
        $r = true;
        $this->insert->execute(array(':title' => $title, ':description' => $description, ':prix' => $prix, ':type' => $type, ':miniature' => $miniature, ':caracteristiques'=> $caracteristiques, ':stock' => $stock));
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

    public function deleteById($id){
        $this->deleteById->execute(array(':id' => $id));
        if ($this->deleteById->errorCode()!=0){
            print_r($this->deleteById->errorInfo());
        }
        return $this->deleteById->fetch();
    }
    public function update($id, $title, $description, $prix, $type, $miniature, $caracteristiques, $stock){
        $r = true;
        $this->update->execute(array(':id' => $id, ':title' => $title, ':description' => $description, ':prix' => $prix, ':type' => $type, ':miniature' => $miniature, ':caracteristiques' => $caracteristiques, ':stock' => $stock));
        if ($this->update->errorCode()!=0){
            print_r($this->update->errorInfo());
            $r=false;
        }
        return $r;
    }
    public function deleteImage($miniature){
        $r = true;
        $this->deleteImage->execute(array(':miniature' => $miniature));
        if ($this->deleteImage->errorCode()!=0){
            print_r($this->deleteImage->errorInfo());
            $r=false;
        }
        return $r;
    }
}