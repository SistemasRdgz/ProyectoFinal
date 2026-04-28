<?php
require_once "../helpers/auth.php";
require_once "../config/database.php";
require_once "../app/models/user.php";

requiereAdmin();

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Solicitud inválida para editar el usuario.";
    header("Location: usuarios.php");
    exit;
}

$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

if (!$id) {
    $_SESSION['error'] = "El usuario seleccionado no es válido.";
    header("Location: usuarios.php");
    exit;
}

$db = (new Database())->connect();
$model = new User($db);

$usuario = $model->getById($id);

if (!$usuario) {
    $_SESSION['error'] = "El usuario no existe.";
    header("Location: usuarios.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Usuario</title>

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
        <h3 class="fw-bold">✏️ Editar Usuario</h3>
        <a href="usuarios.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card shadow p-4">
        <form action="update_user.php" method="POST">

            <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['IdUsuario']) ?>">

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input 
                    type="text" 
                    name="nombre" 
                    class="form-control" 
                    value="<?= htmlspecialchars($usuario['Nombre']) ?>" 
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Correo</label>
                <input 
                    type="email" 
                    name="correo" 
                    class="form-control" 
                    value="<?= htmlspecialchars($usuario['Correo']) ?>" 
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Rol</label>
                <select name="rol" class="form-control" required>
                    <option value="usuario" <?= $usuario['Rol'] === 'usuario' ? 'selected' : '' ?>>
                        Usuario
                    </option>
                    <option value="admin" <?= $usuario['Rol'] === 'admin' ? 'selected' : '' ?>>
                        Administrador
                    </option>
                </select>
            </div>

            <button class="btn btn-success">
                <i class="bi bi-save"></i> Guardar Cambios
            </button>

        </form>
    </div>

</div>

</body>
</html>