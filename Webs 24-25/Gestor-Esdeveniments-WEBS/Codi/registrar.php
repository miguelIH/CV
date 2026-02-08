<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclou la configuració de la base de dades
require 'db_config.php';

// Comprova si la sol·licitud és de tipus POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obté les dades del formulari
    $username = trim($_POST['username']);
    $data_naixament = trim($_POST['data_naixament']);
    $poble = trim($_POST['poble']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm-password']);

    // Comprova si les contrasenyes coincideixen
    if ($password !== $confirmPassword) {
        die("Les contrasenyes no coincideixen.");
    }

    // Encripta la contrasenya
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insereix el nou usuari a la base de dades
    $stmt = $pdo->prepare("INSERT INTO usuaris (nom, data_naixament, poble, email, password) VALUES (:username, :data_naixament, :poble, :email, :password)");
    $stmt->execute(['username' => $username, 'data_naixament' => $data_naixament, 'poble' => $poble, 'email' => $email, 'password' => $hashedPassword]);

    // Crear o actualizar el archivo CSV
    $csvFile = 'usuaris.csv';
    $fileExists = file_exists($csvFile);

    $file = fopen($csvFile, 'a'); // Abrir el archivo en modo de escritura (append)
    if (!$fileExists) {
        // Si el archivo no existe, escribir la cabecera
        fputcsv($file, ['email', 'password']);
    }
    // Escribir los datos del usuario
    fputcsv($file, [$email, $hashedPassword]);
    fclose($file);

    // Verificar si los encabezados ya se han enviado
    if (headers_sent()) {
        die('Encabezados ya enviados');
    }

    // Redirigeix a la pàgina d'inici de sessió
    header('Location: iniciarSessio.html');
    exit;
}
?>