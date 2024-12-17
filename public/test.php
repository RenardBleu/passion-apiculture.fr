<?php
// Informations de connexion à la base de données
$host = 'renardserveur.freeboxos.fr';
$dbname = 'DBFoxtech';
$user = 'iarenard';
$password = 'c*KM8Q%4W1aRMW';

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Si la connexion réussit, on peut afficher un message de succès (optionnel)
    echo "Connexion à la base de données réussie !";

} catch (PDOException $e) {
    // En cas d'erreur, on affiche le message d'erreur
    echo "Erreur de connexion : " . $e->getMessage();
}