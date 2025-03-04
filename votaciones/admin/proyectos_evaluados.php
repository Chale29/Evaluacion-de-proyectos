<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Obtener el ID del usuario de GET o POST
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
} elseif (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
} else {
    die("Error: No se recibió el ID del usuario.");
}

// Conectar a la base de datos
$host = "localhost";
$user = "root";
$password = "";
$dbname = "votaciones";
$conexion = new mysqli($host, $user, $password, $dbname);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consultar los proyectos evaluados por el usuario seleccionado
$sql = "SELECT c.proyecto_id, p.nombre, SUM(c.puntuacion) AS puntuacion, 
        MAX(c.fecha_calificacion) AS fecha_calificacion, 
        (SELECT COUNT(*) FROM preguntas WHERE proyecto_id = p.id) AS total_preguntas
    FROM calificaciones c
    JOIN proyectos p ON c.proyecto_id = p.id
    WHERE c.user_id = ?
    GROUP BY c.proyecto_id, p.nombre;";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyectos Evaluados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
</head>
<body class="container mt-5">
    <h2 class="text-center mb-4">Proyectos Evaluados (Por usuario)</h2>
    
    <div class="d-flex justify-content-between mb-3">
        <a href="detalle_usuario.php" class="btn btn-secondary">Volver</a>
    </div>

    <?php if ($result->num_rows == 0): ?>
        <div class="alert alert-warning text-center">
            Este usuario no ha evaluado ningún proyecto aún.
        </div>
    <?php else: ?>
        <div class="card shadow p-3">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Puntos Obtenidos / Puntos Máximos</th>
                        <th>Fecha de Calificación</th>
                        <th>Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row_proyecto = $result->fetch_assoc()) : 
                        // Cálculo de los puntos obtenidos y puntos máximos
                        $puntos_obtenidos = $row_proyecto['puntuacion'];
                        $puntos_maximos = $row_proyecto['total_preguntas'] * 5;  // 5 puntos por cada pregunta
                        $proyectoId = htmlspecialchars($row_proyecto['proyecto_id']);
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row_proyecto['nombre']); ?></td>
                            <td><?php echo $puntos_obtenidos . "/" . $puntos_maximos; ?></td>
                            <td><?php echo htmlspecialchars($row_proyecto['fecha_calificacion']); ?></td>
                            <td>
                                <div class="accordion" id="accordion_<?php echo $proyectoId; ?>">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_<?php echo $proyectoId; ?>" aria-expanded="false">
                                                Ver Evaluación
                                            </button>
                                        </h2>
                                        <div id="collapse_<?php echo $proyectoId; ?>" class="accordion-collapse collapse">
                                            <div class="accordion-body">
                                                <strong>Preguntas evaluadas:</strong>
                                                <ul class="list-group">
                                                    <?php
                                                    // Consultar las preguntas y calificaciones del usuario para este proyecto
                                                    $sql_preguntas = "SELECT p.pregunta, c.puntuacion, p.fecha_creacion
                                                                      FROM calificaciones c
                                                                      JOIN preguntas p ON c.pregunta_id = p.id
                                                                      WHERE c.user_id = ? AND c.proyecto_id = ?";
                                                    $stmt_preguntas = $conexion->prepare($sql_preguntas);
                                                    $stmt_preguntas->bind_param("ii", $user_id, $proyectoId);
                                                    $stmt_preguntas->execute();
                                                    $result_preguntas = $stmt_preguntas->get_result();

                                                    while ($row_pregunta = $result_preguntas->fetch_assoc()) :
                                                    ?>
                                                        <li class="list-group-item">
                                                            <strong><?php echo htmlspecialchars($row_pregunta['pregunta']); ?>:</strong>
                                                            <?php echo htmlspecialchars($row_pregunta['puntuacion']); ?>/5 
                                                        </li>
                                                    <?php endwhile; 
                                                    $stmt_preguntas->close();
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</body>
</html>

<?php
$stmt->close();
$conexion->close();
?>
