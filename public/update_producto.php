<?php
require_once "../config/database.php";

$db = (new Database())->connect();

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$precio = $_POST['precio'];
$stock = $_POST['stock'];

$sql = "UPDATE productos 
        SET Nombre = ?, Precio = ?, StockActual = ? 
        WHERE IdProducto = ?";

$stmt = $db->prepare($sql);
$stmt->execute([$nombre, $precio, $stock, $id]);

header("Location: productos.php");
exit;