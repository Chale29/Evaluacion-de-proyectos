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

// Excluir usuarios con rol 'admin'
$sql_usuarios = "SELECT id, nombre, email FROM usuarios WHERE rol != 'admin'";
$result_usuarios = $conexion->query($sql_usuarios);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Mejoras en la tabla */
        .table {
            border-radius: 8px;
            overflow: hidden;
            background-color: white;
        }

        .table thead {
            background-color: #003366; /* Azul oscuro para el encabezado */
            color: white;
        }

        .table th, .table td {
            padding: 12px;
            vertical-align: middle;
        }

        .table tbody tr:nth-child(odd) {
            background-color: #f8f9fa; /* Gris claro en filas impares */
        }

        .table tbody tr:nth-child(even) {
            background-color: #e9ecef; /* Gris más oscuro en filas pares */
        }

        .table tbody tr:hover {
            background-color: #d0e4ff; /* Azul claro al pasar el cursor */
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
            z-index: 1000;
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

        /* Botón mejorado */
        .btn-primary {
            background-color: #003366;
            border: none;
            padding: 8px 15px;
            font-size: 14px;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #002244;
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

    <header>
        <div class="topbar">
            <button class="hamburger-menu" onclick="toggleSidebar()">☰</button>
            <span class="h3 ms-4">Lista de Usuarios</span>
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
        <h2 class="text-center mt-5">Usuarios</h2>
        <div class="card shadow p-3">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $result_usuarios->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['nombre']) . "</td>
                                <td>" . htmlspecialchars($row['email']) . "</td>
                            </tr>";
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
