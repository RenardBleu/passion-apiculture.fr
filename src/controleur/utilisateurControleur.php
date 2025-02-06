<?php


// ---- LOGIN ---- //


function loginControleur($twig, $db){
    $form = array();

    if (isset($_POST['btLogin'])){
        $form['valide'] = true;
        $inputEmail = $_POST['inputEmail'];
        $inputPassword = $_POST['inputPassword'];
        $utilisateur = new Utilisateur($db);
        $unUtilisateur = $utilisateur->connect($inputEmail);
        if ($unUtilisateur!=null){
            if(!password_verify($inputPassword,$unUtilisateur['mdp'])){
                $form['valide'] = false;
                $form['message'] = 'Login ou mot de passe incorrect';
            }else{
                if ($unUtilisateur['deleteAt'] != null){
                    $form['valide'] = false;
                    $form['message'] = 'Ce compte à été suprimé';
                }else{
                    $_SESSION['login'] = $inputEmail;
                    $_SESSION['fname'] = $unUtilisateur['prenom'];
                    $_SESSION['lname'] = $unUtilisateur['nom'];
                    $_SESSION['role'] = $unUtilisateur['idRole'];
                    header("Location:index.php");
                }
            }
        }
        else{
            $form['valide'] = false;
            $form['message'] = 'Login ou mot de passe incorrect';
        }
    }
    dump($form['valide']);
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
        $form['valide'] = true;
        if ($inputPassword!=$inputPassword2){
            $form['valide'] = false;
            $form['message'] = 'Les mots de passe sont différents';
        }else{
            if(strlen($inputPassword)<8){
                $form['valide'] = false;
                $form['message'] = 'Le mots de passe est trop faible';
            }else{
                $utilisateur = new Utilisateur($db);
                $unUtilisateur = $utilisateur->connect($inputEmail);
                if($unUtilisateur['email'] === $inputEmail){
                    $form['valide'] = false;
                    $form['message'] = 'Un utilisateur est déjà inscrit avec cette adresse mail';
                }else{
                    try{
                        var_dump($_POST);
                        $utilisateur = new Utilisateur($db);
                        $utilisateur->insert($inputEmail, password_hash($inputPassword, PASSWORD_DEFAULT), $nom, $prenom, $role);
                    }
                    catch(Exception $e){
                        $form['valide'] = false;
                        $form['message'] = 'Problème d\'insertion dans la table utilisateur ';
                        //$form['message'] = $e;
                    }
                }
            }
        }
        $form['email'] = $inputEmail;
        $form['role'] = $role;
        $form['nom'] = $nom;
        $form['prenom'] = $prenom;
    }
    echo $twig->render('inscrire.twig', array('form'=>$form));
}

function userEditControleur($twig, $db){
    $form = array();
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
            $form['valide'] = true;

            try{
                $utilisateur->update($id, $role, $nom, $prenom);
                $form['message'] = 'Modification réussie';
            }catch(e){
                $form['valide'] = false;
                $form['message'] = 'Echec de la modification';
            }
        }
    }
    else{
        $form['message'] = 'Utilisateur non précisé';
    }
    echo $twig->render('userEdit.twig', array('form'=>$form));
}