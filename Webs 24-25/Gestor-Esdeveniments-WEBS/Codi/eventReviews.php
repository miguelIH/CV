<?php
// Inclou la configuració de la base de dades
require 'db_config.php';

// Funció per obtenir els detalls de l'esdeveniment i les ressenyes
function getEventReviews($eventId) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT e.id_event, e.nom_events, e.descripcio, e.color,
                                      r.puntuacio, r.comentari, 
                                      TO_CHAR(r.data_ressenya, 'DD-MM-YYYY HH24:MI') AS data_ressenya, 
                                      r.nomlloc, u.nom AS username
                               FROM events e
                               LEFT JOIN ressenyes r ON e.id_event = r.id_event
                               LEFT JOIN usuaris u ON r.id_usuari = u.id_usuari
                               WHERE e.id_event = :eventId
                               ORDER BY r.data_ressenya DESC");
        $stmt->execute(['eventId' => $eventId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Funció per comprovar si l'usuari ja ha ressenyat l'esdeveniment
function hasUserReviewed($eventId, $userId) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM ressenyes WHERE id_event = :eventId AND id_usuari = :userId");
        $stmt->execute(['eventId' => $eventId, 'userId' => $userId]);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Funció per afegir una nova ressenya
function addReview($eventId, $userId, $rating, $comment) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO ressenyes (id_event, id_usuari, puntuacio, comentari, data_ressenya) 
                               VALUES (:eventId, :userId, :rating, :comment, CURRENT_TIMESTAMP)");
        $stmt->execute([
            'eventId' => $eventId,
            'userId' => $userId,
            'rating' => $rating,
            'comment' => $comment
        ]);
        return true;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

session_start();
if (!isset($_SESSION['username'])) {
    // Redirigeix a la pàgina d'inici de sessió si no hi ha una sessió activa
    header('Location: iniciarSessio.html');
    exit();
}

// Obtenim l'ID de l'esdeveniment del paràmetre URL
$eventId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($eventId === 0) {
    die("ID d'esdeveniment no vàlid");
}

// Gestionem l'enviament del formulari
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'], $_POST['comment'])) {
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);
    $userId = $_SESSION['user_id']; // Suposant que emmagatzemem user_id a la sessió

    if ($rating >= 1 && $rating <= 5 && !empty($comment)) {
        if (!hasUserReviewed($eventId, $userId)) {
            if (addReview($eventId, $userId, $rating, $comment)) {
                // Redirigeix per refrescar la pàgina i mostrar la nova ressenya
                header("Location: eventReviews.php?id=$eventId&success=1");
                exit();
            }
        }
    }
}

// Obtenim les ressenyes de l'esdeveniment
$eventReviews = getEventReviews($eventId);

if (empty($eventReviews)) {
    die("Esdeveniment no trobat o no hi ha ressenyes disponibles");
}

// Els detalls de l'esdeveniment es troben a la primera fila
$eventDetails = $eventReviews[0]; 
// Comprovem si l'usuari ja ha ressenyat l'esdeveniment
$userHasReviewed = hasUserReviewed($eventId, $_SESSION['user_id']); 
?>

<!--
    Aquest fitxer HTML mostra les ressenyes d'un esdeveniment específic.
    Inclou una barra de navegació amb el nom de l'equip i un enllaç per tancar sessió.
    També mostra els detalls de l'esdeveniment i una llista de ressenyes dels usuaris.
    Si l'usuari no ha fet una ressenya, es mostra un botó per afegir-ne una.
    Cada ressenya inclou el nom de l'usuari, la puntuació, el comentari, la data i el lloc (si està disponible).
    Si no hi ha ressenyes, es mostra un missatge indicant-ho.
    També hi ha un enllaç per tornar a la llista d'esdeveniments.
    A la part inferior, hi ha un formulari emergent per afegir una nova ressenya.
    Finalment, hi ha un peu de pàgina amb els drets d'autor i els noms dels membres de l'equip.
-->
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ressenyes de l'Esdeveniment</title>
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

    <div class="reviews-list">
        <div class="event-header">
            <h1 style="color: #744a8b;"><?php echo htmlspecialchars($eventDetails['nom_events']); ?></h1>
            <p><?php echo htmlspecialchars($eventDetails['descripcio']); ?></p>
        </div>

        <h1 style="color: #744a8b; text-align: center; margin-bottom: 20px;">Ressenyes</h1>

        <?php if (!$userHasReviewed): ?>
            <button id="addReviewBtn" class="add-review-btn">Afegir Ressenya</button>
        <?php endif; ?>

        <?php foreach ($eventReviews as $review): ?>
            <?php if ($review['puntuacio'] !== null): ?>
                <div class="review-card">
                    <div class="review-header">
                        <span class="review-username"><?php echo htmlspecialchars($review['username']); ?></span>
                        <span class="review-rating">
                            <?php echo str_repeat('★', $review['puntuacio']) . str_repeat('☆', 5 - $review['puntuacio']); ?>
                        </span>
                    </div>
                    <div class="review-content">
                        <?php echo htmlspecialchars($review['comentari']); ?>
                    </div>
                    <div class="review-footer">
                        <span>Data: <?php echo htmlspecialchars($review['data_ressenya']); ?></span>
                        <?php if ($review['nomlloc']): ?>
                            <span> | Lloc: <?php echo htmlspecialchars($review['nomlloc']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php if (count(array_filter($eventReviews, function($review) { return $review['puntuacio'] !== null; })) === 0): ?>
            <p style="text-align: center; color: #c9bdcf;">No hi ha ressenyes per a aquest esdeveniment.</p>
        <?php endif; ?>

        <div class="back-link">
            <a href="listaEventos.php" class="rating-link">Tornar a la llista d'esdeveniments</a>
        </div>
    </div>
    <div id="reviewPopup" class="popup">
        <div class="popup-content">
            <span class="close">&times;</span>
            <h2>Afegir Ressenya</h2>
            <form id="reviewForm" method="POST">
                <div class="star-rating">
                    <span data-rating="5">☆</span>
                    <span data-rating="4">☆</span>
                    <span data-rating="3">☆</span>
                    <span data-rating="2">☆</span>
                    <span data-rating="1">☆</span>
                </div>
                <input type="hidden" name="rating" id="ratingInput" required>
                <textarea name="comment" rows="4" cols="50" required placeholder="Escriu la teva ressenya aquí"></textarea>
                <button type="submit" class="add-review-btn">Enviar Ressenya</button>
            </form>
        </div>
    </div>
    <script src="script.js"></script>
    <footer class="footer">
        <div class="footer-content">
            <p>&copy; <?php echo date("Y"); ?> FILA 4. Tots els drets reservats.</p>
            <p>Alejandro Perez, Daniel Ruiz, Miguel Ibañez, Pau Miro</p>
        </div>
    </footer>
</body>
</html>