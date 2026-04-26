<?php 
require_once "../helpers/auth.php";
require_once "../config/database.php";
require_once "../app/models/Producto.php";

$db = (new Database())->connect();
$model = new Producto($db);

$productos = $model->getAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-4">

    <h2>📦 Productos</h2>

    <!-- BOTÓN AGREGAR -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalAdd">
        + Nuevo Producto
    </button>

    <!-- TABLA -->
    <table class="table table-bordered table-hover">
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
                <td><?= $p['StockActual'] ?></td>

                <td>
                    <a href="edit_producto.php?id=<?= $p['IdProducto'] ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="delete_producto.php?id=<?= $p['IdProducto'] ?>" class="btn btn-danger btn-sm">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

<!-- MODAL -->
<div class="modal fade" id="modalAdd">
    <div class="modal-dialog">
        <div class="modal-content p-3">

            <h4>Nuevo Producto</h4>

            <form action="save_producto.php" method="POST">
                <input name="nombre" class="form-control mb-2" placeholder="Nombre" required>
                <input name="precio" type="number" class="form-control mb-2" placeholder="Precio" required>
                <input name="stock" type="number" class="form-control mb-2" placeholder="Stock" required>

                <button class="btn btn-success">Guardar</button>
            </form>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>