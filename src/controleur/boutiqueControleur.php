<?php
function BoutiqueProduitControleur($twig, $db) {
    $form = array();
    $produit = new Produit($db);
    $limite=8;

    if(!isset($_GET['nopage'])){
        $inf=0;
        $nopage=0;
    }
    else{
        $nopage=$_GET['nopage'];
        $inf=$nopage * $limite;
    }
    $r = $produit->selectCount();
    $nb = $r['nb'];

    $liste = $produit->selectLimit($inf,$limite);
    $form['nbpages'] = ceil($nb/$limite);
    $form['nopage'] = $nopage;

    if (isset($_SESSION['alert'])) {
        $form['alert'] = $_SESSION['alert'];
        unset($_SESSION['alert']);
    }
    echo $twig->render('boutique.twig', array('form' => $form, 'liste' => $liste));
} 
function rechercheControleur($twig, $db){
    $form = array();
    $produit = new Produit($db);
    $limite=8;

    if(!isset($_GET['nopage'])){
        $inf=0;
        $nopage=0;
    }
    else{
        $nopage=$_GET['nopage'];
        $inf=$nopage * $limite;
    }

    if(isset($_POST['btnRecherche'])){
        $form['recherche'] = $_POST['recherche'];
        $r = $produit->rechercheCount($form['recherche']);
        $nb = $r['nb'];

        $liste = $produit->recherche($form['recherche'], $inf, $limite);
        $form['nbpages'] = ceil($nb/$limite);
        $form['nopage'] = $nopage;
    }
    if (isset($_SESSION['alert'])) {
        $form['alert'] = $_SESSION['alert'];
        unset($_SESSION['alert']);
    }
    echo $twig->render('recherche.twig', array('form' => $form, 'liste' => $liste));
}

function panierControleur($twig, $db){
    $form = array();
    $liste = array();
    if (isset($_SESSION['login'])) {
        if (!empty($_SESSION['panier'])) {
            $ids = array_keys($_SESSION['panier']);
            $produits = new Produit($db);
            $liste = $produits->selectIn($ids);
            if(isset($_GET['remove'])){
                unset($_SESSION['panier'][$_GET['remove']]);
                $liste = $produits->selectIn($ids);
                header("Location:index.php?page=panier");
            }
            if (isset($_POST['update'])) {
                foreach ($_POST as $k => $v) {
                    if(strpos($k,'q-')!== false){
                        $explose = explode('-',$k);
                        $unid = (int)$explose[1];
                        $_SESSION['panier'][$unid]['quantite'] = $v;
                    }
                }
                header("Location:index.php?page=panier");
            }
            if(isset($_POST['placerCommade'])){
                $montant = $_POST['montant'];
                $etat = 1;
                $aujourdhui = new DateTime();
                $aujourdhui->setTimezone(new DateTimeZone('Europe/Paris'));
                $date = $aujourdhui->format("Y-m-d H:i:s");
                $utilisateur = new Utilisateur($db);
                $produit = new Produit($db);
                $unUtil = $utilisateur->selectByEmail($_SESSION['login']);
                $idUtilisateur = $unUtil['id'];
                $form['alert'] = [
                    "msg" => "Commande effectué !",
                    "type" => 'success'
                ];
                $commande = new Commande($db);
                $commande_num = mt_rand();
                while ($commande_num == $i and strlen($commande_num) <= 10){
                    $commande_num = mt_rand();
                    $i = $commande->selectByNum($commande_num);
                }
                $facture = null;
                $commande_pass = false;
                try{
                    $commande->insert($commande_num, $idUtilisateur, $etat, $montant, $facture, $date, $date);
                    $commande_pass = true;
                }catch(PDOException $e){
                    $form['alert'] = [
                        "msg" => "Erreur lors de la création de la commande",
                        "type" => 'danger'
                    ];
                }
                if($commande_pass){
                    $verif = true;
                    $composer = new Composer($db);
                    foreach ($_SESSION['panier'] as $k => $v) {
                        try{
                            $composer->insert($commande_num,$k,$v['quantite']);
                        }catch(PDOException $e){
                            $verif = false;
                            $form['alert'] = [
                                "msg" => "Erreur : au moins un produit n\'a pas été validé",
                                "type" => 'danger'
                            ];
                        }
                        try{
                            $unProduit = $produit->produit($k);
                            $nouveauStock = $unProduit['stock'] - $v['quantite'];
                            $produit->updateStock($k, $nouveauStock);
                        }catch(PDOException $e){
                            $verif = false;
                            $form['alert'] = [
                                "msg" => "Erreur lors de la mise à jour du stock",
                                "type" => 'danger'
                            ];
                        }
                    }
                    if ($verif){
                        unset($_SESSION['panier']);
                        $_SESSION['alert'] = $form['alert'];
                        header("Location:index.php?page=panier");
                    }else{
                        $form['alert'] = [
                            "msg" => "Erreur : au moins un produit n\'a pas été validé",
                            "type" => 'danger'
                        ];
                    }
                }
            }    
        }
        if (isset($_SESSION['alert'])) {
            $form['alert'] = $_SESSION['alert'];
            unset($_SESSION['alert']);
        }
        echo $twig->render('panier.twig', array('form'=>$form,'liste'=>$liste));
    }else{
        header("Location:index.php?page=login");
    }
}

