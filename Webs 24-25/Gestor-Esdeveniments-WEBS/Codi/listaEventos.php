<?php
// Inclou la configuració de la base de dades
require 'db_config.php';

// Funció per obtenir tots els esdeveniments amb la valoració mitjana
function getAllEventsWithRating() {
    global $pdo;
    try {
        // Prepara la consulta SQL per obtenir els esdeveniments amb la valoració mitjana
        $stmt = $pdo->prepare("SELECT e.id_event, e.nom_events, e.descripcio, e.color, 
                                      TO_CHAR(e.data_inici, 'DD-MM-YYYY HH24:MI') AS data_inici, 
                                      TO_CHAR(e.data_fi, 'DD-MM-YYYY HH24:MI') AS data_fi, 
                                      l.localitat, l.provincia, l.capacitat, 
                                      o.nom AS nom_organizador, o.email AS email_organizador, o.telefon AS telefon_organizador,
                                      COALESCE(AVG(r.puntuacio), 0) AS avg_rating,
                                      COUNT(r.id_ressenyes) AS review_count
                               FROM events e
                               JOIN llocs l ON e.id_lloc = l.id_lloc
                               JOIN organizadors o ON e.id_organizador = o.id_organizador
                               LEFT JOIN ressenyes r ON e.id_event = r.id_event
                               GROUP BY e.id_event, l.id_lloc, o.id_organizador
                               ORDER BY e.data_inici ASC");
        // Executa la consulta
        $stmt->execute();
        // Retorna els resultats com a matriu associativa
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // En cas d'error, mostra el missatge d'error i atura l'execució
        die("Error: " . $e->getMessage());
    }
}

// Obté tots els esdeveniments amb la valoració mitjana
$events = getAllEventsWithRating();

// Inicia la sessió
session_start();
if (!isset($_SESSION['username'])) {
    // Redirigeix a la pàgina d'inici de sessió si no hi ha una sessió activa
    header('Location: iniciarSessio.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Llista d'Esdeveniments</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<nav class="navbar">
    <div class="nav-left">
    <a href="calendario.php" class="nav-button">Calendari</a>
    </div>
    <div class="nav-center">
        <span class="nav-title">FILA 4 - Alejandro Perez, Daniel Ruiz, Miguel Ibañez, Pau Miro</span>
    </div>
    <div class="nav-right">
        <a href="listaEventos.php" class="nav-button">Llista d'Esdeveniments</a>
        <div class="user-dropdown">
            <span class="username">Hola, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <div class="user-dropdown-content">
                <a href="logout.php">Tancar sessió</a>
            </div>
        </div>
    </div>
</nav>
    <div class="event-list">
        <h1 style="color: #744a8b; text-align: center; margin-bottom: 30px;">Llista d'Esdeveniments</h1>
        <?php foreach ($events as $event): ?>
            <div class="event-card" style="border-left: 5px solid <?php echo htmlspecialchars($event['color']); ?>">
                <div class="event-title"><?php echo htmlspecialchars($event['nom_events']); ?></div>
                <div class="event-details">
                    <div class="event-detail">
                        <strong>Descripció:</strong> <?php echo htmlspecialchars($event['descripcio']); ?>
                    </div>
                    <div class="event-detail">
                        <strong>Data d'inici:</strong> <?php echo htmlspecialchars($event['data_inici']); ?>
                    </div>
                    <div class="event-detail">
                        <strong>Data de fi:</strong> <?php echo htmlspecialchars($event['data_fi']); ?>
                    </div>
                    <div class="event-detail">
                        <strong>Localitat:</strong> <?php echo htmlspecialchars($event['localitat']); ?>
                    </div>
                    <div class="event-detail">
                        <strong>Província:</strong> <?php echo htmlspecialchars($event['provincia']); ?>
                    </div>
                    <div class="event-detail">
                        <strong>Capacitat:</strong> <?php echo htmlspecialchars($event['capacitat']); ?>
                    </div>
                    <div class="event-detail">
                        <strong>Organitzador:</strong> <?php echo htmlspecialchars($event['nom_organizador']); ?>
                    </div>
                    <div class="event-detail">
                        <strong>Email:</strong> <?php echo htmlspecialchars($event['email_organizador']); ?>
                    </div>
                    <div class="event-detail">
                        <strong>Telèfon:</strong> <?php echo htmlspecialchars($event['telefon_organizador']); ?>
                    </div>
                    <div class="event-detail">
                        <strong>Ressenyes:</strong> 
                        <a href="eventReviews.php?id=<?php echo $event['id_event']; ?>" class="rating-link">
                            <?php echo number_format($event['avg_rating'], 1); ?> / 5
                            (<?php echo $event['review_count']; ?> ressenyes)
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <footer class="footer">
        <div class="footer-content">
            <p>&copy; <?php echo date("Y"); ?> FILA 4. Tots els drets reservats.</p>
            <p>Alejandro Perez, Daniel Ruiz, Miguel Ibañez, Pau Miro</p>
        </div>
    </footer>
</body>
</html>