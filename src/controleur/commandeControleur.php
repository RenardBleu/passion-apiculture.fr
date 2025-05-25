<?php

function AdminCommandeControleur($twig, $db){
    $form = array();

    if ($_SESSION["role"] == 1){

        $list = new Tag($db);
        $liste = $list->select();

        $commande = new Commande($db);
        $commandes = $commande->select();

        if (isset($_SESSION['alert'])) {
            $form['alert'] = $_SESSION['alert'];
            unset($_SESSION['alert']);
        }
        echo $twig->render('commandeAdmin.twig', array('form'=>$form, 'liste'=>$liste, 'commandes'=>$commandes));
    }else{
        $form['alert'] = [
            "msg" => "Vous n'avez pas la permission d'aller sur cette page !",
            "type" => 'danger'
        ];
        $_SESSION['alert'] = $form['alert'];
        header("Location:index.php");
    }
}

function AdminCommandeUpdateControleur($twig, $db){
    $form = array();
    
    if ($_SESSION["role"] == 1){
        $form = array();
        $num = $_GET['id'];
        $status = $_GET['status'];

        if($num!="" && $status!=""){
            $commandes = new Commande($db);
            $commande = $commandes->selectByNum($num);
            if ($commande!=null){
                try{
                    $commandes->updateStatus($num, $status);
                    header("Location:index.php?page=admin-commande");
                }catch(Exception $e){
                    $form['alert'] = [
                        "msg" => "Erreur : lors de la modification du status",
                        "type" => 'danger'
                    ];
                    $_SESSION['alert'] = $form['alert'];
                    header("Location:index.php?page=admin-commande");
                }
            }
            else{
                $form['alert'] = [
                    "msg" => "Erreur : Commande incorrect ou inconnu",
                    "type" => 'danger'
                ];
                $_SESSION['alert'] = $form['alert'];
                header("Location:index.php?page=admin-commande");
            }
        }else{
            $form['alert'] = [
                "msg" => "Erreur : Id de la commande ou nouveau status incorrect ou non renseigné",
                "type" => 'danger'
            ];
            $_SESSION['alert'] = $form['alert'];
            header("Location:index.php?page=admin-commande");
        }


    }else{
        $form['alert'] = [
            "msg" => "Vous n'avez pas la permission d'aller sur cette page !",
            "type" => 'danger'
        ];
        $_SESSION['alert'] = $form['alert'];
        header("Location:index.php");
    }
}