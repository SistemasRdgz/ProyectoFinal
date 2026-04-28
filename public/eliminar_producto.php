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
    $_SESSION['error'] = "El producto que intenta eliminar no existe.";
    header("Location: productos.php");
    exit;
}

$resultado = $model->eliminar($id);

if ($resultado) {
    $_SESSION['success'] = "Producto eliminado correctamente.";
} else {
    $_SESSION['error'] = "No se pudo eliminar el producto.";
}

header("Location: productos.php");
exit;