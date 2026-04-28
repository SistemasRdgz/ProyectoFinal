<?php
require_once "../helpers/auth.php";
require_once "../config/database.php";
require_once "../app/models/movimiento.php";

$db = (new Database())->connect();
$model = new Movimiento($db);

$usuario = usuarioActual();
$esAdmin = esAdmin();

$productos = $db->query("SELECT IdProducto, Nombre, StockActual FROM Productos ORDER BY Nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
$movimientos = $model->getAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Movimientos de Inventario</title>

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
            <h3 class="fw-bold">🔄 Movimientos de Inventario</h3>
            <small class="text-muted">
                Usuario: <?= htmlspecialchars($usuario['Nombre'] ?? 'Usuario') ?> |
                Rol: <?= htmlspecialchars($usuario['Rol'] ?? '') ?>
            </small>
        </div>

        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalMovimiento">
            <i class="bi bi-plus-circle"></i> Nuevo Movimiento
        </button>
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

    <div class="card shadow">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Usuario</th>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                        <th>Fecha</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($movimientos as $m): ?>
                    <tr>
                        <td><?= htmlspecialchars($m['IdMovimiento']) ?></td>
                        <td><?= htmlspecialchars($m['Producto']) ?></td>
                        <td><?= htmlspecialchars($m['Usuario']) ?></td>
                        <td>
                            <?php if ($m['TipoMovimiento'] === 'ENTRADA'): ?>
                                <span class="badge bg-success">ENTRADA</span>
                            <?php else: ?>
                                <span class="badge bg-danger">SALIDA</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($m['Cantidad']) ?></td>
                        <td><?= htmlspecialchars($m['Fecha']) ?></td>
                    </tr>
                    <?php endforeach; ?>

                    <?php if (empty($movimientos)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            No hay movimientos registrados.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- MODAL NUEVO MOVIMIENTO -->
<div class="modal fade" id="modalMovimiento" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content p-3">

            <h4 class="mb-3">Nuevo Movimiento</h4>

            <form action="save_movimiento.php" method="POST">

                <div class="mb-3">
                    <label class="form-label">Producto</label>
                    <select name="id_producto" class="form-control" required>
                        <option value="">Seleccione un producto</option>

                        <?php foreach ($productos as $p): ?>
                            <option value="<?= htmlspecialchars($p['IdProducto']) ?>">
                                <?= htmlspecialchars($p['Nombre']) ?> — Stock: <?= htmlspecialchars($p['StockActual']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipo de movimiento</label>
                    <select name="tipo" class="form-control" required>
                        <option value="">Seleccione</option>
                        <option value="ENTRADA">Entrada</option>
                        <option value="SALIDA">Salida</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Cantidad</label>
                    <input 
                        type="number" 
                        name="cantidad" 
                        min="1" 
                        class="form-control" 
                        placeholder="Cantidad" 
                        required
                    >
                </div>

                <button class="btn btn-success w-100">
                    <i class="bi bi-save"></i> Guardar Movimiento
                </button>

            </form>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>