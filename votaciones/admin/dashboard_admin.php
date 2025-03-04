<?php
session_start();

// Verificar si el usuario está logueado y si tiene rol de 'admin'
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php"); // Redirige a login si no es admin
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

// Obtener los proyectos activos
$sql_proyectos = "SELECT id, nombre, estado FROM proyectos WHERE estado = 1";
$result_proyectos = $conexion->query($sql_proyectos);

$nombre_admin = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : "Administrador";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
    /* Estilos para el cuerpo y la barra lateral */
    body {
        font-family: 'Arial', sans-serif;
        padding-top: 50px;
        margin: 0;
        overflow-x: hidden; /* Asegura que no haya desplazamiento horizontal */
    }

    /* Barra lateral oculta por defecto */
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
        z-index: 1000; /* Asegura que se sobreponga al contenido */
    }

    /* Barra lateral abierta */
    .sidebar.open {
        left: 0; /* Muestra la barra lateral cuando tiene la clase 'open' */
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

    /* Contenido principal */
    .content {
        padding: 30px;
        z-index: 1; /* Asegura que el contenido esté por debajo de la barra lateral cuando esté abierta */
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

    /* Estilos para pantallas pequeñas (celulares) */
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

    <!-- Contenido principal -->
    <div class="content" id="content">
        <div class="container">
            <br>
            <h2 class="mt-5">Bienvenido, <?php echo htmlspecialchars($nombre_admin); ?> 👋</h2>
            <p>Este es tu panel de administración. Desde aquí puedes gestionar proyectos, calificaciones y usuarios.</p>
        </div>
    </div>

    <script>
        // Función para alternar la barra lateral
        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("open");
        }
    </script>

</body>
</html>
