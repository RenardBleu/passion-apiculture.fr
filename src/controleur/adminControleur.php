<?php
function AdminUsersControleur($twig, $db){
    $form = array();
    $utilisateur = new Utilisateur($db);
    $liste = $utilisateur->select();

    if ($_SESSION["role"] == 1){
        echo $twig ->render('userAdmin.twig', array('form'=>$form,'liste'=>$liste));
    }else{
        header("Location:index.php");
    }
}

function AdminProduitControleur($twig, $db){
    $form = array();
    $produit = new Produit($db);
    $liste = $produit->select();

    if ($_SESSION["role"] == 1){
        echo $twig ->render('ProduitAdmin.twig', array('form'=>$form,'liste'=>$liste));
    }else{
        header("Location:index.php");
    }
}

function AdminProduitAddControleur($twig, $db){
    $type = new Type($db);
    $liste = $type->select();

    $form = array();
    if (isset($_POST['btAdd'])){
        $nom = $_POST['nom'];
        $prix = $_POST['prix'];
        $description =$_POST['description'];
        $type = $_POST['type'];
        $miniature = $_POST['miniature'];
        $form['valide'] = true;
        if ($type == "0"){
            $form['valide'] = false;
            $form['message'] = 'Vous devez mettre un type au produit';
        }else{
            try{
                $produit = new Produit($db);
                $produit->insert($nom, $description, $prix, $type, $miniature);
            }
            catch(Exception $e){
                $form['valide'] = false;
                $form['message'] = 'Problème d\'insertion dans la table produit ';
                //$form['message'] = $e;
            }
        }
        $form['nom'] = $nom;
        $form['prix'] = $prix;
        var_dump($type, $form['message'], $form['valide']);
    }
    if ($_SESSION["role"] == 1){
        echo $twig ->render('ProduitAdminAdd.twig', array('form'=>$form,'liste'=>$liste));
    }else {
        header("Location:index.php");
    }
}


function AdminProduitEditControleur($twig, $db){
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
            $inputPassword = $_POST['inputPassword'];
            $inputPassword2 = $_POST['inputPassword2'];

            if($inputPassword!=$inputPassword2){
                $form['valide'] = false;
                $form['message'] = 'Les mots de passe sont différents';
            }else{

                try{
                    if($inputPassword == ""){
                        $mdp = $unUtilisateur['mdp'];
                    }else{
                        $mdp = password_hash($inputPassword, PASSWORD_DEFAULT);
                    }
                    $utilisateur->update($id, $role, $nom, $prenom, $mdp);
                    $form['message'] = 'Modification réussie';
                    var_dump($mdp, !isset($inputPassword));
                }catch(e){
                    $form['valide'] = false;
                    $form['message'] = 'Echec de la modification';
                }
            }
        }
    }
    else{
        $form['message'] = 'Utilisateur non précisé';
    }
    echo $twig->render('produitEdit.twig', array('form'=>$form));
}


function AdminProduitDeleteControleur($twig, $db){
    if ($_SESSION["role"] == 1){

        if($_GET['id'] !=""){
            $produit = new Produit($db);
            try{
                $produit->deleteById($_GET['id']);
            }
            catch(Exception $e){}
            header("Location:index.php?page=admin-produits");
        }else{
            header("Location:index.php?page=admin-produits");
        }
    }else{
        header("Location:index.php");
    }
}