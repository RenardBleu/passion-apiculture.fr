<?php
class Commande
{
    private $db;
    private $select;
    private $selectById;
    private $insert;
    private $electByNum;



    public function __construct($db)
    {
        $this->db = $db;
        $this->select = $db->prepare("SELECT c.num, u.nom as nom, u.prenom as prenom, t.id as idTag, t.libelle as libelleTag, montant, facture, c.createAt, c.updateAt 
                                    FROM commandes c
                                    JOIN users u ON u.id = c.idUser
                                    JOIN tag t ON t.id = c.idTag
                                    ORDER BY c.createAt DESC");
                                    
        $this->selectById = $db->prepare("SELECT c.num, u.nom as nom, u.prenom as prenom, t.id as idTag, t.libelle as libelleTag, montant, facture, c.createAt, c.updateAt 
                                    FROM commandes c
                                    JOIN users u ON u.id = c.idUser
                                    JOIN tag t ON t.id = c.idTag
                                    WHERE c.idUser = :idUser AND c.createAt = :createAt");
        $this->insert = $db->prepare("INSERT INTO commandes (num, idUser, idTag, montant, facture, createAt, updateAt) VALUES (:num, :idUser, :idTag, :montant, :facture, :createAt, :updateAt)");
        $this->electByNum = $db->prepare("SELECT * FROM commandes WHERE num = :num");
    }

    public function select(){
        $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }

    public function selectById($num){
        $this->selectById->execute(array(':idUser'=>$idUser, ':createAt'=>$date)); 
        if ($this->selectById->errorCode()!=0){
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }

    public function insert($num, $idUser, $idTag, $montant, $facture, $createAt, $updateAt){
        $r = true;
        $this->insert->execute(array(':num'=> $num, ':idUser'=> $idUser, ':idTag'=> $idTag, ':montant'=> $montant, ':facture'=> $facture, ':createAt'=> $createAt, ':updateAt'=> $updateAt));
        if ($this->insert->errorCode()!=0){
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    function electByNum($num){
        $this->electByNum->execute(array(':num'=> $num));
        if ($this->electByNum->errorCode()!=0){
            print_r($this->electByNum->errorInfo());
        }
        return $this->electByNum->fetch();
    }
}