<?php
// Inclou la configuració de la base de dades
require 'db_config.php';

// Comprova si la sol·licitud és de tipus POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obté les dades del formulari per a l'esdeveniment
    $nom_events = trim($_POST['nom_events']);
    $descripcio = trim($_POST['descripcio']);
    $color = trim($_POST['color']);
    $data_inici = trim($_POST['data_inici']);
    $data_fi = trim($_POST['data_fi']);
    
    // Obté les dades del formulari per al lloc
    $nom_lloc = trim($_POST['nom_lloc']);
    $provincia = trim($_POST['provincia']);
    $capacitat = trim($_POST['capacitat']);
    
    // Obté les dades del formulari per a l'organitzador
    $nom_organizador = trim($_POST['nom_organizador']);
    $email_organizador = trim($_POST['email_organizador']);
    $telefon_organizador = trim($_POST['telefon_organizador']);
    
    // Inicia una transacció
    $pdo->beginTransaction();
    
    try {
        // Insereix el nou lloc a la base de dades
        $stmt = $pdo->prepare("INSERT INTO llocs (localitat, provincia, capacitat) VALUES (:nom, :provincia, :capacitat)");
        $stmt->execute(['nom' => $nom_lloc, 'provincia' => $provincia, 'capacitat' => $capacitat]);
        $id_lloc = $pdo->lastInsertId();
        
        // Insereix el nou organitzador a la base de dades
        $stmt = $pdo->prepare("INSERT INTO organizadors (nom, email, telefon) VALUES (:nom, :email, :telefon)");
        $stmt->execute(['nom' => $nom_organizador, 'email' => $email_organizador, 'telefon' => $telefon_organizador]);
        $id_organizador = $pdo->lastInsertId();
        
        // Insereix el nou esdeveniment a la base de dades
        $stmt = $pdo->prepare("INSERT INTO events (nom_events, descripcio, color, data_inici, data_fi, id_lloc, id_organizador) VALUES (:nom_events, :descripcio, :color, :data_inici, :data_fi, :id_lloc, :id_organizador)");
        $stmt->execute([
            'nom_events' => $nom_events,
            'descripcio' => $descripcio,
            'color' => $color,
            'data_inici' => $data_inici,
            'data_fi' => $data_fi,
            'id_lloc' => $id_lloc,
            'id_organizador' => $id_organizador
        ]);
        
        // Confirma la transacció
        $pdo->commit();
        
        // Redirigeix a la pàgina del calendari
        header('Location: calendario.php');
        exit;
    } catch (Exception $e) {
        // Si hi ha un error, desfés la transacció
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
}
?>