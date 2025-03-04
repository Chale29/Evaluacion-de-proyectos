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

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre'])) {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    
    // Validación de campos
    if (empty($nombre) || empty($descripcion)) {
        $mensaje = "Por favor, complete todos los campos.";
    } else {
        // Inserción en la base de datos
        $sql = "INSERT INTO proyectos (nombre, descripcion, estado) VALUES (?, ?, 1)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ss", $nombre, $descripcion);
        
        if ($stmt->execute()) {
            $mensaje = "Proyecto creado exitosamente.";
        } else {
            $mensaje = "Error al crear el proyecto: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Eliminar un proyecto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['eliminar'])) {
    $id = $_POST['eliminar'];
    $sql = "UPDATE proyectos SET estado = 0 WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Proyectos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
    /* Estilos personalizados (sin cambios) */
    body {
        font-family: 'Arial', sans-serif;
        padding-top: 50px;
    }

    .sidebar {
        position: fixed;
        top: 0;
        bottom: 0;
        left: -250px; /* Inicialmente escondido */
        width: 250px;
        background-color: #003366;
        color: white;
        padding-top: 30px;
        transition: left 0.3s ease-in-out;
    }

    .sidebar a {
        color: white;
        text-decoration: none;
        padding: 15px;
        display: block;
    }

    .sidebar a:hover {
        background-color: rgb(42, 144, 247);
    }

    .container {
        margin-top: 70px;
    }

    .content {
        margin-left: 250px;
        padding: 30px;
        transition: margin-left 0.3s ease-in-out;
    }

    .card {
        border-radius: 10px;
    }

    .card-body {
        padding: 20px;
    }

    .btn-primary {
        background-color: #003366;
        border: none;
    }

    .btn-primary:hover {
        background-color: #002244;
    }

    .footer {
        text-align: center;
        margin-top: 50px;
        font-size: 0.9rem;
        color: #777;
    }

    /* Notificación */
    .notification {
            position: fixed;
            bottom: 20px;
            right: -300px;
            background-color: #28a745;
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            transition: right 0.5s ease-in-out;
        }

        .notification.show {
            right: 20px;
        }

    /* Barra superior */
    .topbar {
        background-color: #003366;
        color: white;
        padding: 10px 20px;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1001;
    }

    .topbar .hamburger-menu {
        font-size: 30px;
        color: white;
        background-color: transparent;
        border: none;
        cursor: pointer;
    }

    .topbar .hamburger-menu:hover {
        color: #575757;
    }

    /* Barra lateral abierta */
    .sidebar.open {
        left: 0;
    }

    /* Barra lateral abierta en el contenido */
    .content.open {
        margin-left: 250px;
    }

    .card {
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .btn-primary {
        background-color: #003366;
        border: none;
    }

    .btn-primary:hover {
        background-color: #002244;
    }

    /* Notificaciones Toast en la parte inferior derecha */
    .toast-container.position-fixed.bottom-0.end-0.p-3 {
        position: fixed;
        bottom: 0;
        end: 0;
        padding: 1rem;
        z-index: 1050;
    }

    /* Estilos específicos para pantallas pequeñas (celulares) */
    @media (max-width: 767px) {
        /* Barra lateral sobrepuesta en dispositivos móviles */
        .sidebar {
            position: absolute;
            left: -250px;
            z-index: 1000;
        }

        .sidebar.open {
            left: 0;
        }

        .content.open {
            margin-left: 0;
        }

        /* Asegurar que el contenido se vea completo cuando el sidebar está abierto */
        .content {
            padding-left: 1;
        }
    }
</style>
</head>
<body>

    <?php if ($mensaje): ?>
        <div id="notification" class="notification">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
        <script>
            setTimeout(() => {
                document.getElementById('notification').classList.add('show');
                setTimeout(() => {
                    document.getElementById('notification').classList.remove('show');
                }, 3000);
            }, 500);
        </script>
    <?php endif; ?>

    <!-- Barra superior -->
    <div class="topbar">
        <button class="hamburger-menu" onclick="toggleSidebar()">☰</button>
        <span class="h3 ms-4">Panel de Administrador</span>
    </div>

    <!-- Barra lateral -->
    <div class="sidebar mt-5" id="sidebar">
        <a href="dashboard_admin.php">Inicio</a>
        <a href="crear_proyecto.php">Crear Proyecto</a>
        <a href="proyectos_activos.php">Estado de los proyectos</a>
        <a href="calificaciones.php">Ver notas del proyecto</a>
        <a href="register.php">Registrar nuevo usuario</a>
        <a href="detalle_usuario.php">Ver usuarios</a>
        <form action="cerrar_sesion.php" method="POST" class="mt-3">
            <button type="submit" class="btn btn-danger w-100">Cerrar Sesión</button>
        </form>
    </div>

    <div class="container mt-5">
        <div class="row">
            <!-- Card 1: Crear Proyecto -->
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="text-center">Creacion de proyecto</h5>
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre del Proyecto</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Crear Proyecto</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Card 2: Lista de Proyectos Activos -->
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="text-center">Proyectos Activos</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nombre del Proyecto</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT id, nombre FROM proyectos WHERE estado = 1";
                                    $result = $conexion->query($sql);
                                    
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                    <td>" . $row['nombre'] . "</td>
                                                    <td>
                                                        <form action='detalle_proyecto.php' method='POST'>
                                                            <input type='hidden' name='proyecto_id' value='" . $row['id'] . "'>
                                                            <button type='submit' class='btn btn-primary btn-sm'>Crear Item</button>
                                                        </form>
                                                    </td>
                                                </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='2' class='text-center'>No hay proyectos creados.</td></tr>";
                                    }
                                    $conexion->close();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notificaciones Toast -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Notificación</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <?php echo $mensaje; ?>
            </div>
        </div>
    </div>

    <script>
        // Función para alternar la barra lateral
        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("open");
            document.getElementById("content").classList.toggle("open");
        }
    </script>

</body>
</html>
