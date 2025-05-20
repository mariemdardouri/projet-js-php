<?php
include '../php/config.php';
session_start();
$user = $_SESSION['user'] ?? null;
$vetements = $pdo->query("SELECT v.* 
    FROM vetements v
    JOIN categories c ON v.id_categorie = c.id_categorie
    WHERE v.sexe = 'femme' AND c.nom = 'vestes'")->fetchAll();
if ($vetements == 0) {
    $message = "Désolé, aucun vêtement trouvé dans cette catégorie.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Femme - Vêtements Chic</title>
    <link rel="stylesheet" href="femme.css?v=<?php echo time(); ?>">
    
</head>
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
                <img src="../images/<?php echo htmlspecialchars($vetement['image']); ?>" alt="<?php echo htmlspecialchars($vetement['nom']); ?>">
                
                <div class="size-options">
                    <?php foreach ($tailles as $taille): ?>
                        <span class="size"><?php echo htmlspecialchars($taille['taille']); ?></span>
                    <?php endforeach; ?>
                </div>
                
                <p class="price">Prix : <?php echo number_format($vetement['prix'], 2); ?> TND</p>
                <button class="order-btn">Commander</button>
            </div>
        <?php endforeach; ?>
    </section>
</body>
</html>
