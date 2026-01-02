
<?php
session_start();
include("conexion.php");
if (!isset($_SESSION['usuario'])){
    header("Location: login.php");
    exit();
}

$errores = [];
$mostrarModal = false;
$modalMode = null; // crear | editar
$productoModal = ['id' => null, 'nombre' => '', 'precio' => '', 'stock' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $modalMode = $_POST['accion'] ?? null;
    $productoModal['nombre'] = trim($_POST['nombre'] ?? '');
    $productoModal['precio'] = trim($_POST['precio'] ?? '');
    $productoModal['stock'] = trim($_POST['stock'] ?? '');
    $productoModal['id'] = isset($_POST['id']) ? (int)$_POST['id'] : null;

    if ($productoModal['nombre'] === '') {
        $errores[] = 'El nombre es obligatorio.';
    }

    if ($productoModal['precio'] === '' || !is_numeric($productoModal['precio'])) {
        $errores[] = 'El precio debe ser un número válido.';
    }

    if($productoModal['stock'] === '' || !is_numeric($productoModal['stock']) || (int)$productoModal['stock'] < 1) {
        $errores[] = 'El stock debe ser mayor o igual a 1.';
    }

    if ($modalMode === 'actualizar' && !$productoModal['id']) {
        $errores[] = 'Falta el identificador del producto a actualizar.';
    }

    if (empty($errores)) {
        $precio = (float)$productoModal['precio'];
        if ($modalMode === 'crear') {
            $stmt = mysqli_prepare($conexion, "INSERT INTO productos (nombre, precio, stock) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, 'sdi', $productoModal['nombre'], $precio, $productoModal['stock']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } elseif ($modalMode === 'actualizar') {
            $stmt = mysqli_prepare($conexion, "UPDATE productos SET nombre = ?, precio = ?, stock = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, 'sdii', $productoModal['nombre'], $precio, $productoModal['stock'], $productoModal['id']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        header('Location: gestion_productos.php');
        exit;
    } else {
        $mostrarModal = true;
        if ($modalMode === 'actualizar') {
            $modalMode = 'editar';
        }
    }
}

if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    $query_eliminar = "DELETE FROM productos WHERE id=$id";
    mysqli_query($conexion, $query_eliminar);

    header("Location: gestion_productos.php");
    exit;
}

if (!$mostrarModal) {
    if (isset($_GET['crear'])) {
        $modalMode = 'crear';
        $mostrarModal = true;
    } elseif (isset($_GET['editar'])) {
        $modalMode = 'editar';
        $mostrarModal = true;

        $productoModal['id'] = (int)$_GET['editar'];
        $resultadoProducto = mysqli_query($conexion, "SELECT nombre, precio,stock FROM productos WHERE id = " . $productoModal['id']);
        if ($resultadoProducto && mysqli_num_rows($resultadoProducto) === 1) {
            $filaProducto = mysqli_fetch_assoc($resultadoProducto);
            $productoModal['nombre'] = $filaProducto['nombre'];
            $productoModal['precio'] = $filaProducto['precio'];
            $productoModal['stock'] = $filaProducto['stock'];
        } else {
            header('Location: gestion_productos.php');
            exit;
        }
    }
}

$productos = [];
$resultado = mysqli_query($conexion, "SELECT * FROM productos");
while ($fila = mysqli_fetch_assoc($resultado)) {
    $productos[$fila['id']] = [ 'nombre' => $fila['nombre'], 'precio' => $fila['precio'], 'stock' => $fila['stock'] ];
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda de Articulos</title>
     <link rel="stylesheet" href="estilos.css">
</head>
<body class="pagina-productos">
    <main class="pagina-productos__contenedor">
        <header class="encabezado-productos">
            <h1 class="encabezado-productos__titulo">Lista de Productos</h1>
            <div class="encabezado-productos__acciones">
                <a class="boton boton--primario" href="?crear=1">Agregar Nuevo Producto</a>
                <a class="boton boton--icono" href="logout.php">
                    <span class="boton__icono" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 7V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6a2 2 0 0 1-2-2v-2" />
                            <path d="M15 12H3" />
                            <path d="M6 9l-3 3 3 3" />
                        </svg>
                    </span>
                    <span>Cerrar sesión</span>
                </a>
            </div>
        </header>

        <section class="tabla-productos">
            <table class="tabla-productos__tabla">
                <thead class="tabla-productos__encabezado">
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th colspan="2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($productos as $id => $producto): ?>
                    <tr class="tabla-productos__fila">
                        <td><?= $producto['nombre'] ?></td>
                        <td>$<?= number_format($producto['precio'], 2) ?></td>
                        <td><?= $producto['stock'] ?></td>
                        <!-- <td class="tabla-productos__acciones"><a class="enlace-accion" href="?editar_stock=<?= $id ?>">Editar stock</a></td> -->
                        <td class="tabla-productos__acciones"> <a class="enlace-accion" href="?editar=<?= $id ?>">Editar</a></td>
                        <td class="tabla-productos__acciones"> <a class="enlace-accion enlace-accion--peligro" href="?eliminar=<?= $id ?>" onclick="return confirm('¿Eliminar este producto?');">Eliminar</a></td>
                        
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <div class="acciones-pagina">
            <button class="boton boton--secundario" onClick="window.location.href='dashboard.php'">Volver al Dashboard</button>
        </div>
    </main>

<?php if ($mostrarModal && $modalMode): ?>
    <div class="modal-producto">
        <div class="modal-producto__fondo"></div>
        <div class="modal-producto__contenedor" role="dialog" aria-modal="true">
            <h2 class="modal-producto__titulo"><?= $modalMode === 'crear' ? 'Nuevo Producto' : 'Editar Producto' ?></h2>
            <?php if (!empty($errores)): ?>
                <?php foreach ($errores as $error): ?>
                    <p class="mensaje-error"><?= $error ?></p>
                <?php endforeach; ?>
            <?php endif; ?>
            <form class="formulario-producto" method="POST" action="">
                <label class="formulario-producto__campo">Nombre
                    <input class="formulario-producto__input" type="text" name="nombre" value="<?= htmlspecialchars($productoModal['nombre']) ?>" required>
                </label>
                <label class="formulario-producto__campo">Precio
                    <input class="formulario-producto__input" type="number" step="0.01" name="precio" value="<?= htmlspecialchars($productoModal['precio']) ?>" required>

                </label>
                <label class="formulario-producto__campo">Stock
                    <input class="formulario-producto__input" type="number" name="stock" value="<?= htmlspecialchars($productoModal['stock']) ?>" required>
                </label>   

                <?php if ($modalMode === 'editar'): ?>
                    <input type="hidden" name="accion" value="actualizar">
                    <input type="hidden" name="id" value="<?= $productoModal['id'] ?>">
                <?php else: ?>
                    <input type="hidden" name="accion" value="crear">
                <?php endif; ?>
                <div class="formulario-producto__acciones">
                    <button class="boton boton--primario" type="submit"><?= $modalMode === 'crear' ? 'Crear' : 'Actualizar' ?></button>
                    <a class="boton boton--texto" href="gestion_productos.php">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

</body>
</html>