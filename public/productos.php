<?php 
require_once "../helpers/auth.php";
require_once "../config/database.php";
require_once "../app/models/producto.php";

$usuario = usuarioActual();
$rol = strtolower($_SESSION['user']['Rol'] ?? 'usuario');
$esAdmin = esAdmin();

$db = (new Database())->connect();
$model = new Producto($db);
$productos = $model->getAll();

$categorias = $db->query("SELECT IdCategoria, Nombre FROM categorias ORDER BY Nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
$proveedores = $db->query("SELECT IdProveedor, Nombre FROM proveedores ORDER BY Nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Productos</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="sidebar">
    <h4>Farmacia SJ</h4>

    <a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="productos.php"><i class="bi bi-capsule"></i> Productos</a>
    <a href="carrito.php"><i class="bi bi-cart4"></i> Compras</a>
    <a href="movimientos.php"><i class="bi bi-arrow-left-right"></i> Movimientos</a>

    <?php if ($esAdmin): ?>
        <a href="usuarios.php"><i class="bi bi-people"></i> Usuarios</a>
    <?php endif; ?>

    <div style="position:absolute; bottom:20px; width:100%;">
        <a href="logout.php"><i class="bi bi-box-arrow-left"></i> Cerrar sesión</a>
    </div>
</div>

<div class="content">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">📦 Catálogo de Productos</h3>
            <small class="text-muted">
                Usuario: <?= htmlspecialchars($usuario['Nombre'] ?? 'Usuario') ?> |
                Rol: <?= htmlspecialchars($usuario['Rol'] ?? '') ?>
            </small>
        </div>

        <?php if ($esAdmin): ?>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAdd">
                <i class="bi bi-plus-circle"></i> Nuevo Producto
            </button>
        <?php endif; ?>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success']; ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <input 
        class="form-control mb-3" 
        id="buscarProducto"
        placeholder="Buscar producto por nombre, categoría o proveedor..."
        onkeyup="filtrarProductos()"
    >

    <?php if ($esAdmin): ?>

        <div class="card shadow">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="tablaProductos">

                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Proveedor</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Mínimo</th>
                            <th>Vencimiento</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($productos as $p): ?>
                        <?php
                            $stockActual = (int)$p['StockActual'];
                            $stockMinimo = (int)$p['StockMinimo'];
                            $fechaVencimiento = $p['FechaVencimiento'];

                            $badgeStock = $stockActual <= $stockMinimo ? 'bg-danger' : 'bg-success';

                            $hoy = new DateTime();
                            $fecha = new DateTime($fechaVencimiento);
                            $dias = (int)$hoy->diff($fecha)->format('%r%a');

                            if ($dias < 0) {
                                $estadoVencimiento = '<span class="badge bg-dark">Vencido</span>';
                            } elseif ($dias <= 30) {
                                $estadoVencimiento = '<span class="badge bg-warning text-dark">Por vencer</span>';
                            } else {
                                $estadoVencimiento = '<span class="badge bg-success">Vigente</span>';
                            }
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($p['IdProducto']) ?></td>
                            <td><?= htmlspecialchars($p['Nombre']) ?></td>
                            <td><?= htmlspecialchars($p['Categoria'] ?? 'Sin categoría') ?></td>
                            <td><?= htmlspecialchars($p['Proveedor'] ?? 'Sin proveedor') ?></td>
                            <td>$<?= number_format((float)$p['Precio'], 2) ?></td>
                            <td>
                                <span class="badge <?= $badgeStock ?>">
                                    <?= htmlspecialchars($p['StockActual']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($p['StockMinimo']) ?></td>
                            <td><?= htmlspecialchars($p['FechaVencimiento']) ?></td>
                            <td><?= $estadoVencimiento ?></td>

                            <td class="text-center">
                                <a href="editar_producto.php?id=<?= urlencode($p['IdProducto']) ?>" 
                                   class="btn btn-warning btn-sm"
                                   title="Editar producto">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="eliminar_producto.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($p['IdProducto']) ?>">
                                    <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Está seguro de eliminar este producto?');"
                                        title="Eliminar producto">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                        <?php if (empty($productos)): ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted">
                                No hay productos registrados.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>

    <?php else: ?>

        <div class="row" id="contenedorProductos">

            <?php foreach ($productos as $p): ?>
            <?php
                $stockActual = (int)$p['StockActual'];
                $stockMinimo = (int)$p['StockMinimo'];
                $sinStock = $stockActual <= 0;
            ?>

            <div class="col-md-3 mb-4 producto-card">

                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">

                        <h5><?= htmlspecialchars($p['Nombre']) ?></h5>

                        <p class="text-muted small">
                            <?= htmlspecialchars($p['Descripcion'] ?? '') ?>
                        </p>

                        <p class="text-success fw-bold">
                            $<?= number_format((float)$p['Precio'], 2) ?>
                        </p>

                        <span class="badge <?= $stockActual <= $stockMinimo ? 'bg-danger' : 'bg-success' ?> mb-2">
                            Stock: <?= htmlspecialchars($p['StockActual']) ?>
                        </span>

                        <p class="small mt-2 mb-2">
                            Vence: <?= htmlspecialchars($p['FechaVencimiento']) ?>
                        </p>

                        <form action="add_cart.php" method="POST">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($p['IdProducto']) ?>">

                            <button class="btn btn-primary w-100" <?= $sinStock ? 'disabled' : '' ?>>
                                🛒 <?= $sinStock ? 'Sin stock' : 'Agregar al carrito' ?>
                            </button>
                        </form>

                    </div>
                </div>

            </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>

<?php if ($esAdmin): ?>
<div class="modal fade" id="modalAdd" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content p-3">

            <h4 class="mb-3">Nuevo Producto</h4>

            <form action="save_producto.php" method="POST">

                <div class="row">

                    <div class="col-md-6 mb-2">
                        <label class="form-label">Nombre</label>
                        <input name="nombre" class="form-control" placeholder="Nombre del producto" required>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label class="form-label">Precio</label>
                        <input name="precio" type="number" step="0.01" min="0.01" class="form-control" placeholder="Precio" required>
                    </div>

                    <div class="col-md-12 mb-2">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" placeholder="Descripción del producto"></textarea>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label class="form-label">Stock actual</label>
                        <input name="stock_actual" type="number" min="0" class="form-control" placeholder="Stock actual" required>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label class="form-label">Stock mínimo</label>
                        <input name="stock_minimo" type="number" min="0" class="form-control" placeholder="Stock mínimo" value="5" required>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label class="form-label">Fecha de vencimiento</label>
                        <input name="fecha_vencimiento" type="date" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label class="form-label">Categoría</label>
                        <select name="id_categoria" class="form-control">
                            <option value="">Sin categoría</option>
                            <?php foreach ($categorias as $c): ?>
                                <option value="<?= htmlspecialchars($c['IdCategoria']) ?>">
                                    <?= htmlspecialchars($c['Nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Proveedor</label>
                        <select name="id_proveedor" class="form-control">
                            <option value="">Sin proveedor</option>
                            <?php foreach ($proveedores as $pr): ?>
                                <option value="<?= htmlspecialchars($pr['IdProveedor']) ?>">
                                    <?= htmlspecialchars($pr['Nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>

                <button class="btn btn-success w-100">
                    <i class="bi bi-save"></i> Guardar Producto
                </button>
            </form>

        </div>
    </div>
</div>
<?php endif; ?>

<script>
function filtrarProductos() {
    const filtro = document.getElementById("buscarProducto").value.toLowerCase();

    const tabla = document.getElementById("tablaProductos");
    if (tabla) {
        const filas = tabla.getElementsByTagName("tr");

        for (let i = 1; i < filas.length; i++) {
            const texto = filas[i].innerText.toLowerCase();
            filas[i].style.display = texto.includes(filtro) ? "" : "none";
        }
    }

    const cards = document.querySelectorAll(".producto-card");
    cards.forEach(card => {
        const texto = card.innerText.toLowerCase();
        card.style.display = texto.includes(filtro) ? "" : "none";
    });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>