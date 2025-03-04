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
    }elseif ($password !== $password_confirm) {
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
</head>
<body class="d-flex justify-content-center align-items-center vh-100" style="background-color: #f8f9fa;">
    <div class="card p-4 shadow" style="width: 400px;">
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
            <div class="d-flex justify-content-start mb-3">
                <a href="admin/dashboard_admin.php" class="btn btn-secondary w-100 mt-3">Volver</a>
            </div>
        </form>
    </div>

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
