<?php
require_once "../helpers/auth.php";
require_once "../config/database.php";
require_once "../app/models/user.php";

requiereAdmin();

$db = (new Database())->connect();
$model = new User($db);
$usuarios = $model->getAll();

$usuarioActual = usuarioActual();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Gestión de Usuarios</title>

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
        <h3 class="fw-bold">👤 Gestión de Usuarios</h3>

        <div class="text-end">
            <strong><?= htmlspecialchars($usuarioActual['Nombre'] ?? 'Administrador') ?></strong><br>
            <small class="text-muted">
                Rol: <?= htmlspecialchars($usuarioActual['Rol'] ?? 'admin') ?>
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

    <div class="d-flex justify-content-between mb-3">
        <h5>Listado de usuarios registrados</h5>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdd">
            <i class="bi bi-person-plus"></i> Nuevo Usuario
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover shadow">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['IdUsuario']) ?></td>
                    <td><?= htmlspecialchars($u['Nombre']) ?></td>
                    <td><?= htmlspecialchars($u['Correo']) ?></td>
                    <td>
                        <?php if (strtolower($u['Rol']) === 'admin'): ?>
                            <span class="badge bg-primary">Administrador</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Usuario</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($u['Estado'] == 1): ?>
                            <span class="badge bg-success">Activo</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Inactivo</span>
                        <?php endif; ?>
                    </td>

                    <td class="text-center">
                        <a href="edit_user.php?id=<?= urlencode($u['IdUsuario']) ?>" 
                           class="btn btn-warning btn-sm" 
                           title="Editar usuario">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <a href="toggle_user.php?id=<?= urlencode($u['IdUsuario']) ?>&estado=<?= urlencode($u['Estado']) ?>" 
                           class="btn btn-secondary btn-sm" 
                           title="Activar / Desactivar usuario">
                            <i class="bi bi-toggle-on"></i>
                        </a>

                        <a href="delete_user.php?id=<?= urlencode($u['IdUsuario']) ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('¿Está seguro de eliminar este usuario?');"
                           title="Eliminar usuario">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>

                <?php if (empty($usuarios)): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        No hay usuarios registrados.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<!-- MODAL CREAR -->
<div class="modal fade" id="modalAdd" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content p-3">

            <h4 class="mb-3">Nuevo Usuario</h4>

            <form action="save_user.php" method="POST">

                <div class="mb-2">
                    <label class="form-label">Nombre</label>
                    <input name="nombre" class="form-control" placeholder="Nombre completo" required>
                </div>

                <div class="mb-2">
                    <label class="form-label">Correo</label>
                    <input name="correo" type="email" class="form-control" placeholder="correo@ejemplo.com" required>
                </div>

                <div class="mb-2">
                    <label class="form-label">Contraseña</label>
                    <input name="password" type="password" class="form-control" placeholder="Contraseña" required minlength="6">
                </div>

                <div class="mb-3">
                    <label class="form-label">Rol</label>
                    <select name="rol" class="form-control" required>
                        <option value="usuario">Usuario</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>

                <button class="btn btn-success w-100">
                    <i class="bi bi-save"></i> Guardar
                </button>
            </form>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>