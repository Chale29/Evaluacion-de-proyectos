<?php
ob_start(); // Evita problemas con redirecciones
session_start();

$host = "localhost";
$user = "root";
$password = "";
$dbname = "votaciones";
$conexion = new mysqli($host, $user, $password, $dbname);

if ($conexion->connect_error) {
    die("<div class='alert alert-danger mt-3'>Error de conexión: " . $conexion->connect_error . "</div>");
}

// Manejo del login
if (isset($_POST['login'])) {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $sql = "SELECT id, email, password, rol FROM usuarios WHERE email = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Verifica si encontró un usuario con ese email
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $db_email, $db_password, $rol);
        $stmt->fetch();

        // Verifica la contraseña
        if (password_verify($password, $db_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['email'] = $db_email;
            $_SESSION['rol'] = $rol; // Guardar el rol en la sesión

            if ($rol === 'admin') {
                header("Location: admin/dashboard_admin.php");
            } else {
                header("Location: home.php");
            }
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Correo no registrado.";
    }

    $stmt->close();
}

// Si se está registrando un nuevo usuario
if (isset($_POST['register'])) {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    
    // Cifrado de la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Verificar si el correo ya existe
    $sql = "SELECT id FROM usuarios WHERE email = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "El correo ya está registrado.";
    } else {
        // Registrar al nuevo usuario
        $sql = "INSERT INTO usuarios (email, password, rol) VALUES (?, ?, 'cliente')";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ss", $email, $hashed_password);
        if ($stmt->execute()) {
            header("Location: login.php?registro_exitoso=true");
            exit();
        } else {
            $error = "Error al registrar el usuario.";
        }
    }

    $stmt->close();
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="d-flex justify-content-center align-items-center vh-100" style="background-color: #f8f9fa;">
    <div class="card p-4 shadow" style="width: 400px;">
        <h3 class="text-center mb-4">Iniciar Sesión</h3>
        <?php 
        if (isset($_GET['registro_exitoso'])) {
            echo "<div class='alert alert-success'>Registro exitoso. Ahora inicia sesión.</div>";
        }
        if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; 
        ?>
        
        <!-- Formulario de inicio de sesión -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100">Ingresar</button>
        </form>

        <div class="mt-3 text-center">
            <p>¿No tienes cuenta? <a href="singin.php">Regístrate aquí</a></p>
        </div>
    </div>
</body>
</html>
