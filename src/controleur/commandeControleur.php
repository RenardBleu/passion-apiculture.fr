<?php

function AdminCommandeControleur($twig, $db){
    $form = array();

    if ($_SESSION["role"] == 1){
        if (isset($_SESSION['alert'])) {
            $form['alert'] = $_SESSION['alert'];
            unset($_SESSION['alert']);
        }
        echo $twig->render('commandeAdmin.twig', array('form'=>$form, 'liste'=>$liste));
    }else{
        $form['alert'] = [
            "msg" => "Vous n'avez pas la permission d'aller sur cette page !",
            "type" => 'danger'
        ];
        $_SESSION['alert'] = $form['alert'];
        header("Location:index.php");
    }
}