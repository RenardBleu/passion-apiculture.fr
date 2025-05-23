<?php

function ShowProduitControleur($twig, $db){
    $form = array();
    $id = $_GET['id'];
    $form['valide'] = true;
    
    if(isset($id)){
        $produit = new Produit($db);
        $unProduit = $produit->produit($id);

        if(isset($unProduit)){
            if($unProduit['deleteAt'] == null){
                $form['produit'] = $unProduit;
                
                // Vérifier si le produit est dans le panier
                if(isset($_SESSION['panier']) && array_key_exists($unProduit['id'], $_SESSION['panier'])) {
                    $form['quantite_panier'] = $_SESSION['panier'][$unProduit['id']]['quantite'];
                } else {
                    $form['quantite_panier'] = 0;
                }

                if(isset($form['produit']['caracteristiques'])){
                    $caracteristiques = explode('//', $form['produit']['caracteristiques']);
                    foreach ($caracteristiques as $caracteristique){
                        $caracteristique = trim($caracteristique);
                        $form['caracteristiques'][] = $caracteristique;
                    }
                }
            }else{
                $form['message'] = 'le produit que vous voulez voir n\'est plus disponible';
            }
        }else{
            $form['message'] = 'le produit que vous voulez voir n\'existe pas';
        }
    }else{
        $form['message'] = 'id du produit non renseigné';
    }
    if (isset($_SESSION['alert'])) {
        $form['alert'] = $_SESSION['alert'];
        unset($_SESSION['alert']);
    }
    echo $twig ->render('showProduit.twig', array('form'=>$form,'liste'=>$liste));
    dump($_SESSION);
}