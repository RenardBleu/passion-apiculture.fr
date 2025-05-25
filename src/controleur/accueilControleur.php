<?php
    function accueilControleur($twig){
        $form = array();
        if (isset($_SESSION['alert'])) {
            $form['alert'] = $_SESSION['alert'];
            unset($_SESSION['alert']);
        }
        echo $twig ->render('accueil.twig', array('form'=>$form));
    }
    function contactControleur($twig){
        echo $twig ->render('contact.twig', array());
    }
    function aboutControleur($twig){
        echo $twig ->render('about.twig', array());
    }
    function legalControleur($twig){
        echo $twig ->render('legal.twig', array());
    }
    function maintenanceControleur($twig){
        echo $twig ->render('maintenance.twig', array());
    }
   
       
?>