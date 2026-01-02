<?php
session_start();
include("conexion.php");

$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$fecha_nacimiento = $_POST['fecha_nacimiento'];
$usuario = $_POST['usuario'];
$clave = $_POST['clave'];

// Encriptar la contraseña
$clave_encriptada = password_hash($clave, PASSWORD_DEFAULT);

$query_validar = "SELECT * FROM usuarios WHERE usuario='$usuario'";
$resultado_validar = mysqli_query($conexion, $query_validar);

if (mysqli_num_rows($resultado_validar) > 0) {
    echo "El nombre de usuario ya está en uso. Por favor, elige otro.";
    
} else {
    $query = "INSERT INTO usuarios ( usuario, clave, nombre, apellido, fecha_nacimiento) VALUES ('$usuario', '$clave_encriptada', '$nombre', '$apellido', '$fecha_nacimiento')";
    $resultado = mysqli_query($conexion, $query);
    if ($resultado) {
    header("Location: login.php");
    exit();
} else {
    echo "Error al registrar usuario: " . mysqli_error($conexion);
}

}
?>