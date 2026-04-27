<?php
session_start();

$id = $_POST['id'];

if(!isset($_SESSION['carrito'])){
    $_SESSION['carrito'] = [];
}

$_SESSION['carrito'][] = $id;

// header("Location: productos.php");
header("Location: productos.php");
exit();