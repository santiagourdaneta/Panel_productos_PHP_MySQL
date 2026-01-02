<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body class="pagina-autenticacion pagina-autenticacion--registro">
    <main class="tarjeta-acceso">
        <div class="tarjeta-acceso__icono" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="8" r="4"></circle>
                <path d="M5 21a7 7 0 0 1 14 0"></path>
                <path d="M15 8h4"></path>
                <path d="M17 6v4"></path>
            </svg>
        </div>
        <h1 class="tarjeta-acceso__titulo">Crear una cuenta</h1>
        <p class="tarjeta-acceso__descripcion">Complete los datos para registrarse.</p>

        <form class="formulario-acceso" action="insertar_usuario.php" method="POST">
            <label class="formulario-acceso__campo">
                <span class="formulario-acceso__etiqueta">Nombre</span>
                <input class="formulario-acceso__input" type="text" name="nombre" placeholder="Nombre" required>
            </label>
            <label class="formulario-acceso__campo">
                <span class="formulario-acceso__etiqueta">Apellido</span>
                <input class="formulario-acceso__input" type="text" name="apellido" placeholder="Apellido" required>
            </label>
            <label class="formulario-acceso__campo">
                <span class="formulario-acceso__etiqueta">Fecha de nacimiento</span>
                <input class="formulario-acceso__input" type="date" name="fecha_nacimiento" required>
            </label>
            <label class="formulario-acceso__campo">
                <span class="formulario-acceso__etiqueta">Usuario</span>
                <input class="formulario-acceso__input" type="text" name="usuario" placeholder="usuario" required>
            </label>
            <label class="formulario-acceso__campo">
                <span class="formulario-acceso__etiqueta">Contraseña</span>
                <input class="formulario-acceso__input" type="password" name="clave" placeholder="••••••••" required>
            </label>
            <div class="formulario-acceso__acciones">
                <button class="boton boton--primario" type="submit">Registrar</button>
            </div>
        </form>

        <p class="tarjeta-acceso__enlace">¿Ya tienes cuenta? <a class="enlace-accion" href="login.php">Inicia sesión</a></p>
    </main>
</body>
</html>