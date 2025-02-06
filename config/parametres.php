<?php

require_once '../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable('../');
$dotenv->load();

    $config['serveur']= $_ENV['DB_HOST'];
    $config['login'] = $_ENV['DB_USER'];
    $config['mdp'] = $_ENV['DB_PASS'];
    $config['bd'] = $_ENV['DB_NAME'];
