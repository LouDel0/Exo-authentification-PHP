<?php
// Paramètres de connexion à la base de données
$host = "localhost";
$dbname = "exo_auth";
$username = "root";
$password = "";

// Connexion à la base de données
try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Configurer le mode d'erreur PDO pour lever des exceptions
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur lors de la connexion à la base de données : " . $e->getMessage();
}
