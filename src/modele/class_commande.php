<?php
class Commande
{
    private $db;
    private $select;
    private $selectById;

    public function __construct($db)
    {
        $this->db = $db;
        $this->select = $db->prepare("SELECT c.id, c.num, u.nom as nom, u.prenom as prenom, t.id as idTag, t.libelle as libelleTag, montant, facture, c.createAt, c.updateAt 
                                    FROM commandes c
                                    JOIN users u ON u.id = c.idUser
                                    JOIN tag t ON t.id = c.idTag");
                                    
        $this->selectById = $db->prepare("SELECT c.id, c.num, u.nom as nom, u.prenom as prenom, t.id as idTag, t.libelle as libelleTag, montant, facture, c.createAt, c.updateAt 
                                    FROM commandes c
                                    JOIN users u ON u.id = c.idUser
                                    JOIN tag t ON t.id = c.idTag
                                    WHERE c.num = :num");
    }

    public function select(){
        $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }

    public function selectById($num){
        $this->selectById->execute(array(':num'=>$num)); if ($this->selectById->errorCode()!=0){
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }
}