<?php
session_start();

date_default_timezone_set('America/New_York');

// Ruta al archivo JSON donde se guardarán las notas
$jsonFile = 'notas.json';

// Cargar las notas desde el archivo JSON, si existe
if (file_exists($jsonFile)) {
    $notes = json_decode(file_get_contents($jsonFile), true);
} else {
    $notes = [];
}

// Si no se tiene una sesión de notas, inicializarla
if (!isset($_SESSION['notes'])) {
    $_SESSION['notes'] = $notes;
}

// Guardar la nueva nota cuando se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date']) && isset($_POST['note'])) {
    $date = $_POST['date'];
    $note = $_POST['note'];

    // Guardar la nota en la sesión
    $_SESSION['notes'][$date] = $note;

    // Guardar la nota también en el archivo JSON
    $notes[$date] = $note;
    file_put_contents($jsonFile, json_encode($notes, JSON_PRETTY_PRINT));
}

// Seleccionar la fecha actual si no se ha elegido una
$selectedDate = $_POST['date'] ?? date('Y-m-d');
$note = $_SESSION['notes'][$selectedDate] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario de Notas</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #ffebeb;
            color: #333;
            text-align: center;
            padding: 20px;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: #ffd9d9;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            text-align: left;
        }
        h1 {
            font-size: 2em;
            color: #e63946;
            margin-bottom: 20px;
        }
        input[type="date"] {
            border: 2px solid #ff6f61;
            padding: 10px;
            border-radius: 8px;
            font-size: 1.2em;
            width: 80%;
            margin-bottom: 20px;
            outline: none;
            transition: border 0.3s ease;
        }
        input[type="date"]:focus {
            border-color: #ff3b2d;
        }
        textarea {
            width: 100%;
            height: 120px;
            border: 2px solid #ff6f61;
            border-radius: 8px;
            padding: 10px;
            font-size: 1.1em;
            margin-bottom: 20px;
            transition: border 0.3s ease;
            resize: none;
        }
        textarea:focus {
            border-color: #ff3b2d;
        }
        button {
            background: #ff6f61;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 1.1em;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        button:hover {
            background: #e63946;
            transform: scale(1.05);
        }
        .note {
            margin-top: 20px;
            background: #ffe5e5;
            padding: 10px;
            border-radius: 8px;
        }
        .note h3 {
            color: #e63946;
            margin-bottom: 10px;
        }
        .note p {
            color: #555;
        }
        input[type="date"]::before {
            content: "📅";
            padding-right: 10px;
        }
        @keyframes pulse {
            0% {
                transform: scale(1);
                background-color: #ff6f61;
            }
            50% {
                transform: scale(1.1);
                background-color: #e63946;
            }
            100% {
                transform: scale(1);
                background-color: #ff6f61;
            }
        }
        input[type="date"]:focus {
            animation: pulse 0.5s ease-in-out;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Calendario de Notas</h1>
        <form method="POST">
            <input type="date" name="date" value="<?php echo $selectedDate; ?>" required>
            <br><br>
            <textarea name="note" placeholder="Añadir nota..."><?php echo htmlspecialchars($note); ?></textarea>
            <br>
            <button type="submit">Guardar Nota</button>
        </form>
        <?php if (!empty($note)): ?>
            <div class="note">
                <h3>Nota guardada:</h3>
                <p><?php echo htmlspecialchars($note); ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
