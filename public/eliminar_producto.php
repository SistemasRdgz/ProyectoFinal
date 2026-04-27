<?php
require_once "../config/database.php";

$db = (new Database())->connect();

$id = $_POST['id'] ?? $_GET['id'] ?? null;

if (!$id) {
    die("ID no recibido");
}

$sql = "DELETE FROM productos WHERE IdProducto = ?";
$stmt = $db->prepare($sql);

$stmt->execute([$id]);

header("Location: productos.php");
exit;