function PanierAddControleur($twig, $db){
    $form = array();
    $produit = new Produit($db);
    if(isset($_POST['btAjoutP'])){
        if(isset($_POST['id'])){
            $unProduit = $produit->produit($_POST['id']);
            $quantite = $_POST['quantite'];
            if(!$unProduit){
                $form['alert'] = [
                    "msg" => "Erreur lors de l'ajout du produit : Le produit n'existe pas",
                    "type" => 'danger'
                ];
            }else{
                for ($i = 0; $i < $quantite; $i++) {
                    if (isset($_SESSION['panier']) && is_array($_SESSION['panier'])) {
                        if (array_key_exists($unProduit['id'], $_SESSION['panier'])) {
                            // Le produit existe dans le panier, il suffit de mettre à jour la quantité
                            $_SESSION['panier'][$unProduit['id']]['quantite']++;
                            $_SESSION['panier'][$unProduit['id']]['prix'] = $_SESSION['panier'][$unProduit['id']]['prix'] + $unProduit['prix'];
                        } else {
                            // Le produit n'est pas dans le panier, ajoutez-le
                            $_SESSION['panier'][$unProduit['id']] = array(
                                'quantite' => 1,
                                'nom' => $unProduit['title'],
                                'prix' => $unProduit['prix']
                            );
                        }
                    } else {
                        // Il n'y a aucun produit dans le panier, ceci ajoutera le premier produit au panier
                        $_SESSION['panier'] = array(
                            $unProduit['id'] => array(
                                'quantite' => 1,
                                'nom' => $unProduit['title'],
                                'prix' => $unProduit['prix']
                            )
                        );
                    }
                }
                $form['alert'] = [
                    "msg" => "Le produit a bien été ajouté",
                    "type" => 'success'
                ];
            }
        }else{
            $form['alert'] = [
                "msg" => "Erreur lors de l'ajout du produit : Vous n'avez pas sélectionner de produit",
                "type" => 'danger'
            ];
        }
    }
    $_SESSION['alert'] = $form['alert'];
    header("Location:index.php?page=show-produit&id=".$_POST['id']);
}
function PanierDeleteAllControleur($twig, $db){
    if (!isset($_SESSION['login'])) {
        header("Location:index.php?page=login");
    }else{
        if (isset($_SESSION['panier'])) {
            unset($_SESSION['panier']);
        }
        header("Location:index.php?page=boutique");
    }
}