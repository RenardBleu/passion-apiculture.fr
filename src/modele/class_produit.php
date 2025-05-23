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
    private $selectLimit;
    private $selectCount;
    private $recherche;
    private $rechercheCount;
    private $selectByType;
    private $selectIn;
    private $updateStock;
    

    public function __construct($db)
    {
        $this->db = $db;
        $this->insert = $this->db->prepare("insert into produit(title, description, prix, idType, miniature, caracteristiques, stock) values (:title, :description, :prix, :type, :miniature, :caracteristiques, :stock)"); // Étape 2
        $this->produit = $this->db->prepare("select id,title, description, prix, idType, miniature, updateAt, deleteAt, caracteristiques, stock from produit where id=:id");
        $this->select = $db->prepare("select p.id, title, description, prix, miniature, caracteristiques, stock, p.updateAt, p.deleteAt, p.createAt, t.libelle as libelletype from produit p, type t where p.idType = t.id order by p.createAt desc");
        $this->deleteById = $db->prepare("update produit set deleteAt=now() where id=:id");
        $this->update = $db->prepare("update produit set title=:title, description=:description, prix=:prix, idType=:type, miniature=:miniature, caracteristiques=:caracteristiques, stock=:stock, updateAt=now() where id=:id");
        $this->deleteImage = $db->prepare("update produit set miniature=null, updateAt=now() where miniature=:miniature");
        $this->selectLimit = $db->prepare("select p.id, title, description, prix, miniature, caracteristiques, stock, p.updateAt, p.deleteAt, t.libelle as libelletype from produit p, type t where p.idType = t.id order by title limit :inf,:limite");
        $this->selectCount = $db->prepare("select count(*) as nb from produit");
        $this->recherche = $db->prepare("select p.id, title, description, prix, miniature, caracteristiques, stock, p.updateAt, p.deleteAt, t.libelle as libelletype from produit p, type t where p.idType = t.id and (p.title like :recherche OR t.libelle like :recherche) order by title LIMIT :limite OFFSET :inf");
        $this->rechercheCount = $db->prepare("select count(*) as nb from produit p JOIN type t ON p.idType = t.id where p.title like :recherche OR t.libelle like :recherche");
        $this->selectByType = $db->prepare("select p.id, title, description, prix, miniature, caracteristiques, stock, p.updateAt, p.deleteAt, p.createAt, t.libelle as libelletype from produit p, type t where p.idType = t.id order by p.createAt desc WHERE p.idType = :idType");
        $this->selectIn = $this->db->prepare("select id, title, description, prix, miniature, idType, stock from produit where FIND_IN_SET(id, :ids)");
        $this->updateStock = $this->db->prepare("update produit set stock=:stock where id=:id");

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
    public function selectLimit($inf, $limite){
        $this->selectLimit->bindParam(':inf', $inf, PDO::PARAM_INT);
        $this->selectLimit->bindParam(':limite', $limite, PDO::PARAM_INT);
        $this->selectLimit->execute();
        if ($this->selectLimit->errorCode()!=0){
            print_r($this->selectLimit->errorInfo());
        }
        return $this->selectLimit->fetchAll();
    }
    public function selectCount(){
        $this->selectCount->execute();
        if ($this->selectCount->errorCode()!=0){
            print_r($this->selectCount->errorInfo());
        }
        return $this->selectCount->fetch();
    }
    public function recherche($recherche, $inf, $limite){
        $this->recherche->bindValue(':recherche', '%'.$recherche.'%', PDO::PARAM_STR);
        $this->recherche->bindValue(':inf', (int)$inf, PDO::PARAM_INT);
        $this->recherche->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $this->recherche->execute();
        if ($this->recherche->errorCode()!=0){
            print_r($this->recherche->errorInfo());
        }
        return $this->recherche->fetchAll();
    }
    public function rechercheCount($recherche){
        $this->rechercheCount->execute(array('recherche'=>'%'.$recherche.'%'));
        if ($this->rechercheCount->errorCode()!=0){
            print_r($this->rechercheCount->errorInfo());
        }
        return $this->rechercheCount->fetch();
    }
    public function selectByType($idType){
        $this->selectByType->execute(array(':idType' => $idType));
        if ($this->selectByType->errorCode()!=0){
            print_r($this->selectByType->errorInfo());
        }
        return $this->selectByType->fetchAll();
    }
    public function selectIn($ids){
        $implose = implode(',', $ids);
        $this->selectIn->bindParam(':ids', $implose);
        $this->selectIn->execute();
        if ($this->selectIn->errorCode()!=0){
            print_r($this->selectIn->errorInfo());
        }
        return $this->selectIn->fetchAll();
    }
    public function updateStock($id, $stock){
        $r = true;
        $this->updateStock->execute(array(':id' => $id, ':stock' => $stock));
        if ($this->updateStock->errorCode()!=0){
            print_r($this->updateStock->errorInfo());
            $r=false;
        }
        return $r;
    }
}