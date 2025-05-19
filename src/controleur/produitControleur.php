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
    echo $twig ->render('showProduit.twig', array('form'=>$form,'liste'=>$liste));
}