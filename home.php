<?php
include 'php/config.php';
session_start();
$user = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site de Vêtements</title>
    <link rel="stylesheet" href="home.css?v=1">

</head>
<body>
    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    </div>
    <header>
        <nav class="nav">
            <span class="menu-icon" onclick="openNav()">&#9776;</span>
            <a href="femme/femme.php">Femmes</a>
            <a href="homme/homme.php">Hommes</a>
        </nav>
        <div class="logo">Vêtements Chic</div>
        <div class="search-icons">
            <input type="text" placeholder="Rechercher...">
            <?php if ($user): ?>
                <span>Bienvenue, <?= htmlspecialchars($user['prenom']) ?> !</span>
                <form action="php/logout.php" method="post" style="display:inline;">
                    <button type="submit">Déconnexion</button>
                </form>
            <?php else: ?>
                <a href="connexion.html"><button>Connexion</button></a>
            <?php endif; ?>
            <a href="#" title="Favoris">❤</a>
        </div>
    </header>

    <section class="hero">
        <div>
            <h1>Découvrez Notre Collection</h1>
            <p>Tendance, élégance et style.</p>
            <p>Shop Now</p>
        </div>
    </section>
    <section class="hero-second">
        
    </section>
    <section class="hero-third">
        
    </section>
    <footer class="newsletter-footer">
        <div class="newsletter-section">
            <h2>LE VETEMENTS DE LA SAISON</h2>
            <p class="newsletter-subtitle">TU TE TROUVES DANS NOTRE NEWSLETTER !</p>
            <p class="newsletter-description">Préparez-vous pour découvrir toutes les tendances, les collaborations et les petits prix exclusifs !</p>
            <form>
                <input type="email" placeholder="Adresse e-mail" class="email-input">
                <button class="subscribe-button">I'm in</button>
            </form>
            <a href="#" class="unsubscribe-link">Je souhaite me désabonner</a>
        </div>
   <hr> 
        <div class="sales-info">
            <h3>Soldes sur la mode pour femme</h3>
            <p>
                Les musts de la saison Hiver 2025 pour obtenir 
                le look parfait.<br> Mise sur les pantalons droits, les
                blazers, les sweats et les vêtements colorés qui te 
                donneront un aspect toujours parfait en automne.<br> Fais 
                revivre le style le plus vintage en portant ton jean 
                favori avec un pull en maille et des bottes.<br> Lorsqu’il 
                
                fait très froid, n’oublie pas ton manteau et un bonnet
                 pour être totalement équipé.<br> Découvre les vêtements qui 
                 feront la différence cette saison.
            </p>
            
        </div>
        <hr> 
        <div class="footer-links">
            <div class="footer-column">
                <h4>Aide</h4>
                <a href="#">Questions fréquentes</a>
                <a href="#">Effectuer un retour</a>
            </div>
            <div class="footer-column">
                <h4>Entreprise</h4>
                <a href="#">Qui sommes-nous ?</a>
                <a href="#">Rejoignez notre équipe</a>
            </div>
            <div class="footer-column">
                <h4>Moyens de paiement</h4>
                <p>Visa, COD</p>
            </div>
        </div>
    </footer>
    <script>
        function openNav() {
        document.getElementById("mySidebar").style.width = "250px";
    }

    function closeNav() {
        document.getElementById("mySidebar").style.width = "0";
    }
    </script>
</body>
</html>
