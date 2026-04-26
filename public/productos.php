<?php 
require_once "../helpers/auth.php";
require_once "../config/database.php";
require_once "../app/models/Producto.php";

$db = (new Database())->connect();
$model = new Producto($db);
$productos = $model->getAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Productos</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<link rel="stylesheet" href="css/styles.css">
</head>

<body>

<div class="sidebar">
    <h4>Farmacia</h4>

    <a href="dashboard.php">Dashboard</a>
    <a href="productos.php">Productos</a>
</div>

<div class="content">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Catálogo de Productos</h3>

        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAdd">
            <i class="bi bi-plus-circle"></i> Nuevo Producto
        </button>
    </div>

    <input class="form-control mb-3" placeholder="Buscar producto...">

    <div class="card shadow">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach($productos as $p): ?>
                <tr>
                    <td><?= $p['IdProducto'] ?></td>
                    <td><?= $p['Nombre'] ?></td>
                    <td>$<?= $p['Precio'] ?></td>
                    <td>
                        <span class="badge bg-success"><?= $p['StockActual'] ?></span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>

</div>

<!-- MODAL -->
<div class="modal fade" id="modalAdd">
<div class="modal-dialog">
<div class="modal-content p-3">

    <h4>Nuevo Producto</h4>

    <form action="save_producto.php" method="POST">
        <input name="nombre" class="form-control mb-2" placeholder="Nombre">
        <input name="precio" type="number" class="form-control mb-2" placeholder="Precio">
        <input name="stock" type="number" class="form-control mb-2" placeholder="Stock">

        <button class="btn btn-success w-100">Guardar</button>
    </form>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>