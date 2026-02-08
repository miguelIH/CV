<?php
// Configuració de la base de dades
$host = '192.168.1.201';
$db = 'calendari';
$user = 'postgres';
$pass = 'P@ssw0rd';
$charset = 'utf8';

// DSN per a la connexió a la base de dades
$dsn = "pgsql:host=$host;dbname=$db";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Crea una nova connexió PDO
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Gestiona els errors de connexió
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>