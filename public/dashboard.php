<?php require_once "../helpers/auth.php"; ?>
<?php
require_once "../config/database.php";

$db = (new Database())->connect();

/* ===================== */
/* PRODUCTOS */
/* ===================== */
$sql = "SELECT COUNT(*) as total FROM Productos";
$totalProductos = $db->query($sql)->fetch()['total'];

/* ===================== */
/* USUARIOS */
/* ===================== */
$sql = "SELECT COUNT(*) as total FROM Usuarios";
$totalUsuarios = $db->query($sql)->fetch()['total'];

/* ===================== */
/* STOCK BAJO */
/* ===================== */
$sql = "SELECT COUNT(*) as total FROM Productos WHERE StockActual <= 5";
$stockBajo = $db->query($sql)->fetch()['total'];

/* ===================== */
/* VENTAS (según detalle compra) */
/* ===================== */
$sql = "SELECT SUM(Cantidad * Precio) as total FROM DetalleCompras";
$ventas = $db->query($sql)->fetch()['total'];
if($ventas == null) $ventas = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="sidebar">
    <h4>Farmacia SJ</h4>

    <a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="productos.php"><i class="bi bi-capsule"></i> Productos</a>
    <a href="#"><i class="bi bi-arrow-left-right"></i> Movimientos</a>

    <div style="position:absolute; bottom:20px; width:100%;">
        <a href="logout.php"><i class="bi bi-box-arrow-left"></i> Cerrar sesión</a>
    </div>
</div>

<div class="content">

<h2 class="mb-4 fw-bold">📊 Dashboard</h2>

<div class="row">

    <div class="col-md-3">
        <div class="card-box blue text-center">
            <h3>📦</h3>
            <h5>Productos</h5>
            <h3><?= $totalProductos ?></h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-box green text-center">
            <h3>💰</h3>
            <h5>Ventas</h5>
            <h3>$<?= number_format($ventas, 2) ?></h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-box red text-center">
            <h3>👤</h3>
            <h5>Usuarios</h5>
            <h3><?= $totalUsuarios ?></h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-box blue text-center">
            <h3>⚠️</h3>
            <h5>Stock Bajo</h5>
            <h3><?= $stockBajo ?></h3>
        </div>
    </div>

</div>

</div>
</body>
</html>