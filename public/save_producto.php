<?php
require_once "../helpers/auth.php";
require_once "../config/database.php";
require_once "../app/models/producto.php";

requiereAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Acceso no permitido.";
    header("Location: productos.php");
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$precio = $_POST['precio'] ?? '';
$fechaVencimiento = trim($_POST['fecha_vencimiento'] ?? '');
$stockActual = $_POST['stock_actual'] ?? '';
$stockMinimo = $_POST['stock_minimo'] ?? '';
$idCategoria = $_POST['id_categoria'] ?? null;
$idProveedor = $_POST['id_proveedor'] ?? null;

if ($nombre === '' || $precio === '' || $fechaVencimiento === '' || $stockActual === '' || $stockMinimo === '') {
    $_SESSION['error'] = "Los campos nombre, precio, fecha de vencimiento, stock actual y stock mínimo son obligatorios.";
    header("Location: productos.php");
    exit;
}

if (!is_numeric($precio) || $precio <= 0) {
    $_SESSION['error'] = "El precio debe ser un número mayor que cero.";
    header("Location: productos.php");
    exit;
}

if (!filter_var($stockActual, FILTER_VALIDATE_INT) && $stockActual !== "0") {
    $_SESSION['error'] = "El stock actual debe ser un número entero.";
    header("Location: productos.php");
    exit;
}

if (!filter_var($stockMinimo, FILTER_VALIDATE_INT) && $stockMinimo !== "0") {
    $_SESSION['error'] = "El stock mínimo debe ser un número entero.";
    header("Location: productos.php");
    exit;
}

if ((int)$stockActual < 0 || (int)$stockMinimo < 0) {
    $_SESSION['error'] = "El stock actual y el stock mínimo no pueden ser negativos.";
    header("Location: productos.php");
    exit;
}

$fechaActual = date("Y-m-d");

if ($fechaVencimiento < $fechaActual) {
    $_SESSION['error'] = "La fecha de vencimiento no puede ser anterior a la fecha actual.";
    header("Location: productos.php");
    exit;
}

$idCategoria = $idCategoria !== '' ? $idCategoria : null;
$idProveedor = $idProveedor !== '' ? $idProveedor : null;

$db = (new Database())->connect();
$model = new Producto($db);

$resultado = $model->crear(
    $nombre,
    $descripcion,
    $precio,
    $fechaVencimiento,
    (int)$stockActual,
    (int)$stockMinimo,
    $idCategoria,
    $idProveedor
);

if ($resultado) {
    $_SESSION['success'] = "Producto registrado correctamente.";
} else {
    $_SESSION['error'] = "No se pudo registrar el producto. Intente nuevamente.";
}

header("Location: productos.php");
exit;