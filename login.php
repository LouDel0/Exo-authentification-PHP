<?php
// Démarrage de la session
session_start();

// Pour rediriger les user déjà connectés
if(isset($_SESSION["user"])){
    header("Location: profil.php");
}

// On vérifie si le formulaire a été envoyé
if (!empty($_POST)){
    // Vérification que les champs sont remplis
    if (!isset($_POST["email"], $_POST["pass"]) || empty($_POST["email"]) || empty($_POST["pass"])) {
        echo "<h6 class='text-center text-danger'>Veuillez renseigner votre adresse email et le mot de passe pour vous connecter.</h6>";
    }
    // Véfication que l'email est un email
    else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            echo "<h6 class='text-center text-danger'>Veuillez renseigner une adresse mail valide</h6>";
        }
        
    // Si tout est ok : 
    //Connexion à la bdd
    else {
        require_once "db_connect.php";
        
        $sql="SELECT * FROM `users` WHERE `email` = :email";
        $query = $db->prepare($sql);
        $query->bindValue(":email", $_POST["email"], PDO::PARAM_STR);
        
        $query->execute();
    
        $user=$query->fetch();
        
        //Vérification des users et mots de passe avec la bdd
        if(!$user || !password_verify($_POST["pass"], $user['pass'])) {
            echo "<h6 class='text-center text-danger'>L'utilisateur et/ou le mot de passe est incorrect.</h6>";
        }
        
        // User et mot de passe correct
        // Connexion du user = ouverture de la session
        // Démarrage de la session php
        //$_SESSION : un tab qui stocke des tab. Stocke les infos du user
        // A besoin de session_start() pour fonctionner
        $_SESSION["user"] = [
            "id" => $user["id"],
            "pseudo" => $user["username"],
            "email" => $user["email"]
        ];
        
        // Connexion ok. Redirection
        // ATTENTION : pas d'espace entre 'Location' et ':' => erreur 
        header("Location: profil.php");
    }
    
}

include_once "includes/navbar.php";

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Authentification</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    </head>
    <body>
        <div class="container form-control" style="margin-top:50px">
    <h1 class="text-center">Authentification</h1>
        <form method="post">
            <div class="form-group ">
                <label for="email" class="col-form-label-lg">Adresse email</label>
                <input type="email" name="email" id="email" class="form-control form-control-lg">
            </div>
            <div class="form-group">
                <label for="pass" class="col-form-label-lg">Mot de passe</label>
                <input type="password" name="pass" id="pass" class="form-control form-control-lg">
            </div>
            <br/>
            <div class="text-center">
                <button type="reset" class="btn btn-secondary">Effacer</button>
                <button type="submit" class="btn btn-primary">Se connecter</button>
            </div>
        </form>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>