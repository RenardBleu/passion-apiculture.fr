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

    $type = new Type($db);
    $listeType = $type->select();

    if ($_SESSION["role"] == 1){
        if (isset($_SESSION['alert'])) {
            $form['alert'] = $_SESSION['alert'];
            unset($_SESSION['alert']);
        }
        echo $twig ->render('ProduitAdmin.twig', array('form'=>$form,'liste'=>$liste, 'type'=>$listeType));
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
    if (isset($_POST['btnAdd'])){
        $nom = $_POST['nom'];
        $prix = $_POST['prix'];
        $type = $_POST['type'];
        $miniature = null;
        $description =$_POST['description'];
        $caracteristiques = ($_POST['caracteristiques'] == "") ?  null : $_POST['caracteristiques'];
        $stock = $_POST['stock'];
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
                $upload = new Upload(array('png', 'gif', 'jpg', 'jpeg'), 'image/uploaded_image', 5000000);
                $miniature = $upload->enregistrer('miniature');
                if ($miniature['erreur'] != null){
                    $form['alert'] = [
                        "msg" => "Erreur lors de l'ajout du produit : " . $miniature['erreur'],
                        "type" => 'danger'
                    ];
                }else{
                    $produit->insert($nom, $description, $prix, $type, $miniature['nom'], $caracteristiques, $stock);
                }
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
    if ($_SESSION["role"] != 1){
        $form['alert'] = [
            "msg" => "Vous n'avez pas la permission pour ajouter un produit !",
            "type" => 'danger'
        ];
        $_SESSION['alert'] = $form['alert'];
        header("Location:index.php");
    }else{
        $_SESSION['alert'] = $form['alert'];
        header("Location:index.php?page=admin-produits");
    }
}


function AdminProduitEditControleur($twig, $db){
    $form = array();
    if($_GET['id'] !=""){
        $produit = new Produit($db);
        $unProduit = $produit->produit($_GET['id']); 
        if ($unProduit!=null){
            if(isset($_POST['btnEdit'])){
                $form['alert'] = [
                    "msg" => "Modification du produit réussi !",
                    "type" => 'success'
                ];
                $id = $_GET['id'];
                $title = $_POST['nom'];
                $description = $_POST['description'];
                $prix = $_POST['prix'];
                $miniature = null;
                $caracteristiques = ($_POST['caracteristiques'] == "") ?  null : $_POST['caracteristiques'];
                $type = ($_POST['type'] == "0") ? $unProduit['idType'] : $_POST['type'];
                $stock = $_POST['stock'];
                $form['valide'] = true;
    
                try{
                    if($_FILES['miniature']['name'] != ""){
                        $upload = new Upload(array('png', 'gif', 'jpg', 'jpeg'), 'image/uploaded_image', 5000000);
                        $miniature = $upload->enregistrer('miniature');
                        if ($miniature['erreur'] != null){
                            $form['alert'] = [
                                "msg" => "Erreur lors de la modification du produit : " . $miniature['erreur'],
                                "type" => 'danger'
                            ];
                        }else{
                            $produit->update($id, $title, $description, $prix, $type, $miniature['nom'], $caracteristiques, $stock);
                        }
                    }else{
                        $produit->update($id, $title, $description, $prix, $type, $unProduit['miniature'], $caracteristiques, $stock);
                    }
                }catch(Exception $e){
                    $form['alert'] = [
                        "msg" => "Erreur lors de la modification : Echec de la modification" . $e->getMessage(),
                        "type" => 'danger'
                    ];
                }
            }
        }
        else{
            $form['message'] = 'Produit incorrect ou inconnu';
        }
        
    }
    else{
        $form['message'] = 'Produit non précisé';
    }
    $type = new Type($db);
    $_SESSION['alert'] = $form['alert'];
    header("Location:index.php?page=admin-produits");
}


function AdminProduitDeleteControleur($twig, $db){
    if ($_SESSION["role"] == 1){

        if($_GET['id'] !=""){
            $produit = new Produit($db);
            try{
                $produit->deleteById($_GET['id']);
            }
            catch(Exception $e){
                $form['alert'] = [
                    "msg" => "Erreur lors de la suppression du produit : Echec de la suppression",
                    "type" => 'danger'
                ];
            }
            $_SESSION['alert'] = $form['alert'];
            header("Location:index.php?page=admin-produits");
        }else{
            $_SESSION['alert'] = $form['alert'];
            header("Location:index.php?page=admin-produits");
        }
    }else{
        $form['alert'] = [
            "msg" => "Vous n'avez pas la permission pour ajouter un produit !",
            "type" => 'danger'
        ];
        $_SESSION['alert'] = $form['alert'];
        header("Location:index.php");
    }
}

function AdminProduitDeleteImageControleur($twig, $db){
    if ($_SESSION["role"] == 1){

        if($_GET['image_path'] !=""){
            $produit = new Produit($db);
            $form['alert'] = [
                "msg" => "Image supprimée avec succès !",
                "type" => 'success'
            ];
            try{
                $upload = new Upload(array('png', 'gif', 'jpg', 'jpeg'), 'image/uploaded_image', 5000000);
                $isDelete = $upload->delete($_GET['image_path']);
                if($isDelete['erreur'] != null){
                    $form['alert'] = [
                        "msg" => "Erreur lors de la suppression de l'image : " . $isDelete['erreur'],
                        "type" => 'danger'
                    ];
                }else{
                    $produit->deleteImage($_GET['image_path']);
                }
            }
            catch(Exception $e){
                $form['alert'] = [
                    "msg" => "Erreur lors de la suppression de l'image",
                    "type" => 'danger'
                ];
            }
            $_SESSION['alert'] = $form['alert'];
            header("Location:index.php?page=admin-produits");
        }else{
            $form['alert'] = [
                "msg" => "Erreur lors de la suppresion de l'image : Aucune image trouvée",
                "type" => 'danger'
            ];
            $_SESSION['alert'] = $form['alert'];
            header("Location:index.php?page=admin-produits");
        }
    }else{
        $form['alert'] = [
            "msg" => "Vous n'avez pas la permission pour ajouter un produit !",
            "type" => 'danger'
        ];
        $_SESSION['alert'] = $form['alert'];
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
        $form['alert'] = [
            "msg" => "Vous n'avez pas la permission d'aller sur cette page !",
            "type" => 'danger'
        ];
        $_SESSION['alert'] = $form['alert'];
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
        $form['alert'] = [
            "msg" => "Vous n'avez pas la permission pour supprimer un type !",
            "type" => 'danger'
        ];
        $_SESSION['alert'] = $form['alert'];
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
        $form['alert'] = [
            "msg" => "Vous n'avez pas la permission pour ajouter un type !",
            "type" => 'danger'
        ];
        $_SESSION['alert'] = $form['alert'];
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
        $form['alert'] = [
            "msg" => "Vous n'avez pas la permission pour modifier ce type !",
            "type" => 'danger'
        ];
        $_SESSION['alert'] = $form['alert'];
        header("Location:index.php");
    }
}