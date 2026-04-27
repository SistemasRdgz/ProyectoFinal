<!DOCTYPE html>
<html>
<head>
<title>Registro</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
</head>

<body class="login-bg">

<div class="login-card">
    <h3>Registrarse</h3>

    <form action="save_register.php" method="POST">
        <input name="nombre" class="form-control mb-2" placeholder="Nombre" required>
        <input name="correo" class="form-control mb-2" placeholder="Correo" required>
        <input name="password" type="password" class="form-control mb-2" placeholder="Contraseña" required>

        <button class="btn btn-success w-100">Registrarse</button>
    </form>
        <a href="index.php" class="btn btn-outline-green w-100 mt-2">
    Volver al login
</a>
</div>

</body>
</html>