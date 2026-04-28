<?php
require_once "../helpers/auth.php";
require_once "../config/database.php";
require_once "../app/models/producto.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Acceso no permitido.";
    header("Location: productos.php");
    exit;
}

$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);

if (!$id) {
    $_SESSION['error'] = "El producto seleccionado no es válido.";
    header("Location: productos.php");
    exit;
}

$db = (new Database())->connect();
$model = new Producto($db);

$producto = $model->getById($id);

if (!$producto) {
    $_SESSION['error'] = "El producto no existe.";
    header("Location: productos.php");
    exit;
}

if ((int)$producto['StockActual'] <= 0) {
    $_SESSION['error'] = "No se puede agregar el producto porque no tiene stock disponible.";
    header("Location: productos.php");
    exit;
}

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

if (!isset($_SESSION['carrito'][$id])) {
    $_SESSION['carrito'][$id] = [
        'id' => $id,
        'cantidad' => 1
    ];

    $_SESSION['success'] = "Producto agregado al carrito.";
} else {
    $cantidadActual = (int)$_SESSION['carrito'][$id]['cantidad'];

    if ($cantidadActual + 1 > (int)$producto['StockActual']) {
        $_SESSION['error'] = "No puede agregar más unidades que el stock disponible.";
        header("Location: productos.php");
        exit;
    }

    $_SESSION['carrito'][$id]['cantidad']++;
    $_SESSION['success'] = "Cantidad del producto actualizada en el carrito.";
}

header("Location: productos.php");
exit;