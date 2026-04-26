<?php require_once "../helpers/auth.php"; ?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<link rel="stylesheet" href="css/styles.css">
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

    <h3 class="mb-4">
        Bienvenido, <?php echo $_SESSION['user']['Nombre']; ?>
    </h3>

    <div class="row">

        <div class="col-md-4">
            <div class="card-box blue shadow">
                <h5>Productos Activos</h5>
                <h2>120</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box red shadow">
                <h5>Caducan &lt; 30 días</h5>
                <h2>8</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box green shadow">
                <h5>Total Movimientos</h5>
                <h2>56</h2>
            </div>
        </div>

    </div>

</div>

</body>
</html>