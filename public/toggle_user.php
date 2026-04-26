<?php
require_once "../config/database.php";
require_once "../app/models/User.php";

$db = (new Database())->connect();
$model = new User($db);

$nuevoEstado = $_GET['estado'] ? 0 : 1;

$model->toggleEstado($_GET['id'], $nuevoEstado);

header("Location: usuarios.php");