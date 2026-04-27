<?php
session_start();

if (isset($_SESSION['carrito'])) {
    unset($_SESSION['carrito']);
}

// o alternativa:
// $_SESSION['carrito'] = [];

header("Location: carrito.php");
exit;