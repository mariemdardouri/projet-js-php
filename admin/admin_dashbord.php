<?php
include '../php/config.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /connexion.html");
    exit();
}
$sql = "SELECT * FROM vetements"; 
$stmt = $pdo->query($sql);
$vetements = $pdo->query("SELECT * FROM vetements")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Vêtements Chic</title>
    <link rel="stylesheet" href="admin_dashbord.css?v=1">
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
    <span class="menu-icon" onclick="openNav()">&#9776;</span>
    <div class="logo">Admin - Vêtements Chic</div>
    <div class="search-icons">
        <input type="text" placeholder="Rechercher...">
        <span>Bienvenue, <?php echo $_SESSION['user']['prenom']; ?></span>
        <a href="../php/logout.php"><button>Déconnexion</button></a>
    </div>
</header> 



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
                            <span class="size"><?php echo htmlspecialchars($taille['taille']); ?></span>
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
function openNav() {
    document.getElementById("mySidebar").style.width = "250px";
}

function closeNav() {
    document.getElementById("mySidebar").style.width = "0";
}
</script>

</body>
</html>