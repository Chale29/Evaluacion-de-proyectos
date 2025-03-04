<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    echo "error";
    exit();
}

$host = "localhost";
$user = "root";
$password = "";
$dbname = "votaciones";
$conexion = new mysqli($host, $user, $password, $dbname);

if ($conexion->connect_error) {
    echo "error";
    exit();
}

// Verificar que los datos fueron enviados
if (!isset($_POST['proyecto_id']) || !isset($_POST['pregunta'])) {
    echo "error";
    exit();
}

$proyecto_id = intval($_POST['proyecto_id']);
$pregunta = trim($_POST['pregunta']);

if (empty($pregunta)) {
    echo "error";
    exit();
}

// Insertar la nueva pregunta en la base de datos
$sql = "INSERT INTO preguntas (proyecto_id, pregunta) VALUES (?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("is", $proyecto_id, $pregunta);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}

$conexion->close();
?>
