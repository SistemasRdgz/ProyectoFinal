<?php
require_once "../config/database.php";

$db = (new Database())->connect();

$id = $_GET['id'];

$stmt = $db->prepare("SELECT * FROM productos WHERE IdProducto = ?");
$stmt->execute([$id]);

$producto = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">

<body class="login-bg">

<form action="update_producto.php" method="POST">

    <input type="hidden" name="id" value="<?= $producto['IdProducto'] ?>">

    <input class="form-control mb-2" name="nombre" value="<?= $producto['Nombre'] ?>">
    <input class="form-control mb-2" name="precio" value="<?= $producto['Precio'] ?>">
    <input class="form-control mb-2" name="stock" value="<?= $producto['StockActual'] ?>">

    <button class="btn btn-primary w-100">Actualizar</button>

</form>

</body>