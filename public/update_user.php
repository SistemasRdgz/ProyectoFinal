<?php
require_once "../helpers/auth.php";
require_once "../config/database.php";
require_once "../app/models/user.php";

requiereAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Acceso no permitido.";
    header("Location: usuarios.php");
    exit;
}

$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
$nombre = trim($_POST['nombre'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$rol = trim($_POST['rol'] ?? '');

if (!$id || $nombre === '' || $correo === '' || $rol === '') {
    $_SESSION['error'] = "Todos los campos son obligatorios.";
    header("Location: usuarios.php");
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "El correo electrónico no tiene un formato válido.";
    header("Location: edit_user.php?id=" . urlencode($id));
    exit;
}

$rolesPermitidos = ['admin', 'usuario'];

if (!in_array($rol, $rolesPermitidos)) {
    $_SESSION['error'] = "El rol seleccionado no es válido.";
    header("Location: edit_user.php?id=" . urlencode($id));
    exit;
}

$db = (new Database())->connect();
$model = new User($db);

$usuario = $model->getById($id);

if (!$usuario) {
    $_SESSION['error'] = "El usuario que intenta actualizar no existe.";
    header("Location: usuarios.php");
    exit;
}

if ($model->correoExiste($correo, $id)) {
    $_SESSION['error'] = "Ya existe otro usuario registrado con ese correo.";
    header("Location: edit_user.php?id=" . urlencode($id));
    exit;
}

$resultado = $model->actualizar($id, $nombre, $correo, $rol);

if ($resultado) {
    $_SESSION['success'] = "Usuario actualizado correctamente.";

    if (isset($_SESSION['user']['IdUsuario']) && $_SESSION['user']['IdUsuario'] == $id) {
        $_SESSION['user']['Nombre'] = $nombre;
        $_SESSION['user']['Correo'] = $correo;
        $_SESSION['user']['Rol'] = $rol;
    }

} else {
    $_SESSION['error'] = "No se pudo actualizar el usuario.";
}

header("Location: usuarios.php");
exit;