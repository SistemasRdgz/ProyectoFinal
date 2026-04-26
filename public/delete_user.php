<?php
require_once "../config/database.php";
require_once "../app/models/User.php";

$db = (new Database())->connect();
$model = new User($db);

$model->eliminar($_GET['id']);

header("Location: usuarios.php");