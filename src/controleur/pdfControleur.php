<?php
use Dompdf\Dompdf;
use Dompdf\Options;

function facturePdfControleur($twig, $db){
    if(isset($_SESSION['login'])){
        if(isset($_GET['num_commande'])){
            $commande = new Commande($db);
            $maCommande = $commande->selectByNum($_GET['num_commande']);
            $allProduct = $commande->selectComposer($_GET['num_commande']);
            if($maCommande['idFacture'] == null){
                try{
                    $dateFormatted = date('d_m_Y', strtotime($maCommande['createAt']));
                    $commande->insertFacture('facture_' . $dateFormatted . '_'.$maCommande['num'].'.pdf', $_GET['num_commande']);
                    $facture = $commande->selectFacture($_GET['num_commande']);
                    $commande->updateFacture($facture['id'], $_GET['num_commande']);
                }catch(Exception $e){
                    echo $e->getMessage();
                }
            }else{
                try{
                    $facture = $commande->selectFacture($_GET['num_commande']);
                }catch(Exception $e){
                    echo $e->getMessage();
                }
            }
            $produit = new Produit($db);
            $utilisateur = new Utilisateur($db);
            $liste = array();
            $panier = $commande->selectComposer($_GET['num_commande']);
            $unUtilisateur = $utilisateur->selectById($maCommande['idUser']);
            $connectUser = $utilisateur->selectByEmail($_SESSION['login']);
            if($connectUser['id'] == $maCommande['idUser'] or $connectUser['idRole'] == '1'){
                
                foreach($panier as $composer){
                    $unProduit = $produit->produit($composer['idProduit']);
                    $unProduit['quantite'] = $composer['quantite'];
    
                    $liste[] = $unProduit;
                }
    
                //dump($panier, $liste);
                // Convertir le logo en base64
                $logoPath = $_SERVER['DOCUMENT_ROOT'] . '/image/logo.svg';
                $logoData = base64_encode(file_get_contents($logoPath));
                $logoBase64 = 'data:image/svg+xml;base64,' . $logoData;
                
                $html = $twig->render('facturePdf.twig', array(
                    'liste' => $liste,
                    'logoBase64' => $logoBase64,
                    'commande' => $maCommande,
                    'utilisateur' => $unUtilisateur,
                    'facture' => $facture
    
                ));
    
                try {
                    // Configuration de dompdf
                    $options = new Options();
                    $options->set('isHtml5ParserEnabled', true);
                    $options->set('isPhpEnabled', true);
                    $options->set('isRemoteEnabled', true);
                    $options->set('defaultFont', 'Helvetica');
    
                    // Création de l'instance dompdf
                    $dompdf = new Dompdf($options);
                    
                    // Chargement du HTML
                    $dompdf->loadHtml($html);
                    
                    // Configuration du papier
                    $dompdf->setPaper('A4', 'portrait');
                    
                    // Rendu du PDF
                    $dompdf->render();
                    
                    // Envoi du PDF au navigateur
                    if(isset($facture) && isset($facture['path'])) {
                        $dompdf->stream($facture['path'], array('Attachment' => false));
                    } else {
                        $dompdf->stream('facture.pdf', array('Attachment' => false));
                    }
                    
                } catch (Exception $e) {
                    echo 'Erreur : ' . $e->getMessage();
                }
            }else{
                $form['alert'] = [
                    "msg" => "Erreur : Vous n'avez pas l'autorisation de voir cette facture",
                    "type" => 'danger'
                ];
                $_SESSION['alert'] = $form['alert'];
                header("Location:index.php");
            }
        }else{
            $form['alert'] = [
                "msg" => 'Erreur : Aucun numéro de commande renseigné',
                "type" => 'danger'
            ];
            $_SESSION['alert'] = $form['alert'];
            header("Location:index.php");
        }
    }else{
        header("Location:index.php?page=login");
    }
}
