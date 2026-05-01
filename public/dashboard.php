<?php
require_once "../helpers/auth.php";
require_once "../config/database.php";

$db = (new Database())->connect();

$usuario = usuarioActual();
$esAdmin = esAdmin();

/* ===================== */
/* PRODUCTOS */
/* ===================== */
$sql = "SELECT COUNT(*) as total FROM productos";
$totalProductos = $db->query($sql)->fetch()['total'] ?? 0;

/* ===================== */
/* USUARIOS */
/* ===================== */
$sql = "SELECT COUNT(*) as total FROM usuarios";
$totalUsuarios = $db->query($sql)->fetch()['total'] ?? 0;

/* ===================== */
/* STOCK BAJO */
/* ===================== */
$sql = "SELECT COUNT(*) as total 
        FROM productos   
        WHERE StockActual <= StockMinimo";
$stockBajo = $db->query($sql)->fetch()['total'] ?? 0;

/* ===================== */
/* PRODUCTOS POR VENCER */
/* ===================== */
$sql = "SELECT COUNT(*) as total
        FROM productos
        WHERE FechaVencimiento BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)";
$porVencer = $db->query($sql)->fetch()['total'] ?? 0;

/* ===================== */
/* MONTO REGISTRADO EN COMPRAS */
/* ===================== */
$sql = "SELECT SUM(Cantidad * Precio) as total FROM detallecompras";
$montoCompras = $db->query($sql)->fetch()['total'];

if ($montoCompras == null) {
    $montoCompras = 0;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<link rel="stylesheet" href="css/style.css?v=3">
</head>

<body>

<div class="sidebar">
    <h4>Farmacia SJ</h4>

    <a href="dashboard.php">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <a href="productos.php">
        <i class="bi bi-capsule"></i> Productos
    </a>

    <a href="carrito.php">
        <i class="bi bi-cart4"></i> Compras
    </a>

    <a href="movimientos.php">
        <i class="bi bi-arrow-left-right"></i> Movimientos
    </a>

    <?php if ($esAdmin): ?>
        <a href="usuarios.php">
            <i class="bi bi-people"></i> Usuarios
        </a>
    <?php endif; ?>

    <div style="position:absolute; bottom:20px; width:100%;">
        <a href="logout.php">
            <i class="bi bi-box-arrow-left"></i> Cerrar sesión
        </a>
    </div>
</div>

<div class="content">

    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap">
        <div>
            <h2 class="mb-1 fw-bold">
                <i class="bi bi-bar-chart-line-fill me-2"></i>Dashboard
            </h2>
            <small class="text-muted">
                Panel general de control del inventario
            </small>
        </div>

        <div class="text-end">
            <div class="fw-bold">
                <?= htmlspecialchars($usuario['Nombre'] ?? 'Usuario') ?>
            </div>
            <small class="text-muted">
                Rol: <?= htmlspecialchars($usuario['Rol'] ?? 'Sin rol') ?>
            </small>
        </div>
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

    <div class="row g-4">

        <div class="col-md-6 col-lg-3">
            <div class="card-box blue text-center dashboard-card">
                <div class="dashboard-icon mb-3">
                    <i class="bi bi-box-seam"></i>
                </div>
                <h5>Productos</h5>
                <h3><?= $totalProductos ?></h3>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card-box green text-center dashboard-card">
                <div class="dashboard-icon mb-3">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <h5>Monto en Compras</h5>
                <h3>$<?= number_format($montoCompras, 2) ?></h3>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card-box blue text-center dashboard-card">
                <div class="dashboard-icon mb-3">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <h5>Stock Bajo</h5>
                <h3><?= $stockBajo ?></h3>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card-box red text-center dashboard-card">
                <div class="dashboard-icon mb-3">
                    <i class="bi bi-clock-history"></i>
                </div>
                <h5>Por Vencer 30 días</h5>
                <h3><?= $porVencer ?></h3>
            </div>
        </div>

    </div>

    <?php if ($esAdmin): ?>
        <div class="row g-4 mt-1">
            <div class="col-md-6 col-lg-3">
                <div class="card-box blue text-center dashboard-card">
                    <div class="dashboard-icon mb-3">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h5>Usuarios</h5>
                    <h3><?= $totalUsuarios ?></h3>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>

</body>
</html>