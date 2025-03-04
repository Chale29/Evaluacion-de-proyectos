<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
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

// Obtener el ID del proyecto
if (!isset($_POST['proyecto_id'])) {
    header("Location: dashboard_admin.php");
    exit();
}

$proyecto_id = intval($_POST['proyecto_id']);

// Obtener detalles del proyecto
$sql_proyecto = "SELECT nombre FROM proyectos WHERE id = ?";
$stmt = $conexion->prepare($sql_proyecto);
$stmt->bind_param("i", $proyecto_id);
$stmt->execute();
$result_proyecto = $stmt->get_result();
$proyecto = $result_proyecto->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Proyecto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="container mt-5">
    <h2 class="text-center mb-4">Detalles del Proyecto</h2>

    <div class="d-flex justify-content-start mb-3">
        <a href="dashboard_admin.php" class="btn btn-secondary">Volver</a>
    </div>
    
    <div class="d-flex justify-content-between mb-3">
        <h4><?php echo htmlspecialchars($proyecto['nombre']); ?></h4>
    </div>

    <div class="row">
        <!-- Tabla de preguntas (lado izquierdo) -->
        <div class="col-md-6">
            <div class="card shadow p-3">
                <h5 class="text-center mb-3">Preguntas del Proyecto</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Pregunta</th>
                        </tr>
                    </thead>
                    <tbody id="preguntas-lista">
                        <?php
                        $sql_preguntas = "SELECT pregunta FROM preguntas WHERE proyecto_id = ?";
                        $stmt_preguntas = $conexion->prepare($sql_preguntas);
                        $stmt_preguntas->bind_param("i", $proyecto_id);
                        $stmt_preguntas->execute();
                        $result_preguntas = $stmt_preguntas->get_result();

                        if ($result_preguntas->num_rows > 0) {
                            while ($row = $result_preguntas->fetch_assoc()) {
                                echo "<tr><td>" . htmlspecialchars($row['pregunta']) . "</td></tr>";
                            }
                        } else {
                            echo "<tr><td class='text-center'>No hay preguntas creadas.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Formulario para agregar nueva pregunta (lado derecho) -->
        <div class="col-md-6">
            <div class="card shadow p-3">
                <h5 class="text-center mb-3">Agregar Nueva Pregunta</h5>
                <form id="form-agregar-pregunta">
                    <input type="hidden" name="proyecto_id" value="<?php echo $proyecto_id; ?>">
                    <div class="mb-3">
                        <label for="pregunta" class="form-label">Pregunta:</label>
                        <input type="text" class="form-control" id="pregunta" name="pregunta" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Agregar Pregunta</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#form-agregar-pregunta").submit(function(event) {
                event.preventDefault(); // Evita la recarga de la página

                $.ajax({
                    url: "agregar_pregunta.php",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response === "success") {
                            // Agregar la pregunta a la tabla sin recargar
                            let nuevaPregunta = $("#pregunta").val();
                            $("#preguntas-lista").append("<tr><td>" + nuevaPregunta + "</td></tr>");
                            $("#pregunta").val(""); // Limpiar el campo
                        } else {
                            alert("Error al agregar la pregunta");
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

<?php
$conexion->close();
?>
