<?php 
require_once "../helpers/auth.php";
require_once "../config/database.php";
require_once "../app/models/User.php";

if($_SESSION['user']['Rol'] != 'admin'){
    exit("Acceso denegado");
}

$db = (new Database())->connect();
$model = new User($db);
$usuarios = $model->getAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Usuarios</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="css/styles.css">
</head>

<body>

<div class="sidebar">
    <h4>Farmacia</h4>
    <a href="dashboard.php">Dashboard</a>
    <a href="productos.php">Productos</a>
    <a href="usuarios.php">Usuarios</a>
</div>

<div class="content">

    <div class="d-flex justify-content-between mb-3">
        <h3>👤 Gestión de Usuarios</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdd">
            <i class="bi bi-person-plus"></i> Nuevo
        </button>
    </div>

    <table class="table table-bordered shadow">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($usuarios as $u): ?>
            <tr>
                <td><?= $u['IdUsuario'] ?></td>
                <td><?= $u['Nombre'] ?></td>
                <td><?= $u['Correo'] ?></td>
                <td><?= $u['Rol'] ?></td>
                <td>
                    <?php if($u['Estado']): ?>
                        <span class="badge bg-success">Activo</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Inactivo</span>
                    <?php endif; ?>
                </td>

                <td>
                    <a href="edit_user.php?id=<?= $u['IdUsuario'] ?>" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i>
                    </a>

                    <a href="delete_user.php?id=<?= $u['IdUsuario'] ?>" class="btn btn-danger btn-sm">
                        <i class="bi bi-trash"></i>
                    </a>

                    <a href="toggle_user.php?id=<?= $u['IdUsuario'] ?>&estado=<?= $u['Estado'] ?>" class="btn btn-secondary btn-sm">
                        <i class="bi bi-toggle-on"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

<!-- MODAL CREAR -->
<div class="modal fade" id="modalAdd">
<div class="modal-dialog">
<div class="modal-content p-3">

    <h4>Nuevo Usuario</h4>

    <form action="save_user.php" method="POST">
        <input name="nombre" class="form-control mb-2" placeholder="Nombre">
        <input name="correo" class="form-control mb-2" placeholder="Correo">
        <input name="password" type="password" class="form-control mb-2" placeholder="Contraseña">

        <select name="rol" class="form-control mb-2">
            <option value="usuario">Usuario</option>
            <option value="admin">Administrador</option>
        </select>

        <button class="btn btn-success w-100">Guardar</button>
    </form>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>