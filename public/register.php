<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registro</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
</head>

<body class="login-bg">

<div class="login-card">
    <h3>Registrarse</h3>
    <p class="text-muted">
        Registro básico de usuario del sistema.
    </p>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?php
                if ($_GET['error'] === 'campos') {
                    echo "Todos los campos son obligatorios.";
                } elseif ($_GET['error'] === 'correo') {
                    echo "El correo electrónico no tiene un formato válido.";
                } elseif ($_GET['error'] === 'password') {
                    echo "La contraseña debe tener al menos 6 caracteres.";
                } elseif ($_GET['error'] === 'duplicado') {
                    echo "Ya existe un usuario registrado con ese correo.";
                } else {
                    echo "No se pudo completar el registro.";
                }
            ?>
        </div>
    <?php endif; ?>

    <form action="save_register.php" method="POST">
        <input 
            name="nombre" 
            class="form-control mb-2" 
            placeholder="Nombre" 
            required
        >

        <input 
            name="correo" 
            type="email"
            class="form-control mb-2" 
            placeholder="Correo" 
            required
        >

        <input 
            name="password" 
            type="password" 
            class="form-control mb-2" 
            placeholder="Contraseña" 
            required
            minlength="6"
        >

        <button class="btn btn-success w-100">
            Registrarse
        </button>
    </form>

    <a href="index.php" class="btn btn-outline-light w-100 mt-2">
        Volver al login
    </a>
</div>

</body>
</html>