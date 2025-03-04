<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Verificar si se recibió el ID del proyecto
if (!isset($_GET['proyecto_id'])) {
    die("Error: No se recibió el ID del proyecto.");
}

$proyecto_id = $_GET['proyecto_id'];

// Conectar a la base de datos
$host = "localhost";
$user = "root";
$password = "";
$dbname = "votaciones";
$conexion = new mysqli($host, $user, $password, $dbname);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener información del proyecto
$sql_proyecto = "SELECT nombre FROM proyectos WHERE id = ?";
$stmt_proyecto = $conexion->prepare($sql_proyecto);
$stmt_proyecto->bind_param("i", $proyecto_id);
$stmt_proyecto->execute();
$result_proyecto = $stmt_proyecto->get_result();

if ($result_proyecto->num_rows == 0) {
    die("Error: Proyecto no encontrado.");
}

$proyecto = $result_proyecto->fetch_assoc();

// Obtener las calificaciones y comentarios del proyecto
$sql_calificaciones = "SELECT u.email AS juez, c.puntuacion, c.fecha_calificacion, c.comentario
                       FROM calificaciones c
                       JOIN usuarios u ON c.user_id = u.id
                       WHERE c.proyecto_id = ?";
$stmt_calificaciones = $conexion->prepare($sql_calificaciones);
$stmt_calificaciones->bind_param("i", $proyecto_id);
$stmt_calificaciones->execute();
$result_calificaciones = $stmt_calificaciones->get_result();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Evaluación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2 class="text-center mb-4">Detalle de Evaluación</h2>
    <h4 class="text-center">Proyecto: <?php echo htmlspecialchars($proyecto['nombre']); ?></h4>

    <form action="proyectos_evaluados.php" method="POST">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <input type="hidden" name="proyecto_id" value="<?php echo $proyecto_id; ?>">
        <button type="submit" class="btn btn-primary">Ver Proyecto Evaluado</button>
    </form>



    <?php if ($result_calificaciones->num_rows == 0): ?>
        <div class="alert alert-warning text-center">
            Este proyecto no ha sido evaluado aún.
        </div>
    <?php else: ?>
        <div class="card shadow p-3">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Juez</th>
                        <th>Puntaje</th>
                        <th>Fecha de Calificación</th>
                        <th>Comentario</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_calificaciones->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['juez']); ?></td>
                            <td><?php echo htmlspecialchars($row['puntuacion']); ?></td>
                            <td><?php echo htmlspecialchars($row['fecha_calificacion']); ?></td>
                            <td><?php echo htmlspecialchars($row['comentario'] ?? 'Sin comentarios'); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</body>
</html>

<?php
$stmt_proyecto->close();
$stmt_calificaciones->close();
$conexion->close();
?>
