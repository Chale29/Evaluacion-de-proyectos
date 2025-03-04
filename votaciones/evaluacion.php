<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$user = "root";
$password = "";
$dbname = "votaciones";
$conexion = new mysqli($host, $user, $password, $dbname);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$proyecto_id = isset($_GET['proyecto_id']) ? intval($_GET['proyecto_id']) : 0;

if ($proyecto_id == 0) {
    die("Proyecto no válido.");
}

$user_id = $_SESSION['user_id'];

// Verificar si el usuario ya ha calificado este proyecto
$sql_check = "SELECT COUNT(*) FROM calificaciones WHERE user_id = ? AND proyecto_id = ?";
$stmt_check = $conexion->prepare($sql_check);
$stmt_check->bind_param("ii", $user_id, $proyecto_id);
$stmt_check->execute();
$stmt_check->bind_result($calificado);
$stmt_check->fetch();
$stmt_check->close();

$showEvaluacionModal = $calificado > 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && !$showEvaluacionModal) {
    $calificaciones = [];
    $comentario = isset($_POST['comentario']) ? htmlspecialchars($_POST['comentario']) : null;

    // Obtener las preguntas para el proyecto
    $sql = "SELECT id FROM preguntas WHERE proyecto_id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $proyecto_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Guardar las calificaciones
    while ($row = $result->fetch_assoc()) {
        $id_pregunta = $row['id'];
        $calificaciones[$id_pregunta] = isset($_POST["pregunta$id_pregunta"]) ? intval($_POST["pregunta$id_pregunta"]) : null;
    }

    foreach ($calificaciones as $id_pregunta => $puntuacion) {
        if ($puntuacion !== null && $puntuacion >= 1 && $puntuacion <= 5) {
            // Insertar la calificación junto con el comentario
            $sql = "INSERT INTO calificaciones (user_id, pregunta_id, puntuacion, proyecto_id, comentario) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("iiiss", $user_id, $id_pregunta, $puntuacion, $proyecto_id, $comentario);
            $stmt->execute();
        }
    }

    header("Location: home.php");
    exit();
}

$sql = "SELECT id, pregunta FROM preguntas WHERE proyecto_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $proyecto_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluación del Proyecto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function controlarCheckbox(idPregunta) {
            let checkboxes = document.querySelectorAll(`input[name='pregunta${idPregunta}']`);
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        checkboxes.forEach(cb => {
                            if (cb !== this) cb.checked = false;
                        });
                    }
                });
            });
        }
    </script>
</head>
<body class="container mt-5">
    <h2 class="text-center mb-4">Evaluación del Proyecto</h2>

    <?php if ($showEvaluacionModal): ?>
    <div class="modal fade show" id="alreadyEvaluatedModal" tabindex="-1" aria-labelledby="alreadyEvaluatedModalLabel" aria-hidden="true" style="display: block;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #f8d7da;">
                    <h5 class="modal-title" id="alreadyEvaluatedModalLabel">Evaluación ya Realizada</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center" style="background-color: #f8d7da; color: #721c24; font-weight: bold;">
                    Ya has realizado la evaluación de este proyecto.
                </div>
                <div class="modal-footer" style="border-top: 1px solid #f1b0b7; text-align: center; background-color: #f8d7da;">
                    <a href="home.php" class="btn btn-primary w-100">Volver al Inicio</a>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <form method="POST" id="formEvaluacion">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="mb-3">
                    <label class="form-label"><?php echo htmlspecialchars($row['pregunta']); ?>:</label>
                    <div class="d-flex justify-content-between">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="pregunta<?php echo $row['id']; ?>" value="<?php echo $i; ?>" onclick="controlarCheckbox(<?php echo $row['id']; ?>)">
                                <label class="form-check-label"><?php echo $i; ?></label>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-warning">No hay preguntas disponibles para este proyecto.</div>
        <?php endif; ?>

        <!-- Comentario adicional -->
        <div class="mb-3">
            <label for="comentario" class="form-label">Comentario sobre el Proyecto:</label>
            <textarea class="form-control" id="comentario" name="comentario" rows="4"></textarea>
        </div>

        <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#confirmModal">Enviar Calificaciones</button>
    </form>
    <?php endif; ?>

    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmar Evaluación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas calificar este proyecto? Esta acción no se puede deshacer.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" form="formEvaluacion">Confirmar Evaluación</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
