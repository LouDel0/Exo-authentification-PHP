<?php

session_start();

// Pour rediriger les user déjà connectés
if(!isset($_SESSION["user"])){
    header("Location: connexion.php");
}

// Fonction qui supprime une var
unset($_SESSION["user"]);

header("Location: index.php");
