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

// Consulta para obtener los proyectos y su calificación final promedio
$sql = "
    SELECT 
        p.id, 
        p.nombre, 
        p.descripcion, 
        AVG(c.puntuacion) AS calificacion_final, 
        COUNT(c.id) AS num_calificaciones,
        p.fecha_creacion
    FROM 
        proyectos p
    LEFT JOIN 
        calificaciones c ON p.id = c.proyecto_id
    GROUP BY 
        p.id
    ORDER BY 
        calificacion_final DESC
";
$result = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Resultados de Proyectos Calificados</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
            margin-left: 0px;
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

        /* Mejorar el estilo para dispositivos pequeños */
        @media (max-width: 768px) {
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

            /* Asegurarse de que los botones "Ver" estén bien alineados */
            .table td button {
                width: 100%;
                text-align: left;
                margin-bottom: 10px;
            }

            /* Ocultar columnas en dispositivos pequeños */
            .table th:nth-child(2),
            .table td:nth-child(2),
            .table th:nth-child(5),
            .table td:nth-child(5) {
                display: none;
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

            /* Otros ajustes si es necesario */
            .container {
                margin-top: 90px;
            }
                }
    </style>
</head>
<body>

    <!-- Barra superior -->
    <div class="topbar">
        <button class="hamburger-menu" onclick="toggleSidebar()">☰</button>
        <span class="h3 ms-4">Calificaciones</span>
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
        <div class="container mt-5">
        <h1 class="text-center mb-4">Resultados de los Proyectos Calificados</h1>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Proyecto</th>
                        <th>Descripción</th>
                        <th>Calificación Final</th>
                        <th>Fecha de Creación</th>
                        <th>Comentarios</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $i = 1;
                        while ($row = $result->fetch_assoc()) {
                            $calificacion_final = $row['calificacion_final'] ? number_format($row['calificacion_final'] * 20, 2) : 'Sin calificacion';
                            $descripcion = nl2br(htmlspecialchars($row['descripcion']));
                            
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                            echo '<td>';
                            echo '<button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#descripcion_' . $row['id'] . '" aria-expanded="false" aria-controls="descripcion_' . $row['id'] . '">Ver</button>';
                            echo '<div class="collapse mt-2" id="descripcion_' . $row['id'] . '">';
                            echo '<div class="card card-body">' . $descripcion . '</div>';
                            echo '</div>';
                            echo '</td>';
                            echo "<td>" . $calificacion_final . "</td>";
                            echo "<td>" . $row['fecha_creacion'] . "</td>";
                            echo "<td>";

                            if ($row['num_calificaciones'] > 0) {
                                echo '<button class="btn btn-info btn-sm" data-bs-toggle="collapse" data-bs-target="#comentarios_' . $row['id'] . '" aria-expanded="false" aria-controls="comentarios_' . $row['id'] . '">Ver comentarios</button>';
                                echo '<div class="collapse mt-2" id="comentarios_' . $row['id'] . '">';

                                // Obtener comentarios para este proyecto
                                $sql_comentarios = "
                                    SELECT u.nombre AS usuario, c.comentario, c.fecha_calificacion
                                    FROM calificaciones c
                                    JOIN usuarios u ON c.user_id = u.id
                                    WHERE c.proyecto_id = ? 
                                    GROUP BY u.id
                                ";
                                $stmt_comentarios = $conexion->prepare($sql_comentarios);
                                $stmt_comentarios->bind_param("i", $row['id']);
                                $stmt_comentarios->execute();
                                $result_comentarios = $stmt_comentarios->get_result();

                                if ($result_comentarios->num_rows > 0) {
                                    echo '<ul class="list-group">';
                                    while ($comentario = $result_comentarios->fetch_assoc()) {
                                        echo '<li class="list-group-item">';
                                        echo '<strong>' . htmlspecialchars($comentario['usuario']) . ':</strong>';
                                        echo '<p>' . nl2br(htmlspecialchars($comentario['comentario'])) . '</p>';
                                        echo '<small class="text-muted">' . $comentario['fecha_calificacion'] . '</small>';
                                        echo '</li>';
                                    }
                                    echo '</ul>';
                                } else {
                                    echo '<p>No hay comentarios para este proyecto.</p>';
                                }

                                echo '</div>';
                            } else {
                                echo "Sin comentarios";
                            }
                            
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No hay proyectos calificados</td></tr>";
                    }

                    $conexion->close();
                    ?>
                    </tbody>
                </table>
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
