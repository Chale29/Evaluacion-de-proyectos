<?php
// Verificar si la sesión ya está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$user = "root";
$password = "";
$dbname = "votaciones";
$conexion = new mysqli($host, $user, $password, $dbname);

if ($conexion->connect_error) {
    die("<div class='alert alert-danger mt-3'>Error de conexión: " . $conexion->connect_error . "</div>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"] ?? "";
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";
    $password_confirm = $_POST["password_confirm"] ?? "";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "El correo electrónico no es válido.";
    } elseif ($password !== $password_confirm) {
        $error = "Las contraseñas no coinciden.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Verificar si es el primer usuario
        $sql_check = "SELECT COUNT(*) FROM usuarios";
        $result = $conexion->query($sql_check);
        $row = $result->fetch_array();
        $role = ($row[0] == 0) ? 'admin' : 'cliente';
        
        $sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssss", $nombre , $email, $hashed_password, $role);

        if ($stmt->execute()) {
            $success = "Usuario agregado exitosamente.";
        } else {
            $error = "Error al agregar usuario: " . $stmt->error;
        }
        $stmt->close();
    }
}
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Usuario</title>
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
            left: -250px; /* Oculto por defecto en pantallas grandes */
            width: 250px;
            background-color: #003366;
            color: white;
            padding-top: 30px;
            transition: left 0.3s ease-in-out;
            z-index: 1100; /* Asegura que esté sobre el contenido */
        }

        .sidebar.open {
            left: 0;
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

        .content {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* Ocupa toda la altura de la pantalla */
            padding: 20px;
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
            margin-top: 0;
        }

        .card {
            max-width: 500px; /* Ancho máximo cómodo */
            width: 100%;
            border-radius: 10px;
            padding: 20px;
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

        /* Mejorar el estilo para dispositivos pequeños */
        @media (max-width: 768px) {            

            .sidebar {
                width: 60%; /* Ocupa toda la pantalla */
                left: -100%; /* Se esconde completamente */
            }
            
            /* Otros ajustes si es necesario */
            .container {
                margin-top: 90px;
            }
            /* Para la tabla, hacerla desplazable */
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

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

            /* Asegurarse de que la tabla sea más manejable en móvil */
            .container {
                margin-top: 90px;
            }
            
            /* Ajustes en los botones de colapsar para la descripción y comentarios */
            .collapse .card-body {
                padding: 10px;
            }

            /* Ajustar la tabla para que ocupe el 100% del ancho */
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            /* Ajustar la apariencia de los botones */
            .btn-sm {
                font-size: 12px;
                padding: 6px 12px;
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

    <div class="content">
        <div class="card p-4 shadow mt-5">
            <h3 class="text-center mb-4">Agregar Usuario</h3>
            <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
            <form method="POST" action="" onsubmit="return validarContrasena()">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre Completo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="password_confirm" class="form-label">Confirmar Contraseña</label>
                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                    <small id="error-pass" class="text-danger"></small>
                </div>
                <button type="submit" class="btn btn-primary w-100">Agregar Usuario</button>
            </form>
        </div>
    </div>

    <script>
        // Función para alternar la barra lateral
        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("open");
        }
    </script>

    <script>
        function validarContrasena() {
            let pass1 = document.getElementById("password").value;
            let pass2 = document.getElementById("password_confirm").value;
            let errorMsg = document.getElementById("error-pass");

            if (pass1 !== pass2) {
                errorMsg.textContent = "Las contraseñas no coinciden.";
                return false;
            }
            errorMsg.textContent = "";
            return true;
        }
    </script>
</body>
</html>
