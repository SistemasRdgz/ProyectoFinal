<?php
require_once "../helpers/auth.php";
require_once "../config/database.php";
require_once "../app/models/movimiento.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Acceso no permitido.";
    header("Location: movimientos.php");
    exit;
}

$idProducto = filter_var($_POST['id_producto'] ?? null, FILTER_VALIDATE_INT);
$tipo = trim($_POST['tipo'] ?? '');
$cantidad = filter_var($_POST['cantidad'] ?? null, FILTER_VALIDATE_INT);
$idUsuario = $_SESSION['user']['IdUsuario'] ?? null;

if (!$idProducto || !$cantidad || !$idUsuario || $tipo === '') {
    $_SESSION['error'] = "Todos los campos son obligatorios.";
    header("Location: movimientos.php");
    exit;
}

if (!in_array($tipo, ['ENTRADA', 'SALIDA'])) {
    $_SESSION['error'] = "El tipo de movimiento no es válido.";
    header("Location: movimientos.php");
    exit;
}

if ($cantidad <= 0) {
    $_SESSION['error'] = "La cantidad debe ser mayor que cero.";
    header("Location: movimientos.php");
    exit;
}

$db = (new Database())->connect();
$model = new Movimiento($db);

try {
    $model->registrarConStock($idProducto, $idUsuario, $tipo, $cantidad);

    $_SESSION['success'] = "Movimiento registrado correctamente. El stock fue actualizado.";
    header("Location: movimientos.php");
    exit;

} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: movimientos.php");
    exit;
}