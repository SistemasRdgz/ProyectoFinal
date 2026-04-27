<?php
session_start();
require_once "../config/database.php";
require_once "../app/models/Producto.php";

$db = (new Database())->connect();
$model = new Producto($db);

/* ===================== */
/* CARRO REAL (SOLO IDS) */
/* ===================== */
$carrito = $_SESSION['carrito'] ?? [];
?>

<!DOCTYPE html>
<html>
<head>
<title>Carrito</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">

<h2>🛒 Mi Carrito</h2>

<?php if(empty($carrito)): ?>

    <div class="alert alert-warning">
        No hay productos en el carrito
    </div>

<?php else: ?>

<table class="table table-bordered">

    <thead>
        <tr>
            <th>Producto</th>
            <th>Precio</th>
        </tr>
    </thead>

    <tbody>

    <?php 
    $total = 0;

    foreach($carrito as $id):

        $p = $model->getById($id);
        $total += $p['Precio'];
    ?>

        <tr>
            <td><?= $p['Nombre'] ?></td>
            <td>$<?= $p['Precio'] ?></td>
        </tr>

    <?php endforeach; ?>

    </tbody>

</table>

<h4>Total: $<?= $total ?></h4>

<a href="vaciar_carrito.php" class="btn btn-danger">
    Vaciar carrito
</a>

<form action="checkout.php" method="POST">
    <button class="btn btn-success w-100 mt-3">
        💳 Pagar / Confirmar compra
    </button>
</form>

<?php endif; ?>

<a href="productos.php" class="btn btn-primary mt-3">← Volver</a>

</body>
</html>