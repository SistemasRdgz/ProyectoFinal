<?php
require_once "../config/database.php";
require_once "../app/models/user.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($nombre === '' || $correo === '' || $password === '') {
    header("Location: register.php?error=campos");
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header("Location: register.php?error=correo");
    exit;
}

if (strlen($password) < 6) {
    header("Location: register.php?error=password");
    exit;
}

$db = (new Database())->connect();
$model = new User($db);

if ($model->correoExiste($correo)) {
    header("Location: register.php?error=duplicado");
    exit;
}

$model->crear(
    $nombre,
    $correo,
    $password,
    "usuario"
);

header("Location: index.php?registro=ok");
exit;