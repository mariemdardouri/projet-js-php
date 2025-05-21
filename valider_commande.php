<?php
session_start();
include 'php/config.php';

// Rediriger si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user']['id_user'];
    $adresse = $_POST['addressInput'];
    $paiement = $_POST['payment'];
    $date_commande = $_POST['date_commande'] ?? date('Y-m-d');
    $panier_json = $_POST['panier'];
    $panier = json_decode($panier_json, true);

    if (empty($adresse) || empty($paiement) || empty($panier)) {
        die("Données manquantes.");
    }

    // Étape 1 : Insérer dans la table commandes
    $stmt = $pdo->prepare("INSERT INTO commandes (id_user, adresse, paiement,date_commande) VALUES (?, ?, ?,?)");
    $stmt->execute([$user_id, $adresse, $paiement,$date_commande]);

    $id_commande = $pdo->lastInsertId();

    // Étape 2 : Insérer les détails de la commande
    $stmt_detail = $pdo->prepare("INSERT INTO details_commande (id_commande, id_vetement, taille, quantite, prix) VALUES (?, ?, ?, ?, ?)");
    error_log("Panier reçu: " . print_r($panier, true));
    foreach ($panier as $article) {
        if (!isset($article['id_vetement'], $article['taille'], $article['quantite'], $article['prix'])) {
            die("Article invalide dans le panier.");
        }

        $id_vetement = $article['id_vetement'];
        $taille = $article['taille'];
        $quantite = $article['quantite'];
        $prix = $article['prix'];

        $stmt_detail->execute([$id_commande, $id_vetement, $taille, $quantite, $prix]);
    }


    // Vider le panier et rediriger
    echo "<script>
        localStorage.removeItem('cart');
        window.location.href = 'home.php?success=1';
    </script>";
    exit();
} else {
    echo "Méthode non autorisée.";
}
?>
