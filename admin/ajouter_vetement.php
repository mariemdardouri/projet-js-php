<?php
include '../php/config.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /connexion.html");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom'];
    $id_categorie = $_POST['categorie'];
    $prix = $_POST['prix'];
    $description = $_POST['description'];
    $sexe = $_POST['sexe'];
    $tailles = isset($_POST['tailles']) ? $_POST['tailles'] : [];
    $stocks = isset($_POST['stocks']) ? $_POST['stocks'] : [];

    $imageName = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $imageName = uniqid() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], '../images/' . $imageName);
    }

    $stmt = $pdo->prepare("INSERT INTO vetements (nom, description, prix, sexe, image, id_categorie) 
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $description, $prix, $sexe, $imageName, $id_categorie]);

    $id_vetement = $pdo->lastInsertId();

    $stmtTaille = $pdo->prepare("INSERT INTO taillesv (id_vetement, taille, stock) 
                                 VALUES (?, ?, ?)");

    foreach ($tailles as $taille) {
        $stock = isset($stocks[$taille]) ? (int)$stocks[$taille] : 0;
        $stmtTaille->execute([$id_vetement, $taille, $stock]);
    }

    echo "<p style='color:green;text-align:center;'>Vêtement ajouté avec succès avec tailles et stock!</p>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un vêtement</title>
    <link rel="stylesheet" href="/home.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .form-container h1 {
            text-align: center;
            margin-bottom: 25px;
        }

        form label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        form input, form select, form textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        form button {
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #f76c6c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #e55b5b;
        }
    </style>
</head>
<body>

<header>
    <div class="search-icons">
        <span>Admin: <?php echo $_SESSION['user']['email']; ?></span>
        <a href="/logout.php"><button>Déconnexion</button></a>
    </div>
    <div class="logo">Admin - Vêtements Chic</div>
    <nav class="nav">
        <a href="admin_dashboard.php">Dashboard</a>
    </nav>
</header>

<div class="form-container">
    <h1>Ajouter un Vêtement</h1>
    <form action="ajouter_vetement.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="nom" placeholder="Nom" required><br>
        <input type="text" name="description" placeholder="Description" required><br>
        <input type="number" name="prix" placeholder="Prix" required><br>

        <select name="sexe" required>
            <option value="femme">Femme</option>
            <option value="homme">Homme</option>
        </select><br>

        <input type="file" name="image" required><br>

        <select name="categorie" required>
            <option value="1">Jeans</option>
            <option value="2">Pulls</option>
            <!-- add more categories -->
        </select><br>

        <h3>Tailles disponibles:</h3>
        <?php
        $tailles = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        foreach ($tailles as $taille) {
            echo "
            <label>
                <input type='checkbox' name='tailles[]' value='$taille'> $taille
            </label>
            Stock:
            <input type='number' name='stocks[$taille]' min='0' placeholder='Stock'>
            <br>
            ";
        }
        ?>

        <button type="submit">Ajouter</button>
    </form>
</div>

</body>
</html>
