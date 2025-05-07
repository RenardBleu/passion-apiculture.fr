<?php
function AdminUsersControleur($twig, $db){
    $form = array();
    $utilisateur = new Utilisateur($db);
    $liste = $utilisateur->select();

    if ($_SESSION["role"] == 1){
        if (isset($_SESSION['alert'])) {
            $form['alert'] = $_SESSION['alert'];
            unset($_SESSION['alert']);
        }
        echo $twig ->render('userAdmin.twig', array('form'=>$form,'liste'=>$liste));
    }else{
        $form['alert'] = [
            "msg" => "Vous n'avez pas la permission d'aller sur cette page !",
            "type" => 'danger'
        ];
        $_SESSION['alert'] = $form['alert'];
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
        $form['alert'] = [
            "msg" => "Vous n'avez pas la permission d'aller sur cette page !",
            "type" => 'danger'
        ];
        $_SESSION['alert'] = $form['alert'];
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
        $form['alert'] = [
            "msg" => "Produit ajouté avec succès !",
            "type" => 'success'
        ];
        if ($type == "0"){
            $form['alert'] = [
                "msg" => "Erreur lors de l'ajout du produit : Vous devez mettre un type au produit",
                "type" => 'danger'
            ];
        }else{
            try{
                $produit = new Produit($db);
                $produit->insert($nom, $description, $prix, $type, $miniature);
            }
            catch(Exception $e){
                $form['alert'] = [
                    "msg" => "Erreur lors de l'ajout du produit : Problème d'insertion dans la table produit",
                    "type" => 'danger'
                ];
            }
        }
        $form['nom'] = $nom;
        $form['prix'] = $prix;
    }
    if ($_SESSION["role"] == 1){
        echo $twig ->render('ProduitAdminAdd.twig', array('form'=>$form,'liste'=>$liste));
    }else {
        $form['alert'] = [
            "msg" => "Vous n'avez pas la permission pour ajouter un produit !",
            "type" => 'danger'
        ];
        $_SESSION['alert'] = $form['alert'];
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
                    $minia = $form['produit']['miniature'];
                }else{
                    $minia = $miniature;
                }
                $produit->update($id, $title, $description, $prix, $type, $minia);
                $form['message'] = 'Modification réussie';
            }catch(Exception $e){
                $form['valide'] = false;
                $form['message'] = 'Echec de la modification';
            }
            $produit = new Produit($db);
            $unProduit = $produit->produit($_GET['id']); 
            if ($unProduit!=null){
                $form['produit'] = $unProduit;
                $type = new Type($db);
                $liste = $type->select();
                $form['type']=$liste;
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


/* ------ TYPE ------ */


function AdminTypeControleur($twig, $db){
    $form = array();
    $type = new Type($db);
    $liste = $type->select();

    if ($_SESSION["role"] == 1){
        if (isset($_SESSION['alert'])) {
            $form['alert'] = $_SESSION['alert'];
            unset($_SESSION['alert']);
        }
        echo $twig ->render('TypeAdmin.twig', array('form'=>$form,'liste'=>$liste));
    }else{
        header("Location:index.php");
    }
}

function AdminTypeDeleteControleur($twig, $db){
    if ($_SESSION["role"] == 1){

        $form = array();
        $type = new Type($db);

        if($_GET['id'] !=""){
            $type = new Type($db);
            $form['alert'] = [
                "msg" => 'Type supprimé avec succès !',
                "type" => 'success'
            ];
            try{
                $type->deleteById($_GET['id']);
            }
            catch(PDOException $e){
                error_log('Erreur PDO : ' . $e->getMessage());
                $form['alert'] = [
                    "msg" => 'Echec de la suppression du type',
                    "type" => 'danger'
                ];
            }
            $_SESSION['alert'] = $form['alert'];
            header("Location:index.php?page=admin-types");
        }else{
            $_SESSION['alert'] = $form['alert'];
            header("Location:index.php?page=admin-types");
        }
    }else{
        header("Location:index.php");
    }
}

function AdminTypeAddControleur($twig, $db){
    if ($_SESSION["role"] == 1){

        $form = array();
        $type = new Type($db);

        if(isset($_POST['btnAdd'])){
            $type = new Type($db);
            $libelle = $_POST['libelle'];
            $form['alert'] = [
                "msg" => 'Type ajouté avec succès !',
                "type" => 'success'
            ];
            try{
                $type->insert($libelle);
            }
            catch(PDOException $e){
                error_log('Erreur PDO : ' . $e->getMessage());
                $form['alert'] = [
                    "msg" => 'Erreur lors de la création du type',
                    "type" => 'danger'
                ];
            }
            $liste = $type->select();
            $_SESSION['alert'] = $form['alert'];
            header("Location:index.php?page=admin-types");
        }else{
            $_SESSION['alert'] = $form['alert'];
            header("Location:index.php?page=admin-types");
        }
    }else{
        header("Location:index.php");
    }
}

function AdminTypeEditControleur($twig, $db){
    if ($_SESSION["role"] == 1){

        $form = array();
        $type = new Type($db);

        if(isset($_POST['btnEdit'])){
            if($_GET['id'] !=""){

                $libelle = $_POST['libelle'];
                $id = $_GET['id'];

                $form['alert'] = [
                    "msg" => 'Type modifié avec succès !',
                    "type" => 'success'
                ];
                try{
                    $type->update($id, $libelle);
                }
                catch(PDOException $e){
                    error_log('Erreur PDO : ' . $e->getMessage());
                    $form['alert'] = [
                        "msg" => 'Erreur lors de la modification du type',
                        "type" => 'danger'
                    ];
                }
            }else{
                $form['alert'] = [
                    "msg" => 'Erreur lors de la modification du type : identifiant inconnu',
                    "type" => 'danger'
                ];
            }
        }
        $_SESSION['alert'] = $form['alert'];
        header("Location:index.php?page=admin-types");
    }else{
        header("Location:index.php");
    }
}