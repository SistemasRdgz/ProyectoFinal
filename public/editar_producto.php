<?php
require_once "../helpers/auth.php";
require_once "../config/database.php";
require_once "../app/models/producto.php";

requiereAdmin();

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Solicitud inválida para editar el producto.";
    header("Location: productos.php");
    exit;
}

$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

if (!$id) {
    $_SESSION['error'] = "El producto seleccionado no es válido.";
    header("Location: productos.php");
    exit;
}

$db = (new Database())->connect();
$model = new Producto($db);

$producto = $model->getById($id);

if (!$producto) {
    $_SESSION['error'] = "El producto no existe.";
    header("Location: productos.php");
    exit;
}

$categorias = $db->query("SELECT IdCategoria, Nombre FROM categorias ORDER BY Nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
$proveedores = $db->query("SELECT IdProveedor, Nombre FROM proveedores ORDER BY Nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Producto</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <a href="usuarios.php"><i class="bi bi-people"></i> Usuarios</a>

    <div style="position:absolute; bottom:20px; width:100%;">
        <a href="logout.php"><i class="bi bi-box-arrow-left"></i> Cerrar sesión</a>
    </div>
</div>

<div class="content">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">✏️ Editar Producto</h3>
        <a href="productos.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card shadow p-4">
        <form action="update_producto.php" method="POST">

            <input type="hidden" name="id" value="<?= htmlspecialchars($producto['IdProducto']) ?>">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Nombre</label>
                    <input 
                        class="form-control" 
                        name="nombre" 
                        value="<?= htmlspecialchars($producto['Nombre']) ?>" 
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Precio</label>
                    <input 
                        class="form-control" 
                        name="precio" 
                        type="number" 
                        step="0.01" 
                        min="0.01"
                        value="<?= htmlspecialchars($producto['Precio']) ?>" 
                        required
                    >
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea 
                        class="form-control" 
                        name="descripcion"
                    ><?= htmlspecialchars($producto['Descripcion'] ?? '') ?></textarea>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Stock actual</label>
                    <input 
                        class="form-control" 
                        name="stock_actual" 
                        type="number" 
                        min="0"
                        value="<?= htmlspecialchars($producto['StockActual']) ?>" 
                        required
                    >
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Stock mínimo</label>
                    <input 
                        class="form-control" 
                        name="stock_minimo" 
                        type="number" 
                        min="0"
                        value="<?= htmlspecialchars($producto['StockMinimo']) ?>" 
                        required
                    >
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Fecha de vencimiento</label>
                    <input 
                        class="form-control" 
                        name="fecha_vencimiento" 
                        type="date"
                        value="<?= htmlspecialchars($producto['FechaVencimiento']) ?>" 
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Categoría</label>
                    <select name="id_categoria" class="form-control">
                        <option value="">Sin categoría</option>
                        <?php foreach ($categorias as $c): ?>
                            <option 
                                value="<?= htmlspecialchars($c['IdCategoria']) ?>"
                                <?= $producto['IdCategoria'] == $c['IdCategoria'] ? 'selected' : '' ?>
                            >
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
                            <option 
                                value="<?= htmlspecialchars($pr['IdProveedor']) ?>"
                                <?= $producto['IdProveedor'] == $pr['IdProveedor'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($pr['Nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>

            <button class="btn btn-success">
                <i class="bi bi-save"></i> Guardar Cambios
            </button>

        </form>
    </div>

</div>

</body>
</html>