<?php
  session_start();

if (!isset($_SESSION['usuario'])){
    header("Location: login.php");
    exit();
}

include "conexion.php";

$id = $_SESSION['id'];

$sql = "SELECT nombre, apellido FROM usuarios WHERE id = $id";
$resultado = $conexion->query($sql);
$datos = $resultado->fetch_assoc();
$nombre = $datos['nombre'];
$apellido = $datos['apellido'];

$stockErrores = [];
$mostrarStockModal = isset($_GET['cargar_stock']);
$productoSeleccionado = null;
$stockIngresado = '';

$productos = [];
$productosResultado = $conexion->query("SELECT id, nombre FROM productos ORDER BY nombre ASC");
while ($fila = $productosResultado->fetch_assoc()) {
    $productos[] = $fila;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'actualizar_stock') {
    $mostrarStockModal = true;
    $productoSeleccionado = (int)($_POST['producto_id'] ?? 0);
    $stockIngresado = trim($_POST['stock'] ?? '');

    if (!$productoSeleccionado) {
        $stockErrores[] = 'Seleccione un producto válido.';
    }

    if ($stockIngresado === '' || !is_numeric($stockIngresado) || (int)$stockIngresado < 1) {
        $stockErrores[] = 'El stock debe ser un número mayor o igual a 1.';
    }

    if (empty($stockErrores)) {
        $stmt = $conexion->prepare("UPDATE productos SET stock = ? WHERE id = ?");
        $stockInt = (int)$stockIngresado;
        $stmt->bind_param('ii', $stockInt, $productoSeleccionado);
        $stmt->execute();
        $stmt->close();

        header('Location: dashboard.php?stock_actualizado=1');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="estilos.css">
</head>
<body class="pagina-dashboard">
  <main class="panel-dashboard">
    <header class="panel-dashboard__encabezado">
      <div class="panel-dashboard__icono" aria-hidden="true">
        <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 12h7l2-3 2 6 2-3h5"></path>
          <circle cx="5" cy="12" r="2"></circle>
          <circle cx="19" cy="12" r="2"></circle>
        </svg>
      </div>
      <div>
        <p class="panel-dashboard__etiqueta">Dashboard</p>
        <h1 class="panel-dashboard__titulo">Hola, <?php echo $nombre . " " . $apellido; ?></h1>
        <p class="panel-dashboard__descripcion">Has iniciado sesión correctamente. Utiliza las acciones para continuar.</p>
        <?php if (isset($_GET['stock_actualizado'])): ?>
            <p class="panel-dashboard__alerta panel-dashboard__alerta--exito">Stock actualizado correctamente.</p>
        <?php endif; ?>
      </div>
    </header>

    <div class="panel-dashboard__acciones">
      <a class="boton boton--icono" href="gestion_productos.php">
        <span class="boton__icono" aria-hidden="true">
          <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="4" width="18" height="7" rx="1"></rect>
            <path d="M3 13h18"></path>
            <path d="M8 21h8"></path>
            <path d="M10 17h4"></path>
          </svg>
        </span>
        <span>Gestión de productos</span>
      </a>
      <a class="boton boton--icono" href="?cargar_stock=1">
        <span class="boton__icono" aria-hidden="true">
          <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="18" height="18" rx="2"></rect>
            <path d="M12 7v10"></path>
            <path d="M7 12h10"></path>
          </svg>
        </span>
        <span>Cargar stock</span>
      </a>
      <a class="boton boton--icono" href="logout.php">
        <span class="boton__icono" aria-hidden="true">
          <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10 7V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6a2 2 0 0 1-2-2v-2" />
            <path d="M15 12H3" />
            <path d="M6 9l-3 3 3 3" />
          </svg>
        </span>
        <span>Cerrar sesión</span>
      </a>
    </div>
  </main>
  <?php if ($mostrarStockModal): ?>
    <div class="modal-producto">
      <div class="modal-producto__fondo"></div>
      <div class="modal-producto__contenedor" role="dialog" aria-modal="true">
        <h2 class="modal-producto__titulo">Cargar stock</h2>
        <?php foreach ($stockErrores as $error): ?>
          <p class="mensaje-error"><?= $error ?></p>
        <?php endforeach; ?>
        <form class="formulario-producto" method="POST">
          <label class="formulario-producto__campo">Producto
            <select class="formulario-producto__input" name="producto_id" required>
              <option value="">Seleccione un producto</option>
              <?php foreach ($productos as $producto): ?>
                <option value="<?= $producto['id'] ?>" <?= ($productoSeleccionado === (int)$producto['id']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($producto['nombre']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </label>
          <label class="formulario-producto__campo">Stock
            <input class="formulario-producto__input" type="number" name="stock" min="1" value="<?= htmlspecialchars($stockIngresado) ?>" required>
          </label>
          <input type="hidden" name="accion" value="actualizar_stock">
          <div class="formulario-producto__acciones">
            <button class="boton boton--primario" type="submit">Actualizar stock</button>
            <a class="boton boton--texto" href="dashboard.php">Cancelar</a>
          </div>
        </form>
      </div>
    </div>
  <?php endif; ?>
</body>
</html>