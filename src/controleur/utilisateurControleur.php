<?php


// ---- LOGIN ---- //


function loginControleur($twig, $db){
    $form = array();

    if (isset($_POST['btLogin'])){
        $inputEmail = $_POST['inputEmail'];
        $inputPassword = $_POST['inputPassword'];
        $utilisateur = new Utilisateur($db);
        $unUtilisateur = $utilisateur->connect($inputEmail);

        $form['alert'] = [
            "msg" => "Connectée en temps que ".$unUtilisateur['prenom']." ".$unUtilisateur['nom'],
            "type" => 'success'
        ];

        if ($unUtilisateur!=null){
            if(!password_verify($inputPassword,$unUtilisateur['mdp'])){
                $form['alert'] = [
                    "msg" => "Connection refusé : Login ou mot de passe incorrect",
                    "type" => 'danger'
                ];
            }else{
                if ($unUtilisateur['deleteAt'] != null){
                    $form['alert'] = [
                        "msg" => "Connection refusé : Ce compte à été suprimé",
                        "type" => 'danger'
                    ];
                }else{
                    $_SESSION['id'] = $unUtilisateur['id'];
                    $_SESSION['login'] = $inputEmail;
                    $_SESSION['fname'] = $unUtilisateur['prenom'];
                    $_SESSION['lname'] = $unUtilisateur['nom'];
                    $_SESSION['role'] = $unUtilisateur['idRole'];
                    header("Location:index.php");
                }
            }
        }
        else{
            $form['alert'] = [
                "msg" => "Connection refusé : Login ou mot de passe incorrect",
                "type" => 'danger'
            ];
        }
    }
    $_SESSION['alert'] = $form['alert'];
    echo $twig->render('login.twig', array('form'=>$form));
}


// ---- LOGOUT ---- //


function logoutControleur($twig, $db){
    session_unset();
    session_destroy();
    header("Location:index.php");
}


// ---- INSCRIPTION ---- //


function inscrireControleur($twig, $db){
    $form = array();
    if (isset($_POST['btInscrire'])){
        $inputEmail = $_POST['inputEmail'];
        $inputPassword = $_POST['inputPassword'];
        $inputPassword2 =$_POST['inputPassword2'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $role = $_POST['role'];
        $form['alert'] = [
            "msg" => "Inscription réussie ! Vous pouvez vous connecter !",
            "type" => 'success'
        ];
        if ($inputPassword!=$inputPassword2){
            $form['alert'] = [
                "msg" => "Erreur lors de l'inscription : Les mots de passe sont différents",
                "type" => 'danger'
            ];
        }else{
            if(strlen($inputPassword)<8){
                $form['alert'] = [
                    "msg" => "Erreur lors de l\'inscription : Le mots de passe est trop faible",
                    "type" => 'danger'
                ];
            }else{
                $utilisateur = new Utilisateur($db);
                $unUtilisateur = $utilisateur->connect($inputEmail);
                if($unUtilisateur['email'] === $inputEmail){
                    $form['alert'] = [
                        "msg" => "Erreur lors de l\'inscription : Un utilisateur est déjà inscrit avec cette adresse mail",
                        "type" => 'danger'
                    ];
                }else{
                    try{
                        $utilisateur = new Utilisateur($db);
                        $utilisateur->insert($inputEmail, password_hash($inputPassword, PASSWORD_DEFAULT), $nom, $prenom, $role);
                    }
                    catch(Exception $e){
                        $form['alert'] = [
                            "msg" => "Erreur lors de l\'inscription : Problème d\'insertion dans la table utilisateur",
                            "type" => 'danger'
                        ];
                    }
                }
            }
        }
        $form['email'] = $inputEmail;
        $form['role'] = $role;
        $form['nom'] = $nom;
        $form['prenom'] = $prenom;
    }
    $_SESSION['alert'] = $form['alert'];
    echo $twig->render('inscrire.twig', array('form'=>$form));
}

function userEditControleur($twig, $db){
    $form = array();
    if($_GET['id'] == "$_SESSION[id]" or $_SESSION['role'] == 1){
        if($_GET['id'] !=""){
            $utilisateur = new Utilisateur($db);
            $unUtilisateur = $utilisateur->selectById($_GET['id']); 
            if ($unUtilisateur!=null){
                $form['utilisateur'] = $unUtilisateur;
                $role = new Role($db);
                $liste = $role->select();
                $form['roles']=$liste;
            }
            else{
                $form['message'] = 'Utilisateur incorrect';
            }
            if(isset($_POST['btEdit'])){
                $utilisateur = new Utilisateur($db);
                $nom = $_POST['nom'];
                $prenom = $_POST['prenom'];
                $role = $_POST['role'];
                $id = $_GET['id'];
                $form['alert'] = [
                    "msg" => "Modification réussie !",
                    "type" => 'success'
                ];
                $inputPassword = $_POST['inputPassword'];
                $inputPassword2 = $_POST['inputPassword2'];
    
                if($inputPassword!=$inputPassword2){
                    $form['alert'] = [
                        "msg" => "Echec de la modification : Les mots de passe sont différents",
                        "type" => 'danger'
                    ];
                }else{
    
                    try{
                        if($inputPassword == ""){
                            $mdp = $unUtilisateur['mdp'];
                        }else{
                            $mdp = password_hash($inputPassword, PASSWORD_DEFAULT);
                        }
                        $utilisateur->update($id, $role, $nom, $prenom, $mdp);
                        $utilisateur = new Utilisateur($db);
                        $unUtilisateur = $utilisateur->selectById($_GET['id']); 
                        if ($unUtilisateur!=null){
                            $form['utilisateur'] = $unUtilisateur;
                            $role = new Role($db);
                            $liste = $role->select();
                            $form['roles']=$liste;
                        }
                    }catch(e){
                        $form['alert'] = [
                            "msg" => "Echec de la modification",
                            "type" => 'danger'
                        ];
                    }
                }
            }
        }
        else{
            $form['message'] = 'Utilisateur non précisé';
        }
        echo $twig->render('userEdit.twig', array('form'=>$form));
    }else {
        $form['alert'] = [
            "msg" => "Vous n\'avez pas la permission de modifier cette utilisateur !",
            "type" => 'danger'
        ];
        $_SESSION['alert'] = $form['alert'];
        header("Location:index.php");
    }
}