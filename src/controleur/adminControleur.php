<?php
function AdminUsersControleur($twig, $db){
    $form = array();
    $utilisateur = new Utilisateur($db);
    $liste = $utilisateur->select();

    if ($_SESSION["role"] == 1){
        echo $twig ->render('userAdmin.html.twig', array('form'=>$form,'liste'=>$liste));
    }else{
        header("Location:index.php");
    }
}