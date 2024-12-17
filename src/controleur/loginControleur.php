<?php
    function loginControleur($twig, $db){
        $form = array();

        if (isset($_POST['btLogin'])){
           $form['valide'] = true;
            $inputEmail = $_POST['inputEmail'];
            $inputPassword = $_POST['inputPassword'];
            $utilisateur = new Utilisateur($db);
            $unUtilisateur = $utilisateur->connect($inputEmail);
            if ($unUtilisateur!=null){
                if(!password_verify($inputPassword,$unUtilisateur['mdp'])){
                    $form['valide'] = false;
                    $form['message'] = 'Login ou mot de passe incorrect';
                }else{
                    if ($unUtilisateur['deleteAt'] != null){
                        $form['valide'] = false;
                        $form['message'] = 'Ce compte à été suprimé';
                    }else{
                        $_SESSION['login'] = $inputEmail;
                        $_SESSION['fname'] = $unUtilisateur['prenom'];
                        $_SESSION['lname'] = $unUtilisateur['nom'];
                        $_SESSION['role'] = $unUtilisateur['idRole'];
                        header("Location:index.php");
                    }
                }
            }
            else{
                $form['valide'] = false;
                $form['message'] = 'Login ou mot de passe incorrect';
            }
        }
        echo $twig->render('login.html.twig', array('form'=>$form));
    }
?>