<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<link rel="stylesheet" href="css/style.css">
</head>

<body class="login-bg">

<div class="login-card">

    <div class="logo">
        <i class="bi bi-capsule-pill"></i>
    </div>

    <div class="title">Farmacia San José</div>
    <div class="subtitle">Control de Inventario</div>

    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-danger">Credenciales incorrectas</div>
    <?php endif; ?>

    <form action="../app/controllers/AuthController.php" method="POST">

        <div class="mb-3 text-start">
            <label>Correo</label>
            <input type="email" name="correo" class="form-control" required>
        </div>

        <div class="mb-3 text-start">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button class="btn btn-login w-100">
            <i class="bi bi-box-arrow-in-right"></i> Ingresar
        </button>

    </form>

</div>

</body>
</html>