<?php
function BoutiqueProduitControleur($twig, $db) {
    $form = array();
    $produit = new Produit($db);
    $liste = $produit->select();

    if (isset($_SESSION['alert'])) {
        $form['alert'] = $_SESSION['alert'];
        unset($_SESSION['alert']);
    }

    echo $twig->render('boutique.twig', array('form' => $form, 'liste' => $liste));
} 