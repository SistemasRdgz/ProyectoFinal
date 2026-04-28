<?php
require_once "../helpers/auth.php";
require_once "../config/database.php";
require_once "../app/models/user.php";

requiereAdmin();

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Solicitud inválida para eliminar el usuario.";
    header("Location: usuarios.php");
    exit;
}

$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

if (!$id) {
    $_SESSION['error'] = "El usuario seleccionado no es válido.";
    header("Location: usuarios.php");
    exit;
}

// Evitar que el administrador elimine su propia cuenta
if (isset($_SESSION['user']['IdUsuario']) && $_SESSION['user']['IdUsuario'] == $id) {
    $_SESSION['error'] = "No puede eliminar su propio usuario mientras tiene la sesión iniciada.";
    header("Location: usuarios.php");
    exit;
}

$db = (new Database())->connect();
$model = new User($db);

$resultado = $model->eliminar($id);

if ($resultado) {
    $_SESSION['success'] = "Usuario eliminado correctamente.";
} else {
    $_SESSION['error'] = "No se pudo eliminar el usuario.";
}

header("Location: usuarios.php");
exit;