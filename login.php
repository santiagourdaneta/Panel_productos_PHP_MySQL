<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar sesión</title>
  <link rel="stylesheet" href="estilos.css">
</head>
<body class="pagina-autenticacion pagina-autenticacion--login">
  <main class="tarjeta-acceso">
    <div class="tarjeta-acceso__icono" aria-hidden="true">
      <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="11" width="18" height="10" rx="2"></rect>
        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
      </svg>
    </div>
    <h1 class="tarjeta-acceso__titulo">Bienvenido nuevamente</h1>
    <p class="tarjeta-acceso__descripcion">Ingrese sus credenciales para acceder al sistema.</p>

    <form class="formulario-acceso" action="validar.php" method="POST">
      <label class="formulario-acceso__campo">
        <span class="formulario-acceso__etiqueta">Usuario</span>
        <input class="formulario-acceso__input" type="text" name="usuario" placeholder="usuario" required>
      </label>
      <label class="formulario-acceso__campo">
        <span class="formulario-acceso__etiqueta">Contraseña</span>
        <input class="formulario-acceso__input" type="password" name="clave" placeholder="••••••••" required>
      </label>
      <div class="formulario-acceso__acciones">
        <button class="boton boton--primario" type="submit">Ingresar</button>
      </div>
    </form>

    <p class="tarjeta-acceso__enlace">¿Sin cuenta aún? <a class="enlace-accion" href="registro.php">Crear cuenta</a></p>
  </main>

  <footer class="pagina-autenticacion__footer">Realiazado por Diana Clabel Huaman</footer>
</body>
</html>