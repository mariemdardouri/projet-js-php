<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    var_dump($user);  

    if ($user && password_verify($password, $user['mot_de_passe'])) {
        $_SESSION['user'] = $user;

        if ($user['role'] === 'admin') {
            header("Location: ../admin/admin_dashbord.php");
        } else {
            header("Location: ../home.php");
        }
        exit();
    } else {
        echo "Email ou mot de passe incorrect.";
    }
}
?>
