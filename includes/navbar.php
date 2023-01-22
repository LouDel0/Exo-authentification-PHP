<nav>
    <h1>Titre</h1>
    <ul>
        <li><a href="./index.php">Accueil</a></li>
        <?php if(!isset($_SESSION["user"])): ?>
            <li><a href="./login.php">Connexion</a></li>
            <li><a href="./sign_in.php">Inscription</a></li>
        <?php else: ?>
            <li><a href="./disconnect.php">Déconnexion</a></li>
        <?php endif; ?>
    </ul>
</nav>
