<?php
    function inscrireControleur($twig, $db){
        $form = array();
        if (isset($_POST['btInscrire'])){
            $inputEmail = $_POST['inputEmail'];
            $inputPassword = $_POST['inputPassword'];
            $inputPassword2 =$_POST['inputPassword2'];
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $role = $_POST['role'];
            $form['valide'] = true;
            if ($inputPassword!=$inputPassword2){
                $form['valide'] = false;
                $form['message'] = 'Les mots de passe sont différents';
            }else{
                if(strlen($inputPassword)<8){
                    $form['valide'] = false;
                    $form['message'] = 'Le mots de passe est trop faible';
                }else{
                    $utilisateur = new Utilisateur($db);
                    $unUtilisateur = $utilisateur->connect($inputEmail);
                    if($unUtilisateur['email'] === $inputEmail){
                        $form['valide'] = false;
                        $form['message'] = 'Un utilisateur est déjà inscrit avec cette adresse mail';
                    }else{
                        try{
                            var_dump($_POST);
                            $utilisateur = new Utilisateur($db);
                            $utilisateur->insert($inputEmail, password_hash($inputPassword, PASSWORD_DEFAULT), $nom, $prenom, $role);
                        }
                        catch(Exception $e){
                            $form['valide'] = false;
                            $form['message'] = 'Problème d\'insertion dans la table utilisateur ';
                            //$form['message'] = $e;
                        }
                    }
                }
            }
            $form['email'] = $inputEmail;
            $form['role'] = $role;
            $form['nom'] = $nom;
            $form['prenom'] = $prenom;
        }
        echo $twig->render('inscrire.html.twig', array('form'=>$form));
       }       
?>