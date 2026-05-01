<?php
require_once "../helpers/auth.php";
require_once "../config/database.php";
require_once "../app/models/producto.php";

$db = (new Database())->connect();
$model = new Producto($db);

$carrito = $_SESSION['carrito'] ?? [];
$total = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Carrito</title>

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

    <?php if (esAdmin()): ?>
        <a href="usuarios.php"><i class="bi bi-people"></i> Usuarios</a>
    <?php endif; ?>

    <div style="position:absolute; bottom:20px; width:100%;">
        <a href="logout.php"><i class="bi bi-box-arrow-left"></i> Cerrar sesión</a>
    </div>
</div>

<div class="content">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">🛒 Carrito de Compra</h2>

        <a href="productos.php" class="btn btn-primary">
            <i class="bi bi-arrow-left"></i> Volver a productos
        </a>
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

    <?php if (empty($carrito)): ?>

        <div class="alert alert-warning">
            No hay productos en el carrito.
        </div>

    <?php else: ?>

        <div class="card shadow">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">

                    <thead class="table-dark">
                        <tr>
                            <th>Producto</th>
                            <th>Precio unitario</th>
                            <th class="text-center">Cantidad</th>
                            <th>Stock disponible</th>
                            <th>Subtotal</th>
                            <th class="text-center">Acciones</th> <!-- NUEVA COLUMNA -->
                        </tr>
                    </thead>

                    <tbody>

                    <?php foreach ($carrito as $item): ?>
                        <?php
                        $idProducto = $item['id'] ?? null;
                        $cantidad = (int)($item['cantidad'] ?? 1);

                        $producto = $model->getById($idProducto);

                        if (!$producto) {
                            continue;
                        }

                        $precio = (float)$producto['Precio'];
                        $subtotal = $precio * $cantidad;
                        $total += $subtotal;
                        ?>

                        <tr>
                            <td class="align-middle"><?= htmlspecialchars($producto['Nombre']) ?></td>
                            <td class="align-middle">$<?= number_format($precio, 2) ?></td>
                            
                            <!-- CELDA DE CANTIDAD MODIFICADA CON BOTONES -->
                            <td class="align-middle">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <a href="update_cart.php?action=sub&id=<?= $idProducto ?>" class="btn btn-sm btn-outline-danger px-2 py-0 fw-bold">-</a>
                                    <span class="fw-bold fs-6"><?= htmlspecialchars($cantidad) ?></span>
                                    <a href="update_cart.php?action=add&id=<?= $idProducto ?>" class="btn btn-sm btn-outline-success px-2 py-0 fw-bold">+</a>
                                </div>
                            </td>
                            
                            <td class="align-middle">
                                <span class="badge bg-info text-dark">
                                    <?= htmlspecialchars($producto['StockActual']) ?>
                                </span>
                            </td>
                            <td class="align-middle">$<?= number_format($subtotal, 2) ?></td>
                            
                            <!-- NUEVA CELDA DE BOTÓN ELIMINAR --> ...
                            <td class="align-middle text-center">
                                <a href="update_cart.php?action=remove&id=<?= $idProducto ?>" class="btn btn-sm btn-danger" title="Eliminar producto">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <h4>Total: $<?= number_format($total, 2) ?></h4>

            <div>
                <a href="vaciar_carrito.php" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Vaciar carrito
                </a>

                <form action="checkout.php" method="POST" style="display:inline;">
                    <button class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Confirmar compra
                    </button>
                </form>
            </div>
        </div>

    <?php endif; ?>

</div>

</body>
</html> 