<?php
include '../php/config.php'; 

session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /connexion.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ajouter']) && !empty($_POST['nom'])) {
        $stmt = $pdo->prepare("INSERT INTO categories (nom) VALUES (?)");
        $stmt->execute([$_POST['nom']]);
    }

    if (isset($_POST['modifier']) && !empty($_POST['nom']) && !empty($_POST['id_categorie'])) {
        $stmt = $pdo->prepare("UPDATE categories SET nom = ? WHERE id_categorie = ?");
        $stmt->execute([$_POST['nom'], $_POST['id_categorie']]);
    }

    
}

if (isset($_GET['supprimer'])) {
    $id = $_GET['supprimer'];
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id_categorie = ?");
    $stmt->execute([$id]);
    header('Location: admin_categories.php');
    exit();
}
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des catégories - Admin</title>
    <link rel="stylesheet" href="../home.css">
    <link rel="stylesheet" href="admin_categories.css">
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

<section class="admin-content">
    <h2>Gestion des Catégories</h2>

    <div class="button-container">
        <button class="add-category-btn" id="openModalBtn">+</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($categories as $cat): ?>
            <tr>
                <td><?= htmlspecialchars($cat['nom']) ?></td>
                <td class="actions">
                    <a href="#" onclick="openEditModal('<?= $cat['id_categorie'] ?>', '<?= htmlspecialchars($cat['nom']) ?>')">Modifier</a>
                    <a href="admin_categories.php?supprimer=<?= $cat['id_categorie'] ?>" onclick="return confirm('Voulez-vous supprimer cette catégorie ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>

<div class="modal" id="addModal">
    <div class="modal-content">
        <span class="close-btn" id="closeModalBtn">&times;</span>
        <h3>Ajouter une nouvelle catégorie</h3>
        <form method="post">
            <div class="form-group">
                <label name="nom">Nom:</label>
                <input type="text" name="nom" placeholder="Nom de la catégorie" required>
            </div>
            <button type="submit" name="ajouter">Ajouter</button>
        </form>
    </div>
</div>
<!-- MODAL Modifier -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeEditModal">&times;</span>
        <h2>Modifier Catégorie</h2>
        <form method="post">
            <input type="hidden" name="id_categorie" id="editId">
            <input type="text" name="nom" id="editNom" required>
            <button type="submit" name="modifier">Modifier</button>
        </form>
    </div>
</div>
<script>
document.getElementById('openModalBtn').addEventListener('click', function() {
    document.getElementById('addModal').style.display = 'flex';
});

document.getElementById('closeModalBtn').addEventListener('click', function() {
    document.getElementById('addModal').style.display = 'none';
});
function openNav() {
    document.getElementById("mySidebar").style.width = "250px";
}

function closeNav() {
    document.getElementById("mySidebar").style.width = "0";
}
window.onclick = function(event) {
    const addModal = document.getElementById('addModal');
    const editModal = document.getElementById('editModal');
    
    if (event.target == addModal) {
        addModal.style.display = "none";
    }
    if (event.target == editModal) {
        editModal.style.display = "none";
    }
}
function openEditModal(id_categorie, nom) {
    document.getElementById('editId').value = id_categorie;
    document.getElementById('editNom').value = nom;
    document.getElementById('editModal').style.display = 'flex';
}


document.getElementById('closeEditModal').onclick = function() {
    document.getElementById('editModal').style.display = 'none';
}

</script>

</body>
</html>