<?php
include '../php/config.php';
$commandes = $pdo->query("SELECT * FROM commandes")->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Liste des commandes</h2>
<table>
    <tr><th>ID</th><th>Utilisateur</th><th>Date</th><th>Total</th></tr>
    <?php foreach ($commandes as $cmd): ?>
    <tr>
        <td><?= $cmd['id'] ?></td>
        <td><?= $cmd['user_id'] ?></td>
        <td><?= $cmd['date'] ?></td>
        <td><?= $cmd['total'] ?>€</td>
    </tr>
    <?php endforeach; ?>
</table>
