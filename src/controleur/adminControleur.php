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
        $produit = new Produit($db);
        $unProduit = $produit->produit($_GET['id']); 
        if ($unProduit!=null){
            $form['produit'] = $unProduit;
            $type = new Type($db);
            $liste = $type->select();
            $form['type']=$liste;
        }
        else{
            $form['message'] = 'Produit incorrect ou inconnu';
        }
        if(isset($_POST['btEdit'])){
            $produit = new Produit($db);
            $id = $_GET['id'];
            $title = $_POST['title'];
            $description = $_POST['description'];
            $prix = $_POST['prix'];
            $miniature = $_POST['miniature'];
            $type = $_POST['type'];
            $form['valide'] = true;

            try{
                if($miniature == ""){
                    $minia = $produit['miniature'];
                }else{
                    $minia = $miniature;
                }
                $produit->update($id, $title, $description, $prix, $minia, $type,);
                $form['message'] = 'Modification réussie';
            }catch(e){
                $form['valide'] = false;
                $form['message'] = 'Echec de la modification';
            }
        }
    }
    else{
        $form['message'] = 'Produit non précisé';
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