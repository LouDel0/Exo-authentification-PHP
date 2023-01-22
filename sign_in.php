<?php
// Démarrage de la session php
session_start();

// Pour rediriger les user déjà connectés
if(isset($_SESSION["user"])){
    header("Location: profil.php");
}

// On vérifie si le formulaire a été envoyé
if(!empty($_POST)){
    // Formulaire est envoyé

    //Vérification que les champs existent avec isset, et qu'ils sont remplis avec !empty
    if(isset($_POST["pseudo"], $_POST["email"], $_POST["pass"], $_POST["confirmpass"])
    && !empty($_POST["pseudo"]) && !empty($_POST["email"]) && !empty($_POST["pass"]) && !empty($_POST["confirmpass"])){
        // Récupération et protection des données
        // Nettoyage du pseudo
        $pseudo = strip_tags($_POST["pseudo"]);

        //Adresse mail valide
        if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
            echo "<h6 class='text-center text-danger'>Adresse email invalide</h6>";
        }
        
        //Hachage du mdp
        $pass = password_hash($_POST["pass"], PASSWORD_ARGON2ID);

        //Cryptage du mdp
        if (!preg_match('/(?=^.{8,}$)(?=.*\d)(?=.*\W+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/', $_POST["pass"])) {
            echo "<h6 class='text-center text-danger'>Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.</h6>";
        }

        //Confirmation du mdp 
        if($_POST["pass"] !== $_POST["confirmpass"]) {
            echo "<h6 class='text-center text-danger'>Mot de passe invalide</h6>";
        }

        //Enregistrement dans la bdd
        require_once "db_connect.php";
        $sql = "INSERT INTO `users` (`username`,`email`,`pass`) VALUES (:pseudo, :email, '$pass')";

        $query = $db->prepare($sql);
        //bindValue pour connecter les var Php à leurs param SQL
        $query->bindValue(":pseudo", $pseudo, PDO::PARAM_STR);
        $query->bindValue(":email", $_POST["email"], PDO::PARAM_STR);

        $query->execute();

        // Récupérer l'id du nouveau user
        $id = $db->lastInsertId();

        // Connexion du user 
        $_SESSION["user"] = [
            "id" => $id,
            "pseudo" => $pseudo,
            "email" => $_POST["email"]
        ];

        // Connexion ok. Redirection
        // ATTENTION : pas d'espace entre 'Location' et ':' => erreur 
        header("Location: profil.php");
    }
    else {
        echo "<h6 class='text-center text-danger'>Le formulaire est incomplet</h6>";
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
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
<div class="container form-control" style="margin-top:50px">
    <h1 class="text-center">Inscription</h1>
        <form method="post">
            <div class="form-group ">
                <label for="pseudo" class="col-form-label-lg">Pseudo</label>
                <input type="text" name="pseudo" id="pseudo" class="form-control form-control-lg">
            </div>
            <div class="form-group ">
                <label for="email" class="col-form-label-lg">Adresse email</label>
                <input type="email" name="email" id="email" class="form-control form-control-lg">
            </div>
            <div class="form-group">
                <label for="pass" class="col-form-label-lg">Mot de passe</label>
                <input type="password" name="pass" id="pass" class="form-control form-control-lg" pattern="(?=^.{8,}$)(?=.*\d)(?=.*\W+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$">
                <label for="confirmpass" class="col-form-label-lg">Confirmation du mot de passe</label>
                <input type="password" name="confirmpass" id="confirmpass" class="form-control form-control-lg">
            </div>
            <br/>
            <div class="text-center">
                <button type="reset" class="btn btn-secondary">Effacer</button>
                <button type="submit" class="btn btn-primary">S'inscrire</button>
            </div>
        </form>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>