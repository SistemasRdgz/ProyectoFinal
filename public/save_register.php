<?php
require_once "../config/database.php";
require_once "../app/models/User.php";

$db = (new Database())->connect();
$model = new User($db);

$model->crear(
    $_POST['nombre'],
    $_POST['correo'],
    $_POST['password'],
    "usuario" // SIEMPRE USUARIO
);

header("Location: index.php");