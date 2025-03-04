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

// Restaurar proyecto si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['restaurar_id'])) {
    $proyecto_id = $_POST['restaurar_id'];
    $sql_restaurar = "UPDATE proyectos SET estado = 1 WHERE id = ?";
    $stmt = $conexion->prepare($sql_restaurar);
    $stmt->bind_param("i", $proyecto_id);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Proyecto restaurado con éxito.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al restaurar el proyecto.</div>";
    }
    $stmt->close();
}

// Obtener proyectos eliminados
$sql_papelera = "SELECT id, nombre, descripcion FROM proyectos WHERE estado = 0";
$result_papelera = $conexion->query($sql_papelera);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papelera de Proyectos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2 class="text-center mb-4">Papelera de Proyectos</h2>
    <a href="dashboard_admin.php" class="btn btn-secondary mb-3">Volver</a>
    
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
</body>
</html>

<?php
$conexion->close();
?>
