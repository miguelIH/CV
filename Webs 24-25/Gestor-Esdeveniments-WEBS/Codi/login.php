<?php
// Inicia la sessió
session_start();
// Inclou la configuració de la base de dades
require 'db_config.php';

// Comprova si la sol·licitud és de tipus POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obté les dades del formulari
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Cerca l'usuari a la base de dades
    $stmt = $pdo->prepare("SELECT * FROM usuaris WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    // Comprova si l'usuari existeix i la contrasenya és correcta
    if ($user && password_verify($password, $user['password'])) {
        // Estableix les variables de sessió
        $_SESSION['user_id'] = $user['id_usuari'];
        $_SESSION['username'] = $user['nom'];
        // Estableix les cookies
        setcookie('user_id', $user['id_usuari'], time() + (86400 * 30), "/"); // 30 dies
        setcookie('username', $user['nom'], time() + (86400 * 30), "/"); // 30 dies
        // Redirigeix a la pàgina del calendari
        header('Location: calendario.php');
        exit;
    } else {
        // Redirigeix a la pàgina d'inici de sessió amb un missatge d'error
        header('Location: iniciarSessio.html?error=1');
        exit;
    }
}
?>