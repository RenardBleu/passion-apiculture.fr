<?php
    function accueilControleur($twig){
        echo $twig ->render('accueil.twig', array());
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