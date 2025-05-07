<?php
include '../php/config.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /connexion.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site de Vêtements</title>
    <link rel="stylesheet" href="home.css">
    <style>
        .sidebar {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1001;
            top: 0;
            left: 0;
            background-color: #fff;
            overflow-x: hidden;
            transition: 0.3s;
            padding-top: 60px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.2);
        }

        .sidebar a {
            padding: 15px 30px;
            text-decoration: none;
            font-size: 18px;
            color: #333;
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background-color: #f76c6c;
            color: #fff;
        }

        .closebtn {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 30px;
            cursor: pointer;
        }

        #overlay {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }

        .menu-icon {
            font-size: 26px;
            cursor: pointer;
            
        }.nav {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        .nav a:hover {
            color: #f76c6c;
        }
    </style>

</head>
<body>
    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="../admin/admin_utilisateurs.php">Gérer les utilisateurs</a>
        <a href="../admin/admin_vetements.php">Gérer les vêtements</a>
        <a href="../admin/admin_categories.php">Gérer les catégories</a>
        <a href="../admin/admin_commandes.php">Gérer les commandes</a>
    </div>
    <header>
        
        <nav class="nav">
            <span class="menu-icon" onclick="openNav()">&#9776;</span>
            <a href="femme/femme.php">Femmes</a>
            <a href="homme/homme.php">Hommes</a>
        </nav>
        <div class="logo">Vêtements Chic</div>
        <div class="search-icons">
            <input type="search" placeholder="Rechercher...">
            <span>Bienvenue, <?php echo $_SESSION['user']['prenom']; ?></span>
            <a href="../php/logout.php"><button>Déconnexion</button></a>
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
        document.getElementById("overlay").style.display = "block";
    }

    function closeNav() {
        document.getElementById("mySidebar").style.width = "0";
        document.getElementById("overlay").style.display = "none";
    }
    </script>
</body>
</html>
