<?php
session_start();

// Verificamos si el usuario tiene sesión activa
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

// Inicializamos la variable $evaluado
$evaluado = false;

// Verificar si ya se ha evaluado un proyecto
if (isset($_GET['proyecto_id'])) {
    $proyecto_id = $_GET['proyecto_id'];
    $usuario_id = $_SESSION['user_id'];

    // Verificar si el usuario ya ha evaluado el proyecto
    $sql_verificacion = "SELECT * FROM evaluaciones WHERE proyecto_id = $proyecto_id AND usuario_id = $usuario_id";
    $resultado_verificacion = $conexion->query($sql_verificacion);

    if ($resultado_verificacion->num_rows > 0) {
        $evaluado = true;  // Ya se ha evaluado
    }
}

// Obtener los proyectos para el listado
$sql_proyectos = "SELECT id, nombre FROM proyectos";
$result_proyectos = $conexion->query($sql_proyectos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Calificar Proyectos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        /* Estilo personalizado para el modal de evaluación ya realizada */
        #alreadyEvaluatedModal .modal-content {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }
        #alreadyEvaluatedModal .modal-header {
            background-color: #f5c6cb;
            border-bottom: 1px solid #f1b0b7;
        }
        #alreadyEvaluatedModal .modal-footer {
            border-top: 1px solid #f1b0b7;
        }
        .modal-body {
            color: #721c24;
            font-weight: bold;
        }
    </style>
</head>
<body class="container mt-5">
    <h2 class="text-center mb-4">Proyectos para Calificar</h2>

    <!-- Listado de proyectos con botones -->
    <div class="row">
        <?php while ($row = $result_proyectos->fetch_assoc()): ?>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['nombre']; ?></h5>
                        <!-- Verificar si ya fue evaluado y cambiar el botón -->
                        <?php if ($evaluado): ?>
                            <button class="btn btn-secondary" disabled>Calificado</button>
                        <?php else: ?>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmModal" data-proyecto-id="<?php echo $row['id']; ?>" data-proyecto-nombre="<?php echo $row['nombre']; ?>">Calificar</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Modal de confirmación -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas calificar el proyecto <span id="proyecto-name"></span>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="#" id="confirm-btn" class="btn btn-primary">Sí, calificar</a>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Al hacer clic en el botón de calificar, actualizamos el nombre del proyecto en el modal
        var confirmModal = document.getElementById('confirmModal');
        confirmModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Botón que abrió el modal
            var proyectoId = button.getAttribute('data-proyecto-id');
            var proyectoNombre = button.getAttribute('data-proyecto-nombre');

            // Actualizamos el nombre del proyecto en el modal
            var proyectoName = confirmModal.querySelector('#proyecto-name');
            var confirmBtn = document.getElementById('confirm-btn');

            proyectoName.textContent = proyectoNombre;

            // Cambiamos el enlace de confirmación para redirigir a la página de evaluación del proyecto
            confirmBtn.setAttribute('href', 'evaluacion.php?proyecto_id=' + proyectoId);
        });
    </script>

</body>
</html>

<?php
$conexion->close();
?>