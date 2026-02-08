<?php
// Inclou la configuració de la base de dades
require 'db_config.php';

// Comprova si la sol·licitud és de tipus GET per obtenir l'ID de l'esdeveniment
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_esdeveniment'])) {
    $event_id = $_GET['id_esdeveniment'];

    // Obté les dades de l'esdeveniment de la base de dades
    $stmt = $pdo->prepare("SELECT e.nom_events, e.descripcio, e.color, e.data_inici, e.data_fi, l.localitat, l.provincia, l.capacitat, o.nom AS nom_organizador, o.email AS email_organizador, o.telefon AS telefon_organizador
                            FROM events e
                            JOIN llocs l ON e.id_lloc = l.id_lloc
                            JOIN organizadors o ON e.id_organizador = o.id_organizador
                            WHERE e.id_event = :id");

    // Executa la consulta amb l'ID de l'esdeveniment
    $stmt->execute(['id' => $event_id]);
    $events = $stmt->fetch();

    // Si no es troba l'esdeveniment, mostra un missatge d'error
    if (!$events) {
        die("Esdeveniment no trobat.");
    }
}

// Comprova si la sol·licitud és de tipus POST per actualitzar o eliminar l'esdeveniment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si es vol eliminar l'esdeveniment
    if (isset($_POST['delete'])) {
        $event_id =  $_POST['id'];

        // Inicia una transacció
        $pdo->beginTransaction();

        try {
            // Elimina l'esdeveniment de la base de dades
            $stmt = $pdo->prepare("DELETE FROM events WHERE id_event = :id");
            $stmt->execute(['id' => $event_id]);

            // Elimina el lloc associat a l'esdeveniment
            $stmt = $pdo->prepare("DELETE FROM llocs WHERE id_lloc = (SELECT id_lloc FROM events WHERE id_event = :id)");
            $stmt->execute(['id' => $event_id]);

            // Elimina l'organitzador associat a l'esdeveniment
            $stmt = $pdo->prepare("DELETE FROM organizadors WHERE id_organizador = (SELECT id_organizador FROM events WHERE id_event = :id)");
            $stmt->execute(['id' => $event_id]);

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
}

// Si es vol actualitzar l'esdeveniment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $event_id = $_POST['id'];
    $nom_events = trim($_POST['nom_events']);
    $descripcio = trim($_POST['descripcio']);
    $color = trim($_POST['color']);
    $data_inici = trim($_POST['data_inici']);
    $data_fi = trim($_POST['data_fi']);
    $nom_lloc = trim($_POST['nom_lloc']);
    $provincia = trim($_POST['provincia']);
    $capacitat = trim($_POST['capacitat']);
    $nom_organizador = trim($_POST['nom_organizador']);
    $email_organizador = trim($_POST['email_organizador']);
    $telefon_organizador = trim($_POST['telefon_organizador']);

    // Inicia una transacció
    $pdo->beginTransaction();

    try {
        // Actualitza el lloc a la base de dades
        $stmt = $pdo->prepare("UPDATE llocs SET localitat = :nom, provincia = :provincia, capacitat = :capacitat WHERE id_lloc = (SELECT id_lloc FROM events WHERE id_event = :id)");
        $stmt->execute(['nom' => $nom_lloc, 'provincia' => $provincia, 'capacitat' => $capacitat, 'id' => $event_id]);

        // Actualitza l'organitzador a la base de dades
        $stmt = $pdo->prepare("UPDATE organizadors SET nom = :nom, email = :email, telefon = :telefon WHERE id_organizador = (SELECT id_organizador FROM events WHERE id_event = :id)");
        $stmt->execute(['nom' => $nom_organizador, 'email' => $email_organizador, 'telefon' => $telefon_organizador, 'id' => $event_id]);

        // Actualitza l'esdeveniment a la base de dades
        $stmt = $pdo->prepare("UPDATE events SET nom_events = :nom_events, descripcio = :descripcio, color = :color, data_inici = :data_inici, data_fi = :data_fi WHERE id_event = :id");
        $stmt->execute(['nom_events' => $nom_events, 'descripcio' => $descripcio, 'color' => $color, 'data_inici' => $data_inici, 'data_fi' => $data_fi, 'id' => $event_id]);

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

<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Esdeveniment</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
    // Inclou el fitxer PHP amb la lògica d'obtenció de dades
    include('editarEvento.php');
    ?>

    <nav class="navbar">
        <div class="nav-center">
            FILA 4 - Alejandro Perez, Daniel Ruiz, Miguel Ibañez, Pau Miro
        </div>
        <div class="nav-right">
            <div class="user-dropdown">
                <span class="username">Hola, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <div class="user-dropdown-content">
                    <a href="logout.php">Tancar sessió</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="form-wrapper-esdeveniment">
        <div class="form-container-esdeveniment">
            <form id="crear-esdeveniment-form" method="POST" action="modificarEsdeveniment.php">
                <div class="form-column">
                    <h2>Lloc</h2>
                    <label for="nom_lloc">Localitat:</label>
                    <input type="text" id="nom_lloc" name="nom_lloc" value="<?php echo htmlspecialchars($events['localitat']); ?>" required>
                    <label for="provincia">Provincia:</label>
                    <input type="text" id="provincia" name="provincia" value="<?php echo htmlspecialchars($events['provincia']); ?>" required>
                    <label for="capacitat">Capacitat:</label>
                    <input type="number" id="capacitat" name="capacitat" value="<?php echo htmlspecialchars($events['capacitat']); ?>" required>
                </div>

                <div class="form-column">
                    <h2>Modificar Esdeveniment</h2>
                    <label for="nom_events">Nom de l'Esdeveniment:</label>
                    <input type="text" id="nom_events" name="nom_events" value="<?php echo htmlspecialchars($events['nom_events']); ?>" required>
                    <label for="descripcio">Descripció:</label>
                    <textarea id="descripcio" name="descripcio" rows="3" required><?php echo htmlspecialchars($events['descripcio']); ?></textarea>
                    <label for="color">Color:</label>
                    <input type="color" id="color" name="color" value="<?php echo htmlspecialchars($events['color']); ?>" required>
                    <label for="data_inici">Data d'Inici:</label>
                    <input type="datetime-local" id="data_inici" name="data_inici" value="<?php echo htmlspecialchars(substr($events['data_inici'], 0, 16)); ?>" required>
                    <label for="data_fi">Data de Fi:</label>
                    <input type="datetime-local" id="data_fi" name="data_fi" value="<?php echo htmlspecialchars(substr($events['data_fi'], 0, 16)); ?>" required>
                </div>

                <div class="form-column">
                    <h2>Organitzador</h2>
                    <label for="nom_organizador">Nom:</label>
                    <input type="text" id="nom_organizador" name="nom_organizador" value="<?php echo htmlspecialchars($events['nom_organizador']); ?>" required>
                    <label for="email_organizador">Email:</label>
                    <input type="email" id="email_organizador" name="email_organizador" value="<?php echo htmlspecialchars($events['email_organizador']); ?>" required>
                    <label for="telefon_organizador">Telèfon:</label>
                    <input type="text" id="telefon_organizador" name="telefon_organizador" value="<?php echo htmlspecialchars($events['telefon_organizador']); ?>" required>
                </div>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($event_id); ?>" />
                <div class="form-section-submit">
                    <input type="submit" value="Modificar Esdeveniment">
                    <input type="submit" name="delete" value="Eliminar Esdeveniment" onclick="return confirm('Estàs segur que vols eliminar aquest esdeveniment?')" />
                </div>
            </form>
        </div>
    </div>
</body>
</html>