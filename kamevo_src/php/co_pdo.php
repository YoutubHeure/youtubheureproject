<?php
$dsn = 'mysql:dbname=kamevobdd;host=localhost';
$user = 'root';
$password = 'root';

try {
    $bdd = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    echo 'Connexion échouée : ' . $e->getMessage();
}

?>
