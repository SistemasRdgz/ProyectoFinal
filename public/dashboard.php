<?php require_once "../helpers/auth.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand">Sistema Inventario</span>
        <a href="logout.php" class="btn btn-danger">Salir</a>
    </div>
</nav>

<div class="container mt-4">

    <h3>Bienvenido, <?php echo $_SESSION['user']['Nombre']; ?> 👋</h3>

    <div class="row mt-4">

        <div class="col-md-4">
            <div class="card p-3 shadow">
                <h5>📦 Productos</h5>
                <a href="productos.php" class="btn btn-primary">Gestionar</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 shadow">
                <h5>🛒 Compras</h5>
                <a href="compras.php" class="btn btn-success">Gestionar</a>
            </div>
        </div>

        <?php if($_SESSION['user']['Rol'] == 'admin'): ?>
        <div class="col-md-4">
            <div class="card p-3 shadow">
                <h5>👤 Usuarios</h5>
                <a href="usuarios.php" class="btn btn-warning">Administrar</a>
            </div>
        </div>
        <?php endif; ?>

    </div>

</div>

</body>
</html>