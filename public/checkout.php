<?php
session_start();
require_once "../config/database.php";

$db = (new Database())->connect();

/* ===================== */
/* VALIDAR CARRITO */
/* ===================== */
if(!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])){
    header("Location: carrito.php");
    exit;
}

$idUsuario = $_SESSION['user']['IdUsuario'];
$carrito = $_SESSION['carrito'];

/* ===================== */
/* 1. CALCULAR TOTAL */
/* ===================== */
$total = 0;

foreach($carrito as $idProducto){

    $sql = "SELECT Precio FROM Productos WHERE IdProducto = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$idProducto]);
    $p = $stmt->fetch();

    $total += $p['Precio'];
}

/* ===================== */
/* 2. CREAR COMPRA */
/* ===================== */
$sql = "INSERT INTO Compras (IdProveedor, Fecha, Total)
        VALUES (1, NOW(), ?)";

$stmt = $db->prepare($sql);
$stmt->execute([$total]);

$idCompra = $db->lastInsertId();

/* ===================== */
/* 3. DETALLES DE COMPRA */
/* ===================== */
foreach($carrito as $idProducto){

    $sql = "SELECT Precio FROM Productos WHERE IdProducto = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$idProducto]);
    $p = $stmt->fetch();

    $sql = "INSERT INTO DetalleCompras
            (IdCompra, IdProducto, Cantidad, Precio)
            VALUES (?, ?, ?, ?)";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        $idCompra,
        $idProducto,
        1,              // cantidad fija por ahora
        $p['Precio']
    ]);
}

/* ===================== */
/* 4. LIMPIAR CARRITO */
/* ===================== */
unset($_SESSION['carrito']);

/* ===================== */
/* 5. REDIRECCIONAR */
/* ===================== */
header("Location: carrito.php?success=1");
exit;
?>