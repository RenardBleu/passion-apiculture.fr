<?php
class Utilisateur
{
    private $db;
    private $insert; // Étape 1
    private $connect;
    private $update;
    private $selectById;
    private $selectByEmail;

    private $select;

    public function __construct($db)
    {
        $this->db = $db;
        $this->insert = $this->db->prepare("insert into users(email, mdp, nom, prenom, idRole) values (:email, :mdp, :nom, :prenom, :role)"); // Étape 2
        $this->connect = $this->db->prepare("select id, email, idRole, nom, prenom, mdp, deleteAt from users where email=:email");
        $this->select = $db->prepare("select u.id, email, idRole, nom, prenom, updateAt, deleteAt, r.libelle as libellerole from users u, role r where u.idRole = r.id order by nom");
        $this->selectById = $db->prepare("select u.id, email, idRole, nom, prenom, mdp, updateAt, deleteAt, r.libelle as libellerole from users u, role r where u.id=:id");
        $this->update = $db->prepare("update users set nom=:nom, prenom=:prenom, idRole=:role, mdp=:mdp, updateAt=now() where id=:id");
        $this->selectByEmail = $db->prepare("select id, email, idRole, nom, prenom, mdp, updateAt, deleteAt from users where email=:email");
    }

    public function insert($email, $mdp, $nom, $prenom, $role)
    { // Étape 3
        $r = true;
        $this->insert->execute(array(':email' => $email, ':mdp' => $mdp, ':nom' => $nom, ':prenom' => $prenom, ':role' => $role));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function connect($email)
    {
        $this->connect->execute(array(':email' => $email));
        if ($this->connect->errorCode() != 0) {
            print_r($this->connect->errorInfo());
        }
        return $this->connect->fetch();
    }

    public function select(){
        $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }

    public function selectById($id){
        $this->selectById->execute(array(':id'=>$id)); if ($this->selectById->errorCode()!=0){
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }

    public function update($id, $role, $nom, $prenom, $mdp){
        $r = true;
        $this->update->execute(array(':id'=>$id, ':role'=>$role, ':nom'=>$nom, ':prenom'=>$prenom, ':mdp'=>$mdp));
        if ($this->update->errorCode()!=0){ print_r($this->update->errorInfo());
        $r=false;
        }
        return $r;
    }
    public function selectByEmail($email){
        $this->selectByEmail->execute(array(':email' => $email));
        if ($this->selectByEmail->errorCode()!=0){
            print_r($this->selectByEmail->errorInfo());
        }
        return $this->selectByEmail->fetch();
    }
}
?>
}