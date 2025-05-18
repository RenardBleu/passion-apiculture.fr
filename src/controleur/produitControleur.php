<?php

function ShowProduitControleur($twig, $db){
    $form = array();
    echo $twig ->render('showProduit.twig', array());
}