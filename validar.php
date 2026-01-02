<?php
session_start();
include("conexion.php");

$usuario = $_POST['usuario'];
$clave   = $_POST['clave'];

$query = "SELECT * FROM usuarios WHERE usuario='$usuario'";
$resultado = mysqli_query($conexion, $query);

if (mysqli_num_rows($resultado)>0) {
    $fila = mysqli_fetch_assoc($resultado);

    //Validar la contraseña
    if (password_verify($clave,$fila['clave'])) {
    // Se guaradan los datos en la sesión
         $_SESSION['usuario'] = $fila['usuario'];
        $_SESSION['id'] = $fila['id'];             
        $_SESSION['nombre'] = $fila['nombre'];     
        $_SESSION['apellido'] = $fila['apellido'];
        
        header("Location: dashboard.php");
        exit();
    }else {
        echo "Clave Incorrecta";
    }
}else {
     echo "Usuario no encontrado";
}
?>

