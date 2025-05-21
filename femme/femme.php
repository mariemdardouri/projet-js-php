<?php
include '../php/config.php';
 
session_start();
$user = $_SESSION['user'] ?? null;
$vetements = $pdo->query("SELECT * FROM vetements WHERE sexe = 'femme'")->fetchAll();
include '../panier/panier-modal.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Femme - Vêtements Chic</title>
    <link rel="stylesheet" href="femme.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../panier/panier-modal.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<script src="../panier/panier-modal.js?v=<?php echo time(); ?>" defer></script>
<body>
    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    </div>  
    <header>
        <nav class="nav">
            <span class="menu-icon" onclick="openNav()">&#9776;</span>
            <a href="femme.php">Femmes</a>
            <a href="../homme/homme.php">Hommes</a>
        </nav>
        <div class="logo">Vêtements Chic</div>
        <div class="search-icons">
            <input type="text" placeholder="Rechercher...">
            <a href="#" class="panier-icon-global" title="Voir panier" onclick="ouvrirPanierComplet()">
                <i class="bi bi-cart-fill" style="font-size: 24px; position: relative;"></i>
                <span class="cart-count" id="cart-count">0</span>
            </a>
            <?php if ($user): ?>
                <span>Bienvenue, <?= htmlspecialchars($user['prenom']) ?> !</span>
                <form action="../php/logout.php" method="post" style="display:inline;">
                    <button type="submit">Déconnexion</button>
                </form>
            <?php else: ?>
                <a href="../connexion.html"><button>Connexion</button></a>
            <?php endif; ?>
            <a href="#" title="Favoris">❤</a>
        </div>
    </header>

    <section class="categories">
        <a href="femme.php" class="category-btn">Tous</a>
        <a href="jeanf.php" class="category-btn">Jeans</a>
        <a href="vestef.php" class="category-btn">Vestes</a>
        <a href="pullf.php" class="category-btn">Pulls</a>
        <a href="chemisef.php" class="category-btn">Chemises</a>
        <a href="accessoiref.php" class="category-btn">Accessoires</a>
        <a href="chaussuref.php" class="category-btn">Chaussures</a>
    </section>

    <section class="product-grid">
        <?php foreach ($vetements as $vetement): ?>
            <?php
            $stmtTailles = $pdo->prepare("SELECT * FROM taillesv WHERE id_vetement = ?");
            $stmtTailles->execute([$vetement['id_vetement']]);
            $tailles = $stmtTailles->fetchAll();
            ?>
            <div class="product-item">
                <div class="image-container">
                    <img src="../images/<?php echo htmlspecialchars($vetement['image']); ?>" alt="<?php echo htmlspecialchars($vetement['nom']); ?>">
                    <div class="size-overlay">
                        <label>Sélectionne une taille :</label>
                        <div class="size-options">
                            <?php foreach ($tailles as $taille): ?>
                                <a href="#" 
                                class="panier-icon" 
                                title="Ajouter au panier" 
                                onclick="ajouterAuPanier(this)" 
                                data-id="<?= $vetement['id_vetement'] ?>"
                                data-nom="<?= htmlspecialchars($vetement['nom']) ?>"
                                data-prix="<?= $vetement['prix'] ?>"
                                data-image="<?= htmlspecialchars($vetement['image']) ?>"
                                data-taille="<?= $taille['taille'] ?>">
                                <span class="size"><?php echo htmlspecialchars($taille['taille']); ?></span>
                                </a>
                                
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="product-name"><?php echo htmlspecialchars($vetement['nom']); ?></div>
                <p class="price"><?php echo number_format($vetement['prix'], 2); ?> TND</p>
            </div>
        <?php endforeach; ?>
    </section>


    <script>
        //Nav bar 
        function openNav() {
            document.getElementById("mySidebar").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("mySidebar").style.width = "0";
        }
       
    </script>
</body>
</html>
