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

$mensaje = ""; // Variable para el mensaje de notificación

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['eliminar'])) {
    $id = $_POST['eliminar'];
    $sql = "UPDATE proyectos SET estado = 0 WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['restaurar_id'])) {
    $proyecto_id = $_POST['restaurar_id'];
    $sql_restaurar = "UPDATE proyectos SET estado = 1 WHERE id = ?";
    $stmt = $conexion->prepare($sql_restaurar);
    $stmt->bind_param("i", $proyecto_id);
    if ($stmt->execute()) {
        $mensaje = "Proyecto restaurado con éxito.";
    } else {
        $mensaje = "Error al restaurar el proyecto.";
    }
    $stmt->close();
}

$sql_papelera = "SELECT id, nombre, descripcion FROM proyectos WHERE estado = 0";
$result_papelera = $conexion->query($sql_papelera);
?>

<?php if ($mensaje): ?>
    <div id="notification" class="notification">
        <?php echo htmlspecialchars($mensaje); ?>
    </div>
    <script>
        setTimeout(() => {
            document.getElementById('notification').classList.add('show');
        }, 500);  // Retraso de 500ms para permitir la transición

        setTimeout(() => {
            document.getElementById('notification').classList.remove('show');
        }, 3500);  // La notificación desaparecerá después de 3 segundos
    </script>
<?php endif; ?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Proyectos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
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

        /* Barra lateral */
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: -250px;
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

        .sidebar.open {
            left: 0;
        }

        .content {
            margin-left: 30px;
            padding: 30px;
            transition: margin-left 0.3s ease-in-out;
        }

        .content.open {
            margin-left: 0;
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

        /* Estilos generales */
        .container {
            margin-top: 70px;
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

        @media (max-width: 767px) {

            /* Reducir el tamaño de las fuentes y márgenes */
            .table th, .table td {
                font-size: 14px;  /* Tamaño de fuente más pequeño */
                padding: 8px; /* Menor espacio */
            }

            /* Ajustar el espacio entre las filas */
            .table-striped tbody tr:nth-of-type(odd) {
                background-color: #f9f9f9;
            }

            /* Hacer que los botones se vean bien en dispositivos pequeños */
            .btn-sm {
                font-size: 12px;
                padding: 6px 12px;
            }

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
                padding-left: 0;
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

    <header>
        <div class="topbar">
            <button class="hamburger-menu" onclick="toggleSidebar()">☰</button>
            <span class="h3 ms-4">Estado de los proyectos</span>
        </div>
    </header>

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

    <div class="content">
        <h2 class="text-center mt-5">Proyectos Activos</h2>
        <div class="card shadow p-3">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $sql_proyectos = "SELECT id, nombre, descripcion FROM proyectos WHERE estado = 1";
                $result_proyectos = $conexion->query($sql_proyectos);
                
                while ($row = $result_proyectos->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['nombre']) . "</td>
                            <td>" . htmlspecialchars($row['descripcion']) . "</td>
                            <td>
                                <form method='POST' action=''>
                                    <input type='hidden' name='eliminar' value='" . $row['id'] . "'>
                                    <button type='submit' class='btn btn-danger btn-sm'>Eliminar</button>
                                </form>
                            </td>
                        </tr>";
                }
                ?>
                </tbody>
            </table>
        </div>

        <h2 class="text-center mt-5">Proyectos Inactivos</h2>
        <div class="card shadow p-3">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if ($result_papelera->num_rows > 0) {
                    while ($row = $result_papelera->fetch_assoc()) {
                        echo '<tr>
                            <td>' . htmlspecialchars($row['nombre']) . '</td>
                            <td>' . htmlspecialchars($row['descripcion']) . '</td>
                            <td>
                                <form action="" method="POST">
                                    <input type="hidden" name="restaurar_id" value="' . $row['id'] . '">
                                    <button type="submit" class="btn btn-success btn-sm">Restaurar</button>
                                </form>
                            </td>
                        </tr>';
                    }
                } else {
                    echo "<tr><td colspan='3' class='text-center'>No hay proyectos eliminados.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Función para alternar la barra lateral
        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("open");
            document.getElementById("content").classList.toggle("open");
        }
    </script>

<?php $conexion->close(); ?>
</body>
</html>
