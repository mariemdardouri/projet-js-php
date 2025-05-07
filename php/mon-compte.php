<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: connexion.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Compte</title>
</head>
<body>
    <h1>Bienvenue, <?php echo $_SESSION['prenom'] . " " . $_SESSION['nom']; ?> !</h1>
    <p>Vous êtes connecté à votre compte.</p>
    <a href="commander.html">Passer une commande</a><br>
    <a href="php/logout.php">Se déconnecter</a>
</body>
</html>
