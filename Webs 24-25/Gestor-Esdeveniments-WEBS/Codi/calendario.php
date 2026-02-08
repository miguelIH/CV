<?php
// Inclou la configuració de la base de dades
require 'db_config.php';

// Funció per obtenir els esdeveniments del mes i any seleccionats
function obtenirEsdeveniments($mes, $any) {
    global $pdo;
    try {
        // Prepara la consulta SQL per obtenir els esdeveniments del mes i any seleccionats
        $stmt = $pdo->prepare("SELECT id_event, nom_events, color, data_inici FROM events 
        WHERE EXTRACT(MONTH FROM data_inici) = :mes 
        AND EXTRACT(YEAR FROM data_inici) = :any 
        ORDER BY data_inici ASC");

        // Executa la consulta amb els paràmetres proporcionats
        $stmt->execute(['mes' => $mes, 'any' => $any]);
        // Retorna els resultats com un array associatiu
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) { 
        // En cas d'error, mostra un missatge i atura l'execució
        die("Error: " . $e->getMessage());
    }  
}

session_start();
if (!isset($_SESSION['username'])) {
    // Redirigir a la pàgina d'inici de sessió si no hi ha una sessió activa
    header('Location: iniciarSessio.html');
    exit();
}

// Obté el mes i any actuals o seleccionats
$mes = isset($_GET['mes']) ? $_GET['mes'] : date('n');
$any = isset($_GET['any']) ? $_GET['any'] : date('Y');

// Obté els esdeveniments per al mes i any seleccionats
$esdeveniments = obtenirEsdeveniments($mes, $any);

?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FILA 4</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        // Passa els esdeveniments al JavaScript
        const esdeveniments = <?php echo json_encode($esdeveniments); ?>;
    </script>
</head>
<body onload="inicialitzarCalendari(<?php echo $mes; ?>, <?php echo $any; ?>)">
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
    <div class="calendar-header">
        <h1>Calendari d'Esdeveniments</h1>
        <div class="calendar-controls">
            <select id="month-select" onchange="canviarMes()">
                <option value="1">Gener</option>
                <option value="2">Febrer</option>
                <option value="3">Març</option>
                <option value="4">Abril</option>
                <option value="5">Maig</option>
                <option value="6">Juny</option>
                <option value="7">Juliol</option>
                <option value="8">Agost</option>
                <option value="9">Setembre</option>
                <option value="10">Octubre</option>
                <option value="11">Novembre</option>
                <option value="12">Desembre</option>
            </select>
            <input type="number" id="year-input" value="<?php echo $any; ?>" min="1900" max="2100" onchange="canviarMes()">
        </div>
    </div>
    <div class="calendar-container">
        <div class="calendar">
            <div class="day-header">Dilluns</div>
            <div class="day-header">Dimarts</div>
            <div class="day-header">Dimecres</div>
            <div class="day-header">Dijous</div>
            <div class="day-header">Divendres</div>
            <div class="day-header">Dissabte</div>
            <div class="day-header">Diumenge</div>
            <div id="calendar-days"></div>
        </div>
    </div>
    <div id="day-options" class="day-options">
        <a href="listaEventos.php">Visualitzar esdeveniments</a>
        <a href="crearEsdeveniment.html">Afegir esdeveniments</a>
    </div>
    <footer class="footer">
        <div class="footer-content">
            <p>&copy; <?php echo date("Y"); ?> FILA 4. Tots els drets reservats.</p>
            <p>Alejandro Perez, Daniel Ruiz, Miguel Ibañez, Pau Miro</p>
        </div>
    </footer>
    <script src="script.js"></script>
</body>
</html>