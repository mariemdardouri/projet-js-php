<?php
include '../php/config.php';
session_start();
$sql = "SELECT * FROM vetements"; 
$stmt = $conn->query($sql);
$vetements = $stmt->fetchAll(); 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Produits</title>
    <style>
        /* Style simplifié type Bershka */
        body { font-family: sans-serif; background: #fff; margin: 0; }
        .product-grid { display: flex; flex-wrap: wrap; gap: 20px; padding: 20px; }
        .product-card { border: 1px solid #ccc; border-radius: 10px; padding: 15px; width: 250px; }
        .product-card img { width: 100%; height: 300px; object-fit: cover; }
        .size-options span { margin: 5px; padding: 5px 10px; border: 1px solid #000; cursor: pointer; border-radius: 5px; display: inline-block; }
    </style>
</head>
<body>

<div class="product-grid">
    <?php while ($row = $result->fetch_assoc()) : ?>
        <div class="product-card">
            <img src="<?= $row['image'] ?>" alt="<?= htmlspecialchars($row['nom']) ?>">
            <h3><?= htmlspecialchars($row['nom']) ?></h3>
            <p><?= number_format($row['prix'], 2) ?> €</p>
            <div class="sizes">
                <p>Sélectionne une taille :</p>
                <div class="size-options">
                    <?php
                    $id = $row['id'];
                    $categorie = strtolower($row['categorie']);
                    $taille_sql = "SELECT taille FROM tailles WHERE vetement_id=$id AND stock > 0";

                    // Si jeans ou chaussures : filtrer uniquement les tailles standard
                    if ($categorie === "jeans" || $categorie === "chaussure") {
                        $taille_sql .= " AND taille IN ('32','34','36','38','40','42')";
                    }

                    $taille_result = $conn->query($taille_sql);
                    while ($t = $taille_result->fetch_assoc()) {
                        echo "<span>{$t['taille']}</span>";
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>

<?php $conn->close(); ?>
