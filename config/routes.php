<?php
    function getPage($db){
        $lesPages['accueil'] = "accueilControleur";
        $lesPages['contact'] = "contactControleur";
        $lesPages['about'] = "aboutControleur";
        $lesPages['legal'] = "legalControleur";
        $lesPages['inscrire'] = "inscrireControleur";
        $lesPages['login'] = "loginControleur";
        $lesPages['logout'] = "logoutControleur";
        $lesPages['user-edit'] = "userEditControleur";
        $lesPages['admin-users'] = "AdminUsersControleur";
        $lesPages['admin-produits'] = "AdminProduitControleur";
        $lesPages['admin-produit-add'] = "AdminProduitAddControleur";
        $lesPages['admin-produit-delete'] = "AdminProduitDeleteControleur";
        $lesPages['admin-produit-edit'] = "AdminProduitEditControleur";
        $lesPages['admin-types'] = "AdminTypeControleur";
        $lesPages['admin-type-delete'] = "AdminTypeDeleteControleur";
        $lesPages['admin-delete-image'] = "AdminProduitDeleteImageControleur";
        $lesPages['admin-type-edit'] = "AdminTypeEditControleur";
        $lesPages['admin-type-add'] = "AdminTypeAddControleur";
        $lesPages['admin-commande'] = "AdminCommandeControleur";
        $lesPages['admin-commande-update-status'] = "AdminCommandeUpdateControleur";
        $lesPages['show-produit'] = "ShowProduitControleur";
        $lesPages['maintenance'] = "maintenanceControleur";
            if($db != null){
                if (isset($_GET['page'])){
                    $page = $_GET['page'];
                    
                } else {
                    $page = 'accueil';
                }
                if (isset($lesPages[$page])){
                    $contenu = $lesPages[$page];
                } else {
                    $contenu = $lesPages['accueil'];
                }
            }
            else{
                $contenu = $lesPages['maintenance'];
            }
        return $contenu;
    }