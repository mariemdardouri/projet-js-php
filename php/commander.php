<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['name'];
    $adresse = $_POST['address'];
    $phone = $_POST['phone'];
    $paiement = $_POST['payment'];
    $notes = $_POST['notes'];

    $stmt = $pdo->prepare("INSERT INTO commandes (nom, adresse, telephone, paiement, remarques) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $adresse, $phone, $paiement, $notes]);

    echo "Commande envoyée avec succès !";
}
?>
