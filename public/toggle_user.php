<?php
require_once "../helpers/auth.php";
require_once "../config/database.php";
require_once "../app/models/user.php";

requiereAdmin();

if (!isset($_GET['id']) || !isset($_GET['estado'])) {
    $_SESSION['error'] = "Solicitud inválida para cambiar el estado del usuario.";
    header("Location: usuarios.php");
    exit;
}

$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
$estadoActual = filter_var($_GET['estado'], FILTER_VALIDATE_INT);

if (!$id || ($estadoActual !== 0 && $estadoActual !== 1)) {
    $_SESSION['error'] = "Datos inválidos para cambiar el estado del usuario.";
    header("Location: usuarios.php");
    exit;
}

// Evitar que el administrador se desactive a sí mismo
if (isset($_SESSION['user']['IdUsuario']) && $_SESSION['user']['IdUsuario'] == $id) {
    $_SESSION['error'] = "No puede desactivar su propio usuario mientras tiene la sesión iniciada.";
    header("Location: usuarios.php");
    exit;
}

$db = (new Database())->connect();
$model = new User($db);

$nuevoEstado = $estadoActual == 1 ? 0 : 1;

$resultado = $model->toggleEstado($id, $nuevoEstado);

if ($resultado) {
    $_SESSION['success'] = $nuevoEstado == 1 
        ? "Usuario activado correctamente." 
        : "Usuario desactivado correctamente.";
} else {
    $_SESSION['error'] = "No se pudo cambiar el estado del usuario.";
}

header("Location: usuarios.php");
exit;