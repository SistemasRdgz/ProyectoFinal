<?php
require_once "../config/database.php";
require_once "../app/models/Producto.php";

$db = (new Database())->connect();
$model = new Producto($db);

$model->crear($_POST['nombre'], $_POST['precio'], $_POST['stock']);

header("Location: productos.php");