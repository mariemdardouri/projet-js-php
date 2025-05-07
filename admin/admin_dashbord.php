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
    <link rel="stylesheet" href="../home.css">
    <style>
       header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f8f8f8;
            padding: 10px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
        }

        .logo {
            display: flex;
            font-size: 24px;
            font-weight: bold;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .menu-icon {
            font-size: 24px;
            cursor: pointer;
        }

        .sidebar {
            height: 100%;
            width: 0;
            position: fixed;
            top: 0;
            left: 0;
            background-color: white;
            overflow-x: hidden;
            transition: 0.3s;
            padding-top: 60px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.2);
            z-index: 1000;
        }

        .sidebar a {
            padding: 15px 30px;
            text-decoration: none;
            font-size: 18px;
            color: black;
            display: block;
            transition: 0.3s;
        }
        .sidebar a:hover {
            background-color: #f76c6c;
            color: white;
        }

        .closebtn {
            position: absolute;
            top: 15px;
            right: 25px;
            font-size: 30px;
            cursor: pointer;
        }

        .search-icons {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-icons input {
            padding: 8px 15px;
            border-radius: 20px;
            border: 1px solid #ccc;
        }

        .search-icons span {
            font-weight: bold;
        }

        .admin-content {
            padding: 40px;
            background-color: #fff;
            max-width: 1000px;
            margin: 30px auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .admin-content h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .admin-buttons {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
        }

        .admin-buttons a {
            padding: 15px 30px;
            background-color: #f76c6c;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: 0.3s;
        }

        .admin-buttons a:hover {
            background-color: #e55b5b;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
            color: #333;
        }
        
        
        .search-icons {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .search-icons input[type="search"] {
            padding: 5px 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .search-icons button {
            padding: 5px 10px;
            font-size: 14px;
            background-color: #f76c6c;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-icons button:hover {
            background-color: #e55b5b;
        }

        .search-icons a {
            text-decoration: none;
            font-size: 20px;
            color: #333;
        }

        .search-icons a:hover {
            color: #f76c6c;
        }

        .nav {
            display: flex;
            gap: 20px;
        }

        .nav a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        .nav a:hover {
            color: #f76c6c;
        }

        .categories {
            display: flex;
            gap: 10px;
            padding: 20px;
            justify-content: center;
            flex-wrap: wrap;
            background-color: #fff;
        }

        .category-btn {
            text-decoration: none;
            padding: 8px 15px;
            background-color: #f4f4f4;
            color: #333;
            font-size: 14px;
            border-radius: 20px;
            transition: background-color 0.3s ease;
        }

        .category-btn:hover {
            background-color: #333;
            color: #fff;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            padding: 20px;
            background-color: #f8f8f8;
        }

        .product-item {
            overflow: hidden;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .product-item img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }

        .product-item h3 {
            text-align: center;
            padding: 10px 0;
            margin: 0;
            font-size: 16px;
            color: #333;
        }

        .size-options {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }

        .size {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 14px;
            color: #333;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .size:hover {
            background-color: #f76c6c;
            color: white;
        }

        .price {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
            color: #333;
        }

        .order-btn {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #f76c6c;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        .order-btn:hover {
            background-color: #e55b5b;
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