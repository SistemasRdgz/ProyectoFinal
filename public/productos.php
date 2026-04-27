<?php 
require_once "../helpers/auth.php";
require_once "../config/database.php";
require_once "../app/models/Producto.php";

$rol = $_SESSION['user']['Rol']; 

$db = (new Database())->connect();
$model = new Producto($db);
$productos = $model->getAll();
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

<!-- SIDEBAR -->
<div class="sidebar">
    <h4>Farmacia</h4>

    <?php if($rol === 'usuario'): ?>
        <a href="carrito.php" class="btn btn-dark mb-3">Ver carrito</a>
    <?php endif; ?>

    <a href="dashboard.php">Dashboard</a>
    <a href="productos.php">Productos</a>
</div>

<!-- CONTENIDO -->
<div class="content">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Catálogo de Productos</h3>

        <?php if($rol === 'admin'): ?>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAdd">
            <i class="bi bi-plus-circle"></i> Nuevo Producto
        </button>
        <?php endif; ?>
    </div>

    <input class="form-control mb-3" placeholder="Buscar producto...">

    <!-- ========================= -->
    <!-- 👑 ADMIN VIEW (TABLA) -->
    <!-- ========================= -->
    <?php if($rol === 'admin'): ?>

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
                        <span class="badge bg-success">
                            <?= $p['StockActual'] ?>
                        </span>
                    </td>

                    <td>
                        <a href="editar_producto.php?id=<?= $p['IdProducto'] ?>" 
                           class="btn btn-warning btn-sm">
                            ✏️
                        </a>

                        <form action="eliminar_producto.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $p['IdProducto'] ?>">
                            <button class="btn btn-danger btn-sm"
                                onclick="return confirm('¿Eliminar este producto?')">
                                🗑️
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>

    <?php endif; ?>


    <!-- ========================= -->
    <!-- 🛍️ USER VIEW (CARDS) -->
    <!-- ========================= -->
    <?php if($rol === 'usuario'): ?>

    <div class="row">

        <?php foreach($productos as $p): ?>

        <div class="col-md-3 mb-4">

            <div class="card h-100 shadow-sm">

                <div class="card-body text-center">

                    <h5><?= $p['Nombre'] ?></h5>

                    <p class="text-success fw-bold">
                        $<?= $p['Precio'] ?>
                    </p>

                    <span class="badge bg-success mb-2">
                        Stock: <?= $p['StockActual'] ?>
                    </span>

                    <form action="add_cart.php" method="POST">
                        <input type="hidden" name="id" value="<?= $p['IdProducto'] ?>">

                        <button class="btn btn-primary w-100">
                            🛒 Agregar al carrito
                        </button>
                    </form>

                </div>

            </div>

        </div>

        <?php endforeach; ?>

    </div>

    <?php endif; ?>

</div>


<!-- ========================= -->
<!-- MODAL ADMIN -->
<!-- ========================= -->
<?php if($rol === 'admin'): ?>
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
<?php endif; ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>