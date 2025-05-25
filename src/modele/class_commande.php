<?php
/**
 * Classe de gestion des commandes
 * 
 * Cette classe gère toutes les opérations liées aux commandes :
 * - Création et mise à jour des commandes
 * - Gestion des statuts
 * - Gestion des factures
 * - Requêtes de sélection et de mise à jour
 */
class Commande
{
    /** @var PDO Instance de la base de données */
    private $db;

    /** @var PDOStatement */
    private $select;
    private $selectById;
    private $insert;
    private $selectByNum;
    private $updateStatus;
    private $selectComposer;
    private $selectFacture;
    private $insertFacture;
    private $updateFacture;

    /**
     * Constructeur de la classe Commande
     * 
     * Initialise les requêtes préparées pour les différentes opérations
     * 
     * @param PDO $db Instance de la base de données
     */
    public function __construct($db)
    {
        $this->db = $db;
        // Requête pour sélectionner toutes les commandes avec les informations utilisateur et tag
        $this->select = $db->prepare("SELECT c.num, u.nom as nom, u.prenom as prenom, t.id as idTag, t.libelle as libelleTag, montant, idFacture, c.createAt, c.updateAt 
                                    FROM commandes c
                                    JOIN users u ON u.id = c.idUser
                                    JOIN tag t ON t.id = c.idTag
                                    ORDER BY c.createAt DESC");
                                    
        // Requête pour sélectionner une commande spécifique par ID utilisateur et date
        $this->selectById = $db->prepare("SELECT c.num, u.nom as nom, u.prenom as prenom, t.id as idTag, t.libelle as libelleTag, montant, idFacture, c.createAt, c.updateAt 
                                    FROM commandes c
                                    JOIN users u ON u.id = c.idUser
                                    JOIN tag t ON t.id = c.idTag
                                    WHERE c.idUser = :idUser AND c.createAt = :createAt");
        
        // Requête pour insérer une nouvelle commande
        $this->insert = $db->prepare("INSERT INTO commandes (num, idUser, idTag, montant, idFacture, createAt, updateAt) VALUES (:num, :idUser, :idTag, :montant, :facture, :createAt, :updateAt)");
        
        // Requête pour sélectionner une commande par son numéro
        $this->selectByNum = $db->prepare("SELECT * FROM commandes WHERE num = :num");
        
        // Requête pour sélectionner les produits d'une commande via son numéro
        $this->selectComposer = $db->prepare("SELECT * FROM composer WHERE idCommande = :num");
        
        // Requête pour sélectionner une facture via le numéro de la commande lié
        $this->selectFacture = $db->prepare("SELECT * FROM facture WHERE num_commande = :num");
        
        // Requête pour insérer une nouvelle facture
        $this->insertFacture = $db->prepare("INSERT INTO facture (path, num_commande) VALUES (:path, :num)");
        
        // Requête pour mettre à jour l'ID de facture d'une commande
        $this->updateFacture = $db->prepare("UPDATE commandes SET idFacture = :idFacture WHERE num = :num");
        
        // Requête pour mettre à jour le statut d'une commande via son numéro
        $this->updateStatus = $db->prepare("UPDATE commandes SET idTag = :satuts WHERE num = :num");
    }
    
    /**
     * Récupère toutes les commandes
     * 
     * @return array Liste de toutes les commandes
     */
    public function select(){
        $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }

    /**
     * Récupère une commande par son ID
     * 
     * @param int $num Numéro de la commande
     * @return array|false Données de la commande ou false si non trouvée
     */
    public function selectById($num){
        $this->selectById->execute(array(':num'=>$num,)); 
        if ($this->selectById->errorCode()!=0){
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }

    /**
     * Insère une nouvelle commande
     * 
     * @param int $num Numéro de commande
     * @param int $idUser ID de l'utilisateur
     * @param int $idTag ID du statut
     * @param float $montant Montant total
     * @param int $facture ID de la facture
     * @param string $createAt Date de création
     * @param string $updateAt Date de mise à jour
     * @return bool True si l'insertion a réussi, false sinon
     */
    public function insert($num, $idUser, $idTag, $montant, $facture, $createAt, $updateAt){
        $r = true;
        $this->insert->execute(array(':num'=> $num, ':idUser'=> $idUser, ':idTag'=> $idTag, ':montant'=> $montant, ':facture'=> $facture, ':createAt'=> $createAt, ':updateAt'=> $updateAt));
        if ($this->insert->errorCode()!=0){
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    /**
     * Récupère une commande par son numéro
     * 
     * @param int $num Numéro de la commande
     * @return array|false Données de la commande ou false si non trouvée
     */
    function selectByNum($num){
        $this->selectByNum->execute(array(':num'=> $num));
        if ($this->selectByNum->errorCode()!=0){
            print_r($this->selectByNum->errorInfo());
        }
        return $this->selectByNum->fetch();
    }

    /**
     * Récupère les produits d'une commande
     * 
     * @param int $num Numéro de la commande
     * @return array|false Liste des produits ou false si erreur
     */
    function selectComposer($num){
        $this->selectComposer->execute(array(':num'=> $num));
        if ($this->selectComposer->errorCode()!=0){
            print_r($this->selectComposer->errorInfo());
            return false;
        }
        return $this->selectComposer->fetchAll();
    }

    /**
     * Récupère une facture
     * 
     * @param int $num Numéro de la commande
     * @return array|false Données de la facture ou false si non trouvée
     */
    function selectFacture($num){
        $this->selectFacture->execute(array(':num'=> $num));
        if ($this->selectFacture->errorCode()!=0){
            print_r($this->selectFacture->errorInfo());
            return false;
        }
        return $this->selectFacture->fetch();
    }

    /**
     * Insère une nouvelle facture
     * 
     * @param string $path Chemin du fichier facture (son nom)
     * @param int $num Numéro de la commande
     * @return int|false ID de la facture insérée ou false si erreur
     */
    function insertFacture($path, $num){
        $r = true;
        $this->insertFacture->execute(array(':path'=> $path, ':num'=> $num));
        if ($this->insertFacture->errorCode()!=0){
            print_r($this->insertFacture->errorInfo());
            $r = false;
        }
        return $this->db->lastInsertId();
    }

    /**
     * Met à jour l'ID de facture d'une commande
     * 
     * @param int $idFacture ID de la facture
     * @param int $num Numéro de la commande
     * @return bool True si la mise à jour a réussi, false sinon
     */
    function updateFacture($idFacture, $num){
        $r = true;
        $this->updateFacture->execute(array(':idFacture'=> $idFacture, ':num'=> $num));
        if ($this->updateFacture->errorCode()!=0){
            print_r($this->updateFacture->errorInfo());
            $r = false;
        }
        return $r;
    }

    /**
     * Met à jour le statut d'une commande
     * 
     * @param int $num Numéro de la commande
     * @param int $satuts ID du nouveau statut
     * @return bool True si la mise à jour a réussi, false sinon
     */
    function updateStatus($num, $satuts){
        $r = true;
        $this->updateStatus->execute(array(':num'=> $num, ':satuts'=> $satuts));
        if ($this->updateStatus->errorCode()!=0){
            print_r($this->updateStatus->errorInfo());
            $r = false;
        }
        return $r;
    }
}