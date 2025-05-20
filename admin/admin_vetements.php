<?php
include '../php/config.php';

session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /connexion.html");
    exit();
}

$vetements = $pdo->query("
    SELECT v.*, c.nom as categorie, c.id_categorie as id_categorie
    FROM vetements v 
    JOIN categories c ON v.id_categorie = c.id_categorie
")->fetchAll(PDO::FETCH_ASSOC);
if (!$vetements) {
    echo "<p>Aucun vêtement trouvé.</p>";
}
$tailles = $pdo->query("SELECT * FROM taillesv")->fetchAll(PDO::FETCH_ASSOC);
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
$tailles_par_vetement = [];
foreach ($tailles as $taille) {
    $tailles_par_vetement[$taille['id_vetement']][] = $taille;
}


if ($_SERVER["REQUEST_METHOD"] === "POST" ) {
    if (!isset($_POST['id_vetement'])) {
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
        header("Location: admin_vetements.php");
        exit();
    } else {    
        $id_vetement = $_POST['id_vetement'];
        $nom = $_POST['nom'];
        $prix = $_POST['prix'];
        $description = $_POST['description'];
        $sexe = $_POST['sexe'];
        $id_categorie = $_POST['id_categorie'];

    // Gestion de l'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $imageName = uniqid() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], '../images/' . $imageName);
    } else {
        $imageName = $_POST['image_path'];
    }

    // Mise à jour du vêtement
    $stmt = $pdo->prepare("UPDATE vetements SET nom = ?, prix = ?, description = ?, sexe = ?, image = ?, id_categorie = ? WHERE id_vetement = ?");
    $stmt->execute([$nom, $prix, $description, $sexe, $imageName, $id_categorie, $id_vetement]);


    $stmtDeleteTailles = $pdo->prepare("DELETE FROM taillesv WHERE id_vetement = ?");
    $stmtDeleteTailles->execute([$id_vetement]);

    $tailles = isset($_POST['tailles']) ? $_POST['tailles'] : [];
    $stocks = isset($_POST['stocks']) ? $_POST['stocks'] : [];

    $stmtInsertTaille = $pdo->prepare("INSERT INTO taillesv (id_vetement, taille, stock) VALUES (?, ?, ?)");

    foreach ($tailles as $index => $taille) {
        $stock = isset($stocks[$index]) ? (int)$stocks[$index] : 0;
        if (!empty($taille)) {
            $stmtInsertTaille->execute([$id_vetement, $taille, $stock]);
        }
    }

    echo "<p style='color: green;'>Vêtement modifié avec succès !</p>";
    header("Location: admin_vetements.php");
    exit();
}

}
if (isset($_GET['supprimer'])) {
    $id = $_GET['supprimer'];
    $stmt = $pdo->prepare("DELETE FROM vetements WHERE id_vetement = ?");
    $stmt->execute([$id]);
    header('Location: admin_vetements.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des vêtements</title>
    <link rel="stylesheet" href="admin_vetement.css">
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

<div class="admin-content">
    <h2>Liste des vêtements</h2>
    <div class="button-container">
        <button class="add-vetement-btn" id="openAddModalBtn">+</button>
    </div>
    <table>
        <tr>
            <th>Nom</th>
            <th>Prix</th>
            <th>Taille</th>
            <th>Stock</th>
            <th>Sexe</th>
            <th>Description</th>
            <th>Image</th>
            <th>Catégorie</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($vetements as $v): ?>
            <?php 
            $tailles = isset($tailles_par_vetement[$v['id_vetement']]) ? $tailles_par_vetement[$v['id_vetement']] : [];
            $taille_text = '';
            $stock_text = '';
            foreach ($tailles as $t) {
                $taille_text .= htmlspecialchars($t['taille']) . "<br>";
                $stock_text .= htmlspecialchars($t['stock']) . "<br>";
            }
            ?>
        <tr>
            <td><?= $v['nom'] ?></td>
            <td><?= $v['prix'] ?> TND</td>
            <td><?= $taille_text ?: '<em>Aucune taille</em>' ?></td>
            <td><?= $stock_text ?: '<em>0</em>' ?></td>
            <td><?= ucfirst(strtolower($v['sexe'])) ?></td>
            <td><?= $v['description'] ?></td>
            <td>
                <?php if ($v['image']): ?>
                    <img src="../images/<?= htmlspecialchars($v['image']) ?>" alt="<?= htmlspecialchars($v['nom']) ?>">
                <?php else: ?>
                    <p>Aucune image</p>
                <?php endif; ?>
            </td>
            <td><?= $v['categorie'] ?></td>
            <td class="actions">
                <a href="#" 
                onclick="openEditModal('<?= $v['id_vetement'] ?>', 
                '<?= htmlspecialchars($v['nom']) ?>',
                '<?= htmlspecialchars($v['prix']) ?>',
                '<?= htmlspecialchars($taille_text) ?>',
                '<?= htmlspecialchars($stock_text) ?>',
                '<?= htmlspecialchars($v['sexe']) ?>',
                '<?= htmlspecialchars($v['description']) ?>',
                '<?= htmlspecialchars($v['image']) ?>',
                '<?= htmlspecialchars($v['categorie']) ?>',
                '<?= htmlspecialchars($v['id_categorie']) ?>')">Modifier</a>
                <a href="admin_vetements.php?supprimer=<?= $v['id_vetement'] ?>" onclick="return confirm('Voulez-vous supprimer ce vêtement ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>


   <!-- Add Modal -->
   <div class="modal" id="addModal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeAddModal()">&times;</span>
            <h3>Ajouter un Vêtement</h3>
            <form  method="POST" enctype="multipart/form-data">
                <input type="text" name="nom" placeholder="Nom" required><br>
                <input type="text" name="description" placeholder="Description" required><br>
                <input type="number" name="prix" placeholder="Prix" required><br>

                <select name="sexe" required>
                    <option value="femme">Femme</option>
                    <option value="homme">Homme</option>
                </select><br>

                <input type="file" name="image" required><br>

                <select name="categorie" id="addCategorie" required>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id_categorie'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                    <?php endforeach; ?>
                </select><br>

                <h5>Tailles disponibles:</h3>
                <div id="addTaillesContainer"></div>

                <button type="submit">Ajouter</button>
            </form>
        </div>
    </div>
</div>
<!-- Edit Modal -->
<div class="modal" id="editModal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeEditModal()">&times;</span>
        <h3>Modifier Vêtement</h3>
        <form id="editForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_vetement" id="editId">

            <div class="form-group">
                <label for="editNom">Nom:</label>
                <input type="text" name="nom" id="editNom" required>
            </div>

            <div class="form-group">
                <label for="editDescription">Description:</label>
                <input type="text" name="description" id="editDescription" required>
            </div>

            <div class="form-group">
                <label for="editPrix">Prix:</label>
                <input type="number" name="prix" id="editPrix" required>
            </div>

            <div class="form-group">
                <label for="editSexe">Sexe:</label>
                <select name="sexe" id="editSexe" required>
                    <option value="homme">Homme</option>
                    <option value="femme">Femme</option>
                </select>
            </div>

            <div class="form-group">
                <label for="editCategorie">Catégorie:</label>
                <select name="categorie" id="editCategorie" required>
                    <!-- Categories will be populated here dynamically -->
                </select>
            </div>

            <div class="form-group" style="display:none">
                <label for="editPrix">Prix:</label>
                <input type="number" name="id_categorie" id="idCategorie">
            </div>

            <div class="form-group">
                <label for="editImage">Changer l'image:</label>
                <img id="editImagePreview" src="" alt="Image actuelle" style="width: 100px; height: 100px; object-fit: cover;">
                <input type="file" name="image" id="editImage">
                <input type="text" name="image_path" id="editImagePath" style="display:none">
            </div>

            <div class="form-group">
                <label>Tailles et Stocks:</label>
                <div id="editTaillesStocks"></div>
                <button type="button" id="addTailleStockBtn" >+</button>
            </div>
            <button type="submit">Enregistrer</button>
        </form>
    </div>
</div>

<script>
    function openNav() {
        document.getElementById("mySidebar").style.width = "250px";
    }
    function closeNav() {
        document.getElementById("mySidebar").style.width = "0";
    }
    document.getElementById("openAddModalBtn").onclick = function() {
        document.getElementById("addModal").style.display = "flex";
    }


    function closeAddModal() {
        document.getElementById("addModal").style.display = "none";
    }
    document.getElementById('addTailleStockBtn').addEventListener('click', function() {
    const taillesStocksContainer = document.getElementById('editTaillesStocks');

    const div = document.createElement('div');
    div.classList.add('taille-stock-pair');
    div.style.display = 'flex';
    div.style.alignItems = 'center';
    div.style.marginBottom = '5px';

    const tailleInput = document.createElement('input');
    tailleInput.type = 'text';
    tailleInput.name = 'tailles[]';
    tailleInput.placeholder = 'Taille';
    tailleInput.required = true;
    tailleInput.style.marginRight = '5px';

    const stockInput = document.createElement('input');
    stockInput.type = 'number';
    stockInput.name = 'stocks[]';
    stockInput.placeholder = 'Stock';
    stockInput.required = true;
    stockInput.style.marginRight = '5px';

    const deleteBtn = document.createElement('button');
    deleteBtn.type = 'button';
    deleteBtn.textContent = 'x';
    deleteBtn.style.backgroundColor = '#ff4d4d';
    deleteBtn.style.color = 'white';
    deleteBtn.style.border = 'none';
    deleteBtn.style.padding = '5px 10px';
    deleteBtn.style.borderRadius = '5px';
    deleteBtn.style.cursor = 'pointer';
    deleteBtn.onclick = function() {
        div.remove();
    };

    div.appendChild(tailleInput);
    div.appendChild(stockInput);
    div.appendChild(deleteBtn);

    taillesStocksContainer.appendChild(div);
});
document.getElementById('addCategorie').addEventListener('change', function () {
    const selectedCat = this.options[this.selectedIndex].text.toLowerCase();
    const taillesContainer = document.getElementById('addTaillesContainer');
    taillesContainer.innerHTML = '';

    let tailles = [];

    if (selectedCat.includes('jeans') || selectedCat.includes('chaussure')) {
        tailles = ['32', '34', '36', '38', '40', '42'];
    } else {
        tailles = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
    }

    tailles.forEach(taille => {
        const label = document.createElement('label');
        label.innerHTML = `
            <input type="checkbox" name="tailles[]" value="${taille}"> ${taille}
        `;

        const stockInput = document.createElement('input');
        stockInput.type = 'number';
        stockInput.name = `stocks[${taille}]`;
        stockInput.min = '0';
        stockInput.placeholder = 'Stock';

        taillesContainer.appendChild(label);
        taillesContainer.appendChild(stockInput);
        taillesContainer.appendChild(document.createElement('br'));
    });
});

// Déclenche une première génération des tailles au chargement
window.addEventListener('load', function () {
    const event = new Event('change');
    document.getElementById('addCategorie').dispatchEvent(event);
});

    var categories = <?php echo json_encode($categories); ?>;
    function openEditModal(id, nom, prix, tailles, stocks, sexe, description, image, categorie, categorieId) {
        console.log(image);
        document.getElementById('editModal').style.display = 'flex';

        document.getElementById('editId').value = id;
        document.getElementById('editNom').value = nom;
        document.getElementById('editDescription').value = description;
        document.getElementById('editPrix').value = prix;
        document.getElementById('editSexe').value = sexe;
        document.getElementById('editImagePath').value = image;
       
        const categorieSelect = document.getElementById('editCategorie');
        categorieSelect.innerHTML = '';

        categories.forEach(function(category) {
            const option = document.createElement('option');
            option.value = category.id_categorie; 
            option.textContent = category.nom;
            if (category.id_categorie == categorieId) {
                option.selected = true;
            }
            categorieSelect.appendChild(option);
        });

        const idCategorie = document.getElementById('idCategorie');
        idCategorie.value = categorieId;
        document.getElementById('editImagePreview').src = "../images/" + image;

        const taillesStocksContainer = document.getElementById('editTaillesStocks');
        taillesStocksContainer.innerHTML = '';

        const taillesArray = tailles.split('<br>').filter(t => t.trim() !== '');
        const stocksArray = stocks.split('<br>').filter(s => s.trim() !== '');

        for (let i = 0; i < taillesArray.length; i++) {
            const div = document.createElement('div');
            div.classList.add('taille-stock-pair');
            div.style.display = 'flex';
            div.style.alignItems = 'center';
            div.style.marginBottom = '5px';

            const tailleInput = document.createElement('input');
            tailleInput.type = 'text';
            tailleInput.name = 'tailles[]';
            tailleInput.value = taillesArray[i];
            tailleInput.placeholder = 'Taille';
            tailleInput.required = true;
            tailleInput.style.marginRight = '5px';

            const stockInput = document.createElement('input');
            stockInput.type = 'number';
            stockInput.name = 'stocks[]';
            stockInput.value = stocksArray[i] || 0;
            stockInput.placeholder = 'Stock';
            stockInput.required = true;
            stockInput.style.marginRight = '5px';

            const deleteBtn = document.createElement('button');
            deleteBtn.type = 'button';
            deleteBtn.textContent = 'x  ';
            deleteBtn.style.backgroundColor = '#ff4d4d';
            deleteBtn.style.color = 'white';
            deleteBtn.style.border = 'none';
            deleteBtn.style.padding = '5px 10px';
            deleteBtn.style.borderRadius = '5px';
            deleteBtn.style.cursor = 'pointer';

            deleteBtn.onclick = function() {
                div.remove();
            };

            div.appendChild(tailleInput);
            div.appendChild(stockInput);
            div.appendChild(deleteBtn);
            taillesStocksContainer.appendChild(div);
        }
    }


    function closeEditModal() {
        document.getElementById("editModal").style.display = "none";
    }
</script>
</div>
</body>
</html>