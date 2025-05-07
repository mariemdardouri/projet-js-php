<?php
include '../php/config.php';

$vetements = $pdo->query("SELECT * FROM vetements WHERE sexe = 'homme'")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homme - Vêtements Chic</title>
    <link rel="stylesheet" href="homme.css">
    
</head>
<body>
     <header>
        <div class="search-icons">
            <input type="search" placeholder="Rechercher...">
            <a href="../connexion.html"> <button title="Connexion">Connexion</button></a>
            <a href="#" title="Favoris">❤</a>
        </div>
        <div class="logo">Vêtements Chic</div>
        <nav class="nav">
            <a href="../home.html">Acceuil</a>
            <a href="../femme/femme.php">Femmes</a>
            <a href="../homme/homme.php">Hommes</a>
        </nav>
    </header>

    <section class="categories">
        <a href="femme.php" class="category-btn">Tous</a>
        <a href="jeanf.html" class="category-btn">Jeans</a>
        <a href="vestef.html" class="category-btn">Vestes</a>
        <a href="pullf.html" class="category-btn">Pulls</a>
        <a href="accessoiref.html" class="category-btn">Accessoires</a>
        <a href="chaussuref.html" class="category-btn">Chaussures</a>
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